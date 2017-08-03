<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idProjet = $_POST['idProjet'];
	$idPieceTerrain = $_POST['idPieceTerrain'];   
    $piecesTerrainManager = new PiecesTerrainManager(PDOFactory::getMysqlConnection());
	$piecesTerrainManager->delete($idPieceTerrain);
	//delete file from the disk
	$_SESSION['pieces-delete-success'] = "<strong>Opération valide : </strong>Pièce supprimé avec succès.";
	header('Location:../terrain.php?idProjet='.$idProjet.'#listTerrain');
    
    