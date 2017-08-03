<?php 
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idEmploye = $_POST['idEmploye'];   
    $employeManager = new EmployeSocieteManager(PDOFactory::getMysqlConnection());
	$employeManager->delete($idEmploye);
	$_SESSION['employe-delete-success'] = "<strong>Opération valide : </strong>Employé supprimé avec succès.";
	header('Location:../employes-societe.php');
    
    