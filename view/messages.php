<?php 
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
    	//classes managers
		$usersManager = new UserManager(PDOFactory::getMysqlConnection());
		$mailManager = new MailManager(PDOFactory::getMysqlConnection());
		//obj and vars
		$usersNumber = $usersManager->getUsersNumber();
		$mails = $mailManager->getMails();
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
							Messages
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-dashboard"></i>
								<a>Accueil</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a>Messages</a>
							</li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<!-- BEGIN PORTLET-->
				<div class="row-fluid">
					<div class="span12">
						<div class="portlet">
							<div class="portlet-title line">
								<h4><i class="icon-comments"></i>Messages</h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body" id="chats">
								<div class="chat-form">
									<form action="../controller/MailAddController.php" method="POST">
										<div class="input-cont">   
											<input class="m-wrap" type="text" name="mail" placeholder="Taper les messages ici ..." />
										</div>
										<div class="btn-cont"> 
											<span class="arrow"></span>
											<button type="submit" class="btn blue icn-only"><i class="icon-ok icon-white"></i></button>
										</div>
									</form>
								</div>
								<div class="scroller" data-height="500px" data-always-visible="1" id="messages" data-rail-visible1="1">
									<br>
									<ul class="chats">
										<?php 
										foreach($mails as $mail){
										$classInOrOut = "out";	  
										$avatar = "assets/img/red-user-icon.png";
										if($mail->sender()==$_SESSION['userMerlaTrav']->login()){
											$classInOrOut = "in";
											$avatar = "assets/img/green-user-icon.png";
										}	
										?>
										<li class="<?= $classInOrOut ?>">
											<img class="avatar" alt="" src="<?= $avatar ?>" />
											<div class="message">
												<span class="arrow"></span>
												<a href="#" class="name"><strong><?= strtoupper($mail->sender()) ?></strong></a>
												<span class="datetime">
													<?php 
													if(date('Y-m-d', strtotime($mail->created()))==date("Y-m-d")){echo "Ajourd'hui";}
													else{echo date('d-m-Y',strtotime($mail->created()));}  
													echo " à ".date('H:i', strtotime($mail->created())); ?>
												</span>
												<span class="body">
												<?= $mail->content() ?>
												</span>
											</div>
										</li>
										<?php }  ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- END PORTLET-->
				<!-- END PAGE CONTENT-->
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
			App.setPage("sliders");  // set current page
			App.init();
		});
	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>