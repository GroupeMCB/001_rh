<?php  
$title_for_layout = 'GESTION DES CERTIFICATS ET SANCTIONS';
$title_for_page_menu = 'GESTION DES CERTIFICATS ET SANCTIONS';
$current_menu = 'Ajouté une sanction';
$controller = $this->request->controller;
foreach ($unagent as $key => $value) {
	 $nom = $value->nom.' '.$value->prenom;
}
echo  $this->Session->flash();

?> 
<form method="post" action="" name="form" id="form">
 	<input type= "hidden" value="<?php echo $value->idpersonnel; ?>" name="personnel_id">
    						  <div class=" "  >
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Nouvelle Sanction pour  <?php echo $nom; ?></h4>
                                        </div>
                                        
                                        <div class="modal-body">
                                            
                                        	<div class="form-group">
                                           		 <label>Choisir le type de sanction</label>
	                                            <select class="form-control" name="type_sanction_id" >
                                                <?php foreach ($listesanction as $key => $valeur): ?>
	                                                <option value="<?php echo $valeur->idtype_sanction ?>"><?php echo $valeur->libelle_sanction; ?></option>
                                                  
                                                <?php endforeach ?>
	                                            </select>
                                        	</div>
												 

                                             <div class="form-group" >
                                              <label>Date de début</label>
                                                <input class="form-control" id="deb" name="date_debut">
                                            </div>

                                            <div class="form-group" >
                                              <label>Date de fin</label>
                                                <input class="form-control" id="fin" name="date_fin">
                                            </div>
 
                                            
                                            <div class="form-group" >
                                            	<label>Motif</label>
                                          	  	<textarea class="form-control" name="motif_sanction"></textarea>
                                            </div>

                                             

         							  </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">Enrégistrer</button>
                                        </div>
                                    <!-- /.modal-content -->
                                   </div>
                                </div>
                            </div>
                              </form>
                               <script>
       
   $('#deb').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
     
     $('#fin').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
      </script>