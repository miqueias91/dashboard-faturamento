<?php
	include_once("./config/config.php");
	include_once("./class/class.destinatario.php");

  	$des = new Destinatario();
	$grupo = array_unique ($grupo);
  	$grupo = array_filter ($grupo); 
	$des->cadastraDestinatario($nome_destinatario, $email_destinatario, $grupo, $token_user);

	echo "<script>alert('Dados cadastros com sucesso.'); window.location.href = './insert_contato.php';</script>";
	die;