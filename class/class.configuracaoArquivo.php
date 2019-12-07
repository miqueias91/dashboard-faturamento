<?php
	class ConfiguracaoArquivo extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new ConfiguracaoArquivo();
	        return self::$instance;
	    }


		public function buscaConfiguracaoArquivo($id_configarquivobancario = null, $status_configarquivobancario = 'ativo'){

			$filtro = "";
			$filtro .= isset($id_configarquivobancario) ? " AND id_configarquivobancario = :id_configarquivobancario" : "";
			$filtro .= isset($status_configarquivobancario) ? " AND status_configarquivobancario = :status " : "";
			

			try {
	            $sql = "SELECT *

	                FROM configarquivobancario
	                WHERE id_configarquivobancario > :idconfigarquivobancario
	                $filtro
	                group by id_configarquivobancario
					ORDER BY id_configarquivobancario
	            ";

	            $pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':idconfigarquivobancario', 0, PDO::PARAM_INT);
	            if ($id_configarquivobancario) {
		            $pdo->bindValue(':id_configarquivobancario', $id_configarquivobancario, PDO::PARAM_INT);
	            }
	            if ($status_configarquivobancario) {
			        $pdo->bindValue(':status', $status_configarquivobancario, PDO::PARAM_STR);
	            }
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

}