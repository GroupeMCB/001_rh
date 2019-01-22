<?php
/**
* Model princial
*/
class Model
{
	static $connections = array();
	public $table = false;
	public $db;
	public $primarykey = 'id';
   //public $confs = '';

	public function __construct($confs = NULL)
	{
		if ($confs == NULL) {
			$confs = 'default';
		}
		 

		 //Récupere le nom de la table automatiquement
		 if($this->table  === false) {
		 	$this->table = strtolower(get_class($this));
		 }
		$conf = Conf::$databases[$confs];
		 
		// if(Model::$connections[$conf] != NULL){
		// 	$this->db = Model::$connections[$conf];
		// 	return true;
		// }

		// debug(Model::$connections[$conf]);
		// if( isset(Model::$connections[$conf]) ){
		// 	$this->db = Model::$connections[$conf];
		// 	return true;
		// }

		try {
			$pdo = new PDO('mysql:host='.$conf['host'].';dbname='.$conf['database'].';',$conf['login'],$conf['password'],
				array(PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8') );

			// a supprimer après la phase de développement
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
			//$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		//	Model::$connections[$conf] = $pdo;
			$this->db = $pdo;
		 }
		 catch(PDOException $e){
		 	if(Conf::$debug == 1) {
		 		die($e->getMessage());
		 	}
		 	else
		 	{
		 		echo "Impossible de charger la base de données";
		 	}
		 }

	}


	/**
	 * [find description]
	 * @param  [type] $req [description]
	 * @return [type]      [description]
	 */
	public function find($req = null)
	{
	    $sql = 'SELECT ';

	    if(isset($req['fields'])){
	    	if(is_array($req['fields'])){

	    		$sql .= implode(', ',$req['fields']);
	    	}
	    	else{

	    		$sql .= $req['fields'];
	    	}
	    }
	    else {
	    	$sql .= ' * ';
	    }

	    $sql .= ' FROM '.$this->table.' as '.get_class($this).'';

	    if(isset($req['conditions'])){
	    	$sql .= ' WHERE ';
	    	if(!is_array($req['conditions'])){
	    		$sql .= $req['conditions'];
	    	}
	    	else {
	    		$cond = array();
	    			foreach ($req['conditions'] as $k => $v) {
	    				if(!is_numeric($v)){
	    					$v = '"'.$this->db->quote($v).'"';
	    				}
	    				$cond[] = "$k = $v";

	    			}
	    		$sql .= implode(' AND ', $cond);
	    	}


	    }

	    if(isset($req['limit'])){

	    	$sql .= ' LIMIT '.$req['limit'];
	    }

// debug($sql);
		try{

			$pre = $this->db->prepare($sql);
			$pre->execute();
			return   $pre->fetchAll(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		 {

		 	if(Conf::$debug == 1) {

                  debug($e->getMessage());
            }
            else{
               debug("Erreur au niveau de la requête SAVE du Model");

            }
		 }
	}

	public function findfirst($req){

		return current($this->find($req));
	}

public function findlast($req){

		return end($this->find($req));
	}

	public function findcount($conditions){
		   $res = $this->findfirst(array(
				'fields' => 'COUNT('.$this->primarykey.''.$this->table.') as count ',
				'conditions' => $conditions

				));
		   	return $res->count;
				}


	public function delete($id){

		$key = $this->primarykey.''.$this->table;
		$sql = "DELETE FROM {$this->table} WHERE {$key} = $id";
		$this->db->query($sql);
	}

	public function deleteFromTable($latable, $id)
	{
		$key = $this->primarykey.''.$latable;
		$sql = "DELETE FROM {$latable} WHERE {$key} = $id";
		$this->db->query($sql);
	}

	public function save($data){

		$fields =array();
		$d =array();
		$key = $this->primarykey.''.$this->table;
		foreach ($data as $k => $v) {
			$fields[] = "$k=:$k";
			$d[":$k"] = $v;
		}
 
		if(isset($data->$key) && !empty($data->$key) ){
		 	$sql = 'UPDATE '.$this->table.' SET '.implode(',',$fields).' WHERE '.$key.'=:'.$key;
		    $pre = $this->db->prepare($sql);
		    $pre->execute($d);
		    return $data->$key;
		}
		else{

		echo	 $sql = "INSERT INTO ".$this->table." (";
			foreach ($data as $key => $value) {
				$sql .= "$key,";
			}
			$sql = 	substr($sql, 0, -1);
			$sql .= ") VALUES (";
			foreach ($data as $value) {
				$value = addslashes($value);
				$sql .= "'$value',";
			}
			$sql = substr($sql, 0, -1);
			$sql .= ")";
			$pre = $this->db->prepare($sql);
	 	    $pre->execute($d);
	   		return $this->db->LastInsertId();
		}

	}


	/**
	 * Sauvegarder dans une table
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function saveInTable($latable, $data){

		$fields =array();
		$d =array();
		$key = $this->primarykey.''.$latable;
			foreach ($data as $k => $v) {
				$fields[] = "$k=:$k";
				$d[":$k"] = $v;
			}


		if(isset($data[$key]) && !empty($data[$key]) ){
			 $sql = 'UPDATE '.$latable.' SET '.implode(',',$fields).' WHERE '.$key.'=:'.$key;
		    $pre = $this->db->prepare($sql);

		    $pre->execute($d);

	   		return true;
		}
		else{

			$sql = "INSERT INTO ".$latable." (";
			foreach ($data as $key => $value) {
				$sql .= "$key,";
			}
			$sql = 	substr($sql, 0, -1);
			$sql .= ") VALUES (";
			foreach ($data as $value) {
				$sql .= "'$value',";
			}
			$sql = substr($sql, 0, -1);
			$sql .= ")";

			$pre = $this->db->prepare($sql);
	 	    $pre->execute($d);
	   		return $this->db->LastInsertId();
		}

	}




/**
		* Permet de récupérer plusieurs lignes d'une table spécifique dans la base de données
		*
		* @param $data conditions de récupération
		* @param $latable Table spécifique sur laquelle la requete sera executée
		*
		**/
		// public function findInTable($latable, $conditions = null){
		// 	$conditions = "1=1";
		// 	$fields = "*";
		// 	$limit = "";
		// 	$order = "id DESC";
		// 	//extract($data);

		// 	//if (isset($data["limit"])) { $limit = "LIMIT ".$data["limit"]; }
		// 	$sql = "SELECT $fields FROM ".$latable;
		// 	if(is_array($conditions)){

		// 		$sql .= " WHERE $contions";
		// 	}

		//     $pre = $this->db->prepare($sql);
		// 	$pre->execute();
		// 	return   $pre->fetchAll(PDO::FETCH_OBJ);

		// }



	/**
	 * [find description]
	 * @param  [type] $req [description]
	 * @return [type]      [description]
	 */
	public function findInTable($latable, $req = null)
	{
	    $sql = 'SELECT ';

	    if(isset($req['fields'])){
	    	if(is_array($req['fields'])){

	    		$sql .= implode(', ',$req['fields']);
	    	}
	    	else{

	    		$sql .= $req['fields'];
	    	}
	    }
	    else {
	    	$sql .= ' * ';
	    }

	    $sql .= ' FROM '.$latable.' as '.get_class($this).'';

	    if(isset($req['conditions'])){
	    	$sql .= ' WHERE ';
	    	if(!is_array($req['conditions'])){
	    		$sql .= $req['conditions'];
	    	}
	    	else {
	    		$cond = array();
	    			foreach ($req['conditions'] as $k => $v) {
	    				if(!is_numeric($v)){
	    					$v = '"'.$this->db->quote($v).'"';
	    				}
	    				$cond[] = "$k = $v";

	    			}
	    		$sql .= implode(' AND ', $cond);
	    	}


	    }

	    if(isset($req['limit'])){

	    	$sql .= ' LIMIT '.$req['limit'];
	    }

		$pre = $this->db->prepare($sql);
		$pre->execute();
		return   $pre->fetchAll(PDO::FETCH_OBJ);
	}


	public function findfirstInTable($req){

		return current($this->findInTable($req));
	}




}
