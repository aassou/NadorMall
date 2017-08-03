<?php 
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idEmploye = $_POST['idEmploye'];
	$idProjet = $_POST['idProjet'];
    $employeManager = new EmployeProjetManager(PDOFactory::getMysqlConnection());
	$employeManager->delete($idEmploye);
	$_SESSION['employe-delete-success'] = "<strong>Opération valide : </strong>Employé supprimé avec succès.";
	header('Location:../employes-projet.php?idProjet='.$idProjet);
    
    