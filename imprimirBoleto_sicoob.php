<?php    
    include_once("$base/class/class.faturamentoBoletoSicoob.php");
    include_once("$base/class/class.configuracaoArquivo.php");
    
    $sicoob = new FaturamentoBoletoSicoob();
    $arq = new ConfiguracaoArquivo();

    $idparcela_boleto = explode("||", $idboleto);
    $idparcela_boleto = array_filter($idparcela_boleto);  
    

    include_once("$base/header_layout_sicoob.php");

    $total_boletos = sizeof($idparcela_boleto);
    $i = 1;
    foreach ($idparcela_boleto as $idparcela) {
        //Busca dados forma pagamento
        $dadosFormaPagamento = $fin->buscaMovimentoParcelaFormaPagamento($idparcela);



        //Busca dados boleto
        $dados_boleto = $bol->buscaParcelaFormaPagamentoBoleto(null, $dadosFormaPagamento[0]['id_movimento_parcelaformapagamento']);
          
        $configboleto = $dados_boleto[0]['idconfigarquivobancario'];
        $idmovimentoformapagamento = $dados_boleto[0]['id_movimento_parcelaformapagamento'];
        $nosso_numero = $dados_boleto[0]['nosso_numero'];

        $aux_emissao = explode('-', $dados_boleto[0]['dataemissao']);
        $dataemissao_movimento = $aux_emissao[2]."/".$aux_emissao[1]."/".$aux_emissao[0];

        //Busco a configuração do banco
        $dadosBanco = $arq->buscaConfiguracaoArquivo($configboleto, 'todos');

        // DADOS DO BOLETO PARA O CLIENTE
        $agencia = $dadosBanco[0]['numeroagencia'];// Num da agencia, sem digito
        $conta = substr($dadosBanco[0]['numeroconta'], 0, -1); // Num da conta, sem digito
        $convenio = $dadosBanco[0]['codigocedente']; //Número do convênio indicado no frontend
        
        //Busca dados parcela
        $dadosParcela = $fin->buscaMovimentoParcela($idparcela);
        $aux_venc = explode('-', $dadosParcela[0]['vencimentooriginal']);
        $datavencimento_movimento = $aux_venc[2]."/".$aux_venc[1]."/".$aux_venc[0];


        //Busca dados do desconto que persiste
        $dadosDescontoPersiste = $fin->buscaMovimentoParcelaFormaPagamentoDesconto($idparcela, 'sim');
        $valor_persiste = 0;
        if ($dadosDescontoPersiste) {
            foreach ($dadosDescontoPersiste as $row) {
                $valor_persiste += $row['valordesconto'];
            }
        }

        //Busca dados do desconto que não persiste
        $dadosDescontoNaoPersiste = $fin->buscaMovimentoParcelaFormaPagamentoDesconto($idparcela, 'nao');
        $valor_naopersiste = 0;
        if ($dadosDescontoNaoPersiste) {
            $aux_datadesconto = $dadosDescontoNaoPersiste[0]['datadesconto'];
            $aux_datadesconto = explode('-', $aux_datadesconto);
            $datadesconto = $aux_datadesconto[2].'/'.$aux_datadesconto[1].'/'.$aux_datadesconto[0];

            foreach ($dadosDescontoNaoPersiste as $row) {
                $valor_naopersiste += $row['valordesconto'];
            }       
        }


        $dadosFormaPagamento[0]['valor'] = $dadosFormaPagamento[0]['valor'] - $valor_persiste;

        $valor_cobrado_desconto = 0;
        $valor_cobrado_desconto = $dadosFormaPagamento[0]['valor'] - $valor_naopersiste;

        if ($valor_cobrado_desconto < '0.00') {
            $valor_cobrado_desconto = '0.00';
        }

        $valor_cobrado_desconto = $valor_cobrado_desconto;
        $valor_cobrado_desconto = str_replace(",", ".",$valor_cobrado_desconto);
        $valor_cobrado_desconto=number_format($valor_cobrado_desconto, 2, ',', '');

        $dias_de_prazo_para_pagamento = 7;
        $taxa_boleto = 0;
        $valormovimento = str_replace(".", ",",$dadosFormaPagamento[0]['valor']);
        $valor_cobrado = $valormovimento;
        if ($valor_cobrado < '0.00') {
            $valor_cobrado = '0.00';
        }
        $valor_cobrado = str_replace(",", ".",$valor_cobrado);
        $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

        //SICOOB
        $dadosboleto["nosso_numero"] = $nosso_numero;
        $dadosboleto["numero_documento"] = $dados_boleto[0]['numerodocumento'];    // Num do pedido ou do documento
        $dadosboleto["data_vencimento"] = $datavencimento_movimento;
        $dadosboleto["data_documento"] = $dataemissao_movimento;
        $dadosboleto["data_processamento"] = $dataemissao_movimento;
        $dadosboleto["valor_boleto"] = $valor_boleto;


        // DADOS DO SEU CLIENTE
        if (!empty($dadosFormaPagamento[0]['id_representante_financeiro_cliente'])) {
            $dadosboleto["sacado"] = strtoupper($dadosFormaPagamento[0]['nome_rep']);
            $dadosboleto["endereco1"] = $dadosFormaPagamento[0]['logradouro_rep'].", ".$dadosFormaPagamento[0]['numero_rep']." ".$dadosFormaPagamento[0]['complemento_rep'].", ".$dadosFormaPagamento[0]['bairro_rep'];

            $dados_cep = "";
            if ($dadosFormaPagamento[0]['cep_rep']) {
                $dados_cep = " -  CEP: ".$m->OutMascaraCEP($dadosFormaPagamento[0]['cep_rep']);
            }

            $dadosboleto["endereco2"] = $dadosFormaPagamento[0]['cidade_rep']." - ".$dadosFormaPagamento[0]['estado_rep'].$dados_cep;
        }
        else{
            $dadosboleto["sacado"] = strtoupper($dadosFormaPagamento[0]['nome_cliente']);
            $dadosboleto["endereco1"] = $dadosFormaPagamento[0]['logradouro_cliente'].", ".$dadosFormaPagamento[0]['numero_cliente']." ".$dadosFormaPagamento[0]['complemento_cliente'].", ".$dadosFormaPagamento[0]['bairro_cliente'];

            $dados_cep = "";
            if ($dadosFormaPagamento[0]['cep_cliente']) {
                $dados_cep = " -  CEP: ".$m->OutMascaraCEP($dadosFormaPagamento[0]['cep_cliente']);
            }

            $dadosboleto["endereco2"] = $dadosFormaPagamento[0]['cidade_cliente']." - ".$dadosFormaPagamento[0]['estado_cliente'].$dados_cep;

        }

        // INFORMACOES PARA O CLIENTE
        $dadosboleto['descricaohistorico'] = $dadosFormaPagamento[0]['descricaohistorico']; 
        
        if ($valor_naopersiste) {
            $dadosboleto["demonstrativo1"] = "Pagamento at&eacute; o dia $datadesconto, receber valor de R$ $valor_cobrado_desconto";
        }
        else{
            $dadosboleto["demonstrativo1"] = "Pagamento at&eacute; o dia $datavencimento_movimento, receber valor de R$ $valor_boleto";            
        }
        $dadosboleto["demonstrativo2"] = "Ap&oacute;s vencimento, multa de ".str_replace('.', ',', $dadosBanco[0]['multavencimento'])."% e juros mensal de ".str_replace('.', ',', $dadosBanco[0]['jurosmensal'])."%.";
        $dadosboleto["demonstrativo3"] = "Este boleto &eacute; registrado junto &agrave; ag&eacute;ncia banc&aacute;ria. Evite transtornos, pague-o 24h ap&oacute;s sua emiss&atilde;o";

        // INSTRUÇÕES PARA O CAIXA
        // $dadosboleto["instrucoes1"] = "- Sr. Caixa, n&atilde;o Receber o Boleto ap&oacute;s o vencimento.";
        $dadosboleto["instrucoes1"] = isset($dadosboleto["instrucoes1"]) ? $dadosboleto["instrucoes1"] : NULL;
        $dadosboleto["instrucoes2"] = isset($dadosboleto["instrucoes2"]) ? $dadosboleto["instrucoes2"] : NULL;
        $dadosboleto["instrucoes3"] = isset($dadosboleto["instrucoes3"]) ? $dadosboleto["instrucoes3"] : NULL;
        $dadosboleto["instrucoes4"] = isset($dadosboleto["instrucoes4"]) ? $dadosboleto["instrucoes4"] : NULL;

        // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        $dadosboleto["quantidade"] = "01";
        $dadosboleto["valor_unitario"] = $valor_boleto;
        $dadosboleto["aceite"] = "N";       
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = "ME";

        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
        // DADOS ESPECIFICOS DO SICOOB
        $dadosboleto["modalidade_cobranca"] = $dadosBanco[0]['carteira'];
        $dadosboleto["numero_parcela"] = "001";


        // DADOS DA SUA CONTA - BANCO SICOOB
        $dadosboleto["agencia"] = $agencia; // Num da agencia, sem digito
        $dadosboleto["conta"] = $conta; // Num da conta, sem digito

        // DADOS PERSONALIZADOS - SICOOB
        $dadosboleto["convenio"] = $convenio; // Num do convênio - REGRA: No máximo 7 dígitos
        $dadosboleto["carteira"] = substr($dadosBanco[0]['carteira'],-1);

        // SEUS DADOS
        $dadosboleto["identificacao"] = $dadosBanco[0]['nomecedente'];
        $dadosboleto["cpf_cnpj"] = $m->OutMascaraCNPJ($dadosBanco[0]['cnpjcedente']);
        $dadosboleto["endereco"] ='Endereço da sua empresa';
        $dadosboleto["cidade_uf"] = "Cidade da sua empresa / Estado da sua empresa";
        $dadosboleto["cedente"] = $dadosBanco[0]['nomecedente'];

        $codigobanco = "756";
        $codigo_banco_com_dv = $sicoob->geraCodigoBanco($codigobanco);
        $nummoeda = "9";
        $fator_vencimento = $sicoob->fator_vencimento($dadosboleto["data_vencimento"]);

        //valor tem 10 digitos, sem virgula
        $valor = $sicoob->formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
        //agencia é sempre 4 digitos
        $agencia = $sicoob->formata_numero($dadosboleto["agencia"],4,0);
        //conta é sempre 8 digitos
        $conta = $sicoob->formata_numero($dadosboleto["conta"],8,0);

        $carteira = $dadosboleto["carteira"];

        //Zeros: usado quando convenio de 7 digitos
        $livre_zeros='000000';
        $modalidadecobranca = $dadosboleto["modalidade_cobranca"];

        $numeroparcela      = $dadosboleto["numero_parcela"];

        $convenio = $sicoob->formata_numero($dadosboleto["convenio"],7,0);

        //agencia e conta
        $agencia_codigo = $agencia ." / ". $convenio;

        // Nosso número de até 8 dígitos - 2 digitos para o ano e outros 6 numeros sequencias por ano 
        // deve ser gerado no programa boleto_bancoob.php
        $nossonumero = $sicoob->formata_numero($dadosboleto["nosso_numero"],8,0);
        $campolivre  = str_pad($modalidadecobranca,2,'0',STR_PAD_LEFT)."$convenio$nossonumero$numeroparcela";

        $dv= $sicoob->modulo_11("$codigobanco$nummoeda$fator_vencimento$valor$carteira$agencia$campolivre");
        $linha="$codigobanco$nummoeda$dv$fator_vencimento$valor$carteira$agencia$campolivre";

        $dadosboleto["codigo_barras"] = $linha;
        $dadosboleto["linha_digitavel"] = $sicoob->monta_linha_digitavel($linha);
        $dadosboleto["agencia_codigo"] = $agencia_codigo;
        $dadosboleto["nosso_numero"] = $nossonumero;
        $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

        include("$base/body_layout_sicoob.php");

        $i++;
    }        
    include_once("$base/footer_layout_sicoob.php");