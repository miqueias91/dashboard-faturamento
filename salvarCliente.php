<?php
    include_once("./verifica.php");
	include_once("./config/config.php");
    include_once("$base/class/class.cliente.php");
    include_once("$base/class/class.mascara.php");

    $cli = new Cliente();
    $m = new Mascara();

    if ($id_cliente) {
	    $_POST['id_cliente'] = $id_cliente;
	    $_POST['cpf_cliente'] =  $m->InMascaraCPF($_POST['cpf_cliente']);
    	$_POST['celular_cliente'] =  $m->InMascaraCelular($_POST['celular_cliente']);
    	$_POST['cep_cliente'] =  $m->InMascaraCEP($_POST['cep_cliente']);
    	$_POST['data_nascimento_cliente'] = $m->InMascaraData($_POST['data_nascimento_cliente']);

	    $cli->alteraCliente($_POST);

	    if ($id_representante_financeiro_cliente > 0) {
	    	$cli->alterarResponsavelFinanceiroCliente($_POST);
	    }
	    
	    echo "<script>
				alert('Cliente alterado com sucesso.'); 
				window.location.href = 'viewClientes.php';
		</script>";
		die;
    }
    else{ 
    	$_POST['cpf_cliente'] =  $m->InMascaraCPF($_POST['cpf_cliente']);
    	$_POST['celular_cliente'] =  $m->InMascaraCelular($_POST['celular_cliente']);
    	$_POST['cep_cliente'] =  $m->InMascaraCEP($_POST['cep_cliente']);
    	$_POST['data_nascimento_cliente'] = $m->InMascaraData($_POST['data_nascimento_cliente']);

	    $idcliente = $cli->cadastraCliente($_POST);

	    if ($id_representante_financeiro_cliente > 0) {
	    	$cli->alterarResponsavelFinanceiroCliente($_POST);
	    }
	    else{
	    	$_POST['id_cliente'] = $idcliente;
	    	$_POST['id_representante_financeiro_cliente'] = $idcliente;
	    	$cli->alterarResponsavelFinanceiroCliente($_POST);
	    }

		echo "<script>
				alert('Cliente cadastro com sucesso.'); 
				window.location.href = 'viewClientes.php';
		</script>";
		die;
    }
