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

$userManager->changeStatus(0, $ilham);
$userManager->changeStatus(0, $laila);
$userManager->changeStatus(0, $tijani);
$userManager->changeStatus(0, $abdelghani);
$userManager->changeStatus(0, $hamid);
$userManager->changeStatus(0, $aassou);
