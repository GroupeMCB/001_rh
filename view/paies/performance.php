<?php 

	$title_for_layout = 'Paie - Personnels';
	$title_for_page_menu = 'Paie';
	$current_menu = 'validation des indicateurs des agents';
  
echo $this->Session->Flash();
// debug($listeagent);
 if($validation ==0) {
	$disable ='data-toggle="modal" data-target="#valideheure"';
	$icon = "unlock";
	$color = "danger";
	$lib = "Valider les indicateurs";
	 } 
	else {
		$disable = "disable";
		$icon = "lock";
		$color = "success";
		$lib  = "Indicateurs déjà validés!! Aucune modification n'est plus possible";
	}
 $button_option ='<a class="btn btn-'.$color.' btn-sm" '.$disable.' ><i class="fa fa-'.$icon.'"></i> '.$lib.'</a>' ;
?>


	<div class="col-md-12">
		 


	 	<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="contractuelsTable">
	 		<thead>
	 		<tr>
        	 
              	<th class="text-center" width="30%">Nom<br><input type="text" class="form-control input-sm" placeholder="" data-index="1" size="20" /> </th>
        		<th class="text-center" width="25%">Prénom<br><input type="text" class="form-controls input-sm" placeholder="" data-index="2" size="20" /> </th>
        		<th class="text-center" width="25%">Campagne<br><input type="text" class="form-controls input-sm" placeholder="" data-index="2" size="20" /> </th>
        		<th class="text-center" width="5%">Performance </th>
        		<th class="text-center" width="5%">CSSI/NPS </th>
        		<th class="text-center" width="5%">Mysterie Calls </th>
        		<th class="text-center" width="5%">Quizz </th>
        		<th class="text-center" width="5%">Taux écoute QUAL  </th>
        		<th class="text-center" width="5%">TMT</th>
        		<th class="text-center" width="5%">Appels traités</th>
        		<th class="text-center"  >heure de présence</th>
        	</tr>
        	</thead>
        	<?php $i = 1; ?>
			
			<tbody>
			<?php foreach ($listeagent as $k => $value): ?>
		 		<tr>	 		 
		 			<td><?php echo $value->nom; ?></td>
		 			<td><?php echo $value->prenom ;?></td>
		 			<td><?php echo $value->campagne ?></td>
		 			<td><?php echo $value->performance ;?></td>
		 			<td><?php echo $value->cssi ;?></td>		 
		 			<td><?php echo $value->myster_call ;?></td>		 
		 			<td><?php echo $value->quizz; ?></td>
		 			<td><?php echo $value->taux_ecoute; ?></td> 		 		
		 			<td><?php echo $value->tmt; ?></td> 		 		
		 			<td><?php echo $value->appels_traite; ?></td> 		 		
		 			<td><?php echo $value->heure_presence; ?></td> 		 		
		 		</tr>
			<?php endforeach ?>
			</tbody>

	 	</table>
	</div>

 	<!-- La fenetre modal d'ajout d'avance sur salaire -->
 					<form action="" method="post">
	                  	<div class="modal fade" id="valideheure" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-edit"></i> Validation des performances des agents</h4>
		                        </div>
		                        <div class="modal-body">
 				 	  <input type="hidden" value="0" name="validation_heure">

 				 	 			 La validation en block des performances des agents est irreverssible. Les modifications futures seront faites de façon individuelles.
 				 	 			 Voulez-vous vraiment valider ?
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
		                         <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>
       

<script>
	$(document).ready(function() {
var editor;
var table =		$('#contractuelsTable').DataTable({
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
           		"sEmptyTable": "Aucun agents ajout&eacute;e",
           		"sInfo": "Nombre total de agents : _TOTAL_ ",
           		"sInfoEmpty": "Aucun agents ajout&eacute;e",
           		"sLengthMenu": " _MENU_ agents",
            	"sZeroRecords": "Aucun agents ne correspond &agrave; cette recherche",
            	"sInfoFiltered": " - (Filtrer de _MAX_ agents)"
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