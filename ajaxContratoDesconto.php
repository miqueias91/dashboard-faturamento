<?php
    include_once("./config/config.php");

if( isset($verificaLinhasDescontos) && $verificaLinhasDescontos == 'sim'){
    //AGRUPO OS ARRAYS POR COMPETENCIAS
    foreach ($vetorDadosDescontos as $i => $row) {
        $cadaLinhaComp[$row['compdesconto']][$i]['descontotipo'] = $row['descontotipo'];
        $cadaLinhaComp[$row['compdesconto']][$i]['valordesconto'] = $row['valordesconto'];
        $cadaLinhaComp[$row['compdesconto']][$i]['valorparcela'] = $row['valorparcela'];
        $cadaLinhaComp[$row['compdesconto']][$i]['percentualmaximo'] = $row['percentualmaximo'];
        $cadaLinhaComp[$row['compdesconto']][$i]['compdesconto'] = $row['compdesconto'];
    }

    foreach ($cadaLinhaComp as $j => $cadaLinha) {
        $porcentagem = 0;
        $valordesconto = 0;
        $valorparcela = 0;
        $valorMaximoDesconto = '';
        $descontotipoRepetido = '';

        foreach ($cadaLinha as $k => $cada) {
            //FAÇO O SOMATORIO DOS VALORES DOS DESCONTOS DAS COMPETENCIAS
            $valordesconto += $cada['valordesconto'];
            $valorparcela = $cada['valorparcela'];

            //SOMO O TOTAL DAS PORCENTAGEM DOS DESCONTOS
            $porcentagem += (number_format($cada['valordesconto'],2) * 100) / number_format($cada['valorparcela'],2);

            //ARMAZENO OS TIPOS DE DESCONTOS DE CADA COMPETENCIA
            $cadadescontotipo[$j][] = $cada['descontotipo'];
        }

        foreach ($cadadescontotipo as $key => $value) {
            //SE NÃO RETORNAR VAZIO, SIGINIFICA QUE TEM TIPO DESCONTO REPETIDO
            if(!empty(array_unique(array_diff_assoc($value, array_unique($value))))){
                echo $descontotipoRepetido = utf8_decode('Atenção! Existem tipos de descontos repetidos para a mesma competência.');
                die;
            }
        }

        //VERIFICO SE O VALOR TOTAL DOS DESCONTOS DAS COMPETENCIAS É MAIOR QUE O VALOR DO CONTRATO 
        if (number_format($valordesconto,2) > number_format($valorparcela,2)) {
            echo $valorMaximoDesconto = utf8_decode('Atenção! O valor total de desconto não pode ser superior ao valor do contrato.');
            die;
        }
    }
}