<?php
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once './db_ops.php';
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function resetRequest($emailAddress) {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    $pass_reset = new Insert_Reset_Unique($emailAddress);
    if ($pass_reset->check_email_exists() > 0) {
        $pass_reset->insert_new_unique();
    }else {
        echo "<script>alert('No such email exists');</script>";
        header("Refresh:0");
        exit();
    }
    


    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'petromanEmailer@gmail.com';                     //SMTP username
        $mail->Password   = 'PetromanEmailer123';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('petromanEmailer@gmail.com', 'Petroman');
        $mail->addAddress($emailAddress);     //Add a recipient
        $mail->addReplyTo('no-reply@gmail.com', 'No reply');

        //Content
        $url = "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"])."/PhpProcesses/configure_password.php?code={$pass_reset->getCode()}";
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Petroman Password Email';
        $mail->Body    = "<a href='{$url}'>Click here</a> to change password";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


?>