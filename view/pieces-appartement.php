<?php
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav']) ){
    	//les sources
    	$idProjet = 0;
		$idLocaux = 0;
    	$projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
		$appartementManager = new AppartementManager(PDOFactory::getMysqlConnection());
		if((isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId())
			and (isset($_GET['idAppartement']) and($_GET['idAppartement']>0 and $_GET['idAppartement']<=$appartementManager->getLastId()))){
			$idProjet = $_GET['idProjet'];
			$idAppartement = $_GET['idAppartement'];
			$projet = $projetManager->getProjetById($idProjet);
			$appartement = $appartementManager->getAppartementById($idAppartement);
			$piecesManager = new PiecesAppartementManager(PDOFactory::getMysqlConnection());
			$piecesAppartement = "";
			//test the terrain object number: if exists get terrain else do nothing
			$piecesNumber = $piecesManager->getPiecesAppartementNumberByIdAppartement($idAppartement);
			if($piecesNumber != 0){
				$piecesAppartement = $piecesManager->getPiecesAppartementByIdAppartement($idAppartement);
			}
		}
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
	<div class="page-container row-fluid">
		<?php include('../include/sidebar.php') ?>
		<div class="page-content">			
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<ul class="breadcrumb">
							<li><i class="icon-home"></i> <a>Accueil</a><i class="icon-angle-right"></i></li>
							<li><i class="icon-briefcase"></i> <a>Gestion des projets</a><i class="icon-angle-right"></i></li>
							<li><a>Gestion des appartements</a><i class="icon-angle-right"></i></li>
							<li><a><strong>Gestion des documents</strong></a></li>
						</ul>
					</div>
				</div>
				<?php if($idProjet!=0 and $idAppartement!=0){ ?>
				<div class="row-fluid">
					<div class="span12">
						<div class="tab-pane active" id="tab_1">
							<div class="row-fluid add-portfolio">
								<div class="pull-left">
									<a href="appartements.php?idProjet=<?= $idProjet ?>" class="btn icn-only green">
										<i class="m-icon-swapleft m-icon-white"></i> 
										Retour vers Liste des Appartements du projet : <strong><?= $projetManager->getProjetById($idProjet)->nom() ?></strong>
									</a>
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
	                         <?php if(isset($_SESSION['pieces-delete-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['pieces-delete-success'] ?>		
							</div>
	                         <?php } 
	                         	unset($_SESSION['pieces-delete-success']);
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
				<div class="row-fluid">
					<div class="span12">
						<div class="portlet">
							<div class="portlet-title">
								<h4>Pièces de l' Appartement : <?= $appartement->nom() ?></h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body">
								<?php
								if($piecesNumber != 0){
								foreach($piecesAppartement as $pieces){
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
										<h3>Supprimer Pièce de l' Appartement <strong><?= $appartement->nom() ?></strong></h3>
									</div>
									<div class="modal-body">
										<form class="form-horizontal loginFrm" action="../controller/AppartementPiecesDeleteController.php?p=1" method="post">
											<p>Êtes-vous sûr de vouloir supprimer cette pièce ?</p>
											<div class="control-group">
												<label class="right-label"></label>
												<input type="hidden" name="idPieceAppartement" value="<?= $pieces->id() ?>" />
												<input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
												<input type="hidden" name="idAppartement" value="<?= $idAppartement ?>" />
												<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
												<button type="submit" class="btn red" aria-hidden="true">Oui</button>
											</div>
										</form>
									</div>
								</div>
								<!-- delete box end -->
								<?php 
								}//end of loop : terrains
								}//end of if : terrainNumber
								?>
							</div>
						</div>
					</div>
				</div>
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
			</div>
		</div>
	</div>
	<?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>       			
	<script>jQuery(document).ready(function() { App.init(); });</script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>