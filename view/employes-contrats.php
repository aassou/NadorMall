<?php
    include('../app/classLoad.php');  
    //classes loading end
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil() == "admin"){
        //les sources
        $employesManager = new EmployeManager(PDOFactory::getMysqlConnection());
        //$employeNumber = $employesManager->getEmployeNumbers();
        $employes = $employesManager->getEmployes();
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
                            Gestion des employés
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
                                <i class="icon-truck"></i>
                                <a>Gestion des employés</a>
                            </li>
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <!-- BEGIN PAGE CONTENT-->
                <div class="row-fluid">
                    <div class="span12">
                        <!-- addEmploye box begin-->
                        <div id="addEmploye" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter Nouveau Employé</h3>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" action="../controller/EmployeActionController.php" method="post">
                                    <div class="control-group">
                                        <label class="control-label">Nom</label>
                                        <div class="controls">
                                            <input required="required" type="text" name="nom" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">CIN</label>
                                        <div class="controls">
                                            <input type="text" name="cin" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Adresse</label>
                                        <div class="controls">
                                            <input type="text" name="adresse" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Téléphone</label>
                                        <div class="controls">
                                            <input type="text" name="telephone" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">الاسم</label>
                                        <div class="controls">
                                            <input type="text" name="nomArabe" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">العنوان</label>
                                        <div class="controls">
                                            <input type="text" name="adresseArabe" value="" />
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
                        <!-- addEmploye box end -->
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <?php 
                        if ( isset($_SESSION['employe-action-message']) 
                        and isset($_SESSION['employe-type-message'])) {
                            $message = $_SESSION['employe-action-message'];
                            $typeMessage = $_SESSION['employe-type-message'];
                        ?>
                            <div class="alert alert-<?= $typeMessage ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $message ?>      
                            </div>
                         <?php } 
                            unset($_SESSION['employe-action-message']);
                            unset($_SESSION['employe-type-message']);
                         ?>
                        </div>
                        <div class="portlet box light-grey" id="employes-contrats">
                            <div class="portlet-title">
                                <h4>Employés (Contrats)</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                    <div class="clearfix">
                                        <div class="btn-group pull-left">
                                            <a class="btn blue" href="#addEmploye" data-toggle="modal">
                                                <i class="icon-plus-sign"></i>
                                                 Ajouter Employé
                                            </a>
                                        </div>
                                        <!--div class="btn-group pull-right">
                                            <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Print</a></li>
                                                <li><a href="#">Save as PDF</a></li>
                                                <li><a href="#">Export to Excel</a></li>
                                            </ul>
                                        </div-->
                                    </div>
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th class="hidden-phone" style="width:10%">Actions</th>
                                                <th style="width:15%">Nom</th>
                                                <th class="hidden-phone" style="width:20%">Adresse</th>
                                                <th class="hidden-phone" style="width:15%">الاسم</th>
                                                <th class="hidden-phone" style="width:20%">العنوان</th>
                                                <th style="width:10%">CIN</th>
                                                <th style="width:10%">Téléphone</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ( $employes as $employe ) {
                                            ?>
                                            <tr>
                                                <td class="hidden-phone">
                                                    <a class="btn mini red" href="#delete<?= $employe->id();?>" data-toggle="modal" data-id="<?= $employe->id(); ?>">
                                                        <i class="icon-remove"></i>    
                                                    </a>
                                                    <a class="btn mini green" href="#update<?= $employe->id();?>" data-toggle="modal" data-id="<?= $employe->id(); ?>">
                                                        <i class="icon-refresh"></i>
                                                    </a>
                                                </td>
                                                <td><?= $employe->nom() ?></td>
                                                <td class="hidden-phone"><?= $employe->adresse() ?></td>
                                                <td class="hidden-phone"><?= $employe->nomArabe() ?></td>
                                                <td class="hidden-phone"><?= $employe->adresseArabe() ?></td>
                                                <td><?= $employe->cin() ?></td>
                                                <td><?= $employe->telephone() ?></td>
                                            </tr>
                                            <!-- updateEmploye box begin-->
                                            <div id="update<?= $employe->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h3>Modifier Informations Employé </h3>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal" action="../controller/EmployeActionController.php" method="post">
                                                        <div class="control-group">
                                                            <label class="control-label">Nom</label>
                                                            <div class="controls">
                                                                <input type="text" name="nom" value="<?= $employe->nom() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">CIN</label>
                                                            <div class="controls">
                                                                <input type="text" name="cin" value="<?= $employe->cin() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Adresse</label>
                                                            <div class="controls">
                                                                <input type="text" name="adresse" value="<?= $employe->adresse() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Téléphone</label>
                                                            <div class="controls">
                                                                <input type="text" name="telephone" value="<?= $employe->telephone() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">الاسم</label>
                                                            <div class="controls">
                                                                <input type="text" name="nomArabe" value="<?= $employe->nomArabe() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">العنوان</label>
                                                            <div class="controls">
                                                                <input type="text" name="adresseArabe" value="<?= $employe->adresseArabe() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <div class="controls">  
                                                                <input type="hidden" name="action" value="update" />
                                                                <input type="hidden" name="idEmploye" value="<?= $employe->id() ?>" />
                                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- updateEmploye box end -->
                                            <!-- delete box begin-->
                                            <div id="delete<?= $employe->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h3>Supprimer Employé</>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal loginFrm" action="../controller/EmployeActionController.php" method="post">
                                                        <p>Êtes-vous sûr de vouloir supprimer cet employe <strong><?= $employe->nom() ?></strong> ?</p>
                                                        <div class="control-group">
                                                            <label class="right-label"></label>
                                                            <input type="hidden" name="action" value="delete" />
                                                            <input type="hidden" name="idEmploye" value="<?= $employe->id() ?>" />
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>       
                        </div>
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
            App.setPage("table_managed");
            App.init();
        });
        $('.employes').show();
        $('#employe').keyup(function(){
            $('.employes').hide();
           var txt = $('#employe').val();
           $('.employes').each(function(){
               if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                   $(this).show();
               }
            });
        });
        $('#nature').keyup(function(){
            $('.employes').hide();
           var txt = $('#nature').val();
           $('.employes').each(function(){
               if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                   $(this).show();
               }
            });
        });
    </script>
</body>
<!-- END BODY -->
</html>
<?php
}
/*else if(isset($_SESSION['userMerlaTrav']) and $_SESSION->profil()!="admin"){
    header('Location:dashboard.php');
}*/
else{
    header('Location:index.php');    
}
