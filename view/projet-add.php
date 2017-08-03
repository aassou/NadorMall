<?php
    include('../app/classLoad.php');  
    session_start();
    if(isset($_SESSION['userMerlaTrav']) and $_SESSION['userMerlaTrav']->profil()=="admin"){
    	//les sources
    	$usersManager = new UserManager(PDOFactory::getMysqlConnection());
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
							<li><a><strong>Nouveau projet</strong></a></li>
						</ul>>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
                        <?php if ( isset($_SESSION['projet-action-message']) and isset($_SESSION['projet-type-message'])){ $message = $_SESSION['projet-action-message']; $typeMessage = $_SESSION['projet-type-message']; ?>
                        <div class="alert alert-<?= $typeMessage ?>"><button class="close" data-dismiss="alert"></button><?= $message ?></div>
                        <?php } unset($_SESSION['projet-action-message']); unset($_SESSION['projet-type-message']); ?>
						<div class="tab-pane active" id="tab_1">
                           <div class="portlet box grey">
                              <div class="portlet-title">
                                 <h4><i class="icon-edit"></i>Ajouter un nouveau projet</h4>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <form id="addProjetForm" action="../controller/ProjetActionController.php" method="POST" class="horizontal-form">
                                    <div class="row-fluid">
                                        <div class="span3">
                                           <div class="control-group">
                                               <label class="control-label" for="nomArabe">اسم المشروع <sup class="dangerous-action">*</sup></label>
                                               <div class="controls">
                                                   <input type="text" id="nomArabe" name="nomArabe" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                           <div class="control-group">
                                               <label class="control-label" for="adresseArabe">عنوان المشروع <sup class="dangerous-action">*</sup></label>
                                               <div class="controls">
                                                   <input type="text" id="adresseArabe" name="adresseArabe" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                           <div class="control-group">
                                               <label class="control-label">Nom <sup class="dangerous-action">*</sup></label>
                                               <div class="controls">
                                                   <input type="text" name="nom" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                           <div class="control-group">
                                               <label class="control-label">Titre</label>
                                               <div class="controls">
                                                   <input type="text" name="titre" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="superficie">Superficie</label>
                                             <div class="controls">
                                                <input type="text" id="superficie" name="superficie" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="budget">Budget</label>
                                             <div class="controls">
                                                <input type="text" id="budget" name="budget" class="m-wrap span12">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                           <div class="control-group">
                                               <label class="control-label" for="numeroLot">Numero Lot</label>
                                               <div class="controls">
                                                   <input type="text" id="numeroLot" name="numeroLot" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                           <div class="control-group">
                                               <label class="control-label" for="numeroAutorisation">Numero Autorisation</label>
                                               <div class="controls">
                                                   <input type="text" id="numeroAutorisation" name="numeroAutorisation" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="dateAutorisation">Date d'Autorisation</label>
                                             <div class="controls">
                                                <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                    <input name="dateAutorisation" id="dateAutorisation" class="m-wrap m-ctrl-small date-picker" type="text" value="<?= date('Y-m-d') ?>" />
                                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                                 </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                               <label class="control-label" for="nombreEtages">Nombre Etages</label>
                                               <div class="controls">
                                                   <input type="text" id="nombreEtages" name="nombreEtages" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                               <label class="control-label" for="sousSol">Surface Sous-Sol</label>
                                               <div class="controls">
                                                   <input type="text" id="sousSol" name="sousSol" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                               <label class="control-label" for="rezDeChausser">Surface Rez De Chausser</label>
                                               <div class="controls">
                                                   <input type="text" id="rezDeChausser" name="rezDeChausser" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                       <div class="span3">
                                          <div class="control-group">
                                               <label class="control-label" for="mezzanin">Surface Mezzanin</label>
                                               <div class="controls">
                                                   <input type="text" id="mezzanin" name="mezzanin" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                           <div class="control-group">
                                               <label class="control-label" for="cageEscalier">Surface Cage Escaliers</label>
                                               <div class="controls">
                                                   <input type="text" id="cageEscalier" name="cageEscalier" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                           <div class="control-group">
                                               <label class="control-label" for="terrase">Surface Terrasse</label>
                                               <div class="controls">
                                                   <input type="text" id="terrase" name="terrase" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                               <label class="control-label" for="superficieEtages">Surface 1er-Nème Etage</label>
                                               <div class="controls">
                                                   <input type="text" id="superficieEtages" name="superficieEtages" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                       <div class="span3">
                                          <div class="control-group">
                                               <label class="control-label" for="delai">Delai/Mois</label>
                                               <div class="controls">
                                                   <input type="text" id="delai" name="delai" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                               <label class="control-label" for="prixParMetreHT">Prix/m² HT</label>
                                               <div class="controls">
                                                   <input type="text" id="prixParMetreHT" name="prixParMetreHT" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                               <label class="control-label" for="TVA">TVA</label>
                                               <div class="controls">
                                                   <input type="text" id="TVA" name="TVA" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                               <label class="control-label" for="prixParMetreTTC">Prix/m² TTC</label>
                                               <div class="controls">
                                                   <input type="text" id="prixParMetreTTC" name="prixParMetreTTC" class="m-wrap span12" />
                                               </div>
                                           </div>
                                       </div>
                                    </div>
                                    <div class="row-fluid">
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="adresse">Adresse <sup class="dangerous-action">*</sup></label>
                                             <div class="controls">
                                             	<textarea style="width:270px;" name="adresse" class="m-wrap span12" rows="3"></textarea>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="description">Description</label>
                                             <div class="controls">
    											<textarea style="width:270px;" name="description" class="m-wrap span12" rows="3"></textarea>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="architecte">Architecte</label>
                                             <div class="controls">
                                                <textarea style="width:270px;" id="architecte" name="architecte" class="m-wrap span12" rows="3"></textarea>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="bet">Bet</label>
                                             <div class="controls">
                                                <textarea style="width:270px;" id="bet" name="bet" class="m-wrap span12" rows="3"></textarea>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="hidden" name="action" value="add" />  
                                        <p class="dangerous-action">* : Champs obligatoires</p>
                                    	<a href="projets.php" class="btn red"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Retour</a>
                                       	<button type="submit" class="btn black">Ajouter <i class="icon-plus-sign"></i></button>
                                    </div>
                                 </form>
                              </div>
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
		jQuery(document).ready(function() { App.init();
			$('#prixParMetreHT, #TVA').change(function(){
                var prixParMetreHT = +$('#prixParMetreHT').val();
                var TVA = +$('#TVA').val();
                var prixParMetreTTC = prixParMetreHT + TVA;
                $('#prixParMetreTTC').val(prixParMetreTTC);
            });
            //validate form begins
            $("#addProjetForm").validate({
                 rules:{
                   nom: {
                       required: true
                   },
                   nomArabe: {
                       required: true
                   },
                   adresse: {
                       required: true
                   },
                   adresseArabe: {
                       required: true
                   },
                   superficie: {
                       number: true
                   },
                   budget: {
                       number: true
                   },
                   nombreEtages: {
                       number: true
                   },
                   sousSol: {
                       number: true
                   },
                   rezDeChausser: {
                       number: true
                   },
                   mezzanin: {
                       number: true
                   },
                   cageEscalier: {
                       number: true
                   },
                   terrase: {
                       number: true
                   },
                   budget: {
                       number: true
                   },
                   superficieEtages: {
                       number: true
                   },
                   delai: {
                       number: true
                   },
                   prixParMetreHT: {
                       number: true
                   },
                   prixParMetreTTC: {
                       number: true
                   },
                   TVA: {
                       number: true
                   }
                 },
                 errorClass: "error-class",
                 validClass: "valid-class"
            });
            //validate form ends
		});
	</script>
</body>
</html>
<?php
}
else if(isset($_SESSION['userMerlaTrav']) and $_SESSION->profil()!="admin"){
	header('Location:dashboard.php');
}
else{
    header('Location:index.php');    
}
?>