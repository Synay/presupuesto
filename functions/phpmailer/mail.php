<?php
include('phpmailer.php');
/**
** Clase mail que hereda las clases de PhpMailer
**/
class Mail extends PhpMailer
{
    // Establecer las variables de la clase
    public $From     = 'sy2nayblood@gmail.com';
    public $FromName = SITETITLE;
    public $Host     = 'in-v3.mailjet.com';
    public $Mailer   = 'smtp';
    public $SMTPAuth = true;
    public $Username = '191b1f672a123c483f375a3228f0a4ce';
    public $Password = '07e06c45d3bc7147f5560fe8461ccfba';
    public $SMTPSecure = 'tls';
    public $WordWrap = 587;
    public $CharSet = 'UTF-8';

    /*public $From     = 'informatica@comavsa.cl';
    public $FromName = SITETITLE;
    public $Host     = 'mail.comavsa.cl';
    public $Mailer   = 'smtp';
    public $SMTPAuth = true;
    public $Username = 'inf41360';
    public $Password = 'comavsa2019';
    public $SMTPSecure = '';
    public $WordWrap = 587;
    public $CharSet = 'UTF-8';
    public $SMTPDebug = 2;*/



    /**
    ** Función que asigna un titulo del mensaje (asunto)
    **/
    public function subject($subject)
    {
        $this->Subject = $subject;
    }

    /**
    ** Función que asigna el cuerpo de un mensaje
    **/
    public function body($body)
    {
        $this->Body = $body;
    }

    /**
    ** Función que envia el mensaje
    **/ 
    public function send()
    {
        $this->AltBody = strip_tags(stripslashes($this->Body))."\n\n";
        $this->AltBody = str_replace("&nbsp;", "\n\n", $this->AltBody);
        return parent::send();
    }
}
