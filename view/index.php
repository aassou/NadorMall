<?php
    session_start();
	if(isset($_SESSION['userMerlaTrav'])){
		header('Location:dashboard.php');
	}
	else{
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
<body class="login">
  <!-- BEGIN LOGO -->
  <div class="logo">
    <img src="../assets/img/big-logo-new.png" alt="" /> 
  </div>
  <!-- END LOGO -->
  <!-- BEGIN LOGIN -->
  <div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="form-vertical login-form" action="../controller/UserSignInController.php" method="POST">
      <h3 class="form-title">Accéder à votre compte</h3>
      <div class="alert alert-error hide">
        <button class="close" data-dismiss="alert"></button>
        <span><strong>Login</strong> et <strong>Mot de passe</strong> non saisies.</span>
      </div>
      <?php
      	if(isset($_SESSION['signin-error'])){
      ?>			
		  <div class="alert alert-error">
	        <button class="close" data-dismiss="alert"></button>
	        <span><?php echo $_SESSION['signin-error']; ?></span>
	      </div>
      <?php
		}
		unset($_SESSION['signin-error']);
	  ?>	
      <div class="control-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Login</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-user"></i>
            <input class="m-wrap placeholder-no-fix" type="text" placeholder="Login" name="login"/>
          </div>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label visible-ie8 visible-ie9">Mot de passe</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-lock"></i>
            <input class="m-wrap placeholder-no-fix" type="password" placeholder="Mot de passe" name="password"/>
          </div>
        </div>
      </div>
      <div class="form-actions">
        <input type="submit" class="btn green pull-right" value="Se connecter">            
      </div>
    </form>
    <!-- END LOGIN FORM -->
  </div>
  <!-- END LOGIN -->
  <!-- BEGIN COPYRIGHT -->
  <div class="copyright">
    <?= date('Y') ?> &copy; NadorMall. Management Application.
  </div>
  <?php include('../include/scripts.php') ?>
</body>
<!-- END BODY -->
</html>
<?php
}
?>