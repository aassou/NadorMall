<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idProjet = $_POST['idProjet'];
	$idLivraison = $_POST['idLivraison'];
	$idPieceLivraison = $_POST['idPieceLivraison'];   
    $livraisonPiecesManager = new LivraisonPiecesManager(PDOFactory::getMysqlConnection());
	$livraisonPiecesManager->delete($idPieceLivraison);
	//delete file from the disk
	$_SESSION['piece-delete-success'] = "<strong>Opération valide : </strong>Pièce supprimée avec succès.";
	header('Location:../livraison-pieces.php?idProjet='.$idProjet.'&idLivraison='.$idLivraison);
    
    