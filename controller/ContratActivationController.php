<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();    
    //post input processing
    $idProjet = $_POST['idProjet'];
	$idContrat  = $_POST['idContrat'];
	//create classes managers
	$contratManager = new ContratManager(PDOFactory::getMysqlConnection());
	$locauxManager = new LocauxManager(PDOFactory::getMysqlConnection());
	$appartementManager = new AppartementManager(PDOFactory::getMysqlConnection());
	//create classes
	$contrat = $contratManager->getContratById($idContrat);
	$redirectLink = 'Location:../contrats-list.php?idProjet='.$idProjet;
	if( isset($_GET['p']) and $_GET['p']==99 ){
		$redirectLink = 'Location:../clients-search.php';
	}
	if( $contrat->typeBien()=="appartement" ){
		if( $appartementManager->getAppartementById($contrat->idBien())->status()=="Non" ){
			$appartementManager->changeStatus($contrat->idBien(), "Vendu");
			$contratManager->activerContrat($idContrat);
			$_SESSION['contrat-activation-success'] = "<strong>Opération valide : </strong>Le contrat est activé avec succès.";
			header($redirectLink);
			exit;	
		}
		else{
			$_SESSION['contrat-activation-error'] = "<strong>Erreur Activation Contrat : </strong>Le bien est déjà réservé par un autre client.";
			header($redirectLink);
			exit;		
		}
	}
	else if( $contrat->typeBien()=="localCommercial" ){
		if( $locauxManager->getLocauxById($contrat->idBien())->status()=="Non" ){
			$locauxManager->changeStatus($contrat->idBien(), "Vendu");
			$contratManager->activerContrat($idContrat);
			$_SESSION['contrat-activation-success'] = "<strong>Opération valide : </strong>Le contrat est activé avec succès.";
			header($redirectLink);
			exit;	
		}
		else{
			$_SESSION['contrat-activation-error'] = "<strong>Erreur Activation Contrat : </strong>Le bien est déjà réservé par un autre client.";
			header($redirectLink);
			exit;		
		}
	}
	
