<?php 

/**
* 
*/
class ParametresController extends Controller
{
	
	 public function annee(){
	 		$this->loadModel('Annee');
	 	    
	 	    $liste_annee = $this->Annee->find();
	 	    $this->set('liste_annee',$liste_annee);
	 	
	 	if($this->request->data){

	 		if(isset($this->request->data->idannee)) {
	 		    $this->request->data->date_ouverture = date("Y-m-d");
	 		    
	 		    $annee_en_cours =$this->Annee->findcount("etat = 1");

	 		    if($annee_en_cours == 0) {
	 				$this->Annee->save($this->request->data);
	 				$this->render('annee');
	 			}
	 			else{
	 					if($this->request->data->etat == 2){
	 		    $this->request->data->date_cloture = date("Y-m-d");

	 						$this->Annee->save($this->request->data);
	 					}
	 					else{

	 				$this->Session->setFlash('Un exercice est toujours en cours. Veuillez le clôturé avant d\'effectuer cette opération ','danger');
	 			          }
	 			}
	 		}
	 		else{

	 		$this->request->data->date_ouverture ="0000-00-00";
	 		$this->request->data->etat = 0;
	 		$this->request->data->date_cloture = "0000-00-00";
	 		$this->Annee->save($this->request->data); 
	 	     }
	 	}


	 }
}
 ?>