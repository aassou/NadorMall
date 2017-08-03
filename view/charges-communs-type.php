<?php
    include('../app/classLoad.php');    
    include('../lib/pagination.php'); 
    //classes loading end
    session_start();
    if( isset($_SESSION['userMerlaTrav']) 
    and ( $_SESSION['userMerlaTrav']->profil() == "admin" OR $_SESSION['userMerlaTrav']->profil()=="consultant") ){
        //classManagers
        $chargeManager = new ChargeCommunManager(PDOFactory::getMysqlConnection());
        $typeChargeManager = new TypeChargeCommunManager(PDOFactory::getMysqlConnection());
        //
        $typeCharge = $_GET['type'];
        $typeChargeObject = $typeChargeManager->getTypeChargeById($typeCharge);
        $idTypeCharge = $typeChargeObject->id();
        $nomTypeCharge = $typeChargeObject->nom();
        $charges = $chargeManager->getChargesByType($typeCharge);
        $total = number_format($chargeManager->getTotalByType($typeCharge), 2, ',', ' ');
        $typeCharges = $typeChargeManager->getTypeCharges();
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
                            Détails des charges communs</strong>
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-dashboard"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-bar-chart"></i>
                                <a href="charges-communs-grouped.php">Gestion des charges communs</a>
                                <i class="icon-angle-right"></i> 
                            </li>
                            <li>
                                <a>Détails des charges de <strong><?= $nomTypeCharge ?></strong></a> 
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <div class="row-fluid">
                    <div class="span12">
                        <!-- addCharge box begin-->
                        <div id="addCharge" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter une nouvelle charge commun</h3>
                            </div>
                            <form class="form-horizontal" action="../controller/ChargeCommunActionController.php" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="control-group">
                                        <label class="control-label">Type Charge</label>
                                        <div class="controls">
                                            <select name="type">
                                                <option value="<?= $idTypeCharge ?>"><?= $nomTypeCharge ?></option>
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
                                            <input type="hidden" name="action" value="add" />    
                                            <input type="hidden" name="typeCharge" value="<?= $typeCharge ?>" />
                                            <input type="hidden" name="source" value="charges-communs-type" />
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
                                <h3>Ajouter Nouveau Type Charge Commun</h3>
                            </div>
                            <form class="form-horizontal" action="../controller/TypeChargeCommunActionController.php" method="post">
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
                                            <input type="hidden" name="action" value="add" />
                                            <input type="hidden" name="typeCharge" value="<?= $typeCharge ?>" />   
                                            <input type="hidden" name="source" value="charges-communs-type" />     
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- addTypeCharge box end -->
                        <!--**************************** CHARGES BEGIN ****************************-->
                        <div class="row-fluid get-down">
                            <div class="input-box autocomplet_container">
                                <a style="margin-top:5px;" href="#printCharges" class="btn black btn-fixed-width-big" data-toggle="modal">
                                    <i class="icon-print"></i>&nbsp;Imprimer liste des charges
                                </a>
                                <?php 
                                if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
                                ?>
                                <a style="margin-top:5px;" href="#addTypeCharge" data-toggle="modal" class="btn blue btn-fixed-width-big">
                                    Type Charge <i class="icon-plus-sign "></i>
                                </a>
                                <a style="margin-top:5px;" href="#addCharge" data-toggle="modal" class="btn green btn-fixed-width-big">
                                    Nouvelle Charge <i class="icon-plus-sign "></i>
                                </a>
                                <?php 
                                }
                                ?>
                            </div>
                        </div>
                        <!-- printCharge box begin-->
                        <div id="printCharges" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Imprimer Liste des Charges </h3>
                            </div>
                            <form class="form-horizontal" action="../controller/ChargeCommunPrintController.php" method="post" enctype="multipart/form-data">
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
                                             </div> Par date
                                         </label>  
                                      </div>
                                   </div>
                                    <div class="control-group" id="showDateRange">
                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                           <input style="width:100px" name="dateFrom" id="dateFrom" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                           &nbsp;-&nbsp;
                                           <input style="width:100px" name="dateTo" id="dateTo" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="hidden" name="type" value="<?= $typeCharge ?>" />
                                            <input type="hidden" name="source" value="charges-communs-type" />
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- printCharge box end -->
                        <!-- BEGIN Terrain TABLE PORTLET-->
                        <?php 
                        if(isset($_SESSION['charge-action-message'])
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
                        <table class="table table-striped table-bordered  table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 50%"><strong>Σ <?= $nomTypeCharge ?></strong></th>
                                    <th style="width: 50%"><a><strong><?= $total ?>&nbsp;DH</strong></a></th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="portlet box light-grey">
                            <div class="portlet-title">
                                <h4>Liste détaillée des charges de <?= $nomTypeCharge ?></h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                        <div class="portlet-body">
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
                                            <th class="hidden-phone" style="width: 20%">Type</th>
                                            <th style="width: 20%">Date Opération</th>
                                            <th class="hidden-phone" style="width: 20%">Désignation</th>
                                            <th style="width: 20%">Société</th>
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
                                            <td class="hidden-phone">
                                                <?= $nomTypeCharge ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($charge->dateOperation())) ?></td>
                                            <td class="hidden-phone"><?= $charge->designation() ?></td>
                                            <td><?= $charge->societe() ?></td>
                                            <td><?= number_format($charge->montant(), 2, ',', ' ') ?></td>
                                        </tr>
                                        <!-- updateCharge box begin-->
                                        <div id="updateCharge<?= $charge->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier Info Charge </h3>
                                            </div>
                                            <form class="form-horizontal" action="../controller/ChargeCommunActionController.php" method="post">
                                                <div class="modal-body">
                                                    <div class="control-group">
                                                        <label class="control-label">Type Charge</label>
                                                        <div class="controls">
                                                            <select name="type">
                                                                <option value="<?= $charge->type() ?>"><?= $typeChargeManager->getTypeChargeById($charge->type())->nom() ?></option>
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
                                                        <input type="hidden" name="action" value="update" />
                                                        <input type="hidden" name="typeCharge" value="<?= $typeCharge ?>" />
                                                        <input type="hidden" name="source" value="charges-communs-type" />
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
                                            <form class="form-horizontal loginFrm" action="../controller/ChargeCommunActionController.php" method="post">
                                                <div class="modal-body">
                                                    <p>Êtes-vous sûr de vouloir supprimer cette charge <?= $charge->type() ?> ?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="idCharge" value="<?= $charge->id() ?>" />
                                                        <input type="hidden" name="action" value="delete" />
                                                        <input type="hidden" name="typeCharge" value="<?= $typeCharge ?>" />
                                                        <input type="hidden" name="source" value="charges-communs-type" />
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
                                <table class="table table-striped table-bordered  table-hover">
                                    <tbody>
                                        <tr>
                                            <th style="width: 50%"><strong>Σ <?= $nomTypeCharge ?></strong></th>
                                            <th style="width: 50%"><a><strong><?= $total ?>&nbsp;DH</strong></a></th>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--/div--><!-- END DIV SCROLLER --> 
                            </div>
                        </div>
                        <!-- END Terrain TABLE PORTLET-->
                        <!--**************************** CHARGES END ****************************-->
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
<!-- END BODY -->
</html>
<?php
}
else if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()!="admin"){
    header('Location:dashboard.php');
}
else{
    header('Location:index.php');    
}
?>