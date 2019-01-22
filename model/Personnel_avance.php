<?php 

/**
* 
*/
class Personnel_avance extends Model
{

	public function getAvanceByPeriode($personnel_id,$paie_id){

		$sql = 'SELECT SUM(montant_avance) as montant  FROM personnel_avance 
				WHERE personnel_id = '.$personnel_id.'
				AND paie_id = "'.$paie_id.'"   ';
				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);

	}

	public function getAvanceAll($paie_id){

		$sql = 'SELECT personnel.nom,prenom,idpersonnel,personnel_avance.*  FROM personnel_avance 
		LEFT JOIN personnel on idpersonnel = personnel_id
				WHERE  paie_id = "'.$paie_id.'"   ';
				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetchall(PDO::FETCH_OBJ);
	}

	public function gettotalavance($paie_id){

		$sql = 'SELECT SUM(montant_avance) as montant  FROM personnel_avance 
				WHERE paie_id = "'.$paie_id.'"   ';
				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);

	}
	 
}
 ?>