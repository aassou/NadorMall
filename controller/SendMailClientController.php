<?php
    include('../app/classLoad.php');  
    include('../lib/image-processing.php');
    //classes loading end
    session_start();
    $emailClient = htmlentities($_POST['email']);
    $client = htmlentities($_POST['client']);
    $datePaiement = htmlentities($_POST['datePaiement']);
    $subject = htmlentities($_POST['subject']);
    $message = htmlentities($_POST['message']);
    $message = wordwrap($message, 70, "\r\n");
    //$subject = 'Rappel sur paiement de régelement';
    //$message = 'Bonjour Chèr(e)'.$client.','."\n"."nous vous envoyons cet Email pour vous rappeler de votre paiement prévu date du : ".date('d/m/Y', strtotime($datePaiement))."\n"."Nous vous souhaitons une bonne journée.Groupe Annahda Lil Iaamar";
    $headers = 'From: commercial@groupeannahda.com' . "\r\n".
    'MIME-Version: 1.0' . "\r\n".
    'Content-type: text/html; charset=utf-8';

    mail($emailClient, $subject, $message, $headers);
    $actionMessage = "Opération Valide : Email envoyé avec succès.";
    $typeMessage = "success";
    $_SESSION['mail-action-message'] = $actionMessage;
    $_SESSION['mail-type-message'] = $typeMessage;
    header('Location:../contrat-status.php');
