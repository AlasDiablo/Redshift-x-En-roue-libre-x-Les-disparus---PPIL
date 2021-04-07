<?php

namespace ppil\util;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailFactory
{
    public static function envoieEmail($body, $title, $email, $name)
    {

        $mail = new PHPMailer();

        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->IsSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPDebug = 1;
        $mail->SMTPAuth = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;
        $mail->Host = "smtp.gmail.com";

        $gmail_account = parse_ini_file('src/conf/email.ini');

        $mail->Username = $gmail_account['username'];
        $mail->Password = $gmail_account['password'];
        $mail->setFrom("no-repley@sharemyride.fr", "ShareMyRide");

        $mail->addAddress($email, $name);
        $mail->isHTML(false);
        $mail->setLanguage("fr");
        $mail->CharSet = "UTF-8";
        $mail->Subject = $title;
        $mail->Body = $body;
        if (!$mail->send()) {
            echo "erreur envoie mail";
        }
    }
}
