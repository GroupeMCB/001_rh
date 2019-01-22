<?php
/**
 * Conf Class de configuration des parametres à la base de données
 */
class Conf
{
		static $debug = 1;
		static $databases = array(
			'default' => array(
					'host'        =>'localhost',
					'login'        =>'root',
					'password'    =>'toor',
					'database'    =>'MCB_RH'
				),
				'planning' => array(
					'host'        =>'localhost',
					'login'        =>'root',
					'password'    =>'toor',
					'database'    =>'planningDB'
				 )
	);

}

Router::prefix('dsi','admin');
