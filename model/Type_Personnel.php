<?php 
	
	/**
	* 
	*/
	class Type_Personnel extends Model
	{
		function findPersonnelTitre($idpersonnel)
		{

			$sql = "SELECT personnel_titre.personnel_id, titre.idtitre, titre.nom as libele_titre
		        FROM personnel_titre
			    INNER JOIN titre ON personnel_titre.titre_id = titre.idtitre
				    WHERE personnel_titre.personnel_id = ".$idpersonnel." AND personnel_titre.etat = 1";		
			try{

				$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchAll(PDO::FETCH_OBJ);

			}catch (PDOException $e){

		    	//Controller::error($e);
	    	}

		}		

	}