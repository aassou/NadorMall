<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idContrat = $_POST['idContrat'];
	$idProjet = $_POST['idProjet'];
    if( !empty($_POST['dateOperation']) && !empty($_POST['montant'])){
    	$idOperation = $_POST['idOperation'];
        $dateOperation = htmlentities($_POST['dateOperation']);    
        $montant = htmlentities($_POST['montant']);
        $operation = new Operation(array('id' => $idOperation, 'date' => $dateOperation, 'montant' => $montant));
        $operationsManager = new OperationManager(PDOFactory::getMysqlConnection());
        $operationsManager->update($operation);
        $_SESSION['operation-update-success']="<strong>Opération valide : </strong>Opération modifiée avec succès.";
        header('Location:../operations.php?idContrat='.$idContrat.'&idProjet='.$idProjet);
    }
    else{
        $_SESSION['operation-update-error'] = "<strong>Erreur modification opération : </strong>Vous devez remplir les champs 'Date opération' et 'Montant'.";
        header('Location:../operations.php?idContrat='.$idContrat.'&idProjet='.$idProjet);
    }
    