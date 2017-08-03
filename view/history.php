<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    if ( isset($_SESSION['userMerlaTrav']) ) {
        $mois = $_GET['mois'];
        $annee = $_GET['annee'];
        $historyManager = new HistoryManager(PDOFactory::getMysqlConnection());
        $histories = $historyManager->getHistoryByMonthYear($mois, $annee)//getHistorys();
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
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
                            Historique des actions de l'application 
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-wrench"></i>
                                <a href="configuration.php">Paramètrages</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a href="history-group.php">Historique des actions</a>
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a><strong><?= $mois."/".$annee ?></strong></a>
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <!-- BEGIN PAGE CONTENT-->
                <div class="row-fluid">
                    <div class="span12">
                    <!-- printHistory box begin-->
                    <div id="printHistory" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h3>Imprimer l'historique des actions </h3>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" action="../controller/HistoryPrintController.php" method="post" enctype="multipart/form-data">
                                <p><strong>Séléctionner vos dates </strong></p>
                                <div class="control-group" id="">
                                    <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                       <input style="width:100px" name="dateBegin" id="dateBegin" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                       &nbsp;-&nbsp;
                                       <input style="width:100px" name="dateEnd" id="dateEnd" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- printHistory box end -->  
                    <!-- CONTRAT CAS LIBRE BEGIN -->
                    <div class="portlet box light-grey" id="history">
                        <div class="portlet-title">
                            <h4>Historique des actions</h4>
                            <div class="tools">
                                <a href="javascript:;" class="reload"></a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="clearfix">
                                <div class="btn-group">
                                        <a class="btn blue pull-right" href="#printHistory" data-toggle="modal">
                                            <i class="icon-print"></i>
                                             Version Imprimable
                                        </a>
                                    </div>
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th class="hidden-phone" style="width: 20%">Cible</th>
                                            <th class="hidden-phone" style="width: 20%">Action</th>
                                            <th style="width: 20%">Date de l'action</th>
                                            <th style="width: 20%">Réalisé par</th>
                                            <th style="width: 20%">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ( $histories as $history ) {
                                        ?>
                                        <tr>
                                            <td class="hidden-phone"><?= $history->target() ?></td>
                                            <td class="hidden-phone"><?= $history->action() ?></td>
                                            <td><?= date('d/m/Y - H\hi\m', strtotime($history->created())) ?></td>
                                            <td><?= $history->createdBy() ?></td>
                                            <td><?= $history->description() ?></td>
                                        </tr>
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
    </script>
    <script>
        function blinker() {
            $('.blink_me').fadeOut(500);
            $('.blink_me').fadeIn(500);
        }
        setInterval(blinker, 1500);
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