<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
	
	if(!empty($_POST['oldPassword']) 
	and $_POST['oldPassword']==$_SESSION['userMerlaTrav']->password()){
		if($_POST['newPassword1']==$_POST['newPassword2']){
			$newPassword = htmlentities($_POST['newPassword1']);
			$idUser = $_SESSION['userMerlaTrav']->id();
			$userManager = new UserManager(PDOFactory::getMysqlConnection());
			$userManager->changePassword($newPassword, $idUser);
			$_SESSION['password-update-success']="<strong>Opération valide</strong> : Le mot de passe a été changé avec succès.";
		}
		else{
			$_SESSION['password-update-error']="<strong>Erreur Mot de passe</strong> : Les 2 nouveaux mots de passe ne sont pas identiques.";
		}
	}
	else{
		$_SESSION['password-update-error']="<strong>Erreur Mot de passe</strong> : Vous devez saisir votre ancien mot de passe pour créer un nouveau.";
	}
	header('Location:../user-profil.php');
