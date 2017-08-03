<?php
    include('../app/classLoad.php');
	include('../lib/image-processing.php');
    //classes loading end
    session_start();
	
	$idProjet = htmlentities($_POST['idProjet']);
	$idLivraison = htmlentities($_POST['idLivraison']);
	if(file_exists($_FILES['url']['tmp_name']) || is_uploaded_file($_FILES['url']['tmp_name'])) {
		$url = imageProcessing($_FILES['url'], '/pieces/pieces_livraison/');
		$nom = htmlentities($_POST['nom']);
		$livraisonPieces = new LivraisonPieces(array('nom'=>$nom, 'url'=>$url, 'idLivraison'=>$idLivraison));
		$livraisonPiecesManager = new LivraisonPiecesManager(PDOFactory::getMysqlConnection());
		$livraisonPiecesManager->add($livraisonPieces);
		$_SESSION['pieces-add-success'] = "<strong>Opération valide : </strong>La pièce a été ajoutée avec succès.";
		header('Location:../livraisons-list.php?idProjet='.$idProjet);
	}
	else{
		$_SESSION['pieces-add-error'] = "<strong>Erreur Ajout Documents Livraison : </strong>Vous devez ajouté un lien.";
		header('Location:../livraisons-list.php?idProjet='.$idProjet);
	}
