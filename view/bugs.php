<?php
    require('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
        $usersManager = new UserManager(PDOFactory::getMysqlConnection());
        $mailManager  = new MailManager(PDOFactory::getMysqlConnection());
        //classes and vars
        $users = $usersManager->getUsers();
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <?php include('../include/head.php'); ?>
</head>
<body class="fixed-top">
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <?php include('../include/top-menu.php'); $bugs = $bugManager->getbugs(); ?> 
    </div>    
    <div class="page-container row-fluid sidebar-closed">
        <?php include('../include/sidebar.php'); ?>
        <div class="page-content">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="breadcrumb">
                            <li><i class="icon-dashboard"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-warning-sign"></i> <a><strong>Liste des anomalies</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?php if ( isset($_SESSION['bug-action-message']) and isset($_SESSION['bug-type-message']) ) { $message = $_SESSION['bug-action-message']; $typeMessage = $_SESSION['bug-type-message']; ?>
                        <div class="alert alert-<?= $typeMessage ?>"><button class="close" data-dismiss="alert"></button><?= $message ?></div>
                        <?php } unset($_SESSION['bug-action-message']); unset($_SESSION['bug-type-message']); ?>
                        <div class="portlet">
                            <div class="portlet-title line">
                                <h4><i class="icon-bugs"></i>Ajouter une anomalie </h4>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                            </div>
                            <div class="portlet-body" id="chats">
                                <div class="chat-form">
                                    <form action="../controller/BugActionController.php" method="POST">
                                        <div class="input-cont">   
                                            <input class="m-wrap" type="text" name="bug" placeholder="Nom de l'anomalie" />
                                        </div>
                                        <div class="input-cont">   
                                            <input class="m-wrap" type="text" name="lien" placeholder="Lien de l'anomalie" />
                                        </div>
                                        <div class="btn-cont"> 
                                            <input type="hidden" name="action" value="add" />
                                            <span class="arrow"></span>
                                            <button type="submit" class="btn blue icn-only"><i class="icon-ok icon-white"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="portlet box light-grey">
                            <div class="portlet-title">
                                <h4>Liste des anomalies</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th style="width :30%">Anomalie</th>
                                            <th class="hidden-phone" style="width :35%">Lien</th>
                                            <th class="hidden-phone" style="width :15%">Date</th>
                                            <th class="hidden-phone" style="width :10%">Par</th>
                                            <th style="width :10%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($bugs as $bug){
                                            if ( $bug->status() == 0 ) {
                                            $status = '<a class="btn mini red">En cours</a>';
                                            }
                                            else if ( $bug->status() == 1 ) {
                                                $status = '<a class="btn mini green">Validée</a>';
                                            }
                                        ?>
                                        <tr class="odd gradeX">
                                            <td><?= $bug->bug() ?></td>
                                            <td><?= $bug->lien() ?></td>
                                            <td><?= date('d/m/Y - H\hi\m', strtotime($bug->created())) ?></td>
                                            <td><?= $bug->createdBy() ?></td>
                                            <td><?= $status ?></td>
                                        </tr>     
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>       
    </div>
    <?php include('../include/footer.php'); ?>
    <?php include('../include/scripts.php'); ?>        
    <script>jQuery(document).ready(function() { App.setPage("table_managed"); App.init(); });</script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>