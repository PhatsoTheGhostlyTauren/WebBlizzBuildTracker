<?php
    /*** nullify any existing autoloads ***/
    spl_autoload_register(null, false);

    /*** specify extensions that may be loaded ***/
    spl_autoload_extensions('.php, .class.php');

	/*** class Loader ***/
	function classLoader($class){
		try {
			require_once $class . '.php';
		} catch (Exception $e) {
			echo "Error loading Class '".$class."' : ".$e->getMessage()."\n";
		}
	}
	/*** register the loader functions ***/
    spl_autoload_register('classLoader');

?>