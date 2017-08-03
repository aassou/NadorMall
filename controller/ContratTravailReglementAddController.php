<?php 
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
    $idProjet = htmlentities($_POST['idProjet']);
    if( !empty($_POST['montant'])){
        $montant = htmlentities($_POST['montant']);    
        $motif = htmlentities($_POST['motif']);
		$dateReglement = htmlentities($_POST['dateReglement']);
		$idContratTravail = htmlentities($_POST['idContratTravail']);
		
        
        $reglement = new ContratTravailReglement(array('montant' => $montant, 'motif' => $motif,
        'dateReglement' => $dateReglement, 'idContratTravail' => $idContratTravail));
        $contratTravailReglementManager = new ContratTravailReglementManager(PDOFactory::getMysqlConnection());
        $contratTravailReglementManager->add($reglement);
        $_SESSION['contrat-reglement-add-success']="<strong>تم تسجيل الدفعة المالية بنجاح</strong>";
    }
    else{
        $_SESSION['contrat-reglement-add-error'] = "<strong> خطأ في التسجيل</strong>"."يجب ادخال المبلغ  ";
    }
	header('Location:../contrats-travail.php?idProjet='.$idProjet);
    