<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.cliente.php");
    include_once("$base/class/class.mascara.php");

    $cli = new Cliente();
    $m = new Mascara();

    $dados = $cli->buscaResponsavelFinanceiroCliente($term);

	if($dados){
		$i = 0;
		foreach ($dados as $row){
			$dados[$i]['id'] = $row['id_cliente'];
	        $dados[$i]['value'] = $m->OutMascaraCPF($row['cpf_cliente'])." | ".$row['nome_cliente'];
			$i++;
		}
		echo json_encode($dados);
	}	