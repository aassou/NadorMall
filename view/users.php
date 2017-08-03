<?php
    include('../app/classLoad.php');    
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
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
							<li><i class="icon-wrench"></i> <a href="configuration.php">Paramètrages</a><i class="icon-angle-right"></i></li>
							<li><a><strong>Gestion des utilisateurs</strong></a></li>
						</ul>
					</div>
				</div>
				<!-- BEGIN PAGE CONTENT-->
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
                         <?php if(isset($_SESSION['user-update-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['user-update-success'] ?>		
							</div>
                         <?php } 
                         	unset($_SESSION['user-update-success']);
                         ?>
						<div class="tab-pane active" id="tab_1">
                           <div class="portlet box blue">
                              <div class="portlet-title">
                                 <h4><i class="icon-edit"></i>Ajouter un utilisateur</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <?php if(isset($_SESSION['user-add-success'])){ ?>
                                 	<div class="alert alert-success">
    									<button class="close" data-dismiss="alert"></button>
    									<?= $_SESSION['user-add-success'] ?>		
    								</div>
                                 <?php } 
                                 	unset($_SESSION['user-add-success']);
                                 ?>
                                 <?php if(isset($_SESSION['user-add-error'])){ ?>
                                 	<div class="alert alert-error">
    									<button class="close" data-dismiss="alert"></button>
    									<?= $_SESSION['user-add-error'] ?>		
    								</div>
                                 <?php } 
                                 	unset($_SESSION['user-add-error']);
                                 ?>
                                 <form action="../controller/UserAddController.php" method="POST" class="horizontal-form">
                                    <div class="row-fluid">
                                       <div class="span2 ">
                                          <div class="control-group">
                                             <label class="control-label" for="login">Login</label>
                                             <div class="controls">
                                                <input type="text" id="login" name="login" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <!--/span-->
                                       <div class="span3 ">
                                          <div class="control-group">
                                             <label class="control-label" for="password">Mot de passe</label>
                                             <div class="controls">
                                                <input type="password" id="password" name="password" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3 ">
                                          <div class="control-group">
                                             <label class="control-label" for="password2">Retapez mot de passe</label>
                                             <div class="controls">
                                                <input type="password" id="password2" name="rpassword" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3 ">
                                          <div class="control-group">
                                             <label class="control-label" for="profil">Profil</label>
                                             <div class="controls">
                                             	<select name="profil" class="m-wrap">
                                             		<option value="user">Utilisateur</option>
                                             		<option value="consultant">Consultant</option>
                                             		<option value="manager">Manager</option>
                                             		<option value="admin">Administrateur</option>
                                             	</select>   
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                       <button type="submit" class="btn green"><i class="icon-ok"></i> Ajouter</button>
                                       <button type="reset" class="btn">Annuler</button>
                                    </div>
                                 </form> 
                              </div>
                           </div>
                        </div>
						<div class="portlet box blue">
							<div class="portlet-title">
								<h4><i class="icon-reorder"></i>Les utilisateurs</h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-striped table-hover table-bordered" id="sample_editable_1">
									<thead>
										<tr>
											<th>Login</th>
											<th>Profile</th>
											<th class="hidden-phone">Date de création</th>
											<th>Status</th>
											<th class="hidden-phone">Changer status</th>
											<th class="hidden-phone">Modifier Profil</th>
											<th class="hidden-phone">Supprimer</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($users as $user) {
												$status = '<span class="label label-important">Inactif</span>';
												$classStatus = 'btn mini green';
												$message = "Activer";
												if($user->status()==1){
													$status = '<span class="label label-success">Actif</span>';
													$classStatus = 'btn mini red';
													$message = "Désactiver";	
												}	
										?>	
										<tr class="">
											<td><?= $user->login()?></td>
											<td><?= $user->profil()?></td>
											<td class="hidden-phone"><?= date('d-m-Y', strtotime($user->created())) ?></td>
											<td><?= $status ?></td>
											<td class="hidden-phone">
												<a href="../controller/UserChangeStatusController.php?idUser=<?php echo $user->id();?>" class="<?= $classStatus ?>" data-toggle="modal" data-id="<?php echo $user->id(); ?>">
													<?= $message ?>	
												</a>
											</td>
											<td class="hidden-phone">
												<a href="#update<?php echo $user->id();?>" data-toggle="modal" data-id="<?php echo $user->id(); ?>">
													Modifier
												</a>
											</td>
											<td class="hidden-phone">
												<a href="#delete<?php echo $user->id();?>" data-toggle="modal" data-id="<?php echo $user->id(); ?>">
													Supprimer
												</a>
											</td>
										</tr>
										<!-- edit box begin-->
										<div id="update<?php echo $user->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Modifier le Profil Utilisateur</h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal loginFrm" action="../controller/UserUpdateController.php" method="post">
													<p>Êtes-vous sûr de vouloir modifier le profil de <strong><?= $user->login() ?></strong> ?</p>
													<div class="control-group">
														<label class="right-label">Profil</label>
														<div class="controls">
			                                             	<select name="profil" class="m-wrap">
			                                             		<option value="<?= $user->profil() ?>"><?= ucfirst($user->profil()) ?></option>
			                                             		<option disabled="disabled">-----------------</option>
			                                             		<option value="user">Utilisateur</option>
			                                             		<option value="consultant">Consultant</option>
			                                             		<option value="manager">Manager</option>
			                                             		<option value="admin">Administrateur</option>
			                                             	</select>   
			                                             </div>
														<input type="hidden" name="idUser" value="<?= $user->id() ?>" />
														<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
														<button type="submit" class="btn red" aria-hidden="true">Oui</button>
													</div>
												</form>
											</div>
										</div>
										<!-- edit box end -->
										<!-- delete box begin-->
										<div id="delete<?php echo $user->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Supprimer l'utilisateur</h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal loginFrm" action="../controller/UserDeleteController.php" method="post">
													<p>Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
													<div class="control-group">
														<label class="right-label"></label>
														<input type="hidden" name="idUser" value="<?= $user->id() ?>" />
														<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
														<button type="submit" class="btn red" aria-hidden="true">Oui</button>
													</div>
												</form>
											</div>
										</div>
										<!-- delete box end -->				
										<?php } ?>
									</tbody>
								</table>
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
else if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()!="admin"){
	header('Location:dashboard.php');
}
else{
    header('Location:index.php');    
}
?>