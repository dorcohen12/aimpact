<?php
	/*== MSSQL SETTINGS ===*/
	define('SQL_HOST', '127.0.0.1');
	define('SQL_PDO', 'mysqli');
	define('SQL_WEB_DB', 'aimpact');
	define('SQL_USER', 'root');
	define('SQL_PASSWORD', '123123');

    /*== WEBSITE SETTINGS ===*/
	define('SHOW_ERRORS', true);
	define('WEB_PATH', 'aimpact');	//Leave empty if website not in subdirectory
	define('DS', DIRECTORY_SEPARATOR);
	define('BASE_DIR', __DIR__.DS);
	define('TEMPLATE_NAME', 'default');
	define('TEMPLATE_DIR', BASE_DIR.'assets/'.TEMPLATE_NAME.'/');

	/*== DO NOT TOUCH ===*/
	if(!defined('INSITE')){
		define('INSITE', true);
	}