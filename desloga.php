<?php 
include_once("./config/config.php");
//Inicia a sessão
session_start();
//Elimina os dados da sessão
unset($_SESSION['token_user']);
unset($_SESSION['usuario']);

?>
<script >
	window.location.href = './';
</script>

