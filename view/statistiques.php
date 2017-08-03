<?php
    include('../app/classLoad.php');    
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
    	//classes managers
		$usersManager = new UserManager(PDOFactory::getMysqlConnection());
		$mailsManager = new MailManager(PDOFactory::getMysqlConnection());
		$notesClientsManager = new NotesClientManager(PDOFactory::getMysqlConnection());
		$projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
		$contratManager = new ContratManager(PDOFactory::getMysqlConnection());
		$clientManager = new ClientManager(PDOFactory::getMysqlConnection());
		$livraisonsManager = new LivraisonManager(PDOFactory::getMysqlConnection());
		$fournisseursManager = new FournisseurManager(PDOFactory::getMysqlConnection());
		$caisseEntreesManager = new CaisseEntreesManager(PDOFactory::getMysqlConnection());
		$caisseSortiesManager = new CaisseSortiesManager(PDOFactory::getMysqlConnection());
		$operationsManager = new OperationManager(PDOFactory::getMysqlConnection());
		//classes and vars
		//users number
		$usersNumber = $usersManager->getUsersNumber();
		$mailsNumberToday = $mailsManager->getMailsNumberToday();
		$mailsToday = $mailsManager->getMailsToday();
		$clientWeek = $clientManager->getClientsWeek();
		$clientNumberWeek = $clientManager->getClientsNumberWeek();
		$livraisonsWeek = $livraisonsManager->getLivraisonsWeek();
		$livraisonsNumberWeek = $livraisonsManager->getLivraisonsNumberWeek();
		$operationsNumberWeek = $operationsManager->getOperationNumberWeek()
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
	<div class="page-container row-fluid">
		<?php include('../include/sidebar.php') ?>
		<div class="page-content">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<ul class="breadcrumb">
							<li><i class="icon-dashboard"></i> <a>Accueil</a><i class="icon-angle-right"></i></li>
							<li><a>Tableau de bord</a><i class="icon-angle-right"></i></li>
							<li><a><strong>Statistiques</strong></a></li>
						</ul>
					</div>
				</div>
				<!--      BEGIN TILES      -->
				<div class="row-fluid">
					<div class="span12">
						<h4><i class="icon-bar-chart"></i> Statistiques des projets</h4>
						<hr class="line">
						<div id="container1" style="width:100%; height:400px;"></div>
					</div>
				</div>
				<!--      BEGIN TILES      -->
				<!-- BEGIN DASHBOARD STATS -->
				<h4><i class="icon-table"></i> Statistiques de la caisse</h4>
				<hr class="line">
				<div class="row-fluid">
					<div id="container2" style="width:100%; height:400px;"></div>
				</div>
				<h4><i class="icon-table"></i> Statistiques de la société</h4>
				<hr class="line">
				<div class="row-fluid">
					<div id="container3" style="width:100%; height:400px;"></div>
				</div>
				<!-- END DASHBOARD STATS -->
				<!-- END PAGE HEADER-->
			</div>	
		</div>
		<!-- END PAGE -->	 	
	</div>
	<?php include('../include/footer.php') ?>
	<?php include('../include/scripts.php') ?>
	<script>jQuery(document).ready(function() { App.setPage("sliders"); App.init(); });</script>
	<!------------------------- BEGIN HIGHCHARTS  --------------------------->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>
	<!--script src="http://code.highcharts.com/themes/dark-unica.js"></script-->
	<script src="http://code.highcharts.com/modules/data.js"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>
	<script>
		$(function() {
			Highcharts.setOptions({
				lang: {
					downloadPDF: 'PDF',
					printChart: 'Imprimer Statistiques',
					downloadPNG: null,
					downloadJPEG: null,
					downloadSVG: null
				}	
			});
			$('#container1').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: 'Rapport Projet-Réglements Fournisseurs-Apports Clients'
				},
				xAxis: {
					categories: ['Projet1', 'Projet2', 'Projet3']
				},
				yAxis: {
					title: {
						text: 'Statistiques'
					}
				},
				series: [{
					name: 'Apports Clients',
					data: [40, 100, 50]
				}, {
					name: 'Réglements Fournisseurs',
					data: [20, 70, 10]
				}, {
					name: 'Fonds Projets',
					data: [80, 90, 60]
				}]
			});
		});
	</script>
	<script>
		$(function() {
			Highcharts.setOptions({
				lang: {
					downloadPDF: 'PDF',
					printChart: 'Imprimer Statistiques',
					downloadPNG: null,
					downloadJPEG: null,
					downloadSVG: null
				}	
			});
			$('#container2').highcharts({
				chart: {
					type: 'line'
				},
				title: {
					text: 'Entrées/Sorties de la caisse'
				},
				xAxis: {
					categories: ['Projet1', 'Projet2', 'Projet3']
				},
				yAxis: {
					title: {
						text: 'Statistiques'
					}
				},
				series: [{
					name: 'Apports Clients',
					data: [40, 100, 50]
				}, {
					name: 'Réglements Fournisseurs',
					data: [20, 70, 10]
				}, {
					name: 'Fonds Projets',
					data: [80, 90, 60]
				}]
			});
		});
	</script>
	<script>
		$(function() {
			Highcharts.setOptions({
				lang: {
					downloadPDF: 'PDF',
					printChart: 'Imprimer Statistiques',
					downloadPNG: null,
					downloadJPEG: null,
					downloadSVG: null
				}	
			});
			$('#container3').highcharts({
				chart: {
					type: 'bar'
				},
				title: {
					text: 'Activité de la société'
				},
				xAxis: {
					categories: ['Projet1', 'Projet2', 'Projet3']
				},
				yAxis: {
					title: {
						text: 'Statistiques'
					}
				},
				series: [{
					name: 'Apports Clients',
					data: [40, 100, 50]
				}, {
					name: 'Réglements Fournisseurs',
					data: [20, 70, 10]
				}, {
					name: 'Fonds Projets',
					data: [80, 90, 60]
				}]
			});
		});
	</script>
	<!------------------------- END HIGHCHARTS  --------------------------->
	<!-- END JAVASCRIPTS -->
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>