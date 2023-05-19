<?php

require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();

postEmail('nikita.rodionov201555@yandex.ru', 'Reset password', 'This is code');
function postEmail($email, $theme, $title, $text='') {
    global $mail;
    $code = [];
    for ($i = 0; $i < 10; $i++) {
        array_push($code, rand(0, 9));
    }
    $code = implode('', $code);


    $mail -> isSMTP();
    $mail -> CharSet = 'UTF-8';
    $mail -> SMTPAuth = true;
    $mail->Debugoutput = function($str, $level) {
        $GLOBALS['data']['debug'][] = $str;
    };

    $mail -> Host = 'smtp.gmail.com';
    $mail -> Username = 'nikita.rodionov202333@gmail.com';
    // $mail -> Username = 'Register form';
    $mail -> Password = 'xnssohrouhjcllpg';
    $mail -> SMTPSecure = 'ssl';
    $mail -> Port = '465';
    $mail -> setFrom('nikita.rodionov202333@gmail.com', $theme);

    $mail -> addAddress($email);

    $mail -> isHTML(true);
    $mail -> Subject = $title;
    $mail -> Body = $text ? $text : $code;
}


if ($mail -> send()) {
    echo('success');
}else {
    echo $mail -> ErrorInfo;
}
