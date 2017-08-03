<?php
    include('../db/dbconf.php');
	include('../lib/image-processing.php');  
    //classes loading end
    session_start();
    
    //post input processing
    $idEmploye = htmlentities($_POST['idEmploye']);
	if( !empty($_POST['salaire'])){
		$salaire = htmlentities($_POST['salaire']);    
        $prime = htmlentities($_POST['prime']);
		$dateOperation = htmlentities($_POST['dateOperation']);
		//create class
		$salaire = new EmployeSocieteSalaire(array('salaire' => $salaire, 'prime' => $prime, 
		'dateOperation' => $dateOperation,'idEmploye' => $idEmploye ));
		//create class manager
        $salaireManager = new EmployeSocieteSalaireManager(PDOFactory::getMysqlConnection());
        $salaireManager->add($salaire);
		$_SESSION['salaire-add-success'] = "<strong>Opération valide : </strong>Le salaire est ajouté avec succès.";
		header('Location:../employe-societe-profile.php?idEmploye='.$idEmploye);
	}
	else{
        $_SESSION['salaire-add-error'] = "<strong>Erreur Ajout Salaire : </strong>Vous devez remplir au moins le champ 'Salaire'.";
		header('Location:../employe-societe-profile.php?idEmploye='.$idEmploye);
		exit;
    }
	