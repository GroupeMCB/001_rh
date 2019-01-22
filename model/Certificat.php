<?php 

/**
* Class model certificat
*/
class Certificat extends Model
{
	
	/**
	 * Permet d'avoir tous les planning de congé 
	 * id ID de l'agent
	 * @return boolean true
	 */
	public function findCertificat($id = null){

				
				$sql = 'SELECT personnel.idpersonnel,personnel.nom,personnel.prenom,cert.*,typ.libelle_certificat
				        FROM certificat as cert 
		 		        LEFT JOIN personnel on personnel.idpersonnel = cert.personnel_id
		 		        LEFT JOIN type_certificat as typ on typ.idtype_certificat = cert.type_certificat_id';
				
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

	 
}

 ?>