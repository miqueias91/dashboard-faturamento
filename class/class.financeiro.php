<?php
	class Financeiro extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new Financeiro();
	        return self::$instance;
	    }

	    public function novoMovimento($dados){
	        global $_SESSION;

	        if(empty($dados['valorfies']))
	            $dados['valorfies'] = 0 ;
	        

	        if(empty($dados['valortotal']))
	            $dados['valortotal'] = $dados['valor'];


	        $m = new Mascara();
	        $dados['entrada']   = $m->InMascaraData($dados['entrada']);
	        $dados['emissao']        = $m->InMascaraData($dados['emissao']);
	        $dados['valor']         = str_replace(",",".",$dados['valor']);
	        $dados['valorliquido']  = str_replace(",",".",$dados['valorliquido']);
	        //Insere o numero de movimento na tabela temporaria e em seguida retorna o numero inserido
	        $numeromovimento = $this->insereProximoMovimentoFinanceiro($dados);

	        //SE EXISTIR NOSSO NUMERO, SIGNIFICA QUE É BOLETO. UTILIZO ELE NO DOCUMENTO
	        //SE NÃO EXISTIR, UTILIZO O DOCUMENTO, CASO O USUARIO DIGITE.
	        //SE NÃO EXISTIR, UTILIZO O NUMEROMOVIMENTO QUE É GERADO PELO SISTEMA.

	        if (isset($dados['nosso_numero']) && $dados['nosso_numero']) {
	            if (is_array($dados['nosso_numero'])) {
	                $nosso_numero = $dados['nosso_numero'][1][0];
	            }
	            else{                
	                $nosso_numero = $dados['nosso_numero'];
	            }
	        }
	        else{
	            $nosso_numero = NULL ;
	        }

	        if ($nosso_numero) {
	            $dados['documento'] = $nosso_numero;
	        }
	        else if ($dados['documento']){
	            $dados['documento'] = $dados['documento'];
	        }
	        else{
	            $dados['documento'] = $numeromovimento;            
	        }       

			try {
		        $sql = "
		            INSERT INTO `movimento`(
						`id_movimento`, 
						`tipomovimento`, 
						`numeromovimento`, 
						`documento`, 
						`serie`, 
						`especie`, 
						`idcliente`, 
						`descricaohistorico`, 
						`valortotal`, 
						`emissao`, 
						`entrada`, 
						`comp`, 
						`tokenuser`
					) 
					VALUES (
						:id_movimento, 
						:tipomovimento, 
						:numeromovimento, 
						:documento, 
						:serie, 
						:especie, 
						:idcliente, 
						:descricaohistorico, 
						:valortotal, 
						:emissao, 
						:entrada, 
						:comp, 
						:tokenuser
					)";

					$pdo = Conexao::getInstance()->prepare($sql);
					$pdo->bindValue(":id_movimento", NULL, PDO::PARAM_INT);
					$pdo->bindValue(":tipomovimento", $dados['tipomovimento'], PDO::PARAM_STR);
					$pdo->bindValue(":numeromovimento", $dados['documento'], PDO::PARAM_STR);
					$pdo->bindValue(":documento", $dados['documento'], PDO::PARAM_STR);
					$pdo->bindValue(":serie", $dados['serie'], PDO::PARAM_STR);
					$pdo->bindValue(":especie", $dados['especie'], PDO::PARAM_STR);
					$pdo->bindValue(":idcliente", $dados['idcliente'], PDO::PARAM_STR);
					$pdo->bindValue(":descricaohistorico", $dados['historico'], PDO::PARAM_STR);
					$pdo->bindValue(":valortotal", $dados['valortotal'], PDO::PARAM_STR);
					$pdo->bindValue(":emissao", $dados['emissao'], PDO::PARAM_STR);
					$pdo->bindValue(":entrada", $dados['entrada'], PDO::PARAM_STR);
					$pdo->bindValue(":comp", $dados['comp'], PDO::PARAM_STR);
					$pdo->bindValue(":tokenuser", $dados['tokenuser'], PDO::PARAM_STR);
					$pdo->execute();
					$idmovimento = Conexao::ultimoID();

		        	$this->novoMovimentoParcelamento($idmovimento,$dados);	   

		        return $idmovimento;
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
	    }

		public function insereProximoMovimentoFinanceiro($dados){
			try {
				$sql = "
				INSERT INTO  `movimento_sequencial` (
					`id_movimento_sequencial`,
					`tipomovimento`,
					`tokenuser`           
				)
				VALUES (
					:id_movimento_sequencial, 
					:tipomovimento, 
					:tokenuser
				)";

				$pdo = Conexao::getInstance()->prepare($sql);
				$pdo->bindValue(":id_movimento_sequencial", NULL, PDO::PARAM_INT);
				$pdo->bindValue(":tipomovimento", $dados['tipomovimento'], PDO::PARAM_STR);
				$pdo->bindValue(":tokenuser", $dados['tokenuser'], PDO::PARAM_STR);
				$pdo->execute();

				$ultimo_id = Conexao::ultimoID();		 		
				return str_pad($ultimo_id,7,'0', STR_PAD_LEFT);		 		
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }	    
	    }

		private function novoMovimentoParcelamento($idmovimento,$dados){
	        $m = new Mascara();

	        foreach ($dados['valorparcela'] as $i => $dado){
	            if(!empty($dados['valorparcela'][$i])){
	                if($dados['valorparcela'][$i] != ''){
	                    $tmp = explode("/",$dados['vencimento'][$i]);
	                    $vencimento = $m->InMascaraData($dados['vencimento'][$i]);
	                    $valor = str_replace(",", ".", $dados['valorparcela'][$i]);
	                    $valorbruto = str_replace(",", ".", $dados['valorbrutoparcela'][$i]);
						try {
		                    $sql = "
								INSERT INTO `movimento_parcela`(
									`id_movimento_parcela`, 
									`idmovimento`, 
									`vencimento`, 
									`vencimentooriginal`, 
									`valor`, 
									`valorbruto`
								) VALUES (
									:id_movimento_parcela, 
									:idmovimento, 
									:vencimento, 
									:vencimentooriginal, 
									:valor, 
									:valorbruto
								)";
								$pdo = Conexao::getInstance()->prepare($sql);
								$pdo->bindValue(":id_movimento_parcela", NULL, PDO::PARAM_INT); 
								$pdo->bindValue(":idmovimento", $idmovimento, PDO::PARAM_INT); 
								$pdo->bindValue(":vencimento", $vencimento, PDO::PARAM_STR); 
								$pdo->bindValue(":vencimentooriginal", $vencimento, PDO::PARAM_STR); 
								$pdo->bindValue(":valor", $valor, PDO::PARAM_STR); 
								$pdo->bindValue(":valorbruto", $valorbruto, PDO::PARAM_STR);
								$pdo->execute();
								$idparcela = Conexao::ultimoID();

		                    	$this->novaParcelaDescontos($idparcela,$i,$dados);
		                    	$this->novaParcelaFormaPagamento($idparcela,$i,$dados);
				        } 
				        catch (Exception $e) {
				            echo "<br>".$e->getMessage();
				        }
	                }
	            }
	        }
	    }

		public function novaParcelaDescontos($idparcela,$i,$dados){
	        if(isset($dados['valordesconto'][$i])){

	            foreach ($dados['valordesconto'] as $key => $value){

	                $vencimento = $dados['datadesconto'][$i] == NULL ? NULL : $dados['datadesconto'][$i];
	                try {
		                $sql  = "
							INSERT INTO `movimento_parceladesconto`(
								`id_movimentoparceladesconto`,
								`idmovimentoparcela`,
								`idtipodesconto`,
								`valordesconto`,
								`vencimento_desconto`
							) VALUES (
								:id_movimentoparceladesconto,
								:idmovimentoparcela,
								:idtipodesconto,
								:valordesconto,
								:vencimento_desconto
							)";

						$pdo = Conexao::getInstance()->prepare($sql);
						$pdo->bindValue(":id_movimentoparceladesconto", NULL, PDO::PARAM_INT); 
						$pdo->bindValue(":idmovimentoparcela", $idparcela, PDO::PARAM_INT); 
						$pdo->bindValue(":idtipodesconto", $dados['tipodesconto'][$i], PDO::PARAM_STR); 
						$pdo->bindValue(":valordesconto", $dados['valordesconto'][$i], PDO::PARAM_STR); 
						$pdo->bindValue(":vencimento_desconto", $vencimento, PDO::PARAM_STR); 
						$pdo->execute();
	       			} 
			        catch (Exception $e) {
			            echo "<br>".$e->getMessage();
			        }
	            }
	        }
	    }

		private function novaParcelaFormaPagamento($idmovimentoparcela,$indexparcela,$dados){
	        //Usado na geracao do boleto de rematricula concedendo descontos com valor menor
	        for( $i = 0 ; $i < sizeof( $dados['valorformapagamento'][$indexparcela] ) ; $i++ ){
	            if($dados['valorformapagamento'][$indexparcela][$i] != ''){
	                $valor = str_replace(",", ".", $dados['valorformapagamento'][$indexparcela][$i]);
	                $despesa = str_replace(",", ".", $dados['despesa'][$indexparcela][$i]);
	                $desconto = str_replace(",", ".", $dados['desconto'][$indexparcela][$i]);
	                $outrasdeducoes = str_replace(",", ".", $dados['deducoes'][$indexparcela][$i]);
	                $juros = str_replace(",", ".", $dados['juros'][$indexparcela][$i]);
	                $multa = str_replace(",", ".", $dados['multa'][$indexparcela][$i]);
	                $perda = str_replace(",", ".", $dados['perda'][$indexparcela][$i]);
	                
	                $idformapagamento = $dados['idformapagamento'][$indexparcela][$i];
	                $dados['tipopgto'][$indexparcela][$i] = isset($dados['tipopgto'][$indexparcela][$i]) ? $dados['tipopgto'][$indexparcela][$i] : 'outro' ;
	                
	                $dados['documentoformapagamento'][$indexparcela][$i] = isset($dados['documentoformapagamento'][$indexparcela][$i]) && !empty($dados['documentoformapagamento'][$indexparcela][$i]) ? $dados['documentoformapagamento'][$indexparcela][$i] : $dados['documento'] ;

	                $dados['complementoformapagamento'][$indexparcela][$i] = str_replace(",", ".", $dados['complementoformapagamento'][$indexparcela][$i]);


					try {
						$sql  = "
							INSERT INTO `movimento_parcelaformapagamento`(
								`id_movimento_parcelaformapagamento`,
								`idparcela`,
								`documento`,
								`complemento`,
								`valor`,
								`despesa`,
								`desconto`,
								`juros`,
								`multa`,
								`perda`,
								`outrasdeducoes`,
								`tipopagamento`
							 ) 
							VALUES (
								:id_movimento_parcelaformapagamento,
								:idparcela,
								:documento,
								:complemento,
								:valor,
								:despesa,
								:desconto,
								:juros,
								:multa,
								:perda,
								:outrasdeducoes,
								:tipopagamento
							)";
						$pdo = Conexao::getInstance()->prepare($sql);
						$pdo->bindValue("id_movimento_parcelaformapagamento", NULL, PDO::PARAM_INT);
						$pdo->bindValue("idparcela", $idmovimentoparcela, PDO::PARAM_INT);
						$pdo->bindValue("documento", $dados['documentoformapagamento'][$indexparcela][$i], PDO::PARAM_STR); 
						$pdo->bindValue("complemento", $dados['complementoformapagamento'][$indexparcela][$i], PDO::PARAM_STR); 
						$pdo->bindValue("valor", $valor, PDO::PARAM_STR); 
						$pdo->bindValue("despesa", $despesa, PDO::PARAM_STR); 
						$pdo->bindValue("desconto", $desconto, PDO::PARAM_STR); 
						$pdo->bindValue("juros", $juros, PDO::PARAM_STR); 
						$pdo->bindValue("multa", $multa, PDO::PARAM_STR); 
						$pdo->bindValue("perda", $perda, PDO::PARAM_STR); 
						$pdo->bindValue("outrasdeducoes", $outrasdeducoes, PDO::PARAM_STR); 
						$pdo->bindValue("tipopagamento", $dados['tipopgto'][$indexparcela][$i], PDO::PARAM_STR); 
						$pdo->execute();
						$idparcela_pgt = Conexao::ultimoID();

		                //CADASTRA OS DADOS DO BOLETO QUANDO É INFORMADO UM PARA CADA FORMA DE PAGAMENTO
		                if(isset($dados['emiteboletocadaparcela'][$indexparcela][$i])){
		                    $dataemissao = isset($dados['emissao']) ? $dados['emissao'] : date('Y-m-d');

							$sql  = "	
								INSERT INTO `movimento_parcelaformapagamentoboleto`(
									`movimento_parcelaformapagamentoboleto`,
									`idparcelaformapagamento`,
									`numerodocumento`,
									`nosso_numero`,
									`idconfigarquivobancario`,
									`dataemissao`
								) 
								VALUES (
									:movimento_parcelaformapagamentoboleto,
									:idparcelaformapagamento,
									:numerodocumento,
									:nosso_numero,
									:idconfigarquivobancario,
									:dataemissao
								)";
							$pdo = Conexao::getInstance()->prepare($sql);
							$pdo->bindValue(":movimento_parcelaformapagamentoboleto", NULL, PDO::PARAM_INT); 
							$pdo->bindValue(":idparcelaformapagamento", $idparcela_pgt, PDO::PARAM_INT); 
							$pdo->bindValue(":numerodocumento", $dados['numerodocumento'][$indexparcela][$i], PDO::PARAM_STR); 
							$pdo->bindValue(":nosso_numero", $dados['nosso_numero'][$indexparcela][$i], PDO::PARAM_STR); 
							$pdo->bindValue(":idconfigarquivobancario", $dados['idconfigarquivobancario'][$indexparcela][$i], PDO::PARAM_STR); 
							$pdo->bindValue(":dataemissao", $dataemissao, PDO::PARAM_STR);	
							$pdo->execute();	
							$ultimoboleto = Conexao::ultimoID();

							$sql = "UPDATE movimento_parcelaformapagamento
				            	SET
									documento 	= :documento
								WHERE id_movimento_parcelaformapagamento = :id_movimento_parcelaformapagamento
				                ";
				            $pdo = Conexao::getInstance()->prepare($sql);
				            $pdo->bindValue(":id_movimento_parcelaformapagamento", $idparcela_pgt, PDO::PARAM_INT);
				            $pdo->bindValue(":documento", $dados['nosso_numero'][$indexparcela][$i], PDO::PARAM_STR);
				            $pdo->execute();
		                }
		                //CADASTRA OS DADOS DO BOLETO QUANDO È INFORMADO UM POR MOVIMENTO ::: DEVE SER ALTERADO
		                else if(isset($dados['idconfigarquivobancario']) && $dados['idconfigarquivobancario']){
		                    $dataemissao = isset($dados['emissao']) ? $dados['emissao'] : date('Y-m-d');
		                    $sql  = "	
								INSERT INTO `movimento_parcelaformapagamentoboleto`(
									`movimento_parcelaformapagamentoboleto`,
									`idparcelaformapagamento`,
									`numerodocumento`,
									`nosso_numero`,
									`idconfigarquivobancario`,
									`dataemissao`
								) 
								VALUES (
									:movimento_parcelaformapagamentoboleto,
									:idparcelaformapagamento,
									:numerodocumento,
									:nosso_numero,
									:idconfigarquivobancario,
									:dataemissao
								)";

							$pdo = Conexao::getInstance()->prepare($sql);
							$pdo->bindValue(":movimento_parcelaformapagamentoboleto", NULL, PDO::PARAM_INT); 
							$pdo->bindValue(":idparcelaformapagamento", $idparcela_pgt, PDO::PARAM_INT); 
							$pdo->bindValue(":numerodocumento", $dados['numerodocumento'], PDO::PARAM_STR); 
							$pdo->bindValue(":nosso_numero", $dados['nosso_numero'], PDO::PARAM_STR); 
							$pdo->bindValue(":idconfigarquivobancario", $dados['idconfigarquivobancario'], PDO::PARAM_STR); 
							$pdo->bindValue(":dataemissao", $dataemissao, PDO::PARAM_STR);   
							$pdo->execute();

		                }

		                //CADASTRA OS DADOS DO DESCONTO DA FORMAPAGAMENTODESCONTO
		                if(!empty($dados['valordesconto'])){
		                    foreach($dados['valordesconto'] as $i => $valor){
		                        $valor = str_replace(",",".",$valor);
		                        $persisteaposvencimento = strtolower($dados['persisteaposvencimento'][$i]);

		                        if (!empty($dados['datadesconto'][$i])) {
		                            $datadesconto = $dados['datadesconto'][$i];
		                        }
		                        else{
		                            $datadesconto = 'NULL';
		                        }

			                    $sql  = "	
									INSERT INTO `movimento_parcelaformapagamentodesconto`(
										`id_movimento_parcelaformapagamentodesconto`,
										`idparcelaformapagamento`,
										`iddescontoclassificacao`,
										`iddescontotipo`,
										`persisteaposvencimento`,
										`valordesconto`,
										`datadesconto`
									) 
									VALUES (
										:id_movimento_parcelaformapagamentodesconto,
										:idparcelaformapagamento,
										:iddescontoclassificacao,
										:iddescontotipo,
										:persisteaposvencimento,
										:valordesconto,
										:datadesconto
									)";

								$pdo = Conexao::getInstance()->prepare($sql);
								$pdo->bindValue(":id_movimento_parcelaformapagamentodesconto", NULL, PDO::PARAM_INT); 
								$pdo->bindValue(":idparcelaformapagamento", $idparcela_pgt, PDO::PARAM_INT); 
								$pdo->bindValue(":iddescontoclassificacao", $dados['descontoclassificacao'][$i], PDO::PARAM_STR); 
								$pdo->bindValue(":iddescontotipo", $dados['tipodesconto'][$i], PDO::PARAM_STR);
								$pdo->bindValue(":persisteaposvencimento", $dados['persisteaposvencimento'][$i], PDO::PARAM_STR); 
								$pdo->bindValue(":valordesconto", $valor, PDO::PARAM_STR);   
								$pdo->bindValue(":datadesconto", $datadesconto, PDO::PARAM_STR);
								$pdo->execute();
		                    }
		                }
	       			} 
			        catch (Exception $e) {
			            echo "<br>".$e->getMessage();
			        }
	            }
	        }
	    }

	    public function salvaLogMovimento($operacao, $idmovimento, $observacao, $tokenuser){
	    	try {
	            $sql = "INSERT INTO movimento_log (
	                id_movimento_log, 
					operacao,
					idmovimento,
					observacao,
					tokenuser
					)
					VALUES (
	                :id_movimento_log, 
					:operacao,
					:idmovimento,
					:observacao,
					:tokenuser
					)

	                ";
	            $pdo = Conexao::getInstance()->prepare($sql);
	            $pdo->bindValue(":id_movimento_log", null, PDO::PARAM_INT);
	            $pdo->bindValue(":operacao", $operacao, PDO::PARAM_STR);
	            $pdo->bindValue(":idmovimento", $idmovimento, PDO::PARAM_INT);
	            $pdo->bindValue(":observacao", $observacao, PDO::PARAM_STR);
	            $pdo->bindValue(":tokenuser", $tokenuser, PDO::PARAM_STR);
	            $pdo->execute();
	        } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
	    }
	    public function buscaMovimentoParcelaFormaPagamento($idparcela = null){
	        $filtro = "";
	        $filtro .= $idparcela ? " AND mfp.idparcela = :idparcela " : "";
	        try{
		        $sql = "
		            select

		            mfp.valor valorformapagamento,
		            mfp.documento documentoformapagamento,
		            mfp.complemento complementoformapagamento,
		            mfp.id_movimento_parcelaformapagamento,

		            mpc.vencimento vencimentoformapagamento,
		            date_format(mpc.vencimento,'%d/%m/%Y') vencimentoformapagamento_,
		            mpc.valor,

		            cli.id_cliente,
		            cli.nome_cliente,
		            cli.email_cliente,
		            cli.celular_cliente,
		            cli.logradouro_cliente,
		            cli.numero_cliente,
		            cli.complemento_cliente,
		            cli.bairro_cliente,
		            cli.cidade_cliente,
		            cli.estado_cliente,
		            cli.cep_cliente,
		            cli.cpf_cliente,
		            cli.id_representante_financeiro_cliente,

		            rep.nome_cliente nome_rep,
		            rep.email_cliente email_rep,
		            rep.celular_cliente celular_rep,
		            rep.logradouro_cliente logradouro_rep,
		            rep.numero_cliente numero_rep,
		            rep.complemento_cliente complemento_rep,
		            rep.bairro_cliente bairro_rep,
		            rep.cidade_cliente cidade_rep,
		            rep.estado_cliente estado_rep,
		            rep.cep_cliente cep_rep,
		            rep.cpf_cliente cpf_rep,

		            mfi.descricaohistorico
		         
		            from
		                movimento_parcelaformapagamento mfp
		            inner join
		                movimento_parcela mpc on mpc.id_movimento_parcela = mfp.idparcela
		            inner join
		                movimento mfi on mfi.id_movimento = mpc.idmovimento
		            inner join 
		                cliente cli on cli.id_cliente = mfi.idcliente
		            left join 
		                cliente rep on rep.id_cliente = cli.id_representante_financeiro_cliente
		            
		       
		            where
		                id_movimento_parcelaformapagamento > :id_movimento_parcelaformapagamento
		                $filtro
		        ";
				$pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':id_movimento_parcelaformapagamento', 0, PDO::PARAM_INT);
	            if ($idparcela) {
		            $pdo->bindValue(':idparcela', $idparcela, PDO::PARAM_INT);
	            }
	        
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
		    }catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
	    }

		public function buscaMovimentoParcela($idparcela = null){
	        $filtro = "";
	        $filtro .= isset($idparcela) ? " AND id_movimento_parcela = :idparcela " : "";
	        try{
		        $sql = "
		            select *
		            from
		                movimento_parcela
		            where
		                id_movimento_parcela > :id_movimento_parcela
		                $filtro
		            order by
		                vencimento

		        ";
				$pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':id_movimento_parcela', 0, PDO::PARAM_INT);
	            if ($idparcela) {
		            $pdo->bindValue(':idparcela', $idparcela, PDO::PARAM_INT);
	            }
	        
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
		    }catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }	    
	    }
	    public function buscaMovimentoParcelaFormaPagamentoDesconto($idparcela = null, $persisteaposvencimento = null){
	        $filtro = "";
	        $filtro .= isset($idparcela) ? " and mfp.idparcela = :idparcela " : "";
	        $filtro .= isset($persisteaposvencimento) ? " and mfd.persisteaposvencimento = :persisteaposvencimento " : "";
	        try{
		        $sql = "
		            SELECT *
		            from
		                movimento_parcelaformapagamentodesconto mfd
		            inner join
		                movimento_parcelaformapagamento mfp on mfp.id_movimento_parcelaformapagamento = mfd.idparcelaformapagamento

		            where
		                mfd.id_movimento_parcelaformapagamentodesconto > :idmovimentoparcelaformapagamentodesconto
		                $filtro
		        ";
				$pdo = Conexao::getInstance()->prepare($sql);

		       	$pdo->bindValue(':idmovimentoparcelaformapagamentodesconto', 0, PDO::PARAM_INT);
	            if ($idparcela) {
		            $pdo->bindValue(':idparcela', $idparcela, PDO::PARAM_INT);
	            }
	            if ($persisteaposvencimento) {
		            $pdo->bindValue(':persisteaposvencimento', $persisteaposvencimento, PDO::PARAM_STR);
	            }
	        
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
		    }catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }	
	    }


	}