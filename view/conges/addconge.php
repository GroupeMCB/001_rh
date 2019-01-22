<?php  
$title_for_layout = 'GESTION DES CONGES';
$title_for_page_menu = 'GESTION DES CONGES';
$current_menu = 'Ajouté un congé';
$controller = $this->request->controller;
 
foreach ($unagent as $key => $value) {
	 $nom = $value->nom.' '.$value->prenom;
}
echo  $this->Session->flash();
?> 

                  <form method="post" action="" name="form" id="form">
                  <input type= "hidden" value="<?php echo $value->idpersonnel; ?>" name="personnel_id">
  					    <div class=""  >
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Ajouter un congé pour <?php echo $nom; ?></h4>
                                        </div>
                                        
                                        <div class="modal-body">
                                 
										  <div class="form-group" >
                                            	<label>Date de début</label>
                                                <input class="form-control" id="debut" name="date_debut">
                                            </div>

                                            <div class="form-group" >
                                              <label>Date de fin</label>
                                                <input class="form-control" id="fin" name="date_fin">
                                            </div>

                                            
                                            <div class="form-group" >
                                            	<label>Nombre de jour</label>
                                          	  		<input class="form-control" name="nombre_jour" value="" >
                                            </div>

                                            <div class="form-group">
                                            	<label>Date de la demande</label>
                                          	  		<input class="form-control" name="date_demande" id="date_demande">
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
       
   $('#debut').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
     
     $('#fin').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });

      $('#date_demande').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });

      </script>