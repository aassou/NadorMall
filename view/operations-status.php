<?php
    include('../app/classLoad.php');
    session_start();
    if( isset($_SESSION['userMerlaTrav']) ) {
        if( isset($_GET['idProjet']) ){
           $idProjet = $_GET['idProjet'];   
        }
        //destroy contrat-form-data session
        if ( isset($_SESSION['contrat-form-data']) ) {
            unset($_SESSION['contrat-form-data']);
        }
        $projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
        $clientManager = new ClientManager(PDOFactory::getMysqlConnection());
        $contratManager = new ContratManager(PDOFactory::getMysqlConnection());
        $operationManager = new OperationManager(PDOFactory::getMysqlConnection());
        $compteBancaireManager = new CompteBancaireManager(PDOFactory::getMysqlConnection());
        
        /*$codeContrat = $_GET['codeContrat'];
        $comptesBancaires = $compteBancaireManager->getCompteBancaires();
        $contrat = $contratManager->getContratByCode($codeContrat);
        
        
        $projet = $projetManager->getProjetById($contrat->idProjet());
        $client = $clientManager->getClientById($contrat->idClient());
        $sommeOperations = $operationManager->sommeOperations($contrat->id());
        $biens = "";
        $niveau = "";
        if($contrat->typeBien()=="appartement"){
            $appartementManager = new AppartementManager(PDOFactory::getMysqlConnection());
            $biens = $appartementManager->getAppartementById($contrat->idBien());
            $niveau = $biens->niveau();
        }
        else if($contrat->typeBien()=="localCommercial"){
            $locauxManager = new LocauxManager(PDOFactory::getMysqlConnection());
            $biens = $locauxManager->getLocauxById($contrat->idBien());
        }*/
        $mois = $_GET['mois'];
        $annee = $_GET['annee'];
        //$operations = "";
        //test the locaux object number: if exists get operations else do nothing
        //$operationsNumber = $operationManager->getOpertaionsNumberByIdContrat($contrat->id());
        $operations = $operationManager->getOperationsValideesByMonthYear($mois, $annee);
        /*if($operationsNumber != 0){
            $operations = $operationManager->getOperationsByIdContrat($contrat->id());  
        }*/
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <?php include('../include/head.php') ?>
</head>
<body class="fixed-top">
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <?php include('../include/top-menu.php') ?>
    </div>
    <div class="page-container row-fluid sidebar-closed">
        <?php include('../include/sidebar.php') ?>
        <div class="page-content">            
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="breadcrumb">
                            <li><i class="icon-home"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-bar-chart"></i> <a href="status.php">Les états</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-money"></i><a href="operations-status-group.php">Les états des paiements clients</a><i class="icon-angle-right"></i></li>
                            <li><a><strong><?= $mois."/".$annee ?></strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                    <div class="portlet box light-grey" id="detailsReglements">
                        <div class="portlet-title">
                            <h4>Liste des paiements clients validés</h4>
                            <div class="tools">
                                <a href="javascript:;" class="reload"></a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="clearfix">
                                <?php if ( isset($_SESSION['operation-action-message']) and isset($_SESSION['operation-type-message']) ){ $message = $_SESSION['operation-action-message']; $typeMessage = $_SESSION['operation-type-message']; ?>
                                <div class="alert alert-<?= $typeMessage ?>"><button class="close" data-dismiss="alert"></button><?= $message ?></div>
                                <?php } unset($_SESSION['operation-action-message']); unset($_SESSION['operation-type-message']); ?>
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>
                                        <th><span class="hidden-phone">Action</span></th>
                                        <th>Client</th>
                                        <th>Projet</th>
                                        <th class="hidden-phone">DOpér</th>
                                        <th class="hidden-phone">DRégl</th>
                                        <th class="hidden-phone">ModPaimnt</th>
                                        <th class="hidden-phone">Compte</th>
                                        <th class="hidden-phone">N°Opé</th>
                                        <th>Montant</th>
                                        <th class="hidden-phone">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($operations as $operation){
                                        $status = "";
                                        $action = "";
                                        $idContrat = $operation->idContrat();
                                        $contrat = $contratManager->getContratById($idContrat);
                                        $nomProjet = $projetManager->getProjetById($contrat->idProjet())->nom();
                                        $nomClient = $contratManager->getClientNameByIdContract($operation->idContrat());
                                        if ( $operation->status() == 0 ) {
                                            $action = '<a class="btn grey mini"><i class="icon-off"></i></a>'; 
                                            if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
                                                $status = '<a class="btn red mini" href="#validateOperation'.$operation->id().'" data-toggle="modal" data-id="'.$operation->id().'"><i class="icon-pause"></i>&nbsp;Non validé</a>';  
                                            } 
                                            else{
                                                $status = '<a class="btn red mini"><i class="icon-pause"></i>&nbsp;Non validé</a>';
                                            } 
                                        } 
                                        else {
                                            if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
                                                $status = '<a class="btn blue mini" href="#cancelOperation'.$operation->id().'" data-toggle="modal" data-id="'.$operation->id().'"><i class="icon-ok"></i>&nbsp;Validé</a>';
                                                $action = '<a class="btn green mini" href="#hideOperation'.$operation->id().'" data-toggle="modal" data-id="'.$operation->id().'"><i class="icon-off"></i></a>';   
                                            }
                                            else {
                                                $status = '<a class="btn blue mini"><i class="icon-ok"></i>&nbsp;Validé</a>';
                                                $action = '<a class="btn grey mini"><i class="icon-off"></i></a>'; 
                                            }
                                        }
                                    ?>      
                                    <tr class="odd gradeX">
                                        <td><?= $action ?></td>
                                        <td><?= $nomClient ?></td>
                                        <td><?= $nomProjet ?></td>
                                        <td class="hidden-phone"><?= date('d/m/Y', strtotime($operation->date())) ?></td>
                                        <td class="hidden-phone"><?= date('d/m/Y', strtotime($operation->dateReglement())) ?></td>
                                        <td class="hidden-phone"><?= $operation->modePaiement() ?></td>
                                        <td class="hidden-phone"><?= $operation->compteBancaire() ?></td>
                                        <td class="hidden-phone"><?= $operation->numeroCheque() ?></td>
                                        <td><?= number_format($operation->montant(), 2, ',', ' ') ?>&nbsp;DH</td>
                                        <td class="hidden-phone"><?= $status ?></td>
                                    </tr>   
                                    <!-- validateOperation box begin-->
                                    <div id="validateOperation<?= $operation->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Valider Paiement Client </h3>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal loginFrm" action="../controller/OperationActionController.php" method="post">
                                                <div class="control-group">
                                                    <input type="hidden" name="action" value="validate" />
                                                    <input type="hidden" name="source" value="operations-status" />
                                                    <input type="hidden" name="mois" value="<?= $mois ?>" />
                                                    <input type="hidden" name="annee" value="<?= $annee ?>" />
                                                    <input type="hidden" name="idOperation" value="<?= $operation->id() ?>" />
                                                    <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                    <button type="submit" class="btn blue" aria-hidden="true">Oui</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- validateOperation box end -->
                                    <!-- cancelOperation box begin-->
                                    <div id="cancelOperation<?= $operation->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Annuler Paiement Client </h3>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal loginFrm" action="../controller/OperationActionController.php" method="post">
                                                <div class="control-group">
                                                    <input type="hidden" name="action" value="cancel" />
                                                    <input type="hidden" name="source" value="operations-status" />
                                                    <input type="hidden" name="mois" value="<?= $mois ?>" />
                                                    <input type="hidden" name="annee" value="<?= $annee ?>" />
                                                    <input type="hidden" name="idOperation" value="<?= $operation->id() ?>" />
                                                    <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                    <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- cancelOperation box end -->
                                    <!-- hideOperation box begin-->
                                    <div id="hideOperation<?= $operation->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Retirer Paiement Client </h3>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal loginFrm" action="../controller/OperationActionController.php" method="post">
                                                <div class="control-group">
                                                    <input type="hidden" name="action" value="hide" />
                                                    <input type="hidden" name="source" value="operations-status" />
                                                    <input type="hidden" name="mois" value="<?= $mois ?>" />
                                                    <input type="hidden" name="annee" value="<?= $annee ?>" />
                                                    <input type="hidden" name="idOperation" value="<?= $operation->id() ?>" />
                                                    <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                    <button type="submit" class="btn green" aria-hidden="true">Oui</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- hideOperation box end -->  
                                    <!-- delete box begin-->
                                    <div id="deleteOperation<?= $operation->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Supprimer Réglement Client </h3>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal loginFrm" action="../controller/OperationActionController.php" method="post">
                                                <p>Êtes-vous sûr de vouloir supprimer ce réglement ?</p>
                                                <div class="control-group">
                                                    <input type="hidden" name="action" value="delete" />
                                                    <input type="hidden" name="mois" value="<?= $mois ?>" />
                                                    <input type="hidden" name="annee" value="<?= $annee ?>" />
                                                    <input type="hidden" name="idOperation" value="<?= $operation->id() ?>" />
                                                    <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                    <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- delete box end --> 
                                    <?php
                                    }//end of loop
                                    ?>
                                </tbody>
                            </table>
                        </div>
                     </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../include/footer.php') ?>        
    <?php include('../include/scripts.php') ?>        
    <script>
        jQuery(document).ready(function() {         
            // initiate layout and plugins
            App.setPage("table_managed");
            $('.hidenBlock').hide();
            App.init();
            function blinker() {
                $('.blink_me').fadeOut(500);
                $('.blink_me').fadeIn(500);
            }
            
            setInterval(blinker, 1500);
        });
    </script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>