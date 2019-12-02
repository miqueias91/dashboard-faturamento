<?php
    include_once("./verifica.php");
	include_once("./config/config.php");
	include_once("./class/class.destinatario.php");

  	$des = new Destinatario();

	$des->excluirDestinatario($token_user, $id_destinatario);

	echo "<script>alert('Registro excluido com sucesso.'); window.location.href = './form_config_contato.php';</script>";

	die;