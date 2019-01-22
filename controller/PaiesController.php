<?php 
/**
* 
*/
class PaiesController extends Controller
{
	public function archives($paie_id){

		     	$this->loadModel('Type_Personnel');
			 	$this->loadModel('Annee');

			 	$typeperso = $this->Type_Personnel->find();

			 	//liste de toutes les périodes de paie
			 	$this->set('typeperso',$typeperso);
			 	$this->set('element',0);
			 	
			 	if($this->request->data){
					$this->personnel($this->request->data->type,$this->decrypt($paie_id));
					$this->set('element',1);
				}
	}

	 public function personnel($type,$paie_id = null){

	 		 if(is_numeric($type) && $type > 0) {

	 		 	switch ($type) {
	 		 		case 1:
	 		 			$libelle = "Personnel Administratif Indirect";
	 		 			break;
	 		 		case 2:
	 		 			$libelle = "Personnel Administratif Direct";
	 		 			break;
	 		 		case 3:
	 		 			$libelle = "Personnel Emission";
	 		 			break;
	 		 		case 4:
	 		 			$libelle = "Personnel Réception";
	 		 			break;
	 		 		case 5:
	 		 			$libelle = "Personnel Entretien";
	 		 			break;
	 		 		case 6:
	 		 			$libelle = "Personnel ABFPA";
	 		 			break;
	 		 	 
	 		 		default:
	 		 			# code...
	 		 			break;
	 		 	}	

	 		 	$this->set('libelle',$libelle);

	 		 	$this->loadModel('Paie');
	 		 	$this->loadModel('Absence');
	 		 	$this->loadModel("Personnel");
	 		 	$this->loadModel('Paie_element');
	 		 	$this->loadModel('Personnel_infopaie');
	 		 	$this->loadModel('Personnel_avance');
	 		 	$this->loadModel('Personnel_retenue');
	 		 	$this->loadModel('Personnel_regularisation');
	 		 	$this->loadModel('Personnel_fraismission');
	 		 	$this->loadModel('Personnel_titre');

	 		 	if(isset($paie_id) ) $paie_encours = $this->Paie->getpaiebyid($paie_id);
	 		 	else
	 		 	$paie_encours = $this->Paie->getpaieencours();

	 		 	$this->set('paie_encours',$paie_encours);
 		 	

	 		 	$unpersonnel = $this->Personnel->findfirst(array("conditions" =>"type_personnel_id = ".$type , "fields" => "idpersonnel"));
	 		 	

	 		 	if($unpersonnel === false) $checkpaielement = -1; 
	 		 	else
	 		 	$checkpaielement = $this->Paie_element->findcount( "paie_id =".$paie_encours->idpaie." AND personnel_id = ".$unpersonnel->idpersonnel );

	 		 	if($checkpaielement == -1) {
	 		 		$listepersonne =array();
	 		 		$infospaie = array();
	 		 	}
 

 				//Si aucune informations n'a été trouvé sur la période en cours
	 		 	if($checkpaielement == 0){		
				$listepersonne = $this->Paie->findpersonnelinfopaie($type);
					foreach ($listepersonne as $key => $value) {
						$listepersonne[$key]->heure_presence = 0;
						$listepersonne[$key]->heure_feriee = "0";
						$listepersonne[$key]->heure_absence_non_justifiee = 0;
						$listepersonne[$key]->heure_absence_maladie = 0;
						$listepersonne[$key]->nombre_jour_pris_conge_annuel = 0;
						$listepersonne[$key]->moyenne_mensuelle = 0;
						$listepersonne[$key]->heure_pris_conge_speciaux = 0;
					}
				

				$this->set('desactive',1);
	 		 	 
	 		 	}
	 		 	else{
	 		 		
	 		 		$listepersonne = $this->Paie->findpersonnelinfopaieall($type,$paie_encours->idpaie);
				    $this->set('desactive',0);


	 		 	}

	 		 
	 		 	foreach ($listepersonne as $key => $value) {

	 		 		 $value->salaire_base = $this->decrypt($value->salaire_base);

	 		 		 $infospaie['avance'][$value->personnel_id] = $this->Personnel_avance->getAvanceByPeriode($value->idpersonnel,$paie_encours->idpaie);
	 		 		
	 		 		 $infospaie['retenue'][$value->personnel_id] = $this->Personnel_retenue->getretenueByPeriode($value->idpersonnel,$paie_encours->idpaie);

	 		  $infospaie['regularisation'][$value->personnel_id] = $this->Personnel_regularisation->getregularisationByPeriode($value->idpersonnel,$paie_encours->idpaie);

	 		 		 $infospaie['mission'][$value->personnel_id] = $this->Personnel_fraismission->getFraismissionByPeriode($value->idpersonnel,$paie_encours->idpaie);

	 		 	}

	 		 	if($this->request->data){
	 	
	 		 			if(isset($this->request->data->validation_heure) && $this->request->data->validation_heure ==0){


	 		 				$this->validation_heure($type,$paie_encours,$listepersonne);
	 		 			}

	 		 			$this->validall($this->request->data,$listepersonne,$type);	 		  
	 		 	}


	 		 	$listebanque = $this->Paie->findInTable('banque','1=1');
	 		  
	 		 	$this->set('listebanque',$listebanque);
	 		 	$this->set('listepersonne',$listepersonne);
	 		 	$this->set('infospaie',$infospaie);

	 		 	


	 		 }
	 		 else
	 		 {
	 		 	$this->e404("Impossible d'afficher la page demandée");
	 		 }
	 	  
	 }


	 public function configpaie($id = null){


			 	if(isset($id) && !empty($id) && is_numeric($id)){

			 			$this->set('id',$id);
			 			$this->loadModel('Paie');
			 			$this->loadModel('Type_Personnel');
			 			$unepaie = $this->Paie->find("idpaie = ".$id);
			 			$this->set('unepaie',$unepaie);

			 			$liste_type_personnel = $this->Type_Personnel->find();
			 			$this->set('liste_type_personnel',$liste_type_personnel);
			 			 

			 	}
			 	else{

			 	$this->loadModel('Paie');
			 	$this->loadModel('Annee');

			 	$listpaie = $this->Paie->findpaie();

			 	//liste de toutes les périodes de paie
			 	$this->set('listpaie',$listpaie);

			 		//si un formulaire est soumis
				 	if($this->request->data){

				 		//on vérifie s'il s'agit d'une mise a jour 
				 		if(isset($this->request->data->idpaie) ) {
 
							 	  // $mois_en_cours = $this->Paie->findcount("etat =1");
				 				 //	on met à jour la paie						 	 
				 				$this->Paie->save($this->request->data);

				 				// s'il s'agit d'une cloture
					 			if($this->request->data->etat == 2){
					 				//on recherche le mois suivant
					 				$paiesuivant = $this->Paie->getnextpaie($this->request->data->idpaie);

									//si aucun le mois suivant existe
					 				if($paiesuivant !== false) {
						 				$paiesuivant->etat = 1;
						 				$this->Paie->save($paiesuivant);
						 				$this->redirect('paies/configpaie', 30);
					 				}
					 				else{
					 					$this->Session->setFlash('Aucune période suivante n\'a été défini. Merci de clôturer l\'exercice.','warning');
					 				}
					 			}
 
				 		}

				 		//S'il ne s'agit pas d'une mise à jour, alors on fait une insertion
				 		else{


				 		  		  $annee_en_cours =$this->Annee->find(array("conditions" => "etat = 1"));

				 		  		  $this->request->data->annee_id = $annee_en_cours[0]->idannee; 

							 	  $this->Paie->save($this->request->data);

							 	  $this->Session->setFlash('Nouvelle période ajoutée avec succès','success');
						 		  
						 		  $this->redirect('paies/configpaie', 30);

							 

				 	  	}
				 	}

		 	}


	 }


	 public function etat(){

	 			
	 		 require_once('Classes/PHPExcel.php');
	 		 require_once('Classes/PHPExcel/Writer/Excel2007.php');

	 		 	$this->loadModel('Paie');
	 		 	$this->loadModel('Absence');
	 		 	$this->loadModel("Personnel");
	 		 	$this->loadModel('Paie_element');
	 		 	$this->loadModel('Personnel_infopaie');
	 		 	$this->loadModel('Personnel_avance');
	 		 	$this->loadModel('Personnel_retenue');
	 		 	$this->loadModel('Personnel_regularisation');
	 		 	$this->loadModel('Personnel_fraismission');
	 		 	$this->loadModel('Personnel_titre');

	 		 	$alletat['indirect']['type'] = 1;
	 		 	$alletat['direct']['type'][1] = 2;
	 		 	$alletat['emission']['type'][2] = 3;
	 		 	$alletat['recep']['type'][3] = 4;
	 		 	$alletat['entre']['type'][4] = 5;
	 		 	$alletat['abfpa']['type'][5] = 6;

	 		 	$style_sheet =  array(
				 	
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '0087EF')	
						) 
				);

			
		 $style_header = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

				
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						),

					'borders' => array(
							 
							'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						)
				);

			$style_body = array(

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

			$style_special =  array(
			 
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						) 
				);


			 $style_footer = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

				
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						),

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						)
				);

			  $style_title = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'font' => array(
							'size' => 16,
							'name' => 'Tw Cen MT',
							'color' => array('rgb' => 'FFFFFF')
						)
				);

			   

			$listepaie = $this->Paie->findpaieactive();
	 		$this->set('listepaie',$listepaie);

	 		$paie_encours = $this->Paie->getpaieencours();

		if($this->request->data) {

			foreach ($alletat as $key => $value) {
			 
				 		 	switch ($this->request->data->type) {
				 		 		case 1:
				 		 			$libelle = "ADMINISTRATIF INDIRECT";
				 		 			break;
				 		 		case 2:
				 		 			$libelle = " ADMINISTRATIF DIRECT";
				 		 			break;
				 		 		case 3:
				 		 			$libelle = " EMISSION";
				 		 			break;
				 		 		case 4:
				 		 			$libelle = " RECEPTION";
				 		 			break;
				 		 		case 5:
				 		 			$libelle = " ENTRETIEN";
				 		 			break;
				 		 		case 6:
				 		 			$libelle = " ABFPA";
				 		 			break;
				 		 	 
				 		 		default:
				 		 			# code...
				 		 			break;
				 		 	}	

				 		 	$this->set('libelle',$libelle);

				 		 
				}

	 		 //ETAT ADMINISTRATIF INDIRECTE
	 		 $listepersonne = $this->Paie->findpersonnelinfopaieall($this->request->data->type,$this->request->data->paie_id);

	 		 // Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
				 		 // Set document properties
			$objPHPExcel->getProperties()->setCreator("RECHERCHE ET INNOVATION")
										 ->setLastModifiedBy("RECHERCHE ET INNOVATION")
										 ->setTitle("ETAT PAIE MCB")
										 ->setSubject("Liste des états");
 
			//$objPHPExcel->createSheet(0); 
			// Rename worksheet
			$objPHPExcel->setActiveSheetIndex(0)->setTitle($libelle);

	 
			// Add some data
			$sheet =	$objPHPExcel->setActiveSheetIndex(0);
								$sheet->mergeCells('B2:J2')
								    ->setCellValue('B2', 'ETAT '.$libelle)
								    ->mergeCells('B4:B6')
						            ->mergeCells('C4:C6')
						            ->mergeCells('D4:D6')
						            ->mergeCells('E4:E6')
						            ->mergeCells('F4:F6')
						            ->mergeCells('G4:G6')
						            ->mergeCells('H4:H6')
						            ->mergeCells('I4:I6')
						            ->mergeCells('J4:J6')
						            ->setCellValue('B4', 'NOM ET PRENOM')
						            ->setCellValue('c4', 'TYPE DE CONTRAT')
						            ->setCellValue('d4', 'NOMBRE D\'ENFANTS')
						            ->setCellValue('e4', 'SALAIRES BRUTS')
						            ->setCellValue('f4', 'CNSS')
						            ->setCellValue('g4', 'IPTS')
						            ->setCellValue('h4', 'AVANCE SUR SALAIRE')
						            ->setCellValue('i4', 'REGULARISAT.')
						            ->setCellValue('j4', 'NET A PAYER');
 			 
 			    $sheet->getStyle('B2:J2')->applyFromArray($style_title); 
 			    $sheet->getStyle('B2:J2')->getFont()->setBold(true);
 			    $sheet->getStyle('A1:Z100')->applyFromArray($style_sheet); 
 			    $sheet->getStyle('B4:J4')->getFont()->setBold(true);
 			    $sheet->getStyle('B4:J4')->getFont()->setSize(10);
 			    $sheet->getStyle('B4:J4')->getAlignment()->setWrapText(true);

				$sheet->getColumnDimension('B')->setWidth('27');
				$sheet->getColumnDimension('C')->setWidth('11');
				$sheet->getColumnDimension('D')->setWidth('11');
				$sheet->getColumnDimension('E')->setWidth('10');
				$sheet->getColumnDimension('F')->setWidth('10');
				$sheet->getColumnDimension('G')->setWidth('11');
				$sheet->getColumnDimension('H')->setWidth('11');
				$sheet->getColumnDimension('I')->setWidth('13');
				$sheet->getColumnDimension('J')->setWidth('11');

				 $sheet->getStyle('B4:C6')->applyFromArray($style_header);
				 $sheet->getStyle('C4:C6')->applyFromArray($style_header);
				 $sheet->getStyle('D4:D6')->applyFromArray($style_header);
				 $sheet->getStyle('E4:E6')->applyFromArray($style_header);
				 $sheet->getStyle('F4:F6')->applyFromArray($style_header);
				 $sheet->getStyle('G4:G6')->applyFromArray($style_header);
				 $sheet->getStyle('H4:H6')->applyFromArray($style_header);
				 $sheet->getStyle('I4:I6')->applyFromArray($style_header);
				 $sheet->getStyle('J4:J6')->applyFromArray($style_header);
 
				 //FIN HEADER


						
							$i = 7; $m=7;
						foreach ($listepersonne as $key => $value) {

							 $avance= $this->Personnel_avance->getAvanceByPeriode($value->idpersonnel,$paie_encours->idpaie);
				 		 		
				 	  	     $regularisation = $this->Personnel_regularisation->getregularisationByPeriode($value->idpersonnel,$paie_encours->idpaie);

							if(!is_object($regularisation))  $regularisation->montant = 0; 

								 $type_contrat = $this->Personnel->findTypeContrat($value->idpersonnel);
	 
									
								  $sheet->setCellValue('B'.$i, $value->nom.' '.$value->prenom)
							            ->setCellValue('c'.$i, $type_contrat[0]->contrat)
							            ->setCellValue('d'.$i, $value->nombre_enfant_charge)
							            ->setCellValue('e'.$i, $value->salaire_brut)
							            ->setCellValue('f'.$i, $value->cnss)
							            ->setCellValue('g'.$i, $value->iptsnet)
							            ->setCellValue('h'.$i, $avance->montant )
							            ->setCellValue('i'.$i, $regularisation->montant)
							            ->setCellValue('j'.$i, $value->salaire_net);

								 $sheet->getStyle('B'.$i.':J'.$i)->applyFromArray($style_body);
								
								 $sheet->getStyle('E'.$i)->applyFromArray($style_special);
							
								 $sheet->getStyle('J'.$i)->applyFromArray($style_special);
			  
							$i++;
						}
	
			//FOoter
				 $sheet->getStyle('B'.$i.':J'.($i+2))->applyFromArray($style_footer);
		         $sheet->getStyle('B'.$i.':J'.($i+2))->getFont()->setBold(true);

				  $sheet->mergeCells('B'.$i.':C'.$i)

						->setCellValue('b'.$i, 'SOUS TOTAUX')
						->setCellValue('d'.$i, '=SUM(D'.$m.':D'.($i-1).')')
			            ->setCellValue('e'.$i, '=SUM(E'.$m.':E'.($i-1).')')
			            ->setCellValue('f'.$i, '=SUM(F'.$m.':F'.($i-1).')')
			            ->setCellValue('g'.$i, '=SUM(G'.$m.':G'.($i-1).')')
			            ->setCellValue('h'.$i, '=SUM(H'.$m.':H'.($i-1).')')
			            ->setCellValue('i'.$i, '=SUM(I'.$m.':I'.($i-1).')')
			            ->setCellValue('j'.$i, '=SUM(J'.$m.':J'.($i-1).')')
			            ->setCellValue('b'.($i+1), 'CUMUL BRUT STAGIAIRE ET FORMATION')
			            ->setCellValue('j'.($i+1), '=SUMIF(C'.$m.':C'.($i-1).',"STAGE",E'.$m.':E'.($i-1).') + SUMIF(C'.$m.':C'.($i-1).',"FORMATION",E'.$m.':E'.($i-1).')')
			            ->setCellValue('b'.($i+2), 'CUMUL BRUT A DECLARER')
			            ->setCellValue('j'.($i+2), '=SUMIF(C'.$m.':C'.($i-1).',"CDD",E'.$m.':E'.($i-1).') + SUMIF(C'.$m.':C'.($i-1).',"CDI",E'.$m.':E'.($i-1).')');

		        $sheet->mergeCells('B'.($i+1).':I'.($i+1));
		        $sheet->mergeCells('B'.($i+2).':I'.($i+2) );

		       

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0); 

				$writer = new PHPExcel_Writer_Excel2007($objPHPExcel);

				$name = $libelle.date("dmY").rand(0,100000).'.xlsx';

				$lien = 'http://'.$_SERVER['SERVER_NAME'].''.BASE_URL.'/webroot/doc/'.$name;

				$this->set('lien',$lien);

				$fichiername = WEBROOT.DS.'doc/'.$name;

				$writer->save($fichiername);


			}
	 
 			//echo '<iframe src="'.$fichiername.'"></iframe>';

	 }

	 


	  public function etatfinancier(){

	 			
	 		 require_once('Classes/PHPExcel.php');
	 		 require_once('Classes/PHPExcel/Writer/Excel2007.php');



	 		 	$this->loadModel('Paie');
	 		 	$this->loadModel('Absence');
	 		 	$this->loadModel("Personnel");
	 		 	$this->loadModel('Paie_element');
	 		 	$this->loadModel('Personnel_infopaie');
	 		 	$this->loadModel('Personnel_avance');
	 		 	$this->loadModel('Personnel_retenue');
	 		 	$this->loadModel('Personnel_regularisation');
	 		 	$this->loadModel('Personnel_fraismission');
	 		 	$this->loadModel('Personnel_titre');

	 		 	$alletat['indirect']['type'] = 1;
	 		 	$alletat['direct']['type'][1] = 2;
	 		 	$alletat['emission']['type'][2] = 3;
	 		 	$alletat['recep']['type'][3] = 4;
	 		 	$alletat['entre']['type'][4] = 5;
	 		 	$alletat['abfpa']['type'][5] = 6;

	 		 	$style_sheet =  array(
				 	
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

				$style_gris =  array(
				 	
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '333333')	
						) 
				);

			
		 $style_header = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

				
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						),

					'borders' => array(
							 
							'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						)
				);

			$style_body = array(

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

			$style_special =  array(
			 
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						) 
				);


			 $style_footer = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

				
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						),

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						)
				);

			  $style_title = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'font' => array(
							'size' => 14,
							'name' => 'Tw Cen MT',
							'color' => array('rgb' => '000000')
						)
				);

			 $style_left = array(
							'alignment' =>array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

								'font' => array(
										'size' => 10
										,
										'name' => 'Tw Cen MT',
										'color' => array('rgb' => '000000')
									),

								'borders' => array(
										 
										'allborders' => array(
											'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
											)
									) ,
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'FFFFFF')	
									) 
							);

			 $style_left_1 = array(
							'alignment' =>array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

								'font' => array(
										'size' => 10
										,
										'name' => 'Tw Cen MT',
										'color' => array('rgb' => '000000')
									),

								'borders' => array(
										 
										'allborders' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN
											)
									) ,
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'FFFFFF')	
									) 
							);
			  $style_modulo = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'font' => array(
							'size' => 10
							,
							'name' => 'Tw Cen MT',
							'color' => array('rgb' => '000000')
						),

					'borders' => array(
							 
							'bottom' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

			   $style_cell = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'font' => array(
							'size' => 10
							,
							'name' => 'Tw Cen MT',
							'color' => array('rgb' => '000000')
						),

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
								)
						) ,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

			    $style_border = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
								)
						)  
				);

			$listepaie = $this->Paie->findpaieactive();
	 		$this->set('listepaie',$listepaie);

	 		$paie_encours = $this->Paie->getpaieencours();

	 		 $listepersonne = $this->Paie->findpersonnelinfopaieall(4,19);

	 		


			if($this->request->data) {
 
 			switch ($this->request->data->type)  {
 				case 'cumul':
 			 
 			 $this->set('libelle','CUMUL CHARGE');
 					 
 		 
			 $this->request->data->type =4;
			 $this->request->data->paie_id = 19;
	 		 //ETAT ADMINISTRATIF INDIRECTE

	 		 // Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
				 		 // Set document properties
			$objPHPExcel->getProperties()->setCreator("RECHERCHE ET INNOVATION")
										 ->setLastModifiedBy("RECHERCHE ET INNOVATION")
										 ->setTitle("ETAT PAIE MCB")
										 ->setSubject("Liste des états");
 
			//$objPHPExcel->createSheet(0); 
			// Rename worksheet
			$objPHPExcel->setActiveSheetIndex(0)->setTitle('CUMUL');

	 
			// Add some data
			$sheet =	$objPHPExcel->setActiveSheetIndex(0);
								$sheet->mergeCells('B10:I11')
								    ->mergeCells('B12:B14')
						            ->mergeCells('C12:C14')
						            ->mergeCells('D12:D14')
						            ->mergeCells('E12:E14')
						            ->mergeCells('F12:F14')
						            ->mergeCells('G12:G14')
						            ->mergeCells('H12:H14')
						            ->mergeCells('I12:I14')
								    ->setCellValue('B10', 'Récap des salaires de '.$paie_encours->libelle_paie)								    
						            ->setCellValue('B12', 'TYPE')
						            ->setCellValue('c12', 'STATUT')
						            ->setCellValue('d12', 'SALAIRES BRUTS')
						            ->setCellValue('e12', 'CNSS')
						            ->setCellValue('f12', 'IPTS')
						            ->setCellValue('g12', 'AVANCE ')
						            ->setCellValue('h12', 'REGULARISAT.')
						            ->setCellValue('i12', 'NET A PAYER');
	
 			 
 			    $sheet->getStyle('B10')->applyFromArray($style_title); 
 			    $sheet->getStyle('J1:Z100')->applyFromArray($style_gris); 
 			    $sheet->getStyle('B2:I2')->getFont()->setBold(true);
 			    $sheet->getStyle('A1:I100')->applyFromArray($style_sheet); 
 			    $sheet->getStyle('B4:I4')->getFont()->setBold(true);
 			    $sheet->getStyle('B4:I4')->getFont()->setSize(10);
 			    $sheet->getStyle('B4:i4')->getAlignment()->setWrapText(true);

				$sheet->getColumnDimension('B')->setWidth('27');
				$sheet->getColumnDimension('C')->setWidth('11');
				$sheet->getColumnDimension('D')->setWidth('11');
				$sheet->getColumnDimension('E')->setWidth('10');
				$sheet->getColumnDimension('F')->setWidth('10');
				$sheet->getColumnDimension('G')->setWidth('11');
				$sheet->getColumnDimension('H')->setWidth('11');
				$sheet->getColumnDimension('I')->setWidth('13');
				 

				 $sheet->getStyle('B12:B14')->applyFromArray($style_header);
				 $sheet->getStyle('C12:C14')->applyFromArray($style_header);
				 $sheet->getStyle('D12:D14')->applyFromArray($style_header);
				 $sheet->getStyle('E12:E14')->applyFromArray($style_header);
				 $sheet->getStyle('F12:F14')->applyFromArray($style_header);
				 $sheet->getStyle('G12:G14')->applyFromArray($style_header);
				 $sheet->getStyle('H12:H14')->applyFromArray($style_header);
				 $sheet->getStyle('I12:I14')->applyFromArray($style_header);
				 //FIN HEADER

				 $sheet->getStyle('C15:C28')->applyFromArray($style_left_1);

				 	$brut_contrat_indirect=0;
				 	$brut_contrat_reception=0;
				 	$cnss_contrat=0;
				 	$ipts_contrat=0;
					
				 	 //Personnel administratif indirect
					$indirect = PaiesController::getsalairebrutbytype($paie_encours->idpaie,1);

					foreach ($indirect as $key => $value) {
						 
						 if($value->type_contrat == 2 || $value->type_contrat == 3 ){

						 	$brut_contrat_indirect += $value->montant;
						 }
					}
				 
					  //Personnel administratif direct
					$direct = PaiesController::getsalairebrutbytype($paie_encours->idpaie,2);


					  //Personnel emission
					 $emission = PaiesController::getsalairebrutbytype($paie_encours->idpaie,3);


					  //Personnel administratif reception 
					$reception = PaiesController::getsalairebrutbytype($paie_encours->idpaie,4);

					foreach ($reception as $key => $value) {
						 

						 if($value->type_contrat == 2 || $value->type_contrat == 3 ){
						 	$brut_contrat_reception += $value->montant;
						 }
					}
					//debug($brut_contrat_reception);
					  //Personnel administratif ABFPA
					 $abfpa = PaiesController::getsalairebrutbytype($paie_encours->idpaie,6);

							$i = 15; $m=15;
								for($i=15; $i<=28; $i++){
								  $sheet->mergeCells('B'.$i.':B'.($i+1));

										  if($i%2 != 0){
										  $sheet->setCellValue('C'.$i,'CONTRAT');
										  $sheet->getStyle('C'.$i.':i'.$i)->applyFromArray($style_cell);

										   }
										   else {
										   	 $sheet->setCellValue('C'.$i,'STAGE');
										     $sheet->getStyle('C'.$i.':i'.$i)->applyFromArray($style_cell);
										     $sheet->getStyle('C'.$i.':i'.$i)->applyFromArray($style_modulo);

										   }

								}
								  $sheet->setCellValue('B15', 'ETAT ADMINISTRATION INDIRECT' )
								  		->setCellValue('B17', 'ETAT ADMINISTRATION DIRECT' )
								  		->setCellValue('B19', 'ETAT EMISSION D\'APPEL' )
								  		->setCellValue('B21', 'ETAT RECEPTION MOOV' )
								  		->setCellValue('B23', 'ETAT RECPTION MTN' )
								  		->setCellValue('B25', 'ETAT GMC' )
								  		->setCellValue('B27', 'ETAT ASP' )
							            ->setCellValue('c'.$i, '')
							            ->setCellValue('D'.$i, '')
							            ->setCellValue('E'.$i,  '')
							            ->setCellValue('F'.$i,  '')
							            ->setCellValue('G'.$i,  '' )
							            ->setCellValue('H'.$i,  '')
							            ->setCellValue('I'.$i,  '');

								// $sheet->getStyle('B'.$i.':i'.$i)->applyFromArray($style_body);
								 $sheet->getStyle('B15:B28')->applyFromArray($style_left);
								
								// $sheet->getStyle('E'.$i)->applyFromArray($style_special);																       

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				
								 
				$objPHPExcel->setActiveSheetIndex(0); 

				$writer = new PHPExcel_Writer_Excel2007($objPHPExcel);

				$name = 'cumulcharge'.date("dmY").rand(0,100000).'.xlsx';

				$lien = 'http://'.$_SERVER['SERVER_NAME'].''.BASE_URL.'/webroot/doc/'.$name;

				$this->set('lien',$lien);

				$fichiername = WEBROOT.DS.'doc/'.$name;

				$writer->save($fichiername);

							break;
				/********************************************************************************************************/
				/*                					FIN CUMUL CHARGES																	*/
				/* 																										*/
				/********************************************************************************************************/
						 
						 case 'etat_banque' :
						  $this->set('libelle','ETAT BANQUE');
					function sheet_header($sheet,$nom_banque){
										

										 $style_color = array(		 
														'fill' => array(
															'type' => PHPExcel_Style_Fill::FILL_SOLID,
															'color' => array('rgb' => '322218')	
															) 
													);

										 $style_header = array(
												'alignment' =>array(
													'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
													'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

												
													'fill' => array(
														'type' => PHPExcel_Style_Fill::FILL_SOLID,
														'color' => array('rgb' => 'F28E8C')	
														),

													'borders' => array(
															 
															'outline' => array(
																'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
																)
														) ,

													'font' => array(
															'size' => 10,
															'name' => 'Tw Cen MT'
														)
												);
									  $style_mod = array(
											'alignment' =>array(
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

												 
												'borders' => array(
														 
														'outline' => array(
															'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
															)
													) 					 
											);

							          $sheet->mergeCells('A8:E8')								   
							                ->mergeCells('A1:E1')								   
							                ->mergeCells('A7:E7')								   
										    ->setCellValue('A8',$nom_banque)								    
								            ->setCellValue('A9', 'N°')
								            ->setCellValue('B9', 'NOM')
								            ->setCellValue('c9', 'PRENOM')
								            ->setCellValue('d9', 'N° DE COMPTE')
								            ->setCellValue('e9', 'MONTANT');
					 			    
					 			    $sheet->getStyle('A8:B8')->getFont()->setBold(true);		 			    
			 			 	
					 			    $sheet->getStyle('A1:E1')->applyFromArray($style_color); 
					 			    $sheet->getStyle('A7:E7')->applyFromArray($style_color); 
					 			    $sheet->getStyle('A8:E8')->applyFromArray($style_mod); 
					 			    $sheet->getStyle('A9')->applyFromArray($style_header); 
					 			    $sheet->getStyle('B9')->applyFromArray($style_header); 
					 			    $sheet->getStyle('C9')->applyFromArray($style_header); 
					 			    $sheet->getStyle('D9')->applyFromArray($style_header); 
					 			    $sheet->getStyle('E9')->applyFromArray($style_header); 
					 			    $sheet->getStyle('A9:E9')->getFont()->setBold(true);		 			    

									$sheet->getColumnDimension('A')->setWidth('3');
									$sheet->getColumnDimension('B')->setWidth('19');
									$sheet->getColumnDimension('C')->setWidth('27');
									$sheet->getColumnDimension('D')->setWidth('23');
									$sheet->getColumnDimension('E')->setWidth('13');

									$objdraw = new PHPExcel_Worksheet_Drawing();
									$logo = WEBROOT.DS.'images/logomcb.png';
									$objdraw->setPath($logo);
									$objdraw->setCoordinates('A2');
									$objdraw->setWorksheet($sheet);
					}
		 			
		 			$listebanque = $this->Paie->findInTable('banque',"1=1");
		 			 
		 			$firstsheet = true; $l=10; $fe = ""; $j=0;
					 foreach ($listebanque as $key => $value) {
					 	
					 	if($firstsheet == true) {

					 			 // Create new PHPExcel object
							$objPHPExcel = new PHPExcel();
								 		 // Set document properties
							$objPHPExcel->getProperties()->setCreator("RECHERCHE ET INNOVATION")
														 ->setLastModifiedBy("RECHERCHE ET INNOVATION")
														 ->setTitle("ETAT PAIE MCB")
														 ->setSubject("Liste des états");
							$sheet =  $objPHPExcel->setActiveSheetIndex($key);
							$sheet =  $objPHPExcel->getActiveSheet();
							$sheet->setTitle($value->libelle_banque);

							sheet_header($sheet,$value->libelle_banque);

							$listeagent = $this->Paie->getEtatbybanque($this->request->data->paie_id,$value->idbanque);

								 if($fe != $key ) $l = 10;
								
								foreach ($listeagent as $k => $v) {
									 
								 $sheet->setCellValue('A'.$l,($k+1));
								 $sheet->setCellValue('B'.$l,$v->nom);
								 $sheet->setCellValue('C'.$l,$v->prenom);
								 $sheet->setCellValue('D'.$l,$v->numero_compte);
								 $sheet->setCellValue('E'.$l,$v->salaire_net);
								 $l++;								
								}
					 		
					 			$sheet->getStyle('A10:E'.($l-1))->applyFromArray($style_body); 

					 			$sheet->mergeCells('A'.$l.':D'.$l);
								$sheet->setCellValue('A'.$l,'TOTAL');
					 			$sheet->getStyle('A'.$l.':E'.$l)->applyFromArray($style_border); 

					 			if(!empty($listeagent)) {				 			 
					 			$sheet->setCellValue('E'.$l,'=SUM(E10:E'.($l-1).')');
					 			}
								$fe = $key;
							$firstsheet = false;
					 	}
					 	else {
					  			
					  			$sheet = $objPHPExcel->createSheet($key);
					  			$sheet =  $objPHPExcel->setActiveSheetIndex($key);
								$sheet =  $objPHPExcel->getActiveSheet();
								$sheet->setTitle($value->libelle_banque);
								// Add some data
								sheet_header($sheet,$value->libelle_banque);

								 $listeagent = $this->Paie->getEtatbybanque($this->request->data->paie_id,$value->idbanque);

								 if($fe != $key ) $l = 10;
								
								foreach ($listeagent as $k => $v) {
									 
								 $sheet->setCellValue('A'.$l,($k+1));
								 $sheet->setCellValue('B'.$l,$v->nom);
								 $sheet->setCellValue('C'.$l,$v->prenom);
								 $sheet->setCellValue('D'.$l,$v->numero_compte);
								 $sheet->setCellValue('E'.$l,$v->salaire_net);
								 $l++;								
								}
					 		
					 			$sheet->getStyle('A10:E'.($l-1))->applyFromArray($style_body); 

					 			$sheet->mergeCells('A'.$l.':D'.$l);
								$sheet->setCellValue('A'.$l,'TOTAL');
					 			$sheet->getStyle('A'.$l.':E'.$l)->applyFromArray($style_border); 

					 			if(!empty($listeagent)) {				 			 
					 			$sheet->setCellValue('E'.$l,'=SUM(E10:E'.($l-1).')');
					 			}
								$fe = $key;
						}

						$j++;
					}


						//PERSONNES SANS COMPTE

						$sheet = $objPHPExcel->createSheet($j);
					  			$sheet =  $objPHPExcel->setActiveSheetIndex($j);
								$sheet =  $objPHPExcel->getActiveSheet();
								$sheet->setTitle('SANS COMPTE');


								// Add some data
								sheet_header($sheet,'CHEQUES/ESPECES');

								 $listeagent = $this->Paie->getEtatbyespece($this->request->data->paie_id,$value->idbanque);

								 if($fe != $key ) $l = 10;
								
								foreach ($listeagent as $k => $v) {
									 
								 $sheet->setCellValue('A'.$l,($k+1));
								 $sheet->setCellValue('B'.$l,$v->nom);
								 $sheet->setCellValue('C'.$l,$v->prenom);
								 $sheet->setCellValue('D'.$l,$v->numero_compte);
								 $sheet->setCellValue('E'.$l,$v->salaire_net);
								 $l++;								
								}
					 		
					 			$sheet->getStyle('A10:E'.($l-1))->applyFromArray($style_body); 

					 			$sheet->mergeCells('A'.$l.':D'.$l);
								$sheet->setCellValue('A'.$l,'TOTAL');
					 			$sheet->getStyle('A'.$l.':E'.$l)->applyFromArray($style_border); 

					 			if(!empty($listeagent)) {				 			 
					 			$sheet->setCellValue('E'.$l,'=SUM(E10:E'.($l-1).')');
					 			}
								$fe = $key;



						$objPHPExcel->setActiveSheetIndex(0); 

						$writer = new PHPExcel_Writer_Excel2007($objPHPExcel);

						$name = 'etatbanque'.date("dmY").rand(0,100000).'.xlsx';

						$lien = 'http://'.$_SERVER['SERVER_NAME'].''.BASE_URL.'/webroot/doc/'.$name;

						$this->set('lien',$lien);

						$fichiername = WEBROOT.DS.'doc/'.$name;

						$writer->save($fichiername);

						 break;

						 case 'recap':

						 	 	PaiesController::getrecapnet($this->request->data->paie_id);		 

						 break;

						 case 'det_cnss':
						 	 PaiesController::getdetailcnss($this->request->data->paie_id);
						 	break;
						 case 'boa':
						 	  PaiesController::getboa($this->request->data->paie_id);
						 break;

					}

			}
	 
 			 

	 }





	 public function validation_heure($type,$paie_en_cours,$listedespersonnes){

				//$listedespersonnes = $this->Paie->findpersonnelinfopaiewithfield($type);
				//$this->loadModel('Personnel_titre');

				 

				foreach ($listedespersonnes as $k => $value) {
					$value->personnel_id =	$value->idpersonnel; 

				//Détermination du titre courant de chaque agent
				 $titrecourant = $this->Personnel_titre->GetTitreCourant($value->personnel_id);
				
				 //calcul du nombre d'heures d'absences non justifiées
				 $heure_non_justifie =  $this->Absence->getAbsencenonjustifiebypersonnel($value->personnel_id,$paie_en_cours->date_debut,$paie_en_cours->date_fin);

				 //Calcul du nombre d'heures d'absences justifiées
				 $heure_justifie =  $this->Absence->getAbsencejustifiebypersonnel($value->personnel_id,$paie_en_cours->date_debut,$paie_en_cours->date_fin);
 

				  // if(empty($heure_non_justifie['nombre'])) $heure_non_justifie['nombre'] = 0;
				 
				  // if(empty($heure_justifie['nombre'])) $heure_justifie['nombre'] = 0;


				 	//S'il s'agit des agents en reception d'appels
				     if($titrecourant->titre_id == 3 && $type = 4) {

	 				 $listeindicateur = $this->Paie->getperformanceagentbypersonnel($paie_en_cours->idpaie,$value->personnel_id); 

	 				 $value->performance  = !empty($listeindicateur->performance)  ?  $listeindicateur->performance :0;
	 				 $value->cssi  =!empty($listeindicateur->cssi)  ?  $listeindicateur->cssi :0;
	 				 $value->myster_call  = !empty($listeindicateur->myster_call)  ?  $listeindicateur->myster_call :0;
	 				 $value->quizz  = !empty($listeindicateur->quizz)  ?  $listeindicateur->quizz :0;
	 				 $value->taux_ecoute  = !empty($listeindicateur->taux_ecoute) ? $listeindicateur->taux_ecoute :0;
	 				 $value->tmt  = !empty($listeindicateur->tmt) ?  $listeindicateur->tmt :0;
	 				 $value->appels_traite  = !empty($listeindicateur->appels_traite) ?  $listeindicateur->appels_traite :0;
	 				 $value->heure_presence  =!empty($listeindicateur->heure_presence) ?  $listeindicateur->heure_presence :0;
	 					 			 
	 				}


	 				//S'il s'agit des agents en émision d'appels
	 				if($titrecourant->titre_id == 3 && $type = 3) {
	 					 $value->quizz  = !empty($listeindicateur->quizz)  ?  $listeindicateur->quizz :0;
	 					 $value->taux_ecoute  = !empty($listeindicateur->taux_ecoute) ? $listeindicateur->taux_ecoute :0;
	 					 $value->heure_presence  =!empty($listeindicateur->heure_presence) ?  $listeindicateur->heure_presence :0;
	 					 $value->rib_27  = !empty($listeindicateur->rib_27) ?  $listeindicateur->rib_27 :0;
	 					 $value->rib_16  = !empty($listeindicateur->rib_16) ?  $listeindicateur->rib_16 :0;

	 				}

				 	
				 	$value->heure_absence_non_justifiee =  $heure_non_justifie->nombre;
				 
				 	$value->heure_absence_maladie =  $heure_justifie->nombre;

				 	if($titrecourant->titre_id != 3) {

				 	$value->heure_presence = $paie_en_cours->heure_tps_plein;

				 	}

				 	if($titrecourant->titre_id != 3) {

				 	$value->heure_feriee = $paie_en_cours->heure_ferie_mois;

				    }
				    else $value->heure_feriee = 0;
				 	
				 	$value->paie_id = $paie_en_cours->idpaie;
 
 					 unset($value->nom);
	 		 		 unset($value->prenom);
	 				 unset($value->statut);
	 				 unset($value->idpersonnel_infopaie);
	 				 unset($value->salaire_base);
	 				 unset($value->type_paiement);
	 				 unset($value->banque);
	 				 unset($value->numero_compte);
	 				 unset($value->idpersonnel);
	 				 unset($value->idbanque);
	 				 unset($value->libelle_banque);



					$this->Paie_element->save($value);  

				    $this->getheureprogramme($value->personnel_id,$paie_en_cours->idpaie);	 
					
				    $this->gettauxpresence($value->personnel_id,$paie_en_cours->idpaie);
					
				    $this->getheureapayer($value->personnel_id,$paie_en_cours->idpaie);


				    if($titrecourant->titre_id != 3) {
		 	 		
		 	 			$this->getremuneration_ferie($value->personnel_id,$paie_en_cours->idpaie);

		 	 		}

		 	 	 

				}

				$this->redirect('paies/personnel/'.$type, 30);
	 }



	 public function performance($type){
	 			$validation = 0;
	 			$this->loadModel('Paie');
	 			$this->loadModel('Paie_element');
	 			$this->loadModel('Personnel_titre');
	 			$this->loadModel('Perf_agent');
	 			
	 			$paie_encours = $this->Paie->getpaieencours();

	 			$listepersonnes = $this->Paie->findpersonnelinfopaie($type);

	 			foreach ($listepersonnes as $key => $value) {

	 				//Détermination du titre courant de chaque agent
				     $titrecourant = $this->Personnel_titre->GetTitreCourant($value->idpersonnel);

				     //S'il s'agit d'un CRCD
				     if($titrecourant->titre_id == 3) {
	 				 $listeindicateur = $this->Paie->getperformanceagentbypersonnel($paie_encours->idpaie,$value->idpersonnel); 	
	 				  
	 				  if(!empty($listeindicateur) && $listeindicateur != false) {

	 				 		if($listeindicateur->etat == 1) {
	 				 			$validation = 1;
	 				 			 
	 				 		}
						}

				     $listeagent[$key] = (object)array(); 
	 				 $listeagent[$key]->nom = $value->nom;
	 				 $listeagent[$key]->prenom= $value->prenom;
	 				 $listeagent[$key]->personnel_id = $value->idpersonnel;
	 				 $listeagent[$key]->paie_id = $paie_encours->idpaie;
	 				 $listeagent[$key]->campagne= $value->statut;
	 				 $listeagent[$key]->performance  = !empty($listeindicateur->performance)  ?  $listeindicateur->performance :0;
	 				 $listeagent[$key]->cssi  =!empty($listeindicateur->cssi)  ?  $listeindicateur->cssi :0;
	 				 $listeagent[$key]->myster_call  = !empty($listeindicateur->myster_call)  ?  $listeindicateur->myster_call :0;
	 				 $listeagent[$key]->quizz  = !empty($listeindicateur->quizz)  ?  $listeindicateur->quizz :0;
	 				 $listeagent[$key]->taux_ecoute  = !empty($listeindicateur->taux_ecoute) ? $listeindicateur->taux_ecoute :0;
	 				 $listeagent[$key]->tmt  = !empty($listeindicateur->tmt) ?  $listeindicateur->tmt :0;
	 				 $listeagent[$key]->appels_traite  = !empty($listeindicateur->appels_traite) ?  $listeindicateur->appels_traite :0;
	 				 $listeagent[$key]->heure_presence  =!empty($listeindicateur->heure_presence) ?  $listeindicateur->heure_presence :0;

	 				  if(isset($listeindicateur->idperf_agent) &&  $listeindicateur->idperf_agent != false )
		 				 	 $listeagent[$key]->idperf_agent  = $listeindicateur->idperf_agent ;
		 				 else $listeagent[$key]->idperf_agent = 0;
		 			 
		 				 $listeagent[$key]->etat  = 1; 		
	 				
	 			 
	 				}
	 			}

	 			$this->set('validation',$validation);
	 			$this->set('listeagent',$listeagent);

	 			if($this->request->data){

	 				if($this->request->data->validation_heure == 0){

	 					foreach ($listeagent as $key => $value) {
	 						 
	 						 		 unset($value->nom);
	 						 		 unset($value->prenom);
	 						 		 unset($value->campagne);

	 						 		debug($value);
	 						$this->Perf_agent->save($value);
	 						$this->redirect('paies/performance/'.$type, 30);
	 					}

	 				}
	 			}
	 		 

	 }


	  public function performance_emission(){
	  			$type = 3;
	  			$validation = 0;
	 			$this->loadModel('Paie');
	 			$this->loadModel('Paie_element');
	 			$this->loadModel('Personnel_titre');
	 			
	 			$paie_encours = $this->Paie->getpaieencours();

	 			$listepersonnes = $this->Paie->findpersonnelinfopaie($type);

	 			foreach ($listepersonnes as $key => $value) {

	 				//Détermination du titre courant de chaque agent
				     $titrecourant = $this->Personnel_titre->GetTitreCourant($value->idpersonnel);

				     if($titrecourant->titre_id == 3) {

		 				 $listeindicateur = $this->Paie->getperformanceagentbypersonnel($paie_encours->idpaie,$value->idpersonnel); 

		 				 	 if(!empty($listeindicateur) && $listeindicateur != false) {

				 				 		if($listeindicateur->etat == 1) {
				 				 			$validation = 1;
				 				 			 
				 				 		}
			 				 		
			 				 	}


		 				// if($listeindicateur->etat == 0) $validation = 0; else $validation = 1;	
					     $listeagent[$key] = (object)array(); 
		 				 $listeagent[$key]->nom = $value->nom;
		 				 $listeagent[$key]->prenom= $value->prenom;
		 				 $listeagent[$key]->personnel_id = $value->idpersonnel;
		 				 $listeagent[$key]->paie_id = $paie_encours->idpaie;
		 				 $listeagent[$key]->campagne= $value->statut;
		 				 $listeagent[$key]->quizz  = !empty($listeindicateur->quizz)  ?  $listeindicateur->quizz :0;
		 				 $listeagent[$key]->taux_ecoute  = !empty($listeindicateur->taux_ecoute) ? $listeindicateur->taux_ecoute :0;
		 				 $listeagent[$key]->heure_presence  = !empty($listeindicateur->heure_presence) ?  $listeindicateur->heure_presence :0;
		 				 $listeagent[$key]->rib_27  = !empty($listeindicateur->rib_27) ?  $listeindicateur->rib_27 :0;
		 				 $listeagent[$key]->rib_16  = !empty($listeindicateur->rib_16) ?  $listeindicateur->rib_16 :0;
		 				 $listeagent[$key]->vente_brute = !empty($listeindicateur->vente_brute) ?  $listeindicateur->vente_brute :0;

		 				 if(isset($listeindicateur->idperf_agent) &&  $listeindicateur->idperf_agent != false )
		 				 	 $listeagent[$key]->idperf_agent  = $listeindicateur->idperf_agent ;
		 				 else $listeagent[$key]->idperf_agent = 0;
		 			 
		 				 $listeagent[$key]->etat  = 1; 		
		 				  			 
	 				}
	 			}
				$this->set('listeagent',$listeagent);
	 			 $this->set('validation',$validation);
	 			if($this->request->data){
	 				$this->loadModel('Perf_agent');

	 				if($this->request->data->validation_performance == 0){

	 					foreach ($listeagent as $key => $value) {
	 						  unset($value->nom);
	 						  unset($value->prenom);
	 						  unset($value->campagne);	
	 						  unset($value->quizz);
	 						  unset($value->taux_ecoute);
	 						  unset($value->heure_presence);
	 						  unset($value->rib_27);
	 						  unset($value->rib_16);
	 						  unset($value->vente_brute);
	 						  unset($value->paie_id);
	 						//unset($value->personn);
	 						  
	 						  $this->Perf_agent->save($value);
	 						  $this->redirect('paies/performance_emission', 30);
	 					}

	 				}
	 			}

	 		

	 			
	 		 

	 }

	 public function saisiepaie($id){

			$this->set('id',$id);
			$this->loadModel('Paie');
			$this->loadModel('Type_Personnel');
			$unepaie = $this->Paie->find("idpaie = ".$id);
			$this->set('unepaie',$unepaie);

			$liste_type_personnel = $this->Type_Personnel->find();
			$this->set('liste_type_personnel',$liste_type_personnel);
	 }



		 public function viewavance(){
		
				$this->loadModel('Personnel_avance');
				$this->loadModel('Paie');

				$paie_encours = $this->Paie->getpaieencours();

				if($this->request->data){
					$this->Personnel_avance->delete($this->request->data->idpersonnel_avance);
					$this->Session->setFlash('Avance supprimée avec succès','success');
				}

				$liste_avance = $this->Personnel_avance->getAvanceAll($paie_encours->idpaie);
				$this->set('liste_avance',$liste_avance);
		}


		public function viewretenue(){
		
				$this->loadModel('Personnel_retenue');
				$this->loadModel('Paie');

				$paie_encours = $this->Paie->getpaieencours();

				if($this->request->data){
					$this->Personnel_retenue->delete($this->request->data->idpersonnel_retenue);
					$this->Session->setFlash('Retenue supprimée avec succès','success');
				}

				$liste_retenue = $this->Personnel_retenue->getRetenueAll($paie_encours->idpaie);
				$this->set('liste_retenue',$liste_retenue);
		}


		public function viewregularisation(){
		
				$this->loadModel('Personnel_regularisation');
				$this->loadModel('Paie');

				$paie_encours = $this->Paie->getpaieencours();

				if($this->request->data){
					$this->Personnel_regularisation->delete($this->request->data->idpersonnel_regularisation);
					$this->Session->setFlash('Régularisation supprimée avec succès','success');
				}

				$liste_regularisation = $this->Personnel_regularisation->getregularisation($paie_encours->idpaie);
				$this->set('liste_regularisation',$liste_regularisation);
		}

		public function viewfraismission(){
		
				$this->loadModel('Personnel_fraismission');
				$this->loadModel('Paie');

				$paie_encours = $this->Paie->getpaieencours();

				if($this->request->data){
					$this->Personnel_fraismission->delete($this->request->data->idpersonnel_fraismission);
					$this->Session->setFlash('Frais de mission supprimés avec succès','success');
				}

				$liste_frais = $this->Personnel_fraismission->getFraismissionAll($paie_encours->idpaie);
				$this->set('liste_frais',$liste_frais);
		}


		public function viewprime(){
		
				$this->loadModel('Paie_element');
				$this->loadModel('Paie');

				$paie_encours = $this->Paie->getpaieencours();

				if($this->request->data){
					$this->Personnel_fraismission->delete($this->request->data->idpersonnel_fraismission);
					$this->Session->setFlash('Frais de mission supprimés avec succès','success');
				}


				$liste_prime = $this->Paie_element->getprime($paie_encours->idpaie);
				$this->set('liste_prime',$liste_prime);
		}



		public function getheureprogramme($personnel_id,$paie_id){

			 
				$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

				foreach ($paie_element as $key => $value) {
					
					$value->heure_programme = $value->heure_presence + $value->heure_absence_maladie + $value->heure_absence_non_justifiee;

					$this->Paie_element->save($value);
				}
			
		}

		public function gettauxpresence($personnel_id,$paie_id){

			    $paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

				foreach ($paie_element as $key => $value) {
					
						if($value->heure_programme != 0){
						$value->taux_presence = round( ($value->heure_presence / $value->heure_programme) *100,2);
					}
					else $value->taux_presence = 0;

					$this->Paie_element->save($value);
				}
		}


		public function getheureapayer($personnel_id,$paie_id){

			
				//Détermination du titre courant de chaque agent
				 $titrecourant = $this->Personnel_titre->GetTitreCourant($personnel_id);

		    $unepaie = $this->Paie->find(array("conditions" =>"etat = 1"));

			$type_contrat = $this->Personnel->findTypeContrat($personnel_id);

			$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

					
		
			    foreach ($paie_element as $key => $value) {

					switch ($type_contrat[0]->contrat) {
						
						case 'FORMATION':
						 
						  $value->heure_payer = 0;
							break;

					 	case 'CDI' :
					 	case 'CDD' :


					if( ($value->heure_presence >= $unepaie[0]->heure_tps_plein) || ($value->heure_presence+$value->heure_absence_maladie >= $unepaie[0]->heure_tps_plein) ){

					 		$value->heure_payer = "173.33";

					 		// if($value->heure_presence+$value->heure_absence_maladie < 173.33 ){

							 // 	$value->heure_payer = $value->heure_presence+$value->heure_absence_maladie;
							 // }
					 	}
					  
						else{

							$value->heure_payer = $value->heure_presence+$value->heure_absence_maladie;
						}

						if($titrecourant->titre_id != 3){

							$value->heure_payer = $value->heure_payer - $value->heure_absence_non_justifiee;
						}

						break;


						case 'STAGE' :
						
						if($value->heure_presence >= $unepaie[0]->heure_tps_plein){

							$value->heure_payer = "173.33";
						}
						else{

							$value->heure_payer = $value->heure_presence;
						}

						break;
						default:
							# code...
							break;
					}

					   //$value->heure_payer = $value->heure_payer - $value->heure_absence_non_justifiee;

					$this->Paie_element->save($value);
			
			}

		}



		public function getremuneration_ferie($personnel_id,$paie_id){

			 
			//Détermination du titre courant de chaque agent
			$titrecourant = $this->Personnel_titre->GetTitreCourant($personnel_id);

			//Si ce n'est pas un agent on calcule la remunération férié
			if($titrecourant->titre_id != 3) {
	
				$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

				$info_paie = $this->Personnel_infopaie->find(array("conditions" => "personnel_id = ".$personnel_id));
				 
				if(empty($info_paie) ) $tx = 0;
				else
				$tx = round( ($this->decrypt($info_paie[0]->salaire_base) / 173.33 ),2);

				$remuneration = $paie_element[0]->heure_feriee * $tx * 0.5;

				$value = (object)array(); 
				$value->taux_horaire = $tx;
				$value->remuneration_jour_ferie = $remuneration;
				$value->idpaie_element = $paie_element[0]->idpaie_element;
				$this->Paie_element->save($value);
			}


		}


		public function getsalairefixe($personnel_id,$paie_id){


			$heure = "173.33";

			$info_paie = $this->Personnel_infopaie->find(array("conditions" => "personnel_id = ".$personnel_id));

			$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

			if(empty($info_paie) ) $salaire_fixe = 0;
			else
			$salaire_fixe =  $this->encrypt( ceil( ( $this->decrypt($info_paie[0]->salaire_base) / $heure ) * $paie_element[0]->heure_payer    ) );

			$value = (object)array(); 
			$value->salaire_fixe = $salaire_fixe;
		
			$value->idpaie_element = $paie_element[0]->idpaie_element;

			$this->Paie_element->save($value);

		}

		public function getallocationcongeannuel($personnel_id,$paie_id){

			$info_paie = $this->Personnel_infopaie->find(array("conditions" => "personnel_id = ".$personnel_id));

			$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

			$allocation = round( ( ($paie_element[0]->moyenne_mensuelle/24) * $paie_element[0]->nombre_jour_pris_conge_annuel),2) ;

			$value = (object)array(); 

			$value->allocation_conge_annuel = $allocation;
		
			$value->idpaie_element = $paie_element[0]->idpaie_element;

			$this->Paie_element->save($value);
		}



		public function getallocationcongespeciaux($personnel_id,$paie_id){

			$info_paie = $this->Personnel_infopaie->find(array("conditions" => "personnel_id = ".$personnel_id));

			$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

			$allocation = round( ($paie_element[0]->taux_conge_speciaux * $paie_element[0]->heure_pris_conge_speciaux),2) ;

			$value = (object)array(); 
			$value->allocation_conge_speciaux = $allocation;
		
			$value->idpaie_element = $paie_element[0]->idpaie_element;

			$this->Paie_element->save($value);
		}


		public function getsalairebrut($personnel_id,$paie_id){

			//Recherche des éléments de paie lié au salaire de base
			$info_paie = $this->Personnel_infopaie->find(array("conditions" => "personnel_id = ".$personnel_id));

			//recherche des éléments de paie
			$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );


			//recherche de la régularisation
			$regularisation = $this->Personnel_regularisation->find(array("conditions" => "personnel_id =".$personnel_id." AND paie_id = ".$paie_id));


			//si aucune régularisation n'a été trouvé
			if(empty($regularisation)) { 

				$regularisation[0] = (object)array(); 

				$regularisation[0]->montant_trop_percu = 0;
			 }

			//calcul du cumul des primes
			$cumul_prime = $paie_element[0]->prime1 + $paie_element[0]->prime2 + $paie_element[0]->prime3 + $paie_element[0]->prime4 + $paie_element[0]->prime5;

			 
			//si aucune inof lié au salaire de base n'a été trouvé
			if(empty($info_paie)) $salaire_brut = 0; 

			//on calcule le salaire brut
			else {
			 
			$salaire_brut = ($paie_element[0]->allocation_conge_annuel + $paie_element[0]->allocation_conge_speciaux + $paie_element[0]->remuneration_jour_ferie + 	$this->decrypt($paie_element[0]->salaire_fixe) + $cumul_prime  ) - $regularisation[0]->montant_trop_percu;
			}

 			//sauvegarde des éléments dans la base
			$value = (object)array(); 
			$value->idpaie_element = $paie_element[0]->idpaie_element;
			$value->salaire_brut = round($salaire_brut) ;
			$this->Paie_element->save($value);
		}



		public function getcnss($personnel_id,$paie_id){

				$info_paie = $this->Personnel_infopaie->find(array("conditions" => "personnel_id = ".$personnel_id));

				$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

				$type_contrat = $this->Personnel->findTypeContrat($personnel_id);

				switch ($type_contrat[0]->contrat) {
					
					case 'STAGE':
						$cnss = 0;
						break;
					
					default:
						$cnss = ($paie_element[0]->salaire_brut * 3.6) / 100; 
						break;
	
			}
 
 				$value = (object)array(); 
				$value->idpaie_element = $paie_element[0]->idpaie_element;
				$value->cnss = round($cnss) ;

				$this->Paie_element->save($value);

		}



		public function getiptsbrut($personnel_id,$paie_id){

			$info_paie = $this->Personnel_infopaie->find(array("conditions" => "personnel_id = ".$personnel_id));

				$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

				$type_contrat = $this->Personnel->findTypeContrat($personnel_id);

				switch ($type_contrat[0]->contrat) {
					
					case 'STAGE':
						$ipts = 0;
						break;
					
					default:
				$salaire_brut = $paie_element[0]->salaire_brut;

				$salaire_brut = intval($salaire_brut/1000)*1000;

				 

				if($salaire_brut > 530001) $ipts = ( ($salaire_brut - 530000) * 0.35) + 80500;
				
				if($salaire_brut > 280001 && $salaire_brut <= 530000) $ipts = round(  ($salaire_brut - 280000) * 0.20) + 30500;

				if($salaire_brut > 130001 && $salaire_brut <= 280000 ) $ipts = (  ($salaire_brut - 130000) * 0.15) + 8000;

				if($salaire_brut > 50001 && $salaire_brut <= 130000 ) $ipts = (  ($salaire_brut - 50000) * 0.10);
			
				if($salaire_brut >= 0 && $salaire_brut <= 50000 ) $ipts = 0;
 
						break;

			}

				$value = (object)array(); 
				$value->idpaie_element = $paie_element[0]->idpaie_element;
				$value->ipts_brut = round($ipts) ;

				$this->Paie_element->save($value);	

		}



		public function getabattementandiptsnet($personnel_id,$paie_id){


			    $personnel = $this->Personnel->find(array("conditions" => "idpersonnel = ".$personnel_id));

				$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

				switch ($personnel[0]->nombre_enfant_charge) {
					case 0:
					case 1:
						$abattement = 0;
						break;
					case 2:
					   $abattement = $paie_element[0]->ipts_brut * 0.05;
					break;
					
					case 3:
						$abattement = $paie_element[0]->ipts_brut * 0.1;
					break;

					case 4:
						$abattement = $paie_element[0]->ipts_brut * 0.15;

					break;

					case 5:
						$abattement = $paie_element[0]->ipts_brut * 0.20;
					break;

					case 6:
						$abattement = $paie_element[0]->ipts_brut * 0.23;
					break;
					default:
						$abattement =0;
						break;
				}
			 
			 	$value = (object)array(); 
				$value->idpaie_element = $paie_element[0]->idpaie_element;
				$value->abattement = $abattement ;
			
				$value->iptsnet = $paie_element[0]->ipts_brut - $abattement ;

				$this->Paie_element->save($value);	
		}


		public function getcnsspatronaleandvps($personnel_id,$paie_id){


				$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

				$type_contrat = $this->Personnel->findTypeContrat($personnel_id);

				switch ($type_contrat[0]->contrat) {
					
					case 'CDD':
					case 'CDI':
						$cnsspatronale = $paie_element[0]->salaire_brut * 0.174 ;
						
						break;
					
					default:
					$cnsspatronale = 0;
					break;
				}
 				
 				$value = (object)array(); 

 				$value->vps = round($paie_element[0]->salaire_brut * 0.04) ;

				$value->idpaie_element = $paie_element[0]->idpaie_element;
				
				$value->cnss_patronale = round($cnsspatronale) ;
			
				$this->Paie_element->save($value);	
			
		}



		public function getsalairenet($personnel_id,$paie_id){

				$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

				$avance = $this->Personnel_avance->getAvanceByPeriode($personnel_id,$paie_id); 

				$retenue = $this->Personnel_retenue->getretenueByPeriode($personnel_id,$paie_id);  

		    	$salaire_net = $paie_element[0]->salaire_brut -($paie_element[0]->cnss + $paie_element[0]->iptsnet + $avance->montant + $retenue->montant);

		    	$value = (object)array(); 

				$value->idpaie_element = $paie_element[0]->idpaie_element;
		    	
		    	$value->salaire_net = ceil($salaire_net);

			
				$this->Paie_element->save($value);	

		}


		public function getprimeagentreception($personnel_id,$paie_id){
			//type de personnel
			 


			$titrecourant = $this->Personnel_titre->GetTitreCourant($personnel_id);

			 

			if($titrecourant->titre_id == 3){

			$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

			$performance = (( ($paie_element[0]->performance/25) *100)*0.1)/100;
			$cssi = ($paie_element[0]->cssi/100)*"0.2";
			$myst = ($paie_element[0]->myster_call/100)*"0.2";
			$quizz = ($paie_element[0]->quizz/100)*"0.1" ;
			$taux =   (($paie_element[0]->taux_ecoute)/100)*"0.2" ;
			$tmt =   ( ( 200 - ( (ceil($paie_element[0]->tmt)/144)*100) ) *0.1 )/100 ;
			$taux_presence =   (($paie_element[0]->taux_presence)/100)*"0.1" ;
			 
			 //Note pondérée
			$total = round( ($performance + $cssi + $myst + $quizz + $tmt + $taux + $taux_presence) * 100,2);
 
 		 
 			$type_contrat = $this->Personnel->findTypeContrat($personnel_id);

						switch ($type_contrat[0]->contrat) {
							
							case 'FORMATION':

							// prime dg
							if($total < 80 ) $prime = $paie_element[0]->appels_traite * 5;
							elseif ($total >= 80 && $total < 94) $prime = $paie_element[0]->appels_traite * 7;
							else $prime = $paie_element[0]->appels_traite * 10;

							//Prime note pondérée
							$prime_pondere = 0;
						
							break;

							CASE 'STAGE':

							// prime dg
							if($total  >= 95 && $paie_element[0]->appels_traite >= 5200) $prime = 11100;
							else $prime = 0;

							//prime ponderee
							if($total  >= 94 && $total > 95) $prime_pondere = 27700;
							elseif($total >= 95  && $total > 97) $prime_pondere = 33300;
							elseif($total >= 97 && $total <98 && $paie_element[0]->appels_traite >= 4600 ) $prime_pondere = 38800;
							elseif($total >=97 && $total < 98 && $paie_element[0]->appels_traite < 4600) $prime_pondere = 33300;
							elseif($total >= 98 && $paie_element[0]->appels_traite >= 4600) $prime_pondere = 44400;
							elseif($total >= 98 && $paie_element[0]->appels_traite < 4600) $prime_pondere = 33300;
							else $prime_pondere = 0;
							
							break;

							CASE 'CDD':
							CASE 'CDI':

							// prime dg
							if($total  >= 95 && $paie_element[0]->appels_traite >= 5200) $prime = 18516;
							else $prime = 0;


							//prime pondérée
							if($total >= 94 && $total <95) $prime_pondere = 28838;
							elseif($total >= 95 && $total < 97) $prime_pondere = 52230;
							elseif($total >=97 && $total < 98 && $paie_element[0]->appels_traite >= 4600) $prime_pondere = 64471;
							elseif($total >=97 && $total < 98 && $paie_element[0]->appels_traite < 4600) $prime_pondere = 52230;
							elseif($total >= 98 && $paie_element[0]->appels_traite >= 4600) $prime_pondere = 70591;
							elseif($total >= 98 && $paie_element->appels_traite < 4600 ) $prime_pondere = 52230;
							else $prime_pondere = 0;
							
							break;


							default:
							$prime = 0;
							$prime_pondere = 0;
							break;
						}

						 $value = (object)array(); 
						 $value->note_pondere = $total;
						 $value->prime1 = $prime;
						 $value->commentaire_prime1 = "PRIME DG";
						 $value->prime2 = $prime_pondere;
						 $value->commentaire_prime2 = "PRIME NOTE PONDEREE";
						 $value->idpaie_element = $paie_element[0]->idpaie_element;

						
						 $this->Paie_element->save($value);

					}
		}


		public function getprimeagentemission($personnel_id,$paie_id){

			$paie_element = $this->Paie_element->find(array("conditions" =>"personnel_id = ".$personnel_id." AND paie_id = ".$paie_id)  );

			$rib_27 = $paie_element[0]->rib_27;
		
			$rib_16 = $paie_element[0]->rib_16;
		
			$total_vente_brut = $paie_element[0]->vente_brute;

			$moyenne = ($paie_element[0]->taux_ecoute + $paie_element[0]->quizz + $paie_element[0]->taux_presence)/3;

			$titrecourant = $this->Personnel_titre->GetTitreCourant($personnel_id);

			if($titrecourant->titre_id == 3){

					$type_contrat = $this->Personnel->findTypeContrat($personnel_id);

						switch ($type_contrat[0]->contrat) {
							
							case 'FORMATION':
							$prime_vente_valide = ($rib_27 + $rib_16) *5000;

							if(($rib_27 + $rib_16) == 30) $prime_vente_brute = 23983;
							else $prime_vente_brute = ceil( (23983/30) * $total_vente_brut);

							if($moyenne >= 94 ) $prime_qualite = 11514;  else $prime_qualite = 0;

							if( $paie_element[0]->heure_payer === 173.33) $prime_heure = 81017;
							else $prime_heure = (81017/173.33) * $paie_element[0]->heure_payer;

							break;

							case 'STAGE':

							if( ($rib_27 + $rib_16) == 12 ) $prime_vente_valide = 35094;

							elseif(($rib_27 + $rib_16) < 12) $prime_vente_valide = $rib_27*1800 + rib_16 * 1600;
							else $prime_vente_valide = 0;

							if(($rib_27 + $rib_16) == 30) $prime_vente_brute = 23983;
							else $prime_vente_brute = ceil( (23983/30) * $total_vente_brut);

							if($moyenne >= 94 ) $prime_qualite = 11514;  else $prime_qualite = 0;

							if( $paie_element[0]->heure_payer === 173.33) $prime_heure = 81017;
							else $prime_heure = (81017/173.33) * $paie_element[0]->heure_payer;


							break;

							case 'CDD':
							case 'CDI':

							if( ($rib_27 + $rib_16) == 12 ) $prime_vente_valide = 35094;
							else $prime_vente_valide = $rib_27*2900 + $rib_16*2400;
							

							if( $total_vente_brut == 30) $prime_vente_brute = 23983;
							else $prime_vente_brute = ceil( (23983/30) * $total_vente_brut); 


							if($moyenne >= 94 ) $prime_qualite = 11514;  else $prime_qualite = 0;

							if( $paie_element[0]->heure_payer === 173.33) $prime_heure = 81017;
							else $prime_heure = (81017/173.33) * $paie_element[0]->heure_payer;

							break;

							default:
							$prime_vente_brute =0;
							$prime_vente_valide =0;

							break;

						}

						 
						 $value = (object)array(); 
						 $value->prime1 = $prime_vente_brute;
						 $value->commentaire_prime1 = "PRIME VENTE BRUTE";
						 $value->prime2 = $prime_vente_valide;
						 $value->commentaire_prime2 = "PRIME VENTE VALIDE";
						 $value->prime3 = $prime_qualite;
						 $value->commentaire_prime3 = "PRIME QUALITE";
						 $value->idpaie_element = $paie_element[0]->idpaie_element;
						 // $value->prime4 = 0;
						 // $value->commentaire_prime4 = "PRIME HEURE";

						 //debug($prime_vente_brute+$prime_vente_valide+$prime_qualite+$prime_heure);
						 $this->Paie_element->save($value);

			}


		}

		public function generatesalaireenblock($personnel_id,$paie_id,$type){

					// $this->getheureprogramme($personnel_id,$paie_id);

					// $this->gettauxpresence($personnel_id,$paie_id);

					 $this->getheureapayer($personnel_id,$paie_id);

					if($type == 4) {

					$this->getprimeagentreception($personnel_id,$paie_id);
						}

						if($type == 3) {
						
					$this->getprimeagentemission($personnel_id,$paie_id);
						}


					$this->getsalairefixe($personnel_id,$paie_id);

					$this->getremuneration_ferie($personnel_id,$paie_id);

					$this->getsalairebrut($personnel_id,$paie_id);

					$this->getcnss($personnel_id,$paie_id);

					$this->getiptsbrut($personnel_id,$paie_id);

					$this->getabattementandiptsnet($personnel_id,$paie_id);

					$this->getcnsspatronaleandvps($personnel_id,$paie_id);

					$this->getallocationcongeannuel($personnel_id,$paie_id);

					$this->getallocationcongespeciaux($personnel_id,$paie_id);

					$this->getsalairenet($personnel_id,$paie_id);			 

		}



		public function validall($data,$listepersonne,$type){
		 	$paie_en_cours = $this->Paie->find(array("conditions" => "etat = 1"));
				//validation en block de la paie
	 		 	 
	 		 		if(isset($data->validation_en_block) && $data->validation_en_block ==0) {
		 
	 		 			foreach ($listepersonne as $key => $value) {
	 		 				 
	 		 				 $this->generatesalaireenblock($value->personnel_id,$paie_en_cours[0]->idpaie,$type);
	 		 			}

			 		 	$this->redirect('paies/personnel/'.$type, 30);

	 		 		}

	 		 		//validation individuelle
	 		 		if(isset($data->validation_individuel) && $data->validation_individuel ==0) {
	 								
	 		 	
	 		 				 $this->generatesalaireenblock($data->personnel_id ,$paie_en_cours[0]->idpaie,$type);
			 		 		$this->redirect('paies/personnel/'.$type, 30);		 
	 		 		}


	 		 		//Modification des heures 
	 		 		if(isset($data->heure_presence)){	 
	 		 			$this->Paie_element->save($data);
	 		 			$this->getheureprogramme($data->personnel_id,$paie_en_cours[0]->idpaie);
						$this->gettauxpresence($data->personnel_id,$paie_en_cours[0]->idpaie);
						$this->getheureapayer($data->personnel_id,$paie_en_cours[0]->idpaie);
	 		 			$this->Session->setFlash('Heures modifiées avec succès','success');
	 		 			$this->redirect('paies/personnel/'.$type, 30);
	 		 		}

	 		 		//Ajout d'une avance sur salaire
	 		 		if(isset($data->montant_avance)){	 
	 		 		    $data->paie_id = $paie_en_cours[0]->idpaie;
	 		 			$this->Personnel_avance->save($data);
	 		 			$this->Session->setFlash('Avance sur salaire ajoutée','success');
	 		 			$this->redirect('paies/personnel/'.$type, 30);
	 		 		}


	 		 		//Ajout d'une retenue sur salaire
	 		 		if(isset($data->montant_retenue)){
	 		 			$data->paie_id = $paie_en_cours[0]->idpaie;
	 		 			$this->Personnel_retenue->save($data);
	 		 			$this->Session->setFlash('Retenue sur salaire ajoutée','success');
	 		 			$this->redirect('paies/personnel/'.$type, 30);
	 		 		}


	 		 		//Ajout d'un frais de mission
	 		 		if(isset($data->montant_mission)){
	 		 			$data->paie_id = $paie_en_cours[0]->idpaie;
	 		 			$this->Personnel_fraismission->save($data);
	 		 			$this->Session->setFlash('Frais de mission ajoutée','success');
	 		 			$this->redirect('paies/personnel/'.$type, 30);
	 		 		}
	 		 		 

	 		 		//Ajout d'une régularisation
	 		 		if(isset($data->montant_salaire_percu)){
	 		 			$data->paie_id = $paie_en_cours[0]->idpaie;
	 		 			$this->Personnel_regularisation->save($data);
	 		 			$this->Session->setFlash('Régularisation ajoutée','success');
	 		 			$this->redirect('paies/personnel/'.$type, 30);
	 		 		}

	 		 		 
	 		 		//ajout d'un congé annuel 
	 		 		if(isset($data->nombre_jour_pris_conge_annuel)){
	 		 			$this->Paie_element->save($data);
	 		 			// $this->getallocationcongeannuel($data->personnel_id,$paie_en_cours[0]->idpaie);
	 		 			$this->Session->setFlash('Congé annuel mise à jour avec succès','success');
	 		 			$this->redirect('paies/personnel/'.$type, 30);
	 		 		}
	 		  
	 		 	 
	 		 	 	//ajout d'un congé spécial 
	 		 		if(isset($data->heure_pris_conge_speciaux)){
	 		 			$this->Paie_element->save($data);
	 		 			// $this->getallocationcongespeciaux($data->personnel_id,$paie_en_cours[0]->idpaie);	 		 			
	 		 			$this->Session->setFlash('Congés spéciaux mise à jour avec succès','success');
	 		 			$this->redirect('paies/personnel/'.$type, 30);
	 		 		}

	 		 		//ajout d'une prime
	 		 		if(isset($data->prime1)){

	 		 			debug($data);
	 		 			$this->Paie_element->save($data);
	 		 			$this->Session->setFlash('Prime ajoutée avec succès','success');
	 		 			$this->redirect('paies/personnel/'.$type, 30);
	 		 		}
	 		  
	 		 		//Modification du salaire de base
	 		 		if(isset($data->salaire_base)) {
	 		 			

	 		 			$ligne = $this->Personnel_infopaie->findcount("personnel_id = ".$data->personnel_id);
			 		 	 
			 		 		if($ligne == 0){
			 		 			$data->salaire_base = $this->encrypt($data->salaire_base);
			 		 			$this->Personnel_infopaie->save($data);

	 		 					// $this->getremuneration_ferie($data->personnel_id,$paie_en_cours[0]->idpaie);
			 		 			// $this->getsalairefixe($data->personnel_id,$paie_en_cours[0]->idpaie);

			 		 			//$this->redirect('paies/personnel/'.$type, 30);
			 		 			$this->Session->setFlash('Informations liées au salaire de base ajoutée','success');
			 		 			$this->redirect('paies/personnel/'.$type, 30);
			 		 		}
			 		 		else{


			 		 			$data->salaire_base = $this->encrypt($data->salaire_base);
			 		 			$this->Personnel_infopaie->save($data);
			 		 			
			 		 			$this->Session->setFlash('Informations liées au salaire de base modifiée','success');
			 		 			$this->redirect('paies/personnel/'.$type, 30);
			 		 		}
	 		 		}
			

		}



		public function getsalairebrutbytype($paie_id,$type_personnel,$campagne = null){

				$liste =	$this->Paie->getsalairebrutbytype($type_personnel,$paie_id);

		return  $liste;
		}


		public function getrecapnet($paie_id){

			require_once('Classes/PHPExcel.php');
	 		 require_once('Classes/PHPExcel/Writer/Excel2007.php');

	 		  $this->set('libelle','RECAPITULATIF NET');

	 		 	// $this->loadModel('Paie');
	 		 	// $this->loadModel('Absence');
	 		 	// $this->loadModel("Personnel");
	 		 	// $this->loadModel('Paie_element');
	 		 	// $this->loadModel('Personnel_infopaie');
	 		 	// $this->loadModel('Personnel_avance');
	 		 	// $this->loadModel('Personnel_retenue');
	 		 	// $this->loadModel('Personnel_regularisation');
	 		 	// $this->loadModel('Personnel_fraismission');
	 		 	// $this->loadModel('Personnel_titre');

	 		   

	 		 	$style_sheet =  array(
				 	
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

				$style_gris =  array(
				 	
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '333333')	
						) 
				);

			
		 $style_header = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

				
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						),

					'borders' => array(
							 
							'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						)
				);

			$style_body = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),
					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

			$style_special =  array(
			 
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						) 
				);


			 $style_footer = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

				
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						),

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						)
				);

			  $style_title = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'font' => array(
							'size' => 14,
							'name' => 'Tw Cen MT',
							'color' => array('rgb' => '000000')
						)
				);

			 $style_left = array(
							'alignment' =>array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

								'font' => array(
										'size' => 10
										,
										'name' => 'Tw Cen MT',
										'color' => array('rgb' => '000000')
									),

								'borders' => array(
										 
										'allborders' => array(
											'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
											)
									) ,
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'FFFFFF')	
									) 
							);

			 $style_left_1 = array(
							'alignment' =>array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

								'font' => array(
										'size' => 10
										,
										'name' => 'Tw Cen MT',
										'color' => array('rgb' => '000000')
									),

								'borders' => array(
										 
										'allborders' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN
											)
									) ,
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'FFFFFF')	
									) 
							);
			  $style_modulo = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'font' => array(
							'size' => 10
							,
							'name' => 'Tw Cen MT',
							'color' => array('rgb' => '000000')
						),

					'borders' => array(
							 
							'bottom' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

			   $style_cell = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'font' => array(
							'size' => 10
							,
							'name' => 'Tw Cen MT',
							'color' => array('rgb' => '000000')
						),

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
								)
						) ,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

			    $style_border = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
								)
						)  
				);
 
			$objPHPExcel = new PHPExcel();
				 		 // Set document properties
			$objPHPExcel->getProperties()->setCreator("RECHERCHE ET INNOVATION")
										 ->setLastModifiedBy("RECHERCHE ET INNOVATION")
										 ->setTitle("ETAT PAIE MCB")
										 ->setSubject("Liste des états");
 
			//$objPHPExcel->createSheet(0); 
			// Rename worksheet
			$objPHPExcel->setActiveSheetIndex(0)->setTitle('RECAP NET');	 
			// Add some data
			$sheet =	$objPHPExcel->setActiveSheetIndex(0);
						 
						      $sheet->setCellValue('B3', 'SALAIRES NET A PAYER')								    
						            ->setCellValue('c3', 'MONTANT PAYE PAR AVANCE EN ESPECES')
						            ->setCellValue('d3', 'PAYE PAR VIREMENT BOA ')
						            ->setCellValue('e3', 'VIREMENT BGFI ET AUTRES BANQUES')
						            ->setCellValue('f3', 'PAYE PAR CHEQUES/ESPECES ')
						            ->setCellValue('g3', 'CUMUL VIREMENTS, CHEQUES ET ESPECES ')
						            ->setCellValue('h3', 'MASSE SALARIALE');
 			    			$sheet->getStyle('B3:i3')->getAlignment()->setWrapText(true);

	
 			 
 			    $sheet->getStyle('J1:Z100')->applyFromArray($style_gris); 
 			    $sheet->getStyle('B3:h3')->getFont()->setBold(true);
 			    $sheet->getStyle('A1:I100')->applyFromArray($style_sheet); 
 			    $sheet->getStyle('B3:I3')->getFont()->setSize(11);

				$sheet->getColumnDimension('B')->setWidth('17');
				$sheet->getColumnDimension('C')->setWidth('18');
				$sheet->getColumnDimension('D')->setWidth('15');
				$sheet->getColumnDimension('E')->setWidth('17');
				$sheet->getColumnDimension('F')->setWidth('21');
				$sheet->getColumnDimension('G')->setWidth('21');
				$sheet->getColumnDimension('H')->setWidth('19');

				 

				 $sheet->getStyle('B3')->applyFromArray($style_header);
				 $sheet->getStyle('C3')->applyFromArray($style_header);
				 $sheet->getStyle('D3')->applyFromArray($style_header);
				 $sheet->getStyle('E3')->applyFromArray($style_header);
				 $sheet->getStyle('F3')->applyFromArray($style_header);
				 $sheet->getStyle('G3')->applyFromArray($style_header);
				 $sheet->getStyle('H3')->applyFromArray($style_header);
				 //FIN HEADER

				 $sheet->getStyle('b4:h4')->applyFromArray($style_left_1);

				 	 //Personnel administratif indirect
				 $totalnetpayer = $this->Paie->gettotalnetpayer($paie_id);
				 $totalavancepayer = $this->Personnel_avance->gettotalavance($paie_id);
				 $totalvirementboa = $this->Paie->getEtatbyboa($paie_id);
				 $totalvirementautre =  $this->Paie->getEtatbyautrebanque($paie_id);
				 $totalespece = 	 $this->Paie->gettotalEtatbyespece($paie_id);
				 $totalcnss = $this->Paie->gettotalcnss($paie_id);
				 $totalipts = $this->Paie->gettotalipts($paie_id);
							

								   $sheet->setCellValue('B4',  $totalnetpayer->montant )								  		
							             ->setCellValue('c4',  $totalavancepayer->montant)
							             ->setCellValue('D4',  $totalvirementboa->montant)
							             ->setCellValue('E4',  $totalvirementautre->montant)
							             ->setCellValue('F4',  $totalespece->montant)
							             ->setCellValue('G4',  '=SUM(D4:E4:F4)' )
							             ->setCellValue('H4',  '=SUM(G4:C4)');

							        $sheet->mergeCells('C9:C10')
							        	  ->mergeCells('d9:d10')
							        	  ->mergeCells('e9:e10')
							        	  ->mergeCells('h9:h10')
							        	  ->mergeCells('f9:g9')

							             ->setCellValue('C9',  'Virements')
							             ->setCellValue('d9',  'Chèques et espèces')
							             ->setCellValue('e9',  'cumul décaissable au paiement')
							             ->setCellValue('f9',  'Charges décaissables à la déclaration')
							             ->setCellValue('f10',  'CNSS')
							             ->setCellValue('g10',  'IPTS')
							             ->setCellValue('h9',  'CHARGES DE SALAIRES TOTALES DECAISSABLES');
							          $sheet->getRowDimension(9)->setRowHeight(40);

							          $sheet->setCellValue('C11','=SUM(D4:E4)')
							          		->setCellValue('D11','=F4')
							          		->setCellValue('E11','=SUM(C11:D11)')
							          		->setCellValue('F11',$totalcnss->montant)
							          		->setCellValue('G11',$totalipts->montant)
							          		->setCellValue('h11','=SUM(E11:F11:G11)');
							          	 

							            // ->setCellValue('I',  '');
							     $sheet->getStyle('c9:h9')->getAlignment()->setWrapText(true);
							     $sheet->getStyle('c9:h9')->getFont()->setBold(true);
								 $sheet->getStyle('c9:h11')->applyFromArray($style_body);

								// $sheet->getStyle('B15:B28')->applyFromArray($style_left);
								
								// $sheet->getStyle('E'.$i)->applyFromArray($style_special);																       

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				
								 
				$objPHPExcel->setActiveSheetIndex(0); 

				$writer = new PHPExcel_Writer_Excel2007($objPHPExcel);

				$name = 'Recapnet'.date("dmY").rand(0,100000).'.xlsx';

				$lien = 'http://'.$_SERVER['SERVER_NAME'].''.BASE_URL.'/webroot/doc/'.$name;

				$this->set('lien',$lien);

				$fichiername = WEBROOT.DS.'doc/'.$name;

				$writer->save($fichiername);
		}


		public function getdetailcnss($paie_id){

			require_once('Classes/PHPExcel.php');
	 		require_once('Classes/PHPExcel/Writer/Excel2007.php');

	 		 $this->set('libelle','DETAIL CNSS');
 
	 		 	$style_sheet =  array(
				 	
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

				$style_gris =  array(
				 	
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '333333')	
						) 
				);

			
		 $style_header = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

				
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						),

					'borders' => array(
							 
							'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						)
				);

			$style_body = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),
					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

			$style_special =  array(
			 
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						) 
				);


			 $style_footer = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

				
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F28E8C')	
						),

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,

					'font' => array(
							'size' => 10,
							'name' => 'Tw Cen MT'
						)
				);

			  $style_title = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'font' => array(
							'size' => 14,
							'name' => 'Tw Cen MT',
							'color' => array('rgb' => '000000')
						)
				);

			 $style_left = array(
							'alignment' =>array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

								'font' => array(
										'size' => 10
										,
										'name' => 'Tw Cen MT',
										'color' => array('rgb' => '000000')
									),

								'borders' => array(
										 
										'allborders' => array(
											'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
											)
									) ,
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'FFFFFF')	
									) 
							);

			 $style_left_1 = array(
							'alignment' =>array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

								'font' => array(
										'size' => 10
										,
										'name' => 'Tw Cen MT',
										'color' => array('rgb' => '000000')
									),

								'borders' => array(
										 
										'allborders' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN
											)
									) ,
								'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'FFFFFF')	
									) 
							);
			  $style_modulo = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'font' => array(
							'size' => 10
							,
							'name' => 'Tw Cen MT',
							'color' => array('rgb' => '000000')
						),

					'borders' => array(
							 
							'bottom' => array(
								'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
								)
						) ,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

			   $style_cell = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'font' => array(
							'size' => 10
							,
							'name' => 'Tw Cen MT',
							'color' => array('rgb' => '000000')
						),

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
								)
						) ,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFFFF')	
						) 
				);

			    $style_border = array(
				'alignment' =>array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

					'borders' => array(
							 
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
								)
						)  
				);
 

 									 $style_color = array(		 
														'fill' => array(
															'type' => PHPExcel_Style_Fill::FILL_SOLID,
															'color' => array('rgb' => '322218')	
															) 
													);

										 $style_header = array(
												'alignment' =>array(
													'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
													'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

												
													'fill' => array(
														'type' => PHPExcel_Style_Fill::FILL_SOLID,
														'color' => array('rgb' => 'F28E8C')	
														),

													'borders' => array(
															 
															'outline' => array(
																'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
																)
														) ,

													'font' => array(
															'size' => 10,
															'name' => 'Tw Cen MT'
														)
												);
									  $style_mod = array(
											'alignment' =>array(
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),

												 
												'borders' => array(
														 
														'outline' => array(
															'style' => PHPExcel_Style_Border::BORDER_MEDIUM	
															)
													) 					 
											);

							       
			$objPHPExcel = new PHPExcel();
				 		 // Set document properties
			$objPHPExcel->getProperties()->setCreator("RECHERCHE ET INNOVATION")
										 ->setLastModifiedBy("RECHERCHE ET INNOVATION")
										 ->setTitle("ETAT PAIE MCB")
										 ->setSubject("Liste des états");
 
			//$objPHPExcel->createSheet(0); 
			// Rename worksheet
			$objPHPExcel->setActiveSheetIndex(0)->setTitle('DETAIL CNSS');	 
			// Add some data
			$sheet =	$objPHPExcel->setActiveSheetIndex(0);

						 			  $sheet->mergeCells('B8:E8')								   
							                ->mergeCells('A1:E1')								   
							                ->mergeCells('A7:E7')								   
										    ->setCellValue('B8','DECLARATION CNSS DU MOIS')								    
								            ->setCellValue('B9', 'N°')
								            ->setCellValue('c9', 'NOM et PRENOM')
								            ->setCellValue('d9', 'SALAIRE BRUT')
								            ->setCellValue('e9', 'NUM CNSS');
								             
					 			    
					 			    $sheet->getStyle('A8:B8')->getFont()->setBold(true);		 			    
			 			 	
					 			    $sheet->getStyle('B1:E1')->applyFromArray($style_color); 
					 			    $sheet->getStyle('B7:E7')->applyFromArray($style_color); 
					 			    $sheet->getStyle('B8:E8')->applyFromArray($style_mod);     
					 			    $sheet->getStyle('B9')->applyFromArray($style_header); 
					 			    $sheet->getStyle('C9')->applyFromArray($style_header); 
					 			    $sheet->getStyle('D9')->applyFromArray($style_header); 
					 			    $sheet->getStyle('E9')->applyFromArray($style_header); 
					 			    $sheet->getStyle('B9:E9')->getFont()->setBold(true);		 			    

									$sheet->getColumnDimension('B')->setWidth('3');
									$sheet->getColumnDimension('C')->setWidth('34');
									$sheet->getColumnDimension('D')->setWidth('13');
									$sheet->getColumnDimension('E')->setWidth('13'); 

									$objdraw = new PHPExcel_Worksheet_Drawing();
									$logo = WEBROOT.DS.'images/logomcb.png';
									$objdraw->setPath($logo);
									$objdraw->setCoordinates('A2');
									$objdraw->setWorksheet($sheet);

 			    
 
								 	 //Personnel administratif indirect
								 $listeagent = $this->Paie->getallpersonnecnss($paie_id);

								  
								 
								 	$i=10; 
									foreach ($listeagent as $key => $value) {
										 $sheet->setCellValue('B'.($i), ($key+1) )
										 	   ->setCellValue('C'.$i,$value->nom.' '.$value->prenom)
										 	   ->setCellValue('D'.$i,$value->salaire_brut)
										 	   	->setCellValue('E'.$i,$value->numero_cnss);
										 	   	$i++;
									}

								   

							      
							          

								// $sheet->getStyle('B15:B28')->applyFromArray($style_left);
								
								// $sheet->getStyle('E'.$i)->applyFromArray($style_special);																       

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				
								 
				$objPHPExcel->setActiveSheetIndex(0); 

				$writer = new PHPExcel_Writer_Excel2007($objPHPExcel);

				$name = 'Cnssdetail'.date("dmY").rand(0,100000).'.xlsx';

				$lien = 'http://'.$_SERVER['SERVER_NAME'].''.BASE_URL.'/webroot/doc/'.$name;

				$this->set('lien',$lien);

				$fichiername = WEBROOT.DS.'doc/'.$name;

				$writer->save($fichiername);
		}



		public function getboa($paie_id){
			//id boa
			$banque_id = 3;
			$liste = $this->Paie->getEtatbybanque($paie_id,$banque_id);

			//$contenu = '';
			$h = fopen(WEBROOT.DS."/doc/banque/boa.txt", "r+");
			fseek($h, 0);

			foreach ($liste as $key => $value) {
				 
				 $espacenom = str_repeat(" ",40-iconv_strlen($value->nom.' '.$value->prenom));

				 $ville = 'COTONOU     ';

				 $date = date("Ymd").' ';

				 $compte = $value->numero_compte.'   ';

				 $montant = $value->salaire_net;
				 
				 $contenu = $value->nom.' '.$value->prenom.$espacenom.$ville.$date.$compte.$montant."\r\n";

				 fputs($h, $contenu);

				 
			}

		 
			 
			// on ouvre le fichier en écriture avec l'option a
			// il place aussi le pointeur en fin de fichier (il tentera de créer aussi le fichier si non existant)
			 
			
			fclose($h);

			//$lien = 'http://'.$_SERVER['SERVER_NAME'].''.BASE_URL.'/webroot/doc/banque/boa.txt';
			$lien = 'http://'.$_SERVER['SERVER_NAME'].''.BASE_URL.'/paies/download/boa';


		    $this->set('lien',$lien);
		    $this->set('libelle','ORDRE BOA');

		}



		public function download($nom){

			  $lien ='http://'.$_SERVER['SERVER_NAME'].''.BASE_URL.'/webroot/doc/banque/'.$nom.'.txt' ;
			  $nom = BASE_URL.'/webroot/doc/banque/'.$nom.'.txt';
			  $this->set("header",1);
			  $this->set("nom",$nom);
			  $this->set("lien",$lien);
				 
  		 
		}


}
 ?>