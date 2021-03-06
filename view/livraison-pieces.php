<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
    	//les sources
    	$idProjet = 0;
		$idLivraison = 0;
    	$projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
		$livraisonManager = new LivraisonManager(PDOFactory::getMysqlConnection());
		if((isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId())
			and (isset($_GET['idLivraison']) and($_GET['idLivraison']>0 and $_GET['idLivraison']<=$livraisonManager->getLastId()))){
			$idProjet = $_GET['idProjet'];
			$idLivraison = $_GET['idLivraison'];
			$projet = $projetManager->getProjetById($idProjet);
			$livraison = $livraisonManager->getLivraisonById($idLivraison);
			$piecesManager = new LivraisonPiecesManager(PDOFactory::getMysqlConnection());
			$pieces = "";
			//test the terrain object number: if exists get terrain else do nothing
			$piecesNumber = $piecesManager->getPiecesLivraisonNumberByIdLivraison($idLivraison);
			if($piecesNumber != 0){
				$pieces = $piecesManager->getPiecesLivraisonByIdLivraison($idLivraison);
			}
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
		<!-- BEGIN TOP NAVIGATION BAR -->
		<?php include('../include/top-menu.php') ?>	
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
	<!-- BEGIN CONTAINER -->
	<div class="page-container row-fluid">
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
							Gestion des Livraison
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a>Accueil</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<i class="icon-briefcase"></i>
								<a>Gestion des projets</a>
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a>Gestion des livraisons</a>
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a>Gestion des documents</a>
							</li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<?php if($idProjet!=0 and $idLivraison!=0){ ?>
				<div class="row-fluid">
					<div class="span12">
						<div class="tab-pane active" id="tab_1">
							<div class="row-fluid add-portfolio">
								<div class="pull-left">
									<a href="livraisons-list.php?idProjet=<?= $idProjet ?>" class="btn icn-only green">
										<i class="m-icon-swapleft m-icon-white"></i> 
										 Retour vers Liste des Livraisons du projet : <strong><?= $projetManager->getProjetById($idProjet)->nom() ?></strong></a>
								</div>
								<div class="pull-right">
									<a href="projet-list.php" class="btn icn-only green"> 
										Aller vers liste des projets
										<i class="m-icon-swapright m-icon-white"></i>	 
									</a>
								</div>
							</div>
							<?php if(isset($_SESSION['appartement-add-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['appartement-add-success'] ?>		
							</div>
	                         <?php } 
	                         	unset($_SESSION['appartement-add-success']);
	                         ?>
	                         <?php if(isset($_SESSION['appartement-update-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['appartement-update-success'] ?>		
							</div>
	                         <?php } 
	                         	unset($_SESSION['appartement-update-success']);
	                         ?>
	                         <?php if(isset($_SESSION['piece-delete-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['piece-delete-success'] ?>		
							</div>
	                         <?php } 
	                         	unset($_SESSION['piece-delete-success']);
	                         ?>
	                         <?php if(isset($_SESSION['appartement-add-error'])){ ?>
	                         	<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<?= $_SESSION['appartement-add-error'] ?>		
								</div>
	                         <?php } 
	                         	unset($_SESSION['appartement-add-error']);
	                         ?>
	                         <?php if(isset($_SESSION['appartement-update-error'])){ ?>
	                         	<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<?= $_SESSION['appartement-update-error'] ?>		
								</div>
	                         <?php } 
	                         	unset($_SESSION['appartement-update-error']);
	                         ?>
                        </div>
					</div>
				</div>
				<!-- BEGIN PicesTerrain GALLERY PORTLET-->
				<div class="row-fluid">
					<div class="span12">
						<div class="portlet">
							<div class="portlet-title">
								<h4>Pièces de la livraison : <?= $livraison->id() ?></h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body">
								<?php
								if($piecesNumber != 0){
								foreach($pieces as $pieces){
								?>
								<div class="span3">
									<div class="item">
										<a class="fancybox-button" data-rel="fancybox-button" title="<?= $pieces->nom() ?>" href="<?= $pieces->url() ?>">
											<div class="zoom">
												<img style="height: 100px; width: 200px" src="<?= $pieces->url() ?>" alt="<?= $pieces->nom() ?>" />							
												<div class="zoom-icon"></div>
											</div>
										</a>
									</div>
									<a class="btn mini red" href="#deletePiece<?= $pieces->id() ?>" data-toggle="modal" data-id="<?= $pieces->id() ?>">
										Supprimer
									</a>
									<br><br>	
								</div>
								<!-- delete box begin-->
								<div id="deletePiece<?php echo $pieces->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h3>Supprimer Pièce de la livraison <strong><?= $livraison->id() ?></strong></h3>
									</div>
									<div class="modal-body">
										<form class="form-horizontal loginFrm" action="../controller/LivraisonPiecesDeleteController.php" method="post">
											<p>Êtes-vous sûr de vouloir supprimer cette pièce ?</p>
											<div class="control-group">
												<label class="right-label"></label>
												<input type="hidden" name="idPieceLivraison" value="<?= $pieces->id() ?>" />
												<input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
												<input type="hidden" name="idLivraison" value="<?= $idLivraison ?>" />
												<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
												<button type="submit" class="btn red" aria-hidden="true">Oui</button>
											</div>
										</form>
									</div>
								</div>
								<!-- delete box end -->
								<?php 
								}//end of loop : pieces
								}//end of if : piecesNumber
								?>
							</div>
						</div>
					</div>
				</div>
				<!-- END PicesTerrain GALLERY PORTLET-->
				<?php }
				else{
				?>
				<div class="alert alert-error">
					<button class="close" data-dismiss="alert"></button>
					<strong>Erreur système : </strong>Ce projet ou ce local n'existe pas sur votre système. Pour plus d'informations consulter votre administrateur.		
				</div>
				<?php
				}
				?>
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
			//App.setPage("table_editable");
			App.init();
		});
	</script>
</body>
<!-- END BODY -->
</html>
<?php
}
else if(isset($_SESSION['userMerlaTrav']) and $_SESSION->profil()!="admin"){
	header('Location:dashboard.php');
}
else{
    header('Location:index.php');    
}
?>