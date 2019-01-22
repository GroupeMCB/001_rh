<?php  
$title_for_layout = 'GESTION DES CERTIFICATS ET SANCTIONS';
$title_for_page_menu = 'GESTION DES CERTIFICATS ET SANCTIONS';
$current_menu = 'Liste des sanctions';
$controller = $this->request->controller;
$button_option = ''.' '.
'<a class="btn btn-default btn-xs" data-toggle="modal" data-target="#planningconge"><i class="fa fa-download"></i> Exporter</a>';
echo  $this->Session->flash();

?> 

 <table width="100%" border="0" class="table table-striped table-bordered table-hover table-condensed" id="example1">
     <thead>
        <tr>
          <th width="25%" class="text-left">Nom et Prénom</th>
          <th width="15%" class="text-left">Type Sanction</th>
          <th width="25%" class="text-left">Motif</th>
          <th width="10%" class="text-left">Date de début</th>
          <th width="10%" class="text-left">Date de fin</th>
          <th width="5%"></th>
          <th width="5%"></th>
        </tr>
  </thead>
 <tbody>
 <?php  

 foreach ($listeAllSanction as $key => $valeur) {
   ?>
        <tr>
          <td> <?php echo $valeur->nom.' '.$valeur->prenom ?></td>
          <td><?php echo $valeur->libelle_sanction ?></td>
          <td><?php echo $valeur->motif_sanction ?></td>
          <td><?php echo date("d-m-Y",strtotime($valeur->date_debut)) ?></td>
          <td><?php echo date("d-m-Y",strtotime($valeur->date_fin)) ?></td>
          <td><a  class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#edit<?php echo $valeur->idpersonnel_sanction ?>">Modifier</a></td>
    <td><a href="#" data-toggle="modal" data-target="#sup<?php echo $valeur->idpersonnel_sanction ?>" >
      <span class="btn btn-danger btn-xs" ><span class="fa fa-trash-o"></span></span>
  </a></td>
        </tr>

	       <!-- suppression d'un certificat -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="sup<?php echo $valeur->idpersonnel_sanction ?>" >
          <div class="modal-dialog  ">
               <div class="modal-content">
                    <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h5 class="modal-title" id="myModalLabel">SUPPRESSION DE LA SANCTION DE <?php echo $valeur->nom.' '.$valeur->prenom;  ?></h5>
                     </div>
                                  <form method="post" action="" name="form" id="form">
                                      <div class="modal-body">

                                        <input type="hidden" value="1" name="etat">
                                        <input type="hidden" value="<?php echo $valeur->idpersonnel_sanction  ?>" name="idpersonnel_sanction">
                                        <h4> Voulez vous vraiment supprimer la sanction pour cet agent? </h4>
                                     
                                      </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                                               <button type="submit" class="btn btn-primary" name="supprimer" value="supprimer">Supprimer</button>
                                          </div>   
                                  </form>
                </div>
          </div>   
     </div>
      <!-- fin suppression -->






      <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="edit<?php echo $valeur->idpersonnel_sanction  ?>" >
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h5 class="modal-title" id="myModalLabel">MODIFICATION DE LA SANCTION DE <?php echo $valeur->nom.' '.$valeur->prenom;  ?></h5>
                                        </div>
                                          <form method="post" action="" name="form" id="form">
   <input type="hidden" value="<?php echo $valeur->idpersonnel_sanction  ?>" name="idpersonnel_sanction">
                                        <div class="modal-body">
												
                                        	 <div class="form-group">
                                           		 <label>Choisir le type de sanction</label>
	                                            <select class="form-control" name="type_sanction_id" >
                                                <?php foreach ($listesanction as $key => $v): ?>
	                                             
 		     	<option value="<?php echo $v->idtype_sanction ?>" <?php if($v->idtype_sanction === $valeur->type_sanction_id) echo 'selected = "selected" '; ?> >
 										<?php echo $v->libelle_sanction; ?>
 									</option>
                                                  
                                                <?php endforeach ?>
	                                            </select>
                                        	</div>

                                             <div class="form-group" >
                                              <label>Date de début</label>
                                                <input class="form-control" id="deb<?php echo $valeur->idpersonnel_sanction ?>" name="date_debut" value="<?php echo $valeur->date_debut ?>">
                                            </div>

                                              <div class="form-group" >
                                              <label>Date de fin</label>
                                                <input class="form-control" id="deb<?php echo $valeur->idpersonnel_sanction ?>" name="date_fin" value="<?php echo $valeur->date_fin ?>">
                                            </div>

                                            
                                            <div class="form-group" >
                                              <label>Motif</label>
                                                <textarea class="form-control" name="motif_sanction"><?php echo $valeur->motif_sanction ?></textarea>
                                            </div>

         							  </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">Modifier</button>
                                        </div>
                                    <!-- /.modal-content -->
                                   </div>
                                </div>
                            </div>
                              </form>




<script>   
   $('#deb<?php echo $valeur->idpersonnel_sanction  ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });  
</script>
 <?php } ?>
</tbody>
</table>


 <form method="post" action="" name="form" id="form">
      <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="addcertificat" >
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Nouvelle Sanction</h4>
                                        </div>
                                        
                                        <div class="modal-body">
                                           	<div class="form-group">
                                           		 <label>Choisir le poste occupé</label>
	                                            <select class="form-control" id="listeagent" onchange="Changeok()" >
                                                <option></option>
                                                <?php foreach ($titre as $key => $val): ?>
	                                                <option value="<?php echo $val->idtitre ?>"><?php echo $val->nom; ?></option>
                                                  
                                                <?php endforeach ?>
	                                            </select>
                                        	</div>

                                        	<div class="form-group" id="results">
                                        		 <label>Choisir un agent</label>
	                                            <select class="form-control" name="personnel_id">
	                                                <option>1</option>
	                                            </select>

                                        	</div>

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
                                                <input class="form-control" id="deb" name="date_sanction">
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
 function Changeok(){  
    alert("deco");
    $('#results').html('');  
   // on vide les resultats
   // $('#ajax-loader').remove(); // on retire le loader
 
      // on envoie la valeur recherch� en GET au fichier de traitement
      $.ajax({
    type : 'POST', // envoi des donn�es en GET ou POST
  url : '<?php echo Helper::ajaxloader() ?>', // url du fichier de traitement
  data : 'q='+document.getElementById('listeagent').value , // donn�es � envoyer en  GET ou POST
  beforeSend : function() { // traitements JS � faire AVANT l'envoi
    //$field.after('<img src="images/loading.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
  },
  success : function(data){ // traitements JS � faire APRES le retour d'ajax-search.php
   // $('#ajax-loader').remove(); // on enleve le loader
    $('#results').html(data); // affichage des r�sultats dans le bloc
  }
      });
  }    
</script>

 <script>   
   $('#deb').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });  
    
</script>

<script>
    $(document).ready(function() {
        $('#example1').DataTable({
    
                responsive: true,
               "oLanguage": {
           "oPaginate": {
                    "sPrevious": "Pr&eacute;c&eacute;dent",
               "sNext": "Suivant"
                   },
           "sSearch": "Rechercher une sanction",
           "sEmptyTable": "Aucune sanction ajout&eacute;e",
           "sInfo": "Nombre Total de sanction: _TOTAL_ ",
           "sInfoEmpty": "Aucune sanction ajout&eacute;e",
           "sLengthMenu": " _MENU_ sanction",
                "sZeroRecords": "Aucune sanction ne correspond &agrave; cette recherche",
             "sInfoFiltered": " - (Filtrer de _MAX_ sanction)"
               }
        });
    });   
    </script>


   
