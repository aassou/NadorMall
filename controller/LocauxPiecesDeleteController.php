<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idProjet = $_POST['idProjet'];
	$idLocaux = $_POST['idLocaux'];
	$idPieceLocaux = $_POST['idPieceLocaux'];   
    $piecesLocauxManager = new PiecesLocauxManager(PDOFactory::getMysqlConnection());
	$piecesLocauxManager->delete($idPieceLocaux);
	//delete file from the disk
	$_SESSION['pieces-delete-success'] = "<strong>Opération valide : </strong>Pièce supprimé avec succès.";
	header('Location:../locaux-detail.php?idLocaux='.$idLocaux.'&idProjet='.$idProjet);
    
    