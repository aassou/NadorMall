<?php
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
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
                            <li><i class="icon-wrench"></i> <a>Paramètrages</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="tiles">
                            <a href="companies.php">
                            <div class="tile bg-red">
                                <div class="tile-body">
                                    <i class="icon-sitemap"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Sociétés
                                    </div>
                                    <div class="number">
                                        
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="users.php">
                            <div class="tile bg-green">
                                <div class="tile-body">
                                    <i class="icon-user"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Utilisateurs
                                    </div>
                                    <div class="number">
                                        
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="type-charges.php">
                            <div class="tile bg-cyan">
                                <div class="corner"></div>
                                <div class="tile-body">
                                    <i class="icon-bar-chart"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Type Charges
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="type-charges-communs.php">
                            <div class="tile bg-dark-cyan">
                                <div class="corner"></div>
                                <div class="tile-body">
                                    <i class="icon-bar-chart"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Type Charges Communs
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="employes-contrats.php">
                            <div class="tile bg-brown">
                                <div class="corner"></div>
                                <div class="tile-body">
                                    <i class="icon-legal"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Employés
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="fournisseurs.php">
                            <div class="tile bg-blue">
                                <div class="corner"></div>
                                <div class="tile-body">
                                    <i class="icon-truck"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Fournisseurs
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="clients-list.php">
                            <div class="tile bg-dark-red">
                                <div class="corner"></div>
                                <div class="tile-body">
                                    <i class="icon-group"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Clients
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="operations-status-archive-group.php">
                            <div class="tile bg-purple">
                                <div class="corner"></div>
                                <div class="tile-body">
                                    <i class="icon-money"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Archive des paiements
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="compte-bancaire.php">
                            <div class="tile bg-yellow">
                                <div class="tile-body">
                                    <i class="icon-credit-card"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Compte Bancaire
                                    </div>
                                    <div class="number">
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="releve-bancaire-archive.php">
                            <div class="tile bg-dark-blue">
                                <div class="tile-body">
                                    <i class="icon-envelope"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Archive Relevés Bancaires
                                    </div>
                                    <div class="number">
                                    </div>
                                </div>
                            </div>
                            </a>
                            <a href="history-group.php">
                            <div class="tile bg-grey">
                                <div class="tile-body">
                                    <i class="icon-calendar"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        Historique
                                    </div>
                                    <div class="number">
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>        
    <script>jQuery(document).ready(function() { App.setPage("sliders"); App.init(); });</script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>