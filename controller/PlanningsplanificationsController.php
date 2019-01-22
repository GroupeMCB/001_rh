<?php 

/**
* Controleur planification
*/
class planifications extends Controller
{

	private $mylog;

	/**
	*	Constructeur de la classe Planification
	*
	**/
	function __construct()
	{
		$this->LoadModel('Planification');

		if (!class_exists('logs')) {
			require(ROOT.'controllers/logs.php');
		}
		
		$this->mylog = new logs;
	}

	/**
	* Permet de génerer les lignes d'un planning
	* 
	* @param $idplanning identifiant du planning
	* @param $allPostInfos les données du planning à enregistrer
	* @param $permissionsDate la liste des permissions qui sont comprises entre la période du planning
	* @param $permissionsPeriode la liste des periodes de permissions qui sont comprises entre la période du planning
	* @param $vacSpcPeriode la liste des vacations spéciales qui sont comprises entre la période du planning
	* @param $idCampagne identifiant de la campagne
	*
	* @return 
	*
	**/
	public function generatePlanification($idplanning, $allPostInfos, $permissionsDate, $permissionsPeriode, $vacSpcPeriode, $idCampagne)
	{

		if (!class_exists('Agent')) {
			require_once(ROOT.'models/agent.php');
		}

		$agent = new Agent;

		if (!class_exists('Cellule')) {
			require_once(ROOT.'models/cellule.php');
		}

		$cellule = new Cellule;

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		$jourDeLaSemaine = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");

		// On récupère les infos sur le précédent planning de la cellule courante
		$theLastPlanning = $this->getLastPlanning($allPostInfos['selectedCel']);

		if ($theLastPlanning) {
			// theLastPlanningId Identifiant du dernier planning de la cellule courante
			$theLastPlanningId = $theLastPlanning['Planning_idPlanning'];
		}else{
			$theLastPlanningId = false;
		}
		
		// Liste de tous les agents de la cellule en cours
		$allCelAgent = $cellule->getCelluleAgent($allPostInfos['selectedCel'], "cellule_has_agent");

		//////////////////////////////////////////////////////////////////////////
		//																		//
		//	Appel de la fonction qui traite les cas des vacations spéciales. 	//
		//																		//
		//////////////////////////////////////////////////////////////////////////
		$newsEffectif = $this->vacSpPlanning($idplanning, $vacSpcPeriode, $allPostInfos, $theLastPlanningId, $allCelAgent);
		/* 
			On redéfinit l'effectif total des agents de la cellule 
		*/ 
		$allCelAgent = $newsEffectif['allCelAgent'];

		/* 
			On redéfinit les nouveaux effectifs pour les différentes vacations 
		*/
		$allPostInfos['effectifVac'] = $newsEffectif['newsEffectifVac'];

		//////////////////////////////////////////////////////////////////////////
		//																		//
		//	On traite avant tout le cas de la vacation de nuit. 				//
		//																		//
		//////////////////////////////////////////////////////////////////////////
		foreach ($allPostInfos['effectifVac'] as $key4 => $value4) {

			// On récupère le libellé de la vacation courante
			$currentVactName = $vacation->getVacation($key4);
			$currentVactName = $currentVactName[0];

			if ($currentVactName['niveau'] == 3) {

				// on récupère les informations par rapport à la vacation
				$vacationInfo = $vacation->getVacation($key4);

				$nuitNbrAgentToPlan = array();

				// On vérifie si il y a déjà eu un planning pour la cellule
				if ($theLastPlanningId) {

					// On récupère la liste des agents qui ont déjà fait la nuit
					$agentDoneNuit = $this->getAgentAvailableForVac($allPostInfos['effectifVac'], $key4, $theLastPlanningId);

					if ($agentDoneNuit) { // Si il y a une planification pour la nuit

						// On exclut les agents qui ont déjà fait la nuit
						$agentNotDoneNuitId = $this->customArrayFilter($allCelAgent, $agentDoneNuit);

						if (sizeof($agentNotDoneNuitId) > $value4) {

							/* 
								L'effectif demandé est inférieur ou également au nombre d'agents n'ayant pas encore fait la nuit,
								On prend alors juste le nombre d'agent souhaité
							*/
							$nuitNbrAgentToPlan = $this->array_random($agentNotDoneNuitId, $value4);

						} else {
							/*
								L'effectif demandé est supérieur au nombre d'agent n'ayant pas encore fait la nuit,
								On prendre le nombre qu'on a, et on complète avec les premiers agents ayant fait la nuit.
								(cela signifie que le cycle de rotation dans la vacation de nuit est fini)

								Ensuite on reprend un nouveau cycle de semaine pour la table nuitcheck
							*/

							$theRest = $value4 - sizeof($agentNotDoneNuitId);

							if ($theRest != 0) {
								
								$theRestAgent = $this->Planification->getAgentNuit(array(
								'order' => 'id ASC',
								'limit'	=> $theRest
								));

								$nuitNbrAgentToPlan = array_merge($agentNotDoneNuitId, $theRestAgent);

							} else {
								$nuitNbrAgentToPlan = $agentNotDoneNuitId;
							}

							/*
								On vide la table nuitcheck si :
								1 - si l'effectif demandé pour la nuit est supérieur
								2 - si tout le monde a déjà fait la nuit 
							*/
							$this->Planification->viderTable("nuitcheck");
						}

					} else {

						/* Dans le cas où il n'y a pas encore de planification pour la nuit */

						if (sizeof($allCelAgent) <= $value4) 
						{
							// On prend juste le nombre d'agents souhaité
							$nuitNbrAgentToPlan = $allCelAgent;
						} else {
							
							// On prend juste le nombre d'agents souhaité
							$nuitNbrAgentToPlan = $this->array_random($allCelAgent, $value4);
						}						
					}
					
				} else {

					/* Dans le cas où il n'y a pas encore de planning pour cette cellule */

					if (sizeof($allCelAgent) <= $value4) 
					{
						// On prend juste le nombre d'agents souhaité
						$nuitNbrAgentToPlan = $allCelAgent;
					} else {
						
						// On prend juste le nombre d'agents souhaité
						$nuitNbrAgentToPlan = $this->array_random($allCelAgent, $value4);
					}
				}

				$observations = "";

				foreach ($nuitNbrAgentToPlan as $key6 => $value6) {

					if ($theLastPlanningId) {

						// On récupère le jour de repos à accorder à l'agent en fonction de la rotation des jours de repos
						$jourDeReposAgent = $this->getReposDay($value6['Agent_idAgent'], $theLastPlanningId);
					} else {
						$jourDeReposAgent = 1;
					}
					
					$details = array();
					// constitution de la partie détail
					foreach ($jourDeLaSemaine as $keyj => $valuej) {

						// Insertion du jour de repos de l'agent dans le détails
						if ($jourDeReposAgent == $keyj+1) {
							$details[$jourDeReposAgent]['start'] 	= "Repos";
							$details[$jourDeReposAgent]['end'] 		= "Repos";
						} else {
							$details[$keyj+1]['start'] 	= $vacationInfo[0]['heureDebut'];
							$details[$keyj+1]['end'] 	= $vacationInfo[0]['heureFin'];
						}
					}

					// On récupère les jours de permissions de l'agent
					$agentPermissions = $this->checkHavePermission($value6['Agent_idAgent'], $permissionsDate);

					// Transforme la date ($allPostInfos['periodeDebut']) en anglais
					$tempdate = explode('-', $allPostInfos['periodeDebut']);
					$ladateD = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

					if ($agentPermissions) {

						foreach ($agentPermissions as $value) {

							$current = strtotime($ladateD);
	                        
	                        foreach ($jourDeLaSemaine as $key2 => $value2) {

	                        	if (strtotime($value['ladate']) == $current) {

									$details[$key2+1]['start'] = $value['motif'];
									$details[$key2+1]['end'] = $value['motif'];
								}

								$current = strtotime('+1 day', $current);
	                        }							
						}
					}

					// On vérifie également si l'agent à prit une période de permission
					foreach ($permissionsPeriode as $keyp => $valuep) {

						foreach ($valuep as $keyp2 => $valuep2) {

							if ($valuep2['Agent_idAgent'] == $value6['Agent_idAgent']) {

								// Transforme la date ($allPostInfos['periodeDebut']) en anglais
								$tempdate = explode('-', $allPostInfos['periodeDebut']);
								$periodeDebut = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

								$tempdate = explode('-', $allPostInfos['periodeFin']);
								$periodeFin = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

								switch ($keyp) {

									case 'startIn':
										
										$current = strtotime($valuep2['periodeDebut']);
										$periodePlanning = strtotime($periodeDebut);
	                        
				                        foreach ($jourDeLaSemaine as $keyp3 => $valuep3) {

				                        	if ($periodePlanning == $current) {

												$details[$keyp3+1]['start'] = $valuep2['motif'];
												$details[$keyp3+1]['end'] = $valuep2['motif'];

												// $current = strtotime('+1 day', $current);
											}
											
											$periodePlanning = strtotime('+1 day', $periodePlanning);
				                        }

										break;

									case 'allIn':
										
										$current = strtotime($valuep2['periodeFin']);
										$current2 = strtotime($valuep2['periodeDebut']);
										$periodePlanning = strtotime($periodeDebut);
	                        			
	                        			$nboucle = $this->nbJours($valuep2['periodeDebut'], $valuep2['periodeFin']);

				                        foreach ($jourDeLaSemaine as $keyp3 => $valuep3) {

				                        	if ($periodePlanning == $current2) {

				                        		for ($ai=$keyp3; $ai <= $nboucle+$keyp3; $ai++) { 
				                        			
				                        			$details[$ai+1]['start'] = $valuep2['motif'];
													$details[$ai+1]['end'] = $valuep2['motif'];
													
				                        		}

				                        		break;
											}

											$periodePlanning = strtotime('+1 day', $periodePlanning);
											
				                        }

										break;

									case 'endIn':

										$current = strtotime($valuep2['periodeFin']);
										$periodePlanning = strtotime($periodeDebut);
	                        
				                        $nboucle = $this->nbJours($periodeDebut, $valuep2['periodeFin']);

										for ($ei=0; $ei <= $nboucle; $ei++) { 

											$details[$ei+1]['start'] = $valuep2['motif'];
											$details[$ei+1]['end'] = $valuep2['motif'];
										}

										break;

									default:
										# code...
										break;
								}
							}
						}
					}

					ksort($details);

					$details = serialize($details);

					$data = array(
						"details" 				=> $details,
						"observations" 			=> mysql_real_escape_string($observations),
						"Planning_idPlanning" 	=> $idplanning,
						"Agent_idAgent" 		=> $value6['Agent_idAgent'],
						"Jours_idJours" 		=> $jourDeReposAgent,
						"Vacation_idVacation" 	=> $key4,
						);

					$result = $this->Planification->add($data);

					/*
						On enregistre les agents planifié dans la table nuitcheck
					*/
					$resultNuitCheck = $this->Planification->findInTable(
						array(
						'conditions'	=> 'Cellule_idCellule='.$allPostInfos['selectedCel'],
						'limit' 		=> 1)
					, "nuitcheck");

					$retVal = ($resultNuitCheck) ? $resultNuitCheck[0]['numSemaine'] + 1 : 1 ;

					$donnees = array(
						"Agent_idAgent"			=> $value6['Agent_idAgent'],
						"Planning_idPlanning"	=> $idplanning,
						"Cellule_idCellule"		=> $allPostInfos['selectedCel'],
						"numSemaine"			=> $retVal);

					$result2 = $this->Planification->addToTable($donnees, "nuitcheck");
				
				} // 

				$nuitNbrAgentToPlanId = array();

				// On récupère les Id uniquement
				foreach ($nuitNbrAgentToPlan as $value7) {
					array_push($nuitNbrAgentToPlanId, $value7['Agent_idAgent']);
				}

				// On redéfinit la liste des agents en excluant les agents de la nuit qu'on vient de planifier
				$allCelAgent = $this->customArrayFilter($allCelAgent, $nuitNbrAgentToPlanId);

				// On enlève également la vacation de la nuit
				unset($allPostInfos['effectifVac'][$key4]);

			} // Fin boucle .if ($currentVactName == "Nuit")
			
		} // Fin boucle foreach ($allPostInfos['effectifVac'] as $key4 => $value4)

		//////////////////////////////////////////////////////////////////////////
		//																		//
		//	On parcours ensuite les autres vacations de la cellule;				//
		//																		//
		//////////////////////////////////////////////////////////////////////////

		foreach ($allPostInfos['effectifVac'] as $key => $value) {

			if ($value <= 0 || $value == "") {
				/* 
					Si l'effectif désiré est inférieur ou égal à zéro ou vide 
				 	on ne fait alors aucun traitement 
				*/
			} else {
				// on récupère les informations par rapport à une vacation
				$vacationInfo = $vacation->getVacation($key);
				
				// on va chercher l'identifiant de la vacation précédent la vacation courante
				$prevVacationId = $this->getPreviousVacation($vacationInfo, $allPostInfos['selectedCel']);

				// Prendre le planning en cours de création et chercher les planifications correspondantes
				$planPlanification = $this->Planification->getPlanification($idplanning);

				// Tableau devant accueillir la liste des agents qui ont été déjà insérer dans le planning courant
				$agentHasPlanId = array();

				// Liste des agents disponibles pour la vacation
				$agentAvailableForVac = array();

				// Liste des agents non encore planifiés
				$agentNotPlan = array();

				// Initialisation du tableau devant accueillir le nombre souhaité d'agents à planifier
				$nbrAgentToPlan = array();

				/* On vérifie si il y a déjà un planning précédent */
				if ($theLastPlanningId) {

					/* 
						Filtrer encore la liste des agents disponibles de la cellule 
						en fonction de la vacation en cours de programmation pour la rotation des agents dans 
						les différentes vacations.

						On récupère l'id des agents qui ont déjà fait cette vacation dans le planning précédent.
						Si c'est la vacation de la nuit on va récupérer dans le tableau nuitcheck ceux qui ont déjà fait
						le nuit pour les explure.

						Si le nombre de personnes demandé pour la vacation de nuit est supérieur au nombre de personnes
						qui n'ont pas encore fait la nuit, on prend ceux qui avait fait la nuit dans la première semaine qu'on
					*/

					$lastPlanVacAgent = $this->getAgentAvailableForVac($allPostInfos['effectifVac'], $key, $theLastPlanningId);

					if ($lastPlanVacAgent) {

						// On exclut les agents qui ont déjà fait cette vacation dans le planning précédent
						$agentAvailableForVac = $this->customArrayFilter($allCelAgent, $lastPlanVacAgent);

						/* 
							Prendre d'abord les agents 
							qui ont fait la vacation précédente dans le planning précédent
						*/
					
						var_dump($theLastPlanningId);
						var_dump($prevVacationId);

						$lastVacPlanAgent = $this->Planification->getForVacLastPlanificationAgent($theLastPlanningId, $prevVacationId);

						var_dump($lastVacPlanAgent);

						$filterLastVacPlanAgent = array();
						$lastVacPlanAgentId = array();
						$agentNotPlanFilter =  array();
						$agentAvailableForVacFilter = array();

						if ($planPlanification) {

							/* 
								Si il y a déjà une planification pour le planning, 
								on exclu les agents déjà planifiés.

								On récupère les id des agents 
								du planning de la vacation précédente dans le planning précédent 
							*/
							foreach ($lastVacPlanAgent as $valuef) {
								array_push($lastVacPlanAgentId, $valuef['Agent_idAgent']);
							}

							/* 
								On récupère l'id de tous les agents 
								qui figurent déjà dans le planning en cours de création 
							*/
							foreach ($planPlanification as $valueAgent) {
								array_push($agentHasPlanId, $valueAgent['Agent_idAgent']);
							}

							// On exclut les agents qui ont déjà été planifié de la liste des agents qui ont déjà fait cette vacation
							$agentNotPlan = $this->customArrayFilter($agentAvailableForVac, $agentHasPlanId);

							/* 
								On filtre également le tableau $lastVacPlanAgent 
								par rapport aux agents qui ont déjà été planifié 
							*/
							$filterLastVacPlanAgent = $this->customArrayFilter($lastVacPlanAgent, $agentHasPlanId);

							// on filtre la liste des agents non planifié avec le tableau $lastVacPlanAgent
							$agentNotPlanFilter = $this->customArrayFilter($agentNotPlan, $lastVacPlanAgentId);

							// si le nombre d'agents souhaité est suffisant
							if (sizeof($filterLastVacPlanAgent) >= $value) {

								$nbrAgentToPlan = $this->array_random($filterLastVacPlanAgent, $value);

							} else { // si le nombre d'agents souhaité est insuffisant						

								$rest = $value - sizeof($lastVacPlanAgent);

								/* 
									Faire une autre vérification sur l'effectif du tableau $agentNotPlanFilter
									avant de passer le parametre $rest à la fonction array_random
								*/

								$rest = (sizeof($agentNotPlanFilter) < $rest) ? sizeof($agentNotPlanFilter) : $rest ;
								
								$reserve = $this->array_random($agentNotPlanFilter, $rest);

								$nbrAgentToPlan = array_merge($filterLastVacPlanAgent, $reserve);
							}

						} else {

							/* 
								Dans le cas où il y a pas encore de planification pour ce planning

								On récupere les id des agents 
								du planning de la vacation précédente dans le planning précédent
							*/

							foreach ($lastVacPlanAgent as $valuef) {
								array_push($lastVacPlanAgentId, $valuef['Agent_idAgent']);
							}

							/* 
								on filtre la liste des agents disponible pour cette vacation
							 	avec le tableau $lastVacPlanAgent 
							*/
							$agentAvailableForVacFilter = $this->customArrayFilter($agentAvailableForVac, $lastVacPlanAgentId);

							if (sizeof($lastVacPlanAgent) >= $value) {

								$nbrAgentToPlan = $this->array_random($lastVacPlanAgent, $value);

							} else {							

								$rest = $value - sizeof($lastVacPlanAgent);

								/* 
									Faire  une autre vérification sur l'effectif du tableau $agentNotPlanFilter
									avant de passer le parametre $rest à la fonction array_random
								*/

								$rest = (sizeof($agentAvailableForVacFilter) < $rest) ? sizeof($agentAvailableForVacFilter) : $rest ;
								
								$reserve = $this->array_random($agentAvailableForVacFilter, $rest);

								$nbrAgentToPlan = array_merge($lastVacPlanAgent, $reserve);
							}
						}

					} else {
						// cas impossible
						// die();
					}
					
				} else{ // si il n'y a pas un planning précédent

					if ($planPlanification) {
						/* 
							si il y a déjà une planification pour le planning, 
							on exclu les agents déjà planifiés
						*/

						// on récupère l'id de tous les agents qui figurent déjà dans le planning en cours de création
						foreach ($planPlanification as $valueAgent) {
							array_push($agentHasPlanId, $valueAgent['Agent_idAgent']);
						}

						// On exclut les agents qui ont déjà été planifié
						$agentNotPlan = $this->customArrayFilter($allCelAgent, $agentHasPlanId);

						if (sizeof($agentNotPlan) <= $value) 
						{
							// On prend maintenant juste le nombre d'agent souhaité
							$nbrAgentToPlan = $this->array_random($agentNotPlan, sizeof($agentNotPlan));
						} else {
							
							// On prend maintenant juste le nombre d'agent souhaité
							$nbrAgentToPlan = $this->array_random($agentNotPlan, $value);
						}

					} else{

						/* dans le cas où il y a pas encore de planification pour ce planning */

						if (sizeof($allCelAgent) <= $value) 
						{
							// On prend maintenant juste le nombre d'agent souhaité
							$nbrAgentToPlan = $this->array_random($allCelAgent, sizeof($agentNotPlan));
						} else {
							
							// On prend maintenant juste le nombre d'agent souhaité
							$nbrAgentToPlan = $this->array_random($allCelAgent, $value);
						}
					}
				}

				// les observations vide pour une première fois
				$observations = "";

				// On rempli les différents champs de la table planification et on sauvegarde la ligne
				foreach ($nbrAgentToPlan as $key2 => $valuea) {

					if ($theLastPlanningId) {

						// On récupère le jour de repos à accorder à l'agent en fonction de la rotation
						$jourDeReposAgent = $this->getReposDay($valuea['Agent_idAgent'], $theLastPlanningId);
					} else {
						
						$jourDeReposAgent = 1;
					}
					
					$details = array();
					// constitution de la partie détail
					foreach ($jourDeLaSemaine as $keyj => $valuej) {

						// Insertion du jour de repos de l'agent dans le détails
						if ($jourDeReposAgent == $keyj+1) {
							$details[$jourDeReposAgent]['start'] = "Repos";
							$details[$jourDeReposAgent]['end'] = "Repos";
						} else{
							$details[$keyj+1]['start'] = $vacationInfo[0]['heureDebut'];
							$details[$keyj+1]['end'] = $vacationInfo[0]['heureFin'];
						}

						// On récupère les jours de permissions de l'agent
						$agentPermissions = $this->checkHavePermission($valuea['Agent_idAgent'], $permissionsDate);

						// Transforme la date ($allPostInfos['periodeDebut']) en anglais
						$tempdate = explode('-', $allPostInfos['periodeDebut']);
						$ladateD = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

						if ($agentPermissions) {

							foreach ($agentPermissions as $value) {

								$current = strtotime($ladateD);
		                        
		                        foreach ($jourDeLaSemaine as $key2 => $value2) {

		                        	if (strtotime($value['ladate']) == $current) {

										$details[$key2+1]['start'] = $value['motif'];
										$details[$key2+1]['end'] = $value['motif'];
									}

									$current = strtotime('+1 day', $current);
		                        }								
							}
						}
					}

					// On vérifie également si l'agent à prit une période de permission
					foreach ($permissionsPeriode as $keyp => $valuep) {

						foreach ($valuep as $keyp2 => $valuep2) {

							if ($valuep2['Agent_idAgent'] == $valuea['Agent_idAgent']) {

								// Transforme la date ($allPostInfos['periodeDebut']) en anglais
								$tempdate = explode('-', $allPostInfos['periodeDebut']);
								$periodeDebut = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

								$tempdate = explode('-', $allPostInfos['periodeFin']);
								$periodeFin = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

								switch ($keyp) {
									case 'startIn':
										
										$current = strtotime($valuep2['periodeDebut']);
										$periodePlanning = strtotime($periodeDebut);
	                        
				                        foreach ($jourDeLaSemaine as $keyp3 => $valuep3) {

				                        	if ($periodePlanning <= $current) {

												$details[$keyp3+1]['start'] = $valuep2['motif'];
												$details[$keyp3+1]['end'] = $valuep2['motif'];

												// $current = strtotime('+1 day', $current);
											}
											
											$periodePlanning = strtotime('+1 day', $periodePlanning);
				                        }

										break;

									case 'allIn':
										
										$current = strtotime($valuep2['periodeFin']);
										$current2 = strtotime($valuep2['periodeDebut']);
										$periodePlanning = strtotime($periodeDebut);
	                        		
										$nboucle = $this->nbJours($valuep2['periodeDebut'], $valuep2['periodeFin']);

				                        foreach ($jourDeLaSemaine as $keyp3 => $valuep3) {

				                        	if ($periodePlanning == $current2) {

				                        		for ($ai=$keyp3; $ai <= $nboucle+$keyp3; $ai++) { 
				                        			
				                        			$details[$ai+1]['start'] = $valuep2['motif'];
													$details[$ai+1]['end'] = $valuep2['motif'];
				                        		}

				                        		break;
											}

											$periodePlanning = strtotime('+1 day', $periodePlanning);											
				                        }

										break;

									case 'endIn':

										$current = strtotime($valuep2['periodeFin']);
										$periodePlanning = strtotime($periodeDebut);

										$nboucle = $this->nbJours($periodeDebut, $valuep2['periodeFin']);

										for ($ei=0; $ei <= $nboucle; $ei++) { 

											$details[$ei+1]['start'] = $valuep2['motif'];
											$details[$ei+1]['end'] = $valuep2['motif'];
										}

										break;

									default:
										# code...
										break;
								}
							}
						}
					}

					ksort($details);
					$details = serialize($details);

					$data = array(
						"details" 				=> $details,
						"observations" 			=> mysql_real_escape_string($observations),
						"Planning_idPlanning" 	=> $idplanning,
						"Agent_idAgent" 		=> $valuea['Agent_idAgent'],
						"Jours_idJours" 		=> $jourDeReposAgent,
						"Vacation_idVacation" 	=> $key,
						);

					$result = $this->Planification->add($data);
					
				} // fin boucle for pour l'insertion de la ligne de chaque agent
				
			} // Fin bloc If pour la vérification sur les effectifs
		
		} // fin boucle foreach pour chaque vacation


		$data['insertPlanningId'] = $idplanning;
		$this->set($data);

		$message['succes'] = "Planning géneré avec succès !";
		$message['infosPlus'] = "Si l'effectif souhaité n'est pas obtenu vous avez la possiblité d'ajouter d'autres agents depuis le buton <b>Options > Ajouter des agents</b>";
		
		$this->set($message);

		$this->veiwPlanification($idplanning, $idCampagne);
		
	}

	/**
	* Permet de récupérer le jour de repos de l'agent dans le planning précédent
	*
	* @param $idAgent identifiant de l'agent
	* @param $idplanning identifiant du planning précédent
	*
	* @return $jourDeRepos l'identifiant du jour de repos 
	* 			ou 1 si l'agent n'a jamais été planifié au paravant
	**/
	public function getReposDay($idAgent, $idplanning) {

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		if ($idplanning) {
			
			$result = $this->Planification->getPlanificationChamp($idplanning, $idAgent);

			if ($result) { // si l'agent a été planifié une fois

				// On récupère l'identifiant de la vacation dans la planification précédente de l'agent
				$lastAgentVacId = $result[0]['Vacation_idVacation'];

				/* 
					On vérifie si la dernière vacation de l'agent était la nuit 
					Si c'est le cas il a automatiquement le lundi comme jour de repos 
				*/
				$vacType = $vacation->getVacation($lastAgentVacId);

				$vacType = $vacType[0]['niveau'];

				if ($vacType == 3) { 
					/* 
						Si l'agent avait fait la vacation de nuit 
						il prends le lundi comme jour de repos dans la prochaine vacation
					*/
					$jourDeRepos = 1;
					return $jourDeRepos;
					
				} else {

					// On récupère l'identifiant du jour de repos
					$resultJr = $result[0]['Jours_idJours'];

					if ($resultJr == 7) { // si son dernier jour de repos était le dimanche

						$jourDeRepos = 1;
						return $jourDeRepos;

					}else{
						
						$jourDeRepos = $resultJr + 1;

						return $jourDeRepos;
					}
				}
				
			} else {

				/* si l'agent n'a jamais été planifié il a le lundi par défaut comme jour de repos */
				return $jourDeRepos = 1;
			}
			

		} else {
			return $jourDeRepos = 1;
		}		
	}

	/**
	* Permet de récupérer uniquement les agents qui sont affectés à une cellule donnée
	* et qui n'ont pas encore été planifié
	*
	* @param $idCellule identifiant de la cellule concernée
	* @param $agentAlreadyPlanId liste des agents qui ont déjà été planificé
	*
	* @return $listAgentAvailable la liste des agents de la cellule qui n'ont pas encore été planifié
	* 		  ou false si il n'y a aucun agent affecté à cette cellule
	**/
	public function getCelAgent($idCellule, $agentAlreadyPlanId) {

		if (!class_exists('Cellule')) {
			require_once(ROOT.'models/cellule.php');
		}

		$cellule = new Cellule;

		// on récupère la liste de tous les agents de la cellule
		$allAgentCel = $cellule->getCelluleAgent($idCellule, "cellule_has_agent");

		if ($allAgentCel) {

			$listAgentAvailable = array();

			// On récupère les agents qui ne figurent pas encore dans le planning en cours de
			for ($i=0; $i < sizeof($allAgentCel); $i++) {

				if (!in_array($allAgentCel[$i]['id'], $agentAlreadyPlanId))
				{
					array_push($listAgentAvailable, $allAgentCel[$i]);
				}
			}

			return $listAgentAvailable;
		}else{
			return $listAgentAvailable = fasle;
		}
		
	}

	/**
	* Permet de récupérer l'identifiant du précédent planning d'une cellule
	*
	* @param $dCellule identifiant de la cellule
	* @return $lastPlanning l'identifiant du précédent planning 
	*			ou fasle si la cellule n'a jamais été planificé
	**/
	public function getLastPlanning($idCellule) {

		$allCelPlanning = $this->Planification->getPlanningCel($idCellule, "planning_has_cellule");

		if (sizeof($allCelPlanning) >= 2) { // Si il y a plus de deux(2) plannings pour la cellule
			$lastPlanning = $allCelPlanning[1];
		}else{
			$lastPlanning = false;
		}

		return $lastPlanning;
	}

	/**
	* Permet de récupérer la liste des agents qui ont été planifié pour la vacation courante
	* dans le planning précédent
	*
	* @param $allCelVacation toutes les vacations de la cellule concernée
	* @param $idCurrentVacation l'identifiant de la vacation courante
	* @param $idLastPlanning identifiant du dernier planning de la cellule concernée
	*
	* @return 
	*
	**/
	public function getAgentAvailableForVac($allCelVacation, $idCurrentVacation, $idLastPlanning) {	

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		// On récupère le libellé de la vacation courante
		$currenteVactName = $vacation->getVacationName($idCurrentVacation);

		// On récupère ensuite le libéllé de toutes les vacations de la cellule
		foreach ($allCelVacation as $key => $value) {
			$tempRequete = $vacation->getVacationName($idCurrentVacation);
			$AllVacationName[$key] = $tempRequete[0];
		}

		/* 
			Pour chaque vacation on cherche les agents qui ont déjà fait l'objet d'une planification 
			dans la vacation courante dans le planning précédent 
		*/
	
		if (strtolower($currenteVactName) === "nuit") {
			// On récupère les personnes à explure de la vacation de nuit
			$allAgentForLastCurrentVac = $this->Planification->getAgentNuit();
		} else {
			/* 
				On fonction de la vacation courante,
				on va chercher les agents qui ont fait cette vacation courante dans le planning précédent 
			*/
			$allAgentForLastCurrentVac = $this->Planification->getForVacLastPlanificationAgent($idLastPlanning, $idCurrentVacation);
		}

		// On récupère uniquement les identifiants
		if ($allAgentForLastCurrentVac) {

			$allAgentForLastCurrentVacId = array();

			foreach ($allAgentForLastCurrentVac as $key => $value) {

				array_push($allAgentForLastCurrentVacId, $value['Agent_idAgent']);
			}

			return $allAgentForLastCurrentVacId;
		} 
		else {
			return $allAgentForLastCurrentVacId = false;
		}
	}

	/**
	* Permet de faire des plannings manuels
	*
	* @param les parametres sont envoyés par la methode POST
	*
	**/
	public function planningManuel() {

		if (!class_exists('Planning')) {
			require_once(ROOT.'models/planning.php');
		}

		$planning = new Planning;

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		$jourDeReposAgent = '';

		// Les jours de la semaine
		$jourDeLaSemaine = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");

		// On récupère les infos sur le précédent planning de la cellule courante
		$theLastPlanning = $this->getLastPlanning($_POST['celId']);

		if ($theLastPlanning) {
			// theLastPlanningId Identifiant du dernier planning de la cellule courante
			$theLastPlanningId = $theLastPlanning['Planning_idPlanning'];
		}else{
			$theLastPlanningId = false;
		}

		$dateCoupee = explode('-', $_POST['periodeDebut']);

		$infosPlanning = array(
			'periodeDebut'      => $_POST['periodeDebut'],
			'periodeFin'        => $_POST['periodeFin'],
			'numSemaine'        => date("W", mktime(0,0,0, $dateCoupee[1], $dateCoupee[0], $dateCoupee[2])),
			'dateEdition'       => date('j-m-Y, H:i:s'),
			'niveauPlanning'    => 0,
			'commentaire'       => '',
			'idAuteur'          => $_SESSION['userid'],
			'idCampagne'        => $_POST['idCampagne'],
			'Cellule_idCellule' => $_POST['celId'],
		);

		$idplanning = $planning->add($infosPlanning);

		$planningCel = array(
			"Planning_idPlanning" => $idplanning, 
			"Cellule_idCellule" => $_POST['celId']
		);

		$addResult = $planning->addToTable($planningCel, "planning_has_cellule");

		foreach ($_POST['selectedVac'] as $key => $value) {

			if ($value[key($value)] != "") { // On enregistre que uniquement les agents planifié

				if ($theLastPlanningId) {
					// On récupère le jour de repos à accorder à l'agent en fonction de la rotation des jours de repos
					$jourDeReposAgent = $this->getReposDay(key($value), $theLastPlanningId);
				} else {
					$jourDeReposAgent = 1;
				}

				// on récupère les informations par rapport à la vacation
				$vacationInfo = $vacation->getVacation($value[key($value)]);

				$details = array();
				// constitution du tableau détails
				foreach ($jourDeLaSemaine as $keyj => $valuej) {

					// Insertion du jour de repos de l'agent dans le détails
					if ($jourDeReposAgent == $keyj+1) {
						$details[$jourDeReposAgent]['start'] 	= "Repos";
						$details[$jourDeReposAgent]['end'] 		= "Repos";
					} else {
						$details[$keyj+1]['start'] 	= $vacationInfo[0]['heureDebut'];
						$details[$keyj+1]['end'] 	= $vacationInfo[0]['heureFin'];
					}
				}

				$infosPlanification = array(
					'details'             => serialize($details),
					'observations'        => '',
					'Planning_idPlanning' => $idplanning,
					'Agent_idAgent'       => key($value),
					'Jours_idJours'       => $jourDeReposAgent,
					'Vacation_idVacation' => $value[key($value)],
				);

				$result = $this->Planification->add($infosPlanification);
			}
		}

		$data['insertPlanningId'] = $idplanning;
		$this->set($data);

		$message['succes'] = "Planning créér avec succès !";
		
		$this->set($message);

		$this->veiwPlanification($idplanning, $_POST['idCampagne']);

	}

	/**
	* Permet de faire des plannings manuels pour l'administration (planning classique)
	*
	* @param les parametres sont envoyés par la methode POST
	*
	**/
	public function planningManuelAdmin() {

		if (!class_exists('Agent')) {
			require_once(ROOT.'models/agent.php');
		}

		$agent = new Agent;

		if (!class_exists('Planning')) {
			require_once(ROOT.'models/planning.php');
		}

		$planning = new Planning;

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		if (!class_exists('configurations')) {
			require_once(ROOT.'controllers/configurations.php');
		}

		$configurations = new configurations;

		$idplanning = 0;

		$planningDepartement = $planning->getCampPlanning($_POST['idDepartement']);

		$planningDepartement = $planningDepartement[0]; // on prendre le dernier planning de la departement

		if (isset($_POST['editPlanningForm'])) { // si c'est le formulaire d'edition

			if ( $planningDepartement == NULL || $planningDepartement['niveauPlanning'] == 1 ) { // pas encore de planning pour ce departement

				$infosPlanning = array(
					'periodeDebut'      => 0,
					'periodeFin'        => 0,
					'numSemaine'        => 0,
					'dateEdition'       => 0,
					'niveauPlanning'    => -1,
					'commentaire'       => '',
					'idAuteur'          => $_SESSION['userid'],
					'idCampagne'        => $_POST['idDepartement'],
					'Cellule_idCellule' => $_POST['idCellule'],
				);

				$idplanning = $planning->add($infosPlanning);

			}elseif($planningDepartement['niveauPlanning'] < 0 ) {
				$idplanning = $planningDepartement['id']; // On recupere l'id du planning en cours				
			}

			if (isset($_POST['applyForAll']) AND $_POST['applyForAll'] == "on") { // On boucle si c'est les memes horaires a appliquer a tout le monde

				if (!class_exists('Agent')) {
					require_once(ROOT.'models/agent.php');
				}

				$agent = new Agent;
				// On récupère les id des agents de la cellule concernée
				$agentDepartement = $agent->getCelAgent($_POST['idCellule'], 'cellule_has_agent');
				foreach ($agentDepartement as $value) {
					// appel de la fonction de formatage des horaires
					$infosPlanification = $this->formatedHoraires($idplanning, $_POST['horaires'], $value['Agent_idAgent']);

					$result = $this->Planification->add($infosPlanification);
				}

			}else{

				//appel de la fonction de formatage des horaires
				$infosPlanification = $this->formatedHoraires($idplanning, $_POST['horaires'], $_POST['idAgent']);
				$result = $this->Planification->add($infosPlanification);
			}

			// on recupere les informations sur le planning
			$infoPlanification = $this->Planification->getPlanification($idplanning);

			/* 
				On désérialise le champ détails et on récupère par la même occasion les infos sur les agents
				concernés par la planification encours d'utilisation 
			*/

			for ($i=0; $i < sizeof($infoPlanification) ; $i++) {
				
				$infoPlanification[$i]['details'] = unserialize($infoPlanification[$i]['details']);
				$tempReq = $agent->getAgent($infoPlanification[$i]['Agent_idAgent']);
				$tempReq = $tempReq[0];
				$infoPlanification[$i]['Agent_idAgent'] = $tempReq;
			}

			$configurations->planningManuel($_POST['idDepartement'], $_POST['idCellule'], $infoPlanification);

		}elseif(isset($_POST['formEnregistrement'])){ // si c'est le formulaire d'enregistrement

			$idplanning = $planningDepartement['id']; // On recupere l'id du planning en cours

			$dateCoupee = explode('-', $_POST['periodeDebut']);
			$infosPlanning = array(
				'id'             => $idplanning,
				'periodeDebut'   => $_POST['periodeDebut'],
				'periodeFin'     => $_POST['periodeFin'],
				'numSemaine'     => date("W", mktime(0,0,0, $dateCoupee[1], $dateCoupee[0], $dateCoupee[2])),
				'dateEdition'    => date('j-m-Y, H:i:s'),
				'niveauPlanning' => 1
			);

			$planning->add($infosPlanning); // on enregistrement le planning

			$planningCel = array(
				"Planning_idPlanning" => $idplanning, 
				"Cellule_idCellule" => $_POST['idCellule']
			);

			$addResult = $planning->addToTable($planningCel, "planning_has_cellule");
		
			$data['insertPlanningId'] = $idplanning;
			$this->set($data);

			$message['succes'] = "Planning créér avec succès !";		
			$this->set($message);
			$this->veiwPlanification($idplanning, $_POST['idDepartement'], "standard");
		}

	}

	/**
	* Permet d'afficher une planification en consultation et en modification
	*
	* @param $idplanification l'identifiant du planning à consulter
	* @param $idCampagne l'identifiant de la campagne concerner
	* @param $typePlanninig le type de planning a afficher
	*
	* @return 
	**/
	public function veiwPlanification($idplanification, $idCampagne, $typePlanninig = "campagne") {

		if ($_SESSION['userpermission']['permissions_planning']['view']) {
			
			if (!class_exists('Agent')) {
				require_once(ROOT.'models/agent.php');
			}

			$agent = new Agent;

			// on recupere les informations sur le planning
			$infoPlanification = $this->Planification->getPlanification($idplanification);

			if (!class_exists('Vacation')) {
				require_once(ROOT.'models/vacation.php');
			}

			$vacation = new Vacation;

			if (!class_exists('Planning')) {
				require_once(ROOT.'models/planning.php');
			}

			$planning = new Planning;

			if (!class_exists('Cellule')) {
				require_once(ROOT.'models/cellule.php');
			}

			$cellule = new Cellule;

			if ($infoPlanification) {
				
				/* 
					On désérialise le champ détails et on récupère par la même occasion les infos sur les agents
					concernés par la planification encours d'utilisation 
				*/

				for ($i=0; $i < sizeof($infoPlanification) ; $i++) {
					
					$infoPlanification[$i]['details'] = unserialize($infoPlanification[$i]['details']);
					$tempReq = $agent->getAgent($infoPlanification[$i]['Agent_idAgent']);
					$tempReq = $tempReq[0];
					$infoPlanification[$i]['Agent_idAgent'] = $tempReq;

					$tempHistoriquePlanning = $this->planningHistory($infoPlanification[$i]['Agent_idAgent']['id']);

					$infoPlanification[$i]['historiquePlanning'] = $tempHistoriquePlanning;
				}

				$planVac = array();

				$celPlanning = $planning->getPlanningCel($infoPlanification[0]['Planning_idPlanning'], "planning_has_cellule");

				$data['celInfoos'] = $cellule->getCelluleName($celPlanning[0]['Cellule_idCellule']);

				$data['celInfoosId'] = $celPlanning[0]['Cellule_idCellule'];

				$celVacList = $vacation->getCelVacation($celPlanning[0]['Cellule_idCellule'], 'cellule_has_vacation');

				if ($typePlanninig == "campagne") {
					
					// On récupère l'identifiant des vacations de la cellules concernée
					foreach ($celVacList as $keyvl => $valuevl) {
						if (!in_array($valuevl['Vacation_idVacation'], $planVac)) {
							array_push($planVac, $valuevl['Vacation_idVacation']);
						}			
					}

					// on récupère les infos sur les vacations concernées
					foreach ($planVac as $keytransf => $valuetransf) {
						$tempReqVac = $vacation->getVacation($valuetransf);
						$planVac[$keytransf] = $tempReqVac[0];
					}

					$data['planVac'] = $planVac;					
				}

				$data['infoPlanification'] = $infoPlanification;

				// renvoie une vue globale du planning donné
				// $this->vueGlobale($infoPlanification, $celPlanning[0]['Cellule_idCellule']);
				
				// On récupère les infos sur le planning
				$planningInfos = $planning->getPlanning($infoPlanification[0]['Planning_idPlanning']);
				$data['planningInfos'] = $planningInfos[0];

				/* 
					on récupère l'id de tous les agents 
					qui figurent déjà dans le planning en cours de lecture 
				*/

				$allCelAgent = $agent->getCelAgent($celPlanning[0]['Cellule_idCellule'], 'cellule_has_agent');

				$agentHasPlanId = array();

				foreach ($infoPlanification as $valueAgent) {
					array_push($agentHasPlanId, $valueAgent['Agent_idAgent']['id']);
				}

				// On exclut les agents qui ont déjà été planifié
				$agentReserve = $this->customArrayFilter($allCelAgent, $agentHasPlanId);

				if ($agentReserve) {

					foreach ($agentReserve as $keyRa => $valueRa) {
						$tempReq = $agent->getAgent($valueRa['Agent_idAgent']);
						$agentReserve[$keyRa] = $tempReq[0];
					}
				}else{
					$agentReserve = false;
				}

				// On récupère l'historique des plannings pour les agents qui ne sont pas le planning
				if ($agentReserve) {

					foreach ($agentReserve as $keyres => $valueres) {
						
						$tempHistoriquePlanning = $this->planningHistory($valueres['id']);

						$agentReserve[$keyres]['historiquePlanning'] = $tempHistoriquePlanning;
					}
				}

				// $data['agentAbs'] = $this->getFicheRetard($infoPlanification, $planVac, $planning->getPlanning($idplanification));

				$data['agentReserve'] = $agentReserve;

				$data['idPlanning'] = $idplanification;

				$data['idCampagne'] = $idCampagne;
				
				$this->set($data);

				$titre['pagetitle'] = "Planning N : ".$planningInfos[0]['id']." / ".$data['celInfoos'];
				$this->set($titre);
				$this->render('viewplanification');

			} else {
				
				/* 	
					Aucune données n'a été enregistré dans les lignes de la planification 
					Dans ce cas on supprime le planning uniquement 
					et on renvoie sur la page de création de planning 
				*/

				if (!class_exists('plannings')) {
					require_once(ROOT.'controllers/plannings.php');
				}

				$planning = new plannings;

				$planning->justDelete($idplanification);

				// if (!class_exists('home')) {
				// 	require_once(ROOT.'controllers/home.php');
				// }

				// $accueil = new home;

				// $accueil->index();
			}

		} else {
			
			if (!class_exists('home')) {
				require_once(ROOT.'controllers/home.php');
			}

			$accueil = new home;

			$accueil->index();
		}
	}

	/**
	* Permet de récupérer et de formater l'historique des plannings d'un agent donné
	*
	* @param idAgent identifiant de l'agent
	*
	**/
	public function planningHistory($idAgent) {

		if (!class_exists('Planning')) {
			require_once(ROOT.'models/planning.php');
		}

		$planning = new Planning;

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		$theHistorique = array();

		// On recupère les 4 derniers planning d'un agent
		$tempHistoriquePlanning = $this->Planification->getPlanificationAgent($idAgent);

		if ($tempHistoriquePlanning) {
			
			foreach ($tempHistoriquePlanning as $key => $value) {
				
				// On récupère le numéro de la semaine du planning
				$tempReqSem = $planning->getPlanning($value['Planning_idPlanning']);
				$tempReqSem = $tempReqSem[0]['numSemaine'];

				// On récupère le libéllé de la vacation
				$tempReqVac = $vacation->getVacation($value['Vacation_idVacation']);
				$tempReqVac = $tempReqVac[0]['libVacation'];

				$value['details'] = unserialize($value['details']);

				$tempReqRepos = '';
				foreach ($value['details'] as $keyd => $valued) {
					
					// Récupération du jour de repos
					if ($valued['start'] == 'Repos') {
						
						switch ($keyd) {
							case 1:
								$tempReqRepos = 'Lundi';
								break;
							
							case 2:
								$tempReqRepos = 'Mardi';
								break;
							
							case 3:
								$tempReqRepos = 'Mercredi';
								break;
							
							case 4:
								$tempReqRepos = 'Jeudi';
								break;
							
							case 5:
								$tempReqRepos = 'Vendredi';
								break;
							
							case 6:
								$tempReqRepos = 'Samedi';
								break;
							
							case 7:
								$tempReqRepos = 'Dimanche';
								break;
							
							default:
								# code...
								break;
						}

						break;
					}
				}

				array_push($theHistorique, array('numSem' => $tempReqSem, 'vacation' => $tempReqVac, 'jrRepos' => $tempReqRepos));
			}
		}

		return $theHistorique;
	}

	/**
	* Permet d'avoir une vue globale du planning
	*
	* @param $infoPlanification Identifiant du planning
	* @param $idCellule 
	*
	**/
	public function vueGlobale($infoPlanification, $idCellule) {

		if (!class_exists('Vacation')) {
			require_once(ROOT.'controllers/vacation.php');
		}

		$vacation = new Vacation;

		$vacationList = $vacation->getCelVacation($idCellule, 'cellule_has_vacation');

		$vueGlobale = array();

		$planningPerVac = array();

		foreach ($vacationList as $keyvac => $valuevac) {
			// Pour chaque vacation

			foreach ($infoPlanification as $key => $value) {
				// Pour chaque ligne du planning

				if ($valuevac['Vacation_idVacation'] == $value['Vacation_idVacation']) {
					
					$repos[] = 0;
					$travail[] = 0;

					foreach ($value['details'] as $keyd => $valued) {
						// Pour chaque jour concernant cette ligne
						var_dump($keyd);
		                $tempo = substr($valued['start'], 0, 1);
		                var_dump($tempo);

						/* On vérifier si cette ligne est un jour de travail ou de repos */

						if (ctype_digit($tempo)) {
							$travail["$keyd"]++;
							// Si c'est un jour de travail
							//$vueGlobale[$valuevac['Vacation_idVacation']][$keyd]['travail'] += 1;

						} else {
							$repos++;
							// Si c'est un jour de repos
							//$vueGlobale[$valuevac['Vacation_idVacation']][$keyd]['repos'] += 1;
						}
					}
					
					echo "<br>";
					echo "<br>";
					
					var_dump($repos);
					die();
				}
			}

			var_dump($vueGlobale);
			die();
		}
	}

	/**
	* Permet de créer la fiche des agents absence a une vacation pour un planning donné
	*
	* @param $infosPlanning toutes les infos sur planning concerné
	*
	* @return $presenceArray retourne un tableau avec la liste des agents absent a une vacation
	*
	**/
	public function getFicheRetard($infosPlanning, $vacInfos, $planningPeriod) {

		$presenceArray = array();

		if (!class_exists('Ficheretard')) {
			require_once(ROOT.'models/ficheretard.php');
		}

		$ficheretard = new Ficheretard;

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		$tempVar = explode('-', $planningPeriod[0]['periodeDebut']);
		$tempVar = $tempVar[0];

		$heureActuelle = date('H');
		$currentVacId = 0;

		$presenceArray = array();

		foreach ($vacInfos as $keyTemp => $valueTemp) {
			$heureFin = ($valueTemp['heureFin'] == 0) ? 23 : $valueTemp['heureFin'] ;

			if ($valueTemp['heureDebut'] <= $heureActuelle AND $valueTemp['heureFin'] >= $heureActuelle) {
				$currentVacId = $valueTemp['id'];
				break;
			}
		}

		$tempVar1 = date('d');

		$dif = $tempVar1 - $tempVar;

		$haveFiche = $ficheretard->getPlanningFicheretard($infosPlanning[0]['Planning_idPlanning']); 

		// On vérifie si le planning consulté est en cours d'exécution
		if (($dif >= 7) || $dif < 0) {
			
			return false;
 
		} elseif($haveFiche) {

			return false;
		}else{
			
			mssql_connect("10.0.5.12","sa","sa");
			mssql_select_db("HN_Ondata");

			$chiffreTest = array(0,1,2,3,4,5,6,7,8,9);

			foreach ($infosPlanning as $keyinfo => $valueinfo) {
					
				if ($currentVacId == $valueinfo['Vacation_idVacation']) { // Si l'agent est dans la vacation actuelle

					$heureStart = $valueinfo['details'][$dif+1]['start'];

					$fL = (int) substr($heureStart, 0, 1);

					if ($fL) {

						if (strlen($heureStart) == 1) {
							$heureStart = '0'.$heureStart;
						}

						$testDate1 = date('Y').date('m').date('d').$heureStart.'0000';
						$testDate2 = date('Y').date('m').date('d').'235959';

						$agentLog = $valueinfo['Agent_idAgent']['log'];

						$sqlReq = "SELECT * FROM [HN_Ondata].[dbo].[ODActions]

						WHERE OriginatorID = $agentLog AND State = -1 AND ActionLocalTimeString BETWEEN '$testDate1' AND '$testDate2'";
						
						$reqResult = mssql_query($sqlReq);			

						if (mssql_num_rows($reqResult) == 0) {

							// Agent absent
							$tempArr = array('Agent_idAgent' => $valueinfo['Agent_idAgent'], 'Planning_idPlanning' => $planningPeriod[0]['id']);
							array_push($presenceArray, $tempArr);

						} else {

							// Agent présent on ne fait rien
						}

					} else {
						
						// Repos ou autres on ne fait rien
					}
				}
			}
			return $presenceArray;
		}
	}

	/*
	* permet de sauvegarder la liste des absents
	*/
	public function saveAbsentList($idCampagne) {
		
		if (!class_exists('Ficheretard')) {
			require_once(ROOT.'models/ficheretard.php');
		}

		$ficheretard = new Ficheretard;
		$_POST['listAgent'] = serialize($_POST['listAgent']);
		$ficheretard->add($_POST);

		$this->veiwPlanification($_POST['Planning_idPlanning'], $idCampagne);
	}

	/**
	* Permet de récupérer la vacation précédente d'une cellule
	*
	* @param $currentVacationInfo infos de la vacation courante
	* @param $celluleId identifiant de la cellule dont on veut la vacation suivante
	*
	* @return $lastVacPlanId identifiant de la vacation précédente de la vacation courante
	*
	**/
	public function getPreviousVacation($currentVacationInfo, $celluleId) {

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		$vacName = $vacation->getVacationName($currentVacationInfo[0]['id']);

		$celVacation = $vacation->getCelVacation($celluleId, 'cellule_has_vacation');

		$tempaTable = array();

		foreach ($celVacation as $keytemp1 => $valuetemp1) {
			$tempResult = $vacation->getVacation($valuetemp1['Vacation_idVacation']);
			array_push($tempaTable, $tempResult[0]['niveau']);
		}

		sort($tempaTable);

		$minEl = $tempaTable[0];
		$prev = 0;

		if ($currentVacationInfo[0]['niveau'] <= $minEl) {
			rsort($tempaTable);

			$prev = $tempaTable[0];
		}else{

			$searchResult = array_search($currentVacationInfo[0]['niveau'], $tempaTable);
			$prev = $tempaTable[$searchResult - 1];
		}

		$lastVacInfos = $vacation->getVacationLine('niveau', $prev);

		$lastVacPlanId = "";

		foreach ($lastVacInfos as $keyv => $valuev) {

			$lastVacPlanId = $vacation->getVacationLinePlus($celluleId, $valuev['id'], "cellule_has_vacation");
			if ($lastVacPlanId) {
				break;
			}
		}

		return $lastVacPlanId[0]['Vacation_idVacation'];
		
	}

	/**
	* Permet de mettre à jour une ligne d'un planning
	*
	*
	**/
	public function updatePlanificationRow() {

		if ($_SESSION['userpermission']['permissions_planning']['edit']) {
			
			$details = array();

			$jourDeRepos = 1;

			foreach ($_POST['details'] as $key => $value) {

				if (strtolower($value['start']) == 'repos') {
					
					$jourDeRepos = $key;
				}

				$details[$key]['start'] = $value['start'];
				$details[$key]['end'] = $value['end'];
			}

			$details = serialize($_POST['details']);

			$data = array(
				'id'			=> $_POST['id'], 
				'details'		=> $details,
				'observations'	=> mysql_real_escape_string($_POST['observations']),
				'Jours_idJours'	=> $jourDeRepos
				);

			$result = $this->Planification->add($data);

			// Enregistrement dans le journal
			$this->mylog->add($_POST['id'], 2, 'a modifié une ligne du planning');

			$message['succes'] = "La ligne du planning a été modifié avec succès ";
			$this->set($message);

			$this->veiwPlanification($_POST['Planning_idPlanning'], $_POST['idCampagne']);

		} else {
			
			if (!class_exists('home')) {
				require_once(ROOT.'controllers/home.php');
			}

			$accueil = new home;

			$accueil->index();
		}
	}

	/**
	* Permet d'ajouter une ou plusieurs lignes dans une planification
	*
	* @param les informations sont envoyés par POST
	*
	* @return 
	*
	**/
	public function addPlanificationRow() {

		if ($_SESSION['userpermission']['permissions_planning']['edit']) {
			
			if (
				(isset($_POST['seletedVac']) && !empty($_POST['seletedVac'])) &&
				(isset($_POST['planningId']) && !empty($_POST['planningId'])) &&
				(isset($_POST['Agent_idAgent']) && !empty($_POST['Agent_idAgent']))
				) 
			{

				if (!class_exists('Vacation')) {
					require_once(ROOT.'models/vacation.php');
				}

				$vacation = new Vacation;

				// on récupère les informations sur la vacation sélectionnée
				$seletedVacInfos = $vacation->getVacation($_POST['seletedVac']);
				$seletedVacInfos = $seletedVacInfos[0];

				$jourDeLaSemaine = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");

				// On récupère les infos sur le précédent planning de la cellule courante
				$theLastPlanning = $this->getLastPlanning($_POST['celId']);

				if ($theLastPlanning) {
					// theLastPlanningId Identifiant du dernier planning de la cellule courante
					$theLastPlanningId = $theLastPlanning['Planning_idPlanning'];
				}else{
					$theLastPlanningId = false;
				}

				foreach ($_POST['Agent_idAgent'] as $key => $value) {

					// On récupère le jour de repos à accorder à l'agent en fonction de la rotation
					$jourDeReposAgent = $this->getReposDay($value, $theLastPlanningId);
					
					$details = array();
					// constitution de la partie détail
					foreach ($jourDeLaSemaine as $keyj => $valuej) {

						// Insertion du jour de repos de l'agent dans le détails
						if ($jourDeReposAgent == $keyj+1) {
							$details[$jourDeReposAgent]['start'] = "Repos";
							$details[$jourDeReposAgent]['end'] = "Repos";
						}
						else{
							$details[$keyj+1]['start'] = $seletedVacInfos['heureDebut'];
							$details[$keyj+1]['end'] = $seletedVacInfos['heureFin'];
						}
					}

					$details = serialize($details);

					$observations = "";

					$data = array(
						"details" 				=> $details,
						"observations" 			=> mysql_real_escape_string($observations),
						"Planning_idPlanning" 	=> $_POST['planningId']	,
						"Agent_idAgent" 		=> $value,
						"Jours_idJours" 		=> $jourDeReposAgent,
						"Vacation_idVacation" 	=> $_POST['seletedVac'],
						);

					$result = $this->Planification->add($data);
				}

				// Enregistrement dans le journal
				$this->mylog->add($_POST['planningId'], 1, 'a ajouté un agent au planning');

				$message['succes'] = "Le planning a été modifié avec succès";
				$this->set($message);

				$this->veiwPlanification($_POST['planningId'], $_POST['idCampagne']);

			} else {

				$message['echec'] = "Merci de sélectionner une vacation et de choisir les agents svp !";
				$this->set($message);

				$this->veiwPlanification($_POST['planningId'], $_POST['idCampagne']);
			}

		} else {
			
			if (!class_exists('home')) {
				require_once(ROOT.'controllers/home.php');
			}

			$accueil = new home;

			$accueil->index();
		}
	}

	/**
	* Permet de supprimer une ligne dans une planification
	*
	* @param $idplanification l'identifiant du planification à consulter
	* @param $idligne identifiant de la ligne à supprimer
	* @param $idCampagne identifiant de la campagne concernée
	*
	* @return Void
	*
	**/
	public function delPlanificationRow($idplanification, $idligne, $idCampagne)
	{
		if ($_SESSION['userpermission']['permissions_planning']['edit']) {
			
			// $delprofil = $this->Profil->getProfilName($id, "libProfil");

			$this->Planification->del($idligne);

			// Enregistrement dans le journal
			$this->mylog->add($idplanification, 3, 'a supprimé un agent du planning');

			$message['succes'] = "La ligne a été suprimé du planning avec succès";
			$this->set($message);

			$this->veiwPlanification($idplanification, $idCampagne);

		} else {
			
			if (!class_exists('home')) {
				require_once(ROOT.'controllers/home.php');
			}

			$accueil = new home;

			$accueil->index();
		}		
	}

	/**
	* Traite les plannings spéciaux
	* Et on en deduit de l'effectif des vacations
	*
	* @param $idplanning identifiant du planning concerné
	* @param $vacSpcPeriode la liste des vacations spéciales qui sont pour la période de planning
	* @param $allPostInfos['effectifVac'] le tableau des effectifs pour les différentes vacations
	* @param $theLastPlanningId l'identifiant du dernier planning de la cellule
	* @param $allCelAgent Liste de tous agents de la cellule
	*
	* @return $newsAllEffectif un tableau contenant les nouveaux les effectifs
	*
	**/
	public function vacSpPlanning($idplanning, $vacSpcPeriode, $allPostInfos, $theLastPlanningId, $allCelAgent)
	{
		$newsAllEffectif = array();

		$newsAllEffectif['newsEffectifVac'] = array();

		$jourDeLaSemaine = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		$nuitNbrAgentToPlanId = array();

		// On traite chaque cas de vacation
		foreach ($allPostInfos['effectifVac'] as $keyeff => $valueeff) {

			// on récupère les informations par rapport à une vacation
			$vacationInfo = $vacation->getVacation($keyeff);

			$vacationInfo = $vacationInfo[0];

			// 
			foreach ($vacSpcPeriode as $keyp => $valuep) {
					
				// Transforme la date ($allPostInfos['periodeDebut']) en anglais
				$tempdate = explode('-', $allPostInfos['periodeDebut']);
				$periodeDebut = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

				$tempdate = explode('-', $allPostInfos['periodeFin']);
				$periodeFin = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

				switch ($keyp) {

					case 'startIn':

						if ($valuep) {

							foreach ($valuep as $keysubtab => $valuesubtab) {
								
								if ($keyeff == $valuesubtab['Vacation_idVacation']) {

									$details = array();

									// On récupère le jour de repos à accorder à l'agent en fonction de la rotation
									$jourDeReposAgent = $this->getReposDay($valuesubtab['Agent_idAgent'], $theLastPlanningId);

									foreach ($jourDeLaSemaine as $keyjr => $valuejr) {

										$details[$keyjr+1]['start'] = $vacationInfo['heureDebut'];
										$details[$keyjr+1]['end'] = $vacationInfo['heureFin'];
									}
									
									$current = strtotime($valuesubtab['periodeDebut']);
									$periodePlanning = strtotime($periodeDebut);

									$newsHeures = unserialize($valuesubtab['horraires']);
				        
				                    foreach ($jourDeLaSemaine as $keyp3 => $valuep3) {

				                    	if ($periodePlanning == $current) {

											$details[$keyp3+1]['start'] = $newsHeures['start'];
											$details[$keyp3+1]['end'] = $newsHeures['end'];
										}
										
										$periodePlanning = strtotime('+1 day', $periodePlanning);
				                    }

				                    // Insertion du jour de repos de l'agent dans le détails
									foreach ($jourDeLaSemaine as $keyj => $valuej) {

										if ($jourDeReposAgent == $keyj+1) {
											$details[$jourDeReposAgent]['start'] 	= "Repos";
											$details[$jourDeReposAgent]['end'] 		= "Repos";
										}

									}

									ksort($details);

									$details = serialize($details);

									$data = array(
										"details" 				=> $details,
										"observations" 			=> mysql_real_escape_string('Vacation spéciale'),
										"Planning_idPlanning" 	=> $idplanning,
										"Agent_idAgent" 		=> $valuesubtab['Agent_idAgent'],
										"Jours_idJours" 		=> $jourDeReposAgent,
										"Vacation_idVacation" 	=> $valuesubtab['Vacation_idVacation'],
										);

									$result = $this->Planification->add($data);

									// On récupère le libellé de la vacation courante
									$currentVactName = $vacation->getVacationName($valuesubtab['Vacation_idVacation']);

									if (strtolower($currentVactName) == "nuit") { // Si c'est une vacation de nuit
										
										/*
											On enregistre l'agent planifié dans la table nuitcheck
										*/
										$resultNuitCheck = $this->Planification->findInTable(
											array(
											'conditions'	=> 'Cellule_idCellule='.$allPostInfos['selectedCel'],
											'limit' 		=> 1)
										, "nuitcheck");

										$retVal = ($resultNuitCheck) ? $resultNuitCheck[0]['numSemaine'] + 1 : 1 ;

										$donnees = array(
											"Agent_idAgent"			=> $valuesubtab['Agent_idAgent'],
											"Planning_idPlanning"	=> $idplanning,
											"Cellule_idCellule"		=> $allPostInfos['selectedCel'],
											"numSemaine"			=> $retVal);

										$result2 = $this->Planification->addToTable($donnees, "nuitcheck");
									}
									
									/*
										Si on planifie un agent pour la vacation spéciale dans cette cellule 
										on le déduit de l'effectif demandé

										On récupère son identifiant 
										pour le faire sortir de la liste totale des agents non encore planifié 
									*/
									if ($result) {

										$valueeff = $valueeff - 1;

										array_push($nuitNbrAgentToPlanId, $valuesubtab['Agent_idAgent']);
									}

								} // Fin if #$keyeff == $valuesubtab['Vacation_idVacation']

							} // Fin boucle foreach($valuep as $keysubtab => $valuesubtab)		

						} else{

							// Si le tableau est vide on ne fait rien
						}

						break;

					case 'allIn':

						if ($valuep) {

							foreach ($valuep as $keysubtab => $valuesubtab) {

								if ($keyeff == $valuesubtab['Vacation_idVacation']) {

									$details = array();

									// On récupère le jour de repos à accorder à l'agent en fonction de la rotation
									$jourDeReposAgent = $this->getReposDay($valuesubtab['Agent_idAgent'], $theLastPlanningId);

									foreach ($jourDeLaSemaine as $keyjr => $valuejr) {

										$details[$keyjr+1]['start'] = $vacationInfo['heureDebut'];
										$details[$keyjr+1]['end'] = $vacationInfo['heureFin'];
									}

									$current = strtotime($valuesubtab['periodeFin']);
									$current2 = strtotime($valuesubtab['periodeDebut']);
									$periodePlanning = strtotime($periodeDebut);
				        			
				        			$nboucle = $this->nbJours($valuesubtab['periodeDebut'], $valuesubtab['periodeFin']);

				        			$newsHeures = unserialize($valuesubtab['horraires']);

				                    foreach ($jourDeLaSemaine as $keyp3 => $valuep3) {

				                    	if ($periodePlanning == $current2) {

				                    		for ($ai=$keyp3; $ai <= $nboucle+$keyp3; $ai++) {
				                    			
				                    			$details[$ai+1]['start'] = $newsHeures['start'];
												$details[$ai+1]['end'] = $newsHeures['end'];
				                    		}

				                    		break;
										}

										$periodePlanning = strtotime('+1 day', $periodePlanning);
										
				                    }

				                    // Insertion du jour de repos de l'agent dans le détails
									foreach ($jourDeLaSemaine as $keyj => $valuej) {

										if ($jourDeReposAgent == $keyj+1) {
											$details[$jourDeReposAgent]['start'] 	= "Repos";
											$details[$jourDeReposAgent]['end'] 		= "Repos";
										}
									}

				                    ksort($details);

									$details = serialize($details);

									$data = array(
										"details" 				=> $details,
										"observations" 			=> mysql_real_escape_string('Vacation spéciale'),
										"Planning_idPlanning" 	=> $idplanning,
										"Agent_idAgent" 		=> $valuesubtab['Agent_idAgent'],
										"Jours_idJours" 		=> $jourDeReposAgent,
										"Vacation_idVacation" 	=> $valuesubtab['Vacation_idVacation'],
										);

									$result = $this->Planification->add($data);

									// On récupère le libellé de la vacation courante
									$currentVactName = $vacation->getVacationName($valuesubtab['Vacation_idVacation']);

									if (strtolower($currentVactName) == "nuit") { // Si c'est une vacation de nuit
										
										/*
											On enregistre l'agent planifié dans la table nuitcheck
										*/
										$resultNuitCheck = $this->Planification->findInTable(
											array(
											'conditions'	=> 'Cellule_idCellule='.$allPostInfos['selectedCel'],
											'limit' 		=> 1)
										, "nuitcheck");

										$retVal = ($resultNuitCheck) ? $resultNuitCheck[0]['numSemaine'] + 1 : 1 ;

										$donnees = array(
											"Agent_idAgent"			=> $valuesubtab['Agent_idAgent'],
											"Planning_idPlanning"	=> $idplanning,
											"Cellule_idCellule"		=> $allPostInfos['selectedCel'],
											"numSemaine"			=> $retVal);

										$result2 = $this->Planification->addToTable($donnees, "nuitcheck");

									}

									/*
										Si on planifie un agent pour la vacation spéciale dans cette cellule 
										on le déduit de l'effectif demandé.

										On récupère son identifiant 
										pour le faire sortir de la liste totale des agents non encore planifié 
									*/
									if ($result) {

										$valueeff = $valueeff - 1;

										array_push($nuitNbrAgentToPlanId, $valuesubtab['Agent_idAgent']);
									}
			                    }
		                	}
							
						} else{

							// Si le tableau est vide on ne fait rien
		                }

						break;

					case 'endIn':


						if ($valuep) {

							foreach ($valuep as $keysubtab => $valuesubtab) {

								if ($keyeff == $valuesubtab['Vacation_idVacation']) {

									$details = array();

									// On récupère le jour de repos à accorder à l'agent en fonction de la rotation
									$jourDeReposAgent = $this->getReposDay($valuesubtab['Agent_idAgent'], $theLastPlanningId);

									foreach ($jourDeLaSemaine as $keyjr => $valuejr) {

										$details[$keyjr+1]['start'] = $vacationInfo['heureDebut'];
										$details[$keyjr+1]['end'] = $vacationInfo['heureFin'];
									}

									$current = strtotime($valuesubtab['periodeFin']);
									$periodePlanning = strtotime($periodeDebut);
				        
				                    $nboucle = $this->nbJours($periodeDebut, $valuesubtab['periodeFin']);

				                    $newsHeures = unserialize($valuesubtab['horraires']);

									for ($ei=0; $ei <= $nboucle; $ei++) {

										$details[$ei+1]['start'] = $newsHeures['start'];
										$details[$ei+1]['end'] = $newsHeures['end'];
									}

									// Insertion du jour de repos de l'agent dans le détails
									foreach ($jourDeLaSemaine as $keyj => $valuej) {

										if ($jourDeReposAgent == $keyj+1) {
											$details[$jourDeReposAgent]['start'] 	= "Repos";
											$details[$jourDeReposAgent]['end'] 		= "Repos";
										}
									}

									ksort($details);

									$details = serialize($details);

									$data = array(
										"details" 				=> $details,
										"observations" 			=> mysql_real_escape_string('Vacation spéciale'),
										"Planning_idPlanning" 	=> $idplanning,
										"Agent_idAgent" 		=> $valuesubtab['Agent_idAgent'],
										"Jours_idJours" 		=> $jourDeReposAgent,
										"Vacation_idVacation" 	=> $valuesubtab['Vacation_idVacation'],
										);

									$result = $this->Planification->add($data);

									// On récupère le libellé de la vacation courante
									$currentVactName = $vacation->getVacationName($valuesubtab['Vacation_idVacation']);

									if (strtolower($currentVactName) == "nuit") { // Si c'est une vacation de nuit
										
										/*
											On enregistre l'agent planifié dans la table nuitcheck
										*/
										$resultNuitCheck = $this->Planification->findInTable(
											array(
											'conditions'	=> 'Cellule_idCellule='.$allPostInfos['selectedCel'],
											'limit' 		=> 1)
										, "nuitcheck");

										$retVal = ($resultNuitCheck) ? $resultNuitCheck[0]['numSemaine'] + 1 : 1 ;

										$donnees = array(
											"Agent_idAgent"			=> $valuesubtab['Agent_idAgent'],
											"Planning_idPlanning"	=> $idplanning,
											"Cellule_idCellule"		=> $allPostInfos['selectedCel'],
											"numSemaine"			=> $retVal);

										$result2 = $this->Planification->addToTable($donnees, "nuitcheck");
									}

									/*
										Si on planifie un agent pour la vacation spéciale dans cette cellule 
										on le déduit de l'effectif demandé.

										On récupère son identifiant 
										pour le faire sortir de la liste totale des agents non encore planifié 
									*/
									if ($result) {

										$valueeff = $valueeff - 1;

										array_push($nuitNbrAgentToPlanId, $valuesubtab['Agent_idAgent']);
									}
								}
							}
							
						} else{

							// Si le tableau est vide on ne fait rien
						}

						break;

					default:
						# code...
						break;
				} // Fin switch(keyps)

			} // Fin boucle foreach($vacSpcPeriode as $keyp => $valuep)


			$newsAllEffectif['newsEffectifVac'][$keyeff] = $valueeff;

		} // Fin boucle foreach($allPostInfos['effectifVac'] as $keyeff => $valueeff)

		// On redéfinit la liste des agents en excluant les agents de la nuit qu'on vient de planifier
		$newsAllEffectif['allCelAgent'] = $this->customArrayFilter($allCelAgent, $nuitNbrAgentToPlanId);
		
		// On retourne le nouveau tableau des effectifs
		return $newsAllEffectif;
	}

	/**
	* Sélectionne des valeurs de facon aléatoire dans un tableau
	*
	* @param $arr le tableau dans lequel la sélection sera faite
	* @param $num le nombre d'éléments à prendre dans le tableau
	*
	* @return un tableau 
	**/
	private function array_random($arr, $num = 1) {
	    shuffle($arr);
	    
	    $r = array();
	    for ($i = 0; $i < $num; $i++) {
	        $r[] = $arr[$i];
	    }
	    return $num == 1 ? $r : $r;
	}

	/**
	* Permet de filtrer un tableau 
	*
	* @param $arrayNeed tableau dans lequel sont les éléments à filtrer
	* @param $arrayModel tableau dans lequel on va faire les 
	*
	* @return $filterArray le tableau filtré
	*
	**/
	public function customArrayFilter($arrayNeed, $arrayModel) {

		$filterArray = array();

		for ($i=0; $i < sizeof($arrayNeed); $i++) {

			if (!in_array($arrayNeed[$i]['Agent_idAgent'], $arrayModel))
			{
				array_push($filterArray, $arrayNeed[$i]);
			}
		}

		return $filterArray;
	}

	/**
	* Vérifie si un agent a une permission en cours dans la période du planning
	*
	* @param $idAgent Identifiant de l'agent
	* @param $permissionsDate la liste de toutes les permissions
	*
	* @return $permissionOfAgent liste des permissions pour un agent false si il n' y a aucune permission
	**/
	public function checkHavePermission($idAgent, $permissionsDate) {
		if ($permissionsDate) {
			
			$permissionOfAgent = array();

			foreach ($permissionsDate as $key => $value) {

				if ($idAgent == $value['Agent_idAgent']) {
					
					$permissionOfAgent[] = $permissionsDate[$key];

				} else {
					
					$permissionOfAgent = false;
				}			
			}

			return $permissionOfAgent;

		} else {

			// Il n'y a aucune permission
			return false;
		}
	}

	/**
	* Calcul le nombre de jours entre deux dates
	*
	* @param $debut la date de début
	* @param $fin la date de fin
	*
	* @return $nbrjrs le nombre de jours
	**/
	private function nbJours($debut, $fin) {

        //60 secondes X 60 minutes X 24 heures dans une journée
        $nbSecondes= 60*60*24;

        $debut_ts = strtotime($debut);
        $fin_ts = strtotime($fin);
        $diff = $fin_ts - $debut_ts;

        $nbrjrs = round($diff / $nbSecondes);

        return $nbrjrs;
    }


    /**
     * permet de formater les heures du planning 
     * 
     * @param $idplanning l'identifiant
     * @param $horaires les heures a appliquer a la personne
     * @param $idAgent identifiant de l'agent
     * 
     **/
    private function formatedHoraires($idplanning, $horaires, $idAgent) {
    	$details = array();
		// constitution du tableau détails
		foreach ($horaires as $keyHoraires => $valueHoraires) {
			
			$details[$keyHoraires]['start'] 	= $valueHoraires[0];
			$details[$keyHoraires]['end'] 		= $valueHoraires[1];
		}

		$infosPlanification = array(
			'details'             => serialize($details),
			'observations'        => '',
			'Planning_idPlanning' => $idplanning,
			'Agent_idAgent'       => $idAgent,
			'Jours_idJours'       => -1,
			'Vacation_idVacation' => -1,
		);

		return $infosPlanification;
    }
}

?>