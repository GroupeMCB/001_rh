<?php
/**
*
*/
class PersonnelsController extends Controller
{

	 
	public function agentdepartement(){


	}

	public function dashboard(){
		$an =array();
			$this->loadModel("Personnel");

			$effectif = $this->Personnel->findcount("1 = 1 AND active = 1");
			$this->set('effectif',$effectif);

			//effectif homme
			$effectifhomme = $this->Personnel->findcount("sexe = 'M' AND active = 1");
			$this->set('effectifhomme',$effectifhomme);

			//effectif femme
			$effectiffemme = $this->Personnel->findcount("sexe = 'F' AND active = 1 ");
			$this->set('effectiffemme',$effectiffemme);

			//effectif cdd
			$cdd = $this->Personnel->findcount("type_contrat = '2' AND active = 1");
			$this->set('cdd',$cdd);



			//effectif cdi
			$cdi = $this->Personnel->findcount("type_contrat = '3' AND active = 1");
			$this->set('cdi',$cdi);

				//effectif stage
			$stage = $this->Personnel->findcount("type_contrat = '5' AND active = 1");
			$this->set('stage',$stage);

			//moyenn d'age
			$touslesages = $this->Personnel->find( array("fields" => "YEAR(date_naissance) as date_nais"));

			foreach ($touslesages as $key => $value) {
			  $an[$key] = get_object_vars($value);
				 //$annee[$key] = date('Y') - $value['date_nais'];
			}

			$moye = array();
			foreach ($an as $k => $v) {
				if($v['date_nais'] != 0)
				   $moye[$k] = date('Y') - $v['date_nais'];
		     	//debug(date('Y') - $v['date_nais']);
			}
			 $ens_note=0;
			foreach($moye as $cle=>$note)
			{
				$ens_note+=$note;
			}

			if($effectif != 0)
				$note_moy= round($ens_note/$effectif);
				else
				$note_moy=0;

			$this->set('moyennegeneral',$note_moy);

			$homme30 = $this->Personnel->getagebypersonnel(0,30,'M');
			$homme35 = $this->Personnel->getagebypersonnel(30,35,'M');
			$homme39 = $this->Personnel->getagebypersonnel(35,39,'M');
			$homme40 = $this->Personnel->getagebypersonnel(39,1000,'M');

			$femme30 = $this->Personnel->getagebypersonnel(0,30,'F');
			$femme35 = $this->Personnel->getagebypersonnel(30,35,'F');
			$femme39 = $this->Personnel->getagebypersonnel(35,39,'F');
			$femme40 = $this->Personnel->getagebypersonnel(39,1000,'F');

			$this->set('homme30',$homme30);
			$this->set('homme35',$homme35);
			$this->set('homme39',$homme39);
			$this->set('homme40',$homme40);

			$this->set('femme30',$femme30);
			$this->set('femme35',$femme35);
			$this->set('femme39',$femme39);
			$this->set('femme40',$femme40);


			//Graph ancienneté
			$age3 = $this->Personnel->getanciennete(0,3);
			$age5 = $this->Personnel->getanciennete(3,5);
			$age8 = $this->Personnel->getanciennete(5,8);
			$age9 = $this->Personnel->getanciennete(8,100);
 			
			$this->set('age3',$age3->nombre);
			$this->set('age5',$age5->nombre);
			$this->set('age8',$age8->nombre);
			$this->set('age9',$age9->nombre);

		}


	function add($typepersonnel = null)
	{
		$this->loadModel('Departement');
		$departements = $this->Departement->find();
		$this->set('departements', $departements);

		$this->loadModel('Titre');
		$titres = $this->Titre->find();
		$this->set('titres', $titres);

		$this->loadModel('Categorie');
		$categories = $this->Categorie->find();
		$this->set('categories', $categories);

		$this->loadModel('Type_contrat');
		$typecontrats = $this->Type_contrat->find();
		$this->set('typecontrats', $typecontrats);

		$this->loadModel('Type_Personnel');
		$type_personnel = $this->Type_Personnel->find();
		$this->set('type_personnel', $type_personnel);

		$this->set('typepersonnel', $typepersonnel);

		$this->render('addform');
	}

	function viewList($typepersonnel)
	{
		$type = '';

		switch ($typepersonnel) {
			case 'contractuels':
				$type = 'contractuels';
				$cat = 1;

				break;

			case 'stagiaires':
				$type = 'stagiaires';
				$cat = 2;
				break;

			default:
				# code...
				break;
		}

		$this->loadModel('Personnel');

		$allPersonnelType = $this->Personnel->find(array
		    	('conditions' => 'categorie_id ='.$cat .' AND active = 1 ORDER BY nom asc' ) );

		foreach ($allPersonnelType as $value) {

			$temp = $this->Personnel->getcontrat($value->idpersonnel);
				//debug($temp);
			if(isset($temp[0])) {

			$value->typecontrat = $temp[0]->nom;
			}
		}

		$this->loadModel('Titre');
		$titres = $this->Titre->find();
		$this->set('titres', $titres);

		$this->loadModel('Departement');
		$departements = $this->Departement->find();
		$this->set('departements', $departements);

		$this->loadModel('Type_contrat');
		$typecontrats = $this->Type_contrat->find();
		$this->set('typecontrats', $typecontrats);

		$this->loadModel('Motif_sortie');
		$motifs_sortie = $this->Motif_sortie->find();
		$this->set('motifs_sortie', $motifs_sortie);

		$this->set($type, $allPersonnelType);
		$this->render($type.'_list');

	}

	function edit($typepersonnel, $id = null)
	{
		$this->loadModel('Personnel');

		$this->loadModel('Departement');
		$departements = $this->Departement->find();
		$this->set('departements', $departements);

		$this->loadModel('Titre');
		$titres = $this->Titre->find();
		$this->set('titres', $titres);

		$this->loadModel('Categorie');
		$categories = $this->Categorie->find();
		$this->set('categories', $categories);

		$this->loadModel('Type_contrat');
		$typecontrats = $this->Type_contrat->find();
		$this->set('typecontrats', $typecontrats);

		// $this->loadModel('statut');
		// $statut_agent = $this->statut->find();
		// $this->set('statut_agent', $statut_agent);



		$this->loadModel('Type_Personnel');
		$type_personnel = $this->Type_Personnel->find();
		$this->set('type_personnel', $type_personnel);

		$temp = $this->Personnel->findTypeContrat($id);

		if(!isset($temp[0]) ) $temp[0] = 0;
		$this->set('personnelcontrat', $temp[0]);

		if(!is_numeric($id) && !isset($id) )
		{
			$this->e404('Impossible d\'afficher cette page');
		}else{

			switch ($typepersonnel) {
				case 'contractuels':
					# code...
					break;

				case 'stagiaires':
					# code...
					break;

				default:
					# code...
					break;
			}


			if($this->request->data){


				$this->Personnel->save($this->request->data);
			}

		    $datapersonnel = $this->Personnel->find(array
		    	('conditions'=>'idpersonnel ='.$id));

		   	$datapersonnel = $datapersonnel[0];

		    if(empty($datapersonnel)){

		    	$this->e404('Impossible d\'afficher cette page');
		    	die();
		    }
		    // debug($datapersonnel);
		    // $this->Session->setFlash('Session ok','danger');
		    $this->set('datapersonnel', $datapersonnel);
		}

		$this->set('typepersonnel', $typepersonnel);

		$this->render('edit');
	}

	function view($typepersonnel, $id = null)
	{
		if(!isset($id))
		{
				$this->e404('Impossible d\'afficher cette page');
		}else{

			switch ($typepersonnel) {
				case 'contractuels':
					# code...
					break;

				case 'stagiaires':
					# code...
					break;

				default:
					# code...
					break;
			}

			$this->loadModel('Personnel');

			if($this->request->data){

				$this->Personnel->save($this->request->data);
			}


		    $datapersonnel = $this->Personnel->find(array
		    	('conditions'=>'idpersonnel ='.$id));

		    if(empty($datapersonnel)){

		    	$this->e404('Impossible d\'afficher cette page');
		    	die();
		    }
		    debug($datapersonnel);
		    $this->Session->setFlash('Session ok','danger');
		    $this->set('datapersonnel', $datapersonnel);
		}

	}

	function presence()
	{
		$this->loadModel('Personnel');

		$allPersonnelAbsent = $this->Personnel->personnelAbsence();
		$this->set('allpersonnelAbsent', $allPersonnelAbsent);
		$this->render('checkpresence');
	}

	function addabsencefrom($idpersonnel)
	{
		$this->loadModel('Personnel');

		$employe = $this->Personnel->find(array('conditions' => 'idpersonnel='.$idpersonnel));
		$this->set('employe', $employe[0]);
		$this->render('addabsenceform');

	}

	function addabsence()
	{
		$this->loadModel('Absence');

		$insertId = '';
		if($this->request->data){

			$insertId = $this->Absence->save($this->request->data);
		}

		if ($_POST['type_absence_id'] == 2) {

			$this->Session->write('idabsence', $insertId);
			$this->redirect('certificats/addcertificat/'.$_POST['personnel_id'], 30);
		}

		$this->Session->setFlash('Absence ajoutée avec succès', 'success');
		$this->presence();
	}

	public function delAbsence()
	{
		$this->loadModel('Absence');

		if($this->request->data){

			$delId = $this->Absence->delete($_POST['idabsence']);

			// on supprime aussi de la table absence justifiée
			$this->Absence->deleteFromTable('absence_justifiee', $_POST['idabsence']);

			$this->Session->setFlash('Absence supprimée avec succès', 'success');
		}

		$this->redirect('personnels/presence', 30);
	}

	function save($typepersonnel = null)
	{
		foreach ($_POST as $key => $value) {

			if ($key != 'type_contrat_id') {
				$data[$key] = $value;
			}
		}

		$this->loadModel('Personnel');
		$this->loadModel('Personnel_departement');
		$this->loadModel('Personnel_titre');


		if ($this->request->data) {

		
			$nomFichierPhoto = '';


			if (isset($_FILES['employephoto']) && $_FILES['employephoto']['error'] == 0) {


				$extensions_valides = array('jpg' , 'jpeg' , 'png');
				//1. strrchr renvoie l'extension avec le point (« . »).
				//2. substr(chaine,1) ignore le premier caractère de chaine.
				//3. strtolower met l'extension en minuscules.
				$extension_upload = strtolower(  substr(  strrchr($_FILES['employephoto']['name'], '.')  ,1)  );
				if ( !in_array($extension_upload,$extensions_valides) ) {
					$this->setFlash("Extension incorrecte", 'danger');
				}

				$id_membre = str_replace(' ', '', trim($data['nom'])) . '_' . str_replace(' ', '', trim($data['prenom']));

				$nomFichierPhoto = WEBROOT.DS."images/user/{$id_membre}.{$extension_upload}";

				$resultat = move_uploaded_file($_FILES['employephoto']['tmp_name'], $nomFichierPhoto);

				if ($resultat) {
					$data['photo'] = $id_membre.'.'.$extension_upload;
				}
			}


			$r = $this->Personnel->saveInTable('personnel', $data);

		//debug($r);
		
			

			$datad = array(
				'personnel_id' => $r,
				'departement_id' => $_POST['departements_id'],
				'date' => date('Y-m-d'),
				'etat' => 1
			);

			$datat = array(
				'personnel_id' => $r,
				'titre_id' => $_POST['titres_id'],
				'date' => date('Y-m-d'),
				'etat' => 1
			);

			$dataq = array(
				'personnel_id' => $r,
				'type_contrat_id' => $_POST['type_contrat'],
				'date_entree_contrat' => '0000-00-00',
				'date_fin_contrat' => '0000-00-00',
				'etat' => 1
			);

			// On récupère le département actuel de l'employé
			$employeCurrentDepartement = $this->Personnel->getEmployeDepartement($r);

			/*
				Si il n'a pas de département actif actuellement ou si il veut changer de département.
				On désactive son ancien département et on insert une nouvelle ligne pour son département actuel
			*/


			//	debug($employeCurrentDepartement);
			if (!$employeCurrentDepartement) {

				$rd = $this->Personnel_departement->saveInTable('personnel_departement', $datad);

			}
			elseif($_POST['departements_id'] != $employeCurrentDepartement[0]->departement_id){

				$this->Personnel_departement->saveInTable('personnel_departement', array('idpersonnel_departement' =>$employeCurrentDepartement->idpersonnel_departement, 'etat' => 0));
				$rd = $this->Personnel_departement->saveInTable('personnel_departement', $datad);
			}

			// On récupère le titre actuel de l'employé
			$employeCurrentTitre = $this->Personnel->getEmployeTitre($r);

			/*
				Si il n'a pas de titre actif actuellement ou si il veut changer de titre.
				On désactive son ancien titre et on insert une nouvelle ligne pour son titre actuel
			*/
			if (!$employeCurrentTitre) {

				$rt = $this->Personnel_titre->saveInTable('personnel_titre', $datat);
			}elseif ($_POST['titres_id'] != $employeCurrentTitre[0]->titre_id) {

				$this->Personnel_titre->saveInTable('personnel_titre', array('idpersonnel_titre' => $employeCurrentTitre[0]->idpersonnel_titre, 'etat' => 0));
				$rt = $this->Personnel_titre->saveInTable('personnel_titre', $datat);
			}


			// On récupère le contrat actuel de l'employé
			$employeCurrentContrat = $this->Personnel->getPersonnelContrat($r);

			/*
				Si il n'a pas de contrat actif actuellement ou si il veut changer de contrat.
				On désactive son ancien contrat et on insert une nouvelle ligne pour son contrat actuel
			*/
			// if (!$employeCurrentContrat) {

			// 	$rt = $this->Personnel_titre->saveInTable('personnel_contrat', $dataq);
			// }elseif ($_POST['type_contrat_id'] != $employeCurrentContrat[0]->type_contrat_id) {

			// 	$this->Personnel_titre->saveInTable('personnel_contrat', array('idpersonnel_contrat' => $employeCurrentContrat[0]->idpersonnel_contrat, 'etat' => 0));
			// 	$rt = $this->Personnel_titre->saveInTable('personnel_contrat', $dataq);
			// }

		}

		$this->redirect('personnels/viewList/'.$typepersonnel, 30);
	}

	function del($typepersonnel, $id = null)
	{
		$this->loadModel('Personnel');
		$this->Personnel->delete($id);
		$this->redirect('personnels/viewList/'.$typepersonnel, 30);
	}

	public function promotion(){

		$this->loadModel('Type_contrat');
	  	$this->loadModel('Personnel_titre');
	  	$this->loadModel('Contrat');
	  	$this->loadModel('Personnel_contrat');

		//Liste des titres
		$titre = $this->Contrat->findInTable('titre');
		$this->set('titre',$titre);

	}


	

	public function contrat(){

		$this->loadModel('Type_contrat');
		$this->loadModel('Personnel_titre');
		$this->loadModel('Contrat');
		$this->loadModel('Personnel_contrat');

		//Liste des titres
	    $titre = $this->Contrat->findInTable('titre');

	    $this->set('titre',$titre);
	}

	public function mouvement(){

		$this->loadModel('Planning_conge');
		$this->loadModel('Type_sanction');
		$this->loadModel('Departement');
		$this->loadModel('Personnel_titre');
		//Liste des titres
	    $titre = $this->Planning_conge->findInTable('titre');
	    $this->set('titre',$titre);

	    $sanction = $this->Type_sanction->find();
	    $this->set('sanction',$sanction);

	    $departement = $this->Departement->find();
	    $this->set('departement',$departement);
	}


	public function sortie(){
		$this->loadModel('Personnel');

		$allPersonnelType = $this->Personnel->find(array
		    	('conditions' => 'active = 0') );

		foreach ($allPersonnelType as $value) {

			$temp = $this->Personnel->findTypeContrat($value->idpersonnel);
 

		 if(isset($temp[0])) $value->typecontrat = $temp[0]->contrat; else $value->typecontrat = '';
		}

		$this->loadModel('Titre');
		$titres = $this->Titre->find();
		$this->set('titres', $titres);

		$this->loadModel('Departement');
		$departements = $this->Departement->find();
		$this->set('departements', $departements);

		$this->loadModel('Type_contrat');
		$typecontrats = $this->Type_contrat->find();
		$this->set('typecontrats', $typecontrats);

		$this->loadModel('Motif_sortie');
		$motifs_sortie = $this->Motif_sortie->find();
		$this->set('motifs_sortie', $motifs_sortie);

		$this->set('contractuels', $allPersonnelType);
		 

	}


		private function nom_jour($date) {
		 
		$jour_semaine = array(1=>"lundi", 2=>"mardi", 3=>"mercredi", 4=>"jeudi", 5=>"vendredi", 6=>"samedi", 7=>"dimanche");
		 
		list($annee, $mois, $jour) = explode ("-", $date);
		 
		$timestamp = mktime(0,0,0, date($mois), date($jour), date($annee));
		$njour = date("N",$timestamp);
		 
		return $jour_semaine[$njour];
		 
		}

	public function indicateurs(){

		$this->loadModel('Absence');
		$this->loadModel('Personnel');
		$this->loadModel('Annee');
		$this->loadModel('Personnel_sanction');

		$annee = $this->Annee->getanneeencours();
		$total_employe = $this->Personnel->gettotalemploye();
		$nombre_sortie = $this->Personnel->getNombreSortie($annee->date_debut,date('Y-m-d'));


		// Taux absenteisme
		$totalpersonnel = $this->Personnel->gettotalemploye();
		$listeabsence = $this->Absence->find(array("conditions" => "date_debut between '".$annee->date_debut."' AND '".date("Y-m-d")."' " ));

		$total = 0;
		foreach ($listeabsence as $key => $value) {
			
			$lejour = $this->nom_jour($value->date_debut);

			 if($lejour != 'samedi' || $lejour != 'dimanche'){

			 	$total += $value->nbre_heures;
			 }
		}

		$nombretotaljour = $total/8;

		$taux = ( ($nombretotaljour/251) * $totalpersonnel->nombre  ) *100;

		$this->set('tauxabsenteisme',$taux);

		 //Turn over
		$nombre_entree = $this->Personnel->getNombreentree($annee->date_debut,date('Y-m-d'));
		

		$turn_over = round( (($nombre_sortie->nombre + $nombre_entree->nombre) / $total_employe->nombre) *100,2);

		$this->set('turn_over',$turn_over);


		//Taux de délinquance
		$nombre_sanction = $this->Personnel_sanction->getNombreagentsanctionne($annee->date_debut,date('Y-m-d'));

		$taux_delinquance = round(($nombre_sanction/$total_employe->nombre)*100,2);
		
		$this->set('taux_delinquance',$taux_delinquance);

		//Taux de rétention
		
		$nombre_employe = $this->Personnel->getNombreEmployeEnDebutAnee($annee->date_debut);

		$embauche = $this->Personnel->getEmbauche($annee->date_debut,date('Y-m-d'));

		$taux_retention  =  round(((($nombre_employe->nombre+$embauche->nombre) - $nombre_sortie->nombre) / ($nombre_employe->nombre+$embauche->nombre)) *100,2);

		$this->set('taux_retention',$taux_retention);

		//graph effectif par poste
		
		$listeposte = $this->Personnel->getPersonnelTitre();
		$this->set('listeposte',$listeposte);

		
	}

	public function statut(){
		$an =array();
			$this->loadModel("Personnel");

			$effectif = $this->Personnel->findcount("1 = 1 AND active = 1");
			$this->set('effectif',$effectif);

			//effectif homme
			$effectifhomme = $this->Personnel->findcount("sexe = 'M' AND active = 1");
			$this->set('effectifhomme',$effectifhomme);

			//effectif femme
			$effectiffemme = $this->Personnel->findcount("sexe = 'F' AND active = 1 ");
			$this->set('effectiffemme',$effectiffemme);

			//effectif cdd
			$cdd = $this->Personnel->findcount("type_contrat = '2' AND active = 1");
			$this->set('cdd',$cdd);



			//effectif cdi
			$cdi = $this->Personnel->findcount("type_contrat = '3' AND active = 1");
			$this->set('cdi',$cdi);

				//effectif stage
			$stage = $this->Personnel->findcount("type_contrat = '5' AND active = 1");
			$this->set('stage',$stage);

				//effectif personnel Administratif direct
			$padmindirect = $this->Personnel->findcount("type_personnel_id = '2' AND active = 1");
			$this->set('padmindirect',$padmindirect);

			//effectif personnel du back office
			$backoffice = $this->Personnel->findcount("type_personnel_id = '9' AND active = 1");
			$this->set('backoffice',$backoffice);
			

			//effectif personnel administratif indirect
$totalindirectpersonnel = $this->Personnel->findcount("type_personnel_id = '1' AND active = 1");
			$this->set('totalindirectpersonnel',$totalindirectpersonnel);

			//effectif personnel d'émission
$personnelemission = $this->Personnel->findcount("type_personnel_id = '3' AND active = 1");
			$this->set('personnelemission',$personnelemission);

//effectif personnel reception
$personnelreception = $this->Personnel->findcount("type_personnel_id = '4' AND active = 1");
			$this->set('personnelreception',$personnelreception);

//effectif personnel d'entretien
$personnelentretien = $this->Personnel->findcount("type_personnel_id = '5' AND active = 1");
			$this->set('personnelentretien',$personnelentretien);


//effectif personnel interimaire
$personnelinterimaire = $this->Personnel->findcount("type_personnel_id = '7' AND active = 1");
			$this->set('personnelinterimaire',$personnelinterimaire);

//effectif personnel interimaire
$personnelebu = $this->Personnel->findcount("type_personnel_id = '8' AND active = 1");
			$this->set('personnelebu',$personnelebu);


			}

			public function profil(){
		$an =array();
			$this->loadModel("Personnel");

			$effectif = $this->Personnel->findcount("1 = 1 AND active = 1");
			$this->set('effectif',$effectif);

			//effectif homme
			$effectifhomme = $this->Personnel->findcount("sexe = 'M' AND active = 1");
			$this->set('effectifhomme',$effectifhomme);

			//effectif femme
			$effectiffemme = $this->Personnel->findcount("sexe = 'F' AND active = 1 ");
			$this->set('effectiffemme',$effectiffemme);

			//effectif cdd
			$cdd = $this->Personnel->findcount("type_contrat = '2' AND active = 1");
			$this->set('cdd',$cdd);



			//effectif cdi
			$cdi = $this->Personnel->findcount("type_contrat = '3' AND active = 1");
			$this->set('cdi',$cdi);

				//effectif stage
			$stage = $this->Personnel->findcount("type_contrat = '5' AND active = 1");
			$this->set('stage',$stage);

				//effectif personnel Administratif direct
			$teamleader = $this->Personnel->findcount("titres_id = '1' AND active = 1");
			$this->set('teamleader',$teamleader);

				//effectif personnel Administratif direct
			$qualityadvisor = $this->Personnel->findcount("titres_id = '2' AND active = 1");
			$this->set('qualityadvisor',$qualityadvisor);

				//effectif personnel Administratif direct
			$crcd = $this->Personnel->findcount("titres_id = '3' AND active = 1");
			$this->set('crcd',$crcd);

				//effectif personnel Administratif direct
			$respodep = $this->Personnel->findcount("titres_id = '4' AND active = 1");
			$this->set('respodep',$respodep);

			//effectif personnel Administratif direct
			$assistandep = $this->Personnel->findcount("titres_id = '5' AND active = 1");
			$this->set('assistandep',$assistandep);

			//effectif personnel Administratif direct
			$directeurdep = $this->Personnel->findcount("titres_id = '6' AND active = 1");
			$this->set('directeurdep',$directeurdep);

			//effectif personnel Administratif direct
			$qualityadvisorjr = $this->Personnel->findcount("titres_id = '7' AND active = 1");
			$this->set('qualityadvisorjr',$qualityadvisorjr);

			//effectif personnel Administratif direct
			$backofficeagent = $this->Personnel->findcount("titres_id = '8' AND active = 1");
			$this->set('backofficeagent',$backofficeagent);

			//effectif personnel Administratif direct
			$superviseurs = $this->Personnel->findcount("titres_id = '9' AND active = 1");
			$this->set('superviseurs',$superviseurs);

			//effectif personnel Administratif direct
			$commercial = $this->Personnel->findcount("titres_id = '10' AND active = 1");
			$this->set('commercial',$commercial);

			//effectif personnel Administratif direct
			$supterrain = $this->Personnel->findcount("titres_id = '11' AND active = 1");
			$this->set('supterrain',$supterrain);

			//effectif personnel Administratif direct
			$assistansi = $this->Personnel->findcount("titres_id = '12' AND active = 1");
			$this->set('assistansi',$assistansi);

			//effectif personnel Administratif direct
			$assistandev = $this->Personnel->findcount("titres_id = '13' AND active = 1");
			$this->set('assistandev',$assistandev);


			//effectif personnel Administratif direct
			$conseillerinterimagence = $this->Personnel->findcount("titres_id = '14' AND active = 1");
			$this->set('conseillerinterimagence',$conseillerinterimagence);

			//effectif personnel Administratif direct
			$conseillerinterimbo = $this->Personnel->findcount("titres_id = '15' AND active = 1");
			$this->set('conseillerinterimbo',$conseillerinterimbo);

			//effectif personnel Administratif direct
			$assistantrh = $this->Personnel->findcount("titres_id = '16' AND active = 1");
			$this->set('assistantrh',$assistantrh);





			//effectif personnel du back office
			$backoffice = $this->Personnel->findcount("type_personnel_id = '9' AND active = 1");
			$this->set('backoffice',$backoffice);
			

			//effectif personnel administratif indirect
$totalindirectpersonnel = $this->Personnel->findcount("type_personnel_id = '1' AND active = 1");
			$this->set('totalindirectpersonnel',$totalindirectpersonnel);

			//effectif personnel d'émission
$personnelemission = $this->Personnel->findcount("type_personnel_id = '3' AND active = 1");
			$this->set('personnelemission',$personnelemission);

//effectif personnel reception
$personnelreception = $this->Personnel->findcount("type_personnel_id = '4' AND active = 1");
			$this->set('personnelreception',$personnelreception);

//effectif personnel d'entretien
$personnelentretien = $this->Personnel->findcount("type_personnel_id = '5' AND active = 1");
			$this->set('personnelentretien',$personnelentretien);


//effectif personnel interimaire
$personnelinterimaire = $this->Personnel->findcount("type_personnel_id = '7' AND active = 1");
			$this->set('personnelinterimaire',$personnelinterimaire);

//effectif personnel interimaire
$personnelebu = $this->Personnel->findcount("type_personnel_id = '8' AND active = 1");
			$this->set('personnelebu',$personnelebu);




			}

}
