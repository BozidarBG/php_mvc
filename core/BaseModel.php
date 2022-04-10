<?php


namespace App\core;

use App\core\database\Query;
use App\app\Middleware;


class BaseModel extends Query
{
    use RelationsTrait;

    protected $table;
    protected $all_table_fields=[];
    protected $fillable=[]; //allowed to be populated by user inputs
    public $rules=[];
    public $per_page=10;
    public $arrayOfValuesFilteredWithFillable=[];

    public function __construct()
    {
        parent::__construct();
        $this->checkPostRequest();
    }
    public function update(string $column_name, string|int $column_value, array $additional_data=[]){
        $new_data_arr=array_merge($this->arrayOfValuesFilteredWithFillable, $additional_data);
        $result=$this->updateRow($column_name, $column_value, $new_data_arr)->executeQuery();

        //ako je result ok, vratiti
        return (bool) $result;
    }

    protected function checkPostRequest(){
        if(Request::method()==='POST'){
            Middleware::csrf();
            $this->fillArrayOfValuesFilteredWithFillable();
        }
    }

    protected function fillArrayOfValuesFilteredWithFillable(){
        $data_arr = Request::getParams();
        foreach($data_arr as $key=>$value){
            if(in_array($key, $this->fillable)){
                $this->arrayOfValuesFilteredWithFillable[$key]=$value;
            }
        }
    }

    //returns array. request+additional data + id=>lastInsertedId
    public function save(array $additional_data){
        $new_data_arr=array_merge($this->arrayOfValuesFilteredWithFillable, $additional_data);
        //return $this->create($new_data_arr)->executeQuery();
        $lastInsertedId=$this->createRow($new_data_arr)->executeQuery()->lastInsertedId();
        return array_merge($new_data_arr, ['id'=>$lastInsertedId]);
    }


    public function deleteWhere($column, $value){
        return $this->deleteRow($column, $value)->executeQuery()->getAffectedRows();
    }



    public function flatten(array $arr, string $key){
        $result=[];
        foreach($arr as $el){
            $result[]=$el->$key;
        }
        return $result;
    }
}
