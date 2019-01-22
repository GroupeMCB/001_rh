<?php

/**
*
*/
class Personnel extends Model
{

	/**
	* Permet d'avoir le type de contrat actuel dans lequel un employé est
	* @param idpersonnel du personnel
	*
	* @return un tableau d'objets contenant le type de contrat
	*/
	public function findTypeContrat($idpersonnel = null){

		$sql = "SELECT personnel_contrat.*, type_contrat.idtype_contrat, type_contrat.nom as contrat
		        FROM personnel_contrat
			    INNER JOIN type_contrat ON type_contrat.idtype_contrat = personnel_contrat.type_contrat_id
			    WHERE personnel_contrat.personnel_id = ".$idpersonnel." AND personnel_contrat.etat = 1";
		try{
			$pre = $this->db->prepare($sql);
		    $pre->execute();
			return $pre->fetchAll(PDO::FETCH_OBJ);

		}catch (PDOException $e){

	    	//Controller::error($e);
	    }
	}

	/**
	* Permet d'avoir la liste des personnes qui ont été au moins une fois absente
	*
	* @return un tableau d'objets contenant la liste des personnes
	**/
	public function personnelAbsence()
	{
		$sql = "SELECT absence.idabsence, personnel.idpersonnel, personnel.nom, personnel.prenom, absence.date_debut, absence.date_fin, absence.nbre_heures, absence.type_absence_id, absence_justifiee.certificat_id
		        FROM absence
			    LEFT JOIN personnel ON absence.personnel_id = personnel.idpersonnel
			    LEFT JOIN absence_justifiee ON absence_justifiee.absence_id = absence.idabsence
			    ORDER BY absence.insertdate DESC";
		try{

			$pre = $this->db->prepare($sql);
		    $pre->execute();
			return $pre->fetchAll(PDO::FETCH_OBJ);

		}catch (PDOException $e){

	    	//Controller::error($e);
	    }
	}

	/**
	* Permet de recupérer le département actuel d'un emplyé
	* @param $idpersonnel Identifiant de l'employé
	*
	* @return un objet contenant l'id du département, date et état
	***/
	public function getEmployeDepartement($idpersonnel)
	{

		     	$sql = "SELECT personnel_departement.*, departement.libelle_departement
		        FROM personnel_departement
			    INNER JOIN departement ON departement.iddepartement = personnel_departement.departement_id
			    WHERE etat = 1 AND personnel_departement.personnel_id = $idpersonnel ORDER BY personnel_departement.date DESC";
		try{

			$pre = $this->db->prepare($sql);
		    $pre->execute();
			return $pre->fetchAll(PDO::FETCH_OBJ);

		}catch (PDOException $e){

	    	//Controller::error($e);
	    }
	}

	/**
	* Permet de recupérer le titre actuel d'un emplyé
	* @param $idpersonnel Identifiant de l'employé
	*
	* @return un objet contenant l'id du titre, date et état
	***/
	public function getEmployeTitre($idpersonnel)
	{
		$sql = "SELECT personnel_titre.*, titre.nom
		        FROM personnel_titre
			    INNER JOIN titre ON titre.idtitre = personnel_titre.titre_id
			    WHERE etat = 1 AND personnel_titre.personnel_id = $idpersonnel ORDER BY personnel_titre.date DESC";
		try{

			$pre = $this->db->prepare($sql);
		    $pre->execute();
			return $pre->fetchAll(PDO::FETCH_OBJ);

		}catch (PDOException $e){

	    	//Controller::error($e);
	    }
	}


	public function getagebypersonnel($debut,$fin,$sexe){

		$sql = "SELECT count(*) as nombre 
		FROM personnel 
		WHERE '".date("Y")."' - YEAR(date_naissance)  > '".$debut."' AND  '".date("Y")."' - YEAR(date_naissance) <= '".$fin."'
		AND sexe = '".$sexe."'  ";
		$pre = $this->db->prepare($sql);
	    $pre->execute();
		return $pre->fetch(PDO::FETCH_OBJ);

	}


	public function getanciennete($debut,$fin){

		$sql = "SELECT count(*) as nombre 
		FROM personnel 
		WHERE '".date("Y")."' - YEAR(date_entree)  > '".$debut."' AND  '".date("Y")."' - YEAR(date_entree) <= '".$fin."'  ";	
		$pre = $this->db->prepare($sql);
	    $pre->execute();
		return $pre->fetch(PDO::FETCH_OBJ);

	}

	public function gettotalemploye(){

		$sql = "SELECT count(idpersonnel) as nombre 
				FROM personnel  ";
		$pre = $this->db->prepare($sql);
	    $pre->execute();
		return $pre->fetch(PDO::FETCH_OBJ);
	}

	public function getNombreentree($date_debut,$date_fin){

		$sql = "SELECT count(idpersonnel) as nombre 
				FROM personnel 
				WHERE date_entree between '".$date_debut."' AND '".$date_fin."' ";
		$pre = $this->db->prepare($sql);
	    $pre->execute();
		return $pre->fetch(PDO::FETCH_OBJ);
	}

	public function getNombreSortie($date_debut,$date_fin){

		$sql = "SELECT count(idpersonnel) as nombre 
				FROM personnel 
				WHERE date_sortie between '".$date_debut."' AND '".$date_fin."' 
				AND active = 0";
		$pre = $this->db->prepare($sql);
	    $pre->execute();
		return $pre->fetch(PDO::FETCH_OBJ);
	}

	public function getNombreEmployeEnDebutAnee($date_debut){

		$sql = "SELECT count(idpersonnel) as nombre 
				FROM personnel 
				WHERE date_entree <= '".$date_debut."'   ";
		$pre = $this->db->prepare($sql);
	    $pre->execute();
		return $pre->fetch(PDO::FETCH_OBJ);
	}


	public function getEmbauche($date_annee,$date_courant){

		$sql = "SELECT count(idpersonnel) as nombre 
				FROM personnel 
				WHERE date_entree > '".$date_annee."' AND date_entree <= '".$date_courant."'   ";
		$pre = $this->db->prepare($sql);
	    $pre->execute();
		return $pre->fetch(PDO::FETCH_OBJ);
	}


	public function getPersonnelTitre(){

		$sql = "SELECT count(titre_id) as nombre, nom
				FROM titre  
				LEFT JOIN `personnel_titre` on idtitre = titre_id
				WHERE etat = 1 group by titre_id";
		$pre = $this->db->prepare($sql);
	    $pre->execute();
		return $pre->fetchall(PDO::FETCH_OBJ);
	}



	public function getPersonnelContrat($idpersonnel){

		$sql = "SELECT personnel_contrat.*
		        FROM personnel_contrat			  
			    WHERE etat = 1 AND personnel_id = $idpersonnel ";
		try{

			$pre = $this->db->prepare($sql);
		    $pre->execute();
			return $pre->fetchAll(PDO::FETCH_OBJ);

		}catch (PDOException $e){

	    	//Controller::error($e);
	    }
	}

}


 ?>
