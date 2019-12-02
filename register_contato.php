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
		echo "<script>alert('Não foi possível cadastrar o contato, dados incompletos.'); window.location.href = './insert_contato.php';</script>";
	}

	$des->cadastraDestinatario($nome_destinatario, $email_destinatario, $grupo, $token_user, $telefone);

	echo "<script>alert('Contato cadastro com sucesso.'); window.location.href = './insert_contato.php';</script>";
	die;