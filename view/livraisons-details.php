<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    if( isset($_SESSION['userMerlaTrav']) ){
    	//classManagers
    	$projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
		$fournisseurManager = new FournisseurManager(PDOFactory::getMysqlConnection());
		$livraisonManager = new LivraisonManager(PDOFactory::getMysqlConnection());
		$livraisonDetailManager = new LivraisonDetailManager(PDOFactory::getMysqlConnection());
		$reglementsFournisseurManager = new ReglementFournisseurManager(PDOFactory::getMysqlConnection());
		//classes and vars
		$livraisonDetailNumber = 0;
		$totalReglement = 0;
		$totalLivraison = 0;
		$titreLivraison ="Détail de la livraison";
		$livraison = "Vide";
		$fournisseur = "Vide";
		$nomProjet = "Non mentionné";
        $idProjet = "";
        $fournisseurs = $fournisseurManager->getFournisseurs();
        $projets = $projetManager->getProjets();
		if( isset($_GET['codeLivraison']) ){
			$livraison = $livraisonManager->getLivraisonByCode($_GET['codeLivraison']);
			$fournisseur = $fournisseurManager->getFournisseurById($livraison->idFournisseur());
			if ( $livraison->idProjet() != 0 ) {
			    $nomProjet = $projetManager->getProjetById($livraison->idProjet())->nom();
                $idProjet = $projetManager->getProjetById($livraison->idProjet())->id();    
			} 
            else {
                $nomProjet = "Non mentionné";
                $idProjet = "";    
            }
            
			$livraisonDetail = $livraisonDetailManager->getLivraisonsDetailByIdLivraison($livraison->id());
            $totalLivraisonDetail = 
            $livraisonDetailManager->getTotalLivraisonByIdLivraison($livraison->id());
            $nombreArticle = 
            $livraisonDetailManager->getNombreArticleLivraisonByIdLivraison($livraison->id());
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
	<div class="page-container row-fluid sidebar-closed">
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
							Gestion des livraisons Fournisseur : <strong><?= $fournisseur->nom() ?></strong> 
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="dashboard.php">Accueil</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<i class="icon-truck"></i>
								<a href="livraisons-group.php">Gestion des livraisons</a>
								<i class="icon-angle-right"></i>
							</li>
							<li>
                                <a href="livraisons-fournisseur-mois.php?idFournisseur=<?= $livraison->idFournisseur() ?>">
                                    Livraisons de <strong><?= $fournisseurManager->getFournisseurById($livraison->idFournisseur())->nom() ?></strong>
                                </a>
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a href="livraisons-fournisseur-mois-list.php?idFournisseur=<?= $livraison->idFournisseur() ?>&mois=<?= $_GET['mois'] ?>&annee=<?= $_GET['annee'] ?>">
                                    <strong><?= $_GET['mois'] ?>/<?= $_GET['annee'] ?></strong>
                                </a>
                                <i class="icon-angle-right"></i>
                            </li>
							<li><a>Détails de Livraison</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN ALERT MESSAGES -->
						 <?php 
						 if( isset($_SESSION['livraison-detail-action-message']) 
                         and isset($_SESSION['livraison-detail-type-message']) ){ 
						     $message = $_SESSION['livraison-detail-action-message'];
                             $typeMessage = $_SESSION['livraison-detail-type-message'];
						 ?>
							<div class="alert alert-<?= $typeMessage ?>">
								<button class="close" data-dismiss="alert"></button>
								<?= $message ?>		
							</div>
						 <?php } 
							unset($_SESSION['livraison-detail-action-message']);
                            unset($_SESSION['livraison-detail-type-message']);
						 ?>
						 <!-- END  ALERT MESSAGES -->
						<?php
						$updateLink = "";
                        if ( 
                            $_SESSION['userMerlaTrav']->profil() == "admin" ||
                            $_SESSION['userMerlaTrav']->profil() == "manager" ||
                            $_SESSION['userMerlaTrav']->profil() == "user"
                            ) {
                            $updateLink = "#updateLivraison";    
                        }
                        ?>
						<div class="portlet">
							<!-- BEGIN PORTLET BODY -->
							<div class="portlet-body">
								<!-- BEGIN Livraison Form -->
								<div class="row-fluid">
								    <div class="span3">
                                      <div class="control-group">
                                         <div class="controls">
                                           <a class="btn" href="<?= $updateLink ?>" data-toggle="modal" style="width: 245px">
                                               <strong>N° BL : <?= $livraison->libelle() ?></strong>
                                           </a>
                                         </div>
                                      </div>
                                   </div>
                                   <div class="span3">
                                      <div class="control-group">
                                         <div class="controls">
                                            <a class="btn" href="<?= $updateLink ?>" data-toggle="modal" style="width: 245px">
                                                <strong>Nombre Articles : <?= $nombreArticle ?></strong>
                                            </a>   
                                         </div>
                                      </div>
                                   </div>
                                    <div class="span3">
                                      <div class="control-group">
                                         <div class="controls">
                                            <a class="btn" href="<?= $updateLink ?>" data-toggle="modal" style="width: 245px">
                                                <strong>Date Livraison : <?= date('d/m/Y', strtotime($livraison->dateLivraison())) ?></strong>
                                            </a>
                                         </div>
                                      </div>
                                   </div>
									<div class="span3">
									  <div class="control-group">
										 <div class="controls">
											<a class="btn" href="<?= $updateLink ?>" data-toggle="modal" style="width: 245px">
											    <strong>Projet : <?= $nomProjet ?></strong>
										    </a>   
										 </div>
									  </div>
								   </div>
								</div>
							<!-- END Livraison Form -->
							<!-- updateLivraison box begin-->
                            <div id="updateLivraison" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h3>Modifier les informations de la livraison </h3>
                                </div>
                                <form id="update-livraison-form" class="form-horizontal" action="../controller/LivraisonActionController.php" method="post">
                                    <div class="modal-body">
                                        <div class="control-group">
                                            <label class="control-label">Fournisseur</label>
                                            <div class="controls">
                                                <select name="idFournisseur">
                                                    <option value="<?= $fournisseur->id() ?>"><?= $fournisseur->nom() ?></option>
                                                    <option disabled="disabled">------------</option>
                                                    <?php foreach($fournisseurs as $fourn){ ?>
                                                    <option value="<?= $fourn->id() ?>"><?= $fourn->nom() ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Projet</label>
                                            <div class="controls">
                                                <select name="idProjet">
                                                    <option value="<?= $idProjet ?>"><?= $nomProjet ?></option>
                                                    <option disabled="disabled">------------</option>
                                                    <?php foreach($projets as $pro){ ?>
                                                    <option value="<?= $pro->id() ?>"><?= $pro->nom() ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Date Livraison</label>
                                            <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                <input name="dateLivraison" id="dateLivraison" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= $livraison->dateLivraison() ?>" />
                                                <span class="add-on"><i class="icon-calendar"></i></span>
                                             </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">N° BL</label>
                                            <div class="controls">
                                                <input required="required" id="libelle" type="text" name="libelle" value="<?= $livraison->libelle() ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="control-group">
                                            <div class="controls">  
                                                <input type="hidden" name="action" value="update" />
                                                <input type="hidden" name="source" value="details-livraison" />
                                                <input type="hidden" name="mois" value="<?= $_GET['mois'] ?>" />
                                                <input type="hidden" name="annee" value="<?= $_GET['annee'] ?>" />
                                                <input type="hidden" name="codeLivraison" value="<?= $livraison->code() ?>" />
                                                <input type="hidden" name="idLivraison" value="<?= $livraison->id() ?>" />    
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- addLivraison box end -->
							<!-- BEGIN Ajouter Article Link -->
							<a target="_blank" href="../controller/LivraisonDetailPrintController.php?idLivraison=<?= $livraison->id() ?>&societe=1" class="get-down btn blue pull-right">
                                <i class="icon-print"></i>&nbsp;Bon de livraison
                            </a>
                            <?php
                            if ( 
                                $_SESSION['userMerlaTrav']->profil() == "admin" ||
                                $_SESSION['userMerlaTrav']->profil() == "manager" ||
                                $_SESSION['userMerlaTrav']->profil() == "user"
                                ) {
                            ?>
							<a class="btn green" href="#addArticle" data-toggle="modal" data-id="">
								Ajouter un article <i class="icon-plus "></i>
							</a>
							<?php
                            }
                            ?>
							<!-- END Ajouter Article Link -->
							<!-- BEGIN addArticle Box -->
							<div id="addArticle" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<h3>Ajouter un artcile </h3>
								</div>
								<form id="add-detail-livraison-form" class="form-horizontal" action="../controller/LivraisonDetailsActionController.php" method="post">
								    <div class="modal-body">
										<div class="control-group">
											<label class="control-label">Désignation</label>
											<div class="controls">
												<input type="text" name="designation" value="" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Prix Unitaire</label>
											<div class="controls">
												<input required="required" type="text" id="prixUnitaire" name="prixUnitaire" value="" />
											</div>	
										</div>
										<div class="control-group">
											<label class="control-label">Quantité</label>
											<div class="controls">
												<input required="required" type="text" id="quantite" name="quantite" value="" />
											</div>	
										</div>
									</div>
									<div class="modal-footer">
									    <div class="control-group">
                                            <div class="controls">  
                                                <input type="hidden" name="action" value="add" />
                                                <input type="hidden" name="mois" value="<?= $_GET['mois'] ?>" />
                                                <input type="hidden" name="annee" value="<?= $_GET['annee'] ?>" />
                                                <input type="hidden" name="idLivraison" value="<?= $livraison->id() ?>" />
                                                <input type="hidden" name="codeLivraison" value="<?= $livraison->code() ?>" />
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
								    </div>
								</form>
							</div>
							<!-- END addArticle BOX -->
							<br><br>
							<!-- BEGIN LivraisonDetail TABLE -->
							<?php
							if( 1 ){
							?>
							<table class="table table-striped table-bordered table-hover">
							<tr>
							    <?php
                                if ( 
                                    $_SESSION['userMerlaTrav']->profil() == "admin" ||
                                    $_SESSION['userMerlaTrav']->profil() == "manager" ||
                                    $_SESSION['userMerlaTrav']->profil() == "user"
                                    ) {
                                ?>
							    <th class="hidden-phone" style="width: 10%">Actions</th>
							    <?php
                                }
                                ?>
								<th style="width: 20%">Désignation</th>
								<th style="width: 10%">Qté</th>
								<th style="width: 30%">PrixUni</th>
								<th style="width: 30%">Total</th>
							</tr>
							<?php
							foreach($livraisonDetail as $detail){
							?>
							<tr>
							    <?php
                                if ( 
                                    $_SESSION['userMerlaTrav']->profil() == "admin" ||
                                    $_SESSION['userMerlaTrav']->profil() == "manager" ||
                                    $_SESSION['userMerlaTrav']->profil() == "user"
                                    ) {
                                ?>
							    <td class="hidden-phone">
                                    <a class="btn mini green" href="#updateLivraisonDetail<?= $detail->id();?>" data-toggle="modal" data-id="<? $detail->id(); ?>">
                                        <i class="icon-refresh "></i>
                                    </a>
                                    <a class="btn mini red" href="#deleteLivraisonDetail<?= $detail->id();?>" data-toggle="modal" data-id="<? $detail->id(); ?>">
                                        <i class="icon-remove "></i>
                                    </a>
                                </td>
                                <?php
                                }
                                ?>
								<td>
									<?= $detail->designation() ?>
								</td>
								<td>
									<?= $detail->quantite() ?>
								</td>
								<td>
									<?= number_format($detail->prixUnitaire(), '2', ',', ' ') ?>&nbsp;DH
								</td>
								<td>
									<?= number_format($detail->prixUnitaire() * $detail->quantite(), '2', ',', ' ') ?>&nbsp;DH
								</td>
							</tr>
							<!-- BEGIN  updateLivraisonDetail BOX -->
							<div id="updateLivraisonDetail<?= $detail->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<h3>Modifier les détails de livraison </h3>
								</div>
								<form id="update-detail-livraison-form" class="form-horizontal" action="../controller/LivraisonDetailsActionController.php" method="post">
								    <div class="modal-body">
										<div class="control-group">
											<label class="control-label" for="designation">Désignation</label>
											<div class="controls">
												<input name="designation" class="m-wrap" type="text" value="<?= $detail->designation() ?>" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="quantite">Quantité</label>
											<div class="controls">
												<input required="required" id="quantite" name="quantite" class="m-wrap" type="text" value="<?= $detail->quantite() ?>" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="prixUnitaire">Prix Unitaire</label>
											<div class="controls">
												<input required="required" id="prixUnitaire" name="prixUnitaire" class="m-wrap" type="text" value="<?= $detail->prixUnitaire() ?>" />
											</div>
										</div>
									</div>
									<div class="modal-footer">
									    <div class="control-group">
                                            <input type="hidden" name="action" value="update" />
                                            <input type="hidden" name="mois" value="<?= $_GET['mois'] ?>" />
                                            <input type="hidden" name="annee" value="<?= $_GET['annee'] ?>" />
                                            <input type="hidden" name="idLivraisonDetail" value="<?= $detail->id() ?>" />
                                            <input type="hidden" name="codeLivraison" value="<?= $livraison->code() ?>" />
                                            <div class="controls">  
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
								    </div>
								</form>
							</div>
							<!-- END  update LivraisonDetail   BOX -->
							<!-- BEGIN  delete LivraisonDetail BOX -->
							<div id="deleteLivraisonDetail<?= $detail->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<h3>Supprimer Article</h3>
								</div>
								<form class="form-horizontal loginFrm" action="../controller/LivraisonDetailsActionController.php" method="post">
								    <div class="modal-body">
										<p>Êtes-vous sûr de vouloir supprimer cet article ?</p>
									</div>
									<div class="modal-footer">
									    <div class="control-group">
                                            <input type="hidden" name="action" value="delete" />
                                            <input type="hidden" name="mois" value="<?= $_GET['mois'] ?>" />
                                            <input type="hidden" name="annee" value="<?= $_GET['annee'] ?>" />
                                            <input type="hidden" name="idLivraisonDetail" value="<?= $detail->id() ?>" />
                                            <input type="hidden" name="codeLivraison" value="<?= $livraison->code() ?>" />
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
								    </div>
								</form>
							</div>
							<!-- END delete LivraisonDetail BOX -->
							<?php
							}//end foreach
							?>
							</table>
							<table class="table table-striped table-bordered table-advance table-hover">
                                <tbody>
                                    <tr>
                                        <th style="width: 70%"><strong>Grand Total</strong></th>
                                        <th style="width: 30%"><strong><a><?= number_format($totalLivraisonDetail, 2, ',', ' ') ?>&nbsp;DH</a></strong></th>
                                    </tr>
                                </tbody>
                            </table>
							<?php
							}//end if
							?>
							<!-- END LivraisonDetail TABLE -->
							</div>
							<!-- END  PORTLET BODY  -->
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
			App.init();
		});
		$("#add-detail-livraison-form").validate({
            rules:{
                quantite:{
                    number: true,
                    required:true
                },
                prixUnitaire:{
                    number: true,
                    required:true
                }
            },
            errorClass: "error-class",
            validClass: "alid-class"
        });
        $("#update-detail-livraison-form").validate({
            rules:{
                quantite:{
                    number: true,
                    required:true
                },
                prixUnitaire:{
                    number: true,
                    required:true
                }
            },
            errorClass: "error-class",
            validClass: "valid-class"
        });
	</script>
</body>
<!-- END BODY -->
</html>
<?php
}
/*else if(isset($_SESSION['userMerlaTrav']) and $_SESSION->profil()!="admin"){
	header('Location:dashboard.php');
}*/
else{
    header('Location:index.php');    
}
?>