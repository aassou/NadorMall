<?php 
    include('../app/classLoad.php');
    session_start();
    if ( isset($_SESSION['userMerlaTrav']) ) {
        $showTodos = 0;
        if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
            $showTodos = 1;    
        }
        //class managers
        $projetsManager = new ProjetManager(PDOFactory::getMysqlConnection());
        $companyManager = new CompanyManager(PDOFactory::getMysqlConnection());
        //obj and vars
        $companies    = $companyManager->getCompanys(); 
        $projetNumber = $projetsManager->getProjetsNumber();
        $projets      = $projetsManager->getProjetsOrdered();
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
        <?php include('../include/top-menu.php'); ?>
    </div>
    <div class="page-container row-fluid sidebar-closed">
        <?php include('../include/sidebar.php'); ?>
        <div class="page-content"> 
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="breadcrumb">
                            <li><i class="icon-home"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-briefcase"></i> <a><strong>Gestion des projets</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?php if ( isset($_SESSION['projet-action-message']) and isset($_SESSION['projet-type-message'])){ $message = $_SESSION['projet-action-message']; $typeMessage = $_SESSION['projet-type-message']; ?>
                        <div class="alert alert-<?= $typeMessage ?>"><button class="close" data-dismiss="alert"></button><?= $message ?></div>
                        <?php } unset($_SESSION['projet-action-message']); unset($_SESSION['projet-type-message']); ?>
                        <div class="tab-pane" id="tab_1_4">
                            <div class="row-fluid add-portfolio">
                                <div class="pull-left get-down">
                                    <span><?= $projetNumber ?> Projets</span>
                                </div>
                                <?php
                                if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
                                ?>
                                <div class="pull-right">
                                    <a href="projet-add.php" class="btn icn-only green"><i class="icon-plus-sign m-icon-white"></i>&nbsp;Nouveau Projet</a>                                  
                                </div>
                                <?php  
                                }
                                ?>
                            </div>
                            <!-- addProjet box begin-->
                            <div id="addProjet" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h3>Ajouter Nouveau Projet</h3>
                                </div>
                                <form class="form-horizontal" action="../controller/ProjetActionController.php" method="post">
                                    <div class="modal-body">
                                        <div class="control-group">
                                            <label class="control-label">Nom</label>
                                            <div class="controls">
                                                <input type="text" name="nom" />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Titre</label>
                                            <div class="controls">
                                                <input type="text" name="titre" />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Budget</label>
                                            <div class="controls">
                                                <input type="text" name="budget" />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Superficie</label>
                                            <div class="controls">
                                                <input type="text" name="superficie" />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Adresse</label>
                                            <div class="controls">
                                                <textarea type="text" name="adresse"></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Description</label>
                                            <div class="controls">
                                                <textarea type="text" name="description"></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">اسم المشروع</label>
                                            <div class="controls">
                                                <input type="text" name="nomArabe" />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">عنوان المشروع</label>
                                            <div class="controls">
                                                <input type="text" name="adresseArabe" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="control-group">
                                            <div class="controls">
                                                <input type="hidden" name="action" value="add" />  
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- addProjet box end -->
                            <!--end add-portfolio-->
                            <?php
                            foreach($projets as $projet){
                            ?>
                            <div class="row'fluid">
                                <div class="btn-group span4 projets">
                                    <a style="width: 250px"  class="btn big blue dropdown-toggle" data-toggle="dropdown" href="#">
                                    <strong><?= $projet->nom() ?></strong> <i class="icon-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="projet-details.php?idProjet=<?= $projet->id() ?>">Gestion du projet</a></li>
                                        <li><a href="#contratConstruction<?= $projet->id() ?>" data-toggle="modal" data-id="<?= $projet->id() ?>" class="dangerous-action">Contrat de Construction</a></li>
                                        <?php
                                        if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
                                        ?>
                                        <li><a href="projet-update.php?idProjet=<?= $projet->id() ?>">Modifier</a></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- updateProjet box begin-->
                            <div id="contratConstruction<?= $projet->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h3>Contrat de construction <?= $projet->nom() ?></h3>
                                </div>
                                <form target="_blank" class="form-horizontal" action="../controller/ContratConstructionPrintController.php" method="post">
                                    <div class="modal-body">
                                        <div class="control-group">
                                            <label class="control-label">Société 1</label>
                                            <div class="controls">
                                                <select name="company1">
                                                    <?php foreach ( $companies as $companie ) { ?>
                                                    <option value="<?= $companie->id() ?>"><?= $companie->nom() ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">ET</label>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Société 2</label>
                                            <div class="controls">
                                                <select name="company2">
                                                    <?php foreach ( $companies as $companie ) { ?>
                                                    <option value="<?= $companie->id() ?>"><?= $companie->nom() ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="control-group">
                                            <div class="controls">
                                                <input type="hidden" name="idProjet" value="<?= $projet->id() ?>" />
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- updateProjet box end -->
                            <?php }//end foreach loop for projets elements ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>
    <script>jQuery(document).ready(function() { App.init(); });
        var todosToday = <?= json_encode($todosToday); ?>;
        var todosTodayInformation = <?= json_encode($todosTodayInformation); ?>;
        var showTodos = <?= $showTodos ?>;
        var color = "";
        if (showTodos == 1){
            for (var k in todosToday, todosTodayInformation) {
                if ( todosTodayInformation[k] === null ) {
                    color = "info";
                }
                else {
                    color = "error";
                }
                $.notify(
                  "Tâche : "+todosToday[k],
                  color
                );    
            }         
        }
    </script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>