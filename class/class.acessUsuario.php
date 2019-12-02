<?php
	class acessUsuario extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new acessUsuario();
	        return self::$instance;
	    }

		public function loginSystem($user, $pwd){
			try {
	            $sql = "SELECT * 
	                FROM acess_user 
	                WHERE senha LIKE :senha
	                AND usuario LIKE :usuario

	                ";
	            $pdo = Conexao::getInstance()->prepare($sql);
	            $pdo->bindValue(":senha", $pwd, PDO::PARAM_STR);
	            $pdo->bindValue(":usuario", $user, PDO::PARAM_STR);
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}
	}