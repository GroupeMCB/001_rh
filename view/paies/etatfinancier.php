<?php 

	$title_for_layout = 'Paie - Etat';
	$title_for_page_menu = 'ETAT';
	$current_menu = 'Etat financier';
 
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
	    				<option value="cumul">Cumul charges salariales</option>
	    				<option value="etat_banque">Etat Banque</option>
	    				<option value="recap">Récapitulatif net</option>
	    				<option value="cnss">Déclaration CNSS</option>
	    				<option value="det_cnss">Détail CNSS</option>
	    				<option value="ipts">Détail IPTS</option>  
	    				<option value="boa"> Fichier BOA</option>  
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