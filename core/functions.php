<?php 
function debug($value)
{
	if(Conf::$debug > 0) {
		$debug = debug_backtrace();
		echo "<p><a href =#><strong>".$debug[0]['file']."</strong> à la ligne ".$debug[0]['line']." </a></p>";
		echo '<pre>';
		var_dump($value);
		echo '</pre>';
	}
}