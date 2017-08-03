<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
        //class managers
        $projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
        $clientManager = new ClientManager(PDOFactory::getMysqlConnection());
        $companyManager = new CompanyManager(PDOFactory::getMysqlConnection());
        $contratManager = new ContratManager(PDOFactory::getMysqlConnection());
        $compteBancaireManager = new CompteBancaireManager(PDOFactory::getMysqlConnection());
        $operationManager = new OperationManager(PDOFactory::getMysqlConnection());
        $locauxManager = new LocauxManager(PDOFactory::getMysqlConnection());
        $appartementManager = new AppartementManager(PDOFactory::getMysqlConnection());
        //vars and objects
        $comptesBancaires = $compteBancaireManager->getCompteBancaires();
        $contrats = $contratManager->getContratsDesistes();   
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <?php include('../include/head.php') ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <?php include('../include/top-menu.php') ?>   
        <!-- END TOP NAVIGATION BAR -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container row-fluid sidebar-closed">
        <!-- BEGIN SIDEBAR -->
        <?php include('../include/sidebar.php') ?>
        <!-- END SIDEBAR -->
        <!-- BEGIN PAGE -->
        <div class="page-content">
            <!-- BEGIN PAGE CONTAINER-->            
            <div class="container-fluid">
                <!-- BEGIN PAGE HEADER-->
                <div class="row-fluid">
                    <div class="span12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->           
                        <h3 class="page-title">
                            Liste de tous les contrats désistés
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-dashboard"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-bar-chart"></i>
                                <a href="status.php">Les états</a>
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-file"></i>
                                <a>Liste de tous les contrats désistés</a>
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <div class="row-fluid">
                    <div class="span12">
                        <!-- BEGIN Terrain TABLE PORTLET-->
                        <?php 
                        if( isset($_SESSION['contrat-action-message']) 
                        and isset($_SESSION['contrat-type-message'])) {
                            $message = $_SESSION['contrat-action-message'];
                            $typeMessage = $_SESSION['contrat-type-message']; 
                        ?>
                            <div class="alert alert-<?= $typeMessage ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $message ?>     
                            </div>
                         <?php } 
                            unset($_SESSION['contrat-action-message']);
                            unset($_SESSION['contrat-type-message']);
                         ?>
                        <div class="portlet box light-grey">
                            <div class="portlet-title">
                                <h4>Liste des contrats clients</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="clearfix">
                                    <div class="btn-group">
                                        <a class="btn blue pull-right" href="">
                                            <i class="icon-print"></i>
                                             Version Imprimable
                                        </a>
                                    </div>
                                </div>
                                <!--div class="scroller" data-height="500px" data-always-visible="1"--><!-- BEGIN DIV SCROLLER -->
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">Actions</th>
                                            <th style="width:20%">Client</th>
                                            <th style="width:15%">Projet</th>
                                            <th style="width:20%">Bien</th>
                                            <th style="width:10%" class="hidden-phone">Date Contrat</th>
                                            <th style="width:10%" class="hidden-phone">Prix</th>
                                            <th style="width:10%" class="hidden-phone">Réglements</th>
                                            <th style="width:10%" class="hidden-phone">Reste</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($contrats as $contrat){
                                            $projet = $projetManager->getProjetById($contrat->idProjet());
                                            $revendreTitle = "";
                                            $montantRevente = 0;
                                            $operationsNumber = $operationManager->getOpertaionsNumberByIdContrat($contrat->id());
                                            $sommeOperations = $operationManager->sommeOperations($contrat->id());
                                            $bien = "";
                                            $typeBien = "";
                                            $etage = "";
                                            if($contrat->typeBien()=="appartement"){
                                                $bien = $appartementManager->getAppartementById($contrat->idBien());
                                                $typeBien = "Appart";
                                                $etage = "Etage ".$bien->niveau();
                                            }
                                            else{
                                                $bien = $locauxManager->getLocauxById($contrat->idBien());
                                                $typeBien = "Local";
                                                $etage = "";
                                            }
                                        ?>      
                                        <tr class="odd gradeX">
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn black mini dropdown-toggle" href="#" data-toggle="dropdown">
                                                        Choisir 
                                                        <i class="icon-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="contrat.php?codeContrat=<?= $contrat->code() ?>" target="_blank">
                                                                Consulter Contrat
                                                            </a>
                                                            <a target="_blank" href="../controller/ContratDesistementPrintController.php?idContrat=<?= $contrat->id() ?>">
                                                                Imprimer Acte de désistement AR&nbsp;
                                                            </a>
                                                            <a target="_blank" href="../controller/ContratArabePrintController.php?idContrat=<?= $contrat->id() ?>">
                                                                Imprimer Contrat AR
                                                            </a>
                                                            <a target="_blank" href="../controller/ContratPrintController.php?idContrat=<?= $contrat->id() ?>">
                                                                Imprimer Contrat FR
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td><?= $clientManager->getClientById($contrat->idClient())->nom() ?></td>
                                            <td><?= $projet->nom() ?></td>
                                            <td><?= $typeBien ?> - <?= $bien->nom() ?> - <?= $etage ?></td>
                                            <td class="hidden-phone"><?= date('d/m/Y', strtotime($contrat->dateCreation())) ?></td>
                                            <td class="hidden-phone"><?= number_format($contrat->prixVente(), 2, ',', ' ') ?></td>
                                            <td class="hidden-phone"><?= number_format($sommeOperations, 2, ',', ' ') ?></td>
                                            <td class="hidden-phone"><?= number_format($contrat->prixVente()-$sommeOperations, 2, ',', ' ') ?></td>
                                        </tr>
                                        <!-- activation box begin-->
                                        <div id="activerContrat<?= $contrat->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Activer le contrat </h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal loginFrm" action="../controller/ContratActionController.php" method="post">
                                                    <p>Êtes-vous sûr de vouloir activer le contrat <strong>N°<?= $contrat->id() ?></strong> ?</p>
                                                    <div class="control-group">
                                                        <input type="hidden" name="action" value="activer" />
                                                        <input type="hidden" name="source" value="contrats-desistes-list" />
                                                        <input type="hidden" name="idContrat" value="<?= $contrat->id() ?>" />
                                                         
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- activation box end --> 
                                        <!-- delete box begin-->
                                        <div id="deleteContrat<?= $contrat->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Supprimer le contrat </h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal loginFrm" action="../controller/ContratActionController.php" method="post">
                                                    <p><strong>Êtes-vous sûr de vouloir supprimer ce contrat ? <span class="dangerous-action">Attention cette action est irréversible !</span></strong></p>
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="action" value="delete" />
                                                        <input type="hidden" name="source" value="contrats-desistes-list" />
                                                        <input type="hidden" name="idContrat" value="<?= $contrat->id() ?>" />
                                                         
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
                                </div><!-- END DIV SCROLLER -->
                            </div>
                        </div>
                        <!-- END Terrain TABLE PORTLET-->
                    </div>
                </div>
                <!-- END PAGE CONTENT -->
            </div>
            <!-- END PAGE CONTAINER-->
        </div>
        <!-- END PAGE -->
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>       
    <script>
        jQuery(document).ready(function() {         
            // initiate layout and plugins
            App.setPage("table_managed");
            App.init();
        });
        $('.currency').on('change',function(){
            if( $(this).val()!=="DH"){
                $('.tauxDeChange').show()
            }
            else{
                $('.tauxDeChange').hide()
            }
        });
    </script>
</body>
<!-- END BODY -->
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>