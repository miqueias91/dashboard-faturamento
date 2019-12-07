<?php
    include_once("./config/config.php");
    include_once("$base/class/class.mascara.php");
    include_once("$base/class/class.contrato.php");

    $m = new Mascara();
    $cont = new Contrato();


    $dados['tipomovimento'] = 'despesa';
    $dados['idcliente'] = $id_cliente;
    $dados['datacadastro'] = date('Y-m-d H:i:s');
    $dados['datainicio'] = $m->InMascaraData($data_inicio);
	$dados['datafinal'] = $m->InMascaraData($data_final);
	$dados['compinicial'] = $compinicial;
	$dados['numparcela'] = $numparcela;
	$dados['valorparcela'] = number_format($valorparcela,2);
	$dados['diavencimento'] = $diavencimento;
	$dados['observacao'] = $observacao;
	$dados['nomeanexo'] = NULL;
	$dados['enderecoanexo'] = NULL;
	$dados['status_contrato'] = $status_contrato;
	$dados['contrato_antigo'] = NULL;
	$dados['tipo_contrato'] = $tipo_contrato;
	$dados['tipopagamento'] = $tipo_pagamento;
	$dados['idconfigarquivobancario'] = $config_boleto ? $config_boleto : NULL;
    $dados['diavencimentodesconto'] = $diavencimentodesconto;
    $dados['idservico'] = $id_servico;
    
    $dados['iddescontotipo'] = $iddescontotipo;
    $dados['persiste'] = $persiste;
    $dados['percentualutilizado'] = $percentualutilizado;
    $dados['valordesconto'] = $valordesconto;
    $dados['compdesconto'] = $compdesconto;

    if (empty($id_contrato)) {
        $idcontrato = $cont->cadastraContrato($dados);

        if ($dados['iddescontotipo']) {
            foreach ($dados['iddescontotipo'] as $key => $value) {
                $cont->cadastraDescontoContrato($idcontrato, $value, $dados['valordesconto'][$key], $dados['compdesconto'][$key]);
            }
        }

        echo "<script>
                alert('Contrato cadastro com sucesso.'); 
                window.location.href = 'viewGerenciarContrato.php';
        </script>";
        die;
    }
    else{
        $dados['id_contrato'] = (int)$id_contrato;
        $cont->alteraContrato($dados);
        
        if ($dados['iddescontotipo']) {
            foreach ($dados['iddescontotipo'] as $key => $value) {
                $cont->cadastraDescontoContrato($id_contrato, $value, $dados['valordesconto'][$key], $dados['compdesconto'][$key]);
            }
        }

        echo "<script>
                alert('Contrato alterado com sucesso.'); 
                window.location.href = 'viewGerenciarContrato.php';
        </script>";
        die;
    }