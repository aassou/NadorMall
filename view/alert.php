<?php 
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
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
<!-- BEGIN HEAD -->
<head>
    <?php include('../include/head.php') ?>
</head>
<body class="fixed-top">
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <?php include('../include/top-menu.php'); $alerts = $alertManager->getAlerts($_SESSION['userMerlaTrav']->login()); ?>   
    </div>    
    <div class="page-container row-fluid sidebar-closed">
        <?php include('../include/sidebar.php'); ?>
        <div class="page-content">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="breadcrumb">
                            <li><i class="icon-dashboard"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-bullhorn"></i> <a><strong>Liste des alertes</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?php if( isset($_SESSION['alert-action-message']) and isset($_SESSION['alert-type-message']) ){ $message = $_SESSION['alert-action-message']; $typeMessage = $_SESSION['alert-type-message']; ?>
                        <div class="alert alert-<?= $typeMessage ?>"><button class="close" data-dismiss="alert"></button><?= $message ?></div>
                        <?php } unset($_SESSION['alert-action-message']); unset($_SESSION['alert-type-message']); ?>
                        <div class="portlet">
                            <div class="portlet-title line">
                                <h4><i class="icon-bullhorn"></i>Ajouter une alerte </h4>
                            </div>
                            <div class="portlet-body" id="chats">
                                <div class="chat-form">
                                    <form action="../controller/AlertActionController.php" method="POST">
                                        <div class="input-cont">   
                                            <input class="m-wrap" type="text" name="alert" placeholder="Description de l'alerte" />
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
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <!-- BEGIN INLINE NOTIFICATIONS PORTLET-->
                        <?php
                        $statusClass = '';
                        $statusUpdate = 0;
                        $action = "";
                        $actionClass = "";
                        foreach($alerts as $alert){
                            if ( $alert->status() == 0 ) {
                                $statusClass = 'error';
                                $statusUpdate = 1;
                                $action = "Valider";
                                $actionClass = "green";
                            }
                            else if ( $alert->status() == 1 ) {
                                $statusClass = 'success';
                                $statusUpdate = 0;
                                $action = "Annuler";
                                $actionClass = "red";
                            }
                        ?>
                        <div class="span3 alert alert-block alert-<?= $statusClass ?> fade in">
                            <a href="#deleteAlert<?= $alert->id() ?>" class="close" data-toggle="modal" data-id="<?= $alert->id() ?>"></a>
                            <h4 class="alert-heading">Alerte</h4>
                            <p>
                                <?= $alert->alert() ?>
                            </p>
                            <p>
                                <!--a class="btn red" href="#">Do this</a--> 
                                <a class="btn <?= $actionClass ?>" href="#updateStatusAlert<?= $alert->id() ?>" data-toggle="modal" data-id="<?= $alert->id() ?>" ><?= $action ?></a>
                            </p>
                        </div>
                        <!-- updateStatusAlert box begin-->
                        <div id="updateStatusAlert<?= $alert->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Modifier Status Alerte</h3>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal loginFrm" action="../controller/AlertActionController.php" method="post">
                                    <p>Êtes-vous sûr de vouloir modifier le status de cette alerte ?</p>
                                    <div class="control-group">
                                        <label class="right-label"></label>
                                        <input type="hidden" name="action" value="updateStatus" />
                                        <input type="hidden" name="idAlert" value="<?= $alert->id() ?>" />
                                        <input type="hidden" name="status" value="<?= $statusUpdate ?>" />
                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                        <button type="submit" class="btn <?= $actionClass ?>" aria-hidden="true">Oui</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- updateStatusAlert box end -->
                        <!-- delete box begin-->
                        <div id="deleteAlert<?= $alert->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Supprimer Alerte</h3>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal loginFrm" action="../controller/AlertActionController.php" method="post">
                                    <p>Êtes-vous sûr de vouloir supprimer cette alerte ?</p>
                                    <div class="control-group">
                                        <label class="right-label"></label>
                                        <input type="hidden" name="action" value="delete" />
                                        <input type="hidden" name="idAlert" value="<?= $alert->id() ?>" />
                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- delete box end -->
                        <?php 
                        }
                        ?>
                    </div>
                </div>
            </div>  
        </div>       
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>        
    <script>jQuery(document).ready(function() { App.setPage("table_managed"); App.init(); });</script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>