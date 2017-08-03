<?php
    include('../app/classLoad.php');    
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
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
                            <li><i class="icon-bar-chart"></i> <a>Liste des états</a></li>
                        </ul>
                    </div>
                </div>
                <!--      BEGIN TILES      -->
                <div class="row-fluid">
                    <div class="span12">
                        <div class="tiles">
                            <a href="contrat-status.php">
                            <div class="tile bg-dark-red">
                                <div class="corner"></div>
                                <div class="tile-body">
                                    <i class="icon-group"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Etats clients
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="operations-status-group.php">
                            <div class="tile bg-blue">
                                <div class="tile-body">
                                    <i class="icon-money"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Etats paiments
                                    </div>
                                    <div class="number">
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="properties-status.php">
                            <div class="tile bg-green">
                                <div class="tile-body">
                                    <i class="icon-home"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Etats Immobilier
                                    </div>
                                    <div class="number">
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="clients-synthese.php">
                            <div class="tile bg-cyan">
                                <div class="tile-body">
                                    <i class="icon-search"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Synthèse clients
                                    </div>
                                    <div class="number">
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="clients-modification.php">
                            <div class="tile bg-grey">
                                <div class="tile-body">
                                    <i class="icon-pencil"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Modifications Clients
                                    </div>
                                    <div class="number">
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="contrats-desistes.php">
                            <div class="tile bg-red">
                                <div class="tile-body">
                                    <i class="icon-file"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Contrats Désistés
                                    </div>
                                    <div class="number">
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="commissions.php">
                            <div class="tile bg-dark-blue">
                                <div class="corner"></div>
                                <div class="tile-body">
                                    <i class="icon-thumbs-up"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Commissions
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!--      END TILES      -->
                <!-- END PAGE HEADER-->
            </div>  
        </div>
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>        
    <script>jQuery(document).ready(function() { App.setPage("sliders"); App.init(); });</script>
    <!-- END JAVASCRIPTS -->
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>