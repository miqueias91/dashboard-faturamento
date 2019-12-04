<?php
	class Cliente extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new Cliente();
	        return self::$instance;
	    }

	    public function buscaCliente($id_cliente = null, $nome_cliente = null, $cpf_cliente = null){ 
			$filtro = "";
			$filtro .= isset($id_cliente) ? " AND cli.id_cliente = :id_cliente" : "";
			$filtro .= isset($nome_cliente) ? " AND cli.nome_cliente LIKE :nome_cliente" : "";			
			$filtro .= isset($cpf_cliente) ? " AND cli.cpf_cliente LIKE :cpf_cliente" : "";			

			try {
	            $sql = "SELECT cli.*, 
	            				rep.id_cliente id_rep_fin,
	            				rep.cpf_cliente cpf_rep_fin,
	            				rep.nome_cliente nome_rep_fin

	                FROM cliente cli
	                LEFT JOIN cliente rep ON rep.id_cliente = cli.id_representante_financeiro_cliente
	                WHERE cli.id_cliente > :idcliente
	                $filtro
	                group by cli.id_cliente
					ORDER BY cli.nome_cliente
	            ";

	            $pdo = Conexao::getInstance()->prepare($sql);
				
		        $pdo->bindValue(':idcliente', 0, PDO::PARAM_INT);
	            if ($id_cliente) {
		            $pdo->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
	            }
	           	if ($nome_cliente) {
		            $pdo->bindValue(':nome_cliente', $nome_cliente, PDO::PARAM_STR);
	            }
	            if ($cpf_cliente) {
		            $pdo->bindValue(':cpf_cliente', $cpf_cliente, PDO::PARAM_STR);
	            }
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

		public function pesquisaCliente($pesquisa = null){ 
			$filtro = "";
			$filtro .= isset($pesquisa) ? " AND ( nome_cliente LIKE :pesquisa OR  cpf_cliente LIKE :pesquisa )" : "";			

			try {
	            $sql = "SELECT

	            	id_cliente,
	            	cpf_cliente,
	            	nome_cliente

	                FROM cliente
	                WHERE id_cliente > :id_cliente AND status_cliente = :status_cliente
	                $filtro
	                group by id_cliente
					ORDER BY id_cliente
	            ";

	            $pdo = Conexao::getInstance()->prepare($sql);
				
		        $pdo->bindValue(':id_cliente', 0, PDO::PARAM_INT);
		       	$pdo->bindValue(':status_cliente', 'ativo', PDO::PARAM_STR);

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


	    public function alterarResponsavelFinanceiroCliente($dados){

			try {
	            $sql = "UPDATE cliente 
	            	SET
	                	id_representante_financeiro_cliente		= :id_representante_financeiro_cliente
	                WHERE id_cliente 	= :id_cliente";

	            $pdo = Conexao::getInstance()->prepare($sql);
	            $pdo->bindValue(':id_cliente', $dados['id_cliente'], PDO::PARAM_INT);
	            $pdo->bindValue(':id_representante_financeiro_cliente', $dados['id_representante_financeiro_cliente'], PDO::PARAM_INT);
	            $pdo->execute();
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

		public function cadastraCliente($dados){
			try {
	            $sql = "INSERT INTO cliente (
					id_cliente,
					nome_cliente,
					email_cliente,
					celular_cliente,
					logradouro_cliente,
					data_nascimento_cliente,
					sexo_cliente,
					cpf_cliente,
					profissao_cliente,
					numero_cliente,
					complemento_cliente,
					bairro_cliente,
					cidade_cliente,
					estado_cliente,
					cep_cliente

					)
					VALUES (
					:id_cliente,
					:nome_cliente,
					:email_cliente,
					:celular_cliente,
					:logradouro_cliente,
					:data_nascimento_cliente,
					:sexo_cliente,
					:cpf_cliente,
					:profissao_cliente,
					:numero_cliente,
					:complemento_cliente,
					:bairro_cliente,
					:cidade_cliente,
					:estado_cliente,
					:cep_cliente
					)

	                ";

	            $pdo = Conexao::getInstance()->prepare($sql);
	            $pdo->bindValue(":id_cliente", null, PDO::PARAM_INT);
	            $pdo->bindValue(":nome_cliente", ucwords(strtolower($dados['nome_cliente'])), PDO::PARAM_STR);
	            $pdo->bindValue(":email_cliente", $dados['email_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(":celular_cliente", $dados['celular_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(":logradouro_cliente", ucwords(strtolower($dados['logradouro_cliente'])), PDO::PARAM_STR);
	            $pdo->bindValue(":data_nascimento_cliente", $dados['data_nascimento_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(":sexo_cliente", $dados['sexo_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(":cpf_cliente", $dados['cpf_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(":profissao_cliente", ucwords(strtolower($dados['profissao_cliente'])), PDO::PARAM_STR);
	            $pdo->bindValue(':numero_cliente', $dados['numero_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':complemento_cliente', $dados['complemento_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':bairro_cliente', ucwords(strtolower($dados['bairro_cliente'])), PDO::PARAM_STR);
	            $pdo->bindValue(':cidade_cliente', ucwords(strtolower($dados['cidade_cliente'])), PDO::PARAM_STR);
	            $pdo->bindValue(':estado_cliente', $dados['estado_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':cep_cliente', $dados['cep_cliente'], PDO::PARAM_STR);
	            $pdo->execute();

	            return Conexao::ultimoID();
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

	    public function alteraCliente($dados){

			try {
	            $sql = "UPDATE cliente 
	            	SET
	                nome_cliente		= :nome_cliente,
	                email_cliente		= :email_cliente,
	                celular_cliente		= :celular_cliente,
	                logradouro_cliente		= :logradouro_cliente,
	                data_nascimento_cliente		= :data_nascimento_cliente,
	                sexo_cliente		= :sexo_cliente,
	                cpf_cliente		= :cpf_cliente,
	                profissao_cliente		= :profissao_cliente,
	                numero_cliente = :numero_cliente,
					complemento_cliente = :complemento_cliente,
					bairro_cliente = :bairro_cliente,
					cidade_cliente = :cidade_cliente,
					estado_cliente = :estado_cliente,
					status_cliente = :status_cliente,
					cep_cliente = :cep_cliente
	                


	                WHERE id_cliente 	= :id_cliente";

	            $pdo = Conexao::getInstance()->prepare($sql);
	            $pdo->bindValue(':id_cliente', $dados['id_cliente'], PDO::PARAM_INT);
	            $pdo->bindValue(':nome_cliente', ucwords(strtolower($dados['nome_cliente'])), PDO::PARAM_STR);
	            $pdo->bindValue(':email_cliente', $dados['email_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':celular_cliente', $dados['celular_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':logradouro_cliente', ucwords(strtolower($dados['logradouro_cliente'])), PDO::PARAM_STR);
	            $pdo->bindValue(':data_nascimento_cliente', $dados['data_nascimento_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':sexo_cliente', $dados['sexo_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':cpf_cliente', $dados['cpf_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':profissao_cliente', $dados['profissao_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':numero_cliente', $dados['numero_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':complemento_cliente', $dados['complemento_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':bairro_cliente', ucwords(strtolower($dados['bairro_cliente'])), PDO::PARAM_STR);
	            $pdo->bindValue(':cidade_cliente', ucwords(strtolower($dados['cidade_cliente'])), PDO::PARAM_STR);
	            $pdo->bindValue(':estado_cliente', $dados['estado_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':status_cliente', $dados['status_cliente'], PDO::PARAM_STR);
	            $pdo->bindValue(':cep_cliente', $dados['cep_cliente'], PDO::PARAM_STR);
	            $pdo->execute();
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
		}

		/*public function excluirCliente($token_user, $id_cliente) {
	        try {
		            $sql = "DELETE 
		            		FROM cliente 
		            		WHERE token_user = :token_user
		            		AND id_cliente = :id_cliente";
		            $pdo = Conexao::getInstance()->prepare($sql);
			        $pdo->bindValue(':token_user', $token_user, PDO::PARAM_INT);
			        $pdo->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
			        $pdo->execute();
			       	echo "<script>alert('cliente excluido com sucesso.'); window.location.href = './form_config_cliente.php';</script>";

	        } 
	        catch (Exception $e) {
	        	echo "<script>alert('Não foi possível excluir o cliente.'); window.location.href = './form_config_cliente.php';</script>";

	            echo "<br>".$e->getMessage();
	        }
	    }*/


	}