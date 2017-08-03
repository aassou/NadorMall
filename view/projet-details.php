<?php
    include('../app/classLoad.php');  
    session_start();
    if( isset($_SESSION['userMerlaTrav']) ) {
        $showTodos = 0;
        if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
            $showTodos = 1;    
        }    
        //les sources
        $idProjet = $_GET['idProjet'];
        $projetsManager = new ProjetManager(PDOFactory::getMysqlConnection());
        $projet = $projetsManager->getProjetById($idProjet);
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
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <?php include('../include/top-menu.php'); ?>
    </div>
    <div class="page-container row-fluid sidebar-closed">
        <?php include('../include/sidebar.php') ?>
        <div class="page-content">            
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="breadcrumb">
                            <li><i class="icon-home"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-briefcase"></i> <a href="projets.php">Gestion des projets</a><i class="icon-angle-right"></i></li>
                            <li><a><strong>Projet <?= $projet->nom() ?></strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?php if(isset($_SESSION['user-delete-success'])){ ?>
                            <div class="alert alert-success">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $_SESSION['user-delete-success'] ?>     
                            </div>
                         <?php } 
                            unset($_SESSION['user-delete-success']);
                         ?>
                        <div class="tab-pane" id="tab_1_4">
                            <div class="row-fluid portfolio-block" id="<?= $projet->id() ?>">
                                <div class="span1 portfolio-text" style="width:200px">
                                    <div class="portfolio-text-info">
                                        <a class="btn big blue"><?= $projet->nom() ?></a>
                                    </div>
                                </div>
                                <div class="span11" style="overflow:hidden;">
                                    <div class="portfolio-info">
                                        <a style="margin-top:5px" href="terrain.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big black stay-away">Terrain</a>
                                        <a style="margin-top:5px" href="appartements.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big brown stay-away">Appartements</a>
                                        <a style="margin-top:5px" href="locaux.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big purple stay-away">Les locaux commerciaux</a>
                                    </div>
                                    <div class="portfolio-info">
                                        <?php
                                        if ( 
                                            $_SESSION['userMerlaTrav']->profil()=="admin" 
                                            || $_SESSION['userMerlaTrav']->profil()=="consultant" 
                                        ) {
                                        ?>
                                        <a style="margin-top:5px" href="projet-charges-grouped.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big dark-red stay-away">Charges du Projet</a>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if ( 
                                            $_SESSION['userMerlaTrav']->profil()=="admin" ||
                                            $_SESSION['userMerlaTrav']->profil()=="manager"
                                            ) {
                                        ?>
                                        <a style="margin-top:5px" href="clients-add.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big red stay-away">Créer Clients et Contrats</a>
                                        <?php
                                        }
                                        ?>
                                        <a style="margin-top:5px" href="contrats-list.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big green stay-away">Listes Clients et Contrats</a>
                                    </div>
                                    <div class="portfolio-info">
                                        <a style="margin-top:5px" href="contrats-desistes-list.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big yellow stay-away">Contrats Désistés</a>
                                        <a style="margin-top:5px" href="projet-contrat-employe.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big stay-away">Contrats employés</a>
                                        <a style="margin-top:5px" href="suivi-projets.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big dark-cyan stay-away">Statistiques</a>
                                    </div>
                                    <div class="portfolio-info">
                                        <a style="margin-top:5px" href="syndique.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big dark-blue stay-away">Gestion Syndique</a>
                                        <a style="margin-top:5px" href="sous-sol.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big blue stay-away">Gestion Sous-Sol</a>
                                        <!--a style="margin-top:5px" href="suivi-projets.php?idProjet=<?= $projet->id() ?>" class="btn btn-fixed-width-big dark-cyan stay-away">Statistiques</a-->
                                    </div>
                                </div>
                            </div>
                            <br><br>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>        
    <script> jQuery(document).ready(function() { App.init(); });
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