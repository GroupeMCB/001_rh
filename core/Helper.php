<?php
class Helper{
	public $controller;
	public $month = array("01" => "Janvier", "02" => "Février","03" => "Mars","04" => "Avril","05" => "Mai","06" => "Juin",
	"07" => "Juillet","08" => "Août",
	"09" => "Septembre","10" => "Octobre","11" => "Novembre","12" => "Décembre");
	public $month_tri = array("01-02-03" => "Jan - Fev - Mars", "04-05-06" => "Avr - Mai - Juin","07-08-09" => "Jui - Aou - Sep","10-11-12" => "Oct - Nov - Dec");

	public function __construct($controller){
		$this->controller = $controller;
	}

 
	public function NombrePersonne(){
		 
			 $total =0;
			 $totals =0;
			 $sommetitre = 0;
			 $sommesanction = 0;

	 		
	 		 $titre = $this->controller->Planning_conge->findInTable('titre');
	    	 

	    	 $sanction = $this->controller->Type_sanction->find();
	    
	    	 $type_contrat = $this->controller->Departement->find();

			 $titrecol = count($titre)+1;
			
			 $sanctioncol = count($sanction)+1;

 
		
								  foreach ($this->month as $keys => $values) {  

								  	if(date("m") == $keys) $act ="active"; else $act = '';

		   $html[$keys] = '<div class="tab-pane fade  in '.$act.' " id="tab'.$values.'" >
		           
			<table width="100%" border="0" class="table table-striped table-bordered table-hover table-condensed dataTables_wrapper form-inline dt-bootstrap no-footer" >
		     <thead>
		       <tr >
		          <th ></th>
		          <th colspan="'.$titrecol.'" class="text-center">Effectif du personnel</th>
		          <th colspan="'.$sanctioncol.'" class="text-center">Sanctions</th>
		      </tr>
		        <tr>     
		         <th ></th> ';
		          foreach ($titre as $key => $value) {  
		    
		     				$html[$keys] .=  '<th class="text-center">  '.$value->nom.'  </th>';		        
		      		   } 
					        $html[$keys] .=  '    <th class="text-center">Total</th>';
					        
					        foreach ($sanction as $k => $v) {  
					      
					        $html[$keys] .=  '  <th class="text-center bg-color-red" >'.$v->libelle_sanction.' </th>';
					            }  
					        $html[$keys] .=  '  <th class="text-center">RATIO</th>  
		        </tr>
					  </thead>
								 <tbody>
		        ';


				  foreach ($type_contrat as $i => $val) {  
							$html[$keys] .=  '<tr>
					 	<td>'.$val->libelle_departement.'  </td>';
			
				 	 	 foreach ($titre as $keyss => $valuess) {  

				  //Calcul du nombre de personnes par titre
				    $result = $this->controller->Personnel_titre->NombrePersonneParTitre($valuess->idtitre,$val->iddepartement,$keys);

                    //Détermination des titres antérieurs au mois en cours
				    $resultanterieur = $this->controller->Personnel_titre->NombrePersonneParTitreMoisAnterieure($valuess->idtitre,$val->iddepartement,$keys);

							 if(isset($result) && !empty($result))
							 {


			    $tot = get_object_vars($result) ;
				if(isset($resultanterieur) && !empty($resultanterieur)) {
					$compteante = get_object_vars($resultanterieur) ;

					$totaux = $tot['cou'] - $compteante['cou'];
				}		
				else {

					$totaux = $tot['cou'];
				} 		
					
 									    $total += $totaux;
							 			$html[$keys] .=  '<td>'.$totaux.' 
							 	   
							 		</td>';

									 
							}
							else
							{

									$html[$keys] .=  '<td>0</td>';

								}
							
						}
			
							$html[$keys] .=  '<td>'.$total.'</td>';

		  				 foreach ($sanction as $ks => $vs) {  

		  			$result = $this->controller->Personnel_titre->NombreSanctionParDepartement($val->iddepartement,$keys,$vs->idtype_sanction);
							 if(isset($result) && !empty($result))
							 {
							 	$tots = get_object_vars($result) ;
							 	$totals += $tots['coun'];
								
								$html[$keys] .=  '<td>'.$totals.'</td>';

							}
							else{
							
								$html[$keys] .=  '<td> 0</td>';

							}
					   } 

					   //Calcul du ratio 
					   
					   if($total != 0) $ratio = round( (($totals/$total) *100),2).'%'; else $ratio = '0,00%';
					   	$html[$keys] .=  '<td>'.$ratio.'</td>';
					   		$total =0;
					   		$totals =0;
					 }
						
				 
			$html[$keys] .=  '	</tr>
				
					 </tbody>
						<tfoot>
							 <tr>
								<th>TOTAL</th>';
						foreach ($titre as $k => $v) {  
		    
		     				$html[$keys] .=  '<th class="text-center">  </th>';		        
		      		   } 
					        $html[$keys] .=  '    <th class="text-center">Total</th>';

					         foreach ($sanction as $k => $v) {  
					      
					        $html[$keys] .=  '  <th class="text-center"> </th>';
					            }  
					        $html[$keys] .=  '  <th class="text-center">RATIO</th>  ';

						$html[$keys] .=	'</tr>
					   </tfoot>
				</table>


		        </div>';
		        }  
		       
		        return $html;
		        
		 }



		 public function ajaxloader(){

		 	debug($this->request->data);
		 }



		 /**
		  * [contratpartitre description]
		  * @return [type] [description]
		  */
		 public function contratpartitre(){
		 		 
		 		 $total = 0;
		 		 $totals =0;
		 		 
		 	 $titre = $this->controller->Contrat->findInTable('titre');

// $Personnel_contrat = $this->controller->Personnel_contrat->find(array("conditions"=>"MONTH(date_entree_contrat) = ".$mois)); 

	     	 $type_contrat = $this->controller->Type_contrat->find();

			 $titrecol = count($titre)+1;
			
			 foreach ($this->month as $keys => $values) {  

								  	if(date("m") == $keys) $act ="active"; else $act = '';

		   $html[$keys] = '<div class="tab-pane fade  in '.$act.' " id="tab'.$values.'" >
		           
			<table width="100%" border="0" class="table table-striped table-bordered table-hover table-condensed dataTables_wrapper form-inline dt-bootstrap no-footer" >
		     <thead>
		       <tr >
		          <th ></th>
		          <th colspan="'.$titrecol.'" class="text-center">Effectif du personnel</th>
		         
		      </tr>
		        <tr>     
		         <th ></th> ';
		          foreach ($titre as $key => $value) {  
 $Titre['titre'][$keys] = $this->controller->Personnel_titre->find(array("conditions"=>array("MONTH(date) = ".$keys,))); 
 
// $Titre['contrat'][$keys] = $this->controller->Personnel_contrat->find(array("conditions"=>"MONTH(date_entree_contrat) = ".$keys)); 
		    
		     				$html[$keys] .=  '<th class="text-center">  '.$value->nom.'  </th>';		        
		      		   } 
					        $html[$keys] .=  '    <th class="text-center">Total</th>';
					        
					      
					        $html[$keys] .=  '   
		        </tr>
					  </thead>
								 <tbody>
		        ';


				  foreach ($type_contrat as $i => $val) {  
							$html[$keys] .=  '<tr>
					 	<th>'.$val->nom.'  </th>';
			
				 	 	 foreach ($titre as $keyss => $valuess) {  

				  //Calcul du nombre de poste par contrat 
				  //on fait appel au modele Contrat
		 //$result = $this->controller->Contrat->NombrePosteParContrat($valuess->idtitre,$val->iddepartement,$keys);


							//  if(isset($result) && !empty($result))
							//  {
							//  		// $tot = get_object_vars($result) ;
							//  		// $total += $tot['cou'];
							// 		$html[$keys] .=  '<td>'.$result->cou.'</td>';

									 
							// }
							// else
							// {

									$html[$keys] .=  '<td>0</td>';

								// }
							
						}
			
							$html[$keys] .=  '<td>'.$total.'</td>';

		  				 
 
					 }
						
				 
			$html[$keys] .=  '	</tr>
				
					 </tbody>
						<tfoot>
							 <tr>
								<th>TOTAL</th>';
						foreach ($titre as $k => $v) {  
		    
		     				$html[$keys] .=  '<th class="text-center">  </th>';		        
		      		   } 
					        $html[$keys] .=  '    <th class="text-center">Total</th>';

						$html[$keys] .=	'</tr>
					   </tfoot>
				</table>


		        </div>';
		        }  
		       
		        return $html;

		 }


   
		  /**
		  * Le nombre de personnes par titre pour l'onglet promotion
		  * @return [type] [description]
		  */
		
		 public function NombrePersonnePartitre(){
		 		 
		 		 $total = 0;
		 		 $totals =0;
		 		 
		 	 $titre = $this->controller->Contrat->findInTable('titre');
			 $titrecol = count($titre)+1;
			
			 foreach ($this->month_tri as $keys => $values) {  
			 	
			 		$cle = explode('-', $keys);
			 	
			 		if(in_array(date("m"), $cle)) $act ="active"; else $act = '';

		   $html[$keys] = '<div class="tab-pane fade  in '.$act.' " id="tab'.$keys.'" >
		           
			<table width="100%" border="0" class="table table-striped table-bordered table-hover table-condensed dataTables_wrapper form-inline dt-bootstrap no-footer" >
		     <thead>
		       <tr >
		          <th ></th>
		            <th colspan="'.$titrecol.'" class="text-center">Effectif du personnel</th>
				      </tr>
				        <tr>     
				         <th ></th> ';
				          foreach ($titre as $key => $value) {  


 //$Titre['titre'][$keys] = $this->controller->Personnel_titre->find(array("conditions"=>array("MONTH(date) in (".$cle[0],$cle[0],$cle[0z].") "))); 
 
		    
		     				$html[$keys] .=  '<th class="text-center">  '.$value->nom.'  </th>';		        
		      		   } 
					        $html[$keys] .=  '    <th class="text-center">Total</th>';
					        
					      
					        $html[$keys] .=  '   
		        </tr>
					  </thead>
								 <tbody>
		        ';


							$html[$keys] .=  '<tr>
					 	      <td> Nombre </td>';

					 	        foreach ($titre as $keye => $valuee) {  
					 	        	$res =	$this->controller->Personnel_titre->getNombrePersonneParTitre($valuee->idtitre,$keys);

					 	        		 if(isset($res) && !empty($res))
											 {
											 	 $tes = get_object_vars($res);
											 	 foreach ($tes as $keya => $valuea) {
												 
											 	 	 
											 	 	 $total += $valuea;
											 	 }
												
												$html[$keys] .=  '<td>'.$res->nombre.'</td>';

											}
											else{
											
												$html[$keys] .=  '<td> 0</td>';

											}
 
			}
		$html[$keys] .=  '<td>'.$total.'</td>';

		  				 
  
						
				 
			$html[$keys] .=  '	</tr>
				
					 </tbody>
						<tfoot>
							 <tr>
								<th>Taux de Promotion</th>';
						foreach ($titre as $k => $v) {  
		    
		     				$html[$keys] .=  '<th class="text-center">  </th>';		        
		      		   } 
					        $html[$keys] .=  '    <th class="text-center">Total</th>';

						$html[$keys] .=	'</tr>
					   </tfoot>
				</table>


		        </div>';
		        }  
		       
		        return $html;

		 }



		 
		 /**
		  * [TableauouvementPersonnel permet d'afficher le tableau de bord du mouvement du personnel
		  */
		 public function TableauouvementPersonnel(){
		 
			 $total =0;
			 $totals =0;
			 $sommetitre = 0;
			 $sommesanction = 0;

	 		
	 		 $titre = $this->controller->Planning_conge->findInTable('titre');
	    	 

	    	 $sanction = $this->controller->Type_sanction->find();
	    	 

	    	 $type_contrat = $this->controller->Departement->find();

			 $titrecol = count($titre)+1;
			
			 $sanctioncol = count($sanction)+1;

 
		
								  foreach ($this->month as $keys => $values) {  

								  	if(date("m") == $keys) $act ="active"; else $act = '';

		   $html[$keys] = '<div class="tab-pane fade  in '.$act.' " id="tab'.$values.'" >
		           
			<table width="100%" border="0" class="table table-striped table-bordered table-hover table-condensed dataTables_wrapper form-inline dt-bootstrap no-footer" >
		     <thead>
		       <tr >
		          <th ></th>
		          <th colspan="'.$titrecol.'" class="text-center">Effectif du personnel</th>
		          <th colspan="'.$sanctioncol.'" class="text-center">Mouvement du personnel</th>
		      </tr>
		        <tr>     
		         <th ></th> ';
		          foreach ($titre as $key => $value) {  
		    
		     				$html[$keys] .=  '<th class="text-center">  '.$value->nom.'  </th>';		        
		      		   } 
					        $html[$keys] .=  '    <th class="text-center">Total</th>';
					        $html[$keys] .=  '<th class="text-center"> Congé </th>';		        
		     				$html[$keys] .=  '<th class="text-center"> Maladie </th>';		        
		     				$html[$keys] .=  '<th class="text-center"> Fin de contrat </th>';		        
		      		        $html[$keys] .=  '    <th class="text-center">Démission / Abandon</th>';    
					        $html[$keys] .=  ' 
										        </tr>
													  </thead>
																 <tbody>
										        ';


				  foreach ($type_contrat as $i => $val) {  
							$html[$keys] .=  '<tr>
					 	<td>'.$val->libelle_departement.'  </td>';
			
				 	 	 foreach ($titre as $keyss => $valuess) {  

				  //Calcul du nombre de personnes par titre
				    $result = $this->controller->Personnel_titre->NombrePersonneParTitre($valuess->idtitre,$val->iddepartement,$keys);
				   $sql = 'SELECT COUNT(nom) as cou,nom, iddepartement,libelle_departement 
			        FROM `titre` 
			        LEFT JOIN personnel_titre on personnel_titre.titre_id = titre.idtitre
			        LEFT JOIN personnel_departement on personnel_departement.personnel_id = personnel_titre.personnel_id
			        LEFT JOIN departement on departement.iddepartement = personnel_departement.departement_id
			        WHERE MONTH(personnel_titre.date) <='.$keys.' 
			        AND idtitre = '.$valuess->idtitre.'
			        AND iddepartement = '.$val->iddepartement.'
			        GROUP BY nom,iddepartement';
					//Détermination des titres antérieurs au mois en cours
				  //  $resultanterieur = $this->controller->Personnel_titre->NombrePersonneParTitreMoisAnterieure($valuess->idtitre,$val->iddepartement,$keys);
				    $resultanterieur =  "";

				    		if($keys > date('m')) $html[$keys] .=  '<td>0</td>'; 
				    		
				    		else {
										 if(isset($result) && !empty($result))
										 {

										 	 $tot = get_object_vars($result) ;
										 	// debug($resultanterieur);
											if(isset($resultanterieur) && !empty($resultanterieur)) {
												$compteante = get_object_vars($resultanterieur) ;

												$totaux = $tot['cou'] - $compteante['cou'];
											}		
											else $totaux = $tot['cou'];		 
										 		
										 		$total += $totaux;
										 		 
										   	    $html[$keys] .=  '<td>'.$totaux.'  
												</td>';
												 
										}
										else
										{

												$html[$keys] .=  '<td>0</td>';

											}
						     }
							
						}
			
							$html[$keys] .=  '<td>'.$total.'</td>';

							//calcul du nombre de personnes en congés par département au cours du mois courant
							$res =  $this->controller->Personnel_titre->getNombrePersonneEnCongeParDepartement($val->iddepartement,$keys);

							 if(isset($res) && !empty($res))
							 {
							 		$total_conge = get_object_vars($res) ;
							 		//$total += $tot['cou'];
							$html[$keys] .=  '<td> '.$res->nombre.'</td>';

						     }
						     else
						     {
						     	$html[$keys] .=  '<td>0</td>';
						     }

						     $totalcertificat = 0;
						     //calcul du nombre de certificats par département au cours du mois 
							$ress =  $this->controller->Personnel_titre->getNombreCertificatParDepartement($val->iddepartement,$keys);

							 if(isset($ress) && !empty($ress))
							 {
							 		 
							 		foreach ($ress as $ks => $vs) {
							 			 
							 			$totalcertificat += $vs['nombre'];
							 		}

							$html[$keys] .=  '<td>'.$totalcertificat.'</td>';

						     }
						     else
						     {
						     	$html[$keys] .=  '<td>0</td>';
						     }



						       //calcul de personne en fin de contrat dans le un département au cours du mois courant
							$results =  $this->controller->Personnel_titre->getNombrePersonneFinContrat($val->iddepartement,$keys);

							 if(isset($results) && !empty($results))
							 {
							 		foreach ($results as $as => $va) {
							 			 
							 			$fincontrat += $va['nombre'];
							 		}

							$html[$keys] .=  '<td> '.$fincontrat.'</td>';

						     }
						     else
						     {
						     	$html[$keys] .=  '<td>0</td>';
						     }



						       //calcul de personne ayant demissionné ou abandonné dans un département au cours du mois courant
							$resultss =  $this->controller->Personnel_titre->getNombrePersonneAbandonEtDemission($val->iddepartement,$keys);

							 if(isset($resultss) && !empty($resultss))
							 {
							 		foreach ($resultss as $cle => $val) {
							 			 
							 			$demission += $val['nombre'];
							 		}
							$html[$keys] .=  '<td> '.$demission.'</td>';

						     }
						     else
						     {
						     	$html[$keys] .=  '<td>0</td>';
						     }
					   
					   
					   		$total =0;
					   		$totals =0;
					 }
						
				 
			$html[$keys] .=  '	</tr>
				
					 </tbody>
						<tfoot>
							 <tr>
								<th>TOTAL</th>';
						foreach ($titre as $k => $v) {  
		    
		     				$html[$keys] .=  '<th class="text-center">  </th>';		        
		      		   } 
					        $html[$keys] .=  '    <th class="text-center">Total</th>';

					         foreach ($sanction as $k => $v) {  
					      
					        $html[$keys] .=  '  <th class="text-center"> </th>';
					            }  
					        $html[$keys] .=  '  <th class="text-center">RATIO</th>  ';

						$html[$keys] .=	'</tr>
					   </tfoot>
				</table>


		        </div>';
		        }  
		       
		        return $html;
		        
		 }
}






 