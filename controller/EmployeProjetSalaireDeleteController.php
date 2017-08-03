<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idEmploye = $_POST['idEmploye'];   
	$idSalaire = $_POST['idSalaire'];  
    $salaireManager = new EmployeProjetSalaireManager(PDOFactory::getMysqlConnection());
	$salaireManager->delete($idSalaire);
	$_SESSION['salaire-delete-success'] = "<strong>Opération valide : </strong>Salaire supprimé avec succès.";
	header('Location:../employe-societe-profile.php?idEmploye='.$idEmploye);