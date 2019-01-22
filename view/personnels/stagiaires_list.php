<?php 

	$title_for_layout = 'Personnel - Stagiaires';
	$title_for_page_menu = 'Personnel';
	$current_menu = 'Stagiaires';

	$button_option = '<a class="btn btn-primary btn-xs" href="'.BASE_URL.'/personnels/add/stagiaires"><i class="fa fa-plus"></i> Ajouter</a>'.' '.
'<a class="btn btn-default btn-xs" data-toggle="modal" data-target="#"><i class="fa fa-download"></i> Exporter</a>';
?>

	<div class="col-md-12">		
	 	<table class="table table-bordered table-striped table-hover table-condensed" id="stagiairesTable">
	 		<thead>
		 		<tr>
	        		<th class="text-center" style="width: 1%;"></th>
	              	<th class="text-center">Nom<br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /> </th>
	        		<th class="text-center">Prénom<br><input type="text" class="form-controls input-sm" placeholder="" data-index="2" size="20" /> </th>
	        		<th class="text-center">Date d'entrée<br><input type="text" class="form-controls input-sm" placeholder="" data-index="3" size="20" /> </th>
	        		<th class="text-center">Département<br><input type="text" class="form-controls input-sm" placeholder="" data-index="3" size="20" /> </th>
	        		<th class="text-center">Poste Occupé<br><input type="text" class="form-controls input-sm" placeholder="" data-index="4" size="20" /> </th>
	              	<th class="text-center" style="width: 11%;">Action</th>
        		</tr>
        	</thead>
			<?php $i = 1; ?>
			<tbody>
			<?php foreach ($stagiaires as $value): ?>
	 		<tr>
	 			<td><?= $i++; ?></td>
	 			<td><?= $value->nom; ?></td>
	 			<td><?= $value->prenom ;?></td>
	 			<td><?= $value->date_entree ;?></td>
	 			<td><?php
		 					foreach ($departements as $departement) {
		 						if ($departement->iddepartement == $value->departements_id) {
		 							echo($departement->libelle_departement);
		 							break;
		 						}
		 					}
		 				?></td>
	 			<td>
					<?php 
	 					foreach ($titres as $titre) {
	 						if ($titre->idtitre == $value->titres_id) {
	 							echo($titre->nom);
	 							break;
	 						}
	 					}
	 				?>
	 			</td>
	 			<td>
	 				<!-- Button action -->
					<div class="btn-group dropup">
					  <button type="button" class="btn btn-primary btn-sm"><span class="fa fa-cog"></span> Actions</button>
					  <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    <span class="caret"></span>
					    <span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu dropdown-menu-right">
					    <li>
					    	<a href="<?= BASE_URL; ?>/personnels/addabsencefrom/<?= $value->idpersonnel; ?>"><span class="fa fa-user-times"></span> Signaler une absence</a>
					    </li>
					    <li>
					    	<a href="#"><a href="<?php echo BASE_URL; ?>/sanctions/addsanction/<?= $value->idpersonnel; ?>"><span class="fa fa-warning"></span> Ajouter une Sanction</a>
					    </li>
					    <li role="separator" class="divider"></li>
					    <li>
					    	<!-- Déclencheur de la fenêtre modale pour la consultation -->
							<a href=""  data-toggle="modal" data-target="#viewModal<?= $value->idpersonnel; ?>"><span class="fa fa-eye"></span> Consulter</a>
					    </li>
					    <li>
					    	<!-- Déclencheur de la fenêtre modale pour la modification -->
							<a href="<?php echo BASE_URL; ?>/personnels/edit/stagiaires/<?= $value->idpersonnel; ?>"><span class="fa fa-edit"></span> Modifier</a>
					    </li>
					    <li>
					    	<!-- Déclencheur de la fenêtre modale pour la suppression -->
							<a href="#" data-toggle="modal" data-target="#delModal<?= $value->idpersonnel; ?>"><span class="fa fa-trash"></span> Supprimer</a>
					    </li>
					    <li role="separator" class="divider"></li>
					    <li>
					    	<a href="#" data-toggle="modal" data-target="#outModal<?= $value->idpersonnel; ?>"><span class="fa fa-sign-out"></span> Sortir de l'effectif</a>
					    </li>
					  </ul>
					</div>

                	<!-- La fenetre modal de confirmation de la suppression -->
                  	<div class="modal fade" id="viewModal<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
	                    <div class="modal-dialog modal-lg">
	                      <div class="modal-content">
	                        <div class="modal-header">
	                          <h3>Consultation</h3>
	                        </div>
	                        <div class="modal-body">
	                          <table class="detail-view table table-striped" id="yw0">
	                          <tbody><tr class="odd">
	                          	<th>Nom</th><td><?= $value->nom; ?></td></tr>
								<tr class="even"><th>Date de naissance</th><td><?= $value->date_naissance; ?></td></tr>
								<tr class="odd"><th>Department</th>
									<td>
										<?php 
						 					foreach ($departements as $departement) {
						 						if ($departement->iddepartement == $value->departements_id) {
						 							echo($departement->libelle_departement);
						 							break;
						 						}
						 					}
						 				?>
									</td>
								</tr>
								<tr class="even"><th>Poste occupé</th>
									<td>
										<?php 
						 					foreach ($titres as $titre) {
						 						if ($titre->idtitre == $value->titres_id) {
						 							echo($titre->nom);
						 							break;
						 						}
						 					}
						 				?>
									</td>
								</tr>
								<tr class="odd"><th>Date entrée</th><td><?= $value->date_entree; ?></td></tr>
								<tr class="even"><th>Type de contrat</th><td><?= $value->typecontrat; ?></td></tr>
								<tr class="odd"><th>Date d'entrée dans le type de contrat</th><td></td></tr>
								<tr class="even"><th>Domaine d'étude</th><td><?= $value->domaine_etude; ?></td></tr>
								<tr class="odd"><th>Niveau d'étude</th><td><?= $value->niveau_etude; ?></td></tr>
								<tr class="even"><th>Adresse complète</th><td><?= $value->adresse_complete; ?></td></tr>
								<tr class="odd"><th>Situation matrimoniale</th><td><?= $value->situation_matrimoniale; ?></td></tr>
								<tr class="even"><th>Nombre d'enfants en charge</th><td><?= $value->nombre_enfant_charge; ?></td></tr>
								<tr class="odd"><th>Numéro CNSS</th><td><?= $value->numero_cnss; ?></td></tr>
								<tr class="even"><th>Emplacement dans le dossier du personnel</th><td></td></tr>
								<tr class="odd"><th>Crédit de congé dû à la date de la dernière MAJ avrilL 2015</th><td></td></tr>
								<tr class="even"><th>Date de la dernière promotion</th><td></td></tr>
								<tr class="odd"><th>Date de sortie</th><td><?= $value->date_sortie; ?></td></tr>
								</tbody></table>
	                        </div>
	                        <div class="modal-footer">
	                          <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Fermer</a>
	                          <a href="<?php echo BASE_URL; ?>/personnels/edit/stagiaires/<?= $value->idpersonnel; ?>" class="btn btn-primary">Modifier</a>
	                        </div>
	                      </div><!-- /.modal-content -->
	                    </div><!-- /.modal-dialog  -->
                  	</div> <!-- /.modal-fade -->

                  	<!-- La fenetre modal de confirmation de la suppression -->
                  	<div class="modal fade" id="delModal<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
	                    <div class="modal-dialog">
	                      <div class="modal-content">
	                        <div class="modal-header">
	                          <h3>Supression</h3>
	                        </div>
	                        <div class="modal-body">
	                          <p>Voulez-vous vraiment supprimer : <b></b>?</p>
	                        </div>
	                        <div class="modal-footer">
	                          <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">NON</a>
	                          <a href="<?php echo BASE_URL; ?>/personnels/del/stagiaires/<?= $value->idpersonnel; ?>" class="btn btn-primary">OUI</a>
	                        </div>
	                      </div><!-- /.modal-content -->
	                    </div><!-- /.modal-dialog  -->
                  	</div> <!-- /.modal-fade -->

                  	<form action="<?= BASE_URL; ?>/personnels/save/contractuels/" method="post">
		                  	<!-- La fenetre modal de confirmation de la suppression -->
		                  	<div class="modal fade" id="outModal<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
			                    <div class="modal-dialog">
			                      <div class="modal-content">
			                        <div class="modal-header">
			                          <h3>Sortir de l'effectif</h3>
			                        </div>
			                        <div class="modal-body">
			                          	<p>Voulez-vous vraiment sortir <b><?= $value->nom; ?> <?= $value->prenom; ?></b> de l'effectif ?</p>
			                          	<input type="hidden" value="<?= $value->idpersonnel; ?>" name="idpersonnel">
			                          	<input type="hidden" value="0" name="active">
										
										<div class="row">
											
											<div class="col-md-12">
					                          	<div class="form-group">
										        	<label for="" class="control-label col-md-4">Motif et date de sortie : </label>
										          	<div class="input-control text col-md-4">
										              	<select name="motif_sortie_id" class="form-control input-sm">
				              								<option value="">Selectionnez le motif de sortie</option>
				              								<?php foreach ($motifs_sortie as $motif): ?>
				                							<option value="<?= $motif->idmotif_sortie; ?>"><?= $motif->libelle_motif; ?></option>
				              								<?php endforeach; ?>
			            								</select>
										          	</div>

										           	<div class="input-control text col-md-4">
										              <input type="text" class="form-control input-sm" id="date_sortie" name="date_sortie" placeholder="Date de sortie" autocomplete="off"/>
										          	</div>

									      		</div>
											</div>
											
										</div>

			                        </div>
			                        <div class="modal-footer">
			                          <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">NON</a>
			                          <button class="btn btn-danger" type="submit">OUI</button>
			                        </div>
			                      </div><!-- /.modal-content -->
			                    </div><!-- /.modal-dialog  -->
		                  	</div> <!-- /.modal-fade -->
						</form>

	 			</td>
	 		</tr>
	 		<?php endforeach ?>
	 		</tbody>
	 	</table>
	</div>

	<script>
	$(document).ready(function() {

	var table=	$('#stagiairesTable').DataTable({
	     "ordering": false,
		 "sorting" : false,
		 "searching": true,
		 "bfilter":false,
		 "bLengthChange": false,
            "oLanguage": {
           		"oPaginate": {
            		"sPrevious": "Pr&eacute;c&eacute;dent",
            		"sNext": "Suivant"
            	},
           		"sSearch": "Rechercher : ",
           		"sEmptyTable": "Aucun stagiaires ajout&eacute;e",
           		"sInfo": "Nombre total de stagiaires : _TOTAL_ ",
           		"sInfoEmpty": "Aucun stagiaire ajout&eacute;e",
           		"sLengthMenu": " _MENU_  stagiaire",
            	"sZeroRecords": "Aucun stagiaire ne correspond &agrave; cette recherche",
            	"sInfoFiltered": " - (Filtrer de _MAX_ stagiaire)"
            }
		});



		 // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.header() ).on( 'keyup change', function () {

            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

	});
</script>