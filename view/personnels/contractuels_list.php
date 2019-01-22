<?php
setlocale (LC_TIME, 'fr_FR');
	$title_for_layout = 'Personnel - Contractuels';
	$title_for_page_menu = 'Personnel';
	$current_menu = 'Contractuels';

	$button_option = '<a class="btn btn-primary btn-xs" href="'.BASE_URL.'/personnels/add/contractuels"><i class="fa fa-plus"></i> Ajouter</a>'.' '.
'<a class="btn btn-default btn-xs" data-toggle="modal" data-target="#"><i class="fa fa-download"></i> Exporter</a>';
//debug($contractuels);
?>
 <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">Tableau de bord</a>
            </li>
            <li class="breadcrumb-item active">Vue</li>
          </ol>
	<div class="col-md-12">
<div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-users"></i>
              Liste du personnel
              <span class="pull-md-right">
              		<a class="btn btn-primary btn-xs" href="<?php echo BASE_URL  ?>/personnels/add/contractuels"><i class="fa fa-plus"></i> Ajouter</a> 
              </span>
          </div>
            <div class="card-body">


	 	<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="contractuelsTable">
	 		<thead>
	 		<tr>
        		 
        		<th class="text-left" style="width: 5%;"></th>
            <th class="text-center">Nom </th>
        		<th class="text-center">Prénom </th>
        		<!-- <th class="text-center">Date d'entrée </th> -->
        		<th class="text-center">Département </th>
        		<th class="text-center">Poste Occupé </th>
        		<th class="text-center">Contrat </th>
            <th class="text-center" style="width:11%;">Action</th>
        	</tr>
        	</thead>
        	<?php $i = 1; ?>

			<tbody>
			<?php foreach ($contractuels as $value): ?>
		 		<tr>
		 		 
		 			<td>  <img src="<?php echo BASE_URL; ?>/webroot/images/user/<?php echo  $value->photo?>" height=50 width=50 alt="Picture"></td>
		 			<td><?= $value->nom; ?></td>
		 			<td><?= $value->prenom ;?></td>
		 			<!-- <td><?php //date('d F Y',strtotime($value->date_entree)) ;?></td> -->
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
		 			<td><?php if(isset($value->typecontrat)) echo $value->typecontrat; ?></td>
		 			<td>
		 				<a href="<?php echo BASE_URL; ?>/personnels/edit/contractuels/<?= $value->idpersonnel; ?>" class="" ><i class="fa fa-edit"></i><a>
		 				<a href="" class="" ><i class="fa fa-eye"></i><a>
		 				<a href="" class="" ><i class="fa fa-close"></i><a>
		 				<!-- Button action -->
					

	                	<!-- La fenetre modal de consultation -->
	                  	<div class="modal fade quickView" id="viewModal<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de consultation" aria-hidden="true">
		                    <div class="modal-dialog modal-lg">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h3>Consultation</h3>
		                        </div>
		                        <div class="modal-body">

															<div class="page-header">
															  <h4>Informations sur l'employe</h4>
															</div>
															<div class="panel panel-default col-md-5">
															  <div class="panel-body">
																	<span class="text-muted">
																		Nom et Prenoms
																	</span>
																	<br>
																	<span><?= $value->nom . ' ' . $value->prenom; ?></span>
															  </div>
															</div>

															<div class="panel panel-default col-md-5 col-md-offset-1">
															  <div class="panel-body">
																	<span class="text-muted">
																		Date de Naissance
																	</span>
																	<br>
																	<span><?= $value->date_naissance; ?></span>
															  </div>
															</div>

															<div class="panel panel-default col-md-5">
															  <div class="panel-body">
																	<span class="text-muted">
																		Domaine d'étude
																	</span>
																	<br>
																	<span><?= $value->domaine_etude; ?></span>
															  </div>
															</div>

															<div class="panel panel-default col-md-5 col-md-offset-1">
															  <div class="panel-body">
																	<span class="text-muted">
																		Niveau d'étude
																	</span>
																	<br>
																	<span><?= ($value->niveau_etude == '') ? '&nbsp;' : $value->niveau_etude; ?></span>
															  </div>
															</div>


															<div class="panel panel-default col-md-5">
															  <div class="panel-body">
																	<span class="text-muted">
																		Situation matrimoniale
																	</span>
																	<br>
																	<span><?= $value->situation_matrimoniale; ?></span>
															  </div>
															</div>

															<div class="panel panel-default col-md-5 col-md-offset-1">
															  <div class="panel-body">
																	<span class="text-muted">
																		Nombre d'enfants en charge
																	</span>
																	<br>
																	<span><?= $value->nombre_enfant_charge; ?></span>
															  </div>
															</div>

															<div class="panel panel-default col-md-11">
																<div class="panel-body">
																	<span class="text-muted">
																		Adresse complète
																	</span>
																	<br>
																	<span><?= $value->adresse_complete; ?></span>
															  </div>
															</div>

															<div class="page-header col-md-12">
															  <h4>Autres Informations</h4>
															</div>

															<div class="panel panel-default col-md-7">
															  <div class="panel-body">
																	<span class="text-muted">
																		Départment
																	</span>
																	<br>
																	<span>
																		<?php
														 					foreach ($departements as $departement) {
														 						if ($departement->iddepartement == $value->departements_id) {
														 							echo($departement->libelle_departement);
														 							break;
														 						}
														 					}
														 				?>
																	</span>
															  </div>
															</div>

															<div class="panel panel-default col-md-3 col-md-offset-1">
															  <div class="panel-body">
																	<span class="text-muted">
																		Poste occupé
																	</span>
																	<br>
																	<span>
																		<?php
														 					foreach ($titres as $titre) {
														 						if ($titre->idtitre == $value->titres_id) {
														 							echo($titre->nom);
														 							break;
														 						}
														 					}
														 				?>
																	</span>
															  </div>
															</div>

															<div class="panel panel-default col-md-3">
															  <div class="panel-body">
																	<span class="text-muted">
																		Numéro CNSS
																	</span>
																	<br>
																	<span><?= $value->numero_cnss; ?></span>
															  </div>
															</div>

															<div class="panel panel-default col-md-3 col-md-offset-1">
															  <div class="panel-body">
																	<span class="text-muted">
																		Date entrée
																	</span>
																	<br>
																	<span><?= $value->date_entree; ?></span>
															  </div>
															</div>

															<div class="panel panel-default col-md-3 col-md-offset-1">
															  <div class="panel-body">
																	<span class="text-muted">
																		Date de sortie
																	</span>
																	<br>
																	<span><?= $value->date_sortie; ?></span>
															  </div>
															</div>

															<div class="panel panel-default col-md-3">
															  <div class="panel-body">
																	<span class="text-muted">
																		Type de contrat
																	</span>
																	<br>
																	<span><?= $value->typecontrat; ?></span>
															  </div>
															</div>

															<div class="panel panel-default col-md-3 col-md-offset-1">
															  <div class="panel-body">
																	<span class="text-muted">
																		Date d'entrée dans le type de contrat
																	</span>
																	<br>
																	<span>&nbsp;</span>
															  </div>
															</div>

															<div class="panel panel-default col-md-3 col-md-offset-1">
															  <div class="panel-body">
																	<span class="text-muted">
																		Date dernière promotion
																	</span>
																	<br>
																	<span>&nbsp;</span>
															  </div>
															</div>

															<div class="panel panel-default col-md-5">
															  <div class="panel-body">
																	<span class="text-muted">
																		Crédit de congé dû à la date de la dernière MAJ avrilL 2015
																	</span>
																	<br>
																	<span>&nbsp;</span>
															  </div>
															</div>

															<div class="panel panel-default col-md-5 col-md-offset-1">
															  <div class="panel-body">
																	<span class="text-muted">
																		Emplacement dans le dossier du personnel
																	</span>
																	<br>
																	<span>&nbsp;</span>
															  </div>
															</div>

		                          <table class="detail-view table table-striped" id="yw0">

															</table>
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Fermer</a>
		                          <a href="<?php echo BASE_URL; ?>/personnels/edit/contractuels/<?= $value->idpersonnel; ?>" class="btn btn-primary">Modifier</a>
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
		                          <p>Voulez-vous vraiment supprimer <b><?= $value->nom; ?> <?= $value->prenom; ?></b>?</p>
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">NON</a>
		                          <a href="<?php echo BASE_URL; ?>/personnels/del/contractuels/<?= $value->idpersonnel; ?>" class="btn btn-primary">OUI</a>
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
            <div class="card-footer small text-muted"> </div>
          </div>
	</div>

<script>
    $('#date_sortie').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd"
    });
</script>

<script>
 
 	$('#contractuelsTable').DataTable();
</script>
