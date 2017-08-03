<?php
    include('../app/classLoad.php');   
    session_start();
    if( isset($_SESSION['userMerlaTrav']) ){
        //class managers
        $projetManager     = new ProjetManager(PDOFactory::getMysqlConnection());
        $clientManager     = new ClientManager(PDOFactory::getMysqlConnection());
        $syndiqueManager   = new SyndiqueManager(PDOFactory::getMysqlConnection());
        $chargeManager     = new ChargeSyndiqueManager(PDOFactory::getMysqlConnection());
        $typeChargeManager = new TypeChargeSyndiqueManager(PDOFactory::getMysqlConnection());
        //obj and vars
        $idProjet                     = $_GET['idProjet'];
        $projet                       = $projetManager->getProjetById($idProjet);
        $projets                      = $projetManager->getProjets();    
        //syndique
        $syndiques                    = $syndiqueManager->getSyndiquesByIdProjet($idProjet);
        $totalSyndiquePaiementsClient = $syndiqueManager->getSyndiquesTotalByIdProjet($idProjet);
        //chargeSyndique
        $typeCharges          = $typeChargeManager->getTypeChargeSyndiques();
        $charges              = $chargeManager->getChargeSyndiquesByIdProjet($idProjet);
        $totalChargesSyndique = $chargeManager->getTotalByIdProjet($idProjet);
        //solde
        $solde = $totalSyndiquePaiementsClient - $totalChargesSyndique;
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
                            <li><i class="icon-briefcase"></i> <a href="projets.php">Gestion des projets</a><i class="icon-angle-right"></i></li>
                            <li><a href="projet-details.php?idProjet=<?= $idProjet ?>">Projet <strong><?= $projet->nom() ?></strong></a><i class="icon-angle-right"></i></li>
                            <li><a><strong>Gestion Syndique</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?php if ( isset($_SESSION['syndique-action-message']) and isset($_SESSION['syndique-type-message']) ){ $message = $_SESSION['syndique-action-message']; $typeMessage = $_SESSION['syndique-type-message']; ?>
                        <div class="alert alert-<?= $typeMessage ?>"><button class="close" data-dismiss="alert"></button><?= $message ?></div>
                        <?php } unset($_SESSION['syndique-action-message']); unset($_SESSION['syndique-type-message']); ?>
                        <!-- addCaisse box begin -->
                        <div id="addSyndique" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <h3>Ajouter Nouveau Paiement Syndique</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            </div>
                            <form class="form-horizontal" action="../controller/SyndiqueActionController.php" method="post">
                                <div class="modal-body">
                                    <div class="control-group autocomplet_container">
                                        <label class="control-label">Client</label>
                                        <div class="controls">
                                            <input required="required" type="text" id="nomClient" name="nom" class="m-wrap" onkeyup="autocompletClient()">
                                            <ul id="clientList"></ul>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Date Opération</label>
                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                            <input name="date" id="date" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                         </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Montant</label>
                                        <div class="controls">
                                            <input required="required" id="montant" type="text" name="montant" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <div class="controls">  
                                            <input type="hidden" name="action" value="add" />
                                            <input type="hidden" name="source" value="syndique" />
                                            <input type="hidden" name="status" value="Non Valide" />     
                                            <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                            <input type="hidden" id="idClient" name="idClient" />
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- addCaisse box end -->  
                        <!-- printBilanCaisse box begin -->
                        <div id="printCaisseBilan" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Imprimer Bilan de la Caisse </h3>
                            </div>
                            <form class="form-horizontal" action="../controller/SyndiqueBilanPrintController.php" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <p><strong>Séléctionner les opérations de caisse à imprimer</strong></p>
                                    <div class="control-group">
                                      <label class="control-label">Imprimer</label>
                                      <div class="controls">
                                         <label class="radio">
                                             <div class="radio" id="toutes">
                                                 <span>
                                                     <input type="radio" class="criteriaPrint" name="criteria" value="toutesCaisse" style="opacity: 0;">
                                                 </span>
                                             </div> Toute la liste
                                         </label>
                                         <label class="radio">
                                             <div class="radio" id="date">
                                                 <span class="checked">
                                                     <input type="radio" class="criteriaPrint" name="criteria" value="parDate" checked="" style="opacity: 0;">
                                                 </span>
                                             </div> Par Choix
                                         </label>  
                                      </div>
                                   </div>
                                   <div id="showDateRange">
                                    <div class="control-group">
                                        <label class="control-label">Date</label>
                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                           <input style="width:100px" name="dateFrom" id="dateFrom" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                           &nbsp;-&nbsp;
                                           <input style="width:100px" name="dateTo" id="dateTo" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Type Opération</label>
                                        <div class="controls">
                                            <select class="m-wrap" name="type">
                                                <option value="Toutes">Toutes les opérations</option>
                                                <option value="Entree">Entrées</option>
                                                <option value="Sortie">Sorties</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Destination</label>
                                        <div class="controls">
                                            <select name="destination">
                                                <option value="Tout">Tout</option>
                                                <option value="Bureau">Bureau</option>
                                                <?php foreach($projets as $projet){ ?>
                                                <option value="<?= $projet->nom() ?>"><?= $projet->nom() ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="hidden" name="societe" value="1" />
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- printBilanCaisse box end -->
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="width: 70%"><strong>Solde (Paiements Syndique - Charges )</strong></td>
                                    <td style="width: 30%"><strong><a><?= number_format($solde, 2, ',', ' ') ?>&nbsp;DH</a></strong></td>
                                </tr>
                            </thead>
                        </table>   
                       <div class="portlet box light-grey">
                            <div class="portlet-title">
                                <h4>Gestion Syndique</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="clearfix">
                                    <?php
                                    if ( 
                                        $_SESSION['userMerlaTrav']->profil() == "admin" ||
                                        $_SESSION['userMerlaTrav']->profil() == "manager" 
                                        ) {
                                    ?>
                                    <div class="btn-group pull-left">
                                        <a class="btn blue" href="#addSyndique" data-toggle="modal">
                                            <i class="icon-plus-sign"></i>
                                             Syndique
                                        </a>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="btn-group pull-right">
                                        <a class="btn green" href="../controller/SyndiqueBilanPrintController.php?idProjet=<?= $idProjet ?>" data-toggle="modal" target="_blank">
                                            <i class="icon-print"></i>
                                             Bilan de syndique
                                        </a>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td style="width: 70%"><strong>Total Paiements Clients</strong></td>
                                            <td style="width: 30%"><strong><a><?= number_format($totalSyndiquePaiementsClient, 2, ',', ' ') ?>&nbsp;DH</a></strong></td>
                                        </tr>
                                    </thead>
                                </table>    
                                <table class="table table-striped table-bordered table-hover" id="sample_2">
                                    <thead>
                                        <tr>
                                            <th class="hidden-phone" style="width:10%">Actions</th>
                                            <th style="width:40%">Client</th>
                                            <th style="width:20%">Date Paiement</th>
                                            <th style="width:20%">Montant</th>
                                            <?php if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) { ?>
                                            <th style="width:10%">Status</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($syndiques as $syndique){
                                            $nomClient = $clientManager->getClientById($syndique->idClient())->nom();
                                            $colorBtn = 'red';
                                            $statusToUpdate = "Valide";
                                            if ( $syndique->status() == "Valide" ) {
                                                $colorBtn = 'green';
                                                $statusToUpdate = "Non Valide";    
                                            }
                                        ?>      
                                        <tr class="odd gradeX">
                                            <td class="hidden-phone">
                                                <?php if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) { ?>
                                                <a class="btn mini red" href="#deleteSyndique<?= $syndique->id() ?>" data-toggle="modal" data-id="<?= $syndique->id() ?>" title="Supprimer"><i class="icon-remove"></i></a>
                                                <?php } ?>
                                                <?php
                                                if ( 
                                                    $_SESSION['userMerlaTrav']->profil() == "admin" ||
                                                    $_SESSION['userMerlaTrav']->profil() == "manager" 
                                                    ) {
                                                ?>
                                                <a class="btn mini green" href="#updateSyndique<?= $syndique->id() ?>" data-toggle="modal" data-id="<?= $syndique->id() ?>" title="Modifier"><i class="icon-refresh"></i></a>
                                                <a class="btn mini blue" href="../controller/QuittanceSyndiqueController.php?idSyndique=<?= $syndique->id() ?>" target="_blank" title="Quittance"><i class="icon-print"></i></a>
                                                <?php
                                                }
                                                ?>    
                                            </td>
                                            <td><?= $nomClient ?></td>
                                            <td><?= date('d/m/Y', strtotime($syndique->date())) ?></td>
                                            <td><?= number_format($syndique->montant(), 2, ',', ' ') ?></td>
                                            <?php if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) { ?>
                                            <td><a class="btn mini <?= $colorBtn ?>" href="#updateStatus<?= $syndique->id() ?>" data-toggle="modal" data-id=<?= $syndique->id() ?>><?= $syndique->status() ?></a></td>
                                            <?php } ?>
                                        </tr>
                                        <!-- updateSyndique box begin -->
                                        <div id="updateSyndique<?= $syndique->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <h3>Modifier Paiement Syndique</h3>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            </div>
                                            <form class="form-horizontal" action="../controller/SyndiqueActionController.php" method="post">
                                                <div class="modal-body">
                                                    <div class="control-group autocomplet_container">
                                                        <label class="control-label">Client</label>
                                                        <div class="controls">
                                                            <input required="required" type="text" id="nomClient" name="nom" class="m-wrap" value="<?= $nomClient ?>" onkeyup="autocompletClient()" />
                                                            <ul id="clientList"></ul>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Date Opération</label>
                                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                            <input name="date" id="date" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= $syndique->date() ?>" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                         </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Montant</label>
                                                        <div class="controls">
                                                            <input required="required" id="montant" type="text" name="montant" value="<?= $syndique->montant() ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <div class="controls">  
                                                            <input type="hidden" name="action" value="update" />
                                                            <input type="hidden" name="source" value="syndique" />        
                                                            <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                            <input type="hidden" name="idSyndique" value="<?= $syndique->id() ?>" />
                                                            <input type="hidden" name="status" value="<?= $syndique->status() ?>" />
                                                            <input type="hidden" id="idClient[]" name="idClient" value="<?= $syndique->idClient() ?>" />
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- updateSyndique box end -->  
                                        <!-- updateStatus box begins-->
                                        <div id="updateStatus<?= $syndique->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <h3>Modifier Status Paiement Syndique</h3>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            </div>
                                            <form class="form-horizontal" action="../controller/SyndiqueActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p class="dangerous-action"><strong>Êtes-vous sûr de vouloir changer le status de "<?= $syndique->status() ?>" vers "<?= $statusToUpdate ?>"?</strong></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <div class="controls">  
                                                            <input type="hidden" name="action" value="updateStatus" />
                                                            <input type="hidden" name="source" value="syndique" />
                                                            <input type="hidden" name="status" value="<?= $statusToUpdate ?>" />            
                                                            <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                            <input type="hidden" name="idSyndique" value="<?= $syndique->id() ?>" />
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- updateStatus box ends-->
                                        <!-- deleteSyndique box begin-->
                                        <div id="deleteSyndique<?= $syndique->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Supprimer Paiement Syndique</h3>
                                            </div>
                                            <form class="form-horizontal loginFrm" action="../controller/SyndiqueActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p class="dangerous-action">Êtes-vous sûr de vouloir supprimer ce paiement du client <strong><?= strtoupper($nomClient) ?></strong>, du montant <strong><?= number_format($syndique->montant(), 2, ',', ' ') ?>&nbsp;DH</strong> ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="action" value="delete" />
                                                        <input type="hidden" name="source" value="syndique" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <input type="hidden" name="idSyndique" value="<?= $syndique->id() ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- deleteSyndique box end --> 
                                        <?php
                                        }//end of loop
                                        ?>
                                    </tbody>
                                </table>
                                </div><!-- END DIV SCROLLER -->
                            </div>
                            <!-- Charges Syndique -->
                            <!-- addCharge box begin-->
                            <div id="addCharge" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h3>Ajouter une nouvelle charge </h3>
                                </div>
                                <form class="form-horizontal" action="../controller/ChargeSyndiqueActionController.php" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="control-group">
                                            <label class="control-label">Type Charge</label>
                                            <div class="controls">
                                                <select name="type">
                                                    <?php foreach($typeCharges as $type) { ?>
                                                    <option value="<?= $type->id() ?>"><?= $type->nom() ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Date Opération</label>
                                            <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                <input name="dateOperation" id="dateOperation" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                                <span class="add-on"><i class="icon-calendar"></i></span>
                                             </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Montant</label>
                                            <div class="controls">
                                                <input type="text" name="montant" value="" />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Désignation</label>
                                            <div class="controls">
                                                <input type="text" name="designation" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="control-group">
                                            <div class="controls">
                                                <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                <input type="hidden" name="action" value="add" />    
                                                <input type="hidden" name="source" value="syndique" />
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- addCharge box end -->
                            <!-- addTypeCharge box begin-->
                            <div id="addTypeCharge" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h3>Ajouter Nouveau Type Charge </h3>
                                </div>
                                <form class="form-horizontal" action="../controller/TypeChargeSyndiqueActionController.php" method="post">
                                    <div class="modal-body">
                                        <div class="control-group">
                                            <label class="control-label">Nom Type Charge</label>
                                            <div class="controls">
                                                <input type="text" name="nom" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="control-group">
                                            <div class="controls">
                                                <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                <input type="hidden" name="action" value="add" />
                                                <input type="hidden" name="source" value="syndique" />     
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- addTypeCharge box end -->
                            <div class="portlet box light-grey">
                                <div class="portlet-title">
                                    <h4>Gestion des charges syndique</h4>
                                    <div class="tools">
                                        <a href="javascript:;" class="reload"></a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="clearfix">
                                        <?php
                                        if ( 
                                            $_SESSION['userMerlaTrav']->profil() == "admin" ||
                                            $_SESSION['userMerlaTrav']->profil() == "manager" 
                                            ) {
                                        ?>
                                        <div class="btn-group pull-left">
                                            <a class="btn blue stay-away" href="#addTypeCharge" data-toggle="modal">
                                                <i class="icon-plus-sign"></i>
                                                 Type Charge
                                            </a>
                                        </div>
                                        <div class="btn-group pull-left">
                                            <a class="btn green" href="#addCharge" data-toggle="modal">
                                                <i class="icon-plus-sign"></i>
                                                 Charge
                                            </a>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <?php if(isset($_SESSION['typeCharge-action-message'])
                                    and isset($_SESSION['typeCharge-type-message'])){
                                        $message = $_SESSION['typeCharge-action-message'];
                                        $typeMessage = $_SESSION['typeCharge-type-message'];
                                    ?>
                                        <div class="alert alert-<?= $typeMessage ?>">
                                            <button class="close" data-dismiss="alert"></button>
                                            <?= $message ?>      
                                        </div>
                                    <?php } 
                                        unset($_SESSION['typeCharge-action-message']);
                                        unset($_SESSION['typeCharge-type-message']);
                                    ?>
                                    <?php if(isset($_SESSION['charge-action-message'])
                                    and isset($_SESSION['charge-type-message'])){
                                        $message = $_SESSION['charge-action-message'];
                                        $typeMessage = $_SESSION['charge-type-message'];
                                    ?>
                                        <div class="alert alert-<?= $typeMessage ?>">
                                            <button class="close" data-dismiss="alert"></button>
                                            <?= $message ?>      
                                        </div>
                                    <?php } 
                                        unset($_SESSION['charge-action-message']);
                                        unset($_SESSION['charge-type-message']);
                                    ?>
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <td style="width: 70%"><strong>Total Charges Syndique</strong></td>
                                                <td style="width: 30%"><strong><a><?= number_format($totalChargesSyndique, 2, ',' , ' ') ?>&nbsp;DH</a></strong></td>
                                            </tr>
                                        </thead>
                                    </table>
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <?php
                                                if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) { 
                                                ?>
                                                <th class="hidden-phone" style="width: 10%">Actions</th>
                                                <?php
                                                } 
                                                ?>
                                                <th style="width: 20%">Type</th>
                                                <th style="width: 20%">DateOp</th>
                                                <th style="width: 30%">Désignation</th>
                                                <th style="width: 20%">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($charges as $charge){
                                            ?>      
                                            <tr class="charges">
                                                <?php
                                                if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) { 
                                                ?>
                                                <td class="hidden-phone">
                                                    <a class="btn mini green" title="Modifier" href="#updateCharge<?= $charge->id();?>" data-toggle="modal" data-id="<?= $charge->id(); ?>"><i class="icon-refresh"></i></a>
                                                    <a class="btn mini red" title="Supprimer" href="#deleteCharge<?= $charge->id() ?>" data-toggle="modal" data-id="<?= $charge->id() ?>"><i class="icon-remove"></i></a>
                                                </td>
                                                <?php  
                                                } 
                                                ?>
                                                <td><?= $typeChargeManager->getTypeChargeSyndiqueById($charge->type())->nom() ?></td>
                                                <td class="hidden-phone"><?= date('d/m/Y', strtotime($charge->dateOperation())) ?></td>
                                                <td><?= $charge->designation() ?></td>
                                                <td><?= number_format($charge->montant(), 2, ',', ' ') ?></td>
                                            </tr>
                                            <!-- updateCharge box begin-->
                                            <div id="updateCharge<?= $charge->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h3>Modifier Info Charge </h3>
                                                </div>
                                                <form class="form-horizontal" action="../controller/ChargeSyndiqueActionController.php" method="post">
                                                    <div class="modal-body">
                                                        <div class="control-group">
                                                            <label class="control-label">Type Charge</label>
                                                            <div class="controls">
                                                                <select name="type">
                                                                    <option value="<?= $charge->type() ?>"><?= $typeChargeManager->getTypeChargeSyndiqueById($charge->type())->nom() ?></option>
                                                                    <option disabled="disabled">-------------</option>
                                                                    <?php foreach($typeCharges as $type){ ?>
                                                                        <option value="<?= $type->id() ?>"><?= $type->nom() ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Date Opération</label>
                                                            <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                                <input name="dateOperation" id="dateOperation" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= $charge->dateOperation() ?>" />
                                                                <span class="add-on"><i class="icon-calendar"></i></span>
                                                             </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Montant</label>
                                                            <div class="controls">
                                                                <input type="text" name="montant" value="<?= $charge->montant() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Désignation</label>
                                                            <div class="controls">
                                                                <input type="text" name="designation" value="<?= $charge->designation() ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="control-group">
                                                            <input type="hidden" name="idCharge" value="<?= $charge->id() ?>" />
                                                            <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                            <input type="hidden" name="action" value="update" />
                                                            <input type="hidden" name="source" value="syndique" />
                                                            <div class="controls">  
                                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- updateCharge box end -->            
                                            <!-- deleteCharge box begin-->
                                            <div id="deleteCharge<?= $charge->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h3>Supprimer la charge</h3>
                                                </div>
                                                <form class="form-horizontal loginFrm" action="../controller/ChargeSyndiqueActionController.php" method="post">
                                                    <div class="modal-body">
                                                        <p class="dangerous-action">Êtes-vous sûr de vouloir supprimer cette charge ?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="control-group">
                                                            <label class="right-label"></label>
                                                            <input type="hidden" name="idCharge" value="<?= $charge->id() ?>" />
                                                            <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                            <input type="hidden" name="action" value="delete" />
                                                            <input type="hidden" name="source" value="syndique" />
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- deleteCharge box end -->    
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
        </div>
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>        
    <script>
        jQuery(document).ready(function() { App.setPage("table_managed"); App.init();
            $('.criteriaPrint').on('change',function(){
                if( $(this).val()==="toutesCaisse"){
                $("#showDateRange").hide()
                }
                else{
                $("#showDateRange").show()
                }
            });
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