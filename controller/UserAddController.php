<?php
include('../app/classLoad.php');  
//classes loading end
session_start();

$redirectLink='../users.php';

if(empty($_POST['login']) || empty($_POST['password']) || empty($_POST['rpassword'])){
    $_SESSION['user-add-error'] = "<strong>Erreur Ajout Utilisateur</strong> : Tous les champs sont obligatoires";
}
else{
    $userManager = new UserManager(PDOFactory::getMysqlConnection());
    if($userManager->exists2($_POST['login'])){
        $_SESSION['user-add-error'] = "<strong>Erreur Ajout Utilisateur</strong> : Ce login existe déjà.";
    }
	else{
		$login = htmlspecialchars($_POST['login']);
    	$password = htmlspecialchars($_POST['password']);
		$rpassword = htmlentities($_POST['rpassword']);
		$profil = htmlentities($_POST['profil']);
		if($password==$rpassword){
			$user = new User(array('login'=>$login, 'password'=>$password, 'created'=>date('Y-m-d'), 'profil'=>$profil, 'status'=>1));
			$userManager->add($user);
			$_SESSION['user-add-success'] = "<strong>Opération Validée</strong> : Utilisateur ajouté avec succès.";	
		}
		else{
			$_SESSION['user-add-error'] = "<strong>Erreur Ajout Utilisateur</strong> : Les mots de passe doivent être identiques.";
		}
	}
}
header('Location:'.$redirectLink);
exit;
?>