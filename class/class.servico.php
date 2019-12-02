<?php
	class Servico extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new Servico();
	        return self::$instance;
	    }

		public function cadastraServico($dados){
			try {
	            $sql = "INSERT INTO servico (
	                id_servico, 
					descricao_servico,
					status_servico
					)
					VALUES (
	                :id_servico, 
					:descricao_servico,
					:status_servico
					)

	                ";
	            $pdo = Conexao::getInstance()->prepare($sql);
	            $pdo->bindValue(":id_servico", null, PDO::PARAM_INT);
	            $pdo->bindValue(":descricao_servico", $dados['descricao_servico'], PDO::PARAM_STR);
	            $pdo->bindValue(":status_servico", $dados['status_servico'], PDO::PARAM_STR);
	            $pdo->execute();
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

		public function buscaServico($id_servico = null, $descricao_servico = null, $status_servico = null){
			$filtro = "";
			$filtro .= isset($id_servico) ? " AND id_servico = :id_servico" : "";
			$filtro .= isset($descricao_servico) ? " AND descricao_servico = :descricao_servico" : "";
			$filtro .= isset($status_servico) ? " AND status_servico = :status_servico" : "";			

			try {
	            $sql = "SELECT *

	                FROM servico	                             
	                WHERE id_servico > :idservico
	                $filtro
	                group by id_servico
					ORDER BY id_servico, status_servico, descricao_servico
	            ";

	            $pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':idservico', 0, PDO::PARAM_INT);
	            
	            if ($id_servico) {
		            $pdo->bindValue(':id_servico', $id_servico, PDO::PARAM_INT);
	            }     
	            if ($descricao_servico) {
		            $pdo->bindValue(':descricao_servico', $descricao_servico, PDO::PARAM_STR);
	            }
	           	if ($status_servico) {
		            $pdo->bindValue(':status_servico', $status_servico, PDO::PARAM_STR);
	            }
	         
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

		public function alteraServico($dados){
			try {
	            $sql = "UPDATE servico
	            	SET
						descricao_servico 	= :descricao_servico,
						status_servico 	= :status_servico

					WHERE id_servico = :id_servico
	                ";
	            $pdo = Conexao::getInstance()->prepare($sql);
	            $pdo->bindValue(":id_servico", $dados['id_servico'], PDO::PARAM_INT);
	            $pdo->bindValue(":descricao_servico", $dados['descricao_servico'], PDO::PARAM_STR);
	            $pdo->bindValue(":status_servico", $dados['status_servico'], PDO::PARAM_STR);
	            $pdo->execute();
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}


















	}