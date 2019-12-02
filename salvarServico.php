<?php
    include_once("./verifica.php");
	include_once("./config/config.php");
    include_once("$base/class/class.servico.php");

    $ser = new Servico();

    if ($id_servico) {
    	$dados['descricao_servico'] = ucwords($descricaoServico);
	    $dados['status_servico'] = $statusServico;
	    $dados['id_servico'] = $id_servico;
	    
	    $ser->alteraServico($dados);
	    
	    echo "<script>
				alert('Serviço alterado com sucesso.'); 
				window.location.href = 'viewServicos.php';
		</script>";
		die;
    }
    else{    	
	    $dados['descricao_servico'] = ucwords($descricaoServico);
	    $dados['status_servico'] = $statusServico;

	    $ser->cadastraServico($dados);

		echo "<script>
				alert('Serviço cadastro com sucesso.'); 
				window.location.href = 'viewServicos.php';
		</script>";
		die;
    }
