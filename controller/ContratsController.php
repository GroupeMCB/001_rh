<?php

/**
*
*/
class ContratsController extends Controller
{

	public function index(){

	 	$this->loadModel('Type_contrat');
		$this->loadModel('Personnel_titre');
		$this->loadModel('Contrat');
		$this->loadModel('Personnel_contrat');

		//Liste des titres
	   	$titre = $this->Contrat->findInTable('titre');
	    $this->set('titre',$titre);

	}

	/**
	* Permet de voir l'historique et de modifier le contrat d'un employé
	*
	* @param $idpersonnel identifiant du personnel
	*
	* @return void
	**/
 	public function editcontrat($idpersonnel)
 	{
 		$this->loadModel('Personnel');
 		$this->loadModel('Type_contrat');
 		$this->loadModel('Personnel_contrat');

 		if($this->request->data && isset($this->request->data->idpersonnel_contrat)){

 			$con = $this->Personnel_contrat->save($this->request->data);
 		}

 		$personnel_contrats = $this->Personnel_contrat->find(array(
 			'conditions' => 'personnel_id='.$idpersonnel));

 		foreach ($personnel_contrats as $key => $personnel_contrat) {

 			$contrat = $this->Type_contrat->find(array('conditions' => 'idtype_contrat='.$personnel_contrat->type_contrat_id));

 			$personnel_contrat->libContrat = $contrat[0]->nom;
 		}

 		$this->set('personnel_contrats', $personnel_contrats);

 		$contrats = $this->Type_contrat->find();
 		$this->set('contrats', $contrats);

 		$employe = $this->Personnel->find(array('conditions' => 'idpersonnel='.$idpersonnel));
 		$this->set('employe', $employe[0]);

 		$this->render('editcontrat');
 	}

 	/**
 	* Permet d'ajouter ou de mettre à jour le contrat pour un employé
 	*
 	* @param
 	**/
 	public function addContrat()
 	{
 		$this->loadModel('Personnel');
 		$this->loadModel('Personnel_contrat');

		// debug($this->request->data);
		// die();

 		if($this->request->data){
 		
 		$employe_actuel_contrat = $this->Personnel_contrat->Contrat_en_cours($this->request->data->personnel_id);



			$this->Personnel_contrat->saveInTable('personnel_contrat',array(
				'idpersonnel_contrat' => $employe_actuel_contrat->idpersonnel_contrat,
				'date_fin_contrat' => $this->request->data->date_fin_contrat,
				'etat' => 0
				));

			$this->Personnel_contrat->save($this->request->data);

			// $this->Personnel->saveInTable('personnel', array('idpersonnel' => , 'type_contrat' => ));
		}

	//	$this->setFlash('Nouveau contrat ajouté', 'success');
		$this->redirect('contrats/editcontrat/'.$this->request->data->personnel_id, 30);
 	}


}
 ?>
