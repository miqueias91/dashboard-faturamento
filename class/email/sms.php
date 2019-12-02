<?php
class sms{
	
	public function Envia_Sms($ddd, $fone, $remetente, $texto, $assunto){
		#Configura email
		$email="seu_email@dominio.com.br";
		$senha="sua_senha";
		
		#Carregamos a biblioteca
		include_once("PHPMailer_v5.1\class.phpmailer.php");
		
		#Instanciamos a classe
		$mail = new PHPMailer();
		
		#Definimos o envio via SMTP
		$mail->IsSMTP();
		
		#Configuramos a conexão ao SMTP 
		$mail->Host = "smtp.dominio.com.br";
		$mail->SMTPAuth = true;
		$mail->Port=25;
		
		#Configuramos o login e senha de conexão a conta SMTP
		$mail->Username = $email;
		$mail->Password = $senha;
		
		#Definimos o remetente		
		$mail->From = $email;
		
		#Definimos o nome do remetente
		$mail->FromName = $remetente;
		
		#Definimos o destinatário
		$ddd=str_replace("0","",$ddd);
		$fone=str_replace("-","",$fone);
		$from=$ddd.$fone."@clarotorpedo.com.br";
		$mail->AddAddress($from);
		
		#Definimos o assunto do e-mail
		$mail->Subject = $assunto;
		
		#Definimos a mensagem do e-mail
		$mail->Body = $texto;
		
		#Checamos se a mensagem foi enviada ou se teve algum erro...
		if(!$mail->Send()) {
			echo "Erro: " . $mail->ErrorInfo;
		}
		else {
			echo "Sms enviado!";
		}
	}
}
?>
