<?php
    include('../app/classLoad.php');
    session_start();
    if ( isset($_SESSION['userMerlaTrav']) ) {
        //les sources
        $idProjet = 0;
        $projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
        $contratEmployeManager = new ContratEmployeManager(PDOFactory::getMysqlConnection());
        $contratDetaislManager = new ContratDetailsManager(PDOFactory::getMysqlConnection());
        $employesManager = new EmployeManager(PDOFactory::getMysqlConnection());
        $companyManager = new CompanyManager(PDOFactory::getMysqlConnection());
        ///
        $companies = $companyManager->getCompanys();
        if(isset($_GET['idProjet']) and ($_GET['idProjet'])>0 and $_GET['idProjet']<=$projetManager->getLastId()){
            $idProjet = $_GET['idProjet'];
            $projet = $projetManager->getProjetById($idProjet);
            $contratEmployes = $contratEmployeManager->getContratEmployesByIdProjet($idProjet);
            $employes = $employesManager->getEmployes();
            //} 
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
                            <li><a href="projet-details.php?idProjet=<?= $idProjet ?>">Projet <strong><?= $projetManager->getProjetById($idProjet)->nom() ?></strong></a><i class="icon-angle-right"></i></li>
                            <li><a><strong>Gestion des Contrats Employés</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <!-- addEmploye box begin-->
                        <div id="addEmploye" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter un nouveau employé </h3>
                            </div>
                            <form class="form-horizontal" action="../controller/EmployeActionController.php" method="post">
                                <div class="modal-body">
                                    <div class="control-group">
                                        <label class="control-label">Nom</label>
                                        <div class="controls">
                                            <input type="text" name="nom" value="" />
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
                                </div>                                    
                                <div class="modal-footer">
                                        <div class="control-group">
                                            <div class="controls">
                                                <input type="hidden" name="action" value="add" />
                                                <input type="hidden" name="source" value="projet-contrat-employe" />
                                                <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </div>
                                </div>
                            </form>
                        </div>
                        <!-- addEmploye box end -->
                        <!-- addContratEmploye box begin-->
                        <div id="addContratEmploye" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h3>Ajouter un nouveau contrat employé </h3>
                            </div>
                            <form class="form-horizontal" action="../controller/ContratEmployeActionController.php" method="post">
                                <div class="modal-body">
                                    <div class="control-group">
                                        <label class="control-label">Employé</label>
                                        <div class="controls">
                                            <select name="employe">
                                                <?php foreach($employes as $employe){ ?>
                                                <option value="<?= $employe->id() ?>"><?= $employe->nom() ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Date début</label>
                                        <div class="controls">
                                            <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                <input name="dateContrat" id="dateContrat" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                                <span class="add-on"><i class="icon-calendar"></i></span>
                                            </div>
                                         </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Date fin</label>
                                        <div class="controls">
                                            <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                <input name="dateFinContrat" id="dateFinContrat" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                                <span class="add-on"><i class="icon-calendar"></i></span>
                                            </div>
                                         </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Traveaux</label>
                                        <div class="controls">
                                            <input type="text" name="traveaux" id="traveaux" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Prix/Unité</label>
                                        <div class="controls">
                                            <input type="text" name="prixUnitaire" id="prixUnitaire" style="width:90px" />&nbsp;/&nbsp;
                                            <select id="unite" name="unite" style="width:100px">
                                                <option value="m²">m²</option>
                                                <option value="m lineaire">m lineaire</option>
                                                <option value="appartement">appartement</option>
                                                <option value="unite">unite</option>
                                            </select>
                                            <input type="text" name="nomUnite" id="nomUnite" style="width:90px; display: none" />
                                        </div>
                                    </div>
                                    <div class="control-group" id="nomUniteArabe" style="display: none">
                                        <label class="control-label">اسم الوحدة</label>
                                        <div class="controls">
                                            <input type="text" name="nomUniteArabe" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Nombre Unités</label>
                                        <div class="controls">
                                            <input type="text" name="nombreUnites" id="nombreUnites" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Ajouter une autre unité</label>
                                        <div class="controls">
                                            <label class="radio">
                                                <div class="radio" id="oui">
                                                    <span>
                                                        <input type="radio" class="addUnite" name="addUnite" value="oui" style="opacity: 0;">
                                                    </span>
                                                </div> oui
                                            </label>
                                            <label class="radio">
                                                <div class="radio" id="non">
                                                    <span class="checked">
                                                        <input type="radio" class="addUnite" name="addUnite" value="non" checked="" style="opacity: 0;">
                                                    </span>
                                                </div> non
                                            </label>  
                                        </div>
                                    </div>
                                    <!-- Debut secondUnite  -->
                                    <div id="secondUnite" style="display: none">
                                    <div class="control-group">
                                        <label class="control-label">Prix/Unité</label>
                                        <div class="controls">
                                            <input type="text" name="prixUnitaire2" id="prixUnitaire2" value="0" style="width:90px" />&nbsp;/&nbsp;
                                            <select id="unite2" name="unite2" style="width:100px">
                                                <option value="m²">m²</option>
                                                <option value="m lineaire">m lineaire</option>
                                                <option value="appartement">appartement</option>
                                                <option value="unite">unite</option>
                                            </select>
                                            <input type="text" name="nomUnite2" id="nomUnite2" style="width:90px; display: none" />
                                        </div>
                                    </div>
                                    <div class="control-group" id="nomUniteArabe2" style="display: none">
                                        <label class="control-label">اسم الوحدة</label>
                                        <div class="controls">
                                            <input type="text" name="nomUniteArabe2" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Nombre Unités</label>
                                        <div class="controls">
                                            <input type="text" name="nombreUnites2" id="nombreUnites2" value="0" />
                                        </div>
                                    </div>
                                    </div>
                                    <!-- Fin secondUnite -->
                                    <div class="control-group">
                                        <label class="control-label">Total</label>
                                        <div class="controls">
                                            <input type="text" name="total" id="total" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">نوع الأشغال</label>
                                        <div class="controls">
                                            <input type="text" name="traveauxArabe" id="traveauxArabe" value="" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">الشركة</label>
                                        <div class="controls">
                                            <select name="idSociete">
                                                <?php
                                                foreach ( $companies as $company ) {
                                                ?>
                                                <option value="<?= $company->id() ?>"><?= $company->nomArabe() ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>  
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">بنود أخرى</label>
                                        <div class="controls">
                                            <textarea style="height:100px; width:300px" name="articlesArabes"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="control-group"> 
                                            <input type="hidden" name="action" value="add" />
                                            <input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- addPaiement box end -->
                        <?php if(isset($_SESSION['contratEmploye-action-message'])){ ?>
                            <div class="alert alert-<?= $_SESSION['contratEmploye-type-message'] ?>">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $_SESSION['contratEmploye-action-message'] ?>       
                            </div>
                         <?php } 
                            unset($_SESSION['contratEmploye-action-message']);
                         ?>
                        <div class="portlet box light-grey" id="employes-contrats">
                            <div class="portlet-title">
                                <h4>Contrats Employés</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                    <div class="clearfix">
                                        <?php
                                        if ( $_SESSION['userMerlaTrav']->profil() == "admin" ||
                                             $_SESSION['userMerlaTrav']->profil() == "manager") {
                                        ?>
                                        <div class="btn-group pull-left">
                                            <a class="btn blue" href="#addEmploye" data-toggle="modal">
                                                <i class="icon-plus-sign"></i>
                                                 Ajouter Employé
                                            </a>
                                        </div>
                                        <div class="btn-group pull-right">
                                            <a class="btn green" href="#addContratEmploye" data-toggle="modal">
                                                <i class="icon-plus-sign"></i>
                                                 Nouveau Contrat
                                            </a>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>
                                                <th style="width:15%">Actions</th>
                                                <th style="width:15%">Employé</th>
                                                <th class="hidden-phone" style="width:15%">Début - Fin</th>
                                                <th class="hidden-phone" style="width:15%">Prix/Unité</th>
                                                <th class="hidden-phone" style="width:10%">Nbr.Unit</th>
                                                <th style="width:10%">Total Payé</th>
                                                <th class="hidden-phone" style="width:10%">Total à Payer</th>
                                                <th style="width:10%">Reste</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($contratEmployes as $contrat){
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
                                                    ?>
                                                    <a class="btn mini red hidden-phone" href="#deleteContrat<?= $contrat->id() ?>" data-toggle="modal" data-id="<?= $contrat->id() ?>">
                                                        <i class="icon-remove"></i>
                                                    </a>
                                                    <a class="btn mini green hidden-phone" href="#updateContrat<?= $contrat->id() ?>" data-toggle="modal" data-id="<?= $contrat->id() ?>">
                                                        <i class="icon-refresh"></i>
                                                    </a>
                                                    <?php
                                                    }
                                                    ?>
                                                    <a class="btn mini" href="contrat-employe-detail.php?idContratEmploye=<?= $contrat->id() ?>&idProjet=<?= $projet->id() ?>">
                                                        <i class="icon-eye-open"></i>
                                                    </a>
                                                    <a class="btn mini blue" href="../controller/ContratEmployeArabePrintController.php?idContratEmploye=<?= $contrat->id() ?>" >
                                                        <i class="icon-file"></i>
                                                    </a>
                                                </td>
                                                <td><?= $employesManager->getEmployeById($contrat->employe())->nom() ?></td> 
                                                <td class="hidden-phone"><?= date('d/m/Y', strtotime($contrat->dateContrat()) ) ?> - <?= date('d/m/Y', strtotime($contrat->dateFinContrat()) ) ?></td>
                                                <td class="hidden-phone">
                                                    <?= number_format($contrat->prixUnitaire(), 2, ',', ' ') ?>&nbsp;/&nbsp;<?= $contrat->unite() ?>&nbsp;/&nbsp;<?= $contrat->nomUnite() ?><br/>
                                                    <?= number_format($contrat->prixUnitaire2(), 2, ',', ' ') ?>&nbsp;/&nbsp;<?= $contrat->unite2() ?>&nbsp;/&nbsp;<?= $contrat->nomUnite2() ?>
                                                </td>
                                                <td class="hidden-phone">
                                                    <?= $contrat->nombreUnites() ?><br/>
                                                    <?= $contrat->nombreUnites2() ?>
                                                </td>
                                                <td><?= number_format($contratDetaislManager->getContratDetailsTotalByIdContratEmploye($contrat->id()), 2, ',', ' ') ?></td>
                                                <td class="hidden-phone"><?= number_format($contrat->total(), 2, ',', ' ') ?></td>
                                                <td><?= number_format($contrat->total()-$contratDetaislManager->getContratDetailsTotalByIdContratEmploye($contrat->id()), 2, ',', ' ') ?></td>       
                                            </tr>      
                                            <!-- printContratArabe box begin-->
                                            <div id="printContratEmployeArabe<?= $contrat->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h3>Imprimer Contrat Employé</h3>
                                                </div>
                                                <form class="form-horizontal" action="../controller/ContratEmployeArabePrintController.php?idContratEmploye=<?= $contrat->id() ?>" method="post">
                                                    <div class="modal-body">
                                                        <div class="control-group">
                                                            <label class="control-label">الشركة</label>
                                                            <div class="controls">
                                                                <select name="nomSociete">
                                                                    <?php
                                                                    foreach ( $companies as $company ) {
                                                                    ?>
                                                                    <option value="<?= $company->id() ?>"><?= $company->nomArabe() ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="control-group">
                                                            <input type="hidden" name="idContrat" value="<?= $contrat->id() ?>" />
                                                            <input type="hidden" name="idProjet" value="<?= $contrat->idProjet() ?>" />
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- printContratArabe box end -->
                                            <!-- updatePaiement box begin -->
                                            <div id="updateContrat<?= $contrat->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h3>Modifier Contrat de  <?= $employesManager->getEmployeById($contrat->employe())->nom() ?></h3>
                                                </div>
                                                <form class="form-horizontal" action="../controller/ContratEmployeActionController.php" method="post">
                                                    <div class="modal-body">
                                                        <div class="control-group">
                                                            <label class="control-label">Employé</label>
                                                            <div class="controls">
                                                                <select name="employe">
                                                                    <option value="<?= $contrat->employe() ?>"><?= $employesManager->getEmployeById($contrat->employe())->nom() ?></option>
                                                                    <option disabled="disabled">-----------------</option>
                                                                    <?php foreach($employes as $employe){ ?>
                                                                    <option value="<?= $employe->id() ?>"><?= $employe->nom() ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Date début</label>
                                                            <div class="controls">
                                                                <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                                    <input name="dateContrat" id="dateContratUpdate" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= $contrat->dateContrat() ?>" />
                                                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                                                </div>
                                                             </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Date fin</label>
                                                            <div class="controls">
                                                                <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                                    <input name="dateFinContrat" id="dateFinContratUpdate" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= $contrat->dateFinContrat() ?>" />
                                                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                                                </div>
                                                             </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Traveaux</label>
                                                            <div class="controls">
                                                                <input type="text" name="traveaux" value="<?= $contrat->traveaux() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Prix/Unité</label>
                                                            <div class="controls">
                                                                <input type="text" name="prixUnitaire" id="pu<?= $contrat->id() ?>" value="<?= $contrat->prixUnitaire() ?>" style="width:90px" />&nbsp;/&nbsp;
                                                                <select name="unite" style="width:100px">
                                                                    <option value="<?= $contrat->unite() ?>"><?= $contrat->unite() ?></option>
                                                                    <option disabled="disabled">-----------------</option>
                                                                    <option value="m²">m²</option>
                                                                    <option value="m lineaire">m lineaire</option>
                                                                    <option value="appartement">appartement</option>
                                                                    <option value="unite">unite</option>
                                                                </select>
                                                                <input type="text" name="nomUnite" id="nomUniteUpdate" value="<?= $contrat->nomUnite() ?>" style="width:90px"  />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">اسم الوحدة</label>
                                                            <div class="controls">
                                                                <input type="text" name="nomUniteArabe" id="" value="<?= $contrat->nomUniteArabe() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Nombre Unités</label>
                                                            <div class="controls">
                                                                <input type="text" name="nombreUnites" id="nu<?= $contrat->id() ?>" value="<?= $contrat->nombreUnites() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Prix/Unité 2</label>
                                                            <div class="controls">
                                                                <input type="text" name="prixUnitaire2" id="pu2<?= $contrat->id() ?>" value="<?= $contrat->prixUnitaire2() ?>" style="width:90px" />&nbsp;/&nbsp;
                                                                <select name="unite2" style="width:100px">
                                                                    <option value="<?= $contrat->unite2() ?>"><?= $contrat->unite2() ?></option>
                                                                    <option disabled="disabled">-----------------</option>
                                                                    <option value="m²">m²</option>
                                                                    <option value="m lineaire">m lineaire</option>
                                                                    <option value="appartement">appartement</option>
                                                                    <option value="unite">unite</option>
                                                                </select>
                                                                <input type="text" name="nomUnite2" id="nomUniteUpdate2" value="<?= $contrat->nomUnite2() ?>" style="width:90px"  />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">2 اسم الوحدة</label>
                                                            <div class="controls">
                                                                <input type="text" name="nomUniteArabe2" id="" value="<?= $contrat->nomUniteArabe2() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Nombre Unités 2</label>
                                                            <div class="controls">
                                                                <input type="text" name="nombreUnites2" id="nu2<?= $contrat->id() ?>" value="<?= $contrat->nombreUnites2() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Total à payer</label>
                                                            <div class="controls">
                                                                <input type="text" name="total" id="tu<?= $contrat->id() ?>" value="<?= $contrat->total() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">نوع الأشغال</label>
                                                            <div class="controls">
                                                                <input type="text" name="traveauxArabe" value="<?= $contrat->traveauxArabe() ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">الشركة</label>
                                                            <div class="controls">
                                                                <select name="idSociete">
                                                                    <option value="<?= $contrat->idSociete() ?>"><?= $companyManager->getCompanyById($contrat->idSociete())->nomArabe() ?></option>
                                                                    <option disabled="disabled">-------------------------------</option>
                                                                    <?php
                                                                    foreach ( $companies as $company ) {
                                                                    ?>
                                                                    <option value="<?= $company->id() ?>"><?= $company->nomArabe() ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>  
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">بنود أخرى</label>
                                                            <div class="controls">
                                                                <textarea style="height:100px; width:300px" name="articlesArabes"><?= $contrat->articlesArabes() ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="control-group">
                                                            <input type="hidden" name="action" value="update" />
                                                            <input type="hidden" name="idContratEmploye" value="<?= $contrat->id() ?>" />
                                                            <input type="hidden" name="idProjet" value="<?= $projet->id() ?>" />
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>    
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- updatePaiementContrat box end -->      
                                            <!-- delete box begin-->
                                            <div id="deleteContrat<?= $contrat->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h3>Supprimer Contrat <?= $contrat->employe() ?></h3>
                                                </div>
                                                <form class="form-horizontal loginFrm" action="../controller/ContratEmployeActionController.php" method="post">
                                                    <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir supprimer contrat <strong><?= $contrat->employe() ?></strong> ?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="control-group">
                                                            <label class="right-label"></label>
                                                            <input type="hidden" name="action" value="delete" />
                                                            <input type="hidden" name="idContratEmploye" value="<?= $contrat->id() ?>" />
                                                            <input type="hidden" name="idProjet" value="<?= $projet->id() ?>" />
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- delete box end -->
                                            <script>
                                                $('#nu<?= $contrat->id() ?>, #pu<?= $contrat->id() ?>, #nu2<?= $contrat->id() ?>, #pu2<?= $contrat->id() ?>').change(function(){
                                                    var nu = $('#nu<?= $contrat->id() ?>').val();
                                                    var pu = $('#pu<?= $contrat->id() ?>').val();
                                                    var nu2 = $('#nu2<?= $contrat->id() ?>').val();
                                                    var pu2 = $('#pu2<?= $contrat->id() ?>').val();
                                                    var tu = (nu * pu) + (nu2 * pu2);
                                                    $('#tu<?= $contrat->id() ?>').val(tu); 
                                                });
                                            </script>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
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
    <script src="script.js"></script>       
    <script>
        jQuery(document).ready(function() {         
            // initiate layout and plugins
            App.setPage("table_managed");
            App.init();
        });
        
        $('#unite').on('change',function(){
            if( $(this).val()==="unite"){
                $("#nomUnite").show();
                $("#nomUniteArabe").show()
            }
            else{
                $("#nomUnite").hide();
                $("#nomUniteArabe").hide();
            }
        });

        $('#unite2').on('change',function(){
            if( $(this).val()==="unite"){
                $("#nomUnite2").show();
                $("#nomUniteArabe2").show()
            }
            else{
                $("#nomUnite2").hide();
                $("#nomUniteArabe2").hide();
            }
        });
        
        $('.addUnite').on('change',function(){
            if( $(this).val()==="oui"){
            $("#secondUnite").show()
            }
            else{
            $("#secondUnite").hide()
            }
        });
         
        $('.clients').show();
        $('#nomClient').keyup(function(){
            $('.clients').hide();
           var txt = $('#nomClient').val();
           $('.clients').each(function(){
               if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
                   $(this).show();
               }
            });
        });
        
        $('#nombreUnites, #prixUnitaire, #nombreUnites2, #prixUnitaire2').change(function(){
            var nombreUnites = $('#nombreUnites').val();
            var prixUnitaire = $('#prixUnitaire').val();
            var nombreUnites2 = $('#nombreUnites2').val();
            var prixUnitaire2 = $('#prixUnitaire2').val();
            var total = (nombreUnites * prixUnitaire) + (nombreUnites2 * prixUnitaire2);
            $('#total').val(total); 
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