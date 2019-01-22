<?php 

	$title_for_layout = 'Paie - Etat';
	$title_for_page_menu = 'ETAT';
	$current_menu = 'Etat ';
 
echo $this->Session->Flash();
 
?>

<div class="col-md-6">
	<form method="post">
	  <fieldset>
	    <legend>Liste des différents états</legend>
	     	
	    	<div class="form-group">
	    	<label>Choisir la période de paie</label>
	    		<select class="form-control" name="paie_id">
	    		<?php foreach ($listepaie as $key => $value) { ?>
	    				<option value="<?php echo $value->idpaie ?>"  <?php if($value->etat == 1) echo 'selected'; ?>><?php echo $value->libelle_paie.' / '. $value->libelle_annee ?></option>
	    		<?php } ?>
	    		</select>
	    	</div>

	    	<div class="form-group">
	    	<label>Choisir le type de rapport</label>
	    		<select class="form-control" name="type">
	    				<option value="1">Etat personnel administratif direct</option>
	    				<option value="2">Etat personnel administratif indirect</option>
	    				<option value="3">Etat personnel  emission</option>
	    				<option value="4">Etat personnel  réception</option>
	    				<option value="5">Etat personnel  entretion</option>
	    				<option value="6">Etat personnel  ABFPA</option>
	    		</select>
	    	</div>
	    	<div class="modal-footer">
              <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
             <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button>  
            </div>
	  </fieldset>
	</form>
</div>

<div class="col-md-6" style="margin-top: 100px">
<?php if(isset($lien) && ($lien != NULL) ) {?> <a href="<?php echo $lien ?>" class="btn btn-primary btn-block"><i class="fa fa-download"></i> Télécharger le fichier <?php echo $libelle; ?> </a> <?php } ?>
</div>