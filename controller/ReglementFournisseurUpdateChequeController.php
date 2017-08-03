<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idFournisseur = htmlentities($_POST['idFournisseur']);
    if( !empty($_POST['numeroCheque']) ){
        $idReglement = htmlentities($_POST['idReglement']);
        $numeroCheque = htmlentities($_POST['numeroCheque']);
        $reglementFournisseurManager = new ReglementFournisseurManager(PDOFactory::getMysqlConnection());
        $reglementFournisseurManager->updateNumeroCheque($numeroCheque, $idReglement);
    }
    header('Location:../fournisseurs-reglements.php?idFournisseur='.$idFournisseur.'#listFournisseurs');
    