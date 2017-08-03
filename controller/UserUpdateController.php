<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idUser = $_POST['idUser'];   
	$profil = $_POST['profil'];
    $userManager = new UserManager(PDOFactory::getMysqlConnection());
	$userManager->updateProfil($idUser, $profil);
	$_SESSION['user-update-success'] = "<strong>Opération valide</strong> : Profil Utlisateur est modifié avec succès.";
	header('Location:../users.php');
    
    