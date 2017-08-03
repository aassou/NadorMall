<?php
    include('../app/classLoad.php');  
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
        //classes managers
        $usersManager = new UserManager(PDOFactory::getMysqlConnection());
        $mailManager = new MailManager(PDOFactory::getMysqlConnection());
        //obj and vars
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
        include("include/top-menu.php"); 
        $myTasks = $taskManager->getTasksByUser($_SESSION['userMerlaTrav']->login());
        $tasksAffectedByMeToOther = 
        $taskManager->getTasksAffectedByMeToOther($_SESSION['userMerlaTrav']->login());
        $myTasksTotalNumber = $taskManager->getTaskNumberByUser($_SESSION['userMerlaTrav']->login())+$taskManager->getTaskDoneNumberByUser($_SESSION['userMerlaTrav']->login());
        $myTasksNotDoneNumber = $taskManager->getTaskNumberByUser($_SESSION['userMerlaTrav']->login());
        $myTasksDoneNumber = $taskManager->getTaskDoneNumberByUser($_SESSION['userMerlaTrav']->login()); 
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
                            <li><i class="icon-tasks"></i> <a><strong>Liste des tâches</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div id="container" style="width:100%; height:400px;"></div>
                        <div class="portlet box light-grey">
                            <div class="portlet-title">
                                <h4>Liste des tâches</h4>
                                <div class="tools">
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="sample_2">
                                    <thead>
                                        <tr>
                                            <th style="width :10%">Actions</th>
                                            <th style="width :10%">Affetcé pour</th>
                                            <th style="width :20%">Date affectation</th>
                                            <th style="width :50%">Tâche</th>
                                            <th style="width :10%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($tasksAffectedByMeToOther as $task){
                                            if ( $task->status() == 0 ) {
                                                $status = '<a class="btn mini red">En cours</a>';
                                                $statusName = "En cours";
                                            }
                                            else if ( $task->status() == 1 ) {
                                                $status = '<a class="btn mini green">Validée</a>';
                                                $statusName = "Validée";
                                            }
                                        ?>
                                        <tr class="odd gradeX">
                                            <td>
                                                <a href="#updateTask<?= $task->id() ?>" data-toggle="modal" data-id="<?= $task->id() ?>" class="btn mini green"><i class="icon-refresh"></i></a>
                                                <a href="#deleteTask<?= $task->id() ?>" data-toggle="modal" data-id="<?= $task->id() ?>" class="btn mini red"><i class="icon-remove"></i></a>
                                            </td>
                                            <td><?= $task->user() ?></td>
                                            <td><?= date('d/m/Y - H\hi\m', strtotime($task->created())) ?></td>
                                            <td><?= $task->content() ?></td>
                                            <td><?= $status ?></td>
                                            <!-- updateTask Box Begin -->
                                            <div id="updateTask<?= $task->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h3>Effacer la tâche</h3>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal" action="../controller/TaskActionController.php" method="post">
                                                        <div class="control-group">
                                                            <label class="control-label" for="user">Tâche pour</label>
                                                            <div class="controls">
                                                                <select name="user" id="user">
                                                                    <option value="<?= $task->user() ?>"><?= $task->user() ?></option>
                                                                    <option disabled="disabled">--------------</option>
                                                                    <?php 
                                                                    foreach ( $users as $user ) {
                                                                        if ( $user->login() != $_SESSION['userMerlaTrav']->login() ) { 
                                                                    ?>
                                                                            <option value="<?= $user->login() ?>"><?= $user->login() ?></option>
                                                                    <?php 
                                                                        }//end if 
                                                                    }//end foreach    
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label" for="user">Détails tâche</label>
                                                            <div class="controls">  
                                                                <textarea name="content" /><?= $task->content() ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label" for="user">Status</label>
                                                            <div class="controls">
                                                                <select name="status">
                                                                    <option value="<?= $task->status() ?>"><?= $statusName ?></option>
                                                                    <option disabled="disabled">--------------</option>
                                                                    <option value="0">En cours</option>
                                                                    <option value="1">Validée</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <div class="controls">  
                                                                <input type="hidden" name="action" value="update" />
                                                                <input type="hidden" name="idTask" value="<?= $task->id() ?>" />
                                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- updateTask Box End -->
                                            <!-- deleteTask Box Begin -->
                                            <div id="deleteTask<?= $task->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h3>Effacer la tâche</h3>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal" action="../controller/TaskActionController.php" method="post">
                                                        <p>Êtes-vous sûr de vouloir effacer cette tâche ?</p>
                                                        <div class="control-group">
                                                            <div class="controls">  
                                                                <input type="hidden" name="action" value="delete" />
                                                                <input type="hidden" name="idTask" value="<?= $task->id() ?>" />
                                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- deleteTask Box End -->
                                        </tr>     
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="portlet">
                            <div class="portlet-title line">
                                <h4><i class="icon-tasks"></i>Affecter des tâches</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>
                            <div class="portlet-body" id="chats">
                                <div class="chat-form">
                                    <form action="../controller/TaskActionController.php" method="POST">
                                        <div class="control-group">
                                                <label class="control-label" for="user">Tâche pour</label>
                                                <div class="controls">
                                                    <select name="user" id="user">
                                                        <?php 
                                                        foreach ( $users as $user ) {
                                                            if ( $user->login() != $_SESSION['userMerlaTrav']->login() ) { 
                                                        ?>
                                                                <option value="<?= $user->login() ?>"><?= $user->login() ?></option>
                                                        <?php 
                                                            }//end if 
                                                        }//end foreach    
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <div class="input-cont">   
                                            <input class="m-wrap" type="text" name="content" placeholder="Tapez votre tâche ..." />
                                        </div>
                                        <div class="btn-cont"> 
                                            <input type="hidden" name="action" value="add" />
                                            <span class="arrow"></span>
                                            <button type="submit" class="btn blue icn-only"><i class="icon-ok icon-white"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="scroller" data-height="500px" data-always-visible="1" id="messages" data-rail-visible1="1">
                                    <?php
                                     if( isset($_SESSION['task-action-message'])
                                     and isset($_SESSION['task-type-message']) ){ 
                                        $message = $_SESSION['task-action-message'];
                                        $typeMessage = $_SESSION['task-type-message'];    
                                     ?>
                                        <div class="alert alert-<?= $typeMessage ?>">
                                            <button class="close" data-dismiss="alert"></button>
                                            <?= $message ?>     
                                        </div>
                                     <?php } 
                                        unset($_SESSION['task-action-message']);
                                        unset($_SESSION['task-type-message']);
                                     ?>
                                    <br>
                                    <!--a href="#deleteValideTasks" data-toggle="modal" class="btn green get-down">
                                        Effacer les tâches validées
                                    </a-->
                                    <!-- DeleteValideTasks Box Begin -->
                                    <div id="deleteValideTasks" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h3>Effacer les tâches validées</h3>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal" action="" method="post">
                                                <div class="control-group">
                                                    <div class="controls">  
                                                        <input type="hidden" name="action" value="deleteValideTasks" />
                                                        <input type="hidden" name="user" value="<?= $_SESSION['userMerlaTrav']->login() ?>" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn green" aria-hidden="true">Oui</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- DeleteValideTasks Box End -->
                                    <ul class="chats">
                                        <?php 
                                        foreach($myTasks as $task){
                                        $status = "";    
                                        $classInOrOut = "out";    
                                        $avatar = "assets/img/red-user-icon.png";
                                        if($task->createdBy() == $_SESSION['userMerlaTrav']->login()){
                                            $classInOrOut = "in";
                                            $avatar = "assets/img/green-user-icon.png";
                                        }   
                                        if ( $task->status() == 0 ) {
                                            $classInOrOut = "out";
                                            $status = '<a data-toggle="modal" data-id="'.$task->id().'" class="btn mini red" href="#validateTask'.$task->id().'">En cours</a>';
                                        }
                                        else if ( $task->status() == 1 ) {
                                            $classInOrOut = "in";
                                            $status = '<a data-toggle="modal" data-id="'.$task->id().'" class="btn mini green" href="#invalidateTask'.$task->id().'">Validée</a>';
                                        }
                                        ?>
                                        <li class="<?= $classInOrOut ?>">
                                            <!--img class="avatar" alt="" src="<?= $avatar ?>" /-->
                                            <div class="message">
                                                <span class="arrow"></span>
                                                <strong>Tâche affectée par =&gt; </strong>
                                                <a href="#" class="name"><strong><?= strtoupper($task->createdBy()) ?></strong></a>
                                                <span class="datetime">
                                                    <?php 
                                                    if(date('Y-m-d', strtotime($task->created()))==date("Y-m-d")){echo "Ajourd'hui";}
                                                    else{echo date('d-m-Y',strtotime($task->created()));}  
                                                    echo " à ".date('H:i', strtotime($task->created())); ?>
                                                </span><?= $status ?>
                                                <span class="body get-down medium-font">
                                                <?= $task->content() ?>
                                                </span>
                                            </div>
                                        </li>
                                        <!-- Validate Task Box Begin -->
                                        <div id="validateTask<?= $task->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Valider la tâche</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="../controller/TaskActionController.php" method="post">
                                                    <div class="control-group">
                                                        <div class="controls">  
                                                            <input type="hidden" name="action" value="updateStatus" />
                                                            <input type="hidden" name="status" value="1" />
                                                            <input type="hidden" name="idTask" value="<?= $task->id() ?>" />
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn green" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Validate Task Box End -->
                                        <!-- Invalidate Task Box Begin -->
                                        <div id="invalidateTask<?= $task->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Invalider la tâche</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="../controller/TaskActionController.php" method="post">
                                                    <div class="control-group">
                                                        <div class="controls">  
                                                            <input type="hidden" name="action" value="updateStatus" />
                                                            <input type="hidden" name="status" value="0" />
                                                            <input type="hidden" name="idTask" value="<?= $task->id() ?>" />
                                                            <button class="btn" data-dismiss="modal" aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Invalidate Task Box End -->   
                                        <?php 
                                        }  
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../include/head.php') ?>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>
    <script>jQuery(document).ready(function() { App.setPage("table_managed"); App.init(); });</script>
    <script> 
        Highcharts.chart('container', {
            chart: {
                type: 'column',
                options3d: {
                    enabled: true,
                    alpha: 10,
                    beta: 25,
                    depth: 70
                }
            },
            title: {
                text: 'Statistiques des tâches personnelles - <?= ucfirst($_SESSION['userMerlaTrav']->login()) ?>'
            },
            plotOptions: {
                column: {
                    depth: 25
                }
            },
            xAxis: {
                categories: ['Total Tâches', 'Tâches réalisées', 'Tâches non réalisées']
            },
            series: [{
                name: 'Tâches personnelles',
                colorByPoint:true,
                colors: ['#003366', '#35aa47', '#e02222'],
                data: [<?= $myTasksTotalNumber ?>, <?= $myTasksDoneNumber ?>, <?= $myTasksNotDoneNumber ?>]
            }]
        });
    </script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>