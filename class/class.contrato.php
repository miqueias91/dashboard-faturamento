<?php
	class Contrato extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new Contrato();
	        return self::$instance;
	    }

		 public function excluiDescontoContrato($idcontrato) {
	         try {
		             $sql = "DELETE 
		             		FROM contrato_desconto 
		             		WHERE idcontrato = :idcontrato";
		             $pdo = Conexao::getInstance()->prepare($sql);
		 	        $pdo->bindValue(':idcontrato', $idcontrato, PDO::PARAM_INT);
		 	        $pdo->execute();
	         } 
	         catch (Exception $e) {
	             echo "<br>".$e->getMessage();
	         }
	     }

		 public function cadastraDescontoContrato($idcontrato, $idtipodesconto, $valordesconto, $compdesconto){

		 	try {
	            $sql = "
					INSERT INTO `contrato_desconto`(
					`id_contratodesconto`,
					`idcontrato`,
					`idtipodesconto`,
					`valordesconto`,
					`compdesconto`) 
					VALUES (
					:id_contratodesconto,
					:idcontrato,
					:idtipodesconto,
					:valordesconto,
					:compdesconto)";

	             $pdo = Conexao::getInstance()->prepare($sql);
	             $pdo->bindValue(":id_contratodesconto", null, PDO::PARAM_INT);
	             $pdo->bindValue(":idcontrato", $idcontrato, PDO::PARAM_INT);
	             $pdo->bindValue(":idtipodesconto", $idtipodesconto, PDO::PARAM_INT);
	             $pdo->bindValue(":valordesconto", number_format($valordesconto,2), PDO::PARAM_STR);
	             $pdo->bindValue(":compdesconto", $compdesconto, PDO::PARAM_STR);
	             $pdo->execute();
	         } 
	         catch (Exception $e) {
	             echo "<br>".$e->getMessage();
	         }
		 }

		public function buscaContrato($id_contrato = null, $pesquisa = null){
			    
			$filtro = "";
			$filtro .= isset($id_contrato) ? " AND cont.id_contrato = :id_contrato" : "";
			$filtro .= isset($pesquisa) ? " AND cli.nome_cliente LIKE :pesquisa " : "";
			

			try {
	            $sql = "SELECT *

	                FROM contrato cont
	                INNER JOIN cliente cli ON cli.id_cliente = cont.idcliente
	                INNER JOIN servico ser ON ser.id_servico = cont.idservico
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

		 public function cadastraContrato($dados){
		 	try {
	            $sql = "
	            INSERT INTO `contrato`(
					`id_contrato`,
					`tipomovimento`,
					`idcliente`,
					`datacadastro`,
					`datainicio`,
					`datafinal`,
					`compinicial`,
					`numparcela`,
					`valorparcela`,
					`diavencimento`,
					`observacao`,
					`nomeanexo`,
					`enderecoanexo`,
					`status_contrato`,
					`contrato_antigo`,
					`tipo_contrato`,
					`tipopagamento`,
					`idconfigarquivobancario`,
					`diavencimentodesconto`,
					`idservico`
					) 
					VALUES (
						:id_contrato,
						:tipomovimento,
						:idcliente,
						:datacadastro,
						:datainicio,
						:datafinal,
						:compinicial,
						:numparcela,
						:valorparcela,
						:diavencimento,
						:observacao,
						:nomeanexo,
						:enderecoanexo,
						:status_contrato,
						:contrato_antigo,
						:tipo_contrato,
						:tipopagamento,
						:idconfigarquivobancario,
						:diavencimentodesconto,
						:idservico
					)";

	             	$pdo = Conexao::getInstance()->prepare($sql);
	             	$pdo->bindValue(":id_contrato", NULL, PDO::PARAM_INT);
					$pdo->bindValue(":tipomovimento", $dados['tipomovimento'], PDO::PARAM_STR);
					$pdo->bindValue(":idcliente", $dados['idcliente'], PDO::PARAM_INT);
					$pdo->bindValue(":datacadastro", $dados['datacadastro'], PDO::PARAM_STR);
					$pdo->bindValue(":datainicio", $dados['datainicio'], PDO::PARAM_STR);
					$pdo->bindValue(":datafinal", $dados['datafinal'], PDO::PARAM_STR);
					$pdo->bindValue(":compinicial", $dados['compinicial'], PDO::PARAM_STR);
					$pdo->bindValue(":numparcela", $dados['numparcela'], PDO::PARAM_INT);
					$pdo->bindValue(":valorparcela", $dados['valorparcela'], PDO::PARAM_STR);
					$pdo->bindValue(":diavencimento", $dados['diavencimento'], PDO::PARAM_INT);
					$pdo->bindValue(":observacao", $dados['observacao'], PDO::PARAM_STR);
					$pdo->bindValue(":nomeanexo", $dados['nomeanexo'], PDO::PARAM_STR);
					$pdo->bindValue(":enderecoanexo", $dados['enderecoanexo'], PDO::PARAM_STR);
					$pdo->bindValue(":status_contrato", $dados['status_contrato'], PDO::PARAM_STR);
					$pdo->bindValue(":contrato_antigo", $dados['contrato_antigo'], PDO::PARAM_STR);
					$pdo->bindValue(":tipo_contrato", $dados['tipo_contrato'], PDO::PARAM_STR);
					$pdo->bindValue(":tipopagamento", $dados['tipopagamento'], PDO::PARAM_STR);
					$pdo->bindValue(":idconfigarquivobancario", $dados['idconfigarquivobancario'], PDO::PARAM_INT);
					$pdo->bindValue(":diavencimentodesconto", $dados['diavencimentodesconto'], PDO::PARAM_INT);
					$pdo->bindValue(":idservico", $dados['idservico'], PDO::PARAM_INT);
	             $pdo->execute();

	             return $ultimo_id = Conexao::ultimoID();
		 		
	         } 
	         catch (Exception $e) {
	             echo "<br>".$e->getMessage();
	         }
		 }

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

	 //            $this->excluiDescontoContrato($id_contrato);

	 //            foreach ($grupo as $row) {
		// 		    $this->cadastraDescontoContrato($id_contrato, $row);
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

		public function buscaDescontoContrato($idcontrato = null){			    
			$filtro = "";
			$filtro .= isset($idcontrato) ? " AND cd.idcontrato = :idcontrato" : "";
			

			try {
	            $sql = "SELECT *

	                FROM contrato_desconto cd
	                INNER JOIN descontotipo dt ON dt.id_descontotipo = cd.idtipodesconto
	                INNER JOIN descontoclassificacao dc ON dc.id_descontoclassificacao = dt.iddescontoclassificacao
	                WHERE cd.id_contratodesconto > :idcontratodesconto
	                $filtro
	                GROUP BY cd.id_contratodesconto
					ORDER BY SUBSTR(cd.compdesconto,4,7),SUBSTR(cd.compdesconto,1,2) ASC
	            ";


	            $pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':idcontratodesconto', 0, PDO::PARAM_INT);
	            if ($idcontrato) {
		            $pdo->bindValue(':idcontrato', $idcontrato, PDO::PARAM_INT);
	            }
	        
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

		public function buscaMovimentoContrato($idcontrato = null){			    
			$filtro = "";
			$filtro .= isset($idcontrato) ? " AND cm.idcontrato = :idcontrato" : "";
			

			try {
	            $sql = "SELECT *
	                FROM contrato_movimento cm
	                INNER JOIN contrato co ON co.id_contrato = cm.idcontrato
	                INNER JOIN movimento mo ON mo.id_movimento = cm.idmovimento
	                WHERE cm.id_contrato_movimento > :idcontratomovimento
	                $filtro
	                GROUP BY cm.id_contrato_movimento
	            ";


	            $pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':idcontratomovimento', 0, PDO::PARAM_INT);
	            if ($idcontrato) {
		            $pdo->bindValue(':idcontrato', $idcontrato, PDO::PARAM_INT);
	            }
	        
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

}