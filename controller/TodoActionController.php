<?php
    include('../app/classLoad.php');  
    include('../lib/image-processing.php');
    //classes loading end
    session_start();
    
    //post input processing
    $action = htmlentities($_POST['action']);
    //This var contains result message of CRUD action
    $actionMessage = "";
    $typeMessage = "";

    //Component Class Manager

    $todoManager = new TodoManager(PDOFactory::getMysqlConnection());
	//Action Add Processing Begin
    if($action == "add"){
        if( !empty($_POST['todo']) ){
			$todo = htmlentities($_POST['todo']);
            $priority = htmlentities($_POST['priority']);
			$status = 0;
            $date = htmlentities($_POST['date']);
			$createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $todo = new Todo(array(
				'todo' => $todo,
				'priority' => $priority,
				'status' => $status,
				'date' => $date,
				'created' => $created,
            	'createdBy' => $createdBy
			));
            //add it to db
            $todoManager->add($todo);
            $actionMessage = "Opération Valide : Todo Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout todo : Vous devez remplir le champ 'todo'.";
            $typeMessage = "error";
        }
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        $idTodo = htmlentities($_POST['idTodo']);
        if(!empty($_POST['todo'])){
			$todo = htmlentities($_POST['todo']);
            $priority = htmlentities($_POST['priority']);
			$status = htmlentities($_POST['status']);
			$updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            $todo = new Todo(array(
				'id' => $idTodo,
				'todo' => $todo,
				'priority' => $priority,
				'status' => $status,
				'updated' => $updated,
            	'updatedBy' => $updatedBy
			));
            $todoManager->update($todo);
            $actionMessage = "Opération Valide : Todo Modifié(e) avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification Todo : Vous devez remplir le champ 'todo'.";
            $typeMessage = "error";
        }
    }
    //Action Update Processing End
    //Action UpdatePriority Processing Begin
    else if($action == "update-priority"){
        $idTodo = htmlentities($_POST['idTodo']);
        $priority = htmlentities($_POST['priority']);
        $todoManager->updatePriority($idTodo);
        $actionMessage = "Opération Valide : Todo Modifié avec succès.";
        $typeMessage = "success";
    }
    //Action UpdatePriority Processing End
    //Action UpdateDate Processing Begin
    else if($action == "update-date"){
        $idTodo = htmlentities($_POST['idTodo']);
        $date = htmlentities($_POST['date']);
        $updatedBy = $_SESSION['userMerlaTrav']->login();
        $updated = date('Y-m-d h:i:s');
        $todoManager->updateDate($idTodo, $date, $updated, $updatedBy);
        $actionMessage = "Opération Valide : Todo Modifié avec succès.";
        $typeMessage = "success";
    }
    //Action UpdateDate Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idTodo = htmlentities($_POST['idTodo']);
        $todoManager->delete($idTodo);
        $actionMessage = "Opération Valide : Todo supprimé(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    $_SESSION['todo-action-message'] = $actionMessage;
    $_SESSION['todo-type-message'] = $typeMessage;
    header('Location:../todo.php');

