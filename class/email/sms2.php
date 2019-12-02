<?php
include_once("PHPMailer_v5.1/class.phpmailer.php");
class sms{
	
	public function Envia_Sms($ddd, $fone, $remetente, $texto, $assunto){
		#Configura email
		$email="hdsonsilva@gmail.com";
		$senha="lisa1002";
		
		#Carregamos a biblioteca
		#Instanciamos a classe
		$mail = new PHPMailer();
		
		#Definimos o envio via SMTP
		$mail->IsSMTP();
		
		#Configuramos a conexão ao SMTP 
		$mail->Host = "smtp.gmail.com";
		$mail->SMTPAuth = true;
		$mail->Port=465;
		$mail->SMTPSecure = 'ssl' ;
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
		//$from=$ddd.$fone."@doctum.edu.br";
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

$sms = new sms();
$sms->Envia_Sms("33","84226106","Hudson","Isso é um teste","Teste");
?>
