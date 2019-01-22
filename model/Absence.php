<?php 

/**
* 
*/
class Absence extends Model
{
	
	public function getAbsencenonjustifiebypersonnel ($personnel_id,$date_debut,$date_fin){

				$sql = "SELECT SUM(nbre_heures) as nombre FROM absence
				WHERE type_absence_id = 1 
				AND personnel_id = ".$personnel_id;
				$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);
	}


	public function getAbsencejustifiebypersonnel ($personnel_id,$date_debut,$date_fin){

				$sql = "SELECT SUM(nbre_heures) as nombre FROM absence
				WHERE type_absence_id = 2 
				AND personnel_id = ".$personnel_id;
				$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);
	}


 

}