<?php 
    include('../app/classLoad.php');    
    //classes loading end
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
    	//les sources
    	$idProjet = 0;
    	$projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
		if(isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId()){
			$idProjet = $_GET['idProjet'];
			$projet = $projetManager->getProjetById($idProjet);
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
							Gestion des Fournisseurs
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
							<li><a>Gestion des fournisseurs</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<?php if($idProjet!=0){ ?>
				<div class="row-fluid">
					<div class="span12">
						<div class="tab-pane active" id="tab_1">
							<div class="row-fluid add-portfolio">
								<div class="pull-left">
									<a href="projet-list.php" class="btn icn-only green"><i class="m-icon-swapleft m-icon-white"></i> Retour vers Liste des projets</a>
								</div>
							</div>
	                         <?php if(isset($_SESSION['fournisseur-add-error'])){ ?>
	                         	<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<?= $_SESSION['fournisseur-add-error'] ?>		
								</div>
	                         <?php } 
	                         	unset($_SESSION['fournisseur-add-error']);
	                         ?>
                           <div class="portlet box grey">
                              <div class="portlet-title">
                                 <h4><i class="icon-edit"></i>Nouveau Fournisseur/Livraison pour le projet : <strong><?= $projet->nom() ?></strong></h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="../controller/FournisseurAddController.php" method="POST" class="horizontal-form">
                                    <div class="row-fluid">
                                    	<div class="span12">
                                    		<img src="assets/img/form_wizard_fournisseur_livraison_1.png">
                                    	</div>
                                    </div>
                                    <div class="row-fluid">
                                    	<div class="span12">
                                    		<div class="progress progress-striped progress-success">
												<div style="width: 50%;" class="bar"></div>
											</div>
                                    	</div>
                                    </div>
                                    <div class="row-fluid">
                                       <div class="span4">
                                          <div class="control-group autocomplet_container">
                                             <label class="control-label" for="nom">Nom</label>
                                             <div class="controls">
                                                <input type="text" id="nomFournisseur" name="nom" class="m-wrap span12" onkeyup="autocompletFournisseur()">
                                                <ul id="fournisseurList"></ul>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="adresse">Adresse</label>
                                             <div class="controls">
                                                <input type="text" id="adresse" name="adresse" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="telephone1">Téléphone 1</label>
                                             <div class="controls">
                                                <input type="text" id="telephone1" name="telephone1" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                    	<div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="telephone2">Téléphone 2</label>
                                             <div class="controls">
                                                <input type="text" id="telephone2" name="telephone2" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="email">Email</label>
                                             <div class="controls">
                                                <input type="text" id="email" name="email" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="fax">Fax</label>
                                             <div class="controls">
                                                <input type="text" id="fax" name="fax" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                    	<input type="hidden" id="idProjet" name="idProjet" value="<?= $idProjet ?>" class="m-wrap span12" />
                                    	<input type="hidden" id="idFournisseur" name="idFournisseur" class="m-wrap span12" />
                                       	<button type="reset" class="btn red">Annuler</button>
                                       	<button type="submit" class="btn black">Continuer <i class="m-icon-swapright m-icon-white"></i></button>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
                        </div>
					</div>
				</div>
				<?php }
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