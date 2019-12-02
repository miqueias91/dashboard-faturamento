<?php
	//Inicia a sessão
	@ session_start();
	extract($_SESSION);

	//Verifica se há dados ativos na sessão
	if(empty($_SESSION["token_user"]) || empty($_SESSION["usuario"])){
		//Caso não exista dados registrados, exige login
		echo "<script language=\"JavaScript\">";
			echo "
			alert('Acesso restrito, faça o login.');
			window.location.href = './index.php';
			";
		echo "</script>";
		exit;
	}