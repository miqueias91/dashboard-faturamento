<?php
	class FaturamentoBoletoSicoob extends Conexao {
		public static $instance;

		public function __construct(){}

		public static function getInstance() {
	        self::$instance = new FaturamentoBoletoSicoob();
	        return self::$instance;
	    }

	    public function formata_numdoc($num,$tamanho){
	        while(strlen($num)<$tamanho){
	            $num="0".$num; 
	        }
	        return $num;
	    }

	    public function calculaNossoNumeroSicoob($IdDoSeuSistemaAutoIncremento, $agencia, $conta, $convenio){
	        $NossoNumero = $this->formata_numdoc($IdDoSeuSistemaAutoIncremento,7);
	        $qtde_nosso_numero = strlen($NossoNumero);
	        $sequencia = $this->formata_numdoc($agencia,4).$this->formata_numdoc(str_replace("-","",$convenio),10).$this->formata_numdoc($NossoNumero,7);
	        $cont=0;
	        $calculoDv = 0;
	            for($num=0;$num<=strlen($sequencia);$num++)
	            {
	                $cont++;
	                if($cont == 1)
	                {
	                    // constante fixa Sicoob » 3197 
	                    $constante = 3;
	                }
	                if($cont == 2)
	                {
	                    $constante = 1;
	                }
	                if($cont == 3)
	                {
	                    $constante = 9;
	                }
	                if($cont == 4)
	                {
	                    $constante = 7;
	                    $cont = 0;
	                }
	                $calculoDv = $calculoDv + (substr($sequencia,$num,1) * $constante);
	            }
	        $Resto = $calculoDv % 11;
	        if($Resto == 0 || $Resto == 1){
	            $Dv = '0' ;
	        }
	        else{
	            $Dv = 11 - $Resto;
	        }

	        return $NossoNumero . $Dv;
	    }

	    public function geraCodigoBanco($numero) {
	        $parte1 = substr($numero, 0, 3);
	        $parte2 = $this->modulo_11($parte1);
	        return $parte1 . "-" . $parte2;
	    }

	    public function fator_vencimento($data) {
	        $data = explode("/",$data);
	        $ano = $data[2];
	        $mes = $data[1];
	        $dia = $data[0];
	        return(abs(($this->_dateToDays("1997","10","07")) - ($this->_dateToDays($ano, $mes, $dia))));
	    }

	    public function _dateToDays($year,$month,$day) {
	        $century = substr($year, 0, 2);
	        $year = substr($year, 2, 2);
	        if ($month > 2) {
	            $month -= 3;
	        } else {
	            $month += 9;
	            if ($year) {
	                $year--;
	            } else {
	                $year = 99;
	                $century --;
	            }
	        }
	        return ( floor((  146097 * $century)    /  4 ) +
	                floor(( 1461 * $year)        /  4 ) +
	                floor(( 153 * $month +  2) /  5 ) +
	                    $day +  1721119);
	    }

	    public function formata_numero($numero,$loop,$insert,$tipo = "geral") {
	        if ($tipo == "geral") {
	            $numero = str_replace(",","",$numero);
	            while(strlen($numero)<$loop){
	                $numero = $insert . $numero;
	            }
	        }
	        if ($tipo == "valor") {
	            /*
	            retira as virgulas
	            formata o numero
	            preenche com zeros
	            */
	            $numero = str_replace(",","",$numero);
	            while(strlen($numero)<$loop){
	                $numero = $insert . $numero;
	            }
	        }
	        if ($tipo == "convenio") {
	            while(strlen($numero)<$loop){
	                $numero = $numero . $insert;
	            }
	        }
	        return $numero;
	    }

	    /*
	    #################################################
	    FUNÇÃO DO MÓDULO 11 RETIRADA DO PHPBOLETO

	    ESTA FUNÇÃO PEGA O DÍGITO VERIFICADOR:

	    NOSSONUMERO
	    AGENCIA
	    CONTA
	    CAMPO 4 DA LINHA DIGITÁVEL
	    #################################################
	    */
	    public function modulo_11($num, $base=9, $r=0) {
	        $soma = 0;
	        $fator = 2; 
	        for ($i = strlen($num); $i > 0; $i--) {
	            $numeros[$i] = substr($num,$i-1,1);
	            $parcial[$i] = $numeros[$i] * $fator;
	            $soma += $parcial[$i];
	            if ($fator == $base) {
	                $fator = 1;
	            }
	            $fator++;
	        }
	        if ($r == 0) {
	            $soma *= 10;
	            $digito = $soma % 11;
	            
	            //corrigido
	            if ($digito == 10) {
	                $digito = "X";
	            }

	            /*
	            
	            O módulo 11 só gera os digitos verificadores do nossonumero,
	            agencia, conta e digito verificador com codigo de barras (aquele que fica sozinho e triste na linha digitável)
	            só que é foi um rolo...pq ele nao podia resultar em 0, e o pessoal do phpboleto se esqueceu disso...
	            
	            No BB, os dígitos verificadores podem ser X ou 0 (zero) para agencia, conta e nosso numero,
	            mas nunca pode ser X ou 0 (zero) para a linha digitável, justamente por ser totalmente numérica.

	            Quando passamos os dados para a função, fica assim:

	            Agencia = sempre 4 digitos
	            Conta = até 8 dígitos
	            Nosso número = de 1 a 17 digitos

	            A unica variável que passa 17 digitos é a da linha digitada, justamente por ter 43 caracteres

	            Entao vamos definir ai embaixo o seguinte...

	            se (strlen($num) == 43) { não deixar dar digito X ou 0 }
	            */
	            
	            if (strlen($num) == "43") {
	                //então estamos checando a linha digitável
	                if ($digito == "0" or $digito == "X" or $digito > 9) {
	                        $digito = 1;
	                }
	            }
	            return $digito;
	        } 
	        elseif ($r == 1){
	            $resto = $soma % 11;
	            return $resto;
	        }
	    }

	    public function monta_linha_digitavel($linha) {
	        // Posição  Conteúdo
	        // 1 a 3    Número do banco
	        // 4        Código da Moeda - 9 para Real
	        // 5        Digito verificador do Código de Barras
	        // 6 a 19   Valor (12 inteiros e 2 decimais)
	        // 20 a 44  Campo Livre definido por cada banco

	        // 1. Campo - composto pelo código do banco, código da moéda, as cinco primeiras posições
	        // do campo livre e DV (modulo10) deste campo
	        $p1 = substr($linha, 0, 4);
	        $p2 = substr($linha, 19, 5);
	        $p3 = $this->modulo_10("$p1$p2");
	        $p4 = "$p1$p2$p3";
	        $p5 = substr($p4, 0, 5);
	        $p6 = substr($p4, 5);
	        $campo1 = "$p5.$p6";

	        // 2. Campo - composto pelas posiçoes 6 a 15 do campo livre
	        // e livre e DV (modulo10) deste campo
	        $p1 = substr($linha, 24, 10);
	        $p2 = $this->modulo_10($p1);
	        $p3 = "$p1$p2";
	        $p4 = substr($p3, 0, 5);
	        $p5 = substr($p3, 5);
	        $campo2 = "$p4.$p5";

	        // 3. Campo composto pelas posicoes 16 a 25 do campo livre
	        // e livre e DV (modulo10) deste campo
	        $p1 = substr($linha, 34, 10);
	        $p2 = $this->modulo_10($p1);
	        $p3 = "$p1$p2";
	        $p4 = substr($p3, 0, 5);
	        $p5 = substr($p3, 5);
	        $campo3 = "$p4.$p5";

	        // 4. Campo - digito verificador do codigo de barras
	        $campo4 = substr($linha, 4, 1);

	        // 5. Campo composto pelo valor nominal pelo valor nominal do documento, sem
	        // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
	        // tratar de valor zerado, a representacao deve ser 000 (tres zeros).
	        $campo5 = substr($linha, 5, 14);

	        return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
	    }

	    /*
	    #################################################
	    FUNÇÃO DO MÓDULO 10 RETIRADA DO PHPBOLETO

	    ESTA FUNÇÃO PEGA O DÍGITO VERIFICADOR DO PRIMEIRO, SEGUNDO
	    E TERCEIRO CAMPOS DA LINHA DIGITÁVEL
	    #################################################
	    */
	    public function modulo_10($num) {
	        $numtotal10 = 0;
	        $fator = 2;
	     
	        for ($i = strlen($num); $i > 0; $i--) {
	            $numeros[$i] = substr($num,$i-1,1);
	            $parcial10[$i] = $numeros[$i] * $fator;
	            $numtotal10 .= $parcial10[$i];
	            if ($fator == 2) {
	                $fator = 1;
	            }
	            else {
	                $fator = 2; 
	            }
	        }
	        
	        $soma = 0;
	        for ($i = strlen($numtotal10); $i > 0; $i--) {
	            $numeros[$i] = substr($numtotal10,$i-1,1);
	            $soma += $numeros[$i]; 
	        }
	        $resto = $soma % 10;
	        $digito = 10 - $resto;
	        if ($resto == 0) {
	            $digito = 0;
	        }

	        return $digito;
	    }

	    public function esquerda($entra,$comp){
	            return substr($entra,0,$comp);
	    }

	    public function direita($entra,$comp){
	            return substr($entra,strlen($entra)-$comp,$comp);
	    } 

	    public function fbarcode($valor){
	        global $base_http;

	        $fino = 1 ;
	        $largo = 3 ;
	        $altura = 50 ;

	        $barcodes[0] = "00110" ;
	        $barcodes[1] = "10001" ;
	        $barcodes[2] = "01001" ;
	        $barcodes[3] = "11000" ;
	        $barcodes[4] = "00101" ;
	        $barcodes[5] = "10100" ;
	        $barcodes[6] = "01100" ;
	        $barcodes[7] = "00011" ;
	        $barcodes[8] = "10010" ;
	        $barcodes[9] = "01010" ;
	        for($f1=9;$f1>=0;$f1--){ 
	            for($f2=9;$f2>=0;$f2--){  
	                $f = ($f1 * 10) + $f2 ;
	                $texto = "" ;
	                for($i=1;$i<6;$i++){ 
	                $texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
	                }
	                $barcodes[$f] = $texto;
	            }
	        }

	        //Desenho da barra
	        //Guarda inicial
	        ?><img src='<?php echo $base_http?>/image/logo_banco/p.png' width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
	        src='<?php echo $base_http?>/image/logo_banco/b.png' width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
	        src='<?php echo $base_http?>/image/logo_banco/p.png' width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
	        src='<?php echo $base_http?>/image/logo_banco/b.png' width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
	        <?php
	            $texto = $valor ;
	            if((strlen($texto) % 2) <> 0){
	                $texto = "0" . $texto;
	            }

	            // Draw dos dados
	            while (strlen($texto) > 0) {          
	                $i = round($this->esquerda($texto,2));
	                $texto = $this->direita($texto,strlen($texto)-2);
	                $f = $barcodes[$i];
	                for($i=1;$i<11;$i+=2){
	                    if (substr($f,($i-1),1) == "0") {
	                      $f1 = $fino ;
	                    }else{
	                      $f1 = $largo ;
	                    }
	        ?>
	                    src=<?php echo $base_http?>/image/logo_banco/p.png width=<?php echo $f1?> height=<?php echo $altura?> border=0><img 
	        <?php
	                    if (substr($f,$i,1) == "0") {
	                      $f2 = $fino ;
	                    }else{
	                      $f2 = $largo ;
	                    }
	        ?>
	                    src=<?php echo $base_http?>/image/logo_banco/b.png width=<?php echo $f2?> height=<?php echo $altura?> border=0><img 
	        <?php
	                }
	            }

	        // Draw guarda final
	        ?>
	        src=<?php echo $base_http?>/image/logo_banco/p.png width=<?php echo $largo?> height=<?php echo $altura?> border=0><img 
	        src=<?php echo $base_http?>/image/logo_banco/b.png width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
	        src=<?php echo $base_http?>/image/logo_banco/p.png width=<?php echo 1?> height=<?php echo $altura?> border=0> 
	          <?php
	    }



	}