<?php 
    include('../app/classLoad.php');
	include('../lib/image-processing.php');  
    //classes loading end
    session_start();
    
    //post input processing
    $idEmploye = htmlentities($_POST['idEmploye']);
	if( !empty($_POST['salaire'])){
		$idSalaire = htmlentities($_POST['idSalaire']);
		$salaire = htmlentities($_POST['salaire']);    
        $prime = htmlentities($_POST['prime']);
		$dateOperation = htmlentities($_POST['dateOperation']);
		//create class
		$salaire = new EmployeSocieteSalaire(array('id' => $idSalaire, 'salaire' => $salaire, 'prime' => $prime, 
		'dateOperation' => $dateOperation ));
		//create class manager
        $salaireManager = new EmployeSocieteSalaireManager(PDOFactory::getMysqlConnection());
        $salaireManager->update($salaire);
		$_SESSION['salaire-update-success'] = "<strong>Opération valide : </strong>Les infos du salaire sont modifiées avec succès.";
		header('Location:../employe-societe-profile.php?idEmploye='.$idEmploye);
	}
	else{
        $_SESSION['salaire-update-error'] = "<strong>Erreur Modification Salaire : </strong>Vous devez remplir au moins le champ 'Salaire'.";
		header('Location:../employe-societe-profile.php?idEmploye='.$idEmploye);
		exit;
    }
	