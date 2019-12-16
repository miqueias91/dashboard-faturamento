<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.contrato.php");
    include_once("$base/class/class.mascara.php");

    include_once("$base/class/class.financeiro.php");
    include_once("$base/class/class.faturamentoBoleto.php");
    include_once("$base/class/class.faturamentoBoletoSicoob.php");
    include_once("$base/class/class.configuracaoArquivo.php");
   
    $m = new Mascara();
    $fin = new Financeiro();
    $bol = new FaturamentoBoleto();

    $dadosbto = $bol->buscaParcelaFormaPagamentoBoleto($idboleto);
    
    //Retorna o nome do banco para concatenar no a url
    $nomeBanco = $bol->nomeBanco($dadosbto[0]['codigobanco']);
    
    include_once("./imprimirBoleto_$nomeBanco.php");
