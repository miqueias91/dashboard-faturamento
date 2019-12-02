<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.servico.php");

    $ser = new Servico();
    $dados = $ser->buscaServico($id_servico);
    $dado = $dados[0];


    if ($dado['status_servico'] == 'ativo') {
        $ativo = 'selected';
        $inativo = '';    
    }
    else{
        $ativo = '';
        $inativo = 'selected';
    }
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
        $( document ).ready(function() {
            $('#salvarAlteracaoServico').click(function(){
                var retorno = true;

                if ($('#descricaoServico').val() == '') {
                    $('#descricaoServico').css('border-color','red');
                    retorno = false;
                }else{
                    $('#descricaoServico').css('border-color','');
                }

                if ($('#statusServico').val() == '') {
                    $('#statusServico').css('border-color','red');
                    retorno = false;
                }else{
                    $('#statusServico').css('border-color','');
                }

                if (!retorno) {
                    alert('Existem campos não preenchidos!');
                }
                else{
                    $("#form").submit();
                    return true;
                }
            });
        });
    </script>
    <?=include_once("./menu.php");?>
    <main role="main" class="container">
      <div class="starter-template">
        <form action='salvarServico.php?id_servico=<?=$id_servico?>' method='post' name='form' class="" id='form' enctype='multipart/form-data'>
        <div class="row">
          <div class="col-md6">
            <button class="btn btn-lg btn-primary btn-block" id="salvarAlteracaoServico" type="button">Salvar alteração</button>
          </div>
        </div>
        </div>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group text-left">
                <label for="descricaoServico">Descrição do serviço</label>
                <input type="text" class="form-control" id="descricaoServico" name="descricaoServico" placeholder="Descrição do serviço" value="<?=$dado[descricao_servico]?>" required>
              </div>          
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="statusServico">Status</label>
                <select class="form-control" id="statusServico" name="statusServico">
                  <option value="ativo" <?=$ativo?>>Ativo</option>
                  <option value="inativo"  <?=$inativo?>>Inativo</option>
                </select>
              </div>          
            </div>
          </div>
        </form>
    </div>
    </main><!-- /.container -->
  </body>
</html>