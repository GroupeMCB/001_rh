<?php

/**
* Controleur planning
*/
class PlanningsplanningsController extends Controller
{

	private $mylog;

	/**
	*
	**/
	public function dashboard()
	{
		$this->loadModel('Planning', 'planning');

		$this->loadModel('Campagnes', 'planning');

		$this->set('campagnes', $this->Campagnes->find());

		$this->render('dashboard');
	}


	/**
	* Permet d'afficher le formulaire de création d'un nouveau planning
	*
	* @param aucun paremètre
	* @return
	**/
	public function newPlanning()
	{
		$this->loadModel('Cellules', 'planning');

		$data = $this->Cellules->find();

		$this->set('cellules', $data);

		$this->render('newplanning');
	}

	/**
	* Permet de génerer le planning
	*
	* @param les données sont reçues par la methode _POST
	*
	**/
	public function generatePlanning()
	{
		if ($_SESSION['userpermission']['permissions_planning']['add']) {

			if (
				(isset($_POST['periodeDebut']) && !empty($_POST['periodeDebut'])) &&
				(isset($_POST['periodeFin']) && !empty($_POST['periodeFin'])) &&
				(isset($_POST['selectedCel']) && !empty($_POST['selectedCel'])) &&
				(isset($_POST['effectifVac']) && !empty($_POST['effectifVac']))
				)
			{

				if ($this->tableTestValue($_POST['effectifVac'])) {

					$message['echec'] = "Merci de renseigner des effectifs corrects !";
					$this->set($message);
					$this->createPlanningForm();

				} else {

					if (!class_exists('Agent')) {
						require_once(ROOT.'models/agent.php');
					}

					$agent = new Agent;

					if (!class_exists('Cellule')) {
						require_once(ROOT.'models/cellule.php');
					}

					$cellule = new Cellule;

					$now = date('j-m-Y H:i');

					$tempRequete = $cellule->getCellule($_POST['selectedCel']);
					$idCampagne = $tempRequete[0]['Campagne_idCampagne'];

					$dateCoupee = explode('-', $_POST['periodeDebut']);

					$dataPlanning = array(
						"periodeDebut"      => $_POST['periodeDebut'],
						"periodeFin"        => $_POST['periodeFin'],
						"numSemaine"        => date("W", mktime(0,0,0, $dateCoupee[1], $dateCoupee[0], $dateCoupee[2])),
						"dateEdition"       => date('j-m-Y, H:i:s'),
						"niveauPlanning"    => 0,
						"commentaire"       => "",
						"message"           => "",
						"idAuteur"          => $_SESSION['userid'],
						"idCampagne"        => $idCampagne,
						"Cellule_idCellule" => $_POST['selectedCel']
						);

					if (!class_exists('permissions')) {
						require_once(ROOT.'controllers/permissions.php');
					}

					$ctrlPermission = new permissions;

					if (!class_exists('Permission')) {
						require_once(ROOT.'models/permission.php');
					}

					$mdlPermission = new Permission;

					$tempdate = explode('-', $_POST['periodeDebut']);
					$dateEnD = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

					$tempdate = explode('-', $_POST['periodeFin']);
					$dateEnF = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

				/* Récupération des permissions */

					// On récupère toutes les permissions qui sont entre la période de planning
					$permissionsDate = $mdlPermission->getPermissionsBetween($dateEnD, $dateEnF, 'ladate');

					// On récupère toutes permissions dont la date de début est comprise entre la période de planning
					$permissionsPeriodeStartIn = $mdlPermission->getPermissionsBetween($dateEnD, $dateEnF, 'periodeDebut');

					// On récupère toutes permissions dont la date de fin est comprise entre la période de planning
					$permissionsPeriodeEndIn = $mdlPermission->getPermissionsBetween($dateEnD, $dateEnF, 'periodeFin');

					$permissionsPeriode = $ctrlPermission->getPermissionInterval($permissionsPeriodeStartIn, $permissionsPeriodeEndIn);

				/* Fin permissions */

				/* Récupération des vacations spéciales */

					// On récupère toutes les vacations spéciales dont la date de début est comprise entre la période de planning
					$vacSpcPeriodeStartIn = $mdlPermission->getPermissionsBetween($dateEnD, $dateEnF, 'periodeDebut', 1);

					// On récupère toutes les vacations spéciales dont la date de fin est comprise entre la période de planning
					$vacSpcPeriodeEndIn = $mdlPermission->getPermissionsBetween($dateEnD, $dateEnF, 'periodeFin', 1);

					$vacSpcPeriode = $ctrlPermission->getPermissionSpcInterval($vacSpcPeriodeStartIn, $vacSpcPeriodeEndIn);

				/* Fin vacations spéciales */

					$idplanning = $this->Planning->add($dataPlanning);

					$planningCel = array(
						"Planning_idPlanning" => $idplanning,
						"Cellule_idCellule" => $_POST['selectedCel']
						);

					$addResult = $this->Planning->addToTable($planningCel, "planning_has_cellule");

					if (!class_exists('planifications')) {
						require_once(ROOT.'controllers/planifications.php');
					}

					$ctrlPlanification = new planifications;

					$ctrlPlanification->generatePlanification($idplanning, $_POST, $permissionsDate, $permissionsPeriode, $vacSpcPeriode, $idCampagne);
				}

			} else {

				$message['echec'] = "Merci de renseigner les différents champs du formulaire svp !";
				$this->set($message);

				$this->createPlanningForm();
			}
		} else {
			# code...
		}

	}

	/**
	* Permet d'afficher la liste de tous les planning d'une campagne
	*
	* @param $idCampagne l'identifiant de la campagne
	*
	* @return
	*
	**/
	public function listPlanning($idCampagne){

		if ($_SESSION['userpermission']['permissions_planning']['view']) {

			// on récupère tous les plannings de la campagne
			$data['plannigsCampagne'] = $this->Planning->getCampPlanning($idCampagne);

			$planningCels =array();

			foreach ($data['plannigsCampagne'] as $keyAut => $valueAut) {

				include_once ROOT.'msserver.php';

				$sql = "select ID_AUTO, NOM, PRENOM from EMPLOYE where ID_AUTO=".$valueAut['idAuteur'];

				$sqlquery = mssql_query($sql);

				$emploidata = mssql_fetch_assoc($sqlquery);

				$data['plannigsCampagne'][$keyAut]['idAuteur'] = $emploidata;
			}

			if ($data['plannigsCampagne']) { // on vérifie si il y a des plannings pour la campagne

				foreach ($data['plannigsCampagne'] as $keyphc => $valuephc) {

					// On récupère les cellules de la campagne qui ont été planifié
					$tempphc = $this->Planning->getPlanningCel($valuephc['id'], "planning_has_cellule");
					$tempphc = $tempphc[0];
					array_push($planningCels, $tempphc);
				}

			}else{

				$planningCels = false;
			}

			if (!class_exists('Cellule')) {
				require_once(ROOT.'models/cellule.php');
			}

			$cellule = new Cellule;

			// On récupère les cellules de la campagne
			$campCels = $cellule->getCampcellule($idCampagne);

			foreach ($data['plannigsCampagne'] as $keyadd => $valueadd) {

				foreach ($planningCels as $keyadd1 => $valueadd1) {

					if (($valueadd['id'] == $valueadd1['Planning_idPlanning']) AND ($planningCels[$keyadd1] != NULL)) {

						$data['plannigsCampagne'][$keyadd]['celPlanning'] = array();
						array_push($data['plannigsCampagne'][$keyadd]['celPlanning'], $valueadd1['Cellule_idCellule']);
					}
				}
			}

			if ($campCels) { // Pour chaque cellule on vérifie si elles ont un planning

				foreach ($campCels as $key => $value1) {

					if ($planningCels) {

						foreach ($planningCels as $value2) {

							if ($value1['id'] == $value2['Cellule_idCellule']) {
								$campCels[$key]['has_planning'] = 1;

								break;
							}else{

								$campCels[$key]['has_planning'] = 0;
							}
						}
					}else{
						$campCels[$key]['has_planning'] = false;
					}
				}
			}

			if (!class_exists('Campagne')) {
				require_once(ROOT.'models/campagne.php');
			}

			$campagne = new Campagne;

			$infosCampagne = $campagne->getCampagne($idCampagne);
			$infosCampagne = $infosCampagne[0];

			$data['infosCampagne'] = $infosCampagne;

			$data['infosCel'] = $campCels;

			$this->set($data);

			$titre['pagetitle'] = "Consulter plannings > ".$infosCampagne['nomCampagne'];
			$this->set($titre);
			$this->render('listplanning');
		} else {

			if (!class_exists('home')) {
				require_once(ROOT.'controllers/home.php');
			}

			$home = new home;

			$home->index();
		}
	}

	/**
	* Permet d'afficher la liste de tous les planning des différentes campagnes
	*
	* @param les parametres sont envoyés par la methode POST
	* @return
	**/
	public function listAllPlanning(){

		if ($_SESSION['userpermission']['permissions_planning']['view']) {

			$lesplannings = $this->Planning->getAll();

			if (!class_exists('Campagne')) {
				require_once(ROOT.'models/campagne.php');
			}

			$campagne = new Campagne;

			$allCampagne = $campagne->getAll(); // on récupère toutes les campagnes

			$lesplanningsByCamp = array();

			if ($allCampagne) { // Vérification du tableau

				foreach ($allCampagne as $value) { //

					$planningByCampagne[$value['nomCampagne']] = $this->Planning->getCampPlanning($value['id']);

					if ($planningByCampagne) {

						$lesplanningsByCamp = array_merge((array)$lesplanningsByCamp, $planningByCampagne);
					}
				}
			}

			$data['lesplanningsByCamp'] = $lesplanningsByCamp;
			$this->set($data);

			$titre['pagetitle'] = "Liste de tous les plannings";
			$this->set($titre);
			$this->render('listallplanning');

		} else {

			if (!class_exists('home')) {
				require_once(ROOT.'controllers/home.php');
			}

			$home = new home;

			$home->index();
		}

	}

	/**
	* Permet de récupérer la liste des vacations d'une cellule,
	* les affiche avec les effectifs à planifier pour chaque vacation
	*
	* @param $idCellule l'identifiant de la cellule
	*
	**/
	public function getVacation($idCellule){

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		$celVacation = $vacation->getCelVacation($idCellule, "cellule_has_vacation");

		if ($celVacation) {
			$vacationInfo = array();
			foreach ($celVacation as $key => $value) {
				$tempRequete = $vacation->getVacation($value['Vacation_idVacation']);
				$tempRequete = $tempRequete[0];
				array_push($vacationInfo, $tempRequete);
			}
		}else{
			$vacationInfo = false;
		}

		echo "<h5>Effectifs</h5>";

		if ($vacationInfo) {

			$vacEffec = $this->getVacationEffec($idCellule, $vacationInfo);

			foreach ($vacationInfo as $keyAdd => $valueAdd) {

				$vacationInfo[$keyAdd]['effectifs'] = $vacEffec[$valueAdd['id']];
			}

			foreach ($vacationInfo as $key => $value) {

				echo '
					<div class="col-md-3">
			          <div class="form-group">
			            <label for="inputEffectif" class="col-md-6 control-label">'.$value['libVacation'].' :</label>
			            <div class="col-md-6">
			              <input type="text" class="form-control inputEffectif" id="inputEffectif" name="effectifVac['.$value['id'].']" value="'.$value['effectifs'].'">
			            </div>
			          </div>
			        </div>';
			}

		}else{
			echo "<b style='color:red;'>Veuillez crée des vacations pour la cellule</b>";
		}
	}

	/**
	* Permet d'obtenir les différents effectifs pour les tranches de vacations de la cellule selectionnée
	* sur la base de certains calcule
	*
	* @param $idCellule l'identifiant de la cellule concernée
	* @param $vacationInfo informations sur la vacation concernbée
	*
	* @return $realEffectif les effectifs à planifier pour chaque vacation
	*
	**/
	public function getVacationEffec($idCellule, $vacationInfo){

		// relever le point de départ
		$timestart=microtime(true);

		if (!class_exists('Cellule')) {
			require_once(ROOT.'models/cellule.php');
		}

		$cellule = new Cellule;

		// Récupération des informations de la cellule
		$celAllInfos = $cellule->getCellule($idCellule);
		$celAllInfos = $celAllInfos[0];

		// Récupération du numéro Hermes de la cellule
		$celHermesNum = $celAllInfos['hermes_num'];

		// Récupération de la performance horaire
		$performenceHoraire = $celAllInfos['performancehoraire'];

		// Nombre total d'agents dans la cellule
		$nbreCelAgent = sizeof($cellule->getCelluleAgent($idCellule, 'cellule_has_agent'));

		$currentDate = date('Y-m-d');

		$nbreAgentToPlan = array();

		$totalAgent = 0; // variable nombre total d'agent nécéssaire pour la vacation (théorique)

		$percent = array();
		$periodeDistribution = 5;

		$realEffectif = array(); // tableau pour accueillir la liste des effectifs à staffer

		if ($vacationInfo) { // la variable $vacationInfo renvoie des informations

			foreach ($vacationInfo as $key => $value) {

				/* Pour chaque vacation de la cellule on détermine l'effectif à staffer */

				$value['distributionHeures'] = unserialize($value['distributionHeures']);

				$theTranche = array($value['distributionHeures']['disHeureDebut'], $value['distributionHeures']['disHeureFin']);

				if (strlen($theTranche[0]) == 1) {
					$theTranche[0] = '0'.$theTranche[0];
				}

				if (strlen($theTranche[1]) == 1) {
					$theTranche[1] = '0'.$theTranche[1];
				}

				$theTranchePlus = NULL;
				$totalAppels = 0;

				$moyHeureEffectifAll = array();

				for ($i=1; $i<=$periodeDistribution ; $i++) { // On récupère le nombre d'appels sur les 5 jours précédents

					$back = '-'.$i.' day';

					$searchDate = strtotime($back, strtotime($currentDate));

					$searchDate = date('Y-m-d', $searchDate);

					$reqDate = explode('-', $searchDate);

					// On recupère le nombre d'appels reçu heure par heure sur cette tranche horaire
					$nbreAppels = $this->nbreAppelsJour($reqDate, $theTranche, $theTranchePlus, $celHermesNum);

					$heureEffectif = array();

					// On divise chaque nombre d'appels par la performance horaire pour déterminer les effectifs théorique
					foreach ($nbreAppels as $keynbrappel => $valuenreappel) {

						$temp = $valuenreappel / $performenceHoraire;

						$effecNecessaire = round($temp, 0, PHP_ROUND_HALF_UP);

						array_push($heureEffectif, $effecNecessaire);
					}

					// Pour chaque jour on détermine la moyenne d'effectif nécéssaire pour chaque vacation
					$j = 1;
					$somme = 0;
					$tailleTab = sizeof($heureEffectif);

					for ($i=0; $i < $tailleTab; $i++) {

						if ($heureEffectif[$i] != 0) {

							$somme += $heureEffectif[$i];
							$j++;
						}
					}

					$moyHeureEffectif = round(($somme / $j), 0, PHP_ROUND_HALF_UP);

					array_push($moyHeureEffectifAll, $moyHeureEffectif);
				}

				// On détermine la moyenne d'effectif nécéssaire pour les 7 jours pour chaque vacation
				$k = 1;
				$sommeAll = 0;
				$tailleTab1 = sizeof($moyHeureEffectifAll);

				for ($i=0; $i < $tailleTab1; $i++) {

					if ($moyHeureEffectifAll[$i] != 0) {

						$sommeAll += $moyHeureEffectifAll[$i];
						$k++;
					}
				}

				// Nombre total d'agent en moyenne sur cette vacation (théorique)
				$nbreAgentToPlan[$value['id']] = round(($sommeAll / $k), 0, PHP_ROUND_HALF_UP);
			}

			foreach ($nbreAgentToPlan as $keytotal => $valuetotal) {
				$totalAgent += $valuetotal;
			}

		} else {

			// Il n'y a pas de vacation définie pour cette cellule
		}

		if ($totalAgent == 0 ) { // Si il n'y a aucun agent dans la cellule

			return $realEffectif = 0;
		}

		// Calcule du pourcentage des effectifs nécessaire (théorique)
		foreach ($nbreAgentToPlan as $key => $value) {

			$percent[$key] = ($value * 100) / $totalAgent;
			$percent[$key] = round($percent[$key]);
		}


		// Détermination des effectifs réels
		foreach ($percent as $keyeff => $valueeff) {


			$realEffectif[$keyeff] = ($valueeff * $nbreCelAgent) / 100;
			$realEffectif[$keyeff] = round($realEffectif[$keyeff]);
		}

		return $realEffectif;
	}

	/**
	* Permet de récupérer le nombre d'appels reçu sur une cellule à une date donnée durant une certaine tranche horaire donnée
	*
	* @param $thedate la date concernée
	* @param $trancheHeure la tranche d'heure concernée
	* @param $thedatePlus la date pour la vacation de nuit
	* @param $celHermesNum identifiant hermes de la cellule concernée
	*
	* @return $nbreAppel le nombre d'appel reçu suivant les paramètres fournis
	*
	**/
	public function nbreAppelsJour($thedate, $trancheHeure, $thedatePlus, $celHermesNum){

		$trancheHeure[1] = ($trancheHeure[1] == 0) ? 23 : $trancheHeure[1] ;

		// On recupere le nombre d'heures qu'il y a dans cette tranche horaire
		$diff = $trancheHeure[1] - $trancheHeure[0];

		$lheure = (int)$trancheHeure[0];

		$nbreAppels = array(); // Nombre d'appels pour reçu de la 1ère plateforme
		$nbreAppels2 = array(); // Nombre d'appels pour reçu de la 2ème plateforme

		/*
			Si c'est une cellule du 111 : Masse Market, ...
		*/
		$cellule111 = array('6015', '6093', '6092');

		$celversion5 = array('1919', '3334', '6090');

		if (in_array($celHermesNum, $cellule111)) {

			$plateformeAdress = "10.0.5.12";
			$plateformeID = "sa";
			$plateformePassWord = "vocalcom";

			// Connecion à la base de données de HERMES pour les requetes
			mssql_connect($plateformeAdress, $plateformeID, $plateformePassWord);
			mssql_select_db("HN_Ondata");
			mssql_select_db("HN_ONDATA_ARCHIVE");

			// On parcours chaque tranche horaire
			for ($i=0; $i <= $diff; $i++) {

				$lheure = (strlen($lheure) == 1)? "0".$lheure : $lheure ;

				if (is_null($thedatePlus)) { // Si la variable $thedatePlus est nulle

					$between = $thedate[0].$thedate[1].$thedate[2].$lheure."0000' AND '".$thedate[0].$thedate[1].$thedate[2].$lheure;
					$tableEnd = $thedate[0]."_".$thedate[1];

				}else{

					$between = $thedate[0].$thedate[1].$thedate[2].$lheure."0000' AND '".$thedatePlus[0].$thedatePlus[1].$thedatePlus[2].$lheure;
					$tableEnd = $thedate[0]."_".$thedate[1];
				}

				// Requete sur la table courante de production
				$thereq = "SELECT count(*) as nbreAppels2
				  FROM [HN_Ondata].[dbo].[ODCalls]
				  where LastCampaign = '".$celHermesNum."' AND CallLocalTimeString BETWEEN '".$between."5959' ";

				$reqResult = mssql_query($thereq);
				$data = mssql_fetch_assoc($reqResult);

				// requete sur la table d'archive J+2
				$nomTable = "[HN_ONDATA_ARCHIVE].[dbo].[ODCalls_".$tableEnd."]";
				$thereq1 = "SELECT count(*) as nbreAppels2
				  FROM $nomTable
				  where LastCampaign = '".$celHermesNum."' AND CallLocalTimeString BETWEEN '".$between."5959' ";

				$reqResult1 = mssql_query($thereq1);
				$data1 = mssql_fetch_assoc($reqResult1);

				array_push($nbreAppels2, (int)$data['nbreAppels2'] + (int)$data1['nbreAppels2']);

				$lheure++;
			}

			return $nbreAppels2;

		}elseif(in_array($celHermesNum, $celversion5)){ // pour les cellules sur la version 5

			$plateformeAdress = "10.0.5.233";
			$plateformeID = "sa";
			$plateformePassWord = "20mcb@";

			// Connecion à la base de données de HERMES pour les requetes
			$link = mssql_connect($plateformeAdress, $plateformeID, $plateformePassWord);
			mssql_select_db("HN_Ondata");
			mssql_select_db("HN_ONDATA_ARCHIVE");

			// On parcours chaque tranche horaire
			for ($i=0; $i <= $diff; $i++) {

				$lheure = (strlen($lheure) == 1)? "0".$lheure : $lheure ;

				if (is_null($thedatePlus)) { // Si la variable $thedatePlus est nulle

					$between = $thedate[0].$thedate[1].$thedate[2].$lheure."0000' AND '".$thedate[0].$thedate[1].$thedate[2].$lheure;
					$tableEnd = $thedate[0]."_".$thedate[1];

				}else{

					$between = $thedate[0].$thedate[1].$thedate[2].$lheure."0000' AND '".$thedatePlus[0].$thedatePlus[1].$thedatePlus[2].$lheure;
					$tableEnd = $thedate[0]."_".$thedate[1];
				}

				// Requete sur la table courante de production
				$thereq = "SELECT count(*) as nbreAppels
				  FROM [HN_Ondata].[dbo].[ODCalls]
				  where LastCampaign = '".$celHermesNum."' AND CallLocalTimeString BETWEEN '".$between."5959' ";

				$reqResult = mssql_query($thereq);
				$data = mssql_fetch_assoc($reqResult);

				// requete sur la table d'archive J+2
				$nomTable = "[HN_ONDATA_ARCHIVE].[dbo].[ODCalls_".$tableEnd."]";
				$thereq1 = "SELECT count(*) as nbreAppels
				  FROM $nomTable
				  where LastCampaign = '".$celHermesNum."' AND CallLocalTimeString BETWEEN '".$between."5959' ";

				$reqResult1 = mssql_query($thereq1);
				$data1 = mssql_fetch_assoc($reqResult1);

				array_push($nbreAppels, (int)$data['nbreAppels'] + (int)$data1['nbreAppels']);

				$lheure++;
			}

			mssql_close($link);

			return $nbreAppels;
		}else{

			$plateformeAdress = "10.0.5.12";
			$plateformeID = "sa";
			$plateformePassWord = "vocalcom";

			// Connecion à la base de données de HERMES pour les requetes
			$link = mssql_connect($plateformeAdress, $plateformeID, $plateformePassWord);
			mssql_select_db("HN_Ondata");
			mssql_select_db("HN_ONDATA_ARCHIVE");

			// On parcours chaque tranche horaire
			for ($i=0; $i <= $diff; $i++) {

				$lheure = (strlen($lheure) == 1)? "0".$lheure : $lheure ;

				if (is_null($thedatePlus)) { // Si la variable $thedatePlus est nulle

					$between = $thedate[0].$thedate[1].$thedate[2].$lheure."0000' AND '".$thedate[0].$thedate[1].$thedate[2].$lheure;
					$tableEnd = $thedate[0]."_".$thedate[1];

				}else{

					$between = $thedate[0].$thedate[1].$thedate[2].$lheure."0000' AND '".$thedatePlus[0].$thedatePlus[1].$thedatePlus[2].$lheure;
					$tableEnd = $thedate[0]."_".$thedate[1];
				}

				// Requete sur la table courante de production
				$thereq = "SELECT count(*) as nbreAppels
				  FROM [HN_Ondata].[dbo].[ODCalls]
				  where LastCampaign = '".$celHermesNum."' AND CallLocalTimeString BETWEEN '".$between."5959' ";

				$reqResult = mssql_query($thereq);
				$data = mssql_fetch_assoc($reqResult);

				// requete sur la table d'archive J+2
				$nomTable = "[HN_ONDATA_ARCHIVE].[dbo].[ODCalls_".$tableEnd."]";
				$thereq1 = "SELECT count(*) as nbreAppels
				  FROM $nomTable
				  where LastCampaign = '".$celHermesNum."' AND CallLocalTimeString BETWEEN '".$between."5959' ";

				$reqResult1 = mssql_query($thereq1);
				$data1 = mssql_fetch_assoc($reqResult1);

				array_push($nbreAppels, (int)$data['nbreAppels'] + (int)$data1['nbreAppels']);

				$lheure++;
			}

			mssql_close($link);

			return $nbreAppels;
		}

	}

	/**
	* Permet de faire des requetes sur les bases de données
	*
	*
	**/
	public function makeRequest($value=''){

		# code...
	}

	/**
	* Supprime un planning
	* et sauvegarde une copie dans la table plannings_del pour des besoins ultérieurs
	*
	* @param $id l'identifiant du planning à supprimer
	* @param $idCampagne
	*
	**/
	public function deletePlanning($id, $idCampagne){


		if ($_SESSION['userpermission']['permissions_planning']['del']) {

			// on récupère les lignes du planning avant suppression
			$delplanning = $this->Planning->getPlanning($id);

			if ($delplanning)
			{
				if (!class_exists('Planification')) {
					require_once(ROOT.'models/planification.php');
				}

				$ctrlPlanification = new Planification;

				// on récupère les lignes du planning avant suppression
				$delPlanification = $ctrlPlanification->getPlanification($id);

				// on format le resultat avant de l'enregistrer
				$delPlanificationSerialize = serialize($delPlanification);

				// on constitue les données à enregistrer dans la table planifications_del
				$data1 = array(
					'associateplanning_id' => $id,
					'planification_details' => $delPlanificationSerialize
					);

				// on fait une copie des lignes du planning avant suppression
				$result1 = $ctrlPlanification->addToTable($data1, 'planifications_del');

				// on récupère les lignes du planning avant suppression
				// $delplanning = $this->Planning->getPlanning($id);

				$delplanning = $delplanning[0];

				$data = array();

				// on format le resultat avant de l'enregistrer
				foreach ($delplanning as $key => $value) {

					if ($key == 'id') {
						$data['delplanning_id'] = $value;
					}else{

						if ($key == 'Cellule_idCellule') {
							// On enregistre pas la colonne 'Cellule_idCellule'
						}else{
							$data[$key] = $value;
						}
					}
				}

				// on fait une copie des lignes du planning avant suppression
				$result = $this->Planning->addToTable($data, "plannings_del");

				// on supprime le planning
				$this->Planning->del($id);

				// Enregistrement dans le journal
				$this->mylog->add($id, 3, 'a supprimé le planning');

				$message['succes'] = "Le planning a été suprimé avec succès";
				$this->set($message);
				$this->listPlanning($idCampagne);

			} else {

				$this->listPlanning($idCampagne);
			}

		} else {

			$this->listPlanning($idCampagne);
		}
	}

	/**
	* Supprime juste un planning
	*
	* @param $idPlanning identifiant du planning à supprimer
	*
	*
	**/
	public function justDelete($idPlanning){

		// on supprime le planning
		$this->Planning->del($idPlanning);

		// Enregistrement dans le journal
		$this->mylog->add($idPlanning, 3, 'a supprimé le planning');
	}

	/**
	* Permet d'enregister un planning crée
	*
	* @param $idPlanning identifiant du planning à enregistrer
	* @param $idCampagne identifiant de la vampagen concerné
	*
	*
	**/
	public function savePlanning($idPlanning, $idCampagne){


		$data = array("id" => $idPlanning, "niveauPlanning" => 1);

		if (!class_exists('planifications')) {
			require_once(ROOT.'controllers/planifications.php');
		}

		$ctrlPlanification = new planifications;

		$result = $this->Planning->save($data);

		// Enregistrement dans le journal
		$this->mylog->add($idPlanning, 1, 'a enregistré le planning');

		$message['succes'] = "Le planning a été enregistré avec succès";
		$ctrlPlanification->set($message);

		$ctrlPlanification->veiwPlanification($idPlanning, $idCampagne);
	}

	/**
	* Permet d'enregistrer la validation  ou non d'un planning
	*
	* @param $idPlanning identifiant du planning à enregistrer
	* @param $validNumber (2) pour un planning validé at (3) pour un planning non validé
	* @param $idCampagne identifiant de la campagne concernée
	*
	**/
	public function validPlanning($idPlanning, $validNumber, $idCampagne){

		if ($_SESSION['userpermission']['permissions_planning']['ok']) {

			$data = array("id" => $idPlanning, "niveauPlanning" => $validNumber);

			if (!class_exists('planifications')) {
				require_once(ROOT.'controllers/planifications.php');
			}

			$ctrlPlanification = new planifications;

			$result = $this->Planning->save($data);

			// Enregistrement dans le journal
			$this->mylog->add($idPlanning, 1, 'a validé le planning');

			$message['succes'] = "Le planning a été validé avec succès";
			$ctrlPlanification->set($message);

			$ctrlPlanification->veiwPlanification($idPlanning, $idCampagne);
		} else {

			// Appel de la classe de gestion des sessions
		}
	}

	/**
	* Permet d'exporter un planning validé
	*
	* @param récupère les informations par post
	*
	*
	**/
	public function exportPlanning(){

		if (!class_exists('Planification')) {
			require_once(ROOT.'models/planification.php');
		}

		$modelPlanification = new Planification;

		if (!class_exists('Planning')) {
			require_once(ROOT.'models/planning.php');
		}

		$modelPlanning = new Planning;

		if (!class_exists('Cellule')) {
			require_once(ROOT.'models/cellule.php');
		}

		$modelCellule = new Cellule;

		$planificationInfos = $modelPlanification->getPlanification($_POST['planningId']);

		$planningInfos = $modelPlanning->getPlanning($_POST['planningId']);
		$planningInfos = $planningInfos[0];

		$current = strtotime($planningInfos['periodeDebut']);

		$header['bas'] = array('Nom et prénoms');
		$header['haut'] = array('');

        $jourDeLaSemaine = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");

        foreach ($jourDeLaSemaine as $key => $value) {

        	$enteteCol = date("d-m-Y", $current);
	        array_push($header['haut'], $value);
	        array_push($header['bas'], $enteteCol);
	        $current = strtotime('+1 day', $current);
        }

        // On récupère l'identifiant de la cellule concernée
        $planningCel = $modelPlanification->getCelPlanning($_POST['planningId'], 'planning_has_cellule');
        $planningCel = $planningCel[0]['Cellule_idCellule'];

        // On récupère par la suite les informations sur la cellule
        $celInfos = $modelCellule->getCellule($planningCel);
        $celInfos = $celInfos[0];

        $allData = $this->tableFormatForPrint($planificationInfos);

		if($_POST['selectedFormat'] == 'pdf'){

			require_once(ROOT.'controllers/pdf.php');

			$pdf = new PDF('L');

			$pdf->SetFont('Arial','',12);

			$globalEntete = array(
				"title"		=> "Planning N ".$planningInfos['id']." / ".$celInfos['libCellule'],
				"periode"	=> "Du ".$planningInfos['periodeDebut']." au ".$planningInfos['periodeFin'],
				"celLib" 	=> "",
				);

			foreach ($allData as $keyAllData => $valueAllData) {

				foreach ($valueAllData as $keys => $values) {
					$globalEntete['vacTitle'] = $keys;

					$pdf->SetAuthor(' ');
					$pdf->AjouterChapitre($header,$globalEntete, $values);
				}
			}

			// Enregistrement dans le journal
			$this->mylog->add($_POST['planningId'], 6, 'a exporté le planning');

			$pdf->Output();

		} elseif($_POST['selectedFormat'] == 'msexcel') {

			$this->exportMsExcel($allData, $header, $celInfos, $planningInfos);
		}
	}

	/**
	* Permet d'exporter sous format excel
	*
	* @param $donnees les données à insérer dans le fichier excel
	* @param $entete entete des plannings (date)
	* @param $infosCel informations sur la cellule
	* @param $planningInfos informations
	*
	**/
	public function exportMsExcel($donnees, $entete, $infosCel, $planningInfos){

		require_once 'Classes/PHPExcel.php';
		require_once 'Classes/PHPExcel/Writer/Excel2007.php';
		require_once 'Classes/PHPExcel/IOFactory.php';

		$classeur =  new PHPExcel;

		// Style font 1
		$style_font_b_arial_11_black = array(
			'font' => array(
				'bold' => true,
				'size' => 11,
				'name' => 'Arial',
				'color' => array('rgb' => '000000')
			)
		);

		// Style font 2
		$style_font_b_arial_12_black = array(
			'font' => array(
				'bold' => true,
				'size' => 12,
				'name' => 'Arial',
				'color' => array('rgb' => '000000')
			)
		);

		// Style font 3
		$style_font_b_arial_26 = array(
			'font' => array(
				'bold' => true,
				'size' => 26,
				'name' => 'Arial',
				'color' => array('argb' => 'FFFFFF')
			)
		);

		// Style font cellule jour de repos et autres
		$style_font_b_arial_jrDeReposEtAutres = array(
			'font' => array(
				'bold' => true,
				'size' => 11,
				'name' => 'Arial',
				'color' => array('argb' => 'FFFFFF')
			)
		);

		// Style centrer
		$style_aligment_h_center_v_center = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'verticat' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				)
			);

		// Style couleur 1
		$style_fill_grey = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('argb' => 'CCCCCC')
				)
			);

		// Style couleur 2
		$style_fill_mcb_color = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('argb' => '2D1805')
				)
			);

		// Style couleur 3
		$style_fill_mcb_color2 = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('argb' => 'D4CBC6')
				)
			);

		// Style couleur cellule jour de repos et autres
		$style_fill_mcb_color3 = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('argb' => '8B6B4A')
				)
			);

		// Style bordure
		$style_border = array(
	       'borders' => array(
	             'allborders' => array(
	                    'style' => PHPExcel_Style_Border::BORDER_THIN,
	                    'color' => array('argb' => '000000'),
	                    ),
	             ),
	       );

		$tempnomFeuille = explode('/', $infosCel['libCellule']);

		if (sizeof($tempnomFeuille) > 1) {

			$nomFeuille = $tempnomFeuille[0]."_".$tempnomFeuille[1];
		}else {

			$nomFeuille = $infosCel['libCellule'];
		}

		$feuille = $classeur->getActiveSheet();
		$feuille->setTitle($nomFeuille);
		$feuille->getColumnDimension("A")->setWidth(40);
		$feuille->getColumnDimension("B")->setWidth(15);
		$feuille->getColumnDimension("C")->setWidth(15);
		$feuille->getColumnDimension("D")->setWidth(15);
		$feuille->getColumnDimension("E")->setWidth(15);
		$feuille->getColumnDimension("F")->setWidth(15);
		$feuille->getColumnDimension("G")->setWidth(15);
		$feuille->getColumnDimension("H")->setWidth(15);
		$feuille->mergeCells("A3:H3");
		$feuille->getRowDimension(3)->setRowheight(60);
		$feuille->setCellValue("A3", "PLANNING S".$planningInfos['numSemaine']."- Du ".$planningInfos['periodeDebut']." Au ".$planningInfos['periodeFin']);
		$lescel = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');
		$ligne = 5;
		foreach ($donnees as $key => $value) {
			foreach ($value as $subkey => $subvalue) {
				/* Initialisation des entetes de chaque vacation */
				$feuille->setCellValueByColumnAndRow(0, $ligne, $subkey." | 7h - 15h");
				$colrow = "A".$ligne;
				$colrowspan = "A".$ligne.":H".$ligne;
				$feuille->getRowDimension($ligne)->setRowheight(30);
				$feuille->mergeCells($colrowspan);
				$feuille->getStyle($colrow)->applyFromArray($style_font_b_arial_12_black);
				$feuille->getStyle($colrow)->applyFromArray($style_aligment_h_center_v_center);
				$feuille->getStyle($colrow)->applyFromArray($style_fill_mcb_color2);
				$feuille->getStyle($colrowspan)->applyFromArray($style_border);
				$ligne++;
				$colrow = "A".$ligne;
				$colrowspan = "A".$ligne.":H".$ligne;

				foreach ($lescel as $lescelkey => $lescelvalue) {
					$temp = $ligne+1;
					$currentcel = "$lescelvalue$ligne:$lescelvalue".$temp;
					$feuille->mergeCells($currentcel);
				}

				foreach ($entete as $entetekey => $entetevalue) {

					foreach ($entetevalue as $subentetekey => $subentetevalue) {
						$celcolrow = $lescel[$subentetekey].$ligne;
						$feuille->setCellValue($celcolrow, $subentetevalue);
						$feuille->getStyle($colrowspan)->applyFromArray($style_border);
						$feuille->getStyle($celcolrow)->applyFromArray($style_font_b_arial_11_black);
						$feuille->getStyle($celcolrow)->applyFromArray($style_fill_grey);

						if ($lescel[$subentetekey] != "A") {
							$feuille->getStyle($celcolrow)->applyFromArray($style_aligment_h_center_v_center);
						}
					}

					$ligne++;
					$colrow = "A".$ligne;
					$colrowspan = "A".$ligne.":H".$ligne;
				}

				/* Fin Initialisation des entetes de chaque vacation */

				foreach ($subvalue as $subkey2 => $subvalue2) {

					foreach ($subvalue2 as $subkey3 => $subvalue3) {

						$celName = $lescel[$subkey3].$ligne;

						$firstStr = substr($subvalue3, 0, 1);

						if ($firstStr == "-") {

							$subvalue3 = substr($subvalue3, 1);

							$feuille->setCellValueByColumnAndRow($subkey3, $ligne, $subvalue3);
							$feuille->getStyle($celName)->applyFromArray($style_fill_mcb_color3);
							$feuille->getStyle($celName)->applyFromArray($style_font_b_arial_jrDeReposEtAutres);
						}else{

							$feuille->setCellValueByColumnAndRow($subkey3, $ligne, $subvalue3);
						}

						if ($lescel[$subkey3] != "A") {

							$feuille->getStyle($celName)->applyFromArray($style_aligment_h_center_v_center);
						}

						$feuille->getRowDimension($ligne)->setRowheight(25);

						$feuille->getStyle($celName)->applyFromArray($style_border);

					}

					$ligne++;
				}

				$ligne += 2;
			}
		}
		$feuille->getStyle("A3")->applyFromArray($style_aligment_h_center_v_center);
		$feuille->getStyle("A3")->applyFromArray($style_font_b_arial_26);
		$feuille->getStyle("A3")->applyFromArray($style_fill_mcb_color);
		$feuille->getStyle("A3:H3")->applyFromArray($style_border);
		$writer = new PHPExcel_Writer_Excel2007($classeur);
		$writer->setOffice2003Compatibility(true);
		// Le fichier est enregistré avec la nomaclature suivante : Numéro du planning / Numéro de la cellule / Numéro de la campagne
		$docName = "assets/docs/fichiersExcel/planning_".$planningInfos['id']."_".$infosCel['id']."_".$infosCel['Campagne_idCampagne'].".xlsx";
		$writer->save($docName);

		$taille = filesize($docName);
		$docNameTable = explode('/', $docName);
		$docNameTable = $docNameTable[3];

		// On télécharge le document excel
		echo '<iframe id="helpFrame" style="display:none;" src="'.WEBROOT.$docName.'"></iframe>';

		if (!class_exists('planifications')) {
			require_once(ROOT.'controllers/planifications.php');
		}

		$ctrlPlanification = new planifications;

		$ctrlPlanification->veiwPlanification(482 , 2);

	}

	/**
	* Permet d'ajouter un commentaire à un planning
	*
	* @param le paramètres sont reçus par post
	*
	*
	**/
	public function addComment(){

		if ($_SESSION['userpermission']['permissions_planning']['comment']) {

			$data = array(
				"id"			=> $_POST['planningId'],
				"commentaire"	=> mysql_real_escape_string($_POST['planningComment'])
				);

			$idplanning = $this->Planning->add($data);

			// Enregistrement dans le journal
			$this->mylog->add($_POST['planningId'], 1, 'a ajouté un nouveau commentaire au planning');

			if (!class_exists('planifications')) {
				require_once(ROOT.'controllers/planifications.php');
			}

			$ctrlPlanification = new planifications;

			$message['succes'] = "Le commentaire a été ajouté avec succès";
			$ctrlPlanification->set($message);

			$ctrlPlanification->veiwPlanification($_POST['planningId'], $_POST['idCampagne']);
		} else {
			# code...
		}
	}

	/**
	* Permet d'ajouter un message pour un planning
	*
	* @param le paramètres sont reçus par post
	*
	*
	**/
	public function addMessage(){

		if ($_SESSION['userpermission']['permissions_planning']['comment']) {

			$msgSerialized = serialize(
					array(
					'titre' => $_POST['titreMessage'],
					// 'message' => preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $_POST['planningMessage']),
					'message' => $_POST['planningMessage'],
					));

			$data = array(
				'id'      => $_POST['planningId'],
				'message' => mysql_real_escape_string($msgSerialized)
				);

			$idplanning = $this->Planning->add($data);

			// Enregistrement dans le journal
			// $this->mylog->add($_POST['planningId'], 1, 'a ajouté un nouveau message au planning');

			if (!class_exists('planifications')) {
				require_once(ROOT.'controllers/planifications.php');
			}

			$ctrlPlanification = new planifications;

			$message['succes'] = "Le message a été ajouté avec succès";
			$ctrlPlanification->set($message);

			$ctrlPlanification->veiwPlanification($_POST['planningId'], $_POST['idCampagne']);
		} else {
			# code...
		}
	}

	/**
	* Permet de récupérer le dernier planning d'une cellule donnée
	*
	* @param $idCellule identifaint de la cellule
	*
	**/
	public function getCelLastPlanning($idCellule){

		if (!class_exists('Planification')) {
			require_once(ROOT.'models/planification.php');
		}

		$planification = new Planification;

		if (!class_exists('Cellule')) {
			require_once(ROOT.'models/cellule.php');
		}

		$cellule = new Cellule;

		// On récupère l'identifiant du dernier planning de la cellule
		$lastPlanning = $this->Planning->getLastCelPlanning($idCellule);
		$lastPlanning = $lastPlanning[0];

		// on récupère par la suite toutes les lignes de planning concerné
		$lastPlanning['id'] = $planification->getPlanification($lastPlanning['id']);

		$allData = $this->tableFormatForPrint($lastPlanning['id']);

		exit(json_encode($allData));
		// exit(serialize($allData));
	}

	/**
	* Permet de récupérer les informations sur le dernier planning d'une cellule donnée
	*
	* @param $idCellule identifaint de la cellule
	*
	**/
	public function getCelLastPlanningInfos($idCellule){

		if (!class_exists('Planification')) {
			require_once(ROOT.'models/planification.php');
		}

		$planification = new Planification;

		if (!class_exists('Cellule')) {
			require_once(ROOT.'models/cellule.php');
		}

		$cellule = new Cellule;

		// On récupère l'identifiant du dernier planning de la cellule
		$lastPlanning = $this->Planning->getLastCelPlanning($idCellule);
		$lastPlanning = $lastPlanning[0];

		// on récupère par la suite toutes les lignes de planning concerné
		$thePlanning = $this->Planning->getPlanning($lastPlanning['id']);

		exit(json_encode($thePlanning));
		// exit(serialize($allData));
	}

	/**
	* Permet de récuperer le dernier planning validé d'un agent
	*
	* @param $idAgent log HERMES de l'agent concerné
	* @param $idCellule identifaint de la cellule
	*
	**/
	public function getAgentLastPlanning($idCellule, $idAgent){

		if (!class_exists('Planification')) {
			require_once(ROOT.'models/planification.php');
		}

		$planification = new Planification;

		if (!class_exists('Cellule')) {
			require_once(ROOT.'models/cellule.php');
		}

		$cellule = new Cellule;

		if (!class_exists('Agent')) {
			require_once(ROOT.'models/agent.php');
		}

		$agent = new Agent;

		// On recuper l'identifiant de l'agent via son log HERMEs
		// $idAgent = $agent->getAgentByLog($logAgent);
		// $idAgent = $idAgent[0]['id'];

		// On récupère l'identifiant du dernier planning de la cellule
		$lastPlanning = $this->Planning->getLastCelPlanning($idCellule);
		$lastPlanning = $lastPlanning[0];

		// on récupère par la suite toutes les lignes de planning concerné
		$lastPlanning['id'] = $planification->getLastPlanificationAgent($idAgent, $lastPlanning['id']);

		$planningFormat = $this->tableFormatForPrint($lastPlanning['id']);

		$allData = array(
			'periodeDebut' => $lastPlanning['periodeDebut'],
			'periodeFin'   => $lastPlanning['periodeFin'],
			'lePlanning'   => $planningFormat
			);

		exit(json_encode($allData));
		// exit(serialize($allData));
	}

	/**
	* Permet d'afficher tous les commentaires envoyes par les agents
	*
	*
	**/
	public function viewAllComment(){

		if (!class_exists('Comment')) {
			require_once(ROOT.'models/comment.php');
		}

		$comment = new Comment;

		if (!class_exists('Planning')) {
			require_once(ROOT.'models/planning.php');
		}

		$planning = new Planning;

		$allComment = $comment->getAll();

		foreach ($allComment as $keyComment => $valueComment) {

			$infosPlanning = $planning->getPlanning($valueComment['idPlanning']);
			$infosPlanning = $infosPlanning[0];

			$allComment[$keyComment]['idCellule'] = $infosPlanning['Cellule_idCellule'];

			mysql_select_db('portail_db');

			$sql = "SELECT Nom, Prenom FROM portail_db.user WHERE newLogin='".$valueComment['loginAgent']."'";

			$req = mysql_query($sql) or die(mysql_error()."<br/> => ".mysql_query());
			$infosAgent = mysql_fetch_assoc($req);

			$allComment[$keyComment]['nomPrenoms'] = $infosAgent['Nom'].' '.$infosAgent['Prenom'];

			mysql_select_db('planning_db');
		}

		$data['allComment'] = $allComment;

		$this->set($data);

		$titre['pagetitle']	= "Liste commentaires";
		$this->set($titre);
		$this->render('viewallcomment');
	}

	/**
	* Format les données reçues en paramètres afin de les adaptées pour l'impression pdf
	*
	* @param $donnees les données à formater
	*
	* @return $allVacation un tableau des données formatées
	*
	**/
	private function tableFormatForPrint($donnees){

		// on récupère la cellule concernée afin de prendre les vacations de cette dernière
		$celId = $this->Planning->getPlanningCel($donnees[0]['Planning_idPlanning'], "planning_has_cellule");
		$celId = $celId[0]['Cellule_idCellule'];

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$modelVacation = new Vacation;

		if (!class_exists('Agent')) {
			require_once(ROOT.'models/agent.php');
		}

		$modelAgent = new Agent;

		// on récupère les vacations de la cellule
		$celVacation = $modelVacation->getCelVacation($celId, "cellule_has_vacation");

		// tableau de toutes les vacations
		$allVacation = array();

		// Pour chaque vacation consitué un tableau avec le détail de la planification
		foreach ($celVacation as $key => $value) {

			$vacationName = $modelVacation->getVacationName($value['Vacation_idVacation']);

			// pour accueillir le tableau pour chaque vacation
			$vacationTable = array();
			$vacationTable[$vacationName] = array();

			foreach ($donnees as $keyd => $valued) {

				// pour accueillir chaque ligne du tableau de chaque vacation
				$vacationLine = array();

				if ($valued['Vacation_idVacation'] == $value['Vacation_idVacation']){

					// on récupère les informations sur l'agent
					$infosAgent = $modelAgent->getAgent($valued['Agent_idAgent']);
					$infosAgent = $infosAgent[0];

					// On désirialize le champ details
					$details = unserialize($valued['details']);

					array_push($vacationLine, trim($infosAgent['nomAgent']).' '.trim($infosAgent['prenomAgent']));

					foreach ($details as $key => $valueJours) {

						$temp = substr($valueJours['start'], 0, 1);

						if (ctype_digit($temp)) {

							array_push($vacationLine, $valueJours['start'].'h - '.$valueJours['end'].'h');
						} else {
							array_push($vacationLine, "-".$valueJours['start']);
						}
					}

					array_push($vacationTable[$vacationName], $vacationLine);
				}

			}

			array_push($allVacation, $vacationTable);
		}

		return $allVacation;
	}

	/**
	* Test si un tableau contient des valeurs négatives ou une chaine vide
	*
	* @param $theArray le tableau à tester
	*
	* @return boolean
	*
	**/
	public function tableTestValue($theArray){

		foreach ($theArray as $valuetest) {
			if ($valuetest == "" || $valuetest <= 0 ) {
				return true;
			}
		}
	}

}
?>
