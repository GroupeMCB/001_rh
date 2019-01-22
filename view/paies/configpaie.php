<?php 

	$title_for_layout = 'Paie - Personnels';
	$title_for_page_menu = 'Paie';
	$current_menu = 'Configuration de la paie';

	$button_option = '<a class="btn btn-primary btn-xs" href="" data-toggle="modal" data-target="#add" ><i class="fa fa-plus"></i> Nouvelle période de paie</a>'.' '.
'<a class="btn btn-default btn-xs" data-toggle="modal" data-target="#"><i class="fa fa-download"></i> Exporter</a>';

echo $this->Session->flash();
if(!isset($id)){ 
?>
<div class="col-md-offset-0 col-md-12">		
 
	<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="contractuelsTable">
	 		<thead>
	 		<tr>
              	<th class="text-left">Libelle</th>
        		<th class="text-left">Date de début</th>
        		<th class="text-left">Date de fin</th>
        		<th class="text-left">Trimestre</th>
        		<th class="text-left">Heure temps plein</th>
        		<th class="text-left">Année</th>
        		<th class="text-left">Etat</th>
        		<th class="text-left"></th>
        	</tr>
        	</thead>
        	<?php $i = 1; ?>
			
			<tbody>
			<?php foreach ($listpaie as $value): ?>
		 		<tr>
		 			<td><?php echo  $value->libelle_paie ?></td>
		 			<td><?php echo  date("d-m-Y", strtotime($value->date_debut)); ?></td>
		 			<td><?php echo  date("d-m-Y", strtotime($value->date_fin)); ?></td>
		 			<td><?php echo  $value->trimestre_id ?></td>
		 			<td><?php echo  $value->heure_tps_plein ?></td>
		 			<td><?php echo  $value->libelle_annee ?></td>
		 			<td>
		 				  <?php if($value->etat == 0) { ?>
                    <a href="#"  data-toggle="modal" data-target="#active<?php echo $value->idpaie ?>"    >
                      <span class="btn btn-warning   btn-sm" ><i class="fa fa-dot-circle-o"></i> Non active</span>
                  </a>
                  <?php } ?>
                  <?php if($value->etat == 1) { ?>
                    <a href="#"  data-toggle="modal" data-target="#clot<?php echo $value->idpaie ?>"  >
                      <span class="btn btn-success   btn-sm" ><i class="fa fa-unlock"></i> Ouverte</span>
                  </a>
                  <?php } ?>
                   <?php if($value->etat == 2) { ?>
                    <a href="#" >
                      <span class="btn btn-danger disabled btn-sm" ><i class="fa fa-lock"></i> clôturée</span>
                  </a>         
                  <?php } ?>

  <!-- La fenetre modal d'activation-->
                  
                        <div class="modal fade" id="active<?php echo $value->idpaie ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4><i class="fa fa-unlock"></i> Ouvrir le mois</h4>
                                </div>
                                  <form action="" method="post">
                    <input type="hidden" name="idpaie" value="<?php echo $value->idpaie; ?>">
                    <input type="hidden" name="etat" value="1">
                                <div class="modal-body">        
                                    <div class="form-group">
                                          <h4>  Voulez vous vraiment ouvrir le mois : <strong><?php echo $value->libelle_paie ?> ?</strong>   </h4> 
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
		 			<td>
		 				<?php if($value->etat == 2) { ?>
		 				<!-- Button action -->
						<div class="btn-group dropdown">
						  <button type="button" class="btn btn-primary btn-sm"><span class="fa fa-cog"></span> Actions</button>
						  <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <span class="caret"></span>
						    <span class="sr-only">Toggle Dropdown</span>
						  </button>
						  <ul class="dropdown-menu dropdown-menu-right">
						     <li>
						    	<a href="<?php echo BASE_URL ?>/paies/archives/<?php echo $this->encrypt($value->idpaie); ?>" ><span class="fa fa-archive"></span> Voir les éléments</a>
						    </li>
						   
						   
						  </ul>
						</div>
            <?php } ?>

		 			</td>
		 		</tr>


                          <div class="modal fade" id="clot<?php echo $value->idpaie ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4><i class="fa fa-lock"></i> Clôturer le mois</h4>
                                </div>
                    <form action="" method="post">
                     <input type="hidden" name="idpaie" value="<?php echo $value->idpaie; ?>">
                    <input type="hidden" name="etat" value="2">
                                <div class="modal-body">        
                                    <div class="form-group">
                                <h4>  Voulez vous vraiment clôturer le mois : <strong><?php echo $value->libelle_paie ?> ?</strong> <br>
                                <span class="color-red">NB: La clôture du mois en cours entraîne l'ouverture du mois suivant.</span>
                                 </h4> 
                                </div>
                                
                              </div><div class="modal-footer">
                                  <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
                                 <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Valider</button>  
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
		                          <h4><i class="fa fa-edit"></i>Nouvelle période de paie</h4>
		                        </div>
		                        <div class="modal-body">	 	
		                         	<div class="form-group">
                                      <label>Libelle </label>
                       <input class="form-control"  name="libelle_paie" required="" >
                                     </div>
                                     <div class="form-group">
                                     	<select name="trimestre_id" class="form-control" >
                                     		<option value="1">Trimstre 1</option>
                                     		<option value="2">Trimstre 2</option>
                                     		<option value="3">Trimstre 3</option>
                                     		<option value="4">Trimstre 4</option>
                                     	</select>
                                     </div>
                                     <div class="form-group">
                                      <label>Date de début</label>
                                        <input class="form-control"  name="date_debut" id="date_debut"  required="" >
                                     </div>

                                     <div class="form-group">
                                      <label>Date de fin</label>
                                        <input class="form-control"  name="date_fin" id="date_fin"  required="" >
                                     </div>
                                     <div class="form-group">
                                      <label>Heure temps plein</label>
                                        <input class="form-control"  name="heure_tps_plein"  required="" >
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

<?php } else { 
?>

  <div class="navbar-default sidebar" role="navigation" style="background: #e6e6e6;">
                <div class="sidebar-nav navbar-collapse">
                <div class="modal-content" class="col-md-4">
	    <div class="modal-body">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="index.html"><i class="fa fa-dashboard fa-fw"></i> Eléments de paie <span class="fa arrow"></span></a>
                              <ul class="nav nav-second-level">
                              <?php foreach ($liste_type_personnel as $key => $value): ?>	
							  <li><a href=""> <?php echo $value->libelle; ?></a> </li>
							  	<?php endforeach ?>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Fiches de paie<span class="fa arrow"></span></a>  
                            <!-- /.nav-second-level -->
                        </li>
                        
                    </ul></div></div>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
<div menuitemname="Active Products/Services" class="panel panel-default panel-accent-gold col-md-offset-3">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                             <div class="pull-right">
                                    <a href=" " class="btn btn-default bg-color-gold btn-xs">
                                        <i class="fa fa-plus"></i>  
                                    </a>
                                </div>
                                    <i class="fa fa-cube"></i>&nbsp;   
                        </h3>
    				</div>
                 <div class="list-group">
         		 </div>
            <div class="panel-footer">
                </div>
                </div>
<?php } ?>

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