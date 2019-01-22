<?php 
	$title_for_layout = 'Personnel - Présence';
	$title_for_page_menu = 'Personnel';
	$current_menu = 'Présence';
?>
<div class="col-md-12">
	<form action="<?php echo BASE_URL; ?>/personnels/addabsence" class="form-horizontal" role="form" method="post">
	  <div class="modal-content">
	    <div class="modal-header">
	      <h3>Signaler une absence</h3>
	    </div>
	    <div class="modal-body">
	    	<p>Signaler une absence pour : <b><?= $employe->nom.' '.$employe->prenom; ?></b></p>
	    	<div class="form-group">

	    		<input type="hidden" name="personnel_id" value="<?= $employe->idpersonnel; ?>">

	    		<input type="hidden" name="insertdate" value="<?= date('Y-m-d'); ?>">
		      	<label for="" class="control-label col-md-4">Date de début et fin : </label>

		        <div class="input-control text col-md-4">
		            <input type="text" class="form-control input-sm" id="date_debut" name="date_debut" placeholder="Date de début" autocomplete="off"/>
		        </div>

		         <div class="input-control text col-md-4">
		            <input type="text" class="form-control input-sm" id="date_fin" name="date_fin" placeholder="Date de fin" autocomplete="off"/>
		        </div>

	    	</div>

	    	<div class="form-group">
	    		<label for="" class="control-label col-md-4">Nbre d'heures : </label>
	    		<div class="input-control text col-md-8">
	    			<input type="text" class="form-control input-sm" name="nbre_heures">
	    		</div>
	    	</div>

			<div class="form-group">
	    		<label for="" class="control-label col-md-4">Motitif : </label>
	    		<div class="input-control text col-md-8">
	    			<select name="type_absence_id" class="form-control input-sm">
	    				<option value="1">Absence non justifié</option>
	    				<option value="2">Absence justifié</option>
	    			</select>
	    		</div>
	    	</div>

	    </div>
	    <div class="modal-footer">
	      <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Fermer</a>
	      <button type="submit" class="btn btn-primary">Enregistrer</button>
	    </div>
	</form>
</div>

<script>
   $('#date_fin').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
</script>

<script>
   $('#date_debut').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
</script>