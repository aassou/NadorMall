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
<head>
  <?php include('../include/head.php') ?>
</head>
<body class="login">
  <div class="logo">
    <img src="../assets/img/big-logo.png" alt="" /> 
  </div>
  <div class="content">
    <form class="form-vertical login-form" action="../controller/UserSignUpController.php" method="POST">
      <h3 class="form-title">Créer nouveau compte</h3>
      <?php
      	if(isset($_SESSION['signup-error'])){
      ?>			
		  <div class="alert alert-error">
	        <button class="close" data-dismiss="alert"></button>
	        <span><?php echo $_SESSION['signup-error']; ?></span>
	      </div>
      <?php
		}
		unset($_SESSION['signup-error']);
	  ?>
	  <?php
      	if(isset($_SESSION['signup-success'])){
      ?>			
		  <div class="alert alert-success">
	        <button class="close" data-dismiss="alert"></button>
	        <span><?php echo $_SESSION['signup-success']; ?></span>
	      </div>
      <?php
		}
		unset($_SESSION['signup-success']);
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
      <div class="control-group">
        <label class="control-label visible-ie8 visible-ie9">Retaper Mot de passe</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-ok"></i>
            <input class="m-wrap placeholder-no-fix" type="password" placeholder="Retaper mot de passe" name="rpassword"/>
          </div>
        </div>
      </div>
      <div class="form-actions">
        <a href="index.php" class="btn">
        <i class="m-icon-swapleft"></i> Retour
        </a>
        <input type="submit" class="btn green pull-right" value="S'inscrire">            
      </div>
    </form>
  </div>
  <?php include('../include/footer.php') ?>
  <?php include('../include/scripts.php') ?>
</body>
</html>
<?php
}
?>