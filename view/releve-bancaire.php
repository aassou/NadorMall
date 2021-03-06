<?php
    include('../app/classLoad.php');
    session_start();
    if(isset($_SESSION['userMerlaTrav'])){
        //classes managers
        $releveBancaireManager   = new ReleveBancaireManager(PDOFactory::getMysqlConnection());
        $chargesCommunsManager   = new ChargeCommunManager(PDOFactory::getMysqlConnection());
        $typeChargeCommunManager = new TypeChargeCommunManager(PDOFactory::getMysqlConnection());
        $typeChargeProjetManager = new TypeChargeManager(PDOFactory::getMysqlConnection());
        $projetManager           = new ProjetManager(PDOFactory::getMysqlConnection());
        $compteBancaireManager   = new CompteBancaireManager(PDOFactory::getMysqlConnection());
        //obj and vars
        $typeChargesCommuns = $typeChargeCommunManager->getTypeCharges();
        $typeChargesProjets = $typeChargeProjetManager->getTypeCharges();
        $projets            = $projetManager->getProjetsOrdered();
        $releveBancaires    = $releveBancaireManager->getReleveBancaires();
        $comptesBancaires   = $compteBancaireManager->getCompteBancaires();
        $debit              = $releveBancaireManager->getTotalDebit();
        $credit             = $releveBancaireManager->getTotalCredit();
        $solde              = $credit - $debit;      
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
        <?php include('../include/top-menu.php') ?>   
    </div>
    <div class="page-container row-fluid sidebar-closed">
        <?php include('../include/sidebar.php') ?>
        <div class="page-content">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <ul class="breadcrumb">
                            <li><i class="icon-dashboard"></i> <a href="dashboard.php">Accueil</a><i class="icon-angle-right"></i></li>
                            <li><i class="icon-envelope"></i> <a><strong>Gestion des Relevés Bancaires</strong></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?php if ( isset($_SESSION['releveBancaire-action-message']) and isset($_SESSION['releveBancaire-type-message']) ){ $message = $_SESSION['releveBancaire-action-message']; $typeMessage = $_SESSION['releveBancaire-type-message']; ?>
                        <div class="alert alert-<?= $typeMessage ?>"><button class="close" data-dismiss="alert"></button><?= $message ?></div>
                        <?php } unset($_SESSION['releveBancaire-action-message']); unset($_SESSION['releveBancaire-type-message']); ?>
                        <div class="portlet">
                            <div class="portlet-title line">
                                <h4><i class="icon-envelope"></i>Ajouter un relevé bancaire</h4>
                            </div>
                            <div class="portlet-body" id="chats">
                                <form action="../controller/ReleveBancaireActionController.php" method="POST" enctype="multipart/form-data">
                                    <div class="control-group">
                                        <label class="control-label">Compte bancaire</label>
                                        <div class="controls">
                                            <select name="idCompteBancaire" class="m-wrap" >
                                                <?php foreach($comptesBancaires as $compte){ ?>
                                                <option value="<?= $compte->id() ?>"><?= $compte->numero() ?></option>
                                                <?php } ?>    
                                            </select>    
                                        </div>
                                    </div>
                                    <div class="control-group">   
                                        <input class="m-wrap" type="file" name="excelupload" />
                                    </div>
                                    <div class="btn-cont"> 
                                        <input type="hidden" name="action" value="add" />
                                        <button type="submit" class="btn blue icn-only"><i class="icon-save icon-white"></i>&nbsp;Enregistrer</button>
                                    </div>
                                </form>
                                <a href="#deleteReleveActuel" data-toggle="modal" class="btn red pull-right get-down"><i class="icon-trash"></i>&nbsp;Supprimer le relevé actuel</a>
                                <!-- deleteReleveActuel box begin-->
                                <div id="deleteReleveActuel" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h3>Supprimer Relevé Actuel</h3>
                                    </div>
                                    <div class="modal-body">
                                        <form class="form-horizontal loginFrm" action="../controller/ReleveBancaireActionController.php" method="post">
                                            <p>Êtes-vous sûr de vouloir supprimer ce relevé actuel ?</p>
                                            <div class="control-group">
                                                <label class="right-label"></label>
                                                <input type="hidden" name="action" value="deleteReleveActuel" />
                                                <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- deleteReleveActuel box end -->     
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="portlet box light-grey">
                            <div class="portlet-title">
                                <h4>Les Relevés Bancaires</h4>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"></a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th style="width:10%;"><span class="hidden-phone">Actions</span></th>
                                            <th class="hidden-phone" style="width:10%;">DateOpe</th>
                                            <th class="hidden-phone" style="width:10%;">DateVal</th>
                                            <th style="width:20%;">Libelle</th>
                                            <th style="width:10%;">Ref</th>
                                            <th style="width:15%;">Déb</th>
                                            <th style="width:15%;">Créd</th>
                                            <th class="hidden-phone" style="width:10%;">Projet</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($releveBancaires as $releve){
                                            $numeroCompte = $compteBancaireManager->getCompteBancaireById($releve->idCompteBancaire())->numero();
                                        ?>
                                        <tr class="odd gradeX">
                                            <td>
                                                <?php
                                                if ( $_SESSION['userMerlaTrav']->profil() == "admin" ) {
                                                ?>
                                                    <a href="#update<?= $releve->id() ?>" data-toggle="modal" data-id="<?= $releve->id() ?>" class="btn mini green hidden-phone"><i class="icon-refresh"></i></a>
                                                    <a href="#delete<?= $releve->id() ?>" data-toggle="modal" data-id="<?= $releve->id() ?>" class="btn mini red hidden-phone"><i class="icon-remove"></i></a>
                                                <?php  
                                                    //In this section we will process credit and debit element.
                                                    //The debit element will be added for fournisseur component
                                                    //The credit element will be added for client component
                                                    if ( $releve->debit() > 0 ) {
                                                ?>
                                                        <a title="Opérations Fournisseurs" href="#processFournisseur<?= $releve->id() ?>" data-toggle="modal" data-id="<?= $releve->id() ?>" class="btn mini blue"><i class="icon-cogs"></i></a>
                                                <?php
                                                    }
                                                    else if ( $releve->credit() > 0 ) {
                                                ?>
                                                        <a title="Opérations Client" href="#processClient<?= $releve->id() ?>" data-toggle="modal" data-id="<?= $releve->id() ?>" class="btn mini purple"><i class="icon-cogs"></i></a>
                                                <?php        
                                                    }
                                                }
                                                ?>
                                            </td>    
                                            <td class="hidden-phone"><?= $releve->dateOpe() ?></td>
                                            <td class="hidden-phone"><?= $releve->dateVal() ?></td>
                                            <td><?= $releve->libelle() ?></td>
                                            <td><?= $releve->reference() ?></td>
                                            <td><?= number_format($releve->debit(), 2, ',', ' ' ) ?></td>
                                            <td><?= number_format($releve->credit(), 2, ',', ' ') ?></td>
                                            <td class="hidden-phone"><?= $releve->projet() ?></td>
                                        </tr>
                                        <!-- updateReleve box begin-->
                                        <div id="update<?= $releve->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Modifier les informations du relevé </h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="../controller/ReleveBancaireActionController.php" method="post">
                                                    <div class="control-group">
                                                        <label class="control-label">Compte bancaire</label>
                                                        <div class="controls">
                                                            <select name="idCompteBancaire" class="m-wrap" >
                                                                <option value="<?= $releve->idCompteBancaire() ?>"><?= $numeroCompte ?></option>
                                                                <option disabled="disabled">----------------------</option>
                                                                <?php foreach($comptesBancaires as $compte){ ?>
                                                                <option value="<?= $compte->id() ?>"><?= $compte->numero() ?></option>
                                                                <?php } ?>    
                                                            </select>    
                                                         </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">DateOpe</label>
                                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                            <input name="dateOpe" id="dateOpe" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= $releve->dateOpe() ?>" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                         </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">DateVal</label>
                                                        <div class="controls date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                            <input name="dateVal" id="dateVal" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= $releve->dateVal() ?>" />
                                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                                         </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Libelle</label>
                                                        <div class="controls">
                                                            <textarea class="textarea-width" rows="3" name="libelle"><?= $releve->libelle() ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Reference</label>
                                                        <div class="controls">
                                                            <input type="text" name="reference" value="<?= $releve->reference() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Débit</label>
                                                        <div class="controls">
                                                            <input type="text" name="debit" value="<?= $releve->debit() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Crédit</label>
                                                        <div class="controls">
                                                            <input type="text" name="credit" value="<?= $releve->credit() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Projet</label>
                                                        <div class="controls">
                                                            <input type="text" name="projet" value="<?= $releve->projet() ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <input type="hidden" name="idReleveBancaire" value="<?= $releve->id() ?>" />
                                                        <input type="hidden" name="action" value="update" />
                                                        <div class="controls">  
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- updateReleve box end -->
                                        <!-- processFournisseur box begin-->
                                        <div id="processFournisseur<?= $releve->id() ?>" class="modal modal-big hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Affecter opération débit au système</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" action="../controller/ReleveBancaireActionController.php" method="post">
                                                    <div class="control-group">
                                                        <label class="control-label">Destination</label>
                                                        <div class="controls">
                                                            <select name="destinations" class="destinations">
                                                                <option value="ChargesCommuns">Charges communs</option>
                                                                <option value="ChargesProjets">Charges Projets</option>
                                                                <option value="Ignorer">Ignorer</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="chargesCommunsElements">
                                                        <div class="control-group">
                                                            <label class="control-label">Type Charge Commun</label>
                                                            <div class="controls">
                                                                <select name="typeChargesCommuns">
                                                                    <?php foreach( $typeChargesCommuns as $type ) { ?>    
                                                                    <option value="<?= $type->id() ?>"><?= $type->nom() ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Société</label>
                                                            <div class="controls">
                                                                <input type="text" name="societe" value="" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="chargesProjetsElements" style="">
                                                        <div class="control-group">
                                                            <label class="control-label">Type Charge Projet</label>
                                                            <div class="controls">
                                                                <select name="typeChargesProjet">
                                                                    <?php foreach( $typeChargesProjets as $type ) { ?>    
                                                                    <option value="<?= $type->id() ?>"><?= $type->nom() ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Projet</label>
                                                            <div class="controls">
                                                                <select name="projet">
                                                                    <?php foreach( $projets as $projet ) { ?>    
                                                                    <option value="<?= $projet->id() ?>"><?= $projet->nom() ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label">Société</label>
                                                            <div class="controls">
                                                                <input type="text" name="societe2" value="" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <input type="hidden" name="idReleveBancaire" value="<?= $releve->id() ?>" />
                                                        <input type="hidden" name="montant" value="<?= $releve->debit() ?>" />
                                                        <input type="hidden" name="dateOperation" value="<?= $releve->dateOpe() ?>" />
                                                        <input type="hidden" name="designation" value="<?= $releve->libelle() ?>" />
                                                        <input type="hidden" name="action" value="process-fournisseur" />
                                                        <div class="controls">  
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <button type="submit" class="btn red" aria-hidden="true">Oui</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- processFournisseur box end -->
                                        <!-- processClient box begin-->
                                        <div id="processClient<?= $releve->id() ?>" class="modal modal-big hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Affecter opération crédit au système</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal processClient" action="releve-bancaire-process-client.php" method="post">
                                                    <div class="control-group">
                                                        <label class="control-label">Action</label>
                                                        <div class="controls">
                                                            <select name="projet-contrat" class="projet-contrat span12">
                                                                <option value="Ignorer">Séléctionnez un projet ou Ignorer ?</option>
                                                                <?php foreach( $projets as $projet ) { ?>    
                                                                <option value="<?= $projet->id() ?>"><?= $projet->nom() ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Contrat client</label>
                                                        <div class="controls">
                                                            <select name="contrat-client" class="contrat-client span12">
                                                                <option value="">Séléctionnez un contrat ou Ignorer ?</option>
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label">Mode Paiement</label>
                                                        <div class="controls">
                                                            <select name="mode-paiement" class="span12">   
                                                                <option value="Especes">Espèces</option>
                                                                <option value="Cheque">Cheque</option>
                                                                <option value="Versement">Versement</option>
                                                                <option value="Virement">Virement</option>
                                                                <option value="Lettre de change">Lettre de change</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <input type="hidden" name="idReleveBancaire" value="<?= $releve->id() ?>" />
                                                        <input type="hidden" name="idOperation" class="idOperation" value="" />
                                                        <input type="hidden" name="montant" value="<?= $releve->credit() ?>" />
                                                        <input type="hidden" name="compte-bancaire" value="<?= $numeroCompte ?>" />
                                                        <input type="hidden" name="dateOperation" value="<?= $releve->dateOpe() ?>" />
                                                        <input type="hidden" name="dateReglement" value="<?= $releve->dateVal() ?>" />
                                                        <input type="hidden" name="observation" value="<?= $releve->libelle() ?>" />
                                                        <input type="hidden" name="reference" value="<?= $releve->reference() ?>" />
                                                        <input type="hidden" name="action" value="process-client" />
                                                        <div class="controls">  
                                                            <button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
                                                            <input type="submit" name="submit" value="Oui" class="btn red" aria-hidden="true">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- processClient box end -->
                                        <!-- delete box begin-->
                                        <div id="delete<?= $releve->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h3>Supprimer Relevé</h3>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal loginFrm" action="../controller/ReleveBancaireActionController.php" method="post">
                                                    <div class="control-group">
                                                        <label class="right-label"></label>
                                                        <input type="hidden" name="idReleveBancaire" value="<?= $releve->id() ?>" />
                                                        <input type="hidden" name="action" value="delete" />
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
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <tbody>
                                        <tr>
                                            <th style="width:60%;">Total Débit</th>
                                            <th style="width:20%"><a><?= number_format($debit, '2', ',', ' ') ?></a>&nbsp;DH</th>
                                            <th style="width:20%;"></th>
                                        </tr>
                                        <tr>
                                            <th style="width:60%;">Total Crédit</th>
                                            <th style="width:20%;"></th>
                                            <th style="width:20%"><a><?= number_format($credit, '2', ',', ' ') ?></a>&nbsp;DH</th>
                                        </tr>
                                        <tr>
                                            <th style="width:60%;">Solde</th>
                                            <th style="width:20%"><a><?= number_format($solde, '2', ',', ' ') ?></a>&nbsp;DH</th>
                                            <th style="width:20%;"></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>             
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <?php include('../include/footer.php') ?>
    <?php include('../include/scripts.php') ?>
    <script>
        jQuery(document).ready(function() { App.setPage("table_managed"); App.init();
            //processFournisseur begin
            $(".chargesProjetsElements").hide();
            $('.destinations').on('change',function(){
                if ( $(this).val() === "ChargesCommuns" ) {
                    $(".chargesCommunsElements").show();
                    $(".chargesProjetsElements").hide();
                }
                else if ( $(this).val() === "ChargesProjets" ) {
                    $(".chargesProjetsElements").show();
                    $(".chargesCommunsElements").hide();
                }
                else {
                    $(".chargesCommunsElements").hide();
                    $(".chargesProjetsElements").hide();    
                }
                
            }); 
            //processFournisseur end
            //processClient begin
            $('.projet-contrat').change(function(){
                var idProjet = $(this).val();
                var data     = 'idProjet='+idProjet;
                $.ajax({
                    type: "POST",
                    url: "projets-contrats.php",
                    data: data,
                    cache: false,
                    success: function(html){
                        $('.contrat-client').html(html);
                    }
                });
            });
            //synthese client
            $('.contrat-client').change(function(){
                var idContrat = $(this).val();
                var data      = 'idContrat='+idContrat;
                $.ajax({
                    type: "POST",
                    url: "synthese-client.php",
                    data: data,
                    cache: false,
                    success: function(data){
                        //$('.synthese-client').html('');
                        $(data).appendTo(".processClient");
                        //$('.synthese-client').html(data);
                    }
                });
            });
            //Update Client Operations based on ReleveBancaire Informations
            $('.processClient').submit(function(e){
                e.preventDefault(); // Prevent Default Submission
                $.ajax({
                    url: 'releve-bancaire-process-client.php',
                    type: 'POST',
                    data: $(this).serialize(), // it will serialize the form data
                    dataType: 'html'
                })
                .done(function(data){
                    alert(data);
                    location.reload();
                })
                .fail(function(){
                    alert('Ajax Submit Failed ...'); 
                });
            });
            $('.operationValue').change(function (){
                var idOperation = $(this).val();
                $('.idOperation').val(idOperation);
            });
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