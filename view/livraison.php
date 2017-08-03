<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
    	$idProjet = 0;
    	$projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
		$fournisseurManager = new FournisseurManager(PDOFactory::getMysqlConnection());
		$livraisonManager = new LivraisonManager(PDOFactory::getMysqlConnection());
		if(isset($_GET['codeLivraison']) and (bool)$livraisonManager->getCodeLivraison($_GET['codeLivraison']) ){
			$codeLivraison = $_GET['codeLivraison'];
			$livraison = $livraisonManager->getLivraisonByCode($codeLivraison);
			$projet = $projetManager->getProjetById($livraison->idProjet());
			$fournisseur = $fournisseurManager->getFournisseurById($livraison->idFournisseur());
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
							Gestion des Livraisons/Fournisseurs
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
							<li><a>Gestion des Fournisseurs/Livraisons</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<?php if(isset($_GET['codeLivraison']) and (bool)$livraisonManager->getCodeLivraison($_GET['codeLivraison']) ){
						?>
						<div class="row-fluid add-portfolio">
							<div class="pull-left">
								<a href="livraisons-list.php?idProjet=<?= $projet->id() ?>" class="btn icn-only green"><i class="m-icon-swapleft m-icon-white"></i> Retour vers Liste des livraisons du projet : <strong><?= $projet->nom() ?></strong></a>
							</div>
							<div class="pull-right">
								<a href="projet-list.php" class="btn icn-only green">Aller vers Liste des projets <i class="m-icon-swapright m-icon-white"></i></a>
							</div>
						</div>
	                     <?php if(isset($_SESSION['livraison-add-success'])){ ?>
	                     	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['livraison-add-success'] ?>		
							</div>
	                     <?php } 
	                     	unset($_SESSION['livraison-add-success']);
	                     ?>
	                     <?php if(isset($_SESSION['fournisseur-update-success'])){ ?>
	                     	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['fournisseur-update-success'] ?>		
							</div>
	                     <?php } 
	                     	unset($_SESSION['fournisseur-update-success']);
	                     ?>
	                     <?php if(isset($_SESSION['fournisseur-update-error'])){ ?>
	                     	<div class="alert alert-error">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['fournisseur-update-error'] ?>		
							</div>
	                     <?php } 
	                     	unset($_SESSION['fournisseur-update-error']);
	                     ?>
	                     <?php if(isset($_SESSION['livraison-update-success'])){ ?>
	                     	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['livraison-update-success'] ?>		
							</div>
	                     <?php } 
	                     	unset($_SESSION['livraison-update-success']);
	                     ?>
	                      <?php if(isset($_SESSION['livraison-update-error'])){ ?>
	                     	<div class="alert alert-error">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['livraison-update-error'] ?>		
							</div>
	                     <?php } 
	                     	unset($_SESSION['livraison-update-error']);
	                     ?>
	                    <h3>Résumé de la livraison</h3>
	                    <hr>
                       <div class="span5">
						<div class="portlet sale-summary">
							<div class="portlet-title">
								<h4>Infos fournisseur</h4>
								<a href="#updateFournisseur<?= $fournisseur->id() ?>" class="pull-right btn red hidden-phone" data-toggle="modal" data-id="<?= $fournisseur->id(); ?>">
									Modifier <i class="icon-refresh icon-white"></i>
								</a>
								<br><br>	
							</div>
							<ul class="unstyled">
								<li>
									<span class="sale-info">Fournisseur</span> 
									<span class="sale-num"><?= $fournisseur->nom() ?></span>
								</li>
								<li>
									<span class="sale-info"><i class="icon-map-marker"></i></span> 
									<span class="sale-num"><?= $fournisseur->adresse() ?></span>
								</li>
								<li>
									<span class="sale-info"><i class="icon-phone"></i></span> 
									<span class="sale-num"><?= $fournisseur->telephone1() ?></span>
								</li>
								<li>
									<span class="sale-info"><i class="icon-phone-sign"></i></span> 
									<span class="sale-num"><?= $fournisseur->telephone2() ?></span>
								</li>
								<li>
									<span class="sale-info">Fax</span> 
									<span class="sale-num"><?= $fournisseur->fax() ?></span>
								</li>
								<li>
									<span class="sale-info">@</span> 
									<span class="sale-num"><?= $fournisseur->email() ?></span>
								</li>
							</ul>
						</div>
					 </div>
					 <div class="span6">
						<div class="portlet sale-summary">
							<div class="portlet-title">
								<h4>Informations de la livraison</h4>
								<a href="#updateLivraison<?= $livraison->id() ?>" class="pull-right btn red hidden-phone" data-toggle="modal" data-id="<?= $livraison->id(); ?>">
									Modifier <i class="icon-refresh icon-white"></i>
								</a>
								<br><br>
							</div>
							<ul class="unstyled">
								<li>
									<span class="sale-info">Date de livraison</span> 
									<span class="sale-num"><?= $livraison->dateLivraison() ?></span>
								</li>
								<li>
									<span class="sale-info">Libelle</span> 
									<span class="sale-num"><?= $livraison->libelle() ?></span>
								</li>
								<li>
									<span class="sale-info">Designation</span> 
									<span class="sale-num"><?= $livraison->designation() ?></span>
								</li>
								<li>
									<span class="sale-info">Quantité</span> 
									<span class="sale-num"><?= $livraison->quantite() ?></span>
								</li>
								<li>
									<span class="sale-info">Prix unitaire</span> 
									<span class="sale-num"><?= $livraison->prixUnitaire() ?></span>
								</li>
								<li>
									<span class="sale-info">Total</span> 
									<span class="sale-num"><?= $livraison->prixUnitaire()*$livraison->quantite() ?></span>
								</li>
							</ul>
						</div>
					 </div>
					 </div>
				   </div>
				</div>
				<!-- updateFournisseur box begin-->
				<div id="updateFournisseur<?= $fournisseur->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h3>Modifier les informations du fournisseur </h3>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" action="../controller/FournisseurUpdateController.php" method="post">
							<p>Êtes-vous sûr de vouloir modifier les infos du fournisseur <strong><?= $fournisseur->nom() ?></strong> ?</p>
							<div class="control-group">
								<label class="control-label">Nom</label>
								<div class="controls">
									<input type="text" name="nom" value="<?= $fournisseur->nom() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Adresse</label>
								<div class="controls">
									<input type="text" name="adresse" value="<?= $fournisseur->adresse() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Tél.1</label>
								<div class="controls">
									<input type="text" name="telephone1" value="<?= $fournisseur->telephone1() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Tél.2</label>
								<div class="controls">
									<input type="text" name="telephone2" value="<?= $fournisseur->telephone2() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Fax</label>
								<div class="controls">
									<input type="text" name="fax" value="<?= $fournisseur->fax() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Email</label>
								<div class="controls">
									<input type="text" name="email" value="<?= $fournisseur->email() ?>" />
								</div>	
							</div>
							<div class="control-group">
								<input type="hidden" name="idFournisseur" value="<?= $fournisseur->id() ?>" />
								<input type="hidden" name="codeLivraison" value="<?= $livraison->code() ?>" />
								<div class="controls">	
									<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
									<button type="submit" class="btn red" aria-hidden="true">Oui</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- updateFournisseur box end -->
				<!-- updateLivraison box begin-->
				<div id="updateLivraison<?= $livraison->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h3>Modifier les informations de la livraison </h3>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" action="../controller/LivraisonUpdateController.php" method="post">
							<p>Êtes-vous sûr de vouloir modifier la livraison <strong>N°<?= $livraison->id() ?></strong>  ?</p>
							<div class="control-group">
								<label class="control-label">Date Livraison</label>
								<div class="controls">
									<input type="text" name="dateLivraison" value="<?= $livraison->dateLivraison() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Libelle</label>
								<div class="controls">
									<input type="text" name="libelle" value="<?= $livraison->libelle() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Désignation</label>
								<div class="controls">
									<input type="text" name="designation" value="<?= $livraison->designation() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Quantité</label>
								<div class="controls">
									<input type="text" id="quantite" name="quantite" value="<?= $livraison->quantite() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Prix unitaire</label>
								<div class="controls">
									<input type="text" id="prixUnitaire" name="prixUnitaire" value="<?= $livraison->prixUnitaire() ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Total</label>
								<div class="controls">
									<input type="text" id="total" name="total" value="<?= $livraison->quantite()*$livraison->prixUnitaire() ?>" />
								</div>
							</div>
							<div class="control-group">
								<input type="hidden" name="codeLivraison" value="<?= $livraison->code() ?>" />
								<input type="hidden" name="idLivraison" value="<?= $livraison->id() ?>" />
								<div class="controls">	
									<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
									<button type="submit" class="btn red" aria-hidden="true">Oui</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- updateLivraison box end -->		
				<?php 
				}
				else{
				?>
				<div class="alert alert-error">
					<button class="close" data-dismiss="alert"></button>
					<strong>Erreur système : </strong>Ce projet n'existe pas sur votre système. Pour plus d'informations consulter votre administrateur.		
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
			$('.hidenBlock').hide();
			App.init();
		});
	</script>
	<script>
		$(function(){
	        $('#prixUnitaire, #quantite').change(function(){
	            var prixUnitaire = parseFloat($('#prixUnitaire').val());
	            var quantite = parseFloat($('#quantite').val());
	            var total = 0;
	            total = prixUnitaire * quantite;
	            $('#total').val(total);
	        });
    	});
		$(document).ready(function() {
			$('.typeBien').change(function(){
				$('.hidenBlock').show();
				var typeBien = $(this).val();
				var data = 'typeBien='+typeBien;
				$.ajax({
					type: "POST",
					url: "types-biens.php",
					data: data,
					cache: false,
					success: function(html){
						$('#bien').html(html);
						if(typeBien=="appartement"){
							$('#nomBienLabel').text("Appartements");	
						}
						else{
							$('#nomBienLabel').text("Locaux commerciaux");
						}
					}
				});
			});
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