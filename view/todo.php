<?php 
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
        $showTodos = 0;
        if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
            $showTodos = 1;    
        }
        //classes managers
        $usersManager = new UserManager(PDOFactory::getMysqlConnection());
        $mailManager = new MailManager(PDOFactory::getMysqlConnection());
        //objs and vars
        $users = $usersManager->getUsers();
        
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <?php include('../include/head.php') ?>
</head>
<body class="fixed-top">
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <?php 
        include('../include/top-menu.php'); 
        $todos = $todoManager->getTodosByUser($_SESSION['userMerlaTrav']->login());
        ?>   
    </div>    
    <div class="page-container row-fluid sidebar-closed">
        <?php include('../include/sidebar.php') ?>
        <div class="page-content">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="breadcrumb">
                            <li><i class="icon-dashboard"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-check"></i> <a>Liste des tâches personnelles</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <h4><i class="icon-check"></i>Ajouter une tâche personnelle </h4>
                            </div>
                            <div class="portlet-body form">
                                    <form action="../controller/TodoActionController.php" method="POST" class="horizontal-form">
                                        <div class="row-fluid">
                                            <div class="span3">
                                                <div class="control-group">
                                                    <label class="control-label" for="numero">Tâche</label>
                                                    <div class="controls">
                                                        <input required="required" type="text" id="todo" name="todo" class="m-wrap">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span3">
                                                <div class="control-group">
                                                    <label class="control-label" for="numero">Date</label>
                                                    <div class="controls">
                                                        <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                            <input name="date" id="date" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                         </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span3">
                                                <label class="control-label" for="numero">&nbsp;</label>
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" id="idProjet" name="idProjet" value="">
                                                <button type="submit" class="btn blue">Terminer <i class="icon-ok m-icon-white"></i></button>
                                            </div>
                                        </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <!-- BEGIN INLINE NOTIFICATIONS PORTLET-->
                        <button id="showAllTodosButton" class="get-down">Afficher tous</button>
                        <br>
                        <?php
                        foreach( $todos as $todo ) {
                            $color = "";
                            $priorityOption = "";
                            $style = "";
                            //test tasks date to show them in different colors
                            if ( $todo->date() >= date('Y-m-d') ) {
                                $color = "blue";
                            }
                            elseif ( $todo->date() < date('Y-m-d') )  {
                                $color = "red";
                            }
                            //
                            if ( $todo->date() == date('Y-m-d') ) {
                                $style = '';
                            }
                            else {
                                $style = 'style="display : none;"';
                            }
                        ?>
                        <div class="showAllTodos" <?= $style ?> >
                            <a href="include/delete-task.php?idTask=<?= $todo->id() ?>"><i class="icon-remove"></i></a>
                            <a href="#updateTodo<?= $todo->id() ?>" data-toggle="modal" data-id="<?= $todo->id() ?>" class="btn <?= $color ?> get-down delete-checkbox">
                            <?= $todo->todo() ?></a><br />
                            <!-- updateTodo box begin-->
                            <div id="updateTodo<?= $todo->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h3>Modifier Todo </h3>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" action="../controller/TodoActionController.php" method="post">
                                        <div class="control-group">
                                            <label class="control-label">Todo</label>
                                            <div class="controls">
                                                <input type="text" name="todo" value="<?= $todo->todo() ?>" />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Date</label>
                                            <div class="controls">
                                                <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                    <input name="date" id="date" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= $todo->date() ?>" />
                                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <input type="hidden" name="idTodo" value="<?= $todo->id() ?>" />
                                            <input type="hidden" name="action" value="update-date" />
                                            <div class="controls">  
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <!-- updateTodo box end -->
                        </div>    
                        <?php 
                        //}//end if
                        }//end foreach
                        ?>
                    </div>
                </div>
            </div>  
        </div>       
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>        
    <script>jQuery(document).ready(function() { App.setPage("table_managed"); App.init(); });
        $(document).ready(function() { $('#showAllTodosButton').on('click', function() { $(".showAllTodos").toggle("show"); }); });
    </script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>