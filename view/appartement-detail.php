<?php
    include('../app/classLoad.php');
    include('../lib/image-processing.php');
    //classes loading end
    session_start();
    if( isset($_SESSION['userMerlaTrav']) ){
    	//les sources
    	$projetManager = new ProjetManager(PDOFactory::getMysqlConnection());
		$appartementManager = new AppartementManager(PDOFactory::getMysqlConnection());
		$appartement = "";
		$idAppartement = 0;
		$idProjet = $_GET['idProjet'];
        $projet = $projetManager->getProjetById($idProjet);
		if( isset($_GET['idAppartement']) and 
		( $_GET['idAppartement']>0 and $_GET['idAppartement']<=$appartementManager->getLastId() ) ){
			$idAppartement = htmlentities($_GET['idAppartement']);
			$appartement = $appartementManager->getAppartementById($idAppartement);
			$piecesManager = new AppartementPiecesManager(PDOFactory::getMysqlConnection());
			$piecesNumber = $piecesManager->getPiecesAppartementNumberByIdAppartement($idAppartement);
			if($piecesNumber != 0){
				$piecesAppartement = $piecesManager->getPiecesAppartementByIdAppartement($idAppartement);
		}	
	}
    //	DROPBOX Process
    $imageToDropBox = 0;
    if (isset($_FILES['url'])){
        if(file_exists($_FILES['url']['tmp_name']) || is_uploaded_file($_FILES['url']['tmp_name'])) {
            $imageToDropBox = imageProcessingSimlpePath($_FILES['url'], 'pieces/dropbox/', $projet->nom().'-Etage'.$appartement->niveau().'-Code'.$appartement->nom().'-ID'.$idAppartement);
            //echo $imageToDropBox;
        }    
    }	
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<?= include('../include/head.php') ?>
	<style type="text/css">
		a {outline: 0 none;}
		#wrap {
		 width: 800px;
		 margin: 0 auto;
		}
		div#fancy_print {
		 background: url("assets/img/print.png") no-repeat scroll left top transparent;
		 cursor: pointer;
		 width: 58px;
		 height: 60px;
		 position: absolute;
		 left: -15px;
		 top: -15px;
		 z-index: 9999;
		 display: block;
		}
	</style>
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
                            <li><i class="icon-briefcase"></i> <a href="projets.php">Gestion des projets</a><i class="icon-angle-right"></i></li>
                            <li><a href="projet-details.php?idProjet=<?= $projet->id() ?>">Projet <strong><?= $projet->nom() ?></strong></a><i class="icon-angle-right"></i></li>
                            <li><a href="appartements.php?idProjet=<?= $projet->id() ?>">Gestion des appartements</a><i class="icon-angle-right"></i></li>
							<li><a><strong>Fiche de l'appartement</strong></a></li>
						</ul>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<div class="tab-pane active" id="tab_1">
							<?php if(isset($_SESSION['pieces-add-success'])){ ?>
                         	<div class="alert alert-success"><button class="close" data-dismiss="alert"></button><?= $_SESSION['pieces-add-success'] ?></div>
	                         <?php } unset($_SESSION['pieces-add-success']);?>
	                         <?php if(isset($_SESSION['appartement-update-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['appartement-update-success'] ?>		
							</div>
	                         <?php } 
	                         	unset($_SESSION['appartement-update-success']);
	                         ?>
	                         <?php if(isset($_SESSION['pieces-delete-success'])){ ?>
                         	<div class="alert alert-success">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['pieces-delete-success'] ?>		
							</div>
	                         <?php } 
	                         	unset($_SESSION['pieces-delete-success']);
	                         ?>
	                         <?php if(isset($_SESSION['pieces-add-error'])){ ?>
	                         	<div class="alert alert-error">
									<button class="close" data-dismiss="alert"></button>
									<?= $_SESSION['pieces-add-error'] ?>		
								</div>
	                         <?php } 
	                         	unset($_SESSION['pieces-add-error']);
	                         ?>
                        </div>
					</div>
				</div>
				<div class="row-fluid profile"> 
					<div class="span12">
						<!--BEGIN TABS-->
						<?php
						if( $idAppartement != 0 ){
						?>
						<div class="tabbable tabbable-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_1_1" data-toggle="tab">Fiche de l'appartement</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane row-fluid active" id="tab_1_1">
									<ul class="unstyled profile-nav span3">
										<li>
											<a href="../controller/AppartementFichePrintController.php?idAppartement=<?= $appartement->id() ?>">
												Imprimer Fiche
											</a>
										</li>
										<!--li>
											<a href="#updateAppartement<?php //$appartement->id();?>" data-toggle="modal" data-id="<?php //$appartement->id(); ?>">
												Modifier Infos
											</a>
										</li-->
										<!--li>
											<a href="#addPiece<?php //$appartement->id();?>" data-toggle="modal" data-id="<?php //$appartement->id(); ?>">
												Ajouter un document
											</a>
										</li-->
									</ul>
									<div class="span9">
										<div class="row-fluid">
											<div class="span8 profile-info">
												<?php 
													if($appartement->status()=="Non"){
														echo '<a class="btn mini green">Disponible</a>';
													} 
													else if($appartement->status()=="Vendu"){
														echo '<a class="btn mini blue">Vendu</a>';
													}
													else{
														echo '<a class="btn mini red">Réservé</a>';
													}
												?>
												<br /><br />
												<h1>Code Appartement : <?= strtoupper($appartement->nom()) ?></h1>
												<h4>Projet : <?= strtoupper($projetManager->getProjetById($idProjet)->nom()) ?></h4>
												<ul class="unstyled inline">
													<li>
														<?php
														if($appartement->cave()=="Avec"){
															echo '<a class="btn mini blue">Avec Cave</a>';
														} 
														else{
															echo '<a class="btn mini black">Sans Cave</a>';
														}
														?>
													</li>
													<li><a>Supérifice</a> : <?= $appartement->superficie() ?></li>
													<li><a>Nombre de pièces</a> : <?= $appartement->nombrePiece() ?></li><br />
													<li><a>Niveau</a> : <?= $appartement->niveau() ?></li>
													<li><a>Façade</a> : <?= $appartement->facade() ?></li>
													<li><a>Prix</a> : <?= number_format($appartement->prix(), 2, ',', ' ') ?> DH</li>
													<li><a>Réservé par</a> : <?= $appartement->par() ?></li>
												</ul>
											</div>
											<!--end span8-->
											<form action="" method="post" enctype="multipart/form-data">
                                                <input type="file" name="url" />
                                                <input type="submit" value="Charger" />
                                            </form>  
                                            <a href="<?= $imageToDropBox ?>" class="dropbox-saver dropbox-dropin-btn dropbox-dropin-default"><span class="dropin-btn-status"></span>Enregistrer dans Dropbox</a>
										</div>
										<!--end row-fluid-->
									</div>
									<!--end span9-->
								</div>
								<!--end tab-pane-->
								<!-- update box begin-->
								<!-- update box begin-->
								<div id="updateAppartement<?= $appartement->id() ?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h3>Modifier Infos Appartement <strong><?= $appartement->nom() ?></strong></h3>
									</div>
									<div class="modal-body">
										<form class="form-horizontal" action="../controller/AppartementUpdateController.php?p=2" method="post">
											<p>Êtes-vous sûr de vouloir modifier les informations de l'appartement ?</p>
											<div class="control-group">
												<label class="right-label">Code</label>
												<input type="text" name="code" value="<?= $appartement->nom() ?>" />
												<label class="right-label">Supérficie</label>
												<input type="text" name="superficie" value="<?= $appartement->superficie() ?>" />
												<label class="right-label">Façade</label>
												<input type="text" name="facade" value="<?= $appartement->facade() ?>" />
												<label class="right-label">Niveau</label>
												<select name="niveau" class="m-wrap">
													<option value="<?= $appartement->niveau() ?>"><?= $appartement->niveau() ?></option>
													<option disabled="disabled">-------</option>
                                             		<option value="RC">RC</option>
                                             		<option value="Mezzanine">Mezzanine</option>
                                             		<option value="1">1</option>
                                             		<option value="2">2</option>
                                             		<option value="3">3</option>
                                             		<option value="4">4</option>
                                             		<option value="5">5</option>
                                             		<option value="6">6</option>
                                             		<option value="7">7</option>
                                             </select>
												<label class="right-label">Nombre Pièces</label>
												<input type="text" name="nombrePiece" value="<?= $appartement->nombrePiece() ?>" />
												<label class="right-label">Prix</label>
												<input type="text" name="prix" value="<?= $appartement->prix() ?>" />
												<label class="right-label">Status</label>
												<?php
												$statusReserve = "";
												$statusNonReserve = "";
												if($appartement->status()=="Oui"){
													$statusReserve = "selected";
													$statusNonReserve = "";		
												}
												if($appartement->status()=="Oui"){
													$statusReserve = "";
													$statusNonReserve = "selected";		
												}
												?>
												<select name="status" class="m-wrap">
													<option value="Non" <?php echo $statusReserve; echo $statusNonReserve ?> >
														Non réservé
													</option>
                                     				<option value="Oui" <?php echo $statusReserve; echo $statusNonReserve ?> >
                                     					Réservé
                                     				</option>
												</select>
												<label class="right-label">Cave</label>
												<?php
												$avecCave = "";
												$sansCave = "";
												if($appartement->cave()=="Avec"){
													$avecCave = "selected";
													$sansCave = "";		
												}
												if($appartement->cave()=="Sans"){
													$avecCave = "";
													$sansCave = "selected";		
												}
												?>
												<select name="cave" class="m-wrap">
													<option value="Sans" <?php echo $avecCave; echo $sansCave; ?> >
														Sans
													</option>
                                     				<option value="Avec" <?php echo $avecCave; echo $sansCave; ?> >
                                     					Avec
                                     				</option>
												</select>
												<input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
												<input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
												<label class="right-label"></label>
												<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
												<button type="submit" class="btn red" aria-hidden="true">Oui</button>
											</div>
										</form>
									</div>
								</div>	
								<!-- update box end -->	
								<!-- add piece box begin-->
								<div id="addPiece<?= $appartement->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h3>Ajouter Document Appartement</h3>
									</div>
									<div class="modal-body">
										<form class="form-horizontal" action="../controller/AppartementPiecesAddController.php?p=2" method="post" enctype="multipart/form-data">
											<p>Êtes-vous sûr de vouloir ajouter un document pour l'appartement <strong><?= $appartement->nom() ?></strong> ?</p>
											<div class="control-group">
												<label class="right-label">Nom Pièce</label>
												<input type="text" name="nom" />
												<label class="right-label">Lien</label>
												<input type="file" name="url" />
												<input type="hidden" name="idAppartement" value="<?= $appartement->id() ?>" />
												<input type="hidden" name="idProjet" value="<?= $appartement->idProjet() ?>" />
												<label class="right-label"></label>
												<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
												<button type="submit" class="btn red" aria-hidden="true">Oui</button>
											</div>
										</form>
									</div>
								</div>
								<!-- add piece box end -->
							</div>
						</div>
						<!--END TABS-->
						<div class="portlet">
							<div class="portlet-title" id="container-dropbox">
								<h4>Liste des documents</h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body">
							    <!--div id="container"></div-->
                                <!--a target="_blank" id="link-dropbox"></a-->
                                <div class="span3">
                                    <div class="item" id="container-dropbox-links">
                                        <!--a id="link-dropbox" class="fancybox-button fancybox"></a-->
                                    </div>
                                    <br><br>    
                                </div>
								<?php
								if($piecesNumber != 0){
								foreach($piecesAppartement as $pieces){
								?>
								<div class="span3">
									<div class="item">
										<a class="fancybox-button fancybox" data-rel="fancybox-button" title="<?= $pieces->nom() ?>" href="<?= $pieces->url() ?>">
											<div class="zoom">
												<img style="height: 100px; width: 200px" src="<?= $pieces->url() ?>" alt="<?= $pieces->nom() ?>" />							
												<div class="zoom-icon"></div>
											</div>
										</a>
									</div>
									<a class="btn mini red" href="#deletePiece<?= $pieces->id() ?>" data-toggle="modal" data-id="<?= $pieces->id() ?>">
										Supprimer
									</a>
									<br><br>	
								</div>
								<!-- delete box begin-->
								<div id="deletePiece<?= $pieces->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h3>Supprimer Pièce de l' Appartement <strong><?= $appartement->nom() ?></strong></h3>
									</div>
									<div class="modal-body">
										<form class="form-horizontal loginFrm" action="../controller/AppartementPiecesDeleteController.php?p=2" method="post">
											<p>Êtes-vous sûr de vouloir supprimer cette pièce ?</p>
											<div class="control-group">
												<label class="right-label"></label>
												<input type="hidden" name="idPieceAppartement" value="<?= $pieces->id() ?>" />
												<input type="hidden" name="idProjet" value="<?= $idProjet ?>" />
												<input type="hidden" name="idAppartement" value="<?= $idAppartement ?>" />
												<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
												<button type="submit" class="btn red" aria-hidden="true">Oui</button>
											</div>
										</form>
									</div>
								</div>
								<!-- delete box end -->
								<?php 
								}//end of loop : terrains
								}//end of if : terrainNumber
								?>
							</div>
						</div>
						<?php
						}
						else{
						?>
						<div class="alert alert-error">
							<button class="close" data-dismiss="alert"></button>
							Cet appartement n'existe pas dans votre système.		
						</div>
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
	<script>jQuery(document).ready(function() {App.init();});
		$(document).ready(function() {
		 $('.fancybox').attr("rel","gallery").fancybox({
		  afterShow: function(){
		    var win=null;
		    var content = $('.fancybox-inner');
		    $('.fancybox-wrap')
		    // append print button
		    .append('<div id="fancy_print"></div>')
		    // use .on() in its delegated form to bind a click to the print button event for future elements
		    .on("click", "#fancy_print", function(){
		      win = window.open("width=200,height=200");
		      self.focus();
		      win.document.open();
		      win.document.write('<'+'html'+'><'+'head'+'><'+'style'+'>');
		      win.document.write('body, td { font-family: Verdana; font-size: 10pt;}');
		      win.document.write('<'+'/'+'style'+'><'+'/'+'head'+'><'+'body'+'>');
		      win.document.write(content.html());
		      win.document.write('<'+'/'+'body'+'><'+'/'+'html'+'>');
		      win.document.close();
		      win.print();
		      win.close();
		    }); // on
		  } //  afterShow
		 }); // fancybox
		}); //  ready
	</script>
	<script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="ii1kxxvro0fr484"></script>
	<script>   
	    var containerDropBoxLinks = document.getElementById('container-dropbox-links');
	    function filesArray(element, index, array){
	        var link = document.createElement('a');
	        link.className = "fancybox-button fancybox";
	        var divZoom = document.createElement('div');
            divZoom.className = "zoom";
            var img = document.createElement('img');
            img.src = array[index].link;
            img.style.height = "100px";
            img.style.width = "200px";
            var divZoomIcon = document.createElement('div');
            divZoomIcon.className = "zoom-icon";
            //append childs
            divZoom.appendChild(img);
            divZoom.appendChild(divZoomIcon);
            link.appendChild(divZoom);
            containerDropBoxLinks.appendChild(link); 
	     }     
         var button = Dropbox.createChooseButton({
            success: function(files) {
                files.forEach(filesArray);
                //var containerDropBoxLinks = document.getElementById('container-dropbox-links');
                //var linkTag = document.getElementById('link-dropbox');
                //linkTag.href = files[0].link;
                //linkTag.textContent = files[0].name;
                //create div and img
            },
            multiselect: true,
            linkType: 'direct'
        });
        document.getElementById('container-dropbox').appendChild(button);
    </script>
</body>
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>