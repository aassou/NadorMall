<?php 
    include('../app/classLoad.php');
    session_start();
    if( isset($_SESSION['userMerlaTrav']) ){
        //les sources
        $operationManager = new OperationManager(PDOFactory::getMysqlConnection());
        $operations =$operationManager->getOperationsGroupByMonth();
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
        <?php include('../include/top-menu.php'); ?>
    </div>
    <div class="page-container row-fluid sidebar-closed">
        <?php include('../include/sidebar.php'); ?>
        <div class="page-content">    
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="breadcrumb">
                            <li><i class="icon-home"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-wrench"></i> <a href="configuration.php">Paramètrages</a><i class="icon-angle-right"></i></li>
                            <li><a><strong>Archive des opérations des paiements clients</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                       <div class="portlet box light-grey">
                            <div class="portlet-title">
                                <h4>Archive des opérations des paiements clients</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="clearfix">
                                </div>
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th style="width:100%">Mois/Année</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($operations as $operation){
                                        ?>      
                                        <tr class="odd gradeX">
                                            <?php
                                            $mois = date('m', strtotime($operation->dateReglement()));
                                            $annee = date('Y', strtotime($operation->dateReglement()));
                                            ?>
                                            <td>
                                                <a class="btn mini" href="operations-status-archive.php?mois=<?= $mois ?>&annee=<?= $annee ?>">
                                                    <strong><?= date('m/Y', strtotime($operation->dateReglement())) ?></strong>
                                                </a>
                                            </td>
                                        </tr>
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
    <script>jQuery(document).ready(function() { App.init(); $('.criteriaPrint').on('change',function() { if( $(this).val()==="toutesCaisse") { $("#showDateRange").hide() } else{ $("#showDateRange").show() } }); });</script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>