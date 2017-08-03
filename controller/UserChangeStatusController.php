<?php
include('../app/classLoad.php');  
//classes loading end
session_start();

$redirectLink='../users.php';
$idUser = $_GET['idUser'];
$userManager = new UserManager(PDOFactory::getMysqlConnection());
$status = $userManager->getStatusById($idUser);
if ( $status == 0 ) {
	$userManager->changeStatus(1, $idUser);
}
else {
	$userManager->changeStatus(0, $idUser);
}
$_SESSION['user-status-success'] = "<strong>Opération valide</strong> : Status Utilisateur est changé avec succès.";
header('Location:'.$redirectLink);
exit;
?>