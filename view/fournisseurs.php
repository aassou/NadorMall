<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
    	//les sources
    	$fournisseursManager = new FournisseurManager(PDOFactory::getMysqlConnection());
		$fournisseurNumber = $fournisseursManager->getFournisseurNumbers();
		if($fournisseurNumber!=0){
			$fournisseurs = $fournisseursManager->getFournisseurs();	 
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
							Gestion des fournisseurs
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="dashboard.php">Accueil</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
                                <i class="icon-wrench"></i>
                                <a href="configuration.php">Paramètrages</a>
                                <i class="icon-angle-right"></i>
                            </li>
							<li>
								<i class="icon-group"></i>
								<a>Gestion des fournisseurs</a>
							</li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <!--div class="pull-right get-down">
                            <a href="#addFournisseur" data-toggle="modal" class="btn blue">
                                Ajouter Nouveau Fournisseur <i class="icon-plus-sign "></i>
                            </a>
                        </div>
                        <div class="row-fluid">
                            <div class="input-box">
                                <input class="m-wrap" name="provider" id="provider" type="text" placeholder="Fournisseur..." />
                            </div>
                        </div-->
                        <!-- addFournisseur box begin-->
                        <div id="addFournisseur" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter un nouveau fournisseur </h3>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" action="../controller/FournisseurActionController.php" method="post">
                                    <div class="control-group">
                                        <label class="control-label">Nom</label>
                                        <div class="controls">
                                            <input required="required" type="text" name="nom" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Adresse</label>
                                        <div class="controls">
                                            <input type="text" name="adresse" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Tél.1</label>
                                        <div class="controls">
                                            <input type="text" name="telephone1" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Tél.2</label>
                                        <div class="controls">
                                            <input type="tel" name="telephone2" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Fax</label>
                                        <div class="controls">
                                            <input type="text" name="fax" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Email</label>
                                        <div class="controls">
                                            <input type="email" name="email" value="" />
                                        </div>  
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">  
                                            <input type="hidden" name="action" value="add" />
                                            <input type="hidden" name="source" value="fournisseurs" />
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- addFournisseur box end -->
                         <?php 
                         if ( isset($_SESSION['fournisseur-action-message'])
                         and isset($_SESSION['fournisseur-type-message']) ) {
                             $message = $_SESSION['fournisseur-action-message'];
                             $typeMessage = $_SESSION['fournisseur-type-message']; 
                         ?>
                         	<div class="alert alert-<?= $typeMessage ?>">
								<button class="close" data-dismiss="alert"></button>
								<?= $message ?>		
							</div>
                         <?php } 
                         	unset($_SESSION['fournisseur-action-message']);
                            unset($_SESSION['fournisseur-type-message']);
                         ?>
    						<div class="portlet box light-grey">
                                <div class="portlet-title">
                                    <h4>Liste des fournisseurs</h4>
                                    <div class="tools">
                                        <a href="javascript:;" class="reload"></a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="clearfix">
                                        <div class="btn-group">
                                            <a href="#addFournisseur" data-toggle="modal" class="btn blue">
                                            Nouveau Fournisseur <i class="icon-plus-sign"></i>
                                            </a>
                                        </div>
                                        <!--div class="btn-group pull-right">
                                            <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Print</a></li>
                                                <li><a href="#">Save as PDF</a></li>
                                                <li><a href="#">Export to Excel</a></li>
                                            </ul>
                                        </div-->
                                    </div>
								<table class="table table-striped table-bordered table-hover" id="sample_1">
									<thead>
										<tr>
										    <th class="hidden-phone" style="width:5%">Actions</th>
											<th style="width:20%">Nom</th>
											<th class="hidden-phone" style="width:30%">Adresse</th>
											<th style="width:10%">Tél1</th>
											<th style="width:10%">Tél2</th>
											<th class="hidden-phone" style="width:10%">Fax</th>
											<th class="hidden-phone" style="width:15%">Email</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										if($fournisseurNumber!=0){
										foreach ($fournisseurs as $fournisseur) {
										?>	
										<tr class="odd gradeX">
										    <td class="hidden-phone"><a class="btn green mini" href="#update<?= $fournisseur->id();?>" data-toggle="modal" data-id="<?= $fournisseur->id(); ?>"><i class="icon-refresh"></i></a></td>
                                            <td><?= $fournisseur->nom() ?></td>
											<td class="hidden-phone"><?= $fournisseur->adresse()?></td>
											<td><?= $fournisseur->telephone1() ?></td>
											<td><?= $fournisseur->telephone2() ?></td>
											<td class="hidden-phone"><?= $fournisseur->fax() ?></td>
											<td class="hidden-phone"><a target="_blank" href="mailto:<?= $fournisseur->email() ?>"><?= $fournisseur->email() ?></a></td>
										</tr>
										<!-- updateFournisseur box begin-->
										<div id="update<?= $fournisseur->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Modifier les informations du fournisseur </h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal" action="../controller/FournisseurActionController.php" method="post">
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
															<input type="email" name="email" value="<?= $fournisseur->email() ?>" />
														</div>	
													</div>
													<div class="control-group">
														<input type="hidden" name="idFournisseur" value="<?= $fournisseur->id() ?>" />
														<input type="hidden" name="action" value="update" />
                                                        <input type="hidden" name="source" value="fournisseurs" />
														<div class="controls">	
															<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
															<button type="submit" class="btn red" aria-hidden="true">Oui</button>
														</div>
													</div>
												</form>
											</div>
										</div>
										<!-- updateFournisseur box end -->
										<!-- delete box begin-->
										<div id="delete<?= $fournisseur->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Supprimer Fournisseur</h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal loginFrm" action="../controller/FournisseurActionController.php" method="post">
													<p>Êtes-vous sûr de vouloir supprimer ce fournisseur <?= $fournisseur->nom() ?> ?</p>
													<div class="control-group">
														<label class="right-label"></label>
														<input type="hidden" name="idFournisseur" value="<?= $fournisseur->id() ?>" />
														<input type="hidden" name="action" value="delete" />
                                                        <input type="hidden" name="source" value="fournisseurs" />
														<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
														<button type="submit" class="btn red" aria-hidden="true">Oui</button>
													</div>
												</form>
											</div>
										</div>
										<!-- delete box end -->				
										<?php }
										} ?>
									</tbody>
								</table>
								</div><!-- END DIV SCROLLER -->
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
			App.setPage("table_managed");
			App.init();
		});
		$('.providers').show();
        $('#provider').keyup(function(){
           $('.providers').hide();
           var txt = $('#provider').val();
           $('.providers').each(function(){
               if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                   $(this).show();
               }
            });
        });
	</script>
</body>
<!-- END BODY -->
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>