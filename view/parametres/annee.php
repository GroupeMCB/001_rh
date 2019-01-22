<?php 

	$title_for_layout = 'Parametres';
	$title_for_page_menu = 'Parametres';
	$current_menu = 'Gestion des années';

	$button_option = '<a class="btn btn-primary btn-xs" href="" data-toggle="modal" data-target="#add" ><i class="fa fa-plus"></i> Nouvelle exercice</a>'.' '.
'<a class="btn btn-default btn-xs" data-toggle="modal" data-target="#"><i class="fa fa-download"></i> Exporter</a>';

echo  $this->Session->flash();
?>
 
	<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="contractuelsTable">
	 		<thead>
	 		<tr>
                <th class="text-left">Libelle</th>
              	<th class="text-left">Date ouverture</th>
        		<th class="text-left">Date de début</th>
                <th class="text-left">Date de fin</th>
        		<th class="text-left">Date de clôture</th>
        		<th class="text-left">Etat</th>
        		<th class="text-left"></th>
        	</tr>
        	</thead>
        	<?php $i = 1; ?>
			
			<tbody>
			<?php foreach ($liste_annee as $value): ?>
		 		<tr>

		 			<td><?php echo  $value->libelle_annee ?></td>
                    <td><?php if($value->date_ouverture == "0000-00-00") echo "";else echo  date("d-m-Y", strtotime($value->date_ouverture)); ?></td>
		 			<td><?php echo  date("d-m-Y", strtotime($value->date_debut)); ?></td>
                    <td><?php echo  date("d-m-Y", strtotime($value->date_fin)); ?></td>
		 			<td><?php if($value->date_cloture == "0000-00-00") echo "";else echo  date("d-m-Y", strtotime($value->date_cloture)); ?></td>
		 			<td>
                            <?php if($value->etat == 0) { ?>
                    <a href="#"  data-toggle="modal" data-target="#active<?php echo $value->idannee ?>"  >
                      <span class="btn btn-warning  btn-sm" ><i class="fa fa-dot-circle-o"></i> Non active</span>
                  </a>
                  <?php } ?>
                  <?php if($value->etat == 1) { ?>
                    <a href="#"  data-toggle="modal" data-target="#clot<?php echo $value->idannee ?>"  >
                      <span class="btn btn-success  btn-sm" ><i class="fa fa-unlock"></i> Ouverte</span>
                  </a>
                  <?php } ?>
                   <?php if($value->etat == 2) { ?>
                    <a href="#" >
                      <span class="btn btn-danger  btn-sm" ><i class="fa fa-lock"></i> clôturée</span>
                  </a>         
                  <?php } ?>

                   <!-- La fenetre modal d'activation-->
                    <form action="" method="post">
                    <input type="hidden" name="idannee" value="<?php echo $value->idannee; ?>">
                    <input type="hidden" name="etat" value="1">
                        <div class="modal fade" id="active<?php echo $value->idannee ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4><i class="fa fa-unlock"></i> Ouvrir l'exercice</h4>
                                </div>
                                <div class="modal-body">        
                                    <div class="form-group">
                                          <h4>  Voulez vous vraiment ouvrir l'exercice : <strong><?php echo $value->libelle_annee ?> ?</strong><br> Aucun exercice non clôturé ne doit être en cours. </h4> 
                                </div>
                                <div class="modal-footer">
                                  <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
                                 <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Valider</button>  
                                </div>
                              </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog  -->
                        </div> <!-- /.modal-fade -->
                        </form>

                       
  </td>
		 			<td></td>
		 		</tr>
                  <!-- La fenetre modal d'activation-->
                   
                        <div class="modal fade" id="clot<?php echo $value->idannee ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4><i class="fa fa-lock"></i> Clôturer l'exercice</h4>
                                </div>
                    <form action="" method="post">
                     <input type="hidden" name="idannee" value="<?php echo $value->idannee; ?>">
                    <input type="hidden" name="etat" value="2">
                                <div class="modal-body">        
                                    <div class="form-group">
                                <h4>  Voulez vous vraiment clôturer l'exercice : <strong><?php echo $value->libelle_annee ?> ?</strong> </h4> 
                                </div>
                                <div class="modal-footer">
                                  <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
                                 <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Valider</button>  
                                </div>
                              </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog  -->
                        </div> <!-- /.modal-fade -->
                        </form>
		 	<?php endforeach; ?>
		 		<script>
    $('#date_avance<?php //echo $value->idpersonnel  ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

      $('#date_retenue<?php //echo $value->idpersonnel  ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

   
</script>
		 		</tbody>
		 		</table> 
		 		  
		 			<!-- La fenetre modal d'ajout d'avance sur salaire -->
 					<form action="" method="post">
	                  	<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-sm">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-edit"></i>Nouvelle exercice</h4>
		                        </div>
		                        <div class="modal-body">	 	
		                         	<div class="form-group">
                                      <label>Libelle </label>
                       <input class="form-control"  name="libelle_annee" required="" >
                                     </div>
                                     <div class="form-group">
                                      <label>Date de début</label>
                                        <input class="form-control"  name="date_debut" id="date_debut"   required="" >
                                     </div>

                                     <div class="form-group">
                                      <label>Date de fin</label>
                                        <input class="form-control"  name="date_fin" id="date_fin"  required="" >
                                     </div> 
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
		                         <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Enregistrer</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>



	<script>
    $('#date_debut').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

      $('#date_fin').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

   
</script>

<script>
	$(document).ready(function() {

		$('#contractuelsTable').DataTable({
		  "ordering": false,
		 "sorting" : false,
		 "searching": true,
		 "bfilter":false,
		 "bLengthChange": false,
            "oLanguage": {
           		"oPaginate": {
            		"sPrevious": "Pr&eacute;c&eacute;dent",
            		"sNext": "Suivant"
            	},
           		"sSearch": "Rechercher : ",
           		"sEmptyTable": "Aucune periode ajout&eacute;e",
           		"sInfo": "Nombre total de periode : _TOTAL_ ",
           		"sInfoEmpty": "Aucun contractuel ajout&eacute;e",
           		"sLengthMenu": " _MENU_  periode",
            	"sZeroRecords": "Aucun periode ne correspond &agrave; cette recherche",
            	"sInfoFiltered": " - (Filtrer de _MAX_ periode)"
            }
		});
	});
</script>