<?php 
/**
* Cette classe permet de savoir quel URL a été appelé
*/
class Request
{
	public $url; // contient l'url appelé par l'utilisateur
	public $param = 1;
	public $prefixe = false;
	public $data = false;
		function __construct()
		{

			if(isset($_SERVER['PATH_INFO'])){
			   $this->url = $_SERVER['PATH_INFO'];
		    }
		    


			if(isset($_GET['param'])){
				if(is_numeric($_GET['param'])){

					if($_GET['param'] > 0){
					 	$this->param = round($_GET['param']);

						}
				}


			}
			if(!empty($_POST)){

				$this->data = new stdClass();
				foreach ($_POST as $key => $value) {
					$this->data->$key = $value;
				}
				
			}
		}
}

 ?>