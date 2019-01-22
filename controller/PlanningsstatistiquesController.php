<?php

	/**
	* 
	*/
	class statistiques extends Controller
	{
		
		function __construct(){

			// $this->activeclass['statistiques'] = 'active';

			//	$this->set($this->activeclass);

			// if (!isset($_SESSION['userid'])) {

			// 	require(ROOT.'controllers/home.php');
			// 	$home = new home;
			// 	$home->loginForm();
			// 	die();
			// }
		}

		public function index(){
			
			if (!class_exists('Campagne')) {
				require_once(ROOT.'models/campagne.php');
			}

			$campagne = new Campagne;

			if (!class_exists('Cellule')) {
				require_once(ROOT.'models/cellule.php');
			}

			$cellule = new Cellule;

			if (!class_exists('Agent')) {
				require_once(ROOT.'models/agent.php');
			}

			$agent = new Agent;

			$titre['pagetitle'] = "Statistiques";
			$this->set($titre);
			$this->render('index');
		}

		public function statCamp(){
			
			if (!class_exists('Campagne')) {
				require_once(ROOT.'models/campagne.php');
			}

			$campagne = new Campagne;

			if (!class_exists('Cellule')) {
				require_once(ROOT.'models/cellule.php');
			}

			$cellule = new Cellule;

			if (!class_exists('Agent')) {
				require_once(ROOT.'models/agent.php');
			}

			$agent = new Agent;

			$allCampagne = $campagne->getAll();

			foreach ($allCampagne as $key => $value) {
				
				$allCel = $cellule->getCampcellule($value['id']);

				foreach ($allCel as $keycel => $valuecel) {
					
					$agentList = $agent->getCelAgent($valuecel['id'], 'cellule_has_agent');

					$allCel[$keycel]['effectifCel'] = sizeof($agentList);
				}

				$allCampagne[$key]['cellules'] = $allCel;
			}

			$CampEffectif = array();
			$subCampEffectif = array();

			foreach ($allCampagne as $keyCampEffectif => $valueCampEffectif) {
				
				$effectif = 0;
				$theCel = array();
				foreach ($valueCampEffectif['cellules'] as $keytemp => $valuetemp) {
					$effectif += $valuetemp['effectifCel'];
					array_push($theCel, array($valuetemp['libCellule'], $valuetemp['effectifCel']));
				}

				$indexTab = $valueCampEffectif['nomCampagne'];

				array_push($CampEffectif, array("name" => $indexTab, "y" =>$effectif, "drilldown" => true));

				$subCampEffectif["$indexTab"] = array("name" => $indexTab, "data" => $theCel);

			}
			
			$data['printData'] = $CampEffectif;

			$data['subPrintData'] = $subCampEffectif;

			$this->set($data);

			$titre['pagetitle'] = "Statistiques campagne";
			$this->set($titre);
			$this->render('statcampagne');
		}

		/**
		* Permet
		* @param les paramètres sont envoyés par _POST
		*
		**/
		public function statPlanning(){

			if (!class_exists('Campagne')) {
				require_once(ROOT.'models/campagne.php');
			}

			$campagne = new Campagne;

			if (!class_exists('Cellule')) {
				require_once(ROOT.'models/cellule.php');
			}

			$cellule = new Cellule;
			
			if (!class_exists('Planning')) {
				require_once(ROOT.'models/planning.php');
			}

			$planning = new Planning;
			
			if (!class_exists('Planification')) {
				require_once(ROOT.'models/planification.php');
			}
			
			$planification = new Planification;

			$allCel = $cellule->getAll();

			$lesMois = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

			$lesSemaines['S1'] = array();
			$lesSemaines['S2'] = array();
			$lesSemaines['S3'] = array();
			$lesSemaines['S4'] = array();
			$lesSemaines['S5'] = array();

			if (isset($_POST['selectedCellule'])) {

				$theYear = ($_POST['statAnnee'] != '') ? $_POST['statAnnee'] : date('Y');
				$theYear = trim($theYear);

				foreach ($lesMois as $keymois => $valuemois) { // Pour chaque mois
						
					$searchPattern = '%-'.$valuemois.'-'.$theYear.'%';

					// On recherche les plannings du mois concerné
					$celPlanning = $planning->getCelPlanning($_POST['selectedCellule'], $searchPattern);

					if ($celPlanning) {

						foreach ($celPlanning as $keySemPlan => $valueSemPlan) { // pour chaque planning du mois concerné

							$allPlanLine = $planification->getPlanification($valueSemPlan['id']);

							$retVal = ($allPlanLine) ? sizeof($allPlanLine) : 0 ;

							switch ($this->getWeeks($valueSemPlan['periodeDebut'])) {
								case 1:

									array_push($lesSemaines['S1'], $retVal);
									break;
								
								case 2:
									array_push($lesSemaines['S2'], $retVal);
									break;
								
								case 3:
									array_push($lesSemaines['S3'], $retVal);
									break;
								
								case 4:
									array_push($lesSemaines['S4'], $retVal);
									break;
								
								default:
									array_push($lesSemaines['S5'], $retVal);
									break;
							}
						}
						
					} else {

						array_push($lesSemaines['S1'], 0);
						array_push($lesSemaines['S2'], 0);
						array_push($lesSemaines['S3'], 0);
						array_push($lesSemaines['S4'], 0);
						array_push($lesSemaines['S5'], 0);
					}
					
				}

				$selectedCelluleInfos = $cellule->getCellule($_POST['selectedCellule']);

				$data['selectedCelluleInfos'] = $selectedCelluleInfos[0]['libCellule'];
				$data['lesSemaines'] = $lesSemaines;

			}else{
				
				$data['lesSemaines'] = false;	

			}

			$data['allCel'] = $allCel;

			$this->set($data);
			
			$titre['pagetitle'] = "Statistiques planning";
			$this->set($titre);
			$this->render('statplanning');

		}

	    /**
	     * Returns the amount of weeks into the month a date is
	     * @param $date a YYYY-MM-DD formatted date
	     * @param $rollover The day on which the week rolls over
	     */
	    function getWeeks($date, $rollover = "sunday")
	    {
	    	$dateNewForma = explode('-', $date);
	    	$date = $dateNewForma[2].'-'.$dateNewForma[1].'-'.$dateNewForma[0];

	        $cut = substr($date, 0, 8);
	        $daylen = 86400;

	        $timestamp = strtotime($date);
	        $first = strtotime($cut . "00");
	        $elapsed = ($timestamp - $first) / $daylen;

	        $i = 1;
	        $weeks = 1;

	        for($i; $i<=$elapsed; $i++)
	        {
	            $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
	            $daytimestamp = strtotime($dayfind);

	            $day = strtolower(date("l", $daytimestamp));

	            if($day == strtolower($rollover))  $weeks ++;
	        }

	        return $weeks;
	    }
	}

?>