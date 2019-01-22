<?php 

/**
* 
*/
class CongesController extends Controller
{
	

		 public  function nbJours($debut, $fin) {
        //60 secondes X 60 minutes X 24 heures dans une journée
        $nbSecondes= 60*60*24;
 
        $debut_ts = strtotime($debut);
        $fin_ts = strtotime($fin);
        $diff = $fin_ts - $debut_ts;
       return  round($diff/$nbSecondes);
    }
	/**
	 * [view description]
	 * @return [type] [description]
	 */
	public function view(){

	}

    /**
     * [create description]
     * @return [type] [description]
     */
	public function create(){

	}

	public function addconge($id){
		if(isset($id) && is_numeric($id) && $id !=0){
			$this->loadModel('Personnel');

			$agent = $this->Personnel->find(array("conditions" =>"idpersonnel = ".$id));
			$this->set('unagent',$agent);

			 if($this->request->data){
				$this->loadModel('Planning_conge');
	 		    $this->Planning_conge->save($this->request->data);
			    $this->Session->setFlash('Congé ajouté avec succès','success');
			    //$this->Session->flash();
			 }

		}
		else{

			$this->e404('Page introuvable');
		}

	}


	public function planning(){
			
			$this->loadModel('Planning_conge');
			

			//Vérification si des données ont été envoyées par POST
			    if($this->request->data){




			    	//Suppression d'une plannification
			    	if(isset($this->request->data->supprimer) && $this->request->data->supprimer == "supprimer")
			    	{

				    	$this->Planning_conge->delete($this->request->data->idplanning_conge);
				    	$this->Session->setFlash('Planification supprimé avec succès','success');
					    $this->Session->flash();
			    	}
			    	elseif(isset($this->request->data->q) && !empty($this->request->data->q) )
			    	{

			    			//debug(isset($this->request->data->q) );

			    	}
			 	//Verification si c'est le formulaire de validation de congé qui a été envoyé 
				 	elseif(isset($this->request->data->etat) )
				 	{
				 		$tab_data =array();
				 		
				 		//ID de l'agent dont on veut activer le congé
				 		$id_person = $this->request->data->idpersonnel;
				 		
				 		//récupération des élément de std class envoyé via post dans un tableau
				 		$tab_data = get_object_vars($this->request->data);
				 		
				 		//Suppression de la premiere ligne du tableau qui représente l'id de l'agent
				 		array_shift($tab_data);
				 		
				 		//Mise à jour de la table planning congé
				 		$this->Planning_conge->saveInTable('planning_conge',$tab_data);

				 		//récupération des infos liés a l'agent sélectionné dans la table planning congé
				 	    $agent = $this->Planning_conge->findInTable('personnel', array(
				 	    	"fields" => "solde_conge",
				 	    	"conditions"=>"idpersonnel = ".$id_person));

				 	    //récupération des infos liés au solde de l'agent 
				 	    $agent_planning = $this->Planning_conge->findfirst(array(
				 	    	"fields" => "nombre_jour",
				 	    	"conditions"=>"idplanning_conge = ".$this->request->data->idplanning_conge));

				 	     foreach ($agent as $key => $value) {
				 	     	$unagent = $value;
				 	     }
				 	    $unagent = get_object_vars($unagent);

				 	     foreach ($agent_planning as $key => $value) {
				 	     	$unsolde = $value;
				 	     }
				 	    
				 	    //Calcul du solde de congé restant de l'agent
				 	    $data_req["idpersonnel"] = $id_person;
				 	    $data_req['solde_conge']  =$unagent['solde_conge'] - $unsolde;
				 	    
				 	    //Modification de la table personnel
				 	    $this->Planning_conge->saveInTable('personnel',$data_req);
				 	
					    $this->Session->setFlash('Congé validé avec succès','success');
					    $this->Session->flash();
				 	}

				 	//Modification de la plannification de congé d'un agent
				 	elseif(isset($this->request->data->idplanning_conge) )
				 	{
				 		$this->Planning_conge->save($this->request->data);
					    $this->Session->setFlash('Congé modifié avec succès','success');
					    $this->Session->flash();

				 	}

				 	// Insertion d'une nouvelle plannification
				 	else
				 	{
					    $this->Planning_conge->save($this->request->data);
					    $this->Session->setFlash('Congé ajouté avec succès','success');
					    $this->Session->flash();

					}
	         }
	         //fin insertion

	         //Liste des plannings de congé
		     $planning = $this->Planning_conge->findAgentConge();
		    //envoi des données de la requêtes à la vue
	    	 $this->set('planning',$planning);

	    	 //Liste des titres
	    	 $titre = $this->Planning_conge->findInTable('titre');
	    	 $this->set('titre',$titre);
	}
	
	 
	 /**
	 * Tableau de bord
	 **/
	 public function dashboard(){

	 	 $this->loadModel('Planning_conge');
	 	
	 	 $congeprevu = $this->Planning_conge->findcount("MONTH(date_debut) = ".date('m') );
	 	 $this->set('congeprevu',$congeprevu);


	 	 $congepris = $this->Planning_conge->findcount("MONTH(date_debut) = ".date('m')." AND etat = 1"  );
	 	 $this->set('congepris',$congepris);

	 	 $congeprisan = $this->Planning_conge->findcount("etat = 1"  );
	 	 $this->set('congeprisan',$congeprisan);

	 	 $congeprisrestant = $this->Planning_conge->findcount("etat = 0"  );
	 	 $this->set('congeprisrestant',$congeprisrestant);

	 	
	 	//Liste des personnes devant aller en congé le mois en cours
	 	 $personnesprevues = $this->Planning_conge->findAgentConge(NULL,date('m'));
	 	 $this->set('personnesprevues',$personnesprevues);

	 	 //Liste des personnes devant revenir de congé dont la durée est inférieure à 15 jours
	 	 $personnesretour = $this->Planning_conge->Retourconge();

	 	 
	 	 foreach ($personnesretour as $key => $value) {

	 	 	         $tab[$key] = get_object_vars($value);

	 	 }
	
	 	 if(!empty($tab) ) {
		foreach ($tab as $k => $v) {
			 	 
			    	$nombre = $this->nbJours(date("Y-m-d"),$v['date_fin']) ;
			    	if($nombre >=0 && $nombre <= 7 ){

			    		$listepersonne[$k]['nom'] = $v['nom'].' '.$v['prenom'];
			    		$listepersonne[$k]['jour'] = $nombre;

			    	}
			 }	 
	 	 $this->set('personnesretour',$listepersonne);

	 	}
	 	else {
	 			
	 	 $this->set('personnesretour',0);

	 	}

	 	//GRAPH sur le dashboard
	 	
	 	$nombre24 = $this->Planning_conge->findSoldeCongeAll(0,24);
	 	$nombre50 = $this->Planning_conge->findSoldeCongeAll(24,50);
	 	$nombre80 = $this->Planning_conge->findSoldeCongeAll(50,80);
	 	$nombreplus = $this->Planning_conge->findSoldeCongeAll(80,1000);

	  
	  	$this->set('nombre24',$nombre24);
	  	$this->set('nombre50',$nombre50);
	  	$this->set('nombre80',$nombre80);
	  	$this->set('nombreplus',$nombreplus);
	 	
	 }



	 public function soldeconge()
	 {
			 $this->loadModel('Planning_conge');
			
	    	 // si le titre a été choisi dans la liste déroulante
	    	 if($this->request->data){

	    	 	if(isset($this->request->data->idpersonnel)){

	    	 		$data = get_object_vars($this->request->data);
	    	 		$this->Planning_conge->saveInTable('personnel',$data);

	    	 		 
	    	 	}
	    	 	else{
	    	
			    	 $titre = $this->Planning_conge->findInTable('titre');
			    	 $this->set('titre',$titre);
	    	 	//liste de tous les agents contractuels (CDD, CDI) 
	    	    $liste =$this->Planning_conge->findSoldeConge($this->request->data->titre);
	    	    $liste_compteur =array();
	    	    foreach ($liste as $k => $v) {

	    	    	$liste_compteur[$k]['idpersonnel'] = $v->idpersonnel;
	    	    	$liste_compteur[$k]['nom'] = $v->nom;
	    	    	$liste_compteur[$k]['prenom'] = $v->prenom;
	    	    	$liste_compteur[$k]['solde_conge'] = $v->solde_conge; 
	    	    	$liste_compteur[$k]['compteur_conge'] = $this->Planning_conge->CompteurConge($v->idpersonnel);

	    	    	 $planningconge[$k] = $this->Planning_conge->findAgentConge($v->idpersonnel);
				     //envoi des données de la requêtes à la vue
			    	    	  
	    	    }
	    	    

	    	   //Liste des plannings de congé
	    	    if(isset($planningconge))
	    	    	 $this->set('planningconge',$planningconge);
		    
	    	    
	    	    $this->set('letitre',$this->request->data->titre);
	    	    $this->set('listeagent',$liste_compteur);

	    	}
	    	     
    	 }
    	 else{

    	 $titre = $this->Planning_conge->findInTable('titre');
    	 $this->set('titre',$titre);
    	 }

	 }






}
 ?>