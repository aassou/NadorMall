<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
    	$idProjet = 0;
    	$projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
		$fournisseurManager = new FournisseurManager(PDOFactory::getMysqlConnection());
		$livraisonManager = new LivraisonManager(PDOFactory::getMysqlConnection());
		$showFournisseurSelect = 0;
		$showProjetSelect = 0;
		if((isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId())
		and ( isset($_GET['idFournisseur']) and ($_GET['idFournisseur'])>0 and $_GET['idFournisseur']<=$fournisseurManager->getLastId() )){
			$idProjet = $_GET['idProjet'];
			$idFournisseur = $_GET['idFournisseur'];
			$projet = $projetManager->getProjetById($idProjet);
			$fournisseur = $fournisseurManager->getFournisseurById($idFournisseur);
			$showFournisseurSelect = 1;
			$showProjetSelect = 1;
		}
		else if( isset($_GET['idFournisseur']) and ($_GET['idFournisseur'])>0 and $_GET['idFournisseur']<=$fournisseurManager->getLastId() ){
			$idFournisseur = $_GET['idFournisseur'];
			$projets = $projetManager->getProjets();
			$fournisseur = $fournisseurManager->getFournisseurById($idFournisseur);
			$showFournisseurSelect = 1;
			$showProjetSelect = 0;
		}
		else if( isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId() ){
			$idProjet = $_GET['idProjet'];
			$projet = $projetManager->getProjetById($idProjet);
			$fournisseurs = $fournisseurManager->getFournisseurs();
			$showFournisseurSelect = 0;
			$showProjetSelect = 1;
		}
		else{
			$projets = $projetManager->getProjets();
			$fournisseurs = $fournisseurManager->getFournisseurs();
			$showFournisseurSelect = 0;
			$showProjetSelect = 0;
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
							Gestion des Livraisons
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
							<li><a>Gestion des livraisons</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<div class="tab-pane active" id="tab_1">
							<div class="row-fluid add-portfolio">

								<div class="pull-left">
									<a href="livraisons-list.php?idProjet=<?= $projet->id() ?>" class="btn icn-only green"><i class="m-icon-swapleft m-icon-white"></i> Retour vers Liste des livraisons</a>
								</div>
								<div class="pull-right">
									<a href="projet-list.php" class="btn icn-only green">Allez vers Liste des projets <i class="m-icon-swapright m-icon-white"></i></a>
								</div>
							</div>
	                         <?php if(isset($_SESSION['livraison-add-error'])){ ?>
	                         	<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<?= $_SESSION['livraison-add-error'] ?>		
								</div>
	                         <?php } 
	                         	unset($_SESSION['livraison-add-error']);
	                         ?>
                           <div class="portlet box grey">
                              <div class="portlet-title">
                                 <h4><i class="icon-edit"></i>
                                 	<?php
                                 	$messageProjet = "Nouvelle Livraison";
                                 	if($showProjetSelect!=0){
                                 		$messageProjet = "Nouvelle Livraison pour le projet : <strong>".$projet->nom()."</strong>";
                                 	}
									echo $messageProjet;
                                 	?>
                                 </h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <form action="../controller/LivraisonAddController.php" method="POST" class="horizontal-form">
                                 	<?php
                                 		$messageFournisseur = "Création de livraison";
                                 		if($showFournisseurSelect!=0){
                                 			$messageFournisseur = "Création de livraison pour le fournisseur : ".$fournisseur->nom();
                                 		}
                                 	?>
                                    <legend><?= $messageFournisseur ?></legend>
                                     <div class="row-fluid">
                                     	<?php
                                     	if($showFournisseurSelect==0){
                                     	?>	
										<div class="span6">
	                                      <div class="control-group">
	                                         <label class="control-label" for="fournisseur">Fournisseur</label>
	                                         <div class="controls">
	                                            <select name="idFournisseur">
	                                            	<?php foreach($fournisseurs as $fournisseur){ ?>
	                                            	<option value="<?= $fournisseur->id() ?>"><?= $fournisseur->nom() ?></option>
	                                            	<?php } ?>
	                                            </select>
	                                         </div>
	                                      </div>
	                                   </div>
										<?php
                                     	}
                                     	?>
                                       	<?php
                                     	if($showProjetSelect==0){
                                     	?>	
										<div class="span6">
	                                      <div class="control-group">
	                                         <label class="control-label" for="pojet">Projet</label>
	                                         <div class="controls">
	                                            <select name="idProjet">
	                                            	<?php foreach($projets as $projet){ ?>
	                                            	<option value="<?= $projet->id() ?>"><?= $projet->nom() ?></option>
	                                            	<?php } ?>
	                                            </select>
	                                         </div>
	                                      </div>
	                                   </div>
										<?php
                                     	}
                                     	?>
                                    </div>
                                    <div class="row-fluid">
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="dateCreation">Date de livraison</label>
                                             <div class="controls">
                                                <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
				                                    <input name="dateLivraison" id="dateLivraison" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
				                                    <span class="add-on"><i class="icon-calendar"></i></span>
				                                 </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="libelle">Libellé</label>
                                             <div class="controls">
                                                <input type="text" id="libelle" name="libelle" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="designation">Désignation</label>
                                             <div class="controls">
                                                <input type="text" id="designation" name="designation" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                    	<div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="prixUnitaire">Prix unitaire</label>
                                             <div class="controls">
                                                <input type="text" id="prixUnitaire" name="prixUnitaire" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="quantite">Quantité</label>
                                             <div class="controls">
                                                <input type="text" id="quantite" name="quantite" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span4">
                                          <div class="control-group">
                                             <label class="control-label" for="total">Total</label>
                                             <div class="controls">
                                                <input type="text" id="total" name="total" class="m-wrap span12" value="0">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                    	<?php
                                    	if($showProjetSelect!=0){
                                    	?>
										<input type="hidden" id="idProjet" name="idProjet" value="<?= $idProjet ?>" class="m-wrap span12">
										<?php
                                    	}
                                    	?>
                                    	<?php
                                    	if($showFournisseurSelect!=0){
                                    	?>
										<input type="hidden" id="idFournisseur" name="idFournisseur" value="<?= $fournisseur->id() ?>" class="m-wrap span12">
										<?php
                                    	}
                                    	?>
                                       <button type="submit" class="btn black">Terminer <i class="icon-ok m-icon-white"></i></button>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                              </div>
                           </div>
                        </div>
					</div>
				</div>
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
            $('#paye').val(0);
            $('#reste').val(total);
        });
    });
    $(function(){
        $('#paye').change(function(){
            var paye = parseFloat($('#paye').val());
            var total = parseFloat($('#total').val());
            var reste = 0;
            reste = total - paye;
            $('#reste').val(reste);
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