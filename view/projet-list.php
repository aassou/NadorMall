<?php
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
    	//les sources
    	$projetsManager = new ProjetManager(PDOFactory::getMysqlConnection());
		$projetPerPage = 4;
        $projetNumber = ($projetsManager->getProjetsNumber());
        $pageNumber = ceil($projetNumber/$projetPerPage);
        $p = 1;
        if(isset($_GET['p']) and ($_GET['p']>0 and $_GET['p']<=$pageNumber)){
            $p = $_GET['p'];
        }
        else{
            $p = 1;
        }
        $begin = ($p - 1) * $projetPerPage;
        $projets = $projetsManager->getProjetsByLimits($begin, $projetPerPage);
        $pagination = paginate('projet-list.php', '?p=', $pageNumber, $p);
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
							<li><i class="icon-home"></i> <a>Accueil</a><i class="icon-angle-right"></i></li>
							<li><i class="icon-briefcase"></i> <a>Gestion des projets</a><i class="icon-angle-right"></i></li>
							<li><a>Liste des projets</a></li>
						</ul>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<?php if(isset($_SESSION['user-delete-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['user-delete-success'] ?>		
							</div>
                         <?php } 
                         	unset($_SESSION['user-delete-success']);
                         ?>
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="tab-pane" id="tab_1_4">
							<div class="row-fluid add-portfolio">
								<div class="pull-left">
									<span><?= $projetNumber ?> Projets en Total</span>
								</div>
								<div class="pull-right">
									<a href="projet-add.php" class="btn icn-only green">Ajouter Nouveau Projet <i class="icon-plus-sign m-icon-white"></i></a> 									
								</div>
							</div>
							<!--end add-portfolio-->
							
							<div class="row-fluid">
								<?= $pagination ?>
							</div>
							<br>
							<?php
							foreach($projets as $projet){
							?>
							<div class="row-fluid portfolio-block" id="<?= $projet->id() ?>">
								<div class="span5 portfolio-text">
									<img src="assets/img/logo_company.png" alt="" />
									<div class="portfolio-text-info">
										<h4><?= $projet->nom() ?></h4>
										<p><?= $projet->description() ?></p>
										<a href="projet-update.php?idProjet=<?= $projet->id() ?>" class="btn red-stripe">
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
									<!--div class="portfolio-info">
										<a href="livraison-add.php?idProjet=<?= $projet->id() ?>" class="btn blue-stripe">Créer nouvelle Livraison</a>
									</div-->
									<!--div class="portfolio-info">
										<a href="fournisseur-reglement.php?idProjet=<?= $projet->id() ?>" class="btn blue-stripe">Réglement&nbsp;&nbsp;&nbsp;fournisseurs</a>
									</div-->
									<div class="portfolio-info">
										<a href="employes-projet.php?idProjet=<?= $projet->id() ?>" class="btn">Gérer employés du projet</a>
									</div>
									<div class="portfolio-info">
										<a class="btn brown arabic" href="contrats-travail.php?idProjet=<?= $projet->id() ?>" class="btn">تنظيم عقود العمل</a>
									</div>
								</div>
								<div class="span2 portfolio-btn">
									<a href="suivi-projets.php?idProjet=<?= $projet->id() ?>" class="btn bigicn-only"><span>Suivi</span></a>								
								</div>
							</div>
							<br><br>
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
							<?php }//end foreach loop for projets elements ?>
						</div>
						<?= $pagination ?>
					</div>
				</div>
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
else if(isset($_SESSION['userMerlaTrav']) and $_SESSION->profil()!="admin"){
	header('Location:dashboard.php');
}
else{
    header('Location:index.php');    
}
?>