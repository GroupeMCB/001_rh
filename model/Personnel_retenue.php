<?php 

/**
* 
*/
class Personnel_retenue extends Model
{
	
	 public function getretenueByPeriode($personnel_id,$paie_id){

		$sql = 'SELECT SUM(montant_retenue) as montant  FROM personnel_retenue 
				WHERE personnel_id = '.$personnel_id.'
				AND paie_id = "'.$paie_id.'"   ';
				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);

	}


	public function getRetenueAll($paie_id){

		$sql = 'SELECT personnel.nom,prenom,idpersonnel,personnel_retenue.*  FROM personnel_retenue 
		LEFT JOIN personnel on idpersonnel = personnel_id
				WHERE  paie_id = "'.$paie_id.'"   ';
				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetchall(PDO::FETCH_OBJ);
	}
}

 ?>