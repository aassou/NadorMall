<?php
include('../app/classLoad.php');  
//classes loading end
session_start();

$ilham = 3;
$laila = 7;
$tijani = 8;
$abdelghani = 9;
$hamid = 10;
$aassou = 11;
//process
$userManager = new UserManager(PDOFactory::getMysqlConnection());

$userManager->changeStatus(1, $ilham);
$userManager->changeStatus(1, $laila);
$userManager->changeStatus(1, $tijani);
$userManager->changeStatus(1, $abdelghani);
$userManager->changeStatus(1, $hamid);
$userManager->changeStatus(1, $aassou);
