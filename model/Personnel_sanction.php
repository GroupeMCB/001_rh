<?php 


/**
* 
*/
class Personnel_sanction extends Model
{
	
	 /**
	 * Permet d'avoir tous les  agents sanction
	 * id ID de l'agent
	 * @return boolean true
	 */
	public function findSanction($id = null){

				
				$sql = 'SELECT personnel.idpersonnel,personnel.nom,personnel.prenom,sanct.*,typ.libelle_sanction
				        FROM personnel_sanction as sanct 
		 		        LEFT JOIN personnel on personnel.idpersonnel = sanct.personnel_id
		 		        LEFT JOIN type_sanction as typ on typ.idtype_sanction = sanct.type_sanction_id';
				
				if(isset($id))
				 {
				 	$sql .= ' WHERE personnel.idpersonnel ='.$id;

				 }
 			
 			try{
		 		$pre = $this->db->prepare($sql);
		 	    $pre->execute(); 
		 	   
		   		 
		   		 return $pre->fetchAll(PDO::FETCH_OBJ);
		 	    }
		 	    catch (PDOException $e)
		 	    {

		 	    	//Controller::error($e);
		 	    }
	}


	public function getNombreagentsanctionne($date_debut,$date_fin){

		$sql = "SELECT DISTINCT personnel_id 
				FROM personnel_sanction
				WHERE date_debut between '".$date_debut."' AND '".$date_fin."' 
				";
				$pre = $this->db->prepare($sql);
		 	    $pre->execute(); 
 				return	$pre->rowCount();
	}
}

 ?>