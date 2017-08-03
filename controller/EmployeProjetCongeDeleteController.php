<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idEmploye = $_POST['idEmploye'];   
	$idConge = $_POST['idConge'];  
    $congeManager = new EmployeProjetCongeManager(PDOFactory::getMysqlConnection());
	$congeManager->delete($idConge);
	$_SESSION['conge-delete-success'] = "<strong>Opération valide : </strong>Congé supprimé avec succès.";
	header('Location:../employe-projet-profile.php?idEmploye='.$idEmploye);