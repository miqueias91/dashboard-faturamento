<?php
include_once("PHPMailer_v5.1/class.phpmailer.php");
class Email extends PHPMailer{
	var $remetente;
	var $texto ;
	var $assunto;
	var $destinatario;
	var $remetentePadrao;
	var $meusdestinatarios = '';
	var $meusBCCdestinatarios = '';
	var $meusCCdestinatarios = '';

	/**
	 * Inicia a classe, seleciona o remetente padrao
	 *
	 * @param $remetente 1=> sind 2=> sicof
	 */
	function __construct($remetente = 1){
		$this->remetente 		= '';
		$this->texto			= '';
		$this->assunto			= '';
		$this->destinatario		= '';
		$this->remetentePadrao 	= $remetente;
	}
	public function AddAddress($address, $name = '') {
    	return $this->AddAnAddress('to', $address, $name);
  	}
	public function AddCC($destinatario, $name = ''){
		return $this->AddAnAddress('cc', $destinatario, $name);
	}
	public function AddBCC($destinatario, $name = ''){
		return $this->AddAnAddress('bcc', $destinatario, $name);
	}
	public function enviaEmail( $remetente, $senha, $destinatario, $assunto, $texto ){
        //$texto = utf8_encode($texto);
		$this->destinatario		= $destinatario ;
		$this->remetente		= $remetente ;
		$this->texto			= $texto ;
		$this->assunto			= $assunto ;
		#Configura email
		$email = $remetente;
		$senha = $senha;

		if($destinatario != '' && $destinatario != null){
			$destinatario = $destinatario.',';
		}
		$this->meusBCCdestinatarios = $this->meusBCCdestinatarios == '' ? '' : 'Bcc: '.$this->meusBCCdestinatarios."\r\n";
		$this->meusCCdestinatarios = $this->meusCCdestinatarios == '' ? '' : 'Cc: '.$this->meusCCdestinatarios."\r\n";

		#Definimos o envio via SMTP

		$this->IsSMTP();

		#Configuramos a conexão ao SMTP
		$this->Host = "smtp.gmail.com";
		$this->SMTPAuth = true;
        $this->Port=465;
		$this->SMTPSecure = 'ssl';

		#Configuramos o login e senha de conexão a conta SMTP
		$this->Username = $remetente;
		$this->Password = $senha;



		#Definimos o remetente
		$this->From = $this->remetente;

		#Definimos o nome do remetente
		$this->FromName = $remetente;

		#Definimos o assunto do e-mail
		$this->Subject = $this->assunto;

		#Definimos a mensagem do e-mail
		$this->Body = $this->texto;


		#Checamos se a mensagem foi enviada ou se teve algum erro...
		if(!$this->Send()) {
			echo "Erro: " . $this->ErrorInfo;
			return false;
		}
		else {
			return true;
		}
	}
}