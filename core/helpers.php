<?php
use App\Core\Session;
//use \App\Core\Log;



function logmsg(){
    $args=func_get_args();
    foreach ($args as $arg){
        \App\Core\Log::add(toJson($arg));
    }

}


function redirect($path)
{
    header("Location: /{$path}");
    exit();
}
function redirectBack()
{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;

}

function active($href){
    $uri=\App\Core\Request::uri();
    $questionMarkPosition=strpos($href, '?');
    $cleanHref=$questionMarkPosition ? substr($href, 0, $questionMarkPosition) : $href;
    return $uri === $cleanHref ? ' active ' : '' ;
}



function redirectToHome()
{
    header('Location: '.'/' );
    exit;
}


function getAppValue($key){
    return \app\Core\App::get('config')[$key];
}

function getToken(){
    return \App\Core\Csrf::token();
}


//used in view
function old($key){
    if(Session::has($key)){
        return Session::flash($key);
   }
}

function printr($value){
    echo "<pre>";
    print_r($value);
    echo "</pre>";
    exit;
}

function vardump($value){
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    exit;
}
/*
$errors=[];
$errors['course']=['You have already applied for this course'];
Session::add('errors_course', $errors);
*/
function showError($input_name, $errors_array){

    if($errors_array){
        if(array_key_exists($input_name, $errors_array)){
            //we have errors
            foreach($errors_array[$input_name] as $message){
                echo "<span class='text-danger'>{$message}</span><br>";
            }
        }
    }
}

function showSuccess($message, $tag, $class){
    if($message){
        echo '<'.$tag.' class="'.$class.'" >'.$message.'</'.$tag.'>';
    }
}

function flashSessionMessage(string $key){
    return Session::has($key) ? Session::flash($key) : null;
}


function showPaginationHtml($tag, $classList, $link, $value){
    echo "<{$tag} class={$classList} href={$link}>{$value}</{$tag}>";
}

function layout($file, $data=[]){
    
    if(file_exists('../app/views/'.$file)){
        extract($data);
        require_once '../app/views/'.$file;

    }else{
        throw new \Exception("{$file} does not exist");
    }


}

function isLoggedIn(){
    return Session::has('authenticated_user_id');
}

function getAuthUserId(){
    return Session::get('authenticated_user_id');
}

function getAuthUserModel(){
    return Session::get('authenticated_user_object');
}

function toJson($var){
    return json_encode($var);
}

function createRandomToken(int $length){
   // return base64_encode(openssl_random_pseudo_bytes($length));
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz9876543210";
    $var_size = strlen($chars);
    $random_str="";
    for( $x = 0; $x < $length; $x++ ) {
        $random_str .= $chars[ rand( 0, $var_size - 1 ) ];
    }
    return $random_str;

}

/*
 * <script>
    // let c=document.cookie;
    // console.log(c);

    function getCookieValue(a) {
        var b = document.cookie.match('(^|;)\\s*' + a + '\\s*=\\s*([^;]+)');
        return b ? b.pop() : '';
    }
    console.log('aaa'+getCookieValue('PHPSESSID'))
</script>
 */


