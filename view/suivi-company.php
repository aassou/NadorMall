<?php
    include('../app/classLoad.php'); 
    session_start();
    if(isset($_SESSION['userMerlaTrav']) ){
        //classes managers
        $appartementManager = new AppartementManager(PDOFactory::getMysqlConnection());
        $locauxManager = new LocauxManager(PDOFactory::getMysqlConnection());
        $usersManager = new UserManager(PDOFactory::getMysqlConnection());
        $projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
        $contratManager = new ContratManager(PDOFactory::getMysqlConnection());
        $clientManager = new ClientManager(PDOFactory::getMysqlConnection());
        $chargeManager = new ChargeManager(PDOFactory::getMysqlConnection());
        $chargeCommunManager = new ChargeCommunManager(PDOFactory::getMysqlConnection());
        $livraisonsManager = new LivraisonManager(PDOFactory::getMysqlConnection());
        $livraisonDetailManager = new LivraisonDetailManager(PDOFactory::getMysqlConnection());
        $fournisseursManager = new FournisseurManager(PDOFactory::getMysqlConnection());
        $reglementsFournisseurManager = new ReglementFournisseurManager(PDOFactory::getMysqlConnection());
        $caisseEntreesManager = new CaisseEntreesManager(PDOFactory::getMysqlConnection());
        $caisseSortiesManager = new CaisseSortiesManager(PDOFactory::getMysqlConnection());
        $operationsManager = new OperationManager(PDOFactory::getMysqlConnection());
        //classes and vars
        //$idProjet = $_GET['idProjet'];
        //$projet = $projetManager->getProjetById($idProjet);
        //Container 1 : Statistiques
        $chiffreAffaireTheorique = 
        ceil($appartementManager->getTotalPrixAppartements() + $locauxManager->getTotalPrixLocaux());
        
        //get contacts ids and get sum of client operations
        $idsContrats = $contratManager->getContratActifIds();
        $sommeOperationsClients = 0;
        $sommePrixVente = 0;
        foreach($idsContrats as $id){
            $sommeOperationsClients += $operationsManager->sommeOperations($id);
            $sommePrixVente += $contratManager->getContratById($id)->prixVente();
        }
        $sommeApportsClients = ($sommeOperationsClients);
        $reliquat = $sommePrixVente - $sommeOperationsClients; 
        $sommeCharges = 
        $chargeCommunManager->getTotal() + $chargeManager->getTotal();
        $sommeCharges = ceil($sommeCharges);
        
        //Container 2 : Statistiques
        $sommeLivraisons = 0;
        $idsLivraisons = $livraisonsManager->getLivraisonIds();
        foreach ( $idsLivraisons as $id ) {
            $sommeLivraisons += $livraisonDetailManager->getTotalLivraisonByIdLivraison($id);
        }
        $sommeReglements = ceil($reglementsFournisseurManager->sommeReglementFournisseur());
        $sommeLivraison = ceil($livraisonsManager->getTotalLivraisons());
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
                            <li><i class="icon-dashboard"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-bar-chart"></i> <a>Statistiques Globales</a></li>
                        </ul>
                    </div>
                </div>
                <!--      BEGIN TILES      -->
                <div class="row-fluid">
                    <div class="span12">
                        <h4><i class="icon-bar-chart"></i> Statistiques des projets NadorMall</h4>
                        <hr class="line">
                        <div id="container1" style="width:100%; height:400px;"></div>
                    </div>
                    <div class="span12">
                        <hr class="line">
                        <div id="container2" style="width:100%; height:400px;"></div>
                    </div>
                </div>
                <!--      BEGIN TILES      -->
                <!-- BEGIN DASHBOARD STATS -->
                <!--h4><i class="icon-table"></i> Statistiques de la caisse</h4>
                <hr class="line">
                <div class="row-fluid">
                    <div id="container3" style="width:100%; height:400px;"></div>
                </div-->
                <!--h4><i class="icon-table"></i> Statistiques de la société</h4>
                <hr class="line">
                <div class="row-fluid">
                    <div id="container3" style="width:100%; height:400px;"></div>
                </div-->
                <!-- END DASHBOARD STATS -->
                <!-- END PAGE HEADER-->
            </div>
            <!-- END PAGE CONTAINER-->  
        </div>
        <!-- END PAGE -->       
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>
    <script>jQuery(document).ready(function() { App.setPage("sliders"); App.init(); });</script>
    <!------------------------- BEGIN HIGHCHARTS  --------------------------->
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <!--script src="http://code.highcharts.com/themes/dark-unica.js"></script-->
    <script src="http://code.highcharts.com/modules/data.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
    <script> 
        $(function() {
            Highcharts.setOptions({
                credits: {
                      enabled: false
                },
                lang: {
                    downloadPDF: 'PDF',
                    printChart: 'Imprimer Statistiques',
                    downloadPNG: null,
                    downloadJPEG: null,
                    downloadSVG: null,
                }   
            });
            $('#container1').highcharts({
                chart: {
                    type: 'column'
                },
                exporting: {
                    filename:'StatistiquesProjet-CA-Charges'
                },   
                title: {
                    text: 'Chiffre d\'affaires et charges'
                },
                xAxis: {
                    categories: ['Entrées-Sorties']
                },
                yAxis: {
                    title: {
                        text: 'Montants en Millions de DH'
                    }
                },
                series: [
                {
                    name: 'Valeur des biens avant vente',
                    data: [<?= json_encode($chiffreAffaireTheorique) ?>]
                },
                {
                    name: 'Valeur des biens vendus',
                    data: [<?= json_encode($sommePrixVente) ?>]
                },
                {
                    name: 'Les charges',
                    data: [<?= json_encode($sommeCharges) ?>]
                },
                {
                    name: 'Bénéfice',
                    data: [<?= json_encode($sommePrixVente - $sommeCharges) ?>]
                },
                {
                    name: 'Réglements Clients',
                    data: [<?= json_encode($sommeApportsClients) ?>]
                }, 
                {
                    name: 'Reliquat Réglements',
                    data: [<?= json_encode($reliquat) ?>]
                }
                ]
            });
        });
    </script>
    <script> 
        $(function() {
            Highcharts.setOptions({
                credits: {
                      enabled: false
                },
                lang: {
                    downloadPDF: 'PDF',
                    printChart: 'Imprimer Statistiques',
                    downloadPNG: null,
                    downloadJPEG: null,
                    downloadSVG: null,
                }
            });
            $('#container2').highcharts({
                chart: {
                    type: 'column'
                },
                exporting: {
                    filename:'StatistiquesLivraisonsReglementsFournisseurs'
                },   
                title: {
                    text: 'Livraisons et réglements des fournisseurs'
                },
                xAxis: {
                    categories: ['Entrées-Sorties']
                },
                yAxis: {
                    title: {
                        text: 'Montants en Millions de DH'
                    }
                },
                series: [
                {
                    name: 'Réglements Fournisseurs',
                    data: [<?= json_encode($sommeReglements) ?>]
                },
                {
                    name: 'Livraison Fournisseurs',
                    data: [<?= json_encode($sommeLivraisons) ?>]
                },
                {
                    name: 'Reliquat',
                    data: [<?= json_encode($sommeLivraisons - $sommeReglements) ?>]
                }
                ]
            });
        });
    </script>
    <!------------------------- END HIGHCHARTS  --------------------------->
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>