<?php 
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
    	//les sources
    	$projetsManager = new ProjetManager(PDOFactory::getMysqlConnection());
		if( isset($_GET['idProjet']) ){
			$idProjet = $_GET['idProjet'];
			$projet = $projetsManager->getProjetById($idProjet);
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
<body class="fixed-top">
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<?php include('../include/top-menu.php'); ?>
	</div>
	<div class="page-container row-fluid">
		<?php include('../include/sidebar.php'); ?>
		<div class="page-content">			
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<ul class="breadcrumb">
							<li><i class="icon-home"></i> <a>Accueil</a><i class="icon-angle-right"></i></li>
							<li><i class="icon-briefcase"></i> <a>Gestion des projets</a><i class="icon-angle-right"></i></li>
							<li><a><strong>Liste des projets</strong></a></li>
						</ul>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<div class="tab-pane active" id="projetTab">
                           <div class="portlet box purple">
                              <div class="portlet-title">
                                 <h4><i class="icon-search"></i>Chercher un projet</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                              	<?php if(isset($_SESSION['projet-search-error'])){ ?>
			                 	<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<?= $_SESSION['projet-search-error'] ?>		
								</div>
				                 <?php } 
				                 unset($_SESSION['projet-search-error']);
				                 ?>
                                 <form action="../controller/SearchProjetController.php" method="POST" class="horizontal-form">
                                    <div class="row-fluid">
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group autocomplet_container">
                                             <label class="control-label" for="nomProjet">Nom du projet</label>
                                             <div class="controls">
                                                <input type="text" id="nomProjet" name="nomProjet" class="m-wrap span12" onkeyup="autocompletProjet()">
                                                <ul id="projetList"></ul>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                       <button type="submit" class="btn black"><i class="icon-search"></i> Lancer</button>
                                    </div>
                                 </form> 
                              </div>
                           </div>
                        </div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<?php if ( isset($_SESSION['projet-delete-success'])){ ?>
                        <div class="alert alert-success"><button class="close" data-dismiss="alert"></button><?= $_SESSION['projet-delete-success'] ?></div>
                        <?php } unset($_SESSION['projet-delete-success']); ?>
                        <?php if(isset($_SESSION['projet-update-success'])){ ?>
                        <div class="alert alert-success"><button class="close" data-dismiss="alert"></button><?= $_SESSION['projet-update-success'] ?></div>
                        <?php } unset($_SESSION['projet-update-success']); ?>
                        <?php if(isset($_SESSION['projet-update-error'])){ ?>
                        <div class="alert alert-error"><button class="close" data-dismiss="alert"></button><?= $_SESSION['projet-update-error'] ?></div>
                        <?php } unset($_SESSION['projet-update-error']); ?>
						<div class="tab-pane" id="tab_1_4">
							<div class="row-fluid portfolio-block" id="<?= $projet->id() ?>">
								<div class="span5 portfolio-text">
									<img src="assets/img/logo_company.png" alt="" />
									<div class="portfolio-text-info">
										<h4><?= $projet->nom() ?></h4>
										<p><?= $projet->description() ?></p>
										<a href="#update<?= $projet->id() ?>" class="btn red-stripe" data-toggle="modal" data-id="<?php echo $projet->id(); ?>">
											<i class="icon-refresh"></i>  Modifier
										</a><br><br>
										<a href="#delete<?= $projet->id() ?>" class="btn green-stripe" data-toggle="modal" data-id="<?php echo $projet->id(); ?>">
											<i class="icon-remove"></i> Supprimer
										</a>
									</div>
								</div>
								<div class="span5" style="overflow:hidden;">
									<div class="portfolio-info">
										<a href="terrain.php?idProjet=<?= $projet->id() ?>" class="btn black">Terrain</a>
										<a href="appartements.php?idProjet=<?= $projet->id() ?>" class="btn blue">Appartements</a>
									</div>
									<div class="portfolio-info">
										<a href="locaux.php?idProjet=<?= $projet->id() ?>" class="btn purple">Les locaux commerciaux</a>
									</div>
									<div class="portfolio-info">
										<a href="clients-add.php?idProjet=<?= $projet->id() ?>" class="btn red">Créer Clients et Contrats</a>
									</div>
									<div class="portfolio-info">
										<a href="contrats-list.php?idProjet=<?= $projet->id() ?>" class="btn green">Listes Clients et Contrats</a>
										<!--a href="#" class="btn mini yellow">Opérations</a-->
									</div>
									<div class="portfolio-info">
										<a href="livraisons-list.php?idProjet=<?= $projet->id() ?>" class="btn yellow">Livraisons / Fournisseurs</a>
										<!--a href="#" class="btn">Fournisseurs</a-->
									</div>
									<!--div class="portfolio-info">
										<a href="fournisseur-add.php?idProjet=<?= $projet->id() ?>" class="btn orange">Gestion des fournisseurs</a>
									</div-->
									<div class="portfolio-info">
										<a href="livraison-add.php?idProjet=<?= $projet->id() ?>" class="btn blue-stripe">Créer nouvelle Livraison</a>
									</div>
									<!--div class="portfolio-info">
										<a href="fournisseur-reglement.php?idProjet=<?= $projet->id() ?>" class="btn blue-stripe">Réglement&nbsp;&nbsp;&nbsp;fournisseurs</a>
									</div-->
									<div class="portfolio-info">
										<a href="employes-projet.php?idProjet=<?= $projet->id() ?>" class="btn black">Gérer employés du projet</a>
									</div>
								</div>
								<div class="span2 portfolio-btn">
									<a href="suivi-projets.php?idProjet=<?= $projet->id() ?>" class="btn bigicn-only"><span>Suivi</span></a>								
								</div>
							</div>
							<br><br>
							<!-- update box begin-->
							<div id="update<?php echo $projet->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<h3>Modifier Infos Projet <?= $projet->nom() ?></h3>
								</div>
								<div class="modal-body">
									<form class="form-horizontal loginFrm" action="../controller/ProjetUpdateController.php?p=99" method="post">
										<p>Êtes-vous sûr de vouloir modifier les infos du projet <strong><?= $projet->nom() ?></strong> ?</p>
	                                      <div class="control-group">
	                                         <label class="control-label" for="nomProjet">Nom du Projet</label>
	                                         <div class="controls">
	                                            <input type="text" id="nomProjet" name="nomProjet" class="m-wrap span12" value="<?= $projet->nom() ?>">
	                                         </div>
	                                      </div>
	                                      <div class="control-group">
	                                         <label class="control-label" for="budget">Budget</label>
	                                         <div class="controls">
	                                            <input type="text" id="budget" name="budget" class="m-wrap span12" value="<?= $projet->budget() ?>">
	                                         </div>
	                                      </div>
	                                      <div class="control-group">
	                                         <label class="control-label" for="superficie">Superficie</label>
	                                         <div class="controls">
	                                            <input type="text" id="superficie" name="superficie" class="m-wrap span12" value="<?= $projet->superficie() ?>">
	                                         </div>
	                                      </div>
	                                      <div class="control-group">
	                                         <label class="control-label" for="adresse">Adresse</label>
	                                         <div class="controls">
	                                         	<textarea name="adresse" class="large m-wrap" rows="3"><?= $projet->adresse() ?></textarea>
	                                         </div>
	                                      </div>
	                                      <div class="control-group">
	                                         <label class="control-label" for="description">Description</label>
	                                         <div class="controls">
												<textarea name="description" class="large m-wrap" rows="3"><?= $projet->description() ?></textarea>
	                                         </div>
	                                      </div>
										<div class="control-group">
											<label class="right-label"></label>
											<input type="hidden" name="idProjet" value="<?= $projet->id() ?>" />
											<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
											<button type="submit" class="btn red" aria-hidden="true">Oui</button>
										</div>
									</form>
								</div>
							</div>
							<!-- update box end -->
							<!-- delete box begin-->
							<div id="delete<?php echo $projet->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<h3>Supprimer Projet <?= $projet->nom() ?></h3>
								</div>
								<div class="modal-body">
									<form class="form-horizontal loginFrm" action="../controller/ProjetDeleteController.php" method="post">
										<p>Êtes-vous sûr de vouloir supprimer ce projet <strong><?= $projet->nom() ?></strong> ?</p>
										<div class="control-group">
											<label class="right-label"></label>
											<input type="hidden" name="idProjet" value="<?= $projet->id() ?>" />
											<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
											<button type="submit" class="btn red" aria-hidden="true">Oui</button>
										</div>
									</form>
								</div>
							</div>
							<!-- delete box end -->		
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include('../include/footer.php') ?>
	<?php include('../include/scripts.php') ?>
	<script type="text/javascript" src="script.js"></script>		
	<script>jQuery(document).ready(function() { App.init(); });</script>
</body>
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