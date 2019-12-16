<?php
	class FaturamentoBoleto extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new FaturamentoBoleto();
	        return self::$instance;
	    }

	    public function calculaNossoNumero($idconfig){
	    	include_once("./class/class.configuracaoArquivo.php");

	        $conf = new ConfiguracaoArquivo();
    		$dadosBanco = $conf->buscaConfiguracaoArquivo($idconfig);

	        //Se for SICOOB
	        if($dadosBanco[0]['codigobanco'] == '756-0'){
				include_once("./class/class.faturamentoBoletoSicoob.php");
	            $sicoob = new FaturamentoBoletoSicoob();
	            // DADOS DO BOLETO PARA O CLIENTE	            
	            $IdDoSeuSistemaAutoIncremento = $dadosBanco[0]['numerodocumentoatual']; // Deve informar um numero sequencial a ser passada a função abaixo, Até 6 dígitos
	            $agencia = substr($dadosBanco[0]['numeroagencia'], 0,4);// Num da agencia, sem digito
	            $conta = $dadosBanco[0]['numeroconta']; // Num da conta, sem digito
	            $convenio = $dadosBanco[0]['codigocedente']; //Número do convênio indicado no frontend
	            $nosso_numero = $sicoob->calculaNossoNumeroSicoob($IdDoSeuSistemaAutoIncremento, $agencia, $conta, $convenio);
	        }
	        else {
	            $nosso_numero = $IdDoSeuSistemaAutoIncremento;
	        }

	        //Usado em todas as situações, quando existirem mais bancos, vai funcionar
	        $dados['numero_documento']  = $IdDoSeuSistemaAutoIncremento ;
	        $dados['nosso_numero']      = $nosso_numero ;


	        return $dados ;
	    }

		public function buscaParcelaFormaPagamentoBoleto($idparcela = null, $idparcelaformapagamento = null){

	        $filtro = "";
	        $filtro .= isset($idparcela) ? " AND mpc.id_movimento_parcela = :idparcela " : "";
	        $filtro .= isset($idparcelaformapagamento) ? " AND mfp.id_movimento_parcelaformapagamento = :idparcelaformapagamento " : "";
			try {
		        $sql = "SELECT 
		                    mpb.movimento_parcelaformapagamentoboleto, 
		                    mpb.idparcelaformapagamento, 
		                    mpb.numerodocumento, 
		                    mpb.nosso_numero, 
		                    mpb.idconfigarquivobancario, 
		                    mpb.dataemissao,
		                    
		                    mfp.idparcela AS 'idboleto',
		                    mfp.valorpago AS 'valorpago',
		                    mfp.pagamento AS 'datapagamento',
		                    mfp.documento,

		                    mpc.idmovimento,
		                    mpc.vencimento,
		                    mpc.valor,

		                    cab.codigobanco,

		                    cont.numparcela AS 'parctotal',
		                    cont.diavencimento,
		                    cont.status_contrato,

		                    cmf.idcontrato AS 'idcontrato_gcvc',

		                    valordescontototal,
		                    valordescontopersiste,
		                    valordescontonaopersiste

		            FROM `movimento_parcelaformapagamentoboleto` mpb
		            inner join
		                movimento_parcelaformapagamento mfp on mfp.id_movimento_parcelaformapagamento = mpb.idparcelaformapagamento
		            inner join
		                movimento_parcela mpc on mpc.id_movimento_parcela = mfp.idparcela   
		            
		            inner join
		                configarquivobancario cab on cab.id_configarquivobancario = mpb.idconfigarquivobancario
		            left join
		                contrato_movimento cmf on cmf.idmovimento = mpc.idmovimento
		            left join
		                contrato cont on cont.id_contrato = cmf.idcontrato

		            LEFT JOIN
		                (select 
		                    idparcelaformapagamento, 
		                    sum(valordesconto) valordescontototal, 
		                    sum(if(persisteaposvencimento = 'sim', valordesconto, 0)) valordescontopersiste, 
		                    sum(if(persisteaposvencimento = 'nao', valordesconto, 0)) valordescontonaopersiste
		                 from 
		                 movimento_parcelaformapagamentodesconto  group by idparcelaformapagamento) descontos on descontos.idparcelaformapagamento = mfp.id_movimento_parcelaformapagamento

		            WHERE mpb.movimento_parcelaformapagamentoboleto > :movimento_parcelaformapagamentoboleto
		            $filtro

		            GROUP BY mpb.movimento_parcelaformapagamentoboleto
		            order by mpb.movimento_parcelaformapagamentoboleto;
		        ";

	            $pdo = Conexao::getInstance()->prepare($sql);
				
	            $pdo->bindValue(':movimento_parcelaformapagamentoboleto', 0, PDO::PARAM_INT);
	            if ($idparcela) {
		            $pdo->bindValue(':idparcela', $idparcela, PDO::PARAM_INT);
	            }
	            if ($idparcelaformapagamento) {
		            $pdo->bindValue(':idparcelaformapagamento', $idparcelaformapagamento, PDO::PARAM_INT);
	            }
	        
	        
	            $pdo->execute();
	            return $pdo->fetchAll(PDO::FETCH_BOTH);
		        
		    } 
	        catch (Exception $e) {
	            echo "<br>".$e->getMessage();
	        }
	    }

	    public function nomeBanco($codigobanco){
	        $banco = array(
	            'sicoob' => '756-0',
	            'bradesco' => '237',
	            'safra'     => '422-7'
	        );

	        return array_search($codigobanco, $banco);
	    }



	}