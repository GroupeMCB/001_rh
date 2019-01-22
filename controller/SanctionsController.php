<?php 

/**
* 
*/
class SanctionsController extends Controller
{
	
	public function index(){

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

	    	// $this->Personnel_titre->NombrePersonneParTitre();

	}



	public function addsanction($id){

				 $this->loadModel('Personnel_sanction');
		 	     $this->loadModel('Type_sanction');
		 	     $this->loadModel('Planning_conge');

				if(isset($id) && is_numeric($id) && $id !=0 ) { 
					 //Liste des types de sanction
				     $listesanction = $this->Type_sanction->find();

				    //envoi des données de la liste à la vue
			    	 $this->set('listesanction',$listesanction);

					$this->loadModel('Personnel');
					$agent = $this->Personnel->find(array("conditions" =>"idpersonnel = ".$id));
					$this->set('unagent',$agent);

					if($this->request->data){
						 
				        $this->Personnel_sanction->save($this->request->data);
		    	 		$this->Session->setFlash('Sanction ajouté avec succès','success');
		    	 	}

	    	 	}
	    	 	else{

	    	 		$this->e404('Page introuvable');
	    	 	}

	}

	 public function listview(){


	 	     $this->loadModel('Personnel_sanction');
	 	     $this->loadModel('Type_sanction');
	 	     $this->loadModel('Planning_conge');
	 	     
	 	      //Liste des titres
	    	 $titre = $this->Planning_conge->findInTable('titre');
	    	 $this->set('titre',$titre);
	 		 
	 		 //Liste des types de sanction
		     $listesanction = $this->Type_sanction->find();

		    //envoi des données de la liste à la vue
	    	 $this->set('listesanction',$listesanction);

	    	 if(isset($this->request->data) && !empty($this->request->data)  ){

	    	 		//Suppression d'une sanction
			    	if(isset($this->request->data->supprimer) && $this->request->data->supprimer == "supprimer")
			    	{
			    		//Appel de la méthode delete du Model principal
				    	$this->Personnel_sanction->delete($this->request->data->idpersonnel_sanction);
				    	$this->Session->setFlash('La sanction a été supprimée avec succès','success');
					    
			    	}

			    	// Modification d'une sanction
			    	elseif(isset($this->request->data->idpersonnel_sanction) )
				 	{
				 		//Appel de la méthode save du Model principal
				 		$this->Personnel_sanction->save($this->request->data);
					    $this->Session->setFlash('Sanction modifiée avec succès','success');
					  

				 	}

				 	//Ajout d'une sanction
			    	else{

	    	 		$this->Personnel_sanction->save($this->request->data);

	    	 		$this->Session->setFlash('Sanction ajouté avec succès','success');
	    	 		 }
	    	 }


	    	 

	    	 //Liste de toutes les sanctions au niveau du modèle Certificat.php
	    	 $listeAllSanction = $this->Personnel_sanction->findSanction();

	    	 $this->set('listeAllSanction',$listeAllSanction);

	 }
}
 ?>