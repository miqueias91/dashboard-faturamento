<?php
	class Contrato extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new Contrato();
	        return self::$instance;
	    }

		// public function excluirGrupoContrato($id_contrato) {
	 //        try {
		//             $sql = "DELETE 
		//             		FROM contrato 
		//             		WHERE id_contrato = :id_contrato";
		//             $pdo = Conexao::getInstance()->prepare($sql);
		// 	        $pdo->bindValue(':id_contrato', $id_contrato, PDO::PARAM_INT);
		// 	        $pdo->execute();
	 //        } 
	 //        catch (Exception $e) {
	 //            echo "<br>".$e->getMessage();
	 //        }
	 //    }

		// public function cadastraGrupoContrato($id_contrato, $grupo){
		// 	try {
	 //            $sql = "INSERT INTO contrato (
	 //                id_contrato, 
	 //                id_contrato,
		// 			grupo
		// 			)
		// 			VALUES (
	 //                :id_contrato, 
	 //                :id_contrato,
		// 			:grupo
		// 			)

	 //                ";
	 //            $pdo = Conexao::getInstance()->prepare($sql);
	 //            $pdo->bindValue(":id_contrato", null, PDO::PARAM_INT);
	 //            $pdo->bindValue(":id_contrato", $id_contrato, PDO::PARAM_STR);
	 //            $pdo->bindValue(":grupo", $grupo, PDO::PARAM_STR);
	 //            $pdo->execute();
	 //        } 
	 //        catch (Exception $e) {
	 //            echo "<br>".$e->getMessage();
	 //        }
		// }

		public function buscaContrato($id_contrato = null, $pesquisa = null){
			    
			$filtro = "";
			$filtro .= isset($id_contrato) ? " AND cont.id_contrato = :id_contrato" : "";
			$filtro .= isset($pesquisa) ? " AND cli.nome_cliente LIKE :pesquisa " : "";
			

			try {
	            $sql = "SELECT *

	                FROM contrato cont
	                INNER JOIN cliente cli ON cli.id_cliente = cont.idcliente
	                WHERE cont.id_contrato > :idcontrato
	                $filtro
	                group by cont.id_contrato
					ORDER BY cont.id_contrato
	            ";


	            $pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':idcontrato', 0, PDO::PARAM_INT);
	            if ($id_contrato) {
		            $pdo->bindValue(':id_contrato', $id_contrato, PDO::PARAM_INT);
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

		// public function cadastraContrato($nome_contrato, $email_contrato, $contrato, $token_user, $telefone){
		// 	try {
	 //            $sql = "INSERT INTO contrato (
	 //                id_contrato, 
	 //                nome_contrato,
		// 			email_contrato,
		// 			token_user,
		// 			telefone
		// 			)
		// 			VALUES (
	 //                :id_contrato, 
	 //                :nome_contrato,
		// 			:email_contrato,
		// 			:token_user,
		// 			:telefone
		// 			)

	 //                ";
	 //            $pdo = Conexao::getInstance()->prepare($sql);
	 //            $pdo->bindValue(":id_contrato", null, PDO::PARAM_INT);
	 //            $pdo->bindValue(":nome_contrato", $nome_contrato, PDO::PARAM_STR);
	 //            $pdo->bindValue(":email_contrato", $email_contrato, PDO::PARAM_STR);
	 //            $pdo->bindValue(":token_user", $token_user, PDO::PARAM_STR);
	 //            $pdo->bindValue(":telefone", $telefone, PDO::PARAM_STR);
	 //            $pdo->execute();

	 //            $ultimo_id = Conexao::ultimoID();
		// 		foreach ($contrato as $row) {
		// 		    $this->cadastraGrupoContrato($ultimo_id, $row);
		// 		}
	 //        } 
	 //        catch (Exception $e) {
	 //            echo "<br>".$e->getMessage();
	 //        }
		// }

		// public function excluirContrato($token_user, $id_contrato) {
	 //        try {
		//             $sql = "DELETE 
		//             		FROM contrato 
		//             		WHERE token_user = :token_user
		//             		AND id_contrato = :id_contrato";
		//             $pdo = Conexao::getInstance()->prepare($sql);
		// 	        $pdo->bindValue(':token_user', $token_user, PDO::PARAM_INT);
		// 	        $pdo->bindValue(':id_contrato', $id_contrato, PDO::PARAM_INT);
		// 	        $pdo->execute();
	 //        } 
	 //        catch (Exception $e) {
	 //            echo "<br>".$e->getMessage();
	 //        }
	 //    }

	 //    public function alterarContrato($id_contrato, $nome_contrato, $email_contrato, $grupo, $token_user, $telefone){

		// 	try {
	 //            $sql = "UPDATE contrato 
	 //            	SET
	 //                nome_contrato		= :nome_contrato,
	 //                email_contrato 		= :email_contrato,
	 //                telefone 				= :telefone
	 //                WHERE id_contrato 	= :id_contrato
	 //                AND token_user 			= :token_user";

	 //            $pdo = Conexao::getInstance()->prepare($sql);
	 //            $pdo->bindValue(':nome_contrato', $nome_contrato, PDO::PARAM_STR);
	 //            $pdo->bindValue(':email_contrato', $email_contrato, PDO::PARAM_STR);
	 //            $pdo->bindValue(':telefone', $telefone, PDO::PARAM_STR);
	 //            $pdo->bindValue(':id_contrato', $id_contrato, PDO::PARAM_INT);
	 //            $pdo->bindValue(':token_user', $token_user, PDO::PARAM_INT);
	 //            $pdo->execute();

	 //            $this->excluirGrupoContrato($id_contrato);

	 //            foreach ($grupo as $row) {
		// 		    $this->cadastraGrupoContrato($id_contrato, $row);
		// 		}

	 //        } 
	 //        catch (Exception $e) {
	 //            echo "<br>".$e->getMessage();
	 //        }
		// }

		public function buscaDescontoTipo($id_descontotipo = null){			    
			$filtro = "";
			$filtro .= isset($id_descontotipo) ? " AND dt.id_descontotipo = :id_descontotipo" : "";
			

			try {
	            $sql = "SELECT *

	                FROM descontotipo dt
	                INNER JOIN descontoclassificacao dc ON dc.id_descontoclassificacao = dt.iddescontoclassificacao
	                WHERE dt.id_descontotipo > :iddescontotipo
	                $filtro
	                group by dt.id_descontotipo
					ORDER BY dt.id_descontotipo
	            ";


	            $pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':iddescontotipo', 0, PDO::PARAM_INT);
	            if ($id_descontotipo) {
		            $pdo->bindValue(':id_descontotipo', $id_descontotipo, PDO::PARAM_INT);
	            }
	        
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

}