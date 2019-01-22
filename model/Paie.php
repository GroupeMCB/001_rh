<?php 

/**
* 
*/
class Paie extends Model
{
	
	 public function findpersonnelinfopaie($type){


	 			$sql = "SELECT nom,prenom,info.*,idpersonnel,personnel.statut ,banque.* FROM personnel 
	 					LEFT JOIN personnel_infopaie as info on info.personnel_id = personnel.idpersonnel
	 					LEFT JOIN banque on idbanque = info.banque
	 					WHERE type_personnel_id = '".$type."' ORDER by statut asc";
		 		$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchAll(PDO::FETCH_OBJ);

	 }

	  public function findpersonnelinfopaiewithfield($type){


	 			$sql = "SELECT  idpersonnel AS personnel_id FROM personnel 
	 					LEFT JOIN personnel_infopaie as info on info.personnel_id = personnel.idpersonnel
	 					WHERE type_personnel_id = '".$type."' ";
		 		$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchAll(PDO::FETCH_OBJ);

	 }


	  public function findpersonnelinfopaieall($type,$paie_id){


	 			$sql = "SELECT nom,prenom,type_personnel_id,type_contrat,info.salaire_base as salaire_base,info.type_paiement,info.banque,numero_compte,idpersonnel,paie_element.*,idpersonnel_infopaie,nombre_enfant_charge,banque.* FROM personnel 
	 					LEFT JOIN personnel_infopaie as info on info.personnel_id = personnel.idpersonnel
	 					LEFT JOIN paie_element on paie_element.personnel_id = idpersonnel
	 					LEFT JOIN banque on idbanque = info.banque
	 					WHERE type_personnel_id = '".$type."'
	 					AND paie_id = '".$paie_id."' ";
		 		$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchAll(PDO::FETCH_OBJ);
	 }


	 public function findpaie(){
	 			$sql = "SELECT libelle_annee,paie.* FROM paie 
	 					LEFT JOIN annee on annee.idannee = paie.annee_id ";
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchAll(PDO::FETCH_OBJ);

	 }

	  public function findpaieactive(){
	 			$sql = "SELECT libelle_annee,paie.* FROM paie 
	 					LEFT JOIN annee on annee.idannee = paie.annee_id 
	 					WHERE paie.etat in (1,2)";
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchAll(PDO::FETCH_OBJ);

	 }


	 public function getnextpaie($paieencours){

	 				$paie = $paieencours+1;
	 			$sql = "SELECT * FROM paie 
	 					WHERE idpaie = ".$paie;					
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);	
	 }


	 public function getpaieencours(){

	 	  		$sql = "SELECT * FROM paie 
	 	  				WHERE etat = 1";
	 	  		$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);	
	 }

	  public function getpaiebyid($id){

	 	  		$sql = "SELECT * FROM paie 
	 	  				WHERE idpaie = ".$id;
	 	  		$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);	
	 }


	 public function getperformanceagent($paie_id,$personnel_id = null){

			 	$sql = "SELECT * FROM personnel 
			 			LEFT JOIN perf_agent on perf_agent.personnel_id = idpersonnel
			 	  		WHERE paie_id = ".$paie_id;
			 	if(is_numeric($personnel_id) && $$personnel_id != 0){

			 	$sql .= " AND personnel_id = ".$personnel_id;

		 	}
		 	 
	 	  		$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchall(PDO::FETCH_OBJ);	

		 

	 }


	 public function getperformanceagentbypersonnel($paie_id,$personnel_id){

			 	$sql = "SELECT * FROM   perf_agent  
			 	  		WHERE paie_id = ".$paie_id."
			 	  		AND personnel_id = ".$personnel_id;
	 	  		$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);	

	 }



	public function getsalairebrutbytype($type_personnel_id,$paie_id){


	 			$sql = "SELECT  type_contrat ,SUM(salaire_brut) AS montant FROM personnel 
	 					LEFT JOIN paie_element on paie_element.personnel_id = idpersonnel
	 					WHERE type_personnel_id = '".$type_personnel_id."'
	 					AND paie_id = '".$paie_id."'
	 					GROUP BY type_contrat ";
		 		$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchAll(PDO::FETCH_OBJ);
	 }


	 public function getEtatbybanque($paie_id,$banque_id){

	 	$sql = "SELECT * FROM paie_element 
	 			LEFT JOIN personnel on idpersonnel = paie_element.personnel_id
	 			LEFT JOIN personnel_infopaie as info on info.personnel_id = idpersonnel
	 			WHERE paie_element.paie_id = ".$paie_id." 
	 			AND info.banque = ".$banque_id."
	 			AND type_paiement = 'VIREMENT' ";
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchAll(PDO::FETCH_OBJ);
	 }


	  public function getEtatbyespece($paie_id){

	 	$sql = "SELECT * FROM paie_element 
	 			LEFT JOIN personnel on idpersonnel = paie_element.personnel_id
	 			LEFT JOIN personnel_infopaie as info on info.personnel_id = idpersonnel
	 			WHERE paie_element.paie_id = ".$paie_id." 
	 			AND type_paiement <> 'VIREMENT' ";
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchAll(PDO::FETCH_OBJ);
	 }


	 public function gettotalnetpayer($paie_id){

	 	$sql = "SELECT SUM(salaire_net) as montant FROM paie_element
	 			WHERE paie_id = ".$paie_id;
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);
	 }


	  public function getEtatbyboa($paie_id){

	 	$sql = "SELECT SUM(salaire_net) as montant FROM paie_element 
	 			LEFT JOIN personnel_infopaie as info on info.personnel_id = paie_element.personnel_id
	 			WHERE paie_element.paie_id = ".$paie_id."
	 			AND info.banque =  3
	 			AND type_paiement = 'VIREMENT' ";
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);
	 }

	  public function getEtatbyautrebanque($paie_id){

	 	$sql = "SELECT SUM(salaire_net) as montant FROM paie_element 
	 			LEFT JOIN personnel_infopaie as info on info.personnel_id = paie_element.personnel_id
	 			WHERE paie_element.paie_id = ".$paie_id."
	 			AND info.banque <>  3
	 			AND type_paiement = 'VIREMENT' ";
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);
	 }

	  public function gettotalEtatbyespece($paie_id){

	 	$sql = "SELECT SUM(salaire_net) as montant FROM paie_element 
	 			LEFT JOIN personnel_infopaie as info on info.personnel_id = paie_element.personnel_id
	 			WHERE paie_element.paie_id = ".$paie_id."
	 			AND type_paiement <> 'VIREMENT' ";
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);
	 }

	 public function gettotalcnss($paie_id){

	 	$sql = "SELECT SUM(salaire_brut) as montant FROM paie_element 
	 			LEFT JOIN personnel on idpersonnel = personnel_id 
	 			WHERE numero_cnss <> '' AND paie_id = ".$paie_id;
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);

	 }


	 public function getallpersonnecnss($paie_id){


	 	$sql =  "SELECT nom,prenom,numero_cnss, salaire_brut FROM paie_element
	 			  LEFT JOIN personnel on idpersonnel = personnel_id 
	 			  WHERE numero_cnss <> '' AND paie_id =	".$paie_id;
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetchall(PDO::FETCH_OBJ);
	 }


	 public function gettotalipts($paie_id){


	 	$sql = "SELECT SUM(iptsnet) as montant FROM paie_element 
	 			LEFT JOIN personnel on idpersonnel = personnel_id 
	 			WHERE type_contrat in (2,3) AND paie_id =".$paie_id;
	 			$pre = $this->db->prepare($sql);
			    $pre->execute();
				return $pre->fetch(PDO::FETCH_OBJ);
	 }

}

 ?>