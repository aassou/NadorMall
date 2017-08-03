<?php
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav']) 
    and ($_SESSION['userMerlaTrav']->profil() == "admin" OR $_SESSION['userMerlaTrav']->profil() == "consultant") ){
        //classManagers
        $projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
        $chargeManager = new ChargeManager(PDOFactory::getMysqlConnection());
        $typeChargeManager = new TypeChargeManager(PDOFactory::getMysqlConnection());
        //
        if(isset($_GET['idProjet']) and 
        ($_GET['idProjet'] >=1 and $_GET['idProjet'] <= $projetManager->getLastId()) ){
            $idProjet = $_GET['idProjet'];
            $charges = $chargeManager->getChargesByGroupByIdProjet($idProjet);
            $typeCharges = $typeChargeManager->getTypeCharges();
            $projet = $projetManager->getProjetById($idProjet);
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
        <?php include('../include/top-menu.php') ?>
    </div>
    <div class="page-container row-fluid sidebar-closed">
        <?php include('../include/sidebar.php') ?>
        <div class="page-content">            
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="breadcrumb">
                            <li><i class="icon-dashboard"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-briefcase"></i> <a href="projets.php">Gestion des projets</a><i class="icon-angle-right"></i></li>
                            <li><a href="projet-details.php?idProjet=<?= $idProjet ?>">Projet <strong><?= $projet->nom() ?></strong></a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-bar-chart"></i> <a><strong>Gestion des charges</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <!-- addCharge box begin-->
                        <div id="addCharge" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter une nouvelle charge </h3>
                            </div>
                            <form class="form-horizontal" action="../controller/ChargeActionController.php" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="control-group">
                                        <label class="control-label">Type Charge</label>
                                        <div class="controls">
                                            <select name="type">
                                                <?php foreach($typeCharges as $type){ ?>
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
                                    <div class="control-group">
                                        <label class="control-label">Société</label>
                                        <div class="controls">
                                            <input type="text" name="societe" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                            <input type="hidden" name="action" value="add" />
                                            <input type="hidden" name="source" value="projet-charges-grouped" />        
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
                            <form class="form-horizontal" action="../controller/TypeChargeActionController.php" method="post">
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
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- addTypeCharge box end -->
                        <!--**************************** CHARGES BEGIN ****************************-->
                        <div class="row-fluid">
                            <div class="input-box autocomplet_container">
                                <a style="margin-top:5px;" href="#printCharges" class="btn black btn-fixed-width-big" data-toggle="modal">
                                    <i class="icon-print"></i>&nbsp;Imprimer liste des charges
                                </a>
                                <?php 
                                if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
                                ?>
                                <a style="margin-top:5px;" href="#addTypeCharge" data-toggle="modal" class="btn blue btn-fixed-width-big">
                                    <i class="icon-plus-sign"></i>&nbsp;Type Charge
                                </a>
                                <a style="margin-top:5px;" href="#addCharge" data-toggle="modal" class="btn green btn-fixed-width-big">
                                    <i class="icon-plus-sign"></i>&nbsp;Nouvelle Charge
                                </a>
                                <?php 
                                }
                                ?>
                            </div>
                            <input style="margin-top:5px; width:98%" class="m-wrap" name="type" id="type" type="text" placeholder="Type..." />
                        </div>
                        <!-- printCharge box begin-->
                        <div id="printCharges" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Imprimer Liste des Charges </h3>
                            </div>
                            <form class="form-horizontal" action="../controller/ChargePrintController.php" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <p><strong>Séléctionner les charges à imprimer</strong></p>
                                    <div class="control-group">
                                      <label class="control-label">Imprimer</label>
                                      <div class="controls">
                                         <label class="radio">
                                             <div class="radio" id="toutes">
                                                 <span>
                                                     <input type="radio" class="criteriaPrint" name="criteria" value="toutesCharges" style="opacity: 0;">
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
                                        <label class="control-label">Type Charge</label>
                                        <div class="controls">
                                            <select class="m-wrap" name="type">
                                                <?php foreach($typeCharges as $type){ ?>
                                                    <option value="<?= $type->nom() ?>"><?= $type->nom() ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- printCharge box end -->
                        <?php if ( isset($_SESSION['charge-action-message']) and isset($_SESSION['charge-type-message'])){ $message = $_SESSION['charge-action-message']; $typeMessage = $_SESSION['charge-type-message']; ?>
                        <div class="alert alert-<?= $typeMessage ?>"><button class="close" data-dismiss="alert"></button><?= $message ?></div>
                        <?php } unset($_SESSION['charge-action-message']); unset($_SESSION['charge-type-message']); ?>
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 50%"><strong>Total des charges</strong></th>
                                    <th style="width: 50%"><a><strong><?= number_format($chargeManager->getTotalByIdProjet($idProjet), 2, ',', ' ') ?>&nbsp;DH</strong></a></th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="portlet charges">
                            <div class="portlet-body">
                                <div class="scroller" data-height="500px" data-always-visible="1"><!-- BEGIN DIV SCROLLER -->
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 70%">Type</th>
                                            <th class="hidden-phone" style="width: 30%">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($charges as $charge){
                                        ?>      
                                        <tr class="charges">
                                            <td>
                                                <a class="btn mini btn-fixed-width-big btn-fixed-height" href="projet-charges-type.php?idProjet=<?= $idProjet ?>&type=<?= $charge->type() ?>">
                                                    Charges <?= $typeChargeManager->getTypeChargeById($charge->type())->nom() ?> 
                                                </a>
                                            </td>
                                            <td class="hidden-phone"><?= number_format($charge->montant(), 2, ',', ' ') ?></td>
                                        </tr>
                                        <!-- updateCharge box begin-->
                                        <div id="updateCharge<?= $charge->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier Info Charge </h3>
                                            </div>
                                            <form class="form-horizontal" action="../controller/ChargeActionController.php" method="post">
                                                <div class="modal-body">
                                                    <div class="control-group">
                                                        <label class="control-label">Type Charge</label>
                                                        <div class="controls">
                                                            <select name="type">
                                                                <option value="<?= $charge->type() ?>"><?= $charge->type() ?></option>
                                                                <option disabled="disabled">-------------</option>
                                                                <?php foreach($typeCharges as $type){ ?>
                                                                    <option value="<?= $type->nom() ?>"><?= $type->nom() ?></option>
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
                                                    <div class="control-group">
                                                        <label class="control-label">Société</label>
                                                        <div class="controls">
                                                            <input type="text" name="societe" value="<?= $charge->societe() ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <input type="hidden" name="idCharge" value="<?= $charge->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <input type="hidden" name="action" value="update" />
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
                                            <form class="form-horizontal loginFrm" action="../controller/ChargeActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer cette charge <?= $charge->type() ?> ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="idCharge" value="<?= $charge->id() ?>" />
                                                        <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                        <input type="hidden" name="action" value="delete" />
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
                                        <tr class="hidden-phone">
                                            <td></td>
                                            <th><strong>Total des charges</strong></th>
                                        </tr>
                                        <tr class="hidden-phone">
                                            <td></td>
                                            <th><strong><a><?= number_format($chargeManager->getTotalByIdProjet($idProjet), 2, ',', ' ') ?>&nbsp;DH</a></strong></th>
                                        </tr>
                                    </tbody>
                                </table>
                                </div> 
                            </div>
                        </div>
                        <!--**************************** CHARGES END ****************************-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>
    <script type="text/javascript" src="script.js"></script>        
    <script>
        jQuery(document).ready(function() { App.init(); });
        $('.charges').show();
        $('#type').keyup(function(){
            $('.charges').hide();
           var txt = $('#type').val();
           $('.charges').each(function(){
               if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                   $(this).show();
               }
            });
        }); 
        $('#designation').keyup(function(){
            $('.charges').hide();
           var txt = $('#designation').val();
           $('.charges').each(function(){
               if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                   $(this).show();
               }
            });
        }); 
        $('#societe').keyup(function(){
            $('.charges').hide();
           var txt = $('#societe').val();
           $('.charges').each(function(){
               if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                   $(this).show();
               }
            });
        });
        $('.criteriaPrint').on('change',function(){
            if( $(this).val()==="toutesCharges"){
            $("#showDateRange").hide()
            }
            else{
            $("#showDateRange").show()
            }
        }); 
    </script>
</body>
</html>
<?php
}
else if ( isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil() != "admin" ) {
    header('Location:dashboard.php');
}
else{
    header('Location:index.php');    
}
?>