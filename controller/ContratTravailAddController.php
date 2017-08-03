<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
    $idProjet = htmlentities($_POST['idProjet']);
    if( !empty($_POST['nom'])){
        $nom = htmlentities($_POST['nom']);    
        $cin = htmlentities($_POST['cin']);
		$adresse = htmlentities($_POST['adresse']);
		$dateNaissance = htmlentities($_POST['dateNaissance']);
		$dateContrat = htmlentities($_POST['dateContrat']);
		$matiere = htmlentities($_POST['matiere']);
		$prix = htmlentities($_POST['prix']);
		$mesure = htmlentities($_POST['mesure']);
		$prixTotal = htmlentities($_POST['prixTotal']);
		
        
        $contrat = new ContratTravail(array('nom' => $nom, 'cin' => $cin,
        'dateNaissance' => $dateNaissance, 'dateContrat' => $dateContrat,
		'adresse' => $adresse, 'matiere' => $matiere, 'prix' => $prix, 'mesure' => $mesure,
		'prixTotal' => $prixTotal, 'idProjet' => $idProjet));
        $contratTravailManager = new ContratTravailManager(PDOFactory::getMysqlConnection());
        $contratTravailManager->add($contrat);
        $_SESSION['contrat-add-success']="<strong>تم تسجيل العقد بنجاح</strong>";
    }
    else{
        $_SESSION['contrat-add-error'] = "<strong> خطأ في التسجيل</strong>"."يجب ادخال  الاسم ";
    }
	header('Location:../contrats-travail.php?idProjet='.$idProjet);
    