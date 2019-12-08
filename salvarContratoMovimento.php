<?php
include_once("./verifica.php");
include_once("./config/config.php");
include_once("$base/class/class.contrato.php");
include_once("$base/class/class.mascara.php");

include_once("$base/class/class.financeiro.php");
include_once("$base/class/class.faturamentoBoleto.php");
include_once("$base/class/class.faturamentoBoletoSicoob.php");
include_once("$base/class/class.configuracaoArquivo.php");

echo "<pre>";

function listaMeses($mesinicial, $anoinicial, $mesfinal, $anofinal){
    //Inicializa array
    $meses = array();
    //Percorrendo os anos do periodo solicitado
    for($ano = $anoinicial ; $ano <= $anofinal ; $ano++){
        //Condicao de inicio e parada levando em consideracao o ano inicial e final
        $mes_ = $ano == $anoinicial ?  $mesinicial : 1 ;
        $_mes = $ano == $anofinal ? $mesfinal : 12 ;
        //Percorrendo os meses dentro de cada ano solicitado
        for($mes = $mes_ ; $mes <= $_mes ; $mes++){
            //Coloca em um array o mes/ano percorrido
            array_push($meses, str_pad($mes,2,"0",STR_PAD_LEFT)."/$ano");
        }
    }
    //Retorna
    return $meses ;
}

$m = new Mascara();
$contrato = new Contrato();
$fin = new Financeiro();




$movimentos_gerados_cont = 0 ;
$movimentos_gerados = $contrato->buscaMovimentoContrato($idcontrato);
if ($movimentos_gerados) {
    foreach ($movimentos_gerados as $key => $value) {
        $movimentos_gerados_cont++;
    }
}
$dadoscontrato = $contrato->buscaContrato($idcontrato);
$dadoscontrato = $dadoscontrato[0];
// $num_parcelas_geradas = $movimentos_gerados ? sizeof($movimentos_gerados_cont) : 0;
$num_parcelas_geradas = $movimentos_gerados ? $movimentos_gerados_cont : 0;

if($num_parcelas_geradas >= $dadoscontrato['numparcela']){    
    echo "<script>alert('Esse contrato já está completo. Não é permitido gerar mais parcelas.');";
    echo "</script>";
    die;
}

/******************* Montando o  array de mes/ano de vencimento de cada movimento ***********************/
// Pegando o ano de inicio
$tmp = explode("/", $dadoscontrato['compinicial']);
$ano = $tmp[1];
$mes = $tmp[0];
$dia = '01';
// Concatenando ano-mes-dia para pegar o inicio da primeira parcela
$tmp = "$ano-$mes-$dia";
// Transformando a data inicial da primeira parcela
$time = strtotime($tmp);
$num_parcelas = $dadoscontrato['numparcela']-1;
// Incrementando a quantidade parcelas(meses) para obter a data final de acordo com o numero de parcelas cadastrado pelo usuario date("Y-m-d", strtotime("+1 month", $time));
$final = date("Y-m-d", strtotime("+$num_parcelas month", $time));
// Montando o array com numero de meses obtido entre as datas inicial e final
$meses = listaMeses($mes, $ano, substr($final,5,2), substr($final,0,4));
/***********************************************************/

/******************* Montando o array do mes/ano da competencia da apropriacao ***********************/
$tmp = explode("/", $dadoscontrato['compinicial']); // formato mm/YYYY
$ano = $tmp[1];
$mes = $tmp[0];
$dia = 1;
// Concatenando ano-mes-dia para pegar o inicio da primeira parcela
$tmp = "$ano-$mes-$dia";
// Transformando a data inicial da primeira parcela
$time = strtotime($tmp);
//$num_parcelas = $dadoscontrato['numparcela']-1;
// Incrementando a quantidade parcelas(meses) para obter a data final de acordo com o numero de parcelas cadastrado pelo usuario date("Y-m-d", strtotime("+1 month", $time));
$final = date("Y-m-d", strtotime("+$num_parcelas month", $time));
// Montando o array com numero de meses obtido entre as datas inicial e final
$meses_competencia = listaMeses($mes, $ano, substr($final,5,2), substr($final,0,4));
/***********************************************************/

/* DADOS DA ABA DESCONTO */
$datavencimentodesconto = null;
$valor_naopersiste = 0;
$valor_persiste = 0;
$valor_total_descontos = 0;

$comp_ = $competencia ? $competencia : ($meses_competencia[$num_parcelas_geradas] ? $meses_competencia[$num_parcelas_geradas] : date("m/Y") );
$dadosdesconto = $contrato->buscaDescontoContrato($idcontrato, $comp_);
if (isset($dadosdesconto)) {

    if ($datavencimento_desconto) {
        $datavencimentodesconto = explode('/', $datavencimento_desconto);
    }
    else{
        $datavencimentodesconto = explode('/', $datavencimento_movimento);
        $datavencimentodesconto[0] = $diavencimentodesconto;
        $datavencimentodesconto[1] = $datavencimentodesconto[1];
        $datavencimentodesconto[2] = $datavencimentodesconto[2];
    }

    $tipodesconto = null;
    $descontoclassificacao = null;
    $persisteaposvencimento = null;
    $percentualmaximo = null;
    $valordesconto = null;
    foreach ($dadosdesconto as $key => $cada) {
        $tipodesconto[] = $cada['idtipodesconto'];
        $descontoclassificacao[] = $cada['iddescontoclassificacao'];
        $persisteaposvencimento[] = $cada['persisteaposvencimento'];
        $percentualmaximo[] = $cada['percentualmaximo'];
        $valordesconto[] = $cada['valordesconto'];
    }

    $data_vencimento = explode('/', $datavencimento_movimento);
    $dados['tipodesconto'] = $tipodesconto;
    $dados['descontoclassificacao'] = $descontoclassificacao;
    $dados['persisteaposvencimento'] = $persisteaposvencimento;
    $dados['percentualmaximo'] = $percentualmaximo;
    $dados['valordesconto'] = $valordesconto;    
    
    foreach ($persisteaposvencimento as $key => $row) {
        //SE PERSISTIR O DESCONTO, PEGO OS VALORES E A DATA DE VENCIMENTO DO DESCONTO É NULL
        if ($row == 'sim') {
            $datadesconto[] = null;
            $valor_persiste += str_replace(',', '.', $valordesconto[$key]);
        }
        else{
            //SE O DESCONTO NÃO PERSISITR, PEGO O DIA DE VENCIMENTO E SOMO OS VALORES DOS DESCONTOS QUE NÃO PERSISTEM
            $datadesconto[] = $datavencimentodesconto[2].'-'.$datavencimentodesconto[1].'-'.$datavencimentodesconto[0];
            $valor_naopersiste += str_replace(',', '.', $valordesconto[$key]);
        }
    }
    
    $dados['datadesconto'] = $datadesconto;
    $aux_datavencimentodesconto = $datavencimentodesconto[2].'-'.$datavencimentodesconto[1].'-'.$datavencimentodesconto[0];
    $aux_data_vencimento = $data_vencimento[2].'-'.$data_vencimento[1].'-'.$data_vencimento[0];

    $valor_total_descontos = $valor_persiste + $valor_naopersiste;
}

// Vencimento da parcela
$vencimento = str_pad($dadoscontrato['diavencimento'], 2, "0", STR_PAD_LEFT)."/".$meses[$num_parcelas_geradas];


$valor_original = $dadoscontrato['valorparcela'];
$valor_liquido = $dadoscontrato['valorparcela'];





//Incrementando o numero de parcelas geradas
$num_parcelas_geradas++;

$num_parcelas_historico = $num_parcelas_geradas;




/* Dados do movimento principal */
$dados['entrada'] =  date('d/m/Y') ;
$dados['emissao'] =  date('d/m/Y');
$dados['valor'] = isset($valormovimento) ? str_replace(",", ".", $valormovimento) : $dadoscontrato['valorparcela'];
$dados['valorliquido'] = isset($valormovimento) ? str_replace(",", ".", $valormovimento) : $dadoscontrato['valorparcela'];
$dados['tipomovimento']= $dadoscontrato['tipomovimento'];
$dados['documento']= NULL;
$dados['serie']= NULL;
$dados['especie']= NULL;
$dados['idcliente']= $dadoscontrato['idcliente'];
$dados['historico']= "MOVIMENTO $num_parcelas_historico/{$dadoscontrato['numparcela']} REF. AO CONTRATO N ".str_pad($idcontrato,7,'0', STR_PAD_LEFT);
$dados['tokenuser']= $_SESSION['token_user'];

    

/* Dados da parcela */
$dados['valorbrutoparcela'][0] = $dados['valorliquido'];
$dados['valorparcela'][0] = $dados['valorliquido'];
$dados['valorparcela'][0] = number_format($dados['valorparcela'][0], 2, ",", "");
$dados['valorparcela'][0] = str_replace(",", ".", $dados['valorparcela'][0]);
$dados['vencimento'][0] = $datavencimento_movimento;

/* Dados da forma de pagamento */
$dados['valorformapagamento'][0][0] = $dados['valorparcela'][0];
$dados['documentoformapagamento'][0][0] = "";
$dados['complementoformapagamento'][0][0] = "";
$dados['despesa'][0][0] = "0,00";
$dados['desconto'][0][0] = $valor_total_descontos > 0 ? number_format($valor_total_descontos, 2, ",", "") : "0,00";
$dados['deducoes'][0][0] = "0,00";
$dados['juros'][1][0] = "0,00";
$dados['multa'][0][0] = "0,00";
$dados['pagamento'][0][0] = "";
$dados['valorpago'][0][0] = "0,00";
$dados['contabancaria'][0][0] = "";
$dados['tipopgto'][0][0]= $dadoscontrato['tipopagamento'];
$dados['indexador_desconto'] = 100;

$dados['nosso_numero'] = NULL;
$dados['idconfigboleto']= NULL; 
$dados['numerodocumento'] = NULL;

$boleto = isset($dadoscontrato['tipopagamento']) && $dadoscontrato['tipopagamento'] == 'boleto' ? true : false;
if($boleto){
    //Busco a configuração do banco
    $conf = new ConfiguracaoArquivo();
    $dadosBanco = $conf->buscaConfiguracaoArquivo($dadoscontrato['idconfigarquivobancario']);
    //Se for SICOOB
    $bol = new FaturamentoBoleto();
    $dadosboleto = $bol->calculaNossoNumero( $dadoscontrato['idconfigarquivobancario'] );
    $dados['nosso_numero'] = $dadosboleto['nosso_numero'];
    $dados['idconfigboleto']= $dadoscontrato['idconfigarquivobancario'];
    $dados['numerodocumento'] = $dadosboleto['numero_documento'];
    //Incremento mais 1 no numero documento atual
    $conf->incrementaNumeroDocumentoAtual($dadosBanco[0]['id_configarquivobancario']);
}
$idmovimentofinanceiro = $orc->novoMovimento($dados);
print_r($dadosboleto);
die;

$ngFinan = new ngFinanceiro($id);
$confere = $ngFinan->novoMovimentoFinanceiro($idmovimentofinanceiro);

$aux_comp = $competencia ? $competencia : $meses_competencia[$num_parcelas_geradas-1];
$dados['dataprevistaentrega'] = $dadoscontrato['diavencimento']."/".$aux_comp;
$dados['datarecebimento'] = NULL;
$dados['idmovimento'] = $idmovimentofinanceiro;

$orc->novoMovimentoJustificativa($idmovimentofinanceiro, $dados['justificativa']);
$log->salvaLogFinanceiro("INCLUIR", $id, null, $idmovimentofinanceiro, $dados['tipomovimento'], $_SESSION['id_usuario'], null, "NOVO MOV. CONTRATO");
$contrato->cadastraMovimentoContrato($dadoscontrato['idcont_contrato'], $dados);
$contrato->cadastraJustificativaContrato($dadoscontrato['idcont_contrato'], $_SESSION['id_usuario'], 'Gerou o movimento financeiro - Finalizado', $dados['valor'],'finalizou');

$contrato->alteraLiberacaoContrato($dadoscontrato['idcont_contrato'], 'nao');
$justificativas = $contrato->buscaJustificativaContrato($dadoscontrato['idcont_contrato'],null, 'sim');
foreach ($justificativas as $key => $value) {
    $contrato->cadastraMovimentoJustifcativa($value['id_justificativa'], $dadoscontrato['idcont_contrato'], $idmovimentofinanceiro);
}

$baixarmovimento = null;
//SE O VALOR LIQUIDO MENOS O VALOR DOS DESCONTOS FOR IGUAL A 0, FAÇO A BAIXA
$indexadordesconto = isset($dados['indexador_desconto']) ? $dados['indexador_desconto'] : 100;
if ( ($dados['valorliquido'] - (str_replace(",", ".", $dados['desconto'][0][0]) * ($indexadordesconto/100) )) == 0 ) {
    $baixarmovimento = true;
}

if($baixarmovimento && $dadosBanco[0]['idcontabancariapadraobaixa']){
    $dadosMovimentoParcela = $orc->buscaMovimentoParcelaFormaPagamento(null, $idmovimentofinanceiro, $id, $dados['tipomovimento']);
    $dadosMovimento = $dadosMovimentoParcela[0];        
    
    $numeromovimento = $dadosMovimento['numeromovimento'] ? $dadosMovimento['numeromovimento'] : null;
    $idparcelaformapagamento = $dadosMovimento['idparcelaformapagamento'] ? $dadosMovimento['idparcelaformapagamento'] : null;

    $log->salvaLogFinanceiro("BAIXAFPAG", $id, $numeromovimento, $idmovimentofinanceiro, $dados['tipomovimento'], $_SESSION['id_usuario'], $idparcelaformapagamento, "BAIXA MOVIMENTO");

    $dadosBaixa['tipomovimento']                = $dados['tipomovimento'];
    $dadosBaixa['idmovimento']                  = $idmovimentofinanceiro;
    $dadosBaixa['numeromovimento']              = $numeromovimento;
    $dadosBaixa['idhistoricobancarioquitacao']  = $dados['idhistorico'];
    $dadosBaixa['idparcelaformapagamento']      = $idparcelaformapagamento;
    $dadosBaixa['valororiginalquitacao']        = $dados['valorliquido'];
    $dadosBaixa['despesaquitacao']              = '0,00';
    $dadosBaixa['descontoquitacao']             = $dados['desconto'][0][0];
    $dadosBaixa['deducaoquitacao']              = '0,00';
    $dadosBaixa['perdaquitacao']                = '0,00';
    $dadosBaixa['jurosquitacao']                = '0,00';
    $dadosBaixa['multaquitacao']                = '0,00';
    $dadosBaixa['valortotalquitacao']           = '0,00';
    $dadosBaixa['pagamentoquitacao']            = date('d/m/Y');
    $dadosBaixa['conciliar']                    = 1;
    $dadosBaixa['idcontabancariaquitacao']      = $dadosBanco[0]['idcontabancariapadraobaixa'];
    $dadosBaixa['historicobancarioquitacao']    = $dados['historico'];
    $dadosBaixa['idusuario']                    = $_SESSION['id_usuario'];
    $dadosBaixa['idempresa']                    = $id;

    //Verificando baixa para nao gerar duplicidade
    if($dadosMovimento['pagamento'] == null){
        //Baixando no SGO
        $ngFinan->baixarFormaPagamento($dadosBaixa);
        //Baixando lancamento no NG
        $conferencia = $ngFinan->baixarFormaPagamentoNG($dadosBaixa);

        //Conferindo Baixa NG e alterando no SGO em caso de problema
        if(substr($conferencia, 0, 2) == 'OK'){
            if(isset($valornovaparcela)){
                $idparcelaformapagamentonew = $ngFinan->novaParcelaMovimento($dadosBaixa);
                $_POST['idparcelaformapagamentonew'] = $idparcelaformapagamentonew ;
                $ngFinan->novaParcelaNG($dadosBaixa);
            }
        }
        else{
            $ngFinan->excluirBaixaFormaPagamento($_POST['idparcelaformapagamento']);
        }
    }
}