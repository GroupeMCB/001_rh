<?php
  $title_for_layout = 'Personnel';
  $title_for_page_menu = 'Personnel';
  $current_menu = 'contrat';
?>

<div class="col-md-12">

	<div class="col-md-5">
	
		<form action="<?= BASE_URL; ?>/contrats/addContrat" class="form-horizontal" role="form" method="post">
		  	<div class="form-group">
			    <label for="textareaDescription" class="col-md-4 control-label">Type de contrat : </label>

		      	<div class="input-control text col-md-8">

		      		<input type="hidden" value="<?= $employe->idpersonnel; ?>" name="personnel_id">
		      		<input type="hidden" value="1" name="etat">
		      		<select name="type_contrat_id" id="" class="form-control input-sm">
		              <option value="">Selectionnez le type de contrat</option>
		              <?php foreach ($contrats as $contrat): ?>
		              	
		                <option value="<?= $contrat->idtype_contrat; ?>"><?= $contrat->nom; ?></option>
		              <?php endforeach; ?>
		            </select>
		      	</div>

		  	</div>
			
			<div class="form-group">
		    	<label for="" class="control-label col-md-4">Date de début et de fin : </label>

		      	<div class="input-control text col-md-4">
		          	<input type="text" class="form-control input-sm" id="date_entree_contrat" name="date_entree_contrat" placeholder="Date de début" autocomplete="off"/>
		      	</div>

		       <div class="input-control text col-md-4">
		          	<input type="text" class="form-control input-sm" id="date_fin_contrat" name="date_fin_contrat" placeholder="Date de fin" autocomplete="off"/>
		      </div>

		  	</div>

		  	<hr>
	      	<div class="form-group">
	    	    <div class="col-sm-offset-2 col-sm-6">
	    	      <button type="submit" id="validation" class="btn btn-success">Enregistrer</button>
	    	      <a href="#" class="btn btn-danger">Annuler</a>
	    	    </div>
	      	</div>

		</form>
	</div>

	<div class="col-md-7">
		<h3>Historique contrat : <?= $employe->nom.' '.$employe->prenom; ?></h3>
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Type de contrat</th>
					<th>Date d'entrée</th>
					<th>Date de fin du contrat</th>
					<th>...</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($personnel_contrats as $personnel_contrat): ?>
					<tr>
						<td></td>
						<td><?= $personnel_contrat->libContrat; ?></td>
						<td><?= $personnel_contrat->date_entree_contrat; ?></td>
						<td><?= $personnel_contrat->date_fin_contrat; ?></td>
						<td><?= ($personnel_contrat->etat == 1) ? '<span class="label label-success">Actuel</span>' : '' ; ?></td>
						<td><?= ($personnel_contrat->etat == 1) ? '<a href="" data-toggle="modal" data-target="#editsolde'.$personnel_contrat->idpersonnel_contrat.'" class="btn btn-primary btn-xs">Modifier</a>' : '' ; ?></td>
					</tr>
					<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="editsolde<?php echo  $personnel_contrat->idpersonnel_contrat ?>" >
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h5 class="modal-title" id="myModalLabel">MODIFIER LE CONTRAT </h5>
                                                 
                                        </div>
                                        <form method="post">
                                        <div class="modal-body">
                                           <input type="hidden" value="<?php echo $personnel_contrat->idpersonnel_contrat ?>" name="idpersonnel_contrat">
                                            
                                            <div class="form-group" >
                                              <label>Date de début</label>
                                                <input class="form-control" id="date_entree_contrat<?php echo $personnel_contrat->idpersonnel_contrat ?>"  name="date_entree_contrat" value="<?php echo $personnel_contrat->date_entree_contrat ?>">
                                            </div>

                                             <div class="form-group" >
                                              <label>Date de fin</label>
                                                <input class="form-control" id="date_fin_contrat<?php echo $personnel_contrat->idpersonnel_contrat ?>"  name="date_fin_contrat" value="<?php echo $personnel_contrat->date_fin_contrat ?>">
                                            </div>
                                              
                                      </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary"  >Modifier</button>
                                        </div></form>
                                    <!-- /.modal-content -->
                                   </div>
                                </div>
                            </div>

                            <script>
    $('#date_entree_contrat<?php echo $personnel_contrat->idpersonnel_contrat ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

    $('#date_fin_contrat<?php echo $personnel_contrat->idpersonnel_contrat ?>').datepicker({
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
    $('#date_entree_contrat').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

    $('#date_fin_contrat').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

 </script>