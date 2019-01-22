<?php 

/**
* 
*/
class CertificatsController extends Controller
{
	
	/**
	 * Permet d'afficher le tableau de Bords des certificats 
	 * @return [type] [description]
	 */
	 public function index(){

	 	$this->loadModel('Certificat');
	 	$this->loadModel('Personnel_sanction');

	 	$nombrecertificatmedical = $this->Certificat->findcount("type_certificat_id =2");
	 	$this->set('nombrecertificatmedical',$nombrecertificatmedical);

	 	$nombrecertificatgrossesse = $this->Certificat->findcount("type_certificat_id =1");
	 	$this->set('nombrecertificatgrossesse',$nombrecertificatgrossesse);

	 	$nombremise = $this->Personnel_sanction->findcount("type_sanction_id = 2");
	 	$this->set('nombremise',$nombremise);

	 	$nombreavertissement = $this->Personnel_sanction->findcount("type_sanction_id = 1");
	 	$this->set('nombreavertissement',$nombreavertissement);

	 	$nombrelicenciement = $this->Personnel_sanction->findcount("type_sanction_id = 3");
	 	$this->set('nombrelicenciement',$nombrelicenciement);
	 }


	 /**
	  * Permet d'ajouter un certificat pour un utilisateur donné
	  * @param  [type] $idpersonnel      [description]
	  * @param  [type] $idtypecertificat [description]
	  * @return [type]                   [description]
	  */
	 public function addcertificat($idpersonnel = null){

	 	if (isset($this->request->data) && !empty($this->request->data) ) {
	 		
	 		
	 		$this->loadModel('Certificat');
	 		//Appel de la méthode save du Model principal

	 		$insertCertificatId = $this->Certificat->save($this->request->data);

	 		$dataToSave = array(
	 			'absence_id' => $this->Session->read('idabsence'), 
	 			'certificat_id' => $insertCertificatId
	 		);

	 		$this->Certificat->saveInTable('absence_justifiee', $dataToSave);
		    $this->Session->setFlash('Certificat ajouté avec succès','success');		    
		   	$this->redirect('certificats/listview', 30);
		   	exit();
	 	}

	 	$this->loadModel('Personnel');
	 	$datapersonnel = $this->Personnel->find(array
		    	('conditions'=>'idpersonnel ='.$idpersonnel));

	 	$this->loadModel('Type_certificat');
	 	//Liste des types de certicats
		$listecertificat = $this->Type_certificat->find();
		//envoi des données de la liste à la vue
	   	$this->set('listecertificat', $listecertificat);

	 	$this->loadModel('Type_Personnel');
	 	//Liste des types de certicats
		$personnelTitre = $this->Type_Personnel->findPersonnelTitre($idpersonnel);
		//envoi des données de la liste à la vue
	   	$this->set('personnelTitre', $personnelTitre[0]);

	 	$this->set('datapersonnel', $datapersonnel[0]);
	 	$this->render('add');

	 }


	 /**
	  * Permet de voir la liste de tous les certificats
	  * @return [type] [description]
	  */
	 public function listview(){

	 	     $this->loadModel('Certificat');
	 	     $this->loadModel('Type_certificat');
	 		 //Liste des types de certicats
		     $listecertificat = $this->Type_certificat->find();
		    //envoi des données de la liste à la vue
	    	 $this->set('listecertificat',$listecertificat);

	    	 //Liste des titres
	    	 $titre = $this->Certificat->findInTable('titre');
	    	 $this->set('titre',$titre);

	    	 if(isset($this->request->data) && !empty($this->request->data)  ){

	    	 		//Suppression d'un certificat
			    	if(isset($this->request->data->supprimer) && $this->request->data->supprimer == "supprimer")
			    	{
			    		//Appel de la méthode delete du Model principal
				    	$this->Certificat->delete($this->request->data->idcertificat);
				    	$this->Session->setFlash('Le certificat a été supprimé avec succès','success');
					    
			    	}

			    	// Modification d'un certificat
			    	elseif(isset($this->request->data->idcertificat) )
				 	{
				 		//Appel de la méthode save du Model principal
				 		$this->Certificat->save($this->request->data);
					    $this->Session->setFlash('Certificat modifié avec succès','success');
				 	}

				 	//Ajout d'un certificat
			    	else{

		    	 		$this->Certificat->save($this->request->data);

		    	 		$this->Session->setFlash('Certificat ajouté avec succès','success');
	    	 		}
	    	 }

	    	 //Liste de tous les certiciats au niveau du modèle Certificat.php
	    	 $listeAllcertificat = $this->Certificat->findCertificat();
	    	 $this->set('listeAllcertificat',$listeAllcertificat);

	 }


	public function add($id = null){
	//debug($this->request->controller->error('dgdg'));
	 	if(isset($id) && is_numeric($id) ) {
	 		
	 		 $this->loadModel('Certificat');
	 	     $this->loadModel('Type_certificat');
	 	     $this->loadModel('Personnel');
	 		 //Liste des types de certicats
		     $listecertificat = $this->Type_certificat->find();
		    //envoi des données de la liste à la vue
	    	 $this->set('listecertificat',$listecertificat);

	    	 $thepersonnel = $this->Personnel->find(array("conditions"=>"idpersonnel = ".$id));
	    	 foreach ($thepersonnel as $key => $value) {
	    	 	  $perso = $value;
	    	 }
	    	 $this->set('perso',$perso);

	    	}
	    	else {

	    		$this->e404('Impossible d\'afficher la page demandée');
	    	}

	 }
}

 ?>