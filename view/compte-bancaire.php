<?php
    include('../app/classLoad.php');    
    include('../lib/pagination.php'); 
    //classes loading end
    session_start();
    if( isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil() == "admin" ) {
        //classManagers
        $compteBancaireManager = new CompteBancaireManager(PDOFactory::getMysqlConnection());
        $comptesBancaires = $compteBancaireManager->getCompteBancaires();
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <?php include('../include/head.php') ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <?php include('../include/top-menu.php') ?>   
        <!-- END TOP NAVIGATION BAR -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container row-fluid sidebar-closed">
        <!-- BEGIN SIDEBAR -->
        <?php include('../include/sidebar.php') ?>
        <!-- END SIDEBAR -->
        <!-- BEGIN PAGE -->
        <div class="page-content">
            <!-- BEGIN PAGE CONTAINER-->            
            <div class="container-fluid">
                <!-- BEGIN PAGE HEADER-->
                <div class="row-fluid">
                    <div class="span12">
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->           
                        <h3 class="page-title">
                            Gestion des comptes bancaires 
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="dashboard.php">Accueil</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <i class="icon-wrench"></i>
                                <a href="configuration.php">Paramètrages</a> 
                                <i class="icon-angle-right"></i>
                            </li>
                            <li>
                            <li>
                                <i class="icon-credit-card"></i>
                                <a>Gestion des comptes bancaires</a>
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <div class="row-fluid">
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="pull-right">
                                <a class="btn green get-down" href="#addCompteBancaire" data-toggle="modal">
                                    Nouveau Compte Bancaire <i class="icon-plus-sign m-icon-white"></i>
                                </a>    
                            </div>
                        </div>
                        <!-- addCompte box begin-->
                        <div id="addCompteBancaire" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Nouveau Compte Bancaire</h3>
                            </div>
                            <div class="modal-body">
                            <form class="form-horizontal" action="../controller/CompteBancaireActionController.php" method="post">
                                    <div class="control-group">
                                        <label class="control-label">Numéro du compte</label>
                                        <div class="controls">
                                            <input type="text" name="numero">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Dénomination</label>
                                        <div class="controls">
                                            <input type="text" name="denomination">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Date de création</label>
                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                            <input name="dateCreation" id="dateCreation" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                         </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">  
                                            <input type="hidden" name="action" value="add" />
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- addCompte box end -->
                        <!-- BEGIN Terrain TABLE PORTLET-->
                        <?php if(isset($_SESSION['CompteBancaire-action-message']) 
                        and isset($_SESSION['CompteBancaire-type-message'])){
                                $message = $_SESSION['CompteBancaire-action-message'];
                                $typeMessage = $_SESSION['CompteBancaire-type-message'];
                        ?>
                            <br>
                            <div class="alert alert-<?= $typeMessage ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $message ?>     
                            </div>
                         <?php } 
                            unset($_SESSION['CompteBancaire-action-message']);
                            unset($_SESSION['CompteBancaire-type-message']);
                         ?>
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-bordered table-advance table-hover">
                                    <thead>
                                        <tr>
                                            <th class="hidden-phone" style="width:25%">Actions</th>
                                            <th style="width:25%">Numéro compte</th>
                                            <th style="width:25%">Dénomination</th>
                                            <th style="width:25%">Date création</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($comptesBancaires as $compte){
                                        ?>      
                                        <tr>
                                            <td class="hidden-phone"><a class="btn green mini" href="#updateCompte<?= $compte->id();?>" data-toggle="modal" data-id="<?= $compte->id(); ?>"><i class="icon-refresh"></i></a></td>
                                            <td><?= $compte->numero() ?></td>
                                            <td><?= $compte->denomination() ?></td>
                                            <td><?= date('d/m/Y', strtotime($compte->dateCreation())) ?></td>
                                        </tr>
                                        <!-- updateCompte box begin-->
                                        <div id="updateCompte<?= $compte->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier les informations du compte bancaire</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="../controller/CompteBancaireActionController.php" method="post" enctype="multipart/form-data">
                                                    <div class="control-group">
                                                        <label class="control-label">Numéro Compte</label>
                                                        <div class="controls">
                                                            <input type="text" name="numero" value="<?= $compte->numero() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Dénomination</label>
                                                        <div class="controls">
                                                            <input type="text" name="denomination" value="<?= $compte->denomination() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Date Création</label>
                                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                            <input name="dateCreation" id="dateCreation" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= $compte->dateCreation() ?>" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                         </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <input type="hidden" name="idCompteBancaire" value="<?= $compte->id() ?>">
                                                            <input type="hidden" name="action" value="update" />
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- updateCompte box end -->   
                                        <!-- delete box begin-->
                                        <div id="deleteCompte<?= $compte->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Supprimer le compte bancaire</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal loginFrm" action="../controller/CompteBancaireActionController.php" method="post">
                                                    <p>Êtes-vous sûr de vouloir supprimer le compte bancaire <strong>N°<?= $compte->numero() ?></strong> ?</p>
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="idCompte" value="<?= $compte->id() ?>" />
                                                        <input type="hidden" name="action" value="delete" />
                                                        <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                        <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- delete box end --> 
                                        <?php
                                        }//end of loop
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END Terrain TABLE PORTLET-->
                    </div>
                </div>
                <!-- END PAGE CONTENT -->
            </div>
            <!-- END PAGE CONTAINER-->
        </div>
        <!-- END PAGE -->
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>        
    <script>
        jQuery(document).ready(function() {         
            // initiate layout and plugins
            //App.setPage("table_editable");
            App.init();
        });
    </script>
</body>
<!-- END BODY -->
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>