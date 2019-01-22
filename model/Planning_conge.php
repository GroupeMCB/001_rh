<?php 

/**
* 
*/
class Planning_conge extends Model
{
	
		/**
	 * Permet d'avoir toutes les personnes dont les congés ont été validé
	 *  
	 * @return boolean true
	 */
	public function Retourconge(){
		$sql = 'SELECT personnel.idpersonnel,personnel.nom,personnel.prenom,pla.*
				        FROM planning_conge as pla 
		 		        LEFT JOIN personnel on personnel.idpersonnel = pla.personnel_id 
		 		        WHERE pla.etat = 1';	

		 		$pre = $this->db->prepare($sql);
		 	    $pre->execute(); 
		 	    return $pre->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Permet d'avoir tous les planning de congé 
	 * id ID de l'agent
	 * @return boolean true
	 */
	public function findAgentConge($id = null,$mois =null){

				

				$sql = 'SELECT personnel.idpersonnel,personnel.nom,personnel.prenom,pla.*
				        FROM planning_conge as pla 
		 		        LEFT JOIN personnel on personnel.idpersonnel = pla.personnel_id 
		 		        WHERE';
				
				if(isset($id))
				 {
				 	$sql .= '  personnel.idpersonnel ='.$id;

				 }

				 elseif(isset($mois))
				 {
				 	$sql .= ' etat = 0 AND MONTH(pla.date_debut) ='.$mois;

				 }
				 else{

				 	$sql .= ' 1=1 ';
				 }

		 		$pre = $this->db->prepare($sql);
		 	    $pre->execute(); 
		 	    if($pre){
		   		 
		   		 return $pre->fetchAll(PDO::FETCH_OBJ);
		 	    }
		 	    else{
		 	    	$this->request->controller->error('Une erreur s\'est produite');
		 	    }
	}


	/**
	 * Permet de calculer le solde de congés de tous les agents par poste occupé
	 * @return [type] [description]
	 */
	 public function findSoldeConge($titre){

	 		// $sql = 'SELECT perso.nom,perso.prenom,perso.solde_conge,perso.idpersonnel FROM personnel as perso
	 		//         LEFT JOIN personnel_contrat as contrat on  contrat.personnel_id = perso.idpersonnel
	 		//         LEFT JOIN titre on titre.idtitre = perso.titres_id
	 		//         WHERE type_contrat_id in (2,3) 
	 		//         AND contrat.etat = 1
	 		//         AND titre.idtitre ='.$titre;
	 		$sql = 'SELECT perso.nom,perso.prenom,perso.solde_conge,perso.idpersonnel
	 		        FROM personnel as perso   
	 		        LEFT JOIN titre on titre.idtitre = perso.titres_id
	 		        WHERE type_contrat in (2,3)  
	 		        AND titre.idtitre ='.$titre;

	        $pre = $this->db->prepare($sql);
	        $pre->execute();

	        return $pre->fetchAll(PDO::FETCH_OBJ);
		 	     
	 }



	 /**
	  * Permet d'avoir le cumul de tous les jours de congés obtenus par un agent
	  * @param [type] $id ID de l'agent
	  */
	 public function CompteurConge($id){

	     $sql = 'SELECT SUM(nombre_jour) as nbre_jour FROM planning_conge
	     		WHERE personnel_id = "'.$id.'"  AND etat = 1 ';

	      $pre = $this->db->prepare($sql);
	      $pre->execute();

	      return $pre->fetch(PDO::FETCH_OBJ);
	 }



 	 /**
 	  * Permet de calculer le nombre de personnes dont les soldes de conge sont compris entre deux valeurs  
 	  * @param  [type] $debut [description]
 	  * @param  [type] $fin   [description]
 	  * @return [type]        [description]
 	  */
	 public function findSoldeCongeAll($debut,$fin){

	 		$sql = 'SELECT count(idpersonnel) as nombre FROM personnel as perso
	 		        LEFT JOIN personnel_contrat as contrat on  contrat.personnel_id = perso.idpersonnel
	 		        LEFT JOIN titre on titre.idtitre = perso.titres_id
	 		        WHERE type_contrat_id in (2,3) 
	 		        AND contrat.etat = 1
	 		        AND   solde_conge > '.$debut.' AND solde_conge <= '.$fin;

	        $pre = $this->db->prepare($sql);
	        $pre->execute();

	        return $pre->fetch(PDO::FETCH_OBJ);
		 	     
	 }

}
 ?>