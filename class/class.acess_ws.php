<?php
class acessWS extends Conexao {
	public static $instance;

	public function __construct(){}

	public static function getInstance() {
        self::$instance = new acessWS();
        return self::$instance;
    }


	public function loginSystemWs($usuario, $token_user){
		try {
            $sql = "SELECT *

                FROM acess_user	                             
                WHERE token_user = :token_user AND usuario = :usuario
            ";
            $pdo = Conexao::getInstance()->prepare($sql);
            $pdo->bindValue(':token_user', $token_user, PDO::PARAM_STR);
            $pdo->bindValue(':usuario', $usuario, PDO::PARAM_STR);
			
            $pdo->execute();
            return $pdo->fetchAll(PDO::FETCH_BOTH);
        } 
        catch (Exception $e) {
            echo "<br>".$e->getMessage();
        }
	}
}