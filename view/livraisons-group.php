<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    if( isset($_SESSION['userMerlaTrav']) ){
        //classManagers
        $projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
        $fournisseurManager = new FournisseurManager(PDOFactory::getMysqlConnection());
        $livraisonManager = new LivraisonManager(PDOFactory::getMysqlConnection());
        $livraisonDetailManager = new LivraisonDetailManager(PDOFactory::getMysqlConnection());
        $reglementsFournisseurManager = new ReglementFournisseurManager(PDOFactory::getMysqlConnection());
        //classes and vars
        $projets = $projetManager->getProjets();
        $fournisseurs = $fournisseurManager->getFournisseurs();
        $projet = $projetManager->getProjets();
        $livraisonNumber = 0;
        $totalReglement = 0;
        $totalLivraison = 0;
        $titreLivraison ="Liste de toutes les livraisons";
        $hrefLivraisonBilanPrintController = "controller/Livraison2BilanPrintController.php";
        $livraisonListDeleteLink = "";
        $titreLivraison ="Société Annahda";
        $livraisonNumber = $livraisonManager->getLivraisonNumber();
        if($livraisonNumber != 0){
            $livraisons = $livraisonManager->getLivraisonsByGroup();
            $totalReglement = $reglementsFournisseurManager->getTotalReglement();
            $totalLivraison = $livraisonDetailManager->getTotalLivraison(); 
            $hrefLivraisonBilanPrintController = "controller/Livraison2BilanPrintController.php?societe=1";
        }
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
                        <h3 class="page-title">Gestion des livraisons</h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-truck"></i>
                                <a>Gestion des livraisons</a>
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <div class="row-fluid">
                    <div class="span12">
                        <?php
                        if ( 
                            $_SESSION['userMerlaTrav']->profil() == "admin" ||
                            $_SESSION['userMerlaTrav']->profil() == "manager" ||  
                            $_SESSION['userMerlaTrav']->profil() == "user"
                            ) {
                        ?>
                        <div class="row-fluid get-down">
                            <?php
                            if ( 
                                $_SESSION['userMerlaTrav']->profil() == "admin" ||
                                $_SESSION['userMerlaTrav']->profil() == "manager" 
                                ) {
                            ?>
                            <a href="#addReglement" data-toggle="modal" class="btn black" style="width:266px; margin-top:5px">
                                <i class="icon-plus-sign"></i>&nbsp;Nouveau Réglement
                            </a>
                            <?php
                            }
                            ?>
                            <a href="#addFournisseur" data-toggle="modal" class="btn blue" style="width:266px; margin-top:5px">
                                <i class="icon-plus-sign"></i>&nbsp;Nouveau Fournisseur
                            </a>
                            <a href="#addLivraison" data-toggle="modal" class="btn green" style="width:266px; margin-top:5px">
                                <i class="icon-plus-sign"></i>&nbsp;Nouvelle Livraison
                            </a>
                            <a target="_blank" href="<?= $hrefLivraisonBilanPrintController ?>" class="btn brown" style="width:267px; margin-top:5px">
                                <i class="icon-print"></i>&nbsp;Imprimer Bilan
                            </a>
                        </div>
                        <?php
                        }
                        ?>
                        <!-- addFournisseur box begin-->
                        <div id="addFournisseur" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter un nouveau fournisseur </h3>
                            </div>
                            <form class="form-horizontal" action="../controller/FournisseurActionController.php" method="post">
                                <div class="modal-body">
                                    <div class="control-group">
                                        <label class="control-label">Nom</label>
                                        <div class="controls">
                                            <input required="required" type="text" name="nom" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Adresse</label>
                                        <div class="controls">
                                            <input type="text" name="adresse" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Tél.1</label>
                                        <div class="controls">
                                            <input type="text" name="telephone1" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Tél.2</label>
                                        <div class="controls">
                                            <input type="text" name="telephone2" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Fax</label>
                                        <div class="controls">
                                            <input type="text" name="fax" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Email</label>
                                        <div class="controls">
                                            <input type="text" name="email" value="" />
                                        </div>  
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <div class="controls">  
                                            <input type="hidden" name="action" value="add" />
                                            <input type="hidden" name="source" value="livraisons-group" />
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- addFournisseur box end -->
                        <!-- addLivraison box begin-->
                        <div id="addLivraison" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter une nouvelle livraison </h3>
                            </div>
                            <form id="addLivraisonForm" class="form-horizontal" action="../controller/LivraisonActionController.php" method="post">
                                <div class="modal-body">
                                    <div class="control-group">
                                        <label class="control-label">Fournisseur</label>
                                        <div class="controls">
                                            <select name="idFournisseur">
                                                <?php foreach($fournisseurs as $fournisseur){ ?>
                                                <option value="<?= $fournisseur->id() ?>"><?= $fournisseur->nom() ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Projet</label>
                                        <div class="controls">
                                            <select name="idProjet">
                                                <option value="0">Plusieurs Projets</option>
                                                <?php foreach($projets as $projet){ ?>
                                                <option value="<?= $projet->id() ?>"><?= $projet->nom() ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Date Livraison</label>
                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                            <input name="dateLivraison" id="dateLivraison" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                         </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">N° BL</label>
                                        <div class="controls">
                                            <input required="required" id="libelle" type="text" name="libelle" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Désignation</label>
                                        <div class="controls">
                                            <input id="designation" type="text" name="designation" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <div class="controls">  
                                            <input type="hidden" name="action" value="add" />
                                            <input type="hidden" name="source" value="livraisons-group" />    
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>    
                            </form>
                        </div>
                        <!-- addLivraison box end -->
                        <!-- addReglement box begin-->
                        <div id="addReglement" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter un nouveau réglement </h3>
                            </div>
                            <form id="addReglementForm" class="form-horizontal" action="../controller/ReglementFournisseurActionController.php" method="post">
                                <div class="modal-body">
                                    <div class="control-group">
                                        <label class="control-label">Fournisseur</label>
                                        <div class="controls">
                                            <select name="idFournisseur">
                                                <?php foreach($fournisseurs as $fournisseur){ ?>
                                                <option value="<?= $fournisseur->id() ?>"><?= $fournisseur->nom() ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Désignation</label>
                                        <div class="controls">
                                            <select name="idProjet">
                                                <option value="0">Plusieurs Projets</option>
                                                <?php foreach($projets as $projet){ ?>
                                                <option value="<?= $projet->id() ?>"><?= $projet->nom() ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Date Réglement</label>
                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                            <input name="dateReglement" id="dateReglement" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                         </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Montant</label>
                                        <div class="controls">
                                            <input required="required" id="montant" type="text" name="montant" value="" />
                                        </div>  
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Mode de paiement</label>
                                        <div class="controls">
                                            <select id="modePaiement" name="modePaiement" style="width: 220px" class="m-wrap">
                                                <option value="Especes">Especes</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Versement">Versement</option>
                                                <option value="Virement">Virement</option>
                                                <option value="LetterDeChange">Lettre De Change</option>
                                                <option value="Remise">Remise</option>
                                            </select>
                                        </div>  
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span6">
                                          <div class="control-group">
                                             <label class="control-label">Numéro Operation</label>
                                             <div class="controls">
                                                <input type="text" required="required" id="numeroOperation" name="numeroCheque">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="hidden" name="action" value="add" />
                                            <input type="hidden" name="source" value="livraisons-group" />  
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- addReglement box end -->
                        <div class="row-fluid">
                            <div class="input-box">
                                <input style="width:98%" class="m-wrap" name="provider" id="provider" type="text" placeholder="Fournisseur..."></input>
                            </div>
                        </div>
                        <!-- BEGIN Terrain TABLE PORTLET-->
                         <?php
                         if( isset($_SESSION['livraison-action-message'])
                         and isset($_SESSION['livraison-type-message']) ){ 
                            $message = $_SESSION['livraison-action-message'];
                            $typeMessage = $_SESSION['livraison-type-message'];    
                         ?>
                            <div class="alert alert-<?= $typeMessage ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $message ?>     
                            </div>
                         <?php } 
                            unset($_SESSION['livraison-action-message']);
                            unset($_SESSION['livraison-type-message']);
                         ?>
                         <?php
                         if( isset($_SESSION['reglement-action-message'])
                         and isset($_SESSION['reglement-type-message']) ){ 
                            $message = $_SESSION['reglement-action-message'];
                            $typeMessage = $_SESSION['reglement-type-message'];    
                         ?>
                            <div class="alert alert-<?= $typeMessage ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $message ?>     
                            </div>
                         <?php } 
                            unset($_SESSION['reglement-action-message']);
                            unset($_SESSION['reglement-type-message']);
                         ?>
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <tbody>
                                <tr>
                                    <th class="hidden-phone" style="width: 15%"><strong>Σ Livraisons</strong></th>
                                    <th class="hidden-phone" style="width: 15%"><strong><a><?= number_format($totalLivraison, 2, ',', ' ') ?>&nbsp;DH</a></strong></th>
                                    <th class="hidden-phone" style="width: 15%"><strong>Σ Réglements</strong></th>
                                    <th class="hidden-phone" style="width: 15%"><strong><a><?= number_format($totalReglement, 2, ',', ' ') ?>&nbsp;DH</a></strong></th>
                                    <th style="width: 15%"><strong>Σ Solde</strong></th>
                                    <th style="width: 15%"><strong><a><?= number_format($totalLivraison-$totalReglement, 2, ',', ' ') ?>&nbsp;DH</a></strong></th>
                                </tr>
                            </tbody>
                        </table>    
                        <div class="portlet livraisons">
                            <div class="portlet-body">
                                <div class="scroller" data-height="500px" data-always-visible="1"><!-- BEGIN DIV SCROLLER -->
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 30%">Fournisseur</th>
                                            <th style="width: 20%">Livraisons</th>
                                            <th style="width: 20%">Réglements</th>
                                            <th class="hidden-phone" style="width: 30%">Solde</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                        <?php
                                        if($livraisonNumber != 0){
                                        foreach($livraisons as $livraison){
                                            $livraisonsIds = $livraisonManager->getLivraisonIdsByIdFournisseur($livraison->idFournisseur());
                                            $totalDetailsLivraisons = 0;
                                            foreach($livraisonsIds as $idl){
                                                $totalDetailsLivraisons += $livraisonDetailManager->getTotalLivraisonByIdLivraison($idl);
                                            }
                                        ?>      
                                        <tr class="livraisons">
                                            <td>
                                                <div style="width: 150px">
                                                    <a><strong><?= $fournisseurManager->getFournisseurById($livraison->idFournisseur())->nom() ?></strong></a>
                                                </div>  
                                                <a href="livraisons-fournisseur-mois.php?idFournisseur=<?= $livraison->idFournisseur() ?>" style="width: 75px" class="btn blue mini">
                                                    Livraisons
                                                </a>
                                                <a href="reglements-fournisseur.php?idFournisseur=<?= $livraison->idFournisseur() ?>" style="width: 75px" class="btn green mini">
                                                    Réglements
                                                </a>
                                            </td>
                                            <td>
                                                <?= number_format($totalDetailsLivraisons, 2, ',', ' '); ?>
                                            </td>
                                            <td>
                                                <?= number_format($reglementsFournisseurManager->sommeReglementFournisseursByIdFournisseur($livraison->idFournisseur()), 2, ',', ' '); ?>
                                            </td>
                                            <td class="hidden-phone">
                                                <?= number_format( $totalDetailsLivraisons-$reglementsFournisseurManager->sommeReglementFournisseursByIdFournisseur($livraison->idFournisseur()), 2, ',', ' '); ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }//end of loop
                                        }//end of if
                                        ?>
                                    </tbody>
                                    <tr>
                                        <th></th>
                                        <th><strong>Σ Livraisons</strong></th>
                                        <th><strong>Σ Réglements</strong></th>
                                        <th class="hidden-phone"><strong>Σ Solde</strong></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th><strong><a><?= number_format($totalLivraison, 2, ',', ' ') ?>&nbsp;DH</a></strong></th>
                                        <th><strong><a><?= number_format($totalReglement, 2, ',', ' ') ?>&nbsp;DH</a></strong></th>
                                        <th class="hidden-phone"><strong><a><?= number_format($totalLivraison-$totalReglement, 2, ',', ' ') ?>&nbsp;DH</a></strong></th>
                                    </tr>
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
            //App.setPage("table_editable");
            App.init();
        });
        $('.livraisons').show();
        $('#provider').keyup(function(){
           $('.livraisons').hide();
           var txt = $('#provider').val();
           $('.livraisons').each(function(){
               if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                   $(this).show();
               }
            });
        });
        $("#addLivraisonForm").validate({
            rules:{
                libelle:{
                    required:true
                }
            },
            errorClass: "error-class",
            validClass: "valid-class"
        });
        $("#addReglementForm").validate({
            rules:{
                montant:{
                    number: true,
                    required:true
                },
                numeroOperation:{
                    number: true,
                    required:true
                }
            },
            errorClass: "error-class",
            validClass: "valid-class"
        });
    </script>
</body>
<!-- END BODY -->
</html>
<?php
}
/*else if(isset($_SESSION['userMerlaTrav']) and $_SESSION->profil()!="admin"){
    header('Location:dashboard.php');
}*/
else{
    header('Location:index.php');    
}
?>