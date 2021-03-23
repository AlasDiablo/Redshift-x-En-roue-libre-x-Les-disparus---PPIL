<?php

namespace ppil\util;

use phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailFactory
{
    public static function envoieEmail($body, $title, $email, $name){
    
        $mail = new PHPMailer();

        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->IsSMTP();
        $mail->Mailer     = "smtp";
        $mail->SMTPDebug  = 1;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587; 
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "chiken.killer.lel@gmail.com"; 
        $mail->Password   = file_get_contents(Reference::EMAIL_INI); // C'est le fichier avec le mdp du compte gmail
        $mail->setFrom("no-repley@sharemyride.fr", "ShareMyRide"); // Qui l'envoie

        $mail->addAddress($email, $name); 
        $mail->isHTML(false); 
        $mail->setLanguage("fr");
        $mail->CharSet    = "UTF-8"; 
        $mail->Subject    = $title; 
        $mail->Body       = $body;
        if(!$mail->send()){
            echo "erreur envoie mail";
        }
    }
}