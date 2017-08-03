<?php
    include('../app/classLoad.php');
    session_start();
    if ( isset($_SESSION['userMerlaTrav']) ) {
        $idProjet           = 0;
        $projetManager      = new ProjetManager(PDOFactory::getMysqlConnection());
        $parkingManager     = new ParkingManager(PDOFactory::getMysqlConnection());
        $contratManager     = new ContratManager(PDOFactory::getMysqlConnection());
        $clientManager      = new ClientManager(PDOFactory::getMysqlConnection());
        $appartementManager = new AppartementManager(PDOFactory::getMysqlConnection());
        $locauxManager      = new LocauxManager(PDOFactory::getMysqlConnection());
        if(isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId()){
            $idProjet = $_GET['idProjet'];
            $projet   = $projetManager->getProjetById($idProjet);
            $parkings = $parkingManager->getParkingsByIdProjet($idProjet);
            $contrats = $contratManager->getContratsActifsByIdProjet($idProjet);
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
        <?php include('../include/top-menu.php') ?>
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
                            <li><a href="projet-details.php?idProjet=<?= $projet->id() ?>">Projet <strong><?= $projet->nom() ?></strong></a><i class="icon-angle-right"></i></li>
                            <li><a><strong>Gestion Sous-Sol</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="get-down">
                        <a href="#addParking" class="btn big blue" data-toggle="modal"><i class="icon-plus-sign"></i>&nbsp;Parking</a>
                        <!-- BEGIN addArticle Box -->
                            <div id="addParking" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h3>Ajouter Parkings</h3>
                                </div>
                                <form id="add-detail-livraison-form" class="form-horizontal" action="../controller/ParkingActionController.php" method="post">
                                    <div class="modal-body">
                                        <div class="control-group">
                                            <label class="control-label">Nombre de places</label>
                                            <div class="controls">
                                                <input required="required" type="text" name="nombrePlace" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="control-group">
                                            <div class="controls">  
                                                <input type="hidden" name="action" value="add" />
                                                <input type="hidden" name="idProjet" value="<?= $projet->id() ?>" />
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- END addParking BOX -->
                        </div>
                        <div class="tiles">
                            <?php 
                            foreach ($parkings as $parking) { 
                                $colorStatus = "red";
                                $status      = " -> Réservé pour ".strtoupper($parking->pour());
                                if ( $parking->status() == "Disponible" ) {
                                    $colorStatus = "green";
                                    $status      = "";
                                }
                            ?>
                            <a href="#actionParking<?= $parking->id() ?>" data-toggle="modal" data-id="<?= $parking->id() ?>">
                            <div class="tile double-down bg-<?= $colorStatus ?>">
                                <div class="tile-body">
                                    <i class="icon-truck"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name">
                                        <?= "Parking ".$parking->code().$status ?>
                                    </div>
                                    <div class="number">
                                    </div>
                                </div>
                            </div>
                            </a>
                            <!-- actionParking box box begin-->
                            <div id="actionParking<?= $parking->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h3>Modifier Parking <?= $parking->code() ?></h3>
                                </div>
                                <form class="form-horizontal" action="../controller/ParkingActionController.php" method="post">
                                    <div class="modal-body">
                                        <div class="control-group">
                                            <label class="control-label">Status</label>
                                            <div class="controls">
                                                <select name="status">
                                                    <option value="<?= $parking->status() ?>"><?= $parking->status() ?></option>
                                                    <option disabled="disabled">---------------------------------------</option>
                                                    <option value="Disponbile">Disponible</option>
                                                    <option value="Reservé">Reservé</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group autocomplet_container">
                                            <label class="control-label">Client</label>
                                            <div class="controls">
                                                <select name="client">
                                                    <option value="<?= $parking->pour() ?>"><?= $parking->pour() ?></option>
                                                    <option disabled="disabled">-------------------------------------------------------</option>
                                                    <?php 
                                                    foreach( $contrats as $contrat ){
                                                        $client = $clientManager->getClientById($contrat->idClient());
                                                        $bien = "";
                                                        if ( $contrat->typeBien() == "appartement" ) {
                                                            $appartement = $appartementManager->getAppartementById($contrat->idBien());
                                                            $bien = "Appart ".$appartement->nom();
                                                        } 
                                                        else if( $contrat->typeBien() == "localCommercial" ) {
                                                            $local = $locauxManager->getLocauxById($contrat->idBien());
                                                            $bien = "Local ".$local->nom();
                                                        }
                                                    ?>
                                                    <option value="<?= $client->nom()." : ".$bien ?>"><?= $client->nom()." : ".$bien ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Action</label>
                                            <div class="controls">
                                                <select name="action">
                                                    <option value="update">Modifier</option>
                                                    <option value="delete">Supprimer</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="control-group">
                                            <div class="controls">
                                                <input type="hidden" name="idProjet" value="<?= $projet->id() ?>" />
                                                <input type="hidden" name="idParking" value="<?= $parking->id() ?>" />
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- actionParking box end -->
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
                }
                else{
                ?>
                <div class="alert alert-error">
                    <button class="close" data-dismiss="alert"></button>
                    <strong>Erreur système : </strong>Ce projet n'existe pas sur votre système. Pour plus d'informations consulter votre administrateur.        
                </div>
                <?php
                }
                ?>
            </div>  
        </div>       
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>                 
    <script>jQuery(document).ready(function() { App.setPage("sliders"); App.init(); });</script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>