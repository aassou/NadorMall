<?php
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
    	$userManager = new UserManager(PDOFactory::getMysqlConnection());
		$users = $userManager->getUsers();
        
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
							<li><i class="icon-search"></i> <a>Rechercher</a></li>
						</ul>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<div class="tab-pane active" id="tab_1">
                           <div class="portlet box blue">
                              <div class="portlet-title">
                                 <h4><i class="icon-search"></i>Chercher un client</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <form action="../controller/SearchClientController.php" method="POST" class="horizontal-form">
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">Recherche par</label>
				                              <div class="controls">
				                                 <label class="radio">
				                                 <input type="radio" name="searchOption" value="searchByName" checked="checked"  />
				                                 Nom
				                                 </label>
				                                 <label class="radio">
				                                 <input type="radio" name="searchOption" value="searchByCIN" />
				                                 CIN
				                                 </label>  
				                              </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group autocomplet_container">
                                             <label class="control-label" for="nomClient">Tapez votre recherche</label>
                                             <div class="controls">
                                                <input type="text" id="nomClient" name="search" class="m-wrap span12" onkeyup="autocompletClient()">
                                                <ul id="clientList"></ul>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                       <button type="submit" class="btn green"><i class="icon-search"></i> Lancer</button>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
					</div>
					<div class="span6">
						<div class="tab-pane active" id="tab_1">
                           <div class="portlet box green">
                              <div class="portlet-title">
                                 <h4><i class="icon-search"></i>Chercher un fournisseur</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <form action="livraisons.php" method="POST" class="horizontal-form">
                                    <div class="row-fluid">
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group autocomplet_container">
                                             <label class="control-label" for="nomFournisseur">Nom du fournisseur</label>
                                             <div class="controls">
                                                <input type="text" id="nomFournisseur" name="recherche" class="m-wrap span12" onkeyup="autocompletFournisseur()">
                                                <ul id="fournisseurList"></ul>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                    	<input name="idFournisseur" id="idFournisseur" type="hidden" />
                                       <button type="submit" class="btn blue"><i class="icon-search"></i> Lancer</button>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6">
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
					<div class="span6">
						<div class="tab-pane active" id="tab_1">
                           <div class="portlet box grey">
                              <div class="portlet-title">
                                 <h4><i class="icon-search"></i>Chercher un employé</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <form action="../controller/SearchEmployeController.php" method="POST" class="horizontal-form">
                                    <div class="row-fluid">
                                       <div class="span6 ">
                                          <div class="control-group">
                                             <label class="control-label">Recherche par</label>
				                              <div class="controls">
				                                 <label class="radio">
				                                 <input type="radio" name="searchOption" value="searchByName" checked />
				                                 Nom
				                                 </label>
				                                 <label class="radio">
				                                 <input type="radio" name="searchOption" value="searchByCIN" />
				                                 CIN
				                                 </label>  
				                              </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span6 ">
                                          <div class="control-group autocomplet_container">
                                             <label class="control-label" for="nomEmployeProjet">Tapez votre recherche</label>
                                             <div class="controls">
                                                <input type="text" id="nomEmployeProjet" name="search" class="m-wrap span12" onkeyup="autocompletEmployeProjet()">
                                                <ul id="employeProjetList"></ul>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                       <button type="submit" class="btn purple"><i class="icon-search"></i> Lancer</button>
                                    </div>
                                 </form>
                              </div>
                           </div>
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
else{
    header('Location:index.php');    
}
?>