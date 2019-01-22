<?php 

/**
* 
*/
class Personnel_regularisation extends Model
{
	public function getregularisationByPeriode($personnel_id,$paie_id){

		$sql = 'SELECT  SUM(montant_trop_percu) as montant  FROM personnel_regularisation
				WHERE personnel_id = '.$personnel_id.'
				AND paie_id = "'.$paie_id.'"   ';
				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);

	}

	public function getregularisation($paie_id){

		$sql = 'SELECT personnel.nom,prenom,idpersonnel,personnel_regularisation.*  FROM personnel_regularisation 
		LEFT JOIN personnel on idpersonnel = personnel_id
				WHERE  paie_id = "'.$paie_id.'"   ';
				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetchall(PDO::FETCH_OBJ);
	}
	 
}
 ?>