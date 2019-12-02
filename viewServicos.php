<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.servico.php");

    $ser = new Servico();

    $dados = $ser->buscaServico();


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
        function editarServico(id_servico) {
            $("#form").attr('action','editarServico.php?id_servico='+id_servico);
            $("#form").submit();
            return true;
        }
        $( document ).ready(function() {
            $('#novoServico').click(function(){
                $("#form").attr('action','cadastrarServico.php');
                $("#form").submit();
                return true;
            });
        });
    </script>
    <?=include_once("./menu.php");?>
    <main role="main" class="container">

      <div class="starter-template">
        <form method=post name='form' id='form' enctype='multipart/form-data' action="cadastrarServico.php">
            <div class="row">
              <div class="col-md6">
                <button class="btn btn-lg btn-primary btn-block" id="novoServico" type="button" href="#">Cadastar novo serviço</button>
              </div>
            </div>
            <br>
            <table class="table">
            <thead>
              <tr>
                <td align="Center" scope="col">#</td>
                <td scope="col">AÇÕES</td>
                <td align="Center" scope="col">CÓDIGO</td>
                <td scope="col">DESCRIÇÃO</td>
                <td align="Center" scope="col">STATUS</td>
              </tr>
            </thead>
            <tbody>
                <?php
                    if ($dados) {
                        foreach ($dados as $key => $row) {
                ?>
                          <tr>
                            <td align="Center" scope="row"><?=($key+1)?></td>
                            <td>  
                              <a class="navbar-brand text-Center" href="#" onclick="editarServico('<?=$row['id_servico']?>')">[Editar]</a>
                            </td>
                            <td align="Center"><?=str_pad($row['id_servico'],7,'0', STR_PAD_LEFT)?></td>
                            <td><?=ucwords($row['descricao_servico'])?></td>
                            <td align="Center"><?=ucwords($row['status_servico'])?></td>
                          </tr>                          
                    <?php
                        }
                    }
                    else{
                ?>
                    <tr>
                        <td align="Center" colspan="5">Nenhum serviço encontrado.</td>
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