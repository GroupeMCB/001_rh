<?php  
$title_for_layout = 'GESTION DES CERTIFICATS ET SANCTIONS';
$title_for_page_menu = 'GESTION DES CERTIFICATS ET SANCTIONS';
$current_menu = 'Liste des certificats';
$controller = $this->request->controller;
$button_option = ''.' '.
'<a class="btn btn-default btn-xs" data-toggle="modal" data-target="#planningconge"><i class="fa fa-download"></i> Exporter</a>';
echo  $this->Session->flash();
?> 

 <table width="100%" border="0" class="table table-striped table-bordered table-hover table-condensed" id="example1">
     <thead>
        <tr>
          <th width="25%" class="text-center">Nom et Prénom <br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /></th>
          <th width="10%" class="text-center">Numero<br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /> </th>
          <th width="15%" class="text-center">Date de consult.<br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /></th>
          <th width="10%" class="text-center">Date de début<br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /></th>
          <th width="10%" class="text-center">Date de fin<br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /></th>
          <th width="10%" class="text-center">Nbre Jour<br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /></th>
          <th width="15%" class="text-center" >Type<br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /></th>
          <th width="10%"></th>
          <th width="10%"></th>
        </tr>
  </thead>
 <tbody>
 <?php 

 foreach ($listeAllcertificat as $key => $value) {
   ?>
        <tr>
          <td> <?php echo $value->nom.' '.$value->prenom ?></td>
          <td><?php echo $value->numero ?></td>
          <td><?php echo date("d-m-Y",strtotime($value->date_consultation)) ?></td>
          <td><?php echo date("d-m-Y",strtotime($value->date_debut)) ?></td>
          <td><?php echo date("d-m-Y",strtotime($value->date_fin)) ?></td>
          <td><?php echo $value->total_jour_repos ?></td>
          <td><?php echo $value->libelle_certificat ?></td>
          <td><a  class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#edit<?php echo $value->idcertificat ?>">Modifier</a></td>
    <td><a href="#" data-toggle="modal" data-target="#sup<?php echo $value->idcertificat ?>" >
      <span class="btn btn-danger btn-xs" ><span class="fa fa-trash-o"></span> Supprimer</span>
  </a></td>
        </tr>

	       <!-- suppression d'un certificat -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="sup<?php echo $value->idcertificat ?>" >
          <div class="modal-dialog  ">
               <div class="modal-content">
                    <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h5 class="modal-title" id="myModalLabel">SUPPRESSION DU CERTIFICAT N° <?php echo $value->numero ?> DE <?php echo $value->nom.' '.$value->prenom;  ?></h5>
                     </div>
                                  <form method="post" action="<?php Router::url('conges/planning'); ?>" name="form" id="form">
                                      <div class="modal-body">

                                        <input type="hidden" value="1" name="etat">
                                        <input type="hidden" value="<?php echo $value->idcertificat  ?>" name="idcertificat">
                                        <h4> Voulez vous vraiment supprimer ce certificat pour cet agent? </h4>
                                     
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






      <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="edit<?php echo $value->idcertificat  ?>" >
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h5 class="modal-title" id="myModalLabel">MODIFICATION DU CERTIFICAT N° <?php echo $value->numero ?> DE <?php echo $value->nom.' '.$value->prenom;  ?></h5>
                                        </div>
                                        
                                        <div class="modal-body">
													<form method="post" action="" name="form" id="form">
 	 <input type="hidden" value="<?php echo $value->idcertificat  ?>" name="idcertificat">
                                        	 <div class="form-group">
                                           		 <label>Choisir le type de certificat</label>
	                                            <select class="form-control" name="type_certificat_id" >
                                                <?php foreach ($listecertificat as $key => $v): ?>
	                                             
 			<option value="<?php echo $v->idtype_certificat ?>" <?php if($v->idtype_certificat === $value->type_certificat_id) echo 'selected = "selected" '; ?> >
 										<?php echo $v->libelle_certificat; ?>
 									</option>
                                                  
                                                <?php endforeach ?>
	                                            </select>
                                        	</div>

											    <div class="form-group" >
                                            	<label>Numero</label>
                                                <input class="form-control"  name="numero" value="<?php echo $value->numero ?>" >
                                            </div>

                                            <div class="form-group" >
                                              <label>Date de consultation</label>
                                                <input class="form-control" id="consultation<?php echo $value->idcertificat ?>" name="date_consultation" value="<?php echo $value->date_consultation ?>">
                                            </div>

                                             <div class="form-group" >
                                              <label>Date de début</label>
                                                <input class="form-control" id="deb<?php echo $value->idcertificat ?>" name="date_debut" value="<?php echo $value->date_debut ?>">
                                            </div>

                                             <div class="form-group" >
                                              <label>Date de fin</label>
                                                <input class="form-control" id="fin<?php echo $value->idcertificat ?>" name="date_fin" value="<?php echo $value->date_fin ?>">
                                            </div>

                                            
                                            <div class="form-group" >
                                            	<label>Total jour de repos</label>
                                          	  		<input class="form-control" name="total_jour_repos"  value="<?php echo $value->total_jour_repos ?>" >
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
   $('#deb<?php echo $value->idcertificat  ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });  
     $('#fin<?php echo $value->idcertificat  ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
      $('#consultation<?php echo $value->idcertificat  ?>').datepicker({
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
                                            <h4 class="modal-title" id="myModalLabel">Ajouter un congé</h4>
                                        </div>
                                        
                                        <div class="modal-body">
                                           	<div class="form-group">
                                           		 <label>Choisir le poste occupé</label>
	                                            <select class="form-control" id="listeagent" onchange="" >
                                                <option></option>
                                                <?php foreach ($titre as $keys => $values): ?>
	                                                <option value="<?php echo $values->idtitre ?>"><?php echo $values->nom; ?></option>
                                                  
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
                                           		 <label>Choisir le type de certificat</label>
	                                            <select class="form-control" name="type_certificat_id" >
                                                <?php foreach ($listecertificat as $key => $value): ?>
	                                                <option value="<?php echo $value->idtype_certificat ?>"><?php echo $value->libelle_certificat; ?></option>
                                                  
                                                <?php endforeach ?>
	                                            </select>
                                        	</div>
												
											    <div class="form-group" >
                                            	<label>Numero</label>
                                                <input class="form-control"  name="numero">
                                            </div>

                                            <div class="form-group" >
                                              <label>Date de consultation</label>
                                                <input class="form-control" id="consultation" name="date_consultation">
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
                                            	<label>Total jour de repos</label>
                                          	  		<input class="form-control" name="total_jour_repos" value="" >
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
      $('#consultation').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
        });
</script>

<script>
    $(document).ready(function() {
 var table =       $('#example1').DataTable({
     "ordering": false,
     "sorting" : false,
     "searching": true,
     "bfilter":false,
     "bLengthChange": false,
                responsive: true,
               "oLanguage": {
           "oPaginate": {
                    "sPrevious": "Pr&eacute;c&eacute;dent",
               "sNext": "Suivant"
                   },
           "sSearch": "Rechercher un certificat",
           "sEmptyTable": "un certificat ajout&eacute;e",
           "sInfo": "Nombre Total de certificat: _TOTAL_ ",
           "sInfoEmpty": "Aucun certificat ajout&eacute;e",
           "sLengthMenu": " _MENU_ certificat",
                "sZeroRecords": "Aucun certificat ne correspond &agrave; cette recherche",
             "sInfoFiltered": " - (Filtrer de _MAX_ certificat)"
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