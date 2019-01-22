<?php 


/**
* 
*/
class Personnel_titre extends Model
{

	//le titre courant de l'utilisateur
	public function GetTitreCourant($personnel_id){

		$sql = 'SELECT  titre_id
			        FROM  personnel_titre 
			        WHERE personnel_id = '.$personnel_id ;
			    $pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);
	}


	
	public function GetNombreTotalPersonneParTitre($titre_id,$mois){

		$sql = 'SELECT COUNT(idtitre) as cou
			        FROM `titre` 
			        LEFT JOIN personnel_titre on personnel_titre.titre_id = titre.idtitre
			        WHERE MONTH(personnel_titre.date) <='.$mois.' 
			        AND idtitre = '.$titre_id.'
			        GROUP BY idtitre';

			    $pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);

	}


	public function GetNombreTotalPersonneParTitreMoisAnterieur($titre_id,$mois){

		$sql = 'SELECT COUNT(idtitre) as cou
			        FROM `titre` 
			        LEFT JOIN personnel_titre on personnel_titre.titre_id = titre.idtitre
			        WHERE MONTH(personnel_titre.date) <'.$mois.' 
			        AND idtitre = '.$titre_id.'
			        AND etat = 0
			        GROUP BY idtitre';

			    $pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);

	}

	 /**
	 * Permet le nombre de personne par titre et par département pour un mois donné
	 * id ID de l'agent
	 * @return boolean true
	 */
	
	public function NombrePersonneParTitre($titre_id,$departement_id,$mois)
	{

			$sql = 'SELECT COUNT(nom) as cou,nom, iddepartement,libelle_departement 
			        FROM `titre` 
			        LEFT JOIN personnel_titre on personnel_titre.titre_id = titre.idtitre
			        LEFT JOIN personnel_departement on personnel_departement.personnel_id = personnel_titre.personnel_id
			        LEFT JOIN departement on departement.iddepartement = personnel_departement.departement_id
			        WHERE MONTH(personnel_titre.date) <='.$mois.' 
			        AND idtitre = '.$titre_id.'
			        AND iddepartement = '.$departement_id.'
			        GROUP BY nom,iddepartement';

			    $pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);
			        

	}


public function NombrePersonneParTitreMoisAnterieure($titre_id,$departement_id,$mois)
	{

			$sql = 'SELECT COUNT(nom) as cou,nom, iddepartement,libelle_departement 
			        FROM `titre` 
			        LEFT JOIN personnel_titre on personnel_titre.titre_id = titre.idtitre
			        LEFT JOIN personnel_departement on personnel_departement.personnel_id = personnel_titre.personnel_id
			        LEFT JOIN departement on departement.iddepartement = personnel_departement.departement_id
			        WHERE MONTH(personnel_titre.date) < '.$mois.' 
			        AND idtitre = '.$titre_id.'
			        AND iddepartement = '.$departement_id.'
			        AND personnel_titre.etat = 0
			        GROUP BY nom,iddepartement';
			    $pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);
			        

	}


	/**
	 * [ListePersonneParTitre description]
	 * @param [type] $titre_id       [description]
	 * @param [type] $departement_id [description]
	 * @param [type] $mois           [description]
	 */
	public function ListePersonneParTitre($titre_id,$departement_id,$mois)
	{

			$sql = 'SELECT personnel.nom,prenom
			        FROM `titre` 
			        LEFT JOIN personnel_titre on personnel_titre.titre_id = titre.idtitre
			        LEFT JOIN personnel_departement on personnel_departement.personnel_id = personnel_titre.personnel_id
			        LEFT JOIN departement on departement.iddepartement = personnel_departement.departement_id
			        LEFT JOIN personnel on personnel.idpersonnel = personnel_titre.personnel_id
			        WHERE MONTH(personnel_titre.date) <='.$mois.' 
			        AND idtitre = '.$titre_id.'
			        AND iddepartement = '.$departement_id.' 
			        GROUP BY personnel.nom,prenom';
			    $pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return  $pre->FETCHALL();
			        

	}


	/**********************************************************************************************************************/

	public function NombreSanctionParDepartement($departement_id,$mois,$type){


		$sql = 'SELECT count(iddepartement) as coun FROM personnel_sanction
				LEFT JOIN type_sanction on type_sanction.idtype_sanction = personnel_sanction.type_sanction_id
				LEFT JOIN personnel_departement on personnel_departement.personnel_id = personnel_sanction.personnel_id
				LEFT JOIN departement on departement.iddepartement = personnel_departement.departement_id
				WHERE MONTH(date_debut) ='.$mois.' 
			    AND iddepartement = '.$departement_id.'
			    AND type_sanction.idtype_sanction = '.$type.'
				GROUP BY iddepartement';
				$pre = $this->db->prepare($sql);
		 	    $pre->execute();
		   		return   $pre->fetch(PDO::FETCH_OBJ);
			        
	}



	public function TousLesTitres($titre_id,$mois){

			// $sql = 'SELECT * FROM personnel_titre WHERE 
			// 		titre_id = "'.$titre_id.'" 
			// 		AND MONTH(date) <= '.$mois 'GROUP BY personnel_id';
			// $pre = $this->db->prepare($sql);
		 // 	$pre->execute();
		 //   	return   $pre->fetch(PDO::FETCH_OBJ);
	}



	public function getNombrePersonneParTitre($titre_id,$mois){

		$cle =  explode('-', $mois);

		$sql = "SELECT COUNT(titre_id) as nombre 
				FROM personnel_titre
				WHERE MONTH(date) in ($cle[0],$cle[1],$cle[2]) 
				AND titre_id = $titre_id
				GROUP BY titre_id
		";

		$pre = $this->db->prepare($sql);
		$pre->execute();
		return   $pre->fetch(PDO::FETCH_OBJ);
	}


	public function getNombrePersonneEnCongeParDepartement($departement_id,$mois){

		$sql = 'SELECT COUNT(pers.personnel_id) as nombre 
		        FROM personnel_departement as pers
				LEFT JOIN planning_conge as pla  on pla.personnel_id = pers.personnel_id
		        WHERE pers.etat = 1 
		        AND departement_id = '.$departement_id.' 
		        AND MONTH(date_debut) = '.$mois.' 
		        AND pla.etat = 1 
		        GROUP BY pers.personnel_id';
		$pre = $this->db->prepare($sql);
		$pre->execute();
		return   $pre->fetch(PDO::FETCH_OBJ);


	}


	public function getNombreCertificatParDepartement($departement_id,$mois){

		$sql = 'SELECT COUNT(pers.personnel_id) as nombre 
		        FROM personnel_departement as pers
				LEFT JOIN certificat as cert  on cert.personnel_id = pers.personnel_id
		        WHERE pers.etat = 1 
		        AND departement_id = '.$departement_id.' 
		        AND MONTH(date_debut) = '.$mois.' 
		        GROUP BY pers.personnel_id';
		$pre = $this->db->prepare($sql);
		$pre->execute();
		return   $pre->FETCHALL();
	}


	//Nombre de personnes en fin de contrat :: ID motif fin de contrat = 3
	public function getNombrePersonneFinContrat($departement_id,$mois){

		$sql = 'SELECT COUNT(pers.personnel_id) as nombre 
		        FROM personnel_departement as pers
				LEFT JOIN personnel   on personnel.idpersonnel = pers.personnel_id
		        WHERE pers.etat = 1 
		        AND departement_id = '.$departement_id.' 
		        AND motif_sortie_id = 3 
		        AND MONTH(date_sortie) = '.$mois.' 
		        GROUP BY pers.personnel_id';
		$pre = $this->db->prepare($sql);
		$pre->execute();
		return   $pre->FETCHALL();
	}


	//Nombre de personnes ayant démissionner ou abandonner :: ID (1,2)
	public function getNombrePersonneAbandonEtDemission($departement_id,$mois){

		$sql = 'SELECT COUNT(pers.personnel_id) as nombre 
		        FROM personnel_departement as pers
				LEFT JOIN personnel   on personnel.idpersonnel = pers.personnel_id
		        WHERE pers.etat = 1 
		        AND departement_id = '.$departement_id.' 
		        AND motif_sortie_id in (1,2) 
		        AND MONTH(date_sortie) = '.$mois.' 
		        GROUP BY pers.personnel_id';
		$pre = $this->db->prepare($sql);
		$pre->execute();
		return   $pre->FETCHALL();
	}
 

}

 ?>