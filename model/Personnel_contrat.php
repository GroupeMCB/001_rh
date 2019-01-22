<?php 

/**
* 
*/
class Personnel_contrat extends Model
{
	
	public function Contrat_en_cours($personnel_id){

		$sql = "SELECT * FROM 
		 personnel_contrat 
		WHERE  personnel_id = '".$personnel_id."'  ";	
		$pre = $this->db->prepare($sql);
	    $pre->execute();
		return $pre->fetch(PDO::FETCH_OBJ);

	}
	
}
 ?>