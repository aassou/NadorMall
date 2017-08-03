<?php
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav']) ){
    	//les sources
    	$usersManager = new UserManager(PDOFactory::getMysqlConnection());
		$users = $usersManager->getUsers(); 
        
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
	<div class="page-container row-fluid sidebar-closed">
		<?php include('../include/sidebar.php') ?>
		<div class="page-content">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
						<ul class="breadcrumb">
							<li><i class="icon-home"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
							<li><i class="icon-user"></i> <a>Gestion de mon compte</a></li>
						</ul>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<div class="tab-pane active" id="tab_1">
                           <div class="portlet box blue">
                              <div class="portlet-title">
                                 <h4><i class="icon-edit"></i>Informations du compte</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <?php if(isset($_SESSION['password-update-success'])){ ?>
                                 	<div class="alert alert-success">
    									<button class="close" data-dismiss="alert"></button>
    									<?= $_SESSION['password-update-success'] ?>		
    								</div>
                                 <?php } 
                                 	unset($_SESSION['password-update-success']);
                                 ?>
                                 <?php if(isset($_SESSION['password-update-error'])){ ?>
                                 	<div class="alert alert-error">
    									<button class="close" data-dismiss="alert"></button>
    									<?= $_SESSION['password-update-error'] ?>		
    								</div>
                                 <?php } 
                                 	unset($_SESSION['password-update-error']);
                                 ?>
                                 <form class="horizontal-form">
                                    <div class="row-fluid">
                                       <div class="span3 ">
                                          <div class="control-group">
                                             <label class="control-label" for="login">Login</label>
                                             <div class="controls">
                                                <input disabled="disabled" type="text" id="login" name="login" class="m-wrap span12" value="<?= $_SESSION['userMerlaTrav']->login() ?>">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3 ">
                                          <div class="control-group">
                                             <label class="control-label" for="profil">Profil</label>
                                             <div class="controls">
                                                <input disabled="disabled" type="text" id="profil" name="profil" class="m-wrap span12" value="<?= $_SESSION['userMerlaTrav']->profil() ?>">
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span3 ">
                                          <div class="control-group">
                                             <label class="control-label" for="password">Mot de passe</label>
                                             <div class="controls">
                                                <input disabled="disabled" type="password" id="password" name="password" class="m-wrap span12" value="hahahaha">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3 ">
                                          <div class="control-group">
                                             <label class="control-label" for="dateCreation">Date de création</label>
                                             <div class="controls">
                                                <input disabled="disabled" type="text" id="dateCreation" name="dateCreation" class="m-wrap span12" value="<?= date('Y-m-d', strtotime($_SESSION['userMerlaTrav']->created())) ?>">
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                       <a href="#updatePassword" data-toggle="modal" class="btn red">Modifier mon mot de passe</a>
                                    </div>
                                 </form>
                                 <!-- END FORM--> 
                                 <!-- update password box begin-->
									<div id="updatePassword" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h3>Modification du mot de passe</h3>
										</div>
										<div class="modal-body">
											<form class="form-horizontal loginFrm" action="../controller/UserUpdatePasswordController.php" method="post">
												<p>Êtes-vous sûr de vouloir modifier votre mot de passe ?</p>
												<div class="control-group">
													<label class="right-label">Ancien mot de passe</label>
													<input type="password" name="oldPassword" />
													<label class="right-label">Nouveau mot de passe</label>
													<input type="password" name="newPassword1" />
													<label class="right-label">Retapez nouveau mot de passe</label>
													<input type="password" name="newPassword2" />
												</div>
												<div class="control-group">
													<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
													<button type="submit" class="btn red" aria-hidden="true">Oui</button>
												</div>
											</form>
										</div>
									</div>
									<!-- update password box end -->
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
	<script>jQuery(document).ready(function() { App.init(); });</script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>