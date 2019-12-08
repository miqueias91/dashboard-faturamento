<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.contrato.php");
    include_once("$base/class/class.mascara.php");

    $cont = new Contrato();
    $m = new Mascara();

    $dados = $cont->buscaContrato();


?>

<html lang="pt-br">
    <head>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Página Inicio">
        <meta name="author" content="Miqueias Matias Caetano">
        <meta name="keywords" content="Página Inicio">
        <meta content="pt-br, pt, en" http-equiv="Content-Language">
        <meta name="revised" content="2019-12-29">


        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?=$PATH_CSS;?>/bootstrap.min.css">
        <link rel="stylesheet" href="./fontawesome-free-5.6.3-web/css/all.css">

        <!-- Optional Google Fonts -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
        <link rel="stylesheet" href="<?=$PATH_CSS;?>/stylesheet.css">

        <!-- Optional JavaScript -->
        <script src="<?=$PATH_JS;?>/jquery-3.4.1.min.js"></script>
        <script src="<?=$PATH_JS;?>/popper.min.js"></script>
        <script src="<?=$PATH_JS;?>/bootstrap.min.js"></script>
  </head>
  <body>
    <script type="text/javascript">
        function cadastrarContrato(id_contrato) {
            $("#form").attr('action','cadastrarContrato.php?id_contrato='+id_contrato);
            $("#form").submit();
            return true;
        }
        $( document ).ready(function() {
            $('#filtros').click(function(){
                return true;
            });
        });
    </script>
    <?=include_once("./menu.php");?>
    <main role="main" class="container">

      <div class="starter-template">
        <form method=post name='form' id='form' enctype='multipart/form-data' action="cadastrarContrato.php">
            <div class="row">
              <div class="col-md6">
                <button class="btn btn-lg btn-primary btn-block" id="filtros" type="button" href="#"><i class="fas fa-plus-circle"></i> Filtros</button>
              </div>
            </div>
            <br>
            <table class="table">
            <thead>
              <tr style="background: #212529;color: #fff;">
                <td align="center" scope="col">#</td>
                <td align="center" scope="col">AÇÕES</td>
                <td align="center" scope="col">CÓDIGO</td>
                <td width="150" scope="col">CPF DO CLIENTE</td>
                <td width="200" align="left" scope="col">NOME DO CLIENTE</td>
                <td align="center" scope="col">DATA DO CADASTRO</td>
                <td align="center" scope="col">DATA DE INICIO</td>
                <td align="center" scope="col">DATA DE TERMINO</td>
                <td align="center" scope="col">VALOR DA PARCELA</td>
                <td align="center" scope="col">STATUS</td>
              </tr>
            </thead>
            <tbody>
                <?php
                    if ($dados) {
                        foreach ($dados as $key => $row) {
                ?>
                          <tr>
                            <td align="center" scope="row"><?=($key+1)?></td>
                            <td align="center">
                                <span style="cursor: pointer;" align="center" title="Gerenciar contrato" class="text-center" onclick="cadastrarContrato('<?=$row['id_contrato']?>')"><i style="font-size: 12px" class="fas fa-pen-square"></i></span>                              
                            </td>
                            <td align="center"><?=str_pad($row['id_contrato'],7,'0', STR_PAD_LEFT)?></td>
                            <td width="150"><?=$m->OutMascaraCPF($row['cpf_cliente'])?></td>
                            <td width="200" align="left"><?=ucwords(strtolower($row['nome_cliente']))?></td>
                            <td align="center"><?=date('d/m/Y', strtotime($row['datacadastro']))?></td>
                            <td align="center"><?=date('d/m/Y', strtotime($row['datainicio']))?></td>
                            <td align="center"><?=date('d/m/Y', strtotime($row['datafinal']))?></td>
                            <td align="center">R$ <?=number_format($row['valorparcela'],2,",","")?></td>
                            <td align="center"><?=ucwords($row['status_contrato'])?></td>
                          </tr>                          
                    <?php
                        }
                    }
                    else{
                ?>
                    <tr>
                        <td align="center" colspan="10">Nenhum contrato encontrado.</td>
                    </tr>
                <?php                        
                    }
                ?>
            </tbody>
          </table>
        </form>
    </div>
    </main><!-- /.container -->






  </body>
</html>