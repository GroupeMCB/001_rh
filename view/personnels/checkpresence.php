<?php 
	$title_for_layout = 'Personnel - Absence';
	$title_for_page_menu = 'Personnel';
	$current_menu = 'Absence';
?>

<div class="col-md-12">
	 	<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="presenceTable">
	 	<thead>
	 		<tr>
        		<th class="text-left"></th>
              	<th class="text-left">Nom</th>
        		<th class="text-left">Prénom</th>
        		<th class="text-left">Date de début</th>
        		<th class="text-left">Date de fin</th>
        		<th class="text-left">Nbre d'heures</th>
        		<th class="text-left">Absence justifiée</th>
              	<th class="text-left actionth">Action</th>
        	</tr>
		</thead>
			<?php $i = 1; ?>
			<tbody>
			<?php foreach ($allpersonnelAbsent as $value): ?>
		 		<tr>
		 			<td><?= $i++; ?></td>
		 			<td><?= $value->nom; ?></td>
		 			<td><?= $value->prenom ;?></td>
		 			<td><?= $value->date_debut ;?></td>
		 			<td><?= $value->date_fin ;?></td>
		 			<td><?= $value->nbre_heures ;?></td>
		 			<td>
		 				<?php 
		 					if ($value->type_absence_id == 1) {
		 						echo 'NON';
		 					}else{
		 						echo 'OUI ';
		 					}
		 				?>
		 			</td>
		 			<td>
		 				<!-- Déclencheur de la fenêtre modale Signaler une absence -->
	                	<a href="#" data-toggle="modal" data-target="#viewModal<?= $value->idpersonnel; ?>" class="btn btn-primary btn-sm">Modifier
	                    	<span class="fa fa-edit"></span>
	                	</a>

						<!-- Ajouter un certificat -->
						<?php if ($value->type_absence_id == 2 AND $value->certificat_id == NULL): ?>
		                	<a href="<?= BASE_URL; ?>/certificats/addcertificat/<?= $value->idpersonnel; ?>" class="btn btn-warning btn-sm">Ajouter un certificat 
		                    	
		                	</a>
						<?php endif ?>

						<!-- Déclencheur de la fenêtre modale pour la suppression -->
						<a href="#" data-toggle="modal" data-target="#delModal<?= $value->idpersonnel; ?>" class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></a>

						<!-- La fenetre modal de confirmation de la suppression -->
	                  	<div class="modal fade" id="delModal<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog">
		                      <div class="modal-content">
			                      <form action="<?= BASE_URL; ?>/personnels/delAbsence" method="post">
			                      	
			                        <div class="modal-header">
			                          <h3>Supression une absence</h3>
			                        </div>
			                        <div class="modal-body">
			                          <p>Voulez-vous vraiment supprimer cette absence pour <b><?= $value->nom; ?> <?= $value->prenom; ?></b>?</p>
									<input type="hidden" name="idabsence" value="<?= $value->idabsence; ?>">
			                        </div>
			                        <div class="modal-footer">
			                          <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">NON</a>
			                          <button type="submit" class="btn btn-primary">OUI</button>
			                        </div>
			                      </form>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->

	                	<!-- La fenetre modal Signaler une absence -->
	                  	<div class="modal fade" id="viewModal<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog">
		                      <form action="<?php echo BASE_URL; ?>/personnels/addabsence" class="form-horizontal" role="form" method="post">
			                      <div class="modal-content">
			                        <div class="modal-header">
			                          <h3>Modifier une absence</h3>
			                        </div>
			                        <div class="modal-body">
			                        	<p>Modifier absence pour : <b><?= $value->nom.' '.$value->prenom; ?></b></p>
			                        	<div class="form-group">
											
											<input type="hidden" name="idabsence" value="<?= $value->idabsence; ?>">
			                        		<input type="hidden" name="personnel_id" value="<?= $value->idpersonnel; ?>">

			                        		<input type="hidden" name="insertdate" value="<?= date('Y-m-d'); ?>">
									      	<label for="" class="control-label col-md-4">Date de début et fin : </label>

									        <div class="input-control text col-md-4">
									            <input type="text" class="form-control input-sm" id="date_debut<?php  echo $value->certificat_id ?>" name="date_debut" placeholder="Date de début" autocomplete="off" value="<?= $value->date_debut; ?>" />
									        </div>

									         <div class="input-control text col-md-4">
									            <input type="text" class="form-control input-sm" id="date_fin<?php  echo $value->certificat_id ?>" name="date_fin" placeholder="Date de fin" autocomplete="off" value="<?= $value->date_fin; ?>"/>
									        </div>

								    	</div>

								    	<div class="form-group">
								    		<label for="" class="control-label col-md-4">Nbre d'heures : </label>
								    		<div class="input-control text col-md-8">
								    			<input type="text" class="form-control input-sm" name="nbre_heures" value="<?= $value->nbre_heures; ?>">
								    		</div>
								    	</div>
										
										<div class="form-group">
								    		<label for="" class="control-label col-md-4">Motitif : </label>
								    		<div class="input-control text col-md-8">
								    			<select name="type_absence_id" class="form-control input-sm">
								    				<option value="1" <?= ($value->type_absence_id == '1') ? 'selected' : '' ; ?> >Absence non justifié</option>
								    				<option value="2" <?= ($value->type_absence_id == '2') ? 'selected' : '' ; ?>>Absence justifié</option>
								    			</select>
								    		</div>
								    	</div>

			                        </div>
			                        <div class="modal-footer">
			                          <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Fermer</a>
			                          <button type="submit" class="btn btn-primary">Enregistrer</button>
			                        </div>
		                       </form>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->

		 			</td>
		 		</tr>

				<script>
				   $('#date_fin<?php  echo $value->certificat_id ?>').datepicker({
				        language: 'fr',
				        format: "yyyy-mm-dd" 
				        });
				</script>

				<script>
				   $('#date_debut<?php  echo $value->certificat_id ?>').datepicker({
				        language: 'fr',
				        format: "yyyy-mm-dd" 
				        });
				</script>
			<?php endforeach ?>
			</tbody>
	 	</table>
	</div>
</div>


<script>
	$(document).ready(function() {

		$('#presenceTable').DataTable({
			"columns": [
				{ "orderable": false },
			    null,
			    null,
			    null,
			    null,
			    null,
			    null,
			    {"orderable": false}
			],
			"oLanguage": {
           		"oPaginate": {
            		"sPrevious": "Pr&eacute;c&eacute;dent",
            		"sNext": "Suivant"
            	},
           		"sSearch": "Rechercher : ",
           		"sEmptyTable": "Aucun ligne ajout&eacute;e",
           		"sInfo": "Nombre total de lignes : _TOTAL_ ",
           		"sInfoEmpty": "Aucun ligne ajout&eacute;e",
           		"sLengthMenu": " _MENU_  Absence",
            	"sZeroRecords": "Aucun ligne ne correspond &agrave; cette recherche",
            	"sInfoFiltered": " - (Filtrer de _MAX_ ligne)"
            }
		}); 
	});
</script>