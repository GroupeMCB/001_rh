<?php 

/**
* 
*/
class Personnel_fraismission extends Model
{
	
	 public function getFraismissionByPeriode($personnel_id,$paie_id){

		$sql = 'SELECT SUM(montant_mission) as montant  FROM personnel_fraismission
				WHERE personnel_id = '.$personnel_id.'
				AND paie_id = "'.$paie_id.'"   ';

				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);

	}

	public function getFraismissionAll($paie_id){

		$sql = 'SELECT personnel.nom,prenom,idpersonnel,personnel_fraismission.*  FROM personnel_fraismission
	        	LEFT JOIN personnel on idpersonnel = personnel_id
				WHERE  paie_id = "'.$paie_id.'"   ';
				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetchall(PDO::FETCH_OBJ);
	}
}
 ?>