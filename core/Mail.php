<?php


namespace App\core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mail
{

    //public $body;
    protected array $data=[];
    protected $nameOfEmailFile;
    protected $sendToEmailAddress;
    protected $subject;
    protected $sendToName=null;
    //settings
    private $host, $username, $password,$fromEmail, $fromName;

    // Instantiation and passing `true` enables exceptions
    public function __construct(string $sendToEmailAddress,
                                string $subject,
                                array $data,
                                string $nameOfEmailFile,
                                string $sendToName=null)
    {
        $this->data=$data;
        $this->nameOfEmailFile=$this->checkIfEmailFileExists($nameOfEmailFile);
        $this->sendToEmailAddress=$sendToEmailAddress;
        $this->subject=$subject;
        $this->sendToName=$sendToName;
        $this->fillFromConfig();
        //vardump($this);
        $this->sendEmail();
    }

    private function fillFromConfig(){
        $arr=['host'=>'mailable_host', 'username'=>'mailable_username', 'password'=>'mailable_password',
            'fromEmail'=>'mailable_from_email', 'fromName'=>'mailable_from_name'];
        foreach($arr as $prop=>$config_name){
            $this->$prop=getAppValue($config_name);
        }
    }

    protected function sendEmail(){
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                           // Send using SMTP
        $mail->Mailer     = "smtp";
        $mail->Host       = $this->host;                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $this->username;                     // SMTP username
        $mail->Password   = $this->password;                               // SMTP password
        $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged //PHPMailer::ENCRYPTION_STARTTLS za 587
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        //Recipients
        $mail->setFrom($this->fromEmail, $this->fromName);
        $mail->addAddress($this->sendToEmailAddress, $this->sendToName);     // Add a recipient
//        $mail->addReplyTo('info@example.com', 'Information');
  //      $mail->addCC('cc@example.com');
    //    $mail->addBCC('bcc@example.com');

        // Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $this->subject;
        $mail->Body    = $this->createBody();

        $mail->send();
        return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    protected function checkIfEmailFileExists($nameOfEmailFile){
        if(file_exists("../app/views/mails/{$nameOfEmailFile}.view.php")){
            return $nameOfEmailFile;
        }else{
            throw new \Exception("{$nameOfEmailFile} does not exist!");
        }
    }

    protected function createBody()
    {
        ob_start();
        extract($this->data);
        require "../app/views/mails/{$this->nameOfEmailFile}.view.php";
        return ob_get_clean();
    }
}