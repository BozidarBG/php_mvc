<?php


namespace App\app\mails;


use App\core\Mail;

class AuthenticationMail extends Mail
{

    protected $subject="Confirmation Email";
    protected $emailFile="confirmation";

    public function __construct($sendToEmailAddress, $data, $sendToName)
    {

        //return true;
        parent::__construct($sendToEmailAddress, $this->subject, $data, $this->emailFile, $sendToName);
    }


}