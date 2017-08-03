<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    //post input processing   
	$idProjet = $_POST['idProjet'];
	$idContrat = $_POST['idContrat'];
    $contratTravailManager = new ContratTravailManager(PDOFactory::getMysqlConnection());
	$contratTravailManager->delete($idContrat);
	$_SESSION['contrat-delete-success']="<strong>تم حذف العقد بنجاح</strong>";
	header('Location:../contrats-travail.php?idProjet='.$idProjet);
    
    