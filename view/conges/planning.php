<?php  
$title_for_layout = 'GESTION DES CONGES';
$title_for_page_menu = 'GESTION DES CONGES';
$current_menu = 'Planning des Congés';
$controller = $this->request->controller;
$button_option = '<a class="btn btn-primary btn-xs" data-toggle="modal" data-target="><i class="fa fa-calendar"></i> Passer à la vue calendrier</a>'.' '.
'<a class="btn btn-default btn-xs" data-toggle="modal" data-target="#planningconge"><i class="fa fa-download"></i> Exporter</a>';
?> 
 
 
 <table width="100%" border="0" class="table table-striped table-bordered table-hover table-condensed" id="example1">
     <thead>
        <tr>
          <th width="30%" class="text-center">Nom et Prénom <br><input type="text" class="form-control input-sm" placeholder="" data-index="1" size="20" /></th>
          <th width="15%" class="text-center">Date de début <br><input type="text" class="form-control input-sm" placeholder="" data-index="2" size="20" /></th>
          <th width="15%" class="text-center">Date de fin<br><input type="text" class="form-control input-sm" placeholder="" data-index="3" size="20" /></th>
          <th width="17%" class="text-center">Date de la demande<br><input type="text" class="form-control input-sm" placeholder="" data-index="4" size="20" /></th>
          <th width="15%" class="text-center" >Nombre de jour<br><input type="text" class="form-control input-sm" placeholder="" data-index="5" size="20" /></th>
          <th width="7%" class="text-center">Etat</th>
          <th width="7%"></th>
          <th width="7%"></th>
        </tr>
  </thead>
  <tbody>
  <?php foreach ($planning as $key => $value) {
  if($value->etat == 0) $style = 'danger';else $style = 'success'; 
   ?>
  <tr>
    <td><?php echo $value->nom.' '.$value->prenom; ?></td>
    <td><?php echo date("d-m-Y",strtotime($value->date_debut)); ?></td>
    <td><?php echo date("d-m-Y",strtotime($value->date_fin)); ?></td>
    <td><?php echo date("d-m-Y",strtotime($value->date_demande)); ?></td>
    <td><?php echo $value->nombre_jour; ?></td>
    <td class="text-center">
      <?php if($value->etat == 0) { ?>
           <a href="#"  data-toggle="modal" data-target="#activeconge<?php echo $value->idplanning_conge ?>" >
      <span class="btn btn-danger btn-xs" ><span class="fa fa-times"></span> Non validé</span>
  </a>
      <?php } ?>
       <?php if($value->etat == 1) { ?>
           <a href="#"  data-toggle="modal" data-target="#activeconge<?php echo $value->idplanning_conge ?>" >
      <span class="btn btn-success btn-xs" ><span class="fa fa-check"></span> Validé</span>
  </a>
      <?php } ?>
      
    </td>
    <td><a  class="btn btn-primary btn-xs"  <?php if($value->etat == 0) { ?> data-toggle="modal" data-target="#edit<?php echo $value->idplanning_conge ?>" <?php } ?>><span class="fa fa-edit"></span> Modifier</a></td>
    <td><a href="#" <?php if($value->etat == 0) { ?> data-toggle="modal" data-target="#sup<?php echo $value->idplanning_conge ?>" <?php } ?>>
      <span class="btn btn-danger btn-xs" ><span class="fa fa-trash-o"></span></span>
  </a></td>
  </tr>



        <!-- Validation des congés -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="activeconge<?php echo $value->idplanning_conge ?>" >
          <div class="modal-dialog  ">
               <div class="modal-content">
                    <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title" id="myModalLabel">VALIDATION DES CONGES DE <?php echo $value->nom.' '.$value->prenom;  ?></h4>
                     </div>
                                  <form method="post" action="<?php Router::url('conges/planning'); ?>" name="form" id="form">
                                      <div class="modal-body">

                                        <input type="hidden" value="<?php echo $value->idpersonnel ?>" name="idpersonnel">
                                        <input type="hidden" value="1" name="etat">
                                        <input type="hidden" value="<?php echo $value->idplanning_conge  ?>" name="idplanning_conge">
                                        <h4> Voulez vous vraiment valider les congés pour cet agent? </h4>
                                     
                                      </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-times"></span> Fermer</button>
                                               <button type="submit" class="btn btn-primary"><span class="fa fa-check"></span> Valider</button>
                                          </div>   
                                  </form>
                </div>
          </div>   
     </div>
      <!-- fin Validé un congé -->

       <!-- suppression d'une plannification -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="sup<?php echo $value->idplanning_conge ?>" >
          <div class="modal-dialog  ">
               <div class="modal-content">
                    <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title" id="myModalLabel">SUPPRESSION DE LA PLANNIFICATION DE <?php echo $value->nom.' '.$value->prenom;  ?></h4>
                     </div>
                                  <form method="post" action="<?php Router::url('conges/planning'); ?>" name="form" id="form">
                                      <div class="modal-body">

                                        <input type="hidden" value="1" name="etat">
                                        <input type="hidden" value="<?php echo $value->idplanning_conge  ?>" name="idplanning_conge">
                                        <h4> Voulez vous vraiment supprimer cette plannification pour cet agent? </h4>
                                     
                                      </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-times"></span> Fermer</button>
                                               <button type="submit" class="btn btn-primary" name="supprimer" value="supprimer"><span class="fa fa-trash"></span> Supprimer</button>
                                          </div>   
                                  </form>
                </div>
          </div>   
     </div>
      <!-- fin suppression -->



      <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="edit<?php echo $value->idplanning_conge ?>" >
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">MODIFIER LA PLANNIFICATION DE <?php echo $value->nom.' '.$value->prenom;  ?></h4>
                                        </div>
                                        
                                        <form method="post" action="<?php Router::url('conges/planning'); ?>" name="form" id="form">
                                        <input type="hidden" value="<?php echo $value->idplanning_conge  ?>" name="idplanning_conge">

                                        <div class="modal-body">
                                   
                        
                                           <div class="form-group" >
                                              <label>Date de début</label>
                                                <input class="form-control" id="debut<?php echo $value->idplanning_conge ?>" name="date_debut" value="<?php echo $value->date_debut ?>">
                                            </div>

                                            <div class="form-group" >
                                              <label>Date de fin</label>
                                                <input class="form-control" id="fin<?php echo $value->idplanning_conge ?>" name="date_fin" value="<?php echo $value->date_fin ?>">
                                            </div>

                                            
                                            <div class="form-group" >
                                              <label>Nombre de jour</label>
                                                  <input class="form-control" name="nombre_jour" value="<?php echo $value->nombre_jour?>" >
                                            </div>

                                            <div class="form-group">
                                              <label>Date de la demande</label>
                                                  <input class="form-control" name="date_demande" value="<?php echo $value->date_demande ?>" id="date_demande<?php echo $value->idplanning_conge ?>">
                                            </div>

                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Annuler</button>
                                            <button type="submit" class="btn btn-danger"><span class="fa fa-edit"></span> Modifier</button>
                                        </div>
                                    <!-- /.modal-content -->
                                   </div>
                                </div>
                            </div>
                              </form>

<script>      
   $('#debut<?php echo $value->idplanning_conge ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
     
     $('#fin<?php echo $value->idplanning_conge ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
$('#date_demande<?php echo $value->idplanning_conge ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
      </script>


  <?php } ?>
  </tbody>
</table> 

                                        <form method="post" action="<?php Router::url('conges/planning'); ?>" name="form" id="form">
      <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="planningconge" >
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Ajouter un congé</h4>
                                        </div>
                                        
                                        <div class="modal-body">
                                           	<div class="form-group">
                                           		 <label>Choisir le poste occupé</label>
	                                            <select class="form-control" id="listeagent" onchange="" >
                                                <option></option>
                                                <?php foreach ($titre as $key => $value): ?>
	                                                <option value="<?php echo $value->idtitre ?>"><?php echo $value->nom; ?></option>
                                                  
                                                <?php endforeach ?>
	                                            </select>
                                        	</div>

                                        	<div class="form-group" id="results">
                                        		 <label>Choisir un agent</label>
	                                            <select class="form-control" name="personnel_id">
	                                                <option>1</option>
	                                            </select>

                                        	</div>
												
											                     <div class="form-group" >
                                            	<label>Période</label>
                                                <input class="form-control" id="debut" name="date_debut">
                                            </div>

                                            <div class="form-group" >
                                              <label>Période</label>
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

<?php  


// function nbJours($debut, $fin) {
//         //60 secondes X 60 minutes X 24 heures dans une journée
//         $nbSecondes= 60*60*24;
 
//         $debut_ts = strtotime($debut);
//         $fin_ts = strtotime($fin);
//         $diff = $fin_ts - $debut_ts;
//        return  round($diff);
//     } ?>

 <script>
    $(document).ready(function() {
     var table =   $('#example1').DataTable({
            "ordering": false,
     "sorting" : false,
     "searching": true,
     "bfilter":false,
     "bLengthChange": false,
                "responsive": true,
               "oLanguage": {
           "oPaginate": {
                    "sPrevious": "Pr&eacute;c&eacute;dent",
               "sNext": "Suivant"
                   },
           "sSearch": "Rechercher un conge",
           "sEmptyTable": "Aucun congé ajout&eacute;e",
           "sInfo": "Nombre Total de conge: _TOTAL_ ",
           "sInfoEmpty": "Aucun congé ajout&eacute;e",
           "sLengthMenu": " _MENU_ Congé",
                "sZeroRecords": "Aucun congé ne correspond &agrave; cette recherche",
             "sInfoFiltered": " - (Filtrer de _MAX_ Congé)"
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

       
<script> 
 function change(){  
 alert("deco");
    $('#results').html('');  
   // on vide les resultats
   // $('#ajax-loader').remove(); // on retire le loader
 
      // on envoie la valeur recherch� en GET au fichier de traitement
      $.ajax({
    type : 'POST', // envoi des donn�es en GET ou POST
  url : '', // url du fichier de traitement
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

 <!-- Page-Level Demo Scripts - Tables - Use for reference -->
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