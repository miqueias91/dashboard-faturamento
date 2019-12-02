<?php
    error_reporting(1);

	include_once("../config/config.php");
	include_once("$base/class/email/email.php");
	include_once("$base/class/class.messenger.php");
    include_once("$base/class/class.destinatario.php");

  	$msn = new Messenger();
    $des = new Destinatario();

    $periodo_data = date("Y-m-d");
    $horario = date("H:i");
    $arrayMessenger = $msn->buscaPeriodoMessenger(null, null, null, $periodo_data, $horario);

    $fp = fopen("$base/log/log.csv","a");
    if ($arrayMessenger) {
        foreach ($arrayMessenger as $value) {


            $arrayDestinatarios = $msn->buscaMessengerDestinatario($value['id_messenger']);
            $mensagem = file_get_contents("$base/$value[mensagem]");

            $assunto = $value['assunto'];
            $assunto = '=?UTF-8?B?'.base64_encode($assunto).'?=';

            foreach ($arrayDestinatarios as $cada_destinatario) {
                $email = new Email();
                $email->AddCustomHeader("Content-type: text/html; charset=iso-8859-1");
                $email->AddAddress($cada_destinatario['email_destinatario']);
                $email->enviaEmail($value['email_config'],$value['chave_config'], $cada_destinatario['email_destinatario'], $assunto, $mensagem);
                $log = $cada_destinatario['email_destinatario'].",".date("Y-m-d").",".date("H:i:s")."\n";

            }
            $msn->inativaMessenger($value['id_messenger']);
        }
    }

    $log = "NULL".",".date("Y-m-d").",".date("H:i:s")."\n";
    fwrite($fp,$log);
    fclose($fp);