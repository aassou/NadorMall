<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idUser = $_POST['idUser'];   
    $userManager = new UserManager(PDOFactory::getMysqlConnection());
	$userManager->delete($idUser);
	$_SESSION['user-delete-success'] = "<strong>Opération valide</strong> : Utlisateur supprimé avec succès.";
	header('Location:../users.php');
    
    