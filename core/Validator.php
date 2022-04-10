<?php


namespace App\Core;

use App\core\BaseModel;
class Validator //extends BaseModel
{

    protected $errorsArray=[];

    protected $errorMessages=[
        'required'=> ':input_name: is required.',
        'minLen'=>':input_name: must be at least :required_value: characters long.',
        'maxLen'=>':input_name: must be maximum :required_value: characters long.',
        'email'=>':input_name: is not in correct form (example bob@gmail.com).',
        //'unique'=>':input_name: already exists. You must select different :input_name:',
        'types'=>':input_name: must be of type :required_value:',
        'string'=>':input_name: must be of type string.',
        'same_as'=>':input_name: and :required_value: do not match.'
    ];


    public function __construct($request, $rules)
    {

        if(empty($request) || empty($rules)){
            throw new \Exception('Request or rules is empty');
        }
        //vardump($rules);
       $this->validate($request,  $rules);
       // printr($this->errorsArray);
    }


    //does validation. if something is wrong, put name of the field and error(s) in $errorsArray
    protected function validateold($request,  $rules){
        $errors=[];
        //za svaki npr name=bole, email=bole@gmai.com
        foreach($request as $input_name=>$value_in_input){
            //$errorRow[$input_name];//'input_name=>['input name je required', 'minimalna dužina je 4']
            //key je ime kolone u bazi/input polja/u model::rules je ključ
            $error_messages_array=[];
            if(array_key_exists($input_name, $rules)){
                //imamo rule za ovaj input pa validiramo
                //za svaki red iz niza $rules npr  jedan red je ['required'=>true, 'string'=>true, 'minLen'=>3]
                foreach($rules[$input_name] as $method=>$required_value){
                    $result=call_user_func_array([$this, $method], [$value_in_input, $required_value]);
                    //svaka user func vraća true ili false a mi proveravamo za svaki input, pravila koja smo dali
                    if(!$result){
                        //onda upisati u arr greške
                        array_push($error_messages_array, $this->createErrorMessage($input_name, $required_value, $method));
                    }
                }
                if(count($error_messages_array)>0){
                    $errors[$input_name]=$error_messages_array;
                }

            }
        }
        if(count($errors)){
            $this->errorsArray= $errors;
        }
    }
    //does validation. if something is wrong, put name of the field and error(s) in $errorsArray
    protected function validate($request,  $rules){

        $errors=[];
        //za svaki npr name=bole, email=bole@gmai.com
        foreach($request as $input_name=>$value_in_input){
            //$errorRow[$input_name];//'input_name=>['input name je required', 'minimalna dužina je 4']
            //key je ime kolone u bazi/input polja/u model::rules je ključ
            $error_messages_array=[];
            if(array_key_exists($input_name, $rules)){
                //imamo rule za ovaj input pa validiramo
                //za svaki red iz niza $rules npr  jedan red je ['required'=>true, 'string'=>true, 'minLen'=>3]
                foreach($rules[$input_name] as $method=>$required_value){
                    $result=call_user_func_array([$this, $method], [$value_in_input, $required_value]);
                    //svaka user func vraća true ili false a mi proveravamo za svaki input, pravila koja smo dali
                    if(!$result){
                        //onda upisati u arr greške
                        array_push($error_messages_array, $this->createErrorMessage($input_name, $required_value, $method));
                    }
                }
                if(count($error_messages_array)>0){
                    $errors[$input_name]=$error_messages_array;
                }

            }
        }
        if(count($errors)){
            $this->errorsArray= $errors;
        }
    }
    //does validation. if something is wrong, put name of the field and error(s) in $errorsArray
    protected function validate2($request,  $rules){
        if(isset($_FILES) && !empty($_FILES)){
            $key=array_keys($_FILES)[0];
            $request[$key]=$_FILES[$key];
            //vardump($_FILES);
            //napraviti metodu koja će odmah da uzme vrednost iz Files ako ima
            /*
             array(1) {
                  ["photo"]=>
                  array(5) {
                    ["name"]=>
                    string(12) "php logo.png"
                    ["type"]=>
                    string(9) "image/png"
                    ["tmp_name"]=>
                    string(24) "C:\xampp\tmp\php2A63.tmp"
                    ["error"]=>
                    int(0)
                    ["size"]=>
                    int(177702)
                  }
}
             */
        }
        //vardump($request);
        $errors = [];
        //za svaki npr name=bole, email=bole@gmai.com
        foreach ($rules as $input_name => $arrOfRules) { //input_name is 'name', 'content', arrOfR=['required'=>true, 'string'=>true, 'minLen'=>2],
            $error_messages_array = [];
            foreach ($arrOfRules as $methodName => $argValue) { //method is 'required', 'minLen', argValue=true, 1
                if (method_exists($this, $methodName)) {
                    $errorMsg = $this->$methodName($request[$input_name], $input_name, $argValue);
                    logmsg($errorMsg);
                    if ($errorMsg) {
                        $error_messages_array[] = $this->createErrorMessage($input_name, $argValue, $methodName);
                    }
                } else {
                    throw new \Exception(
                        "{$methodName} does not exist."
                    );
                }//end else throw...
            }
            if (count($error_messages_array) > 0) {
                $errors[$input_name] = $error_messages_array;
            }

        }
        if(count($errors)){
            $this->errorsArray= $errors;
            //vardump($this->errorsArray);
        }
    }




//    public function allowedTypes($value_from_input, $arrayOfTypes)
//    {
//        $predefinedArrayOfTypes=["image/png"];
//        //vardump($value_from_input);
//        //vardump(mime_content_type($value_from_input['tmp_name'])); //string(9) "image/png"
//
//        if(!is_null($value_from_input)){
//            $extension = pathinfo($value_from_input['tmp_name'], PATHINFO_EXTENSION);
//            vardump($extension);
//            //$images = array('jpg', 'jpeg', 'png', 'bmp', 'gif');
//            if(!in_array(strtolower($extension), $arrayOfTypes)){
//                return false;
//            }
//            return true;
//        }
//        return true;
//
//    }

    protected function createErrorMessage($input_name, $required_value, $method){
       // printr($this->errorMessages);
        if(array_key_exists($method, $this->errorMessages)){
            //menjamo :input_name: i :required_value: sa poljima iz inputa
            $message=str_replace(':input_name:', str_replace('_', ' ', $input_name), $this->errorMessages[$method]);
            $message=str_replace(':required_value:', $required_value, $message);
            return ucfirst($message);
        }else{
            throw new \Exception("There is no {$method} as a key in errors messages. You need to create message for this method");
        }

    }

    public function areThereErrors(){
        return count($this->errorsArray)>0 ? $this->errorsArray : false;
    }

    public function getErrors(){
        return $this->errorsArray;
    }


    //$_POST['name'] = minLen => 3
    //
    protected function minLen($value_from_input, $rule){
        return strlen($value_from_input) >= $rule;
    }
    protected function maxLen($value_from_input, $rule){
        return strlen($value_from_input) <= $rule;
    }

    //all input fields will be evaluated. required=>true means that  something must be in the input
    //so if true
    //    return true if field is not empty and null. return false if is
    //if required => false, all input fields will pass 'required' evaluation
    public function required($value_from_input, $rule){

        if($rule){
            if(is_array($value_from_input)){
                return !empty($value_from_input) && $value_from_input != null;
            }else{
                return !empty(trim($value_from_input)) && $value_from_input != null;
            }

        }else{
            return true;
        }
    }
    //for checking uploaded file types




    protected function email($value_from_input)
    {
        if($value_from_input != null && !empty(trim($value_from_input))){
            return filter_var($value_from_input, FILTER_VALIDATE_EMAIL);
        }
        return true;
    }

    protected function string($value_from_input)
    {
        if($value_from_input != null && !empty(trim($value_from_input))){
            if(!is_string($value_from_input)){
                return false;
            }
        }
        return true;
    }

    protected function number($value_from_input)
    {
        if($value_from_input != null && !empty(trim($value_from_input))){
            if(!is_numeric($value_from_input)){
                return false;
            }
        }
        return true;
    }

    //for password confirmation
    protected function same_as($value_from_input, $rule){
        if($value_from_input != null && !empty(trim($value_from_input))){
            $request=Request::getParams();
            return $value_from_input == $request[$rule];
        }
        return false;
    }

}