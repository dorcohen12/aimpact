<?php
	require 'config.php';
	date_default_timezone_set("Asia/Jerusalem");
	switch(SHOW_ERRORS){
		case 0:
			ini_set('display_errors', 0);
			ini_set('display_startup_errors', 0);
			error_reporting(0);
			break;
		case 1:
			ini_set('display_errors', 1);
			error_reporting(E_ALL);
			break;
	}
	
	require 'application/functions.php';
	spl_autoload_register(function($class){
        $path = 'application/classes/';
        $path .= str_replace('\\', '/', $class).'.php';
		require $path;	
    });