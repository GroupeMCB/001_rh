<?php 

/**
* 
*/
class Annee extends Model
{
	
	  public function getanneeencours(){

	 	  		$sql = "SELECT * FROM annee 
	 	  				WHERE etat = 1";
	 	  		$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);	
	 }

}
 ?>