<?php 

/**
* 
*/
class Paie_element extends Model
{
	 
	public function getprime($paie_id){

		$sql = 'SELECT personnel.nom,prenom,idpersonnel,paie_element.*  FROM paie_element 
	        	LEFT JOIN personnel on idpersonnel = personnel_id
				WHERE  paie_id = "'.$paie_id.'" 
				AND (prime1 <> "" OR prime2 <> "" OR prime3 <> "" OR prime4 <> "" OR prime5 <> "" )   ';
				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetchall(PDO::FETCH_OBJ);
	}
	 
}
 ?>