<?php
    include('../app/classLoad.php');
	include('../lib/image-processing.php');  
    //classes loading end
    session_start();
    
    //post input processing
    $idEmploye = htmlentities($_POST['idEmploye']);
	if( !empty($_POST['dateDebut']) and !empty($_POST['dateFin']) ){
		$dateDebut = htmlentities($_POST['dateDebut']);    
        $dateFin = htmlentities($_POST['dateFin']);
		//create class
		$conge = new EmployeProjetConge(array('dateDebut' => $dateDebut, 'dateFin' => $dateFin, 
		'idEmploye' => $idEmploye ));
		//create class manager
        $congeManager = new EmployeProjetCongeManager(PDOFactory::getMysqlConnection());
        $congeManager->add($conge);
		$_SESSION['conge-add-success'] = "<strong>Opération valide : </strong>Le congé est ajouté avec succès.";
		header('Location:../employe-projet-profile.php?idEmploye='.$idEmploye);
	}
	else{
        $_SESSION['conge-add-error'] = "<strong>Erreur Ajout Congé : </strong>Vous devez remplir tous les champs.";
		header('Location:../employe-projet-profile.php?idEmploye='.$idEmploye);
		exit;
    }
	