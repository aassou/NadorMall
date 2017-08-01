<?php
    //classes loading begin
    function classLoad ($myClass) {
        if(file_exists('../model/'.$myClass.'.php')){
            include('../model/'.$myClass.'.php');
        }
        elseif(file_exists('../controller/'.$myClass.'.php')){
            include('../controller/'.$myClass.'.php');
        }
    }
    spl_autoload_register("classLoad"); 
    include('../db/dbconf.php');  
    //classes loading end
    session_start();
    
    //post input processing
	$idEmploye = $_POST['idEmploye'];
	$idProjet = $_POST['idProjet'];
    $employeManager = new EmployeProjetManager($pdo);
	$employeManager->delete($idEmploye);
	$_SESSION['employe-delete-success'] = "<strong>Opération valide : </strong>Employé supprimé avec succès.";
	header('Location:../employes-projet.php?idProjet='.$idProjet);
    
    