<?php
/**
 * Controller générale
 */
 class Controller
 {
 	public $request;
 	private $vars = array();
 	public $layout = 'default_home';
 	public $rendered  = false;

 	/**
 	 * [__construct constructeur de la class]
 	 * @param [Objet] $request [initialise l'objet request]
 	 */
 	function __construct($request =null){
 		$this->Session = new Session();
		$this->Form = new Form($this);
		$this->Helper = new Helper($this);

 		if($request){

 		$this->request = $request;
 		//require ROOT.DS.'config'.DS.'hook.php';
 			}
 	}

 	/**
 	 * [render Affiche la vue]
 	 * @param  [type] $view [Nom de la vue]
 	 * @return [type]       [retourne la vue]
 	 */
 	public function render($view,$layout =null){

        if(isset($layout)) $this->layout = "nolayout";
        if($this->rendered) return false;
        else {
                extract($this->vars);
                if(strpos($view, '/') === 0){
                $view = ROOT.DS.'view'.$view.'.php';
                }
                else {
                $view = ROOT.DS.'view'.DS.$this->request->controller.DS.$view.'.php';
                    }

            ob_start();
            require($view);
            $content_for_layout = ob_get_clean();
            $controller = $this->request->controller; 
            if($controller=='auths' ){
          
                require ROOT.DS.'view'.DS.'auths'.DS."login".'.php';
                 }
            else{
                require ROOT.DS.'view'.DS.'layout'.DS.$this->layout.'.php';
                 }
            $this->rendered = true;
            }
    }
 	/**
 	 * [set Permet de passer un ou plusieurs les variables à la vue]
 	 * @param [var] $key   nom de la variable ou tableau de variables
 	 * @param [var] $value Valeur de la variable
 	 */
 	public function set($key,$value = null){
 		if(is_array($key)) {
 			$this->vars += $key;
 		}
 		else {
			$this->vars[$key] = $value;
			}
 	}

 	/**
 	 * [loadModel pour charger un model]
 	 * @param  [string] $name [Nom du model a chargé]
 	 * @return [Objet]       [Retourne un objet]
 	 */
 	public function loadModel($name, $baseDonnee = NULL){
 	    $file = ROOT.DS.'model'.DS.$name.'.php';
        require_once($file);

        if(!isset($this->$name)){
            $this->$name = new $name($baseDonnee);

 		}
 		else {
 			echo "Le modèle ".$name." n'est pas chargé";
 		}
 	}


 	public function e404($message){
 	//	header('HTTP/1.0 404 not found');
 		$this->set('message',$message);
 		$this->render('/errors/404');
 		die();
 	}


 	/**
 	 * [request description] Permet d'appeler un controller depuis n'importe quelle vue
 	 * @param  [type] $controller [le controlleur a appeler]
 	 * @param  [type] $action     [l'action ]
 	 * @return [type]              les actions du controller applé
 	 */
 	public function request($controller, $action){

 				$controller .= 'Controller';
 				require ROOT.DS.'controller'.DS.$controller.'php';
 				$c = new $controlleur();
 				return $c->action();


 	}

 	public function redirect($url,$code = null){

 		if($code == 30){
 			header("HTTP/1.1 301 Moved Permanently");
 			header("Location:".Router::url($url));
 			}
 	}


 	 public  function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
            return $d;
        }
    }

   public function encrypt($data) {
    $key = "mcbdsi17";  // Clé de 8 caractères max
    $data = serialize($data);
    $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td,$key,$iv);
    $data = base64_encode(mcrypt_generic($td, '!'.$data));
    mcrypt_generic_deinit($td);
    return $data;
}

public function decrypt($data) {
    if(!empty($data)) {
        $key = "mcbdsi17";
        $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td,$key,$iv);
        $data = mdecrypt_generic($td, base64_decode($data));
        mcrypt_generic_deinit($td);

        if (substr($data,0,1) != '!')
            return false;

        $data = substr($data,1,strlen($data)-1);
        return unserialize($data);
    }
    else return 0;
}

 }
