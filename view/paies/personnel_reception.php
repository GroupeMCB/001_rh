<?php 

	$title_for_layout = 'Paie - Personnels';
	$title_for_page_menu = 'Paie';
	$current_menu = 'Personnel en réception ';
 

	if($desactive == 1)  {$classe_paie = "disabled"; $classe_heure ="";} else {$classe_paie = ""; $classe_heure ="disabled";}
	$button_option ='<a class="btn btn-danger btn-sm '.$classe_heure.' " data-toggle="modal" data-target="#valideheure"><i class="fa fa-clock-o"></i> Valider les heures à payer</a>'.' '.'<a class="btn btn-success btn-sm '.$classe_paie.' " data-toggle="modal" data-target="#validation"><i class="fa fa-check"></i> Valider la paie en block</a>'.' '.'<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#"><i class="fa fa-download"></i> Exporter</a>';
echo $this->Session->Flash();
 
?>


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
			<?php foreach ($listepersonne as $value):?>
		 		<tr>
		 		 
		 			<td><?= $value->nom; ?></td>
		 			<td><?= $value->prenom ;?></td>
		 			<td><?= $value->heure_presence ;?></td>
		 			<td><?= $value->heure_feriee ;?></td>		 
		 			<td><?= $value->heure_absence_non_justifiee; ?></td>
		 			<td><?= $value->heure_absence_maladie; ?></td> 
		 			<td>
		 				<!-- Button action -->
						<div class="btn-group dropup">
						  <button type="button" class="btn btn-primary btn-sm"><span class="fa fa-cog"></span> Actions</button>
						  <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <span class="caret"></span>
						    <span class="sr-only">Toggle Dropdown</span>
						  </button>
						  <ul class="dropdown-menu dropdown-menu-right"> 
						     <li>
						    	<a href="#" <?php if($desactive == 1) echo 'class="btn  disabled" ';else echo 'data-toggle="modal" data-target="#info'.$value->idpersonnel.'"' ?> ><span class="fa fa-clock-o"></span> Infos heures</a>
						    </li>
						   <li> 
						    	<a href="#" <?php if($desactive == 1) echo 'class="btn  disabled" ';else echo ' data-toggle="modal" data-target="#autre'.$value->idpersonnel.' " ' ?> ><span class="fa fa-plus"></span> Primes </a>
						    </li>
						     <li>
						    	<a href="#" <?php if($desactive == 1) echo 'class="btn  disabled" ';else echo ' data-toggle="modal" data-target="#regularisation'.$value->idpersonnel.'"'?> ><span class="fa fa-list"></span> Régularisation</a>
						    </li>
						    <li role="separator" class="divider"></li>
						     <li>
						    	<a href="" data-toggle="modal" data-target="#congeannuel<?= $value->idpersonnel; ?>"><span class="fa fa-plane"></span> Congés annuels</a>
						    </li>
						    <li>
						    	<a href="" data-toggle="modal" data-target="#congespeciaux<?= $value->idpersonnel; ?>"><span class="fa fa-plane"></span> Congés spéciaux</a>
						    </li>
						  <li role="separator" class="divider"></li>
						     <li>
						    	<a href="" data-toggle="modal" data-target="#fraismission<?= $value->idpersonnel; ?>"><span class="fa fa-plane"></span> Frais de mission</a>
						    </li>
						    
						    <li>
						    	<a href="" data-toggle="modal" data-target="#avance<?= $value->idpersonnel; ?>"><span class="fa fa-money"></span> Avance sur salaire</a>
						    </li>
						    <li>
						    	<a href="" data-toggle="modal" data-target="#retenue<?= $value->idpersonnel; ?>" ><span class="fa fa-minus"></span> Retenue sur salaire</a>
						    </li>
						    
						    <li role="separator" class="divider"></li>
						    <li>
								<a href="" data-toggle="modal" data-target="#editModal<?= $value->idpersonnel; ?>" ><span class="fa fa-edit"></span> Infos Salaire</a>
						    </li>
						   
						  </ul>
						</div>
 				
 								<!-- La fenetre modal de modification -->
 					<form action="" method="post">
 					<input type="hidden" value="<?php echo $value->idpersonnel;  ?>" name="personnel_id">
 				<?php if(!empty($value->idpersonnel_infopaie)) : ?>
 					<input type="hidden" value="<?php echo $value->idpersonnel_infopaie;  ?>" name="idpersonnel_infopaie">
 				<?php endif; ?>
	                  	<div class="modal fade" id="editModal<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-edit"></i> Salaire et compte <?= $value->nom.' '.$value->prenom; ?></h4>
		                        </div>
		                        <div class="modal-body">
		                         <table class="table">
 				 	  						<tr>
 				 	  							<td class="label-th">Salaire de Base</td>
 				 	  				<td class="">  <input class="form-control"  name="salaire_base" value="<?php echo $value->salaire_base?>" >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Banque</td>
 				 	  							<td class="">  
 				 	  								<select class="form-control" name="banque">
			                                        	<option></option>
			                                         	<option <?php if($value->banque == 'BOA' ) echo 'selected' ?>> BOA</option>
			                                         	<option  <?php if($value->banque == 'BGFI' ) echo 'selected' ?> > BGFI</option>
			                                         	<option  <?php if($value->banque == 'UBA' ) echo 'selected' ?>> UBA</option>
			                                         	<option  <?php if($value->banque == 'ECOBANK' ) echo 'selected' ?>> ECOBANK</option>   
                                        			 </select>
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Numero de compte</td>
 				 	  							<td class=""> <input class="form-control"  name="numero_compte" value="<?php echo $value->numero_compte ?>">
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Type de paiement</td>
 				 	  							<td class="">  <select class="form-control" name="type_paiement">
                                         	<option <?php if($value->type_paiement == 'Virement' ) echo 'selected' ?> value="Virement"> Virement</option>
                                         	<option value="Chèque" <?php if($value->type_paiement == 'Chèque' ) echo 'selected' ?> > Chèque</option>
                                         	<option value="Espèce" <?php if($value->type_paiement == 'Espèce' ) echo 'selected' ?>> Espèce</option>

                                         </select>
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  					</table> 
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
		                         <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>




	                  		<!-- La fenetre modal d'ajout d'avance sur salaire -->
 					<form action="" method="post">
	                  	<div class="modal fade" id="avance<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-edit"></i>Avance sur salaire pour <?= $value->nom.' '.$value->prenom; ?></h4>
		                        </div>
		                        <div class="modal-body">
 				 	  <input type="hidden" value="<?php echo $value->idpersonnel;  ?>" name="personnel_id">

 				 	 				 <table class="table">
 				 	  						<tr>
 				 	  							<td class="label-th">Montant net de l'avance</td>
 				 	  							<td class="has-error">  <input class="form-control"  name="montant_avance" required="" >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Date de l'avance</td>
 				 	  							<td class="has-error"> <input class="form-control"  name="date_avance"  required="" id="date_avance<?php echo $value->idpersonnel  ?>"  >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  					</table> 
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
		                         <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>




	                  		<!-- La fenetre modal d'ajout d'avance sur salaire -->
 					<form action="" method="post">
	                  	<div class="modal fade" id="retenue<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-edit"></i>Retenue sur salaire pour <?= $value->nom.' '.$value->prenom; ?></h4>
		                        </div>
		                        <div class="modal-body">
 				 	  <input type="hidden" value="<?php echo $value->idpersonnel;  ?>" name="personnel_id">
 				 				  <table class="table">
 				 	  						<tr>
 				 	  							<td class="label-th">Montant net de la retenue</td>
 				 	  							<td class="has-error">  <input class="form-control"  name="montant_retenue" required="" >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Date de la retenue</td>
 				 	  							<td class="has-error">   <input class="form-control"  required=""  name="date_retenue" id="date_retenue<?php echo $value->idpersonnel  ?>"  >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Motif de la retenue</td>
 				 	  							<td class="has-error">  <textarea name="motif_retenue" class="form-control"  required=""></textarea>
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  					</table> 
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
		                         <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>



	                  		<!-- La fenetre modal frais de mission -->
 					<form action="" method="post">
	                  	<div class="modal fade" id="fraismission<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-money"></i> Frais de mission <?= $value->nom.' '.$value->prenom; ?>  </h4>
		    		              </div>
		                        <div class="modal-body">
 				 	  <input type="hidden" value="<?php echo $value->idpersonnel;  ?>" name="personnel_id">
 				 	  					<table class="table">
 				 	  						<tr>
 				 	  							<td class="label-th">Montant de la mission</td>
 				 	  							<td class="has-error"><input class="form-control"  name="montant_mission" type="number"  required="" >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Date</td>
 				 	  							<td class="has-error"><input class="form-control"  required=""  name="date_mission" id="date_mission<?php echo $value->idpersonnel  ?>"  >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  					</table> 
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
	                  	<div class="modal fade" id="regularisation<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-edit"></i>Régularisation de <?= $value->nom.' '.$value->prenom; ?></h4>
		                        </div>
		                        <div class="modal-body">
 				 	  <input type="hidden" value="<?php echo $value->idpersonnel;  ?>" name="personnel_id">

 				 	  				<table class="table">
 				 	  						<tr>
 				 	  							<td class="label-th">Salaire perçu mois dernier</td>
 				 	  							<td class="has-error"> <input class=" form-control"  name="montant_salaire_percu"  required="" >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Trop perçu</td>
 				 	  							<td class="has-error"><input class="form-control"  name="montant_trop_percu"  required="" >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Date</td>
 				 	  							<td class="has-error"><input class="form-control"  required=""  name="date_regularisation" id="date_regularisation<?php echo $value->idpersonnel  ?>"  > <small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  					</table> 
		    
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Anuuler</a>
		                         <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>


	                  		<!-- La fenetre modal régularisation -->
 					<form action="" method="post">
	                  	<div class="modal fade" id="congeannuel<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-edit"></i> Congés annuels de <?= $value->nom.' '.$value->prenom; ?></h4>
		                        </div>
		                        <div class="modal-body">
		 				 			  <input type="hidden" value="<?php echo $value->idpaie_element;  ?>" name="idpaie_element">
		 						 	  <input type="hidden" value="<?php echo $value->personnel_id;  ?>" name="personnel_id">

 				 	  				<table class="table">
 				 	  						<tr>
 				 	  							<td class="label-th">Nombre de jour pris</td>
 				 	  							<td class="has-error"> <input class=" form-control"  name="nombre_jour_pris_conge_annuel"  required="" value="<?php echo $value->nombre_jour_pris_conge_annuel ?>">
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Moyenne mensuelle</td>
 				 	  							<td class="has-error"><input class="form-control"  name="moyenne_mensuelle"  required="" value="<?php echo $value->moyenne_mensuelle ?>">
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						
 				 	  					</table> 
		    
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Anuuler</a>
		                         <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>

	                  			<!-- La fenetre modal régularisation -->
 					<form action="" method="post">
	                  	<div class="modal fade" id="congespeciaux<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-edit"></i> Congés spéciaux de <?= $value->nom.' '.$value->prenom; ?></h4>
		                        </div>
		                        <div class="modal-body">
 				 					  <input type="hidden" value="<?php echo $value->personnel_id;  ?>" name="personnel_id">
 				 					  <input type="hidden" value="<?php echo $value->idpaie_element;  ?>" name="idpaie_element">
 				 	  				<table class="table">
 				 	  						<tr>
 				 	  							<td class="label-th">Nombre d'heure jour pris</td>
 				 	  							<td class="has-error"> <input class=" form-control"  name="heure_pris_conge_speciaux"  required="" value="<?php echo $value->heure_pris_conge_speciaux ?>" >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						<tr>
 				 	  							<td class="label-th">Taux</td>
 				 	  							<td class="has-success"><input class="form-control " readonly="" name="taux_conge_speciaux" value="<?php echo round( ($this->decrypt($value->salaire_base)/173.33),2) ?>"  required="" >
 				 	  							<small class="form-text text-muted">Champs requis</small></td>
 				 	  						</tr>
 				 	  						
 				 	  					</table> 
		    
		                        </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Anuuler</a>
		                         <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Valider</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>



	                  	  	<!-- La fenetre modal régularisation -->
 					<form action="" method="post">
	                  	<div class="modal fade" id="autre<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-edit"></i> Prime de <?= $value->nom.' '.$value->prenom; ?></h4>
		                        </div>
		                        <div class="modal-body">
 				 				  <input type="hidden" value="<?php echo $value->idpaie_element;  ?>" name="idpaie_element">
 				 	  			 <table class="table">
 				 	  			 	 
 				 	  			 		<?php for($i=1;$i<=5;$i++) { 
 				 	  			 			$com = "commentaire_prime$i";
 				 	  			 			$prim = "prime$i";
 				 	  			 			 $comment  = $value->$com;
 				 	  			 		   	$pri = $value->$prim ?>
 				 	  						<tr>
 				 	  							<td class="label-th">Montant Prime <?php echo $i; ?> <input class="form-control"  name="prime<?php echo $i; ?>" value="<?php echo  $pri;?>" <?php if($i==1){?> required="" <?php } ?> ></td>
 				 	  						 			 	  						
 				 	  							<td class="label-th">Commentaire Prime <?php echo $i; ?> <input class="form-control" <?php if($i==1){?> required="" <?php } ?> name="commentaire_prime<?php echo $i; ?>"  value="<?php echo $comment;  ?>"  ></td>
 				 	  						</tr>
 				 	  					<?php } ?>
 				 	  					</table> 		                         		 
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
	                  	<div class="modal fade" id="info<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-clock-o"></i> Heures de: <?= $value->nom.' '.$value->prenom; ?></h4>
		                        </div>
		                        <div class="modal-body">
 				 	  <input type="hidden" value="<?php echo $value->idpaie_element;  ?>" name="idpaie_element">
 				 	  <input type="hidden" value="<?php echo $value->personnel_id;  ?>" name="personnel_id">
		                         	

		                         	 <table class="table">
		                         		<tr>
		                         			<th class="label-th">Heures de présence &nbsp;&nbsp;</th>
		                         			<td><input class="form-control"  name="heure_presence" value="<?php echo $value->heure_presence ?>" required="" > <small class="form-text text-muted">Champs requis</small></td>
		                         		</tr>
		                         		<tr>
		                         			<th class="label-th" >Heures fériées &nbsp;&nbsp;</th>
		                         			<td>  <input class="form-control"  name="heure_feriee" value="<?php echo $value->heure_feriee ?>"  required="" > <small class="form-text text-muted">Champs requis</small></td>
		                         		</tr>
		                         		<tr>
		                         			<th>Heures d'absence non justifiées</th>
		                         			<td><input class="form-control"  name="heure_absence_non_justifiee" disabled="" value="<?php echo $value->heure_absence_non_justifiee ?>" required="" ></td>
		                         		</tr>
		                         		<tr>
		                         			<th class="label-th">Heures d'absence maladies</th>
		                         			<td> <input class="form-control"  name="heure_absence_maladie" disabled="" value="<?php echo $value->heure_absence_maladie ?>" required="" ></td>
		                         		</tr>
		                         	</table>
		                         	</div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
		                         <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Modifier</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>


	                  	  	<!-- La fenetre modal régularisation -->
 					<form action="" method="post">
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

				<td> 
	<a href="#" class="btn btn-success " <?php if(empty($value->salaire_net)) echo 'disabled '; else echo 'data-toggle="modal" data-target="#validation_individuel'.$value->idpersonnel.'"';  ?>  ><span class="fa fa-refresh"></span> Recalculer </a>

	    	<!-- La fenetre modal régularisation -->
 					<form action="" method="post">
	                  	<div class="modal fade" id="validation_individuel<?= $value->idpersonnel; ?>" tabindex="-1" role="dialog" aria-labelledby="Fenêtre de confirmation" aria-hidden="true">
		                    <div class="modal-dialog modal-md">
		                      <div class="modal-content">
		                        <div class="modal-header">
		                          <h4><i class="fa fa-clock-o"></i>Recalculer la paie de: <?= $value->nom.' '.$value->prenom; ?></h4>
		                        </div>
		                        <div class="modal-body">
 				 	  <input type="hidden" value="<?php echo $value->idpaie_element;  ?>" name="idpaie_element">
 				 	  <input type="hidden" value="<?php echo $value->personnel_id;  ?>" name="personnel_id">
 				 	  <input type="hidden" value="0" name="validation_individuel">
		                         	La paie sera recalculée sur la base des nouveaux éléments que vous avez intégré. Toutes les anciennes informations seront mises à jour.
 								
		                         </div>
		                        <div class="modal-footer">
		                          <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Annuler</a>
		                         <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Modifier</button>  
		                        </div>
		                      </div><!-- /.modal-content -->
		                    </div><!-- /.modal-dialog  -->
	                  	</div> <!-- /.modal-fade -->
	                  	</form>
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
 				 				  <input type="hidden" value="0" name="validation_heure_reception">
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
		                          <h4><i class="fa fa-edit"></i>  Validation de la paie</h4>
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
						        		<th class="text-center" width="2%">Typ Paiem.</th>
						        		<th class="text-center" width="5%">N° Comp.</th>
						        		<th class="text-center" width="5%">Banque</th>
						        	</tr>
						        	</thead>
						        	<?php $i = 1;  ?> 
									
									<tbody>
									<?php foreach ($listepersonne as $value): ?>
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
									 			<td><?= $value->type_paiement; ?></td> 
									 			<td><?= $value->numero_compte; ?></td> 
									 			<td><?= $value->banque; ?></td> 
								 			</tr>
							 				<?php endforeach; ?>
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