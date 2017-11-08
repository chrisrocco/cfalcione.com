<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'phpmailer/PHPMailerAutoload.php';
$config = require_once 'config.php';

if (isset($_POST['inputName']) && isset($_POST['inputEmail'])&& isset($_POST['inputMessage'])) {

    //check if any of the inputs are empty
    if (empty($_POST['inputName']) || empty($_POST['inputEmail']) ||empty($_POST['inputMessage'])) {
        $data = array('success' => false, 'message' => 'Please fill out the form completely.');
        echo json_encode($data);
        exit;
    }

    //create an instance of PHPMailer
    $mail = new PHPMailer();

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $config['host'];  // Specify main and backup SMTP servers
    $mail->SMTPAuth = $config['smtp_auth'];                               // Enable SMTP authentication
    $mail->Username = $config['username'];                 // SMTP username
    $mail->Password = $config['password'];                           // SMTP password
    $mail->SMTPSecure = $config['smtp_secure'];                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = $config['smtp_port'];                                    // TCP port to connect to

    $mail->From = $_POST['inputEmail'];
    $mail->FromName = $_POST['inputName'];
    $mail->AddAddress( $config['to_email'] ); //recipient
    $mail->Subject = $config['to_subject'];
    $mail->Body = "Name: " . $_POST['inputName'] . "\r\n\r\nMessage: " . stripslashes($_POST['inputMessage']);

    if (isset($_POST['ref'])) {
        $mail->Body .= "\r\n\r\nRef: " . $_POST['ref'];
    }

    if(!$mail->send()) {
        $data = array('success' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        echo json_encode($data);
        exit;
    }

    $data = array('success' => true, 'message' => 'Thanks! We have received your message.');
    echo json_encode($data);

} else {

    $data = array('success' => false, 'message' => 'Please fill out the form completely.');
    echo json_encode($data);

}