<?php
    include('../app/classLoad.php');  
    include('../lib/image-processing.php');
    //classes loading end
    session_start();
    
    //post input processing
    $action = htmlentities($_POST['action']);
    //This var contains result message of CRUD action
    $actionMessage = "";
    $typeMessage = "";
    $redirectLink = "";
    $idProjet = htmlentities($_POST['idProjet']);

    //Component Class Manager
    $clientManager   = new ClientManager(PDOFactory::getMysqlConnection());
    $projetManager   = new ProjetManager(PDOFactory::getMysqlConnection()); 
    $syndiqueManager = new SyndiqueManager(PDOFactory::getMysqlConnection());
    $historyManager  = new HistoryManager(PDOFactory::getMysqlConnection());
	//Action Add Processing Begin
    if($action == "add"){
        if(!empty($_POST['date']) and !empty($_POST['idClient']) and !empty($_POST['montant'])){
			$date = htmlentities($_POST['date']);
			$montant = htmlentities($_POST['montant']);
            $status = htmlentities($_POST['status']);
			$idClient = htmlentities($_POST['idClient']);
			$createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $syndique = new Syndique(array(
				'date' => $date,
				'montant' => $montant,
				'status' => $status,
				'idClient' => $idClient,
				'idProjet' => $idProjet,
				'created' => $created,
            	'createdBy' => $createdBy
			));
            //add it to db
            $syndiqueManager->add($syndique);
            //add history data to db
            $history = new History(array(
                'action' => "Ajout",
                'target' => "Table de Syndique",
                'description' => "Ajout d'une operation au Syndique - Projet: ".$projetManager->getProjetById($idProjet)->nom()." - Client : ".$clientManager->getClientById($idClient)->nom()." - Montant : ".$montant." DH",
                'created' => $created,
                'createdBy' => $createdBy
            ));
            //add it to db
            $historyManager->add($history);
            $actionMessage = "Opération Valide : Ligne Syndique Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout Paiement au Syndique : Vous devez remplir tous les champs!";
            $typeMessage = "error";
        }
        $redirectLink = "Location:../syndique.php?idProjet=$idProjet";
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        $idSyndique = htmlentities($_POST['idSyndique']);
        if(!empty($_POST['date']) and !empty($_POST['idClient']) and !empty($_POST['montant'])){
			$date = htmlentities($_POST['date']);
			$montant = htmlentities($_POST['montant']);
            $status = htmlentities($_POST['status']);
			$idClient = htmlentities($_POST['idClient']);
			$updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            $syndique = new Syndique(array(
				'id' => $idSyndique,
				'date' => $date,
				'montant' => $montant,
				'status' => $status,
				'idClient' => $idClient,
				'idProjet' => $idProjet,
				'updated' => $updated,
            	'updatedBy' => $updatedBy
			));
            $syndiqueManager->update($syndique);
            //add history data to db
            $history = new History(array(
                'action' => "Modification",
                'target' => "Table de Syndique",
                'description' => "Modification d'une operation au Syndique - Projet: ".$projetManager->getProjetById($idProjet)->nom()." - Client : ".$clientManager->getClientById($idClient)->nom()." - Montant : ".$montant." DH",
                'created' => $created,
                'createdBy' => $createdBy
            ));
            //add it to db
            $historyManager->add($history);
            $actionMessage = "Opération Valide : Ligne Syndique Modifié(e) avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification Syndique : Vous devez remplir tous les champs!";
            $typeMessage = "error";
        }
        //redirection Link
        $redirectLink = "Location:../syndique.php?idProjet=$idProjet";
    }
    //Action Update Processing End
    //Action UpdateStatus Processing Begin
    else if($action == "updateStatus"){
        $idSyndique = htmlentities($_POST['idSyndique']);
        $status = htmlentities($_POST['status']);
        $syndiqueManager->updateStatus($idSyndique, $status);
        //add history data to db
        $history = new History(array(
            'action' => "Modification Status",
            'target' => "Table de Syndique",
            'description' => "Modification de status de l'operation de syndique ID : ".$idSyndique."- Projet : ".$projetManager->getProjetById($idProjet)->nom(),
            'created' => $created,
            'createdBy' => $createdBy
        ));
        //add it to db
        $historyManager->add($history);
        $actionMessage = "Opération Valide : Status Modifié(e) avec succès.";
        $typeMessage = "success";
        //redirection Link
        $redirectLink = "Location:../syndique.php?idProjet=$idProjet";
    }
    //Action UpdateStatus Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idSyndique = htmlentities($_POST['idSyndique']);
        $syndiqueManager->delete($idSyndique);
        //add history data to db
        $history = new History(array(
            'action' => "Suppression",
            'target' => "Table de Syndique",
            'description' => "Suppression de l'operation de syndique ID : ".$idSyndique."- Projet : ".$projetManager->getProjetById($idProjet)->nom(),
            'created' => $created,
            'createdBy' => $createdBy
        ));
        //add it to db
        $historyManager->add($history);
        $actionMessage = "Opération Valide : Ligne Syndique supprimé(e) avec succès.";
        $typeMessage = "success";
        //redirection Link
        $redirectLink = "Location:../syndique.php?idProjet=$idProjet";
    }
    //Action Delete Processing End
    $_SESSION['syndique-action-message'] = $actionMessage;
    $_SESSION['syndique-type-message'] = $typeMessage;
    header($redirectLink);

