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
    //The History Component is used in all ActionControllers to mention a historical version of each action
    $historyManager = new HistoryManager(PDOFactory::getMysqlConnection());
    $locauxManager = new LocauxManager(PDOFactory::getMysqlConnection());
    $projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
    $idProjet = htmlentities($_POST['idProjet']);
    $redirectLink = "Location:../locaux.php?idProjet=".$idProjet;
    $nomProjet = $projetManager->getProjetById($idProjet)->nom();
    //Action Add Processing Begin
    if($action == "add"){
        if( !empty($_POST['code']) ){
            $code = htmlentities($_POST['code']);
            $prix = htmlentities($_POST['prix']);
            $superficie = htmlentities($_POST['superficie']);
            $facade = htmlentities($_POST['facade']);
            $mezzanine = htmlentities($_POST['mezzanine']);
            $status = htmlentities($_POST['status']);
            $par = htmlentities($_POST['par']);
            $createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $locaux = 
            new Locaux(array('nom' => $code, 'prix' => $prix, 'superficie' => $superficie, 
            'facade' => $facade, 'mezzanine' => $mezzanine, 'idProjet' => $idProjet, 
            'status' => $status, 'par' => $par, 'createdBy' => $createdBy, 'created' => $created));
            //add it to db
            $locauxManager->add($locaux);
            //add History data
            $history = new History(array(
                'action' => "Ajout",
                'target' => "Table des locaux commerciaux",
                'description' => "Ajout du local commercial : ".$code." - Projet : ".$nomProjet,
                'created' => $created,
                'createdBy' => $createdBy
            ));
            //add it to db
            $historyManager->add($history);
            $actionMessage = "Opération Valide : Local Commercial Ajouté avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout Local Commercial : Vous devez remplir le champ <strong>Nom</strong>.";
            $typeMessage = "error";
        }
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        if(!empty($_POST['code'])){
            $id = htmlentities($_POST['idLocaux']);
            $code = htmlentities($_POST['code']);
            $prix = htmlentities($_POST['prix']);
            $superficie = htmlentities($_POST['superficie']);
            $facade = htmlentities($_POST['facade']);
            $mezzanine = htmlentities($_POST['mezzanine']);
            $status = htmlentities($_POST['status']);
            $par = htmlentities($_POST['par']);
            $updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            $locaux = 
            new Locaux(array('id' => $id, 'nom' => $code, 'prix' => $prix, 'superficie' => $superficie,
            'facade' => $facade, 'mezzanine' => $mezzanine, 'status' => $status, 'par' => $par, 
            'updatedBy' => $updatedBy, 'updated' => $updated));
            $locauxManager->update($locaux);
            //add History data
            $createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            $history = new History(array(
                'action' => "Modification",
                'target' => "Table des locaux commerciaux",
                'description' => "Modification du local commercial : ".$code." - Projet : ".$nomProjet,
                'created' => $created,
                'createdBy' => $createdBy
            ));
            //add it to db
            $historyManager->add($history);
            $actionMessage = "Opération Valide : Local Commercial Modifié avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification Local Commercial : Vous devez remplir le champ <strong>Code</strong>.";
            $typeMessage = "error";
        }
    }
    //Action Update Processign End
    //Action UpdateStatus Processing Begin
    else if($action=="updateStatus"){
        $idLocaux = $_POST['idLocaux'];
        $status = htmlentities($_POST['status']);
        $nomLocal = $locauxManager->getLocauxById($idLocaux)->nom();
        $locauxManager->changeStatus($idLocaux, $status);
        //add History data
        $createdBy = $_SESSION['userMerlaTrav']->login();
        $created = date('Y-m-d h:i:s');
        $history = new History(array(
            'action' => "Modification Status",
            'target' => "Table des locaux commerciaux",
            'description' => "Changement de status du local commercial ".$nomLocal." vers le status : ".$status." - Projet : ".$nomProjet,
            'created' => $created,
            'createdBy' => $createdBy
        ));
        //add it to db
        $historyManager->add($history);
        $actionMessage = "Opération Valide : Local Commercial Status Modifié avec succès.";
        $typeMessage = "success";
    }
    //Action UpdateStatus Processing End
    //UPDATE ETATLOCAUX BEGIN
    else if($action=="updateEtatLocaux"){
        $idLocaux = $_POST['idLocaux'];
        $titre = htmlentities($_POST['titre']);
        $superficie2 = htmlentities($_POST['superficie2']);
        $prixDeclare = htmlentities($_POST['prixDeclare']);
        $avancePrixDeclare = htmlentities($_POST['avancePrixDeclare']);
        $locauxManager->updateEtatLocaux($titre, $superficie2, $prixDeclare, $avancePrixDeclare, $idLocaux);
        //add history data to db
        $createdBy = $_SESSION['userMerlaTrav']->login();
        $created = date('Y-m-d h:i:s');
        $history = new History(array(
            'action' => "Modification état local commercial",
            'target' => "Table des locaux commerciaux",
            'description' => "Changement d'état du local commercial ID : ".$idLocaux." titre : ".$titre." - Supérficie : ".$superficie2." - Prix déclaré : ".$prixDeclare." - Avance sur prix déclaré : ".$avancePrixDeclare." - Projet : ".$nomProjet,
            'created' => $created,
            'createdBy' => $createdBy
        ));
        //add it to db
        $historyManager->add($history);
        $actionMessage = "Opération Valide : État Local Commercial Modifié avec succès.";
        $typeMessage = "success";
    }
    //UPDATE ETATLOCAUX END
    //UPDATE MontantReventeLocaux BEGIN
    else if($action=="updateMontantReventeLocaux"){
        $idLocaux = $_POST['idLocaux'];
        $montantRevente = htmlentities($_POST['montantRevente']);
        $locauxManager->updateMontantRevente($montantRevente, $idLocaux);
        //add history data to db
        $createdBy = $_SESSION['userMerlaTrav']->login();
        $created = date('Y-m-d h:i:s');
        $history = new History(array(
            'action' => "Modification Montant Revente local commercial",
            'target' => "Table des appartements",
            'description' => "Modification Montant Revente Local Commercial ID : ".$idLocaux." Montant Revente : ".$montantRevente,
            'created' => $created,
            'createdBy' => $createdBy
        ));
        //add it to db
        $historyManager->add($history);
        $actionMessage = "Opération Valide : Montant Revente Local Commercial Modifié avec succès.";
        $typeMessage = "success";
        $redirectLink = "Location:../properties-status.php";
    }
    //UPDATE MontantReventeLocaux END
    //Action UpdateClient Processing Begin
    else if($action=="updateClient"){
        $idLocaux = $_POST['idLocaux'];
        $par = htmlentities($_POST['par']);
        $nomLocal = $locauxManager->getLocauxById($idLocaux)->nom();
        $locauxManager->updatePar($par, $idLocaux);
        //add History data
        $createdBy = $_SESSION['userMerlaTrav']->login();
        $created = date('Y-m-d h:i:s');
        $history = new History(array(
            'action' => "Modification Client",
            'target' => "Table des locaux commerciaux",
            'description' => "Changement de réservation du local commercial ".$nomLocal." pour  : ".$par." - Projet : ".$nomProjet,
            'created' => $created,
            'createdBy' => $createdBy
        ));
        //add it to db
        $historyManager->add($history);
        $actionMessage = "Opération Valide : Local Commercial Réservation Modifiée avec succès.";
        $typeMessage = "success";
    }
    //Action UpdateClient Processing End
    //Action Delete Processing Begin
    else if($action=="delete"){
        $idLocaux = $_POST['idLocaux'];
        $nomLocal = $locauxManager->getLocauxById($idLocaux)->nom();
        $locauxManager->delete($idLocaux);
        //add History data
        $createdBy = $_SESSION['userMerlaTrav']->login();
        $created = date('Y-m-d h:i:s');
        $history = new History(array(
            'action' => "Suppression",
            'target' => "Table des locaux commerciaux",
            'description' => "Suppression du local commercial ".$nomLocal." - Projet : ".$nomProjet,
            'created' => $created,
            'createdBy' => $createdBy
        ));
        //add it to db
        $historyManager->add($history);
        $actionMessage = "Opération Valide : Local Commercial Supprimé avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    $_SESSION['locaux-action-message'] = $actionMessage;
    $_SESSION['locaux-type-message'] = $typeMessage;
    header($redirectLink);
    