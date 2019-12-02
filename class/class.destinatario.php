<?php
	class Destinatario extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new Destinatario();
	        return self::$instance;
	    }

		public function excluirGrupoDestinatario($id_destinatario) {
	        try {
		            $sql = "DELETE 
		            		FROM destinatario_grupo 
		            		WHERE id_destinatario = :id_destinatario";
		            $pdo = Conexao::getInstance()->prepare($sql);
			        $pdo->bindValue(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
			        $pdo->execute();
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
	    }

		public function cadastraGrupoDestinatario($id_destinatario, $grupo){
			try {
	            $sql = "INSERT INTO destinatario_grupo (
	                id_destinatario_grupo, 
	                id_destinatario,
					grupo
					)
					VALUES (
	                :id_destinatario_grupo, 
	                :id_destinatario,
					:grupo
					)

	                ";
	            $pdo = Conexao::getInstance()->prepare($sql);
	            $pdo->bindValue(":id_destinatario_grupo", null, PDO::PARAM_INT);
	            $pdo->bindValue(":id_destinatario", $id_destinatario, PDO::PARAM_STR);
	            $pdo->bindValue(":grupo", $grupo, PDO::PARAM_STR);
	            $pdo->execute();
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

		public function buscaGrupoDestinatario($idgrupodestinatario = null, $id_destinatario = null, $grupo = null, $token_user = null, $pesquisa = null){
			    
			$filtro = "";
			$filtro .= isset($idgrupodestinatario) ? " AND gdo.id_destinatario_grupo = :idgrupodestinatario" : "";
			$filtro .= isset($id_destinatario) ? " AND gdo.id_destinatario = :id_destinatario" : "";
			$filtro .= isset($grupo) ? " AND gdo.grupo LIKE :grupo" : "";
			$filtro .= isset($token_user) ? " AND des.token_user LIKE :token_user" : "";			
			$filtro .= isset($pesquisa) ? " AND gpo.nome_grupo LIKE :pesquisa " : "";
			

			try {
	            $sql = "SELECT *

	                FROM destinatario_grupo	gdo                             
	                INNER JOIN grupo gpo ON gpo.id_grupo = gdo.grupo
	                INNER JOIN destinatario des ON des.id_destinatario = gdo.id_destinatario
	                WHERE gdo.id_destinatario_grupo > :id_destinatario_grupo
	                $filtro
	                group by gdo.id_destinatario_grupo
					ORDER BY gdo.id_destinatario_grupo
	            ";


	            $pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':id_destinatario_grupo', 0, PDO::PARAM_INT);
	            if ($idgrupodestinatario) {
		            $pdo->bindValue(':idgrupodestinatario', $idgrupodestinatario, PDO::PARAM_INT);
	            }     
	            if ($id_destinatario) {
		            $pdo->bindValue(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
	            }
	           	if ($grupo) {
		            $pdo->bindValue(':grupo', $grupo, PDO::PARAM_INT);
	            }
	            if ($token_user) {
		            $pdo->bindValue(':token_user', $token_user, PDO::PARAM_STR);
	            }
	            if ($pesquisa) {
			        $pdo->bindValue(':pesquisa', "%".$pesquisa."%", PDO::PARAM_STR);
	            }
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

		public function cadastraDestinatario($nome_destinatario, $email_destinatario, $destinatario_grupo, $token_user, $telefone){
			try {
	            $sql = "INSERT INTO destinatario (
	                id_destinatario, 
	                nome_destinatario,
					email_destinatario,
					token_user,
					telefone
					)
					VALUES (
	                :id_destinatario, 
	                :nome_destinatario,
					:email_destinatario,
					:token_user,
					:telefone
					)

	                ";
	            $pdo = Conexao::getInstance()->prepare($sql);
	            $pdo->bindValue(":id_destinatario", null, PDO::PARAM_INT);
	            $pdo->bindValue(":nome_destinatario", $nome_destinatario, PDO::PARAM_STR);
	            $pdo->bindValue(":email_destinatario", $email_destinatario, PDO::PARAM_STR);
	            $pdo->bindValue(":token_user", $token_user, PDO::PARAM_STR);
	            $pdo->bindValue(":telefone", $telefone, PDO::PARAM_STR);
	            $pdo->execute();

	            $ultimo_id = Conexao::ultimoID();
				foreach ($destinatario_grupo as $row) {
				    $this->cadastraGrupoDestinatario($ultimo_id, $row);
				}
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

		public function buscaDestinatario($iddestinatario = null, $nome_destinatario = null, $email_destinatario = null, $destinatario_grupo = null, $token_user, $pesquisa = null, $limit = false, $inicio = null, $final = null){
			$filtro = "";
			$filtro .= isset($iddestinatario) ? " AND id_destinatario = :iddestinatario" : "";
			$filtro .= isset($nome_destinatario) ? " AND nome_destinatario LIKE :nome_destinatario" : "";
			$filtro .= isset($email_destinatario) ? " AND email_destinatario LIKE :email_destinatario" : "";
			$filtro .= isset($destinatario_grupo) ? " AND destinatario_grupo LIKE :destinatario_grupo" : "";
			$filtro .= isset($token_user) ? " AND token_user LIKE :token_user" : "";			
			$filtro .= isset($pesquisa) ? " AND ( nome_destinatario LIKE :pesquisa OR email_destinatario LIKE :pesquisa )" : "";	

			$limit = $limit	? " limit $inicio, $final" : "";	

			try {
	            $sql = "SELECT *

	                FROM destinatario	                             
	                WHERE id_destinatario > :id_destinatario
	                $filtro
	                group by id_destinatario
					ORDER BY id_destinatario
					$limit
	            ";
	            $pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':id_destinatario', 0, PDO::PARAM_INT);
	            if ($iddestinatario) {
		            $pdo->bindValue(':iddestinatario', $iddestinatario, PDO::PARAM_INT);
	            }     
	            if ($nome_destinatario) {
		            $pdo->bindValue(':nome_destinatario', $nome_destinatario, PDO::PARAM_STR);
	            }
	           	if ($email_destinatario) {
		            $pdo->bindValue(':email_destinatario', $email_destinatario, PDO::PARAM_STR);
	            }
	           	if ($destinatario_grupo) {
		            $pdo->bindValue(':destinatario_grupo', $destinatario_grupo, PDO::PARAM_STR);
	            }
	           	if ($token_user) {
		            $pdo->bindValue(':token_user', $token_user, PDO::PARAM_STR);
	            }
	            if ($pesquisa) {
			        $pdo->bindValue(':pesquisa', "%".$pesquisa."%", PDO::PARAM_STR);
	            }
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

		public function excluirDestinatario($token_user, $id_destinatario) {
	        try {
		            $sql = "DELETE 
		            		FROM destinatario 
		            		WHERE token_user = :token_user
		            		AND id_destinatario = :id_destinatario";
		            $pdo = Conexao::getInstance()->prepare($sql);
			        $pdo->bindValue(':token_user', $token_user, PDO::PARAM_INT);
			        $pdo->bindValue(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
			        $pdo->execute();
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
	    }

	    public function alterarDestinatario($id_destinatario, $nome_destinatario, $email_destinatario, $grupo, $token_user, $telefone){

			try {
	            $sql = "UPDATE destinatario 
	            	SET
	                nome_destinatario		= :nome_destinatario,
	                email_destinatario 		= :email_destinatario,
	                telefone 				= :telefone
	                WHERE id_destinatario 	= :id_destinatario
	                AND token_user 			= :token_user";

	            $pdo = Conexao::getInstance()->prepare($sql);
	            $pdo->bindValue(':nome_destinatario', $nome_destinatario, PDO::PARAM_STR);
	            $pdo->bindValue(':email_destinatario', $email_destinatario, PDO::PARAM_STR);
	            $pdo->bindValue(':telefone', $telefone, PDO::PARAM_STR);
	            $pdo->bindValue(':id_destinatario', $id_destinatario, PDO::PARAM_INT);
	            $pdo->bindValue(':token_user', $token_user, PDO::PARAM_INT);
	            $pdo->execute();

	            $this->excluirGrupoDestinatario($id_destinatario);

	            foreach ($grupo as $row) {
				    $this->cadastraGrupoDestinatario($id_destinatario, $row);
				}

	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

}