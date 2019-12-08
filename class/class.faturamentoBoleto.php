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



	}