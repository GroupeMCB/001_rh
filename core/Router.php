<?php 
/**
* Permet de parser une URL
*/
class Router
{

	static $prefixes = array();
	
	static function prefix($url, $prefix){
		self::$prefixes[$url] = $prefix;

	}


	/**
	*Permet de parser une url
	*@param $url URL à parser
	*@return tableau contenant les paramètres
	**/
	static function parse($url,$request){
		$url = trim($url,'/');
		$params = explode('/', $url);
		if(in_array($params[0],array_keys(self::$prefixes))){
			$request->prefix = self::$prefixes[$params[0]];
			array_shift($params);
			 
		}

		$request->controller = $params[0];
		$request->action = isset($params[1]) ? $params[1] : 'index';
		$request->params = array_slice($params, 2);
		return true;

	}

	static function connect($redir,$origin){


	}

	/**
	 * Permet d'afficher une URL
	 * @param  [type] $url [description]
	 * @return [type]      [description]
	 */
	static function url($url){

	
		// return BASE_URL.DS.'controller'.DS.$url.'.php';
		return BASE_URL.DS.$url;


	}
}

 ?>