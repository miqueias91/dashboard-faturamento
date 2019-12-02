<?php
header('Access-Control-Allow-Origin: *');  
header("Content-type: text/json");

include_once("../config/config.php");
include_once("../class/class.acess_ws.php");

sleep(1);

$usuario = isset($_POST['username']) ? $_POST['username'] : null ;
$token_user = isset($_POST['token_user']) ? $_POST['token_user'] : null ;

//VERIFICO SE A ACTION ESTA VAZIA
if(empty($_POST['action'])) {
    echo json_encode(array('return' => 'NENHUMA ACAO INFORMADA'));
    die;
}
//SE A ACTION FOR LOGIN, VERIFICO SE O USUARIO E TOKEN FORAM INFORMADOS
if($_POST['action'] == 'LOGIN'){
    if ($usuario && $token_user) {
        $classtoken = new acessWS();
        $usuario = filter_var($usuario, FILTER_SANITIZE_STRING);
        $token_user = filter_var($token_user, FILTER_SANITIZE_STRING);
        $token = $classtoken->loginSystemWs($usuario, $token_user);
        if (sizeof($token) == 1) { 
            echo json_encode(array('token' => $token[0]['token_user']));
        } 
        else {
            echo json_encode(array('return' => 'CREDENCIAIS INVALIDAS'));
            die;            
        }
    }
    else{
        echo json_encode(array('return' => 'INFORME USUARIO E TOKEN'));
        die;
    }
}
//SE A ACTION FOR LOGIN, VERIFICO SE O USUARIO E TOKEN FORAM INFORMADOS
if($_POST['action'] == 'NEWSLETTER'){
    if ($usuario && $token_user) {
        $classtoken = new acessWS();
        $usuario = filter_var($usuario, FILTER_SANITIZE_STRING);
        $token_user = filter_var($token_user, FILTER_SANITIZE_STRING);
        $token = $classtoken->loginSystemWs($usuario, $token_user);
        if (sizeof($token) == 1) {


            include_once("../class/class.destinatario.php");
            $des = new Destinatario();
            $nome_destinatario = filter_var($_POST['dado']['nome'], FILTER_SANITIZE_STRING);
            $email_destinatario = filter_var($_POST['dado']['email'], FILTER_SANITIZE_EMAIL);
            $grupo[] = filter_var($_POST['dado']['grupo'], FILTER_SANITIZE_STRING);
            $telefone = filter_var($_POST['dado']['telefone'], FILTER_SANITIZE_NUMBER_INT);
            $telefone = str_replace("(", "", $telefone);
            $telefone = str_replace(")", "", $telefone);
            $telefone = str_replace("-", "", $telefone);
            $telefone = str_replace(" ", "", $telefone);

            $des->cadastraDestinatario($nome_destinatario, $email_destinatario, $grupo, $token_user, $telefone);
            echo json_encode(array('return' => 'EMAIL CADASTRADO COM SUCESSO'));
        } 
        else {
            echo json_encode(array('return' => 'CREDENCIAIS INVALIDAS'));
            die;            
        }
    }
    else{
        echo json_encode(array('return' => 'INFORME USUARIO E TOKEN'));
        die;
    }
}