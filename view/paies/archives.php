<?php 

	$title_for_layout = 'Paie - Personnels';
	$title_for_page_menu = 'Paie';
	$current_menu = "Archive";
 

echo $this->Session->Flash();
 

?>

<form method="post">
		 	<select class="form-control" name="type">
		 		<?php foreach ($typeperso as $key => $value) {
		 			  ?>
		 		<option value="<?php echo $value->idtype_personnel ?>"><?php echo $value->libelle; ?></option>
		 		<?php } ?>
		 	</select>
		 	<div class="modal-footer">
		 	<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button> 
		 	 </div>
		 </form>
		 <?php  if($element == 1){ ?>
	<div class="col-md-12">
		 


	 	<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="contractuelsTable">
	 		<thead>
	 		<tr>
        	 
              	<th class="text-center" width="30%">Nom<br><input type="text" class="form-control input-sm" placeholder="" data-index="1" size="20" /> </th>
        		<th class="text-center" width="25%">Prénom<br><input type="text" class="form-controls input-sm" placeholder="" data-index="2" size="20" /> </th>
        		<th class="text-center" width="5%">Heure prés. </th>
        		<th class="text-center" width="5%">Heure fériée </th>
        		<th class="text-center" width="5%">Abs. non jus. </th>
        		<th class="text-center" width="5%">Abs. Mal.  </th>
        		<th class="text-center" width="15%"></th>
        		<th class="text-center" width="20%"></th>
        		<th class="text-center"  ></th>
        	</tr>
        	</thead>
        	<?php $i = 1; ?>
			
			<tbody>
			<?php foreach ($listepersonne as $value): ?>
		 		<tr>
		 		 
		 			<td><?= $value->nom; ?></td>
		 			<td><?= $value->prenom ;?></td>
		 			<td><?= $value->heure_presence ;?></td>
		 			<td><?= $value->heure_feriee ;?></td>		 
		 			<td><?= $value->heure_absence_non_justifiee; ?></td>
		 			<td><?= $value->heure_absence_maladie; ?></td> 
		 			<td> </td> 
		 			<td>
		 			 
  
 
	                  	  	<!-- La fenetre modal régularisation -->
 				 
	                  	<div class="modal fade" id="recap<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-lg">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-clock-o"></i> Récapitulatif salaire de <?= $value->nom.' '.$value->prenom; ?>  </h4>
		                        </div>
		                        <div class="modal-body">
 				 	 
 				 	 				<h4 class="text-center"> INFORMATIONS GENERALES </h4>
		                         	 <table class="table table-bordered">
		                         		<tr>
		                         			<th class="label-th">Nom et prénom</th>
		                         			<th class="label-th">Type de contrat</th>
		                         			<th class="label-th">Nombre d'enfant a charge</th>	 
		                         		</tr>
		                         		<tr>
		                         			<th class="" ><?php echo $value->nom.' '.$value->prenom ?></th>
		                         			<th class="" ><?php //echo $value->nom ?></th>
		                         			<th class="" ><?php echo $value->nombre_enfant_charge ?></th>	 
		                         		</tr>
		                         	</table>

		                         	<h4 class="text-center"> INFORMATIONS LIEES AUX HEURES </h4>
		                         	 <table class="table table-bordered">
		                         		<tr>
		                         			<th class="label-th">Heure de présence</th>
		                         			<th class="label-th">Heure d'absence non justifiées</th>
		                         			<th class="label-th">Heure d'absence maladie</th>	 
		                         			<th class="label-th">Heure programmée</th>	 
		                         			<th class="label-th">Taux de présence</th>	 
		                         			<th class="label-th">Heure à payer</th>	 
		                         			<th class="label-th">Heures fériées</th>	 
		                         			<th class="label-th">taux horaire</th>	 
		                         			<th class="label-th">Congés annuels </th>	 
		                         			<th class="label-th">heures cong. spéciaux </th>	 
		                         		</tr>
		                         		<tr>
		                         			<th class="label-th" ><?php echo $value->heure_presence ?></th>
		                         			<th class="label-th" ><?php echo $value->heure_absence_non_justifiee ?></th>
		                         			<th class="label-th" ><?php echo $value->heure_absence_maladie ?></th>
		                         			<th class="label-th" ><?php echo $value->heure_programme ?></th>
		                         			<th class="label-th" ><?php echo $value->taux_presence ?></th>
		                         			<th class="label-th" ><?php echo $value->heure_payer ?></th>		                         			  
		                         			<th class="label-th" ><?php echo $value->heure_feriee ?></th>		                         			  
		                         			<th class="label-th" ><?php echo $value->taux_horaire ?></th>		                         			  
		                         			<th class="label-th" ><?php echo $value->nombre_jour_pris_conge_annuel ?></th>		
		                         			<th class="label-th" ><?php echo $value->heure_pris_conge_speciaux ?></th>		                         			  
		                         		</tr>
		                         	</table>

		                         	<h4 class="text-center"> INFORMATIONS LIEES AU SALAIRE </h4>
		                         	 <table class="table table-bordered">
		                         		<tr>
		                         			<th class="label-th">Salaire de base</th>
		                         			<th class="label-th">Salaire fixe</th>
		                         			<th class="label-th">Cumul prime</th>	 
		                         			<th class="label-th">Rémuneration fériée</th>	 
		                         			<th class="label-th">Moyenne mensuelle(Co An.)</th>	 
		                         			<th class="label-th">Alloc Cong Ann</th>	 
		                         			<th class="label-th">Tx Cong Spéc</th>	 
		                         			<th class="label-th">Alloc Cong Spéc</th>	 
		                         			<th class="label-th">Frais mission</th>	 
		                         			<th class="label-th">Trop perçu</th>	 
		                         			<th class="label-th">Salaire brut</th>	  
		                         			<th class="label-th">Avance </th>	  
		                         			<th class="label-th">retenue</th>	  
		                         		</tr>
		                         		<tr>
		                         			<td class="label-th" ><?php echo $value->salaire_base ?></th>
		                         			<td class="label-th" ><?php echo $this->decrypt($value->salaire_fixe) ?></th>
		                         			<td class="label-td" ><?php echo $value->prime1 + $value->prime2 + $value->prime3 + $value->prime4 + $value->prime5   ?></td>
		                         			<td class="label-td" ><?php echo $value->remuneration_jour_ferie ?></td>
		                         			<td class="label-td" ><?php echo $value->moyenne_mensuelle ?></td>
		                         			<td class="label-td" ><?php echo $value->allocation_conge_annuel ?></td>		                         			  
		                         			<td class="label-td" ><?php echo $value->taux_conge_speciaux ?></td>		                         			  
		                         			<td class="label-td" ><?php echo $value->allocation_conge_speciaux ?></td>		                         			  
		                         			<td class="label-td" ><?php echo $infospaie['mission'][$value->idpersonnel]->montant ?></td>		                         			  
		                         			<td class="label-td" ><?php echo $infospaie['regularisation'][$value->idpersonnel]->montant ?></td>		                         			  
		                         			<td class="label-td" ><?php echo $value->salaire_brut ?></td>		                         			  
		                         			<td class="label-td" ><?php echo $infospaie['avance'][$value->idpersonnel]->montant ?></td>		                         			  
		                         			<td class="label-td" ><?php echo $infospaie['retenue'][$value->idpersonnel]->montant ?></td>		                         			  
		                         		</tr>
		                         	</table>

		                         	<h4 class="text-center"> CHARGES SALARIALES, PATRONALES ET SALAIRE NET </h4>
		                         	 <table class="table table-bordered">
		                         		<tr>
		                         			<th class="label-th">CNSS</th>
		                         			<th class="label-th">IPTS BRUT</th>
		                         			<th class="label-th">ABATTEMENT</th>	 
		                         			<th class="label-th">IPTS NET</th>	 
		                         			<th class="label-th">CNSS PATRONALE</th>	 
		                         			<th class="label-th">VPS </th>	 
		                         			<th class="label-th">NET A PAYER </th>	 
		                         		</tr>
		                         		<tr>
		                         			<td class="" ><?php echo $value->cnss  ?></td>
		                         			<td class="" ><?php echo $value->ipts_brut  ?></td>
		                         			<td class="" ><?php echo $value->abattement  ?></td>
		                         			<td class="" ><?php echo $value->iptsnet  ?></td>
		                         			<td class="" ><?php echo $value->cnss_patronale  ?></td>
		                         			<td class="" ><?php echo $value->vps  ?></td>
		                         			<td class="" ><?php echo $value->salaire_net  ?></td>
		                         			  
		                         		</tr>
		                         	</table>

		                         	</div>
		                        <div class="modal-footer">
		                          
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>




		 			</td>
		 			<td> 
				<a href="#" class="btn btn-danger"  <?php if(empty($value->salaire_net)) echo 'disabled '; else echo 'data-toggle="modal" data-target="#recap'.$value->idpersonnel.'"';  ?> ><span class="fa fa-table"></span> Récap.</a>
				</td>
 
		 		</tr>










	 		
	 				<script>
    $('#date_avance<?php echo $value->idpersonnel  ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

      $('#date_retenue<?php echo $value->idpersonnel  ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });

       $('#date_mission<?php echo $value->idpersonnel  ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });
          $('#date_regularisation<?php echo $value->idpersonnel  ?>').datepicker({
        language: 'fr',
        format: "yyyy-mm-dd" 
    });
</script>
			<?php endforeach ?>
			</tbody>

	 	</table>
	</div>

<!-- La fenetre modal frais de mission -->
 					
		<div class="modal fade" id="valideheure" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-clock-o"></i> Validation des heures par défaut </h4>
		    		              </div>
		    		              <form action="" method="post">
 				 				  <input type="hidden" value="0" name="validation_heure">
		                        <div class="modal-body">
		                        Veuillez vérifier que tout le personnel administratif indirect est intégré avant validation. Les éléments suivants seront validés par défaut pour tout le personnel:
		                        	<ul>
		                        		<li><strong>Heures de présence:</strong> <?php echo $paie_encours->heure_tps_plein ?></li>
		                        		<li><strong>Heures fériées :</strong> <?php echo $paie_encours->heure_ferie_mois ?></li>
		                        		<li><strong>Heures d'absence non justifiées </li>
		                        		<li><strong>Heures d'absence maladie </li>
		                        		<li><strong>Heures programmées </li>
		                        		<li><strong>Taux de présence </li>
		                        		<li><strong>Heures à payer </li>
		                        		 
		                        	</ul>
		                         Voulez-vous vraiment continuer? Cette action est irreverssible.

		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
		                         <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>          



	                  		<!-- La fenetre modal régularisation -->
 					<form action="" method="post">
 				 				  <input type="hidden" value="0" name="validation_en_block">
 	                  	<div class="modal fade" id="validation" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-lg">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-edit"></i>  Aperçu des éléments de paie pour validation</h4>
		                        </div>
		                        <div class="modal-body">
 				 	 
 				 	  			 
							 	<table class="table table-bordered table-striped table-hover table-responsive" id="">
							 		<thead>
							 		<tr>
						        	 
						              	<th class="text-center" width="4%">Nom</th>
						        		<th class="text-center" width="3%">Prénom</th>
						        		<th class="text-center" width="1%">H. Pres</th>
						        		<th class="text-center" width="1%">H. Fer</th>
						        		<th class="text-center" width="1%">Abs N. J.</th>
						        		<th class="text-center" width="1%">Abs Mal</th>
						        		<th class="text-center" width="1%">H. Prog</th>
						        		<th class="text-center" width="1%">Tx Pres</th>
						        		<th class="text-center" width="1%">H. Pay</th>
						        		<th class="text-center" width="5%">Cumul Prime</th>
						        		<th class="text-center" width="2%">Tx Hor fer.</th>
						        		<th class="text-center" width="2%">Remu. fer.</th>
						        		<th class="text-center" width="5%">Sal Base</th>
						        		<th class="text-center" width="5%">Sal Fixe</th>
						        		<th class="text-center" width="5%">Sal Net</th>
						        		<!-- <th class="text-center" width="2%">Typ Paiem.</th> -->
						        		<th class="text-center" width="5%">N° Comp.</th>
						        		<!-- <th class="text-center" width="5%">Banque</th> -->
						        	</tr>
						        	</thead>
						        	<?php $i = 1;  ?> 
									
									<tbody>
									<?php foreach ($listepersonne as $value): if($i>10) break;?>
								 			<tr>		 		 
									 			<td><?= $value->nom; ?></td>
									 			<td><?= $value->prenom ;?></td>
									 			<td><?= $value->heure_presence ;?></td>
									 			<td><?= $value->heure_feriee ;?></td>		 
									 			<td><?= $value->heure_absence_non_justifiee; ?></td>
									 			<td><?= $value->heure_absence_maladie; ?></td> 
									 			<td><?= $value->heure_programme; ?></td> 
									 			<td><?= $value->taux_presence; ?></td> 
									 			<td><?= $value->heure_payer; ?></td> 
									 			<td><?= $value->prime1+$value->prime2+$value->prime3+$value->prime4+$value->prime5; ?></td> 
									 			<td><?= $value->taux_horaire; ?></td> 
									 			<td><?= $value->remuneration_jour_ferie; ?></td> 
									 			<td><?= $value->salaire_base; ?></td> 
									 			<td><?= $this->decrypt($value->salaire_fixe); ?></td> 
									 			<td><?= $value->salaire_net; ?></td> 
									 			<!-- <td><?= $value->type_paiement; ?></td>  -->
									 			<td><?= $value->numero_compte; ?></td> 
									 			<!-- <td><?= $value->banque; ?></td>  -->
								 			</tr>
							 				<?php 
							 				
							 				$i++;
							 				 endforeach; ?>
							 			</tbody>
							 			</table>	

                         		 
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
		                         <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                 

      <?php } ?> 

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
           		"sEmptyTable": "Aucun contractuels ajout&eacute;e",
           		"sInfo": "Nombre total de contractuels : _TOTAL_ ",
           		"sInfoEmpty": "Aucun contractuel ajout&eacute;e",
           		"sLengthMenu": " _MENU_  contractuel",
            	"sZeroRecords": "Aucun contractuel ne correspond &agrave; cette recherche",
            	"sInfoFiltered": " - (Filtrer de _MAX_ contractuel)"
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