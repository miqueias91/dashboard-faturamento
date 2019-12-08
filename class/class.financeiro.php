<?php
	class Financeiro extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new Financeiro();
	        return self::$instance;
	    }





	}