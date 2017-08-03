<?php 
    include('../app/classLoad.php');
    session_start();
    if( isset($_SESSION['userMerlaTrav']) ){
        //les sources
        $projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
        $appartementManager = new AppartementManager(PDOFactory::getMysqlConnection());
        $locauxManager = new LocauxManager(PDOFactory::getMysqlConnection());
        $contratManager = new ContratManager(PDOFactory::getMysqlConnection());
        $clientManager = new ClientManager(PDOFactory::getMysqlConnection());
        $appartements = $appartementManager->getAppartementsNonVendu();
        $appartementsRevendre = $contratManager->getAppartementsRevendre();
        $locaux = $locauxManager->getLocauxNonVendu();
        $locauxRevendre = $contratManager->getLocauxRevendre();
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
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
                            <li><i class="icon-home"></i> <a href="projets.php"><strong>États Immobilière</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid"> 
                    <div class="span12">
                        <?php if ( isset($_SESSION['appartement-action-message']) and isset($_SESSION['appartement-type-message'])){ $message = $_SESSION['appartement-action-message']; $typeMessage = $_SESSION['appartement-type-message']; ?>
                        <div class="alert alert-<?= $typeMessage ?>"><button class="close" data-dismiss="alert"></button><?= $message ?></div>
                        <?php } unset($_SESSION['appartement-action-message']); unset($_SESSION['appartement-type-message']); ?>
                        <input style="margin-top:5px;" class="m-wrap stay-away btn-fixed-width-big" name="criteria" id="criteria" type="text" placeholder="Moteur de recherche..." />
                        <div class="portlet box light-grey properties">
                            <div class="portlet-title">
                                <h4>Liste des appartements</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="clearfix">
                                    <div class="btn-group pull-right">
                                        <a target="_blank" class="btn green" href="../controller/StatusAppartements.php" data-toggle="modal">
                                            <i class="icon-print"></i>
                                             État Appartements
                                        </a>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="sample_2">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%"></th>
                                            <th style="width: 5%">Code</th>
                                            <th style="width: 15%">Projet</th>
                                            <th style="width: 5%">Niv</th>
                                            <th class="hidden-phone" style="width: 10%">Superficie</th>
                                            <th class="hidden-phone" style="width: 10%">Façade</th>
                                            <th class="hidden-phone" style="width: 25%">Nbr.Pièces</th>
                                            <th class="hidden-phone" style="width: 5%">Cave</th>
                                            <th style="width: 10%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($appartements as $appartement){
                                        ?>      
                                        <tr class="properties">
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn green mini dropdown-toggle" data-toggle="dropdown"><i class="icon-exclamation-sign"></i></a>
                                                    <ul class="dropdown-menu info-dropdown">
                                                        <?php
                                                        if( $appartement->status()=="R&eacute;serv&eacute;" ){
                                                            if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {
                                                        ?>
                                                        <li>
                                                            <a href="#updateClient<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
                                                                Pour : <strong><?= $appartement->par() ?></strong>
                                                            </a>
                                                        </li>
                                                        <?php
                                                            }
                                                            else{
                                                        ?>
                                                        <li>
                                                            <a>
                                                                Pour : <strong><?= $appartement->par() ?></strong>
                                                            </a>
                                                        </li>    
                                                        <?php        
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a style="width: 50px" class="btn mini dropdown-toggle" href="#" title="Prix : <?= number_format($appartement->prix(), 2, ',', ' ') ?> DH" data-toggle="dropdown">
                                                        <?= $appartement->nom() ?> 
                                                        <i class="icon-angle-down"></i>
                                                    </a>
                                                    <?php
                                                    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ||
                                                        $_SESSION['userMerlaTrav']->profil()=="manager"
                                                    ) {
                                                    ?>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="appartement-detail.php?idAppartement=<?= $appartement->id() ?>&idProjet=<?= $appartement->idProjet() ?>">
                                                                Fiche descriptif
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td><?= $projetManager->getProjetById($appartement->idProjet())->nom() ?></td>
                                            <td><?= $appartement->niveau() ?></td>
                                            <td class="hidden-phone"><?= $appartement->superficie() ?> m<sup>2</sup></td>
                                            <td class="hidden-phone"><?= $appartement->facade() ?></td>
                                            <td class="hidden-phone"><?= $appartement->nombrePiece() ?> pièces</td>
                                            <td class="hidden-phone">
                                                <?php if($appartement->cave()=="Sans"){ ?><a class="btn mini black">Sans</a><?php } ?>
                                                <?php if($appartement->cave()=="Avec"){ ?><a class="btn mini blue">Avec</a><?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ( $appartement->status()=="Disponible" ) {
                                                    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {    
                                                ?>
                                                    <a class="btn mini green" href="#changeToReserve<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
                                                        Disponible
                                                    </a>
                                                <?php 
                                                    }
                                                    else {
                                                ?>
                                                    <a class="btn mini green">
                                                        Disponible
                                                    </a>
                                                <?php        
                                                    } 
                                                }    
                                                ?>
                                                <?php 
                                                if ( $appartement->status()=="R&eacute;serv&eacute;" ) {
                                                     if ( $_SESSION['userMerlaTrav']->profil()=="admin" ||
                                                          $_SESSION['userMerlaTrav']->profil()=="manager"
                                                     ) {   
                                                ?>
                                                    <a class="btn mini red" href="#changeToDisponible<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>">
                                                        Réservé
                                                    </a>
                                                <?php
                                                     }
                                                     else {
                                                ?>
                                                    <a class="btn mini red">
                                                        Réservé
                                                    </a>
                                                <?php         
                                                     }
                                                } 
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }//end of loop
                                        ?>
                                        <?php
                                        foreach($appartementsRevendre as $contrat){
                                            $appartement = $appartementManager->getAppartementById($contrat->idBien());
                                        ?>      
                                        <tr class="properties">
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn green mini dropdown-toggle" data-toggle="dropdown"><i class="icon-exclamation-sign"></i></a>
                                                    <ul class="dropdown-menu info-dropdown">
                                                        <li>
                                                            <a>
                                                                Pour : <strong><?= $clientManager->getClientById($contrat->idClient())->nom() ?></strong> 
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a>
                                                                Montant de Revente : <strong><?= number_format($appartement->montantRevente(), 2, ',', ' ') ?> DH</strong> 
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>    
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a style="width: 50px" class="btn mini dropdown-toggle" href="#" title="Prix : <?= number_format($appartement->prix(), 2, ',', ' ') ?> DH" data-toggle="dropdown">
                                                        <?= $appartement->nom() ?> 
                                                        <i class="icon-angle-down"></i>
                                                    </a>
                                                    <?php
                                                    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ||
                                                        $_SESSION['userMerlaTrav']->profil()=="manager") {
                                                    ?>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="appartement-detail.php?idAppartement=<?= $appartement->id() ?>&idProjet=<?= $appartement->idProjet() ?>">
                                                                Fiche descriptif
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td><?= $projetManager->getProjetById($appartement->idProjet())->nom() ?></td>
                                            <td><?= $appartement->niveau() ?></td>
                                            <td class="hidden-phone"><?= $appartement->superficie() ?> m<sup>2</sup></td>
                                            <td class="hidden-phone"><?= $appartement->facade() ?></td>
                                            <td class="hidden-phone"><?= $appartement->nombrePiece() ?> pièces</td>
                                            <td class="hidden-phone">
                                                <?php if($appartement->cave()=="Sans"){ ?><a class="btn mini black">Sans</a><?php } ?>
                                                <?php if($appartement->cave()=="Avec"){ ?><a class="btn mini blue">Avec</a><?php } ?>
                                            </td>
                                            <td><a href="#updateMontantReventeAppartement<?= $appartement->id() ?>" data-toggle="modal" data-id="<?= $appartement->id() ?>" class="btn mini black">Revendre</a></td>
                                        </tr>
                                        <!-- updateMontantReventeAppartement box end -->
                                        <div id="updateMontantReventeAppartement<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier le prix de revente de l'appartement </h3>
                                            </div>
                                            <form class="form-horizontal loginFrm" action="../controller/AppartementActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir modifier le prix de cet appartement ?</p>
                                                    <div class="control-group">
                                                        <label class="control-label">Montant Revente</label>
                                                        <div class="controls">
                                                            <input type="text" name="montantRevente" value="<?= $appartement->montantRevente() ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <input type="hidden" name="action" value="updateMontantReventeApartement" />
                                                        <input type="hidden" name="source" value="properties-status" />
                                                        <input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="42" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- updateMontantReventeAppartement box end -->
                                        <?php
                                        }//end of loop
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="portlet box light-grey">
                            <div class="portlet-title">
                                <h4>Liste des locaux commerciaux</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="clearfix">
                                    <div class="btn-group pull-right">
                                        <a target="_blank" class="btn green" href="../controller/StatusLocaux.php" data-toggle="modal">
                                            <i class="icon-print"></i>
                                             État Locaux Commerciaux
                                        </a>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th style="width:5%"></th>
                                            <th style="width:15%">Code</th>
                                            <th style="width:20%">Projet</th>
                                            <th class="hidden-phone" style="width:20%">Superficie</th>
                                            <th class="hidden-phone" style="width:20%">Façade</th>
                                            <th class="hidden-phone" style="width:5%">Mezzanine</th>
                                            <th style="width:15%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($locaux as $locau){
                                        ?>      
                                        <tr class="properties">
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn black dropdown-toggle" data-toggle="dropdown"><i class="icon-exclamation-sign"></i></a>
                                                    <ul class="dropdown-menu info-dropdown">
                                                        <?php
                                                        if( $locau->status() == "R&eacute;serv&eacute;" ){
                                                            if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {  
                                                        ?>
                                                        <li>
                                                            <a href="#updateClient<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>">
                                                                Pour : <?= $locau->par() ?>
                                                            </a>
                                                        </li>
                                                        <?php
                                                            }
                                                            else{
                                                        ?>
                                                            <li>
                                                                <a>
                                                                    Pour : <?= $locau->par() ?>
                                                                </a>
                                                            <li>    
                                                        <?php        
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a style="width: 50px" class="btn mini dropdown-toggle" href="#" title="Prix : <?= number_format($locau->prix(), 2, ',', ' ') ?> DH" data-toggle="dropdown">
                                                        <?= $locau->nom() ?> 
                                                        <i class="icon-angle-down"></i>
                                                    </a>
                                                    <?php
                                                    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ||
                                                        $_SESSION['userMerlaTrav']->profil()=="manager"
                                                    ) {    
                                                    ?>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="locaux-detail.php?idLocaux=<?= $locau->id() ?>&idProjet=<?= $locau->idProjet() ?>">
                                                                Fiche descriptif
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <?php
                                                    }    
                                                    ?>
                                                </div>
                                            </td>
                                            <td><?= $projetManager->getProjetById($locau->idProjet())->nom() ?></td>
                                            <td class="hidden-phone"><?= $locau->superficie() ?></td>
                                            <td class="hidden-phone"><?= $locau->facade() ?></td>
                                            <td class="hidden-phone">
                                                <?php if($locau->mezzanine()=="Sans"){ ?><a class="btn mini black"><?= $locau->mezzanine() ?></a><?php } ?>
                                                <?php if($locau->mezzanine()=="Avec"){ ?><a class="btn mini blue"><?= $locau->mezzanine() ?></a><?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if($locau->status()=="R&eacute;serv&eacute;"){ 
                                                    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {    
                                                ?>
                                                    <a class="btn mini red" href="#changeToDisponible<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>">
                                                        Réservé
                                                    </a>
                                                <?php 
                                                    }
                                                    else{
                                                ?>
                                                    <a class="btn mini red" >Réservé</a>
                                                <?php        
                                                    }
                                                } 
                                                ?>
                                                <?php 
                                                if($locau->status()=="Disponible"){ 
                                                    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {  
                                                ?>
                                                    <a class="btn mini green" href="#changeToReserve<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>">
                                                        Disponible
                                                    </a>
                                                <?php 
                                                    }
                                                    else{
                                                ?>
                                                    <a class="btn mini green">Disponible</a>
                                                <?php
                                                    }
                                                }         
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }//end of loop
                                        ?>
                                        <?php
                                        foreach($locauxRevendre as $contrat){
                                            $locau = $locauxManager->getLocauxById($contrat->idBien());
                                        ?>      
                                        <tr class="properties">
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn mini green dropdown-toggle" data-toggle="dropdown"><i class="icon-exclamation-sign"></i></a>
                                                    <ul class="dropdown-menu info-dropdown">
                                                        <li>   
                                                            <a>
                                                                Pour : <strong><?= $clientManager->getClientById($contrat->idClient())->nom() ?></strong> 
                                                            </a>
                                                        </li>
                                                        <li>   
                                                            <a>
                                                                Montant de Revente : <strong><?= number_format($locau->montantRevente(), 2, ',', ' ') ?> DH</strong> 
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>   
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a style="width: 100px" class="btn mini dropdown-toggle" href="#" title="Prix : <?= number_format($locau->prix(), 2, ',', ' ') ?> DH" data-toggle="dropdown">
                                                        <?= $locau->nom() ?> 
                                                        <i class="icon-angle-down"></i>
                                                    </a>
                                                    <?php
                                                    if ( $_SESSION['userMerlaTrav']->profil()=="admin" ) {    
                                                    ?>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="locaux-detail.php?idLocaux=<?= $locau->id() ?>&idProjet=<?= $locau->idProjet() ?>">
                                                                Fiche descriptif
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <?php
                                                    }    
                                                    ?>
                                                </div>
                                            </td>
                                            <td><?= $projetManager->getProjetById($locau->idProjet())->nom() ?></td>
                                            <td class="hidden-phone"><?= $locau->superficie() ?></td>
                                            <td class="hidden-phone"><?= $locau->facade() ?></td>
                                            <td class="hidden-phone">
                                                <?php if($locau->mezzanine()=="Sans"){ ?><a class="btn mini black"><?= $locau->mezzanine() ?></a><?php } ?>
                                                <?php if($locau->mezzanine()=="Avec"){ ?><a class="btn mini blue"><?= $locau->mezzanine() ?></a><?php } ?>
                                            </td>
                                            <td><a href="#updateMontantReventeLocaux<?= $locau->id() ?>" data-toggle="modal" data-id="<?= $locau->id() ?>" class="btn mini black">Revendre</a></td>
                                        </tr>
                                        <!-- updateMontantReventeAppartement box end -->
                                        <div id="updateMontantReventeLocaux<?= $locau->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier le prix de revente du local commercial </h3>
                                            </div>
                                            <form class="form-horizontal loginFrm" action="../controller/LocauxActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir modifier le prix de ce local commercial ?</p>
                                                    <div class="control-group">
                                                        <label class="control-label">Montant Revente</label>
                                                        <div class="controls">
                                                            <input type="text" name="montantRevente" value="<?= $locau->montantRevente() ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <input type="hidden" name="action" value="updateMontantReventeLocaux" />
                                                        <input type="hidden" name="source" value="properties-status" />
                                                        <input type="hidden" name="idLocaux" value="<?= $locau->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="42" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- updateMontantReventeLocaux box end -->
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
        jQuery(document).ready(function() { App.setPage("table_managed"); App.init(); });
        $('.properties').show();
        $('#criteria').keyup(function(){
            $('.properties').hide();
            var txt = $('#criteria').val();
            $('.properties').each(function(){
                if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                    $(this).show();
                }
            });
        });
        $('#status').on('change',function(){
            if( $(this).val()!=="Disponible"){
            $("#par").show()
            }
            else{
            $("#par").hide()
            }
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