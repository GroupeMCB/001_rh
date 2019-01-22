<?php 
/**
* Le Dispatcher permet de récupérer l'URL et de savoir quel composant appelé
*/
class Dispatcher
{
	var $request;

	/**
	 * [__construct description]
	 */
	function __construct()
	{
		$this->request = new Request();
		Router::parse($this->request->url,$this->request);
		$controller = $this->loadController();
		$action =$this->request->action;
		if($this->request->prefixe){
 			$action = $this->request->prefix.'_'.$action;
		}
		if(!in_array($action, array_diff(get_class_methods($controller),get_class_methods('Controller')))){
			$this->error('Le controler '.$this->request->controller.' n\'a pas de methode '.$action);

		}

		//Pour detecter la méthode du controller a appelé en fonction de l'url
		call_user_func_array(array($controller,$action),$this->request->params);
		$controller->render($action);

	}

	/**
	 * [error description]
	 * @param  [type] $message [description]
	 * @return [type]          [description]
	 */
	function error($message){
		$controller = new Controller($this->request);
		$controller->e404($message);		 
	}

	/**
	 * [loadController description]
	 * @return [type] [description]
	 */
	function loadController(){

		$name = ucfirst($this->request->controller).'Controller';
		$file = ROOT.DS.'controller'.DS.$name.'.php';
		
		if(!file_exists($file)){
			$this->error('Le controller '.$this->request->controller.' n\'existe pas');
		}
		 
		require $file;
		$controller = new $name($this->request);
		return $controller;

	}
}


 ?>