<?php
setlocale (LC_TIME, 'fr_FR');
	$title_for_layout = 'Personnel - Contractuels';
	$title_for_page_menu = 'Personnel';
	$current_menu = 'Contractuels';

	 
?>

	<div class="col-md-12">

	 	<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="contractuelsTable">
	 		<thead>
	 		<tr>
        		<th class="text-left" style="width: 1%;"></th>
            <th class="text-center">Nom <br><input type="text" class="form-controls input-sm" placeholder="" data-index="1" size="20" /> </th>
        		<th class="text-center">Prénom<br><input type="text" class="form-controls input-sm" placeholder="" data-index="2" /></th>
        		<th class="text-center">Date d'entrée<br><input type="text" class="form-controls input-sm" placeholder="" data-index="3" /></th>
        		<th class="text-center">Date de sortie<br><input type="text" class="form-controls input-sm" placeholder="" data-index="3" /></th>
        		<th class="text-center">Motif de sortie<br><input type="text" class="form-controls input-sm" placeholder="" data-index="3" /></th>
        		<th class="text-center">Département<br><input type="text" class="form-controls input-sm" placeholder="" data-index="4" /></th>
        		<th class="text-center">Poste Occupé<br><input type="text" class="form-controls input-sm" placeholder="" data-index="5" /></th>
        		<th class="text-center">Type de Contrat<br><input type="text" class="form-controls input-sm" placeholder="" data-index="5" /></th>
            
        	</tr>
        	</thead>
        	<?php $i = 1; ?>

			<tbody>
			<?php foreach ($contractuels as $value): ?>
		 		<tr>
		 			<td><?= $i++; ?></td>
		 			<td><?= $value->nom; ?></td>
		 			<td><?= $value->prenom ;?></td>
		 			<td><?= date('d F Y',strtotime($value->date_entree)) ;?></td>
		 			<td><?= date('d F Y',strtotime($value->date_sortie)) ;?></td>
		 			<td><?php
		 					foreach ($motifs_sortie as $motif)  {
		 						if ($motif->idmotif_sortie == $value->motif_sortie_id) {
		 							echo $motif->libelle_motif;
		 							 
		 						}
		 					}
		 				?> </td>
		 			<td><?php
		 					foreach ($departements as $departement) {
		 						if ($departement->iddepartement == $value->departements_id) {
		 							echo($departement->libelle_departement);
		 							break;
		 						}
		 					}
		 				?></td>


		 			<td>
		 				<?php
		 					foreach ($titres as $titre) {
		 						if ($titre->idtitre == $value->titres_id) {
		 							echo($titre->nom);
		 							break;
		 						}
		 					}
		 				?>
		 			</td>
		 			<td><?= $value->typecontrat; ?></td>


		 		</tr>

			<?php endforeach ?>
			</tbody>

	 	</table>
	</div>
	<script>
	$(document).ready(function() {

	var table =	$('#contractuelsTable').DataTable({
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
           		"sEmptyTable": "Aucun agent ajout&eacute;e",
           		"sInfo": "Nombre total d'agents : _TOTAL_ ",
           		"sInfoEmpty": "Aucun agent ajout&eacute;e",
            	"sZeroRecords": "Aucun agent ne correspond &agrave; cette recherche",
            	"sInfoFiltered": " - (Filtrer de _MAX_ agent)"
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
