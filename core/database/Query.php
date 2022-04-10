<?php

namespace App\core\database;
use App\Core\Log;
use PDO;
class Query extends DBConnection
{
    public $queryString="";
    protected $table;
    public $operators=['=', '>=', '>', '<=', '<', '<>', '!='];
    public $placeholders=[];// ["id = ?", "email = ?"]
    public $bindings=[]; //['5', 'korisnik1@gmail.com']
    public $resultArray;
    public $affectedRows;

    protected $model;
    const PLACEHOLDER="?";


    public function select(array $columns){
        if(count($columns)){
            $columns=implode(', ', $columns);
        }else{
            $columns="*";
        }

        $this->queryString=sprintf("SELECT %s FROM %s", $columns, $this->table);
        //var_dump($this->queryString);
        return $this;
    }
    public function count(){
        $this->queryString=sprintf("SELECT count('id') AS total_records FROM %s ", $this->table);
        return $this;
    }

    public function updateRow(string $column_name, string|int $column_value, array $data_array){
        //vardump($data_array);
        $placeholders=[];
        foreach($data_array as $key=>$value){
            $placeholders[]="{$key}=?";
            $this->bindings[]=$value;
        }
        //vardump($placeholders);
        $condition="{$column_name}=?";
        $this->bindings[]=$column_value;
        //set $key=?, $key2=?, WHERE
        $this->queryString=sprintf("UPDATE `%s` SET %s WHERE %s",
            $this->table, implode(', ', $placeholders), $condition);
        return $this;
    }

    public function createRow($data_array){
        $columns=implode(', ', array_keys($data_array));
        $placeholders=[];
        foreach($data_array as $key=>$value){
            //$placeholders[]=self::PLACEHOLDER;
            $placeholders[]='?';
            $this->bindings[]=$value;
        }

        $this->queryString=sprintf("INSERT INTO `%s` (%s) VALUES (%s)",$this->table, $columns, implode(', ', $placeholders));
        return $this;
    }

    public function deleteRow($column, $value){
        $this->bindings[]=$value;
        $this->queryString=sprintf("DELETE FROM `%s` WHERE %s=?",$this->table, $column);
        //$this->queryString .=$this->where(['id', '1']);
        return $this;
    }

    public function getAffectedRows(){
        return $this->affectedRows;
    }

    public function executeQuery(){
        try{
            $sql=$this->queryString;

            if(getAppValue('environment')==="test"){
                echo "<br>";
                var_dump($sql);
                echo "<br>";
            }
    
            $statement=$this->connection->prepare($sql);
            $statement->execute($this->bindings);

            $this->affectedRows=$statement->rowCount();
            
            $this->resultArray=$statement->fetchAll(PDO::FETCH_OBJ);
            $this->placeholders=[];
            $this->bindings=[];
            return $this;
        }catch(\PDOException $e){
            //vardump($e->getMessage()); //string(104) "SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'harden@gmail.com' for key 'email'"
            logmsg($e->getMessage());
            return $this;
        }
        
    }

    public function lastInsertedId()
    {
        return $this->connection->lastInsertId();
    }

    /*
    protected function withoutValuesFromHiddenColumns(){
        $vars=get_class_vars($this->model);

        if(isset($vars['hidden_columns']) && count($vars['hidden_columns'])){
            $hiddenColumnsArray=$vars['hidden_columns'];
            foreach ($this->resultArray as $obj){
                foreach($hiddenColumnsArray as $unwantedProp){
                    unset($obj->{$unwantedProp});
                }
            }
            //vardump($this->resultArray);
        }
        return $this;
    }
    */

    public function all(){
        $this->executeQuery();
        return $this->resultArray;
    }

    public function get($arg){
        $this->executeQuery();
        if(is_array($arg)){
            $result=[];

            foreach($this->resultArray as $obj){
                $subObj=new StdClass;
                foreach($arg as $column){
                    $subObj->$column=property_exists($obj, $column) ? $obj->$column : null;
                }
                $result[]=$subObj;
            }

            return $result;
        }elseif(strtolower($arg)==='last'){
            return $this->resultArray[(count($this->resultArray))-1] ?? null;
        }elseif(strtolower($arg)==='first'){
            return $this->resultArray[0]  ?? null;
        }elseif(strtolower($arg)==='count'){
            return count($this->resultArray) ?? null;
        }

        else{
            return $this->resultArray ?? null;
        }

    }


    /*
    where([ 1 2 3]) OR where([123], [234], [345]...)
    must have arguments
    every argument must be array
    every argument (condition) must have 2 or 3 members
    foreach $conditions as $condition
        every condition is put in arrayOfParsedConditions
    we form a string at the end of loop
    */

    public function where(){
        $numberOfArgs=func_num_args();
        if($numberOfArgs===0){
            throw new \Exception('There are no arguments in where method');
        }
        $conditions=func_get_args(); //->where(['id', ">", "1"], ['id', "<", 10])
        $arrOfParsedWhereConditions=[];
        foreach($conditions as $condition){
            if(!is_array($condition)){
                throw new \Exception('Arguments passed to where must be in array format.');
            }
            //number of elements in each condition must be 2 or 3
            $numberOfElements=count($condition);
            if($numberOfElements<2 || $numberOfElements>3){
                //var_dump(count($condition));
                throw new \Exception('Each array passed to where, must have 2 or 3 elements. You passed '.implode(', ',$condition));
            }
            if($numberOfElements===2){
                //we have like 'id', 5
                $value = $condition[1];
                $operator = "=";
            }else{
                //we have like 'id', '>', 5
                $value = $condition[2];
                $operator = $condition[1];
                if(!in_array($operator, $this->operators)){
                    throw new \Exception('Operator is not valid. You entered "'.$operator.'". It needs to be in array of '.implode(',  ', $this->operators));
                }
            }
            $column=$condition[0];
            //if we have more than one array member, we need AND keyword in sql query, else we dont need AND
            $arrOfParsedWhereConditions[]=$this->parseWhereCondition($column, $operator, $value);

        }//end foreach $conditions as $condition

        $this->queryString .=" WHERE ".implode(' AND ', $arrOfParsedWhereConditions);
        return $this;
    }
    private function parseWhereCondition($column, $operator, $value){
        $this->bindings[] = $value;
        return sprintf('%s %s %s', $column, $operator, "?");//returns id = ? / date > ?
    }

    //WHERE user_id IN (5, 9, 11)
    public function whereIn(string $column, array $values){
        $this->queryString .=sprintf(" WHERE %s IN (%s)", $column, implode(', ', $values));
        return $this;
    }

    public function groupBy(string $column){
        $this->queryString .=sprintf(" GROUP BY %s ", $column);
        return $this;
    }

    public function orderBy(string $column, string $direction="ASC"){
        $direction=$direction==="DESC" ? "DESC" : "ASC";
        $this->queryString .=sprintf(" ORDER BY %s %s ", $column, $direction);
        return $this;
    }

    public function whereLike(string $column, string $search){
        if(strlen(trim($search))){
            $cleanedSearch="'%".addslashes(trim($search))."%'";
            $this->queryString .=sprintf(" WHERE %s LIKE %s ", $column, $cleanedSearch);
        }
        return $this;
    }

    public function limit(int $size){
        $this->queryString .=sprintf(" LIMIT %s ", $size);
        return $this;
    }

    public function offset(int $start){
        $this->queryString .=sprintf(" OFFSET %s ", $start);
        return $this;
    }
}