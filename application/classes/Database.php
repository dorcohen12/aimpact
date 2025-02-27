<?php
	defined('INSITE') or die("No direct script access allowed");
	class Database{
		public function __construct(){
			$options = [
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			];
			try{
				switch(SQL_PDO){
					case 'mysqli':
						$this->db = new PDO("mysql:host=".SQL_HOST.";dbname=".SQL_WEB_DB, SQL_USER, SQL_PASSWORD, $options);
						break;
					default:
						die('Incorrect PDO driver!');
				}
			}
			catch(PDOException $e){
				die(print_r($e->getMessage()));
			}
		}
	}
