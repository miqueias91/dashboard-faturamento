<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.configuracaoArquivo.php");

    $config = new ConfiguracaoArquivo();

if( isset($buscaConfiguracaoArquivo) && $buscaConfiguracaoArquivo == 'sim'){
    $dados = $config->buscaConfiguracaoArquivo();
    $options = "";
    if ($dados) {
        foreach ($dados as $row) {
            $options .= "<option value='$row[id_configarquivobancario]'>$row[descricaoconfig]</option>\\n";
        }
    }                  
    else {
        $options .= "<option value=''>Nenhuma configuração encontrada.</option>\\n";
    }  

    echo $options;
}