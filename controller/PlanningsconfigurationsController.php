<?php

	/**
	*
	*/
	class PlanningsconfigurationsController extends Controller
	{
		/**
		*
		*
		**/
		function __construct()
		{

		}

		/**
		* Permet d'afficher la liste des campagnes (MOOV, MTN, etc) et les différentes cellules qui la compose
		*
		**/
		public function index()
		{
				$this->loadModel('Campagnes', 'planning');
				$this->loadModel('Cellules', 'planning');

				$campagnes = $this->Campagnes->find();
				$this->set('Campagnes', $campagnes);

				$campAndCel = array();

				if ($campagnes) {

						foreach ($campagnes as $key => $value) {

						$tempCel = $this->Cellules->find(array('conditions' => 'id='.$value->id));

						$tempTab = array();

						array_push($tempTab, $value);
						array_push($tempTab, $tempCel);
						array_push($campAndCel, $tempTab);
					}
				}

				$this->set('campAndCel', $campAndCel);

				$this->render('planningsconfigurations/index');
		}

		/**
		* Permet d'afficher le détails sur une cellule (liste des vacations de la cellule, listes agents affectés)
		* offre aussi la possibilité d'ajout
		*
		* @param idCampagne l'identifiant de la campagne concernée
		* @param idCellule l'identifiant de la cellule à afficher
		**/
		public function viewCellule($idCampagne, $idCellule)
		{
			if ($_SESSION['userpermission']['permissions_cellule']['view']) {

				$this->loadModel('Campagnes', 'planning');
				$this->loadModel('Cellules', 'planning');
				$this->loadModel('Vacations', 'planning');
				$this->loadModel('Periodicites', 'planning');

				if (!class_exists('Agent')) {
					require_once(ROOT.'models/agent.php');
				}

				if (!class_exists('planification')) {
					require_once(ROOT.'controllers/planifications.php');
				}

				$planification = new planifications;

				$camp = $campagne->getCampagne($idCampagne);
				$data['camp'] = $data['camp'][0];

				$data['cel'] = $cellule->getCellule($idCellule);
				$data['cel'] = $data['cel'][0];

				$agent = new Agent;

				$celPeriodicite = $periodicite->getCelPeriodicite($idCellule);
				$data['celPeriodicite'] = $celPeriodicite[0];

				// On récupère les id des vacations de la cellule concernée
				$celVacations = $vacation->getCelVacation($idCellule, 'cellule_has_vacation');

				// On récupère les informations sur les vacation de la cellule
				if ($celVacations) {

					$data['vacList'] = array();
					for ($i=0; $i < sizeof($celVacations); $i++) {

						$templesvacations = $vacation->getVacation($celVacations[$i]['Vacation_idVacation']);
						$templesvacations = $templesvacations[0];
						array_push($data['vacList'], $templesvacations);
					}

				}else{

					$data['vacList'] = false;
				}

				// On récupère les id des agents de la cellule concernée
				$celAgents = $agent->getCelAgent($idCellule, 'cellule_has_agent');

				// On récupère les informations sur les agents de la cellule
				if ($celAgents) {

					$data['agentList'] = array();

					for ($i=0; $i < sizeof($celAgents); $i++) {

						$tempagents = $agent->getAgent($celAgents[$i]['Agent_idAgent']);
						$tempagents = $tempagents[0];
						array_push($data['agentList'], $tempagents);
					}

					foreach ($data['agentList'] as $keyres => $valueres) {

						$tempHistoriquePlanning = $planification->planningHistory($valueres['id']);
						$data['agentList'][$keyres]['historiquePlanning'] = $tempHistoriquePlanning;
					}

				} else {

					$data['agentList'] = false;
				}

				$agentHasCel = $agent->getAgentHasCel("cellule_has_agent");
				$allAgent = $agent->getAll();

				// constitution d'un tableau des agents n'ayant pas de cellule
				$data['agentHasNotCel'] = array();

				$agentHasCelId = array();

				if ($agentHasCel) { // on vérifie sur il y a au moins des agents qui ont déjà été affecté dans des cellules

					foreach ($agentHasCel as $value) {
						array_push($agentHasCelId, $value['Agent_idAgent']);
					}

					for ($i=0; $i < sizeof($allAgent); $i++) {

						if (!in_array($allAgent[$i]['id'], $agentHasCelId))
						{
							array_push($data['agentHasNotCel'], $allAgent[$i]);
						}
					}

				}else{

					$data['agentHasNotCel'] = $allAgent;
				}

				$data['idCellule'] = $idCellule;
				$data['idCampagne'] = $idCampagne;

				$this->set($data);

				$titre['pagetitle'] = $data['camp']['nomCampagne']." > ".$data['cel']['libCellule'];
				$this->set($titre);
				$this->render('viewcellule');
			} else {
				$this->index();
				# code...
			}

		}

		/**
		* Permet d'affecter les agents à une cellule
		*
		*
		**/
		public function addAgentToCel()
		{
			if ($_SESSION['userpermission']['permissions_addagent']['add']) {

				if (isset($_POST['Agent_idAgent']) && !empty($_POST['Agent_idAgent']))
				{
					foreach ($_POST as $key => $value) {

						if ($key != 'tableAddAgent_length' && $key != 'campCelName' && $key != 'celName') {
							$data[$key] = $_POST[$key];
						}
					}

					require_once(ROOT.'models/agent.php');
					$agent = new Agent;

					foreach ($data['Agent_idAgent'] as $value) {

						$data = array('Cellule_idCellule' => $data['Cellule_idCellule'], 'Agent_idAgent' => $value);
						$agent->addToTable($data, "cellule_has_agent");
					}

					$message['succes'] = "L'agent(s) a été ajouté avec succès";
					$this->set($message);
					$this->viewCellule($_POST['campCelName'], $_POST['celName']);

				} else {

					$message['echec'] = "Veillez selectionner au moins un agent svp !";
					$this->set($message);
					$this->viewCellule($_POST['campCelName'], $_POST['celName']);
				}

			} else {

				// si l'utilisateur n'a pas ce droit on le redirectionne sur la page d'index
				$this->index();
			}

		}

		/**
		* Permet d'enregistrer les agents ayant abandonné
		* @param les paramètres sont envoyé par la methode _POST
		*
		**/
		public function abandonAgent()
		{
			if ($_SESSION['userpermission']['permissions_addagent']['del']) {

				require_once(ROOT.'models/agent.php');
				$agent = new Agent;

				$dateAbandon = ($_POST['dateadanbon'] != '') ? $_POST['dateadanbon'] : date('d-m-Y') ;
				$dataSave = array(
					'Agent_idAgent'		=> $_POST['idAgent'],
					'dateabandon' 		=> $dateAbandon,
					'Cellule_idCellule' => $_POST['agentCel'],
					'raisonabandon' 	=> $_POST['raisonabandon']
					);

				// On ajoute l'agent dans la table des abandons
				$delCel = $agent->saveToTable($dataSave, "agent_has_abandon");

				// On supprime l'agent de la cellule a laquelle il a été affecté
				$delCel = $agent->delFromSpecTable("Agent_idAgent=".$_POST['idAgent'], "cellule_has_agent");

				$message['succes'] = "L'agent a été supprimé de la liste des CSCD avec succès";
				$this->set($message);

				$this->viewCellule($_POST['agentCamp'], $_POST['agentCel']);

			} else {
				# code...
				$this->index();
			}
		}

		/**
		* Permet d'ajouter une nouvelle campagne
		*
		*
		**/
		public function addCampagne()
		{

			if ($_SESSION['userpermission']['permissions_camp']['add']) {

				if (isset($_POST['nomCampagne']) && !empty($_POST['nomCampagne']))
				{
					$data = $_POST;
					require_once(ROOT.'models/campagne.php');
					$campagne = new Campagne;

					//
					foreach ($data as $keyEnre => $valueEnre) {

						$data[$keyEnre] = mysql_real_escape_string($valueEnre);
					}

					$campagne->add($data);

					$message['succes'] = "La campagne a été ajouté avec succès";
					$this->set($message);
					$this->index();
				} else {

					$message['echec'] = "Merci de renseigner un nom.";
					$this->set($message);
					$this->index();
				}

			} else {

				$this->index();
			}
		}

		/**
		* Permet d'ajouter une nouvelle cellule
		*
		*
		**/
		public function addCellule()
		{

			if ($_SESSION['userpermission']['permissions_cellule']['add']) {

				if (isset($_POST['libCellule']) && !empty($_POST['libCellule']))
				{
					$data = $_POST;

					if (!class_exists('Cellule')) {
						require_once(ROOT.'models/cellule.php');
					}

					$cellule = new Cellule;

					foreach ($data as $keyEnre => $valueEnre) {

						$data[$keyEnre] = mysql_real_escape_string($valueEnre);
					}

					$cellule->add($data);

					$message['succes'] = "La cellule a été ajouté avec succès";
					$this->set($message);
					$this->index();

				} else {

					$message['echec'] = "Merci de renseigner un nom";
					$this->set($message);
					$this->index();
				}

			} else {

				$this->index();
				# code...
			}
		}

		/**
		* Permet d'ajouter une nouvelle vacation à une cellule
		*
		* @param Les paramètres sont envoyés par la methode post
		*
		**/
		public function addVacToCellule()
		{
			if ($_SESSION['userpermission']['permissions_vacation']['add']) {
				# code...

				if (
					(isset($_POST['heureDebut']) && !empty($_POST['heureDebut'])) &&
					(isset($_POST['heureFin']) && !empty($_POST['heureFin']))
					)
				{

					foreach ($_POST as $key => $value) {

						if ($key != 'campCelName' && $key != 'celName') {
							$data[$key] = $_POST[$key];
						}
					}

					if ($data['libVacation'] == "Matinée") {
						$data['niveau'] = 1;
					} elseif($data['libVacation'] == "Soirée") {
						$data['niveau'] = 2;
					} elseif($data['libVacation'] == "Nuit") {
						$data['niveau'] = 3;
					}

					if (($_POST['heureFin'] < $_POST['heureDebut']) && ($_POST['heureFin'] != 0)) {
						$data['niveau'] = 3;
					}

					if (!class_exists('Vacation')) {
						require_once(ROOT.'models/vacation.php');
					}

					$data['distributionHeures'] = serialize($_POST['distributionHeures']);

					$vacation = new Vacation;

					$idinsert = $vacation->add($data);

					$data2 = array('Cellule_idCellule' => $_POST['celName'], 'Vacation_idVacation' => $idinsert);

					$vacation->addToTable($data2, "cellule_has_vacation"); // Permet d'inserer dans une table spécifique

					$message['succes'] = "La vacation a été ajouté avec succès";
					$this->set($message);
					$this->viewCellule($_POST['campCelName'], $_POST['celName']);

				} else {

					$message['echec'] = "Merci d'indiquer  les heures de vacation svp !";
					$this->set($message);
					$this->viewCellule($_POST['campCelName'], $_POST['celName']);
				}
			} else {

				$this->index();
				# code...
			}
		}

		/**
		* Permet de définir la périodicité des plannings d'une cellule
		*
		* @param Les paramètres sont envoyés par la methode post
		*
		**/
		public function addPeriodiciteToCellule()
		{
			// Vérification des permissions pour l'utilisateur
			if ($_SESSION['userpermission']['permissions_vacation']['add']) {

				// Si la variable $_POST['typejrderepos'] est définie est n'est pas vide
				if (isset($_POST['typejrderepos']) && !empty($_POST['typejrderepos'])) {

					if (!class_exists('Vacation')) {
						require_once(ROOT.'models/periodicite.php');
					}

					$periodicite = new Periodicite;

					if($_POST['typejrderepos'] == 2 ) {
						// Cas jour de repos rotatif

						$data = array(
							'Cellule_idCellule' => $_POST['celName'],
							'nbrejrw' 			=> $_POST['nbrejrw'],
							'typejrderepos' 	=> $_POST['typejrderepos'],
							'nbrejrderepos' 	=> 0,
							'jrderepos1' 		=> 0,
							'jrderepos2' 		=> 0
						);

					} elseif($_POST['typejrderepos'] == 1 ) {
						// Cas jour de repos fixe

						$jrderepos1 = -1;
						$jrderepos2 = -1;

						if($_POST['nbrejrderepos'] == 1) {
							// Cas nombre jour de repos 1
							$jrderepos1 = $_POST['jrderepos1'];

						} elseif($_POST['nbrejrderepos'] == 2) {
							// Cas nombre jour de repos 2
							$jrderepos2 = $_POST['jrderepos2'];
						}

						$data = array(
							'Cellule_idCellule' => $_POST['celName'],
							'nbrejrw' 			=> $_POST['nbrejrw'],
							'typejrderepos' 	=> $_POST['typejrderepos'],
							'nbrejrderepos' 	=> $_POST['nbrejrderepos'],
							'jrderepos1' 		=> $jrderepos1,
							'jrderepos2' 		=> $jrderepos2
						);
					}

					$periodicite->save($data);

					$message['succes'] = "La périodicité a été défini avec succès";
					$this->set($message);
					$this->viewCellule($_POST['campCelName'], $_POST['celName']);
				}

			} else {

				$this->index();
			}
		}

		/**
		* Permet de définir la performance horaire de la cellule
		*
		* @param Les paramètres sont envoyés par la methode post
		*
		**/
		public function addPerformanceToCellule()
		{
			// Vérification des permissions pour l'utilisateur
			if ($_SESSION['userpermission']['permissions_vacation']['add']) {

				// Si la variable $_POST['performanceH'] est définie est n'est pas vide
				if (isset($_POST['performanceH']) && !empty($_POST['performanceH']))
				{
					if (!class_exists('Cellule')) {
						require_once(ROOT.'models/cellule.php');
					}

					$cellule = new Cellule;


					$data = array(
						'id' => $_POST['celName'],
						'performancehoraire' => $_POST['performanceH']
					);

					$cellule->save($data);
					$message['succes'] = "La performance horaire a été défini avec succès";
					$this->set($message);

				}

				$this->viewCellule($_POST['campCelName'], $_POST['celName']);

			} else {

				$this->index();
			}
		}

		/**
		* Permet de mettre à jour les vacations d'une cellule
		*
		*
		*
		**/
		public function updateCelVacation()
		{
			if ($_SESSION['userpermission']['permissions_vacation']['edit']) {
				# code...

				if (
					(isset($_POST['heureDebut']) && !empty($_POST['heureDebut'])) &&
					(isset($_POST['heureFin']) && !empty($_POST['heureFin']))
					)
				{

					foreach ($_POST as $key => $value) {

						if ($key != 'campCelName' && $key != 'celName') {
							$data[$key] = $_POST[$key];
						}
					}

					if (!class_exists('Vacation')) {
						require_once(ROOT.'models/vacation.php');
					}

					$vacation = new Vacation;

					$data['distributionHeures'] = serialize($_POST['distributionHeures']);

					$idinsert = $vacation->add($data);

					$message['succes'] = "La vacation a été modifié avec succès ";
					$this->set($message);
					$this->viewCellule($_POST['campCelName'], $_POST['celName']);

				} else {

					$message['echec'] = "Merci d'indiquer  les heures de vacation svp !";
					$this->set($message);
					$this->viewCellule($_POST['campCelName'], $_POST['celName']);
				}
			} else {
				# code...
				$this->index();
			}

		}

		/**
		* Permet de supprimer une vacation d'une cellule et retourne sur la page des cellules
		*
		* @param $id indentifiant de la vacation à supprimer
		* @param $idCampagne l'identidiant de la campagne
		* @param $idCellule l'identifiant de la cellule
		**/
		public function delCelVacation($id, $idCampagne, $idCellule)
		{
			if ($_SESSION['userpermission']['permissions_vacation']['del']) {
				# code...
				require_once(ROOT.'models/vacation.php');
				$vacation = new Vacation;

				// Récupération du nom de la vacation à supprimer
				$delVac = $vacation->getVacationName($id, "libVacation");

				$vacation->delete($id);

				$message['succes'] = "La vacation a été supprimé avec succès";
				$this->set($message);
				$this->viewCellule($idCampagne, $idCellule);
			} else {
				# code...
				$this->index();
			}

		}

		/**
		* Permet de supprimer une cellule
		*
		* @param $id indentifiant de la cellule à supprimer
		**/
		public function delCellule($id)
		{
			if ($_SESSION['userpermission']['permissions_cellule']['del']) {
				# code...
				require_once(ROOT.'models/cellule.php');
				$cellule = new Cellule;

				$delCel = $cellule->getCelluleName($id, "libCellule");

				$cellule->delete($id);

				$message['succes'] = "La cellule a été supprimé avec succès";
				$this->set($message);
				$this->index();
			} else {
				# code...
				$this->index();
			}

		}

		/**
		* Permet de supprimer un agent d'une cellule
		*
		* @param $id indentifiant de l'agent à supprimer
		*
		**/
		public function delAgentFromCel($id, $idCellule, $idCampagne)
		{
			if ($_SESSION['userpermission']['permissions_addagent']['del']) {
				# code...
				require_once(ROOT.'models/agent.php');
				$agent = new Agent;

				$delCel = $agent->delFromSpecTable("Agent_idAgent=".$id, "cellule_has_agent");

				$message['succes'] = "L'agent a été supprimé de la cellule avec succès";
				$this->set($message);
				$this->viewCellule($idCampagne, $idCellule);
			} else {
				# code...
				$this->index();
			}

		}

		/**
		* Permet de modifier une cellule
		*
		* @param $id indentifiant de la cellule à modifier
		*
		**/
		public function updateCellule()
		{
			if ($_SESSION['userpermission']['permissions_addagent']['edit']) {
				# code...

				if (isset($_POST['libCellule']) && !empty($_POST['libCellule'])){

					$data = $_POST;

					require_once(ROOT.'models/cellule.php');
					$cellule = new Cellule;

					$cellule->save($data);

					$message['succes'] = "La cellule a été modifié avec succès";
					$this->set($message);
					$this->index();

				} else {

					$message['echec'] = "Merci de renseigner le libellé de la cellule svp";
					$this->set($message);
					$this->index();
				}

			} else {
				# code...
				$this->index();
			}

		}

		/**
		* Permet de supprimer une campagne
		*
		* @param $id indentifiant de la campagne à supprimer
		*
		**/
		public function delCampagne($id){

			if ($_SESSION['userpermission']['permissions_camp']['del']) {
				# code...
				require_once(ROOT.'models/campagne.php');
				$campagne = new Campagne;

				$delcampagne = $campagne->getCampagneNom($id, "nomCampagne");

				$campagne->delete($id);

				$message['succes'] = "La campagne a été supprimé avec succès";
				$this->set($message);
				$this->index();
			} else {
				# code...
				$this->index();
			}

		}

		/**
		* Permet de mettre à jour la table des agents
		*
		*
		**/
		public function updateTableAgent(){

			$sqlVerification = "SELECT * FROM agents";

			$req = mysql_query($sqlVerification) or die(mysql_error()."<br/> => ".mysql_query());

			$donnees = array();

			if (!$req) {

				// La requete de selection n'a pas pu s'effectuée

			}else{

				while ($data = mysql_fetch_assoc($req)) {
					$donnees[] = $data;
				}

				$agentLog = array();

				// On récupère les Id uniquement
				foreach ($donnees as $value) {
					array_push($agentLog, $value['log']);
				}

				mssql_connect("10.0.5.12","sa","sa");
				mssql_select_db("HN_ADMIN");

				$req = "SELECT Ident, Nom, Prenom FROM Ident";

				$result = mssql_query($req);
				$temp = mssql_fetch_assoc($result);

				if (!class_exists('Agent')) {
					require_once(ROOT.'models/agent.php');
				}

				$agent = new Agent;

				while ($data = mssql_fetch_assoc($result)) {

					// $log = mysql_real_escape_string($data['Ident']);
					// $nom = mysql_real_escape_string($data['Nom']);
					// $prenom = mysql_real_escape_string($data['Prenom']);

					$log = utf8_encode($data['Ident']);
					$nom = utf8_encode($data['Nom']);
					$prenom = utf8_encode($data['Prenom']);

					if (!in_array($log, $agentLog)) { // On vérifie si l'agent est déjà enregistré dans la table agents

						$agent->add(array(
							'log'         => $log,
							'nomAgent'    => $nom,
							'prenomAgent' => $prenom
							));

						// $test = "INSERT INTO agents (log, nomAgent, prenomAgent) VALUES ('$log', '$nom', '$prenom')";

						// var_dump($test);

						// mysql_query($test) or die(mysql_error()."<br/>");
					}

				} // fin boucle $data = mssql_fetch_assoc($result)

			} // fin if (!$req)

			$this->index();
		}

		/**
		 *
		 * Affiche la fenetre de creaction des plannings manuels pour l'administration
		 *
		 *
		 **/
		public function planningManuel($idCampagne, $idCellule, $infosPlanification = NULL){

			if ($_SESSION['userpermission']['permissions_cellule']['add']) {

				if (!class_exists('Campagne')) {
					require_once(ROOT.'models/campagne.php');
				}

				if (!class_exists('Agent')) {
					require_once(ROOT.'models/agent.php');
				}

				$agent = new Agent;

				if (!class_exists('planification')) {
					require_once(ROOT.'controllers/planifications.php');
				}

				$planification = new planifications;

				if (!class_exists('Cellule')) {
					require_once(ROOT.'models/cellule.php');
				}

				$cellule = new Cellule;

				// Liste de tous les agents de la cellule en cours
				$allCelAgent = $cellule->getCelluleAgent($idCellule, "cellule_has_agent");

				// On récupère les id des agents de la cellule concernée
				$celAgents = $agent->getCelAgent($idCellule, 'cellule_has_agent');

				if ($infosPlanification == NULL) {

					// On récupère les informations sur les agents de la cellule
					if ($celAgents) {

						$data['agentList'] = array();

						for ($i=0; $i < sizeof($celAgents); $i++) {

							$tempagents = $agent->getAgent($celAgents[$i]['Agent_idAgent']);
							$tempagents = $tempagents[0];
							array_push($data['agentList'], $tempagents);
						}

						foreach ($data['agentList'] as $keyres => $valueres) {

							$tempHistoriquePlanning = $planification->planningHistory($valueres['id']);
							$data['agentList'][$keyres]['historiquePlanning'] = $tempHistoriquePlanning;
						}

					} else {

						$data['agentList'] = false;
					}
				}
				else{

					$agentHasPlanId = array();

					foreach ($infosPlanification as $valueAgent) {
						array_push($agentHasPlanId, $valueAgent['Agent_idAgent']['id']);
					}

					// On exclut les agents qui ont déjà été planifié
					$agentReserve = $planification->customArrayFilter($allCelAgent, $agentHasPlanId);

					if ($agentReserve) {

						foreach ($agentReserve as $keyRa => $valueRa) {
							$tempReq = $agent->getAgent($valueRa['Agent_idAgent']);
							$agentReserve[$keyRa] = $tempReq[0];

							array_push($infosPlanification, array("Agent_idAgent" => $tempReq[0]));
						}
					}

					$data['agentList'] = $infosPlanification;
				}

				$data['departement'] = $idCampagne;
				$data['cellule'] = $idCellule;

				$this->set($data);
				$titre['pagetitle'] = "Planning manuel";
				$this->set($titre);
				$this->render('planningmanuel');

			} else {

				if (!class_exists('home')) {
					require_once(ROOT.'controllers/home.php');
				}
				$home = new home;
				$home->index();
			}
		}

	}
 ?>
