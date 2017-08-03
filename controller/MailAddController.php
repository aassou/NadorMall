<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
    if(!empty($_POST['mail'])){
        $content = htmlentities($_POST['mail']);
        $sender = $_SESSION['userMerlaTrav']->login();
        $created = date("Y-m-d H:i:s");
        $mail = new Mail(array('content' => $content, 'sender' => $sender,'created' => $created));
        $mailManager = new MailManager(PDOFactory::getMysqlConnection());
        $mailManager->add($mail);
    }
    else{
        $_SESSION['mail-add-error'] = "Vous devez tapez un message !";
    }
    header('Location:../messages.php');