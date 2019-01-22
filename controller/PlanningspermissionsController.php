<?php 

/**
* 
*/
class permissions extends Controller
{
	
	function __construct()
	{
		$this->LoadModel('Permission');

		if (!class_exists('logs')) {
			require(ROOT.'controllers/logs.php');
			$this->mylog = new logs;
		}
		
	}

	/**
	* Page d'accueil de permission et vacaton speciale
	*
	*
	**/
	public function index(){

		if (!class_exists('Cellule')) {
			require_once(ROOT.'models/cellule.php');
		}

		$cellule = new Cellule;

		$campCelList = array();

		if (isset($_SESSION['campagne']) && !is_null($_SESSION['campagne'])) {
			
			if ($_SESSION['campagne'] == 1) {

				$idCampagne = 2;
			} elseif($_SESSION['campagne'] == 2) {

				$idCampagne = 15;
			}

			$campCelList = $cellule->getCampcellule($idCampagne);

		}else{ // Si l'utilisateur n'est affecté à aucune campagne

			$idCampagne = 0;
			$campListe = array(2, 15);

			foreach ($campListe as $keycp => $valuecp) {

				$campCelListTemp = $cellule->getCampcellule($valuecp);

				if ($campCelListTemp) {

					foreach ($campCelListTemp as $keyp => $valuep) {

						array_push($campCelList, $valuep);
					}

				}
			}	
		}

		$campAgents = array();

		// On va uniquement prendre les agents des cellules concernées
		foreach ($campCelList as $key => $value) {
			
			$agents = $cellule->getCelluleAgent($value['id'], "cellule_has_agent");

			if ($agents) {
				foreach ($agents as $key2 => $value2) {
					array_push($campAgents, $value2);
				}				
			}
		}


		if (!class_exists('Agent')) {
			require_once(ROOT.'models/agent.php');
		}

		$agent = new Agent;

		foreach ($campAgents as $key3 => $value3) {

			$temp = $agent->getAgent($value3['Agent_idAgent']);
			$campAgents[$key3]['Agent_idAgent'] = $temp[0];
		}

		$data['campAgents'] = $campAgents;

		$this->set($data);

		$titre['pagetitle'] = "Gestion des permissions";
		$this->set($titre);
		$this->render('index');
	}

	/**
	* Permet d'afficher la liste de toutes les permissions si l'utilisateur connecté est autre qu'un team leader
	* ou la liste des permissions par campagne si c'est un team leader squi se connecte
	*
	* @param idCampagne identifiant de la campagne (paramètre optionel)
	*
	*
	**/
	public function viewAllPermissionsBy($idCampagne = '')
	{

		if ($idCampagne == '') {

			// on affiche toutes les permissions

		}else{

			// Uniquement les permissions de la campagne

		}

		$allPermission = $this->Permission->getAll();

		if ($allPermission) {
				
			if (!class_exists('Agent')) {
				require_once(ROOT.'models/agent.php');
			}

			$agent = new Agent;

			foreach ($allPermission as $key => $value) {
				
				$temp = $agent->getAgent($value['Agent_idAgent']);
				$allPermission[$key]['Agent_idAgent'] = $temp[0];
			}

		}

		$data['allPermission'] = $allPermission;

		// var_dump($data['allPermission']);

		$this->set($data);


		$titre['pagetitle'] = "Permissions";
		$this->set($titre);
		$this->render('listpermissions');
	}

	/**
	* Affiche l'historique des permissions pour un agent donné
	*
	* @param $idAgent identifiant de l'agent
	*
	*
	**/
	public function agentPermissionHistory($idAgent)
	{
		if (!class_exists('Agent')) {
			require_once(ROOT.'models/agent.php');
		}

		$agent = new Agent;

		if (!class_exists('Vacation')) {
			require_once(ROOT.'models/vacation.php');
		}

		$vacation = new Vacation;

		$temp = $agent->getAgent($idAgent);

		$data['agentInfos'] = $temp[0];

		$agentpermissionsHistory = $this->Permission->getAgentPermission($idAgent);

		$data['agentpermissionsHistory'] = $agentpermissionsHistory;

		$agentCel = $agent->getAgentCel($idAgent);

		$agentCel = $agentCel[0]['Cellule_idCellule'];

		$vacationList = $vacation->getCelVacation($agentCel, 'cellule_has_vacation');

		if ($vacationList) {
			foreach ($vacationList as $key => $value) {

				$temp = $vacation->getVacation($value['Vacation_idVacation']);
				$temp = $temp[0];
				$vacationList[$key]['Vacation_idVacation'] = $temp;
			}
		}

		$data['vacationList'] = $vacationList;

		$this->set($data);
		
		$titre['pagetitle'] = "Historique permissions de ";
		$this->set($titre);
		$this->render('agentpermissions');
	}

	/**
	* Permet d'enregistrer une demande de vacation spéciale
	*
	* @param les informations sont envoyées par POST
	*
	*
	**/
	public function enrNewVacSpec()
	{
		

		if (
			(isset($_POST['idVacation']) && !empty($_POST['idVacation'])) &&
			(isset($_POST['heureDebut']) && !empty($_POST['heureDebut'])) &&
			(isset($_POST['heureFin']) && !empty($_POST['heureFin'])) &&
			(isset($_POST['spVacationPeriodeDebut']) && !empty($_POST['spVacationPeriodeDebut'])) &&
			(isset($_POST['spVacationPeriodeFin']) && !empty($_POST['spVacationPeriodeFin']))

			) 
		{

			$horraires = array();
			$horraires['start'] = $_POST['heureDebut'];
			$horraires['end'] = $_POST['heureFin'];

			$horraires = serialize($horraires);

			$tempdate = explode('-', $_POST['spVacationPeriodeDebut']);
			$_POST['spVacationPeriodeDebut'] = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

			$tempdate = explode('-', $_POST['spVacationPeriodeFin']);
			$_POST['spVacationPeriodeFin'] = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

			$data = array(
				'motifid'				=> 1,
				'motif'					=> 'Vacation spéciale',
				'autremotif'			=> '',
				'ladate'				=> '0000-00-00',
				'periodeDebut'			=> $_POST['spVacationPeriodeDebut'],
				'periodeFin'			=> $_POST['spVacationPeriodeFin'],
				'horraires'				=> $horraires,
				'etat'					=> 0,
				'commentaires'			=> '',
				'observations'			=> mysql_real_escape_string($_POST['observations']),
				'dateedition'			=> date('j-m-Y, H:i:s'),
				'Vacation_idVacation'	=> $_POST['idVacation'],
				'Agent_idAgent'			=> $_POST['idAgent'],
				'idAuteur'				=> $_SESSION['userid'],
				'idValidateur'			=> 0,
				);

			$this->Permission->save($data);

			// Enregistrement dans le journal
			$this->mylog->add($_POST['idAgent'], 1, 'a enregistré une vacation spéciale pour un agent');

			$message['succes'] = "Vacation spéciale enregistrée avec succès";
			$this->set($message);

			$this->viewAllPermissionsBy();

		}else{

			$message['echec'] = "Merci de renseigner les champs obligatoires SVP";
			$this->set($message);
			$this->agentPermissionHistory($_POST['idAgent']);
		}

	}

	/**
	* Permet d'enregistrer une demande de permission (congé, formation, ...)
	*
	* @param les informations sont envoyées par POST
	*
	*
	**/
	public function enrNewPermission()
	{

		if (isset($_POST['datepermission']) && !empty($_POST['datepermission'])) {

			$auteur = (isset($_SESSION['userid'])) ? $_SESSION['userid'] : $_POST['idAgent'] ;

			$tempdate = explode('-', $_POST['datepermission']);
			$dateEn = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];
			
			$data = array(
				'motifid'				=> 2,
				'motif'					=> $_POST['typePermission'],
				'autremotif'			=> $_POST['autrePermission'],
				'ladate'				=> $dateEn,
				'periodeDebut'			=> '0000-00-00',
				'periodeFin'			=> '0000-00-00',
				'horraires'				=> '',
				'etat'					=> 0,
				'commentaires'			=> '',
				'observations'			=> mysql_real_escape_string($_POST['observations']),
				'dateedition'			=> date('j-m-Y, H:i:s'),
				'Vacation_idVacation'	=> 0,
				'Agent_idAgent'			=> $_POST['idAgent'],
				'idAuteur'				=> $auteur,
				'idValidateur'			=> 0,
				);

			$rslt = $this->Permission->save($data);

			if (isset($_POST['fromAPI']) && $_POST['fromAPI'] == 1) {
				return $rslt;
			}
			// Enregistrement dans le journal
			$this->mylog->add($_POST['idAgent'], 1, 'a enregistré une permission pour un agent');

			$message['succes'] = "Permission enregistrée avec succès";
			$this->set($message);

			$this->viewAllPermissionsBy();

		}elseif(	
					(isset($_POST['permissionPeriodeDebut']) && !empty($_POST['permissionPeriodeDebut'])) &&
					(isset($_POST['permissionPeriodeFin']) && !empty($_POST['permissionPeriodeFin'])) 
				) 
		{	

			$auteur = (isset($_SESSION['userid'])) ? $_SESSION['userid'] : $_POST['idAgent'] ;

			$tempdate = explode('-', $_POST['permissionPeriodeDebut']);
			$_POST['permissionPeriodeDebut'] = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

			$tempdate = explode('-', $_POST['permissionPeriodeFin']);
			$_POST['permissionPeriodeFin'] = $tempdate[2].'-'.$tempdate[1].'-'.$tempdate[0];

			$data = array(
				'motifid'				=> 2,
				'motif'					=> $_POST['typePermission'],
				'autremotif'			=> $_POST['autrePermission'],
				'ladate'				=> '0000-00-00',
				'periodeDebut'			=> $_POST['permissionPeriodeDebut'],
				'periodeFin'			=> $_POST['permissionPeriodeFin'],
				'horraires'				=> '',
				'etat'					=> 0,
				'commentaires'			=> '',
				'observations'			=> mysql_real_escape_string($_POST['observations']),
				'dateedition'			=> date('j-m-Y, H:i:s'),
				'Vacation_idVacation'	=> 0,
				'Agent_idAgent'			=> $_POST['idAgent'],
				'idAuteur'				=> $auteur,
				'idValidateur'			=> 0,
				);

			$rslt = $this->Permission->save($data);

			if (isset($_POST['fromAPI']) && $_POST['fromAPI'] == 1 ) {
				return $rslt;
			}
			// Enregistrement dans le journal
			$this->mylog->add($_POST['idAgent'], 1, 'a enregistré une permission pour un agent');

			$message['succes'] = "Permission enregistrée avec succès";
			$this->set($message);

			$this->viewAllPermissionsBy();
			
		}else{

			if (isset($_POST['fromAPI']) && $_POST['fromAPI'] == 1) {
				return false;
			}
			$message['echec'] = "Merci de renseigner les champs obligatoires SVP";
			$this->set($message);
			$this->agentPermissionHistory($_POST['idAgent']);
		}
	}

	/**
	* Valide ou non une permission
	*
	* @param $validationType le type de validation OUI ou NON
	* @param $idPermission identifiant de la permission
	* @param $idAgent identifiant de l'agent
	*
	**/
	public function validedPermission($validationType, $idPermission, $idAgent="")
	{
		if ($validationType == "valided") {
			$validationType = 1;
			$message['succes'] = "Permission accordée ";
		} else {
			$validationType = 2;
			$message['succes'] = "Permission non accordée ";
		}
		
		$data = array('id' => $idPermission, 'etat' => $validationType);
		$this->Permission->save($data);

		// Enregistrement dans le journal
		$this->mylog->add($idPermission, 5, 'a validé une permission pour un agent');
		
		$this->set($message);

		if($idAgent == "") {
			
			$this->viewAllPermissionsBy();
		}else{
			
			$this->agentPermissionHistory($idAgent);
		}	
	}

	/**
	* Ajout un commentaire à une permission ou vacation spéciale
	*
	* @param les informations sont reçu par post
	*
	*
	**/
	public function addComment()
	{
		// if ($_SESSION['userpermission']['permissions_planning']['comment']) {
			
			$data = array(
				"id"			=> $_POST['idPermission'],
				"commentaires"	=> $_POST['permissionComment']
				);

			$idpermission = $this->Permission->add($data);

			// Enregistrement dans le journal
			$this->mylog->add($_POST['idPermission'], 1, 'a ajouté un nouveau commentaire à une permission');

			$message['succes'] = "Le commentaire a été ajouté avec succès";
			$this->set($message);

			if($_POST['idAgent'] == "") {
			
				$this->viewAllPermissionsBy();
			}else{
				
				$this->agentPermissionHistory($_POST['idAgent']);
			}
		// } else {
			# code...
		// }
	}

	/**
	* Permet de supprimer une permission
	*
	* @param $idPermission identifiant de la permission à supprimer
	* @param $pageFrom page depuis laquelle la requete a été faite
	* @param $idAgent identifiant de l'agent
	*
	**/
	public function delPermission($idPermission, $idAgent="")
	{

		// on supprime la permission
		$this->Permission->del($idPermission);

		// Enregistrement dans le journal
		$this->mylog->add($idPermission, 3, 'a supprimé la permission');

		$message['succes'] = "La permission a été suprimé avec succès";
		$this->set($message);

		if($idAgent == "") {
			
			$this->viewAllPermissionsBy();
		}else{
			
			$this->agentPermissionHistory($idAgent);
		}		
	}

	/**
	* Permet de récupérer les périodes de permissions comprises entre la période de planning
	*
	* @param $peiodeStartIn liste des permissions dont la date de début est comprise entre la periode de planning
	* @param $peiodeEndIn liste des permissions dont la date de fin est comprise entre la periode de planning
	*
	*
	**/
	public function getPermissionInterval($peiodeStartIn, $peiodeEndIn)
	{

		$listePermissionsInternal['startIn'] = array();
		$listePermissionsInternal['allIn'] = array();
		$listePermissionsInternal['endIn'] = array();

		$peiodeStartInID = array();
		$peiodeEndInID = array();

		if ($peiodeStartIn) {
			
			foreach ($peiodeStartIn as $value) {
				array_push($peiodeStartInID, $value['id']);
			}
		}
		
		if ($peiodeEndIn) {
			
			foreach ($peiodeEndIn as $value) {
				array_push($peiodeEndInID, $value['id']);
			}
		}
		

		foreach ($peiodeStartInID as $value) {

			if (in_array($value, $peiodeEndInID)) {

				$lapermission = $this->Permission->getPermission($value);
				array_push($listePermissionsInternal['allIn'], $lapermission[0]);
			} else {
				
				$lapermission = $this->Permission->getPermission($value);
				array_push($listePermissionsInternal['startIn'], $lapermission[0]);
			}
		}

		foreach ($peiodeEndInID as $value) {

			if (!in_array($value, $peiodeStartInID)) {

				$lapermission = $this->Permission->getPermission($value);
				array_push($listePermissionsInternal['endIn'], $lapermission[0]);
			}
		}

		return $listePermissionsInternal;

	}

	/**
	* Permet de récupérer les périodes de vacations spéciales comprises entre la période de planning
	*
	* @param $peiodeStartIn liste des vacations spéciales dont la date de début est comprise entre la periode de planning
	* @param $peiodeEndIn liste des vacations spéciales dont la date de fin est comprise entre la periode de planniing
	*
	*
	**/
	public function getPermissionSpcInterval($peiodeStartIn, $peiodeEndIn)
	{
		$listePermissionsInternal['startIn'] = array();
		$listePermissionsInternal['allIn'] = array();
		$listePermissionsInternal['endIn'] = array();

		$peiodeStartInID = array();
		$peiodeEndInID = array();

		if ($peiodeStartIn) {
			
			foreach ($peiodeStartIn as $value) {
				array_push($peiodeStartInID, $value['id']);
			}
		}
		
		if ($peiodeEndIn) {
			
			foreach ($peiodeEndIn as $value) {
				array_push($peiodeEndInID, $value['id']);
			}
		}
		

		foreach ($peiodeStartInID as $value) {

			if (in_array($value, $peiodeEndInID)) {

				$lapermission = $this->Permission->getPermission($value);
				array_push($listePermissionsInternal['allIn'], $lapermission[0]);
			} else {
				
				$lapermission = $this->Permission->getPermission($value);
				array_push($listePermissionsInternal['startIn'], $lapermission[0]);
			}
		}

		foreach ($peiodeEndInID as $value) {

			if (!in_array($value, $peiodeStartInID)) {

				$lapermission = $this->Permission->getPermission($value);
				array_push($listePermissionsInternal['endIn'], $lapermission[0]);
			}
		}

		return $listePermissionsInternal;
	}

}

?>