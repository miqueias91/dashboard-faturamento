<?php
    include_once("./verifica.php");
	include_once("./config/config.php");
	include_once("./class/class.destinatario.php");

  	$des = new Destinatario();

  	$grupo = array_unique ($grupo);
  	$grupo = array_filter ($grupo);  

  	$telefone = str_replace("(", "", $telefone);
    $telefone = str_replace(")", "", $telefone);
    $telefone = str_replace("-", "", $telefone);
    $telefone = str_replace(" ", "", $telefone);

    if (empty($telefone)) {
		echo "<script>alert('Não foi possível alterar, dado(s) incompleto(s).'); window.location.href = './edit_contato.php?id_destinatario=$id_destinatario&token_user=$token_user';</script>";
	}

	$des->alterarDestinatario($id_destinatario, $nome_destinatario, $email_destinatario, $grupo, $token_user, $telefone);

	echo "<script>alert('Dados alterados com sucesso.'); window.location.href = './form_config_contato.php';</script>";
	die;