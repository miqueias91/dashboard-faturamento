<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.cliente.php");
    include_once("$base/class/class.mascara.php");

    $cli = new Cliente();
    $m = new Mascara();

    $dados = $cli->buscaCliente();


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
        function editarCliente(id_cliente) {
            $("#form").attr('action','editarCliente.php?id_cliente='+id_cliente);
            $("#form").submit();
            return true;
        }
        $( document ).ready(function() {
            $('#novoCliente').click(function(){
                $("#form").attr('action','cadastrarCliente.php');
                $("#form").submit();
                return true;
            });
        });
    </script>
    <?=include_once("./menu.php");?>
    <main role="main" class="container">

      <div class="starter-template">
        <form method=post name='form' id='form' enctype='multipart/form-data' action="cadastrarCliente.php">
            <div class="row">
              <div class="col-md6">
                <button class="btn btn-lg btn-primary btn-block" id="novoCliente" type="button" href="#"><i class="fas fa-plus-circle"></i> Filtros</button>
              </div>
            </div>
            <br>
            <table class="table">
            <thead>
              <tr>
                <td align="center" scope="col">#</td>
                <td align="center" scope="col">AÇÕES</td>
                <td align="center" scope="col">CÓDIGO</td>
                <td width="200" scope="col">CPF DO CLIENTE</td>
                <td scope="col">NOME DO CLIENTE</td>
                <td scope="col">DATA DO CADASTRO</td>
                <td scope="col">DATA DE INICIO</td>
                <td scope="col">DATA DE TERMINO</td>
                <td scope="col">VALOR DO CONTRATO</td>
                <td align="Center" scope="col">STATUS</td>
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
                              <a class="navbar-brand text-center" href="#" onclick="editarCliente('<?=$row['id_cliente']?>')">[Gerenciar]</a>
                            </td>
                            <td align="center"><?=str_pad($row['id_cliente'],7,'0', STR_PAD_LEFT)?></td>
                            <td width="200"><?=$m->OutMascaraCPF($row['cpf_cliente'])?></td>
                            <td><?=ucwords(strtolower($row['nome_cliente']))?></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"><?=ucwords($row['status_cliente'])?></td>
                          </tr>                          
                    <?php
                        }
                    }
                    else{
                ?>
                    <tr>
                        <td align="Center" colspan="6">Nenhum cliente encontrado.</td>
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