<?php
    include('../app/classLoad.php');   
    session_start();
    if ( isset($_SESSION['userMerlaTrav']) ) {
        $typeChargesManager = new TypeChargeManager(PDOFactory::getMysqlConnection());
        $typesCharges = $typeChargesManager->getTypeCharges();
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
                            <li><i class="icon-wrench"></i> <a href="configuration.php">Paramètrages</a><i class="icon-angle-right"></i></li>
                            <li><a><strong>Types des charges</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                    <!-- CONTRAT CAS LIBRE BEGIN -->
                    <?php 
                    if(isset($_SESSION['typeCharge-action-message']) 
                    and isset($_SESSION['typeCharge-type-message'])){ 
                          $message = $_SESSION['typeCharge-action-message'];
                          $typeMessage = $_SESSION['typeCharge-type-message'];
                    ?>
                        <br><br>
                        <div class="alert alert-<?= $typeMessage ?>">
                            <button class="close" data-dismiss="alert"></button>
                            <?= $message ?>
                        </div>
                     <?php } 
                        unset($_SESSION['typeCharge-action-message']);
                        unset($_SESSION['typeCharge-type-message']);
                     ?>
                    <!-- addTypeCharge box begin-->
                    <div id="addTypeCharge" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h3>Ajouter Nouveau Type Charge </h3>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" action="../controller/TypeChargeActionController.php" method="post">
                                <div class="control-group">
                                    <label class="control-label">Nom Type Charge</label>
                                    <div class="controls">
                                        <input type="text" name="nom" value="" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <input type="hidden" name="action" value="add" />
                                        <input type="hidden" name="source" value="type-charges" />    
                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- addTypeCharge box end -->
                    <div class="portlet box light-grey" id="history">
                        <div class="portlet-title">
                            <h4>Types des charges</h4>
                            <div class="tools">
                                <a href="javascript:;" class="reload"></a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="clearfix">
                                <div class="btn-group">
                                    <a class="btn blue pull-right" href="#addTypeCharge" data-toggle="modal">
                                        <i class="icon-plus-sign"></i>
                                         Type Charge
                                    </a>
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%">Actions</th>
                                            <th style="width: 80%">Type Charge</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ( $typesCharges as $type ) {
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="#" class="btn mini red"><i class="icon-remove"></i></a>
                                                <a href="#updateCharge<?= $type->id() ?>" data-toggle="modal" data-id="<?= $type->id() ?>" class="btn mini green"><i class="icon-refresh"></i></a>
                                            </td>
                                            <td><?= $type->nom() ?></td>
                                        </tr>
                                        <!-- updateCompte box begin-->
                                        <div id="updateCharge<?= $type->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier Type Charge</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="../controller/TypeChargeActionController.php" method="post">
                                                    <div class="control-group">
                                                        <label class="control-label">Nom Type Charge</label>
                                                        <div class="controls">
                                                            <input type="text" name="nom" value="<?= $type->nom() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <input type="hidden" name="idTypeCharge" value="<?= $type->id() ?>">
                                                            <input type="hidden" name="action" value="update" />
                                                            <input type="hidden" name="source" value="type-charges" />
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- updateCompte box end -->   
                                        <?php
                                        }
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
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>        
    <script>jQuery(document).ready(function() { App.setPage("table_managed"); App.init(); });</script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}

?>