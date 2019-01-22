<?php 
if($this->Session->isLogged()==1){

	$this->redirect('users/login');
}
?>