<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idProjet = $_POST['idProjet'];
	$idTerrain = $_POST['idTerrain'];   
    $terrainManager = new TerrainManager(PDOFactory::getMysqlConnection());
	$terrainManager->delete($idTerrain);
	$_SESSION['terrain-delete-success'] = "<strong>Opération valide : </strong>Terrain supprimé avec succès.";
	header('Location:../terrain.php?idProjet='.$idProjet.'#listTerrain');
    
    