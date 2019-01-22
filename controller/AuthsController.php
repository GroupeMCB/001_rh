<?php


class AuthsController extends Controller
{
	public function index(){

		

	}

	

	public function login(){

			$userId=array();
		$this->loadModel('User');
	 

		if($this->request->data){

	 
					$userId=$this->User->find(array
			    	('conditions' => 'pass ="'.addslashes($this->encrypt($this->request->data->password)).'" AND username ="'.addslashes($this->request->data->username).'"') );
				 
					if(!is_null($userId)){
						
				 
						if(isset($userId[0]->iduser)){
					 
					//debug($userId); die();
					$this->Session->id = $userId[0]->iduser;
					$this->Session->setLogged($userId[0]->iduser);
					$this->Session->write("userInfoName",$userId[0]->nom);
					$this->Session->write("userInfoSurname",$userId[0]->prenom);
					$this->Session->write("idprofil",$userId[0]->idprofil); 
					$this->Session->write("idprofil",$userId[0]->idprofil); 
					 
					$this->redirect('personnels/dashboard',30);

					
				}
			
				else{
					// $this->Session->setFlash("<h4><b>Veuillez Saisir de Corrects Identifiants </b></h4>","danger");
					// $this->Session->setError();
				}
			}
				}

				 
	}


public function disconnect(){
	$this->Session->disconnect();
	$this->redirect('auths/login',30);
}

}

?>