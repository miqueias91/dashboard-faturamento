<?php
if ($_GET["func"]=="envia"){
	include_once "sms.php";
	$sms = new sms();
	$sms->Envia_Sms($_POST["ddd"], $_POST["fone"], $_POST["remetente"], $_POST["mensagem"], $_POST["assunto"]);
}else{
?>
<html>
<head>
<title>Enivia SMS Claro</title>
</head>
<body>
<div align="center">
  <form id="form" name="form" method="post" action="?func=envia">
    <p>Remetente:
  <input type="text" name="remetente" />
      <br /><br />DDD: <input type="text" name="ddd" size="4" maxlength="3" />&nbsp;
      Telefone: <input type="text" name="fone" size="10" maxlength="9" />
      <br /><br />Assunto: <input type="text" name="assunto"  />
      <br /><br />Mensagem:<br />
      <textarea name="mensagem" cols="45" rows="5"></textarea>
    </p>
    <p>
      <input type="submit" name="button" id="button" value="Enviar" />
    </p>
  </form>
</div>
</body>
</html>
<?php
}
?>