<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.cliente.php");

    $cli = new Cliente();
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
        <link rel="stylesheet" href="css/jquery-ui.css">

        <!-- Optional Google Fonts -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
        <link rel="stylesheet" href="<?=$PATH_CSS;?>/stylesheet.css">

        <!-- Optional JavaScript -->
        <script src="<?=$PATH_JS;?>/jquery-3.4.1.min.js"></script>
        <script src="<?=$PATH_JS;?>/popper.min.js"></script>
        <script src="<?=$PATH_JS;?>/bootstrap.min.js"></script>
        <script src="<?=$PATH_JS;?>/jquery.mask.js"></script>
        <script src="js/jquery-ui.js"></script>


  </head>
  <body>
    <script type="text/javascript">
        $( document ).ready(function() {

            $('.data').mask('00/00/0000');
            $('.cep').mask('00000-000');
            $('.celular').mask('(00) 00000-0000');
            $('.cpf').mask('000.000.000-00', {reverse: true});
            $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
            $('.cep').mask('99.999-999');



            $('.cep').change(function(){
                var cep = $(this).val();
                cep = cep.replace('.','');
                cep = cep.replace('-','');
                
                $.ajax({
                    url: "https://viacep.com.br/ws/"+cep+"/json/",
                    context: document.body
                }).done(function(resposta) {
                    $('#logradouro_cliente').val(resposta['logradouro']);
                    $('#bairro_cliente').val(resposta['bairro']);
                    $('#cidade_cliente').val(resposta['localidade']);
                    $('#estado_cliente').val(resposta['uf']);
                });
       
            });

            $('#salvarCliente').click(function(){
                var retorno = true;
                if ($('.campo_obrigatorio').val() == '') {
                    retorno = false;
                    alert('Existem campos obrigatórios não preenchidos!');
                }
                else{
                    $("#form").submit();
                    return true;
                }
            });

            $("#representante_financeiro_cliente").autocomplete({
                source: "./json_busca_cliente.php",
                minLength: 1,
                select: function( event, ui ) {
                    $('#id_representante_financeiro_cliente').val(ui.item.id);
                    $(this).val(ui.item.value);
                }
            });
        });
    </script>
    <?=include_once("./menu.php");?>
    <main role="main" class="container">
      <div class="starter-template">
        <form action='salvarCliente.php' method='post' name='form' class="" id='form' enctype='multipart/form-data'>
        <div class="row">
          <div class="col-md6">
            <button class="btn btn-lg btn-primary btn-block" id="salvarCliente" type="button">Salvar</button>
          </div>
        </div>
        </div>
          <div class="row">
            <div class="col-md-2">
              <div class="form-group text-left">
                <label for="cpf_cliente">CPF <small>(obrigatório)</small></label>
                <input type="text" class="form-control cpf campo_obrigatorio" id="cpf_cliente" name="cpf_cliente" placeholder="CPF do cliente" maxlength="14" required>
              </div>          
            </div>

            <div class="col-md-5">
              <div class="form-group text-left">
                <label for="nome_cliente">Nome <small>(obrigatório)</small></label>
                <input type="text" class="form-control campo_obrigatorio" id="nome_cliente" name="nome_cliente" placeholder="Nome do cliente" required>
              </div>          
            </div>

            <div class="col-md-3">
              <div class="form-group text-left">
                <label for="profissao_cliente">Profissão</label>
                <input type="text" class="form-control" id="profissao_cliente" name="profissao_cliente" placeholder="Profissão do cliente">
              </div>          
            </div>

            <div class="col-md-2">
              <div class="form-group text-left">
                <label for="sexo_cliente">Sexo <small>(obrigatório)</small></label>
                <select class="form-control campo_obrigatorio" id="sexo_cliente" name="sexo_cliente">
                  <option value="N/I">Não informado</option>
                  <option value="F">Feminino</option>
                  <option value="M">Masculino</option>
                </select>
              </div>          
            </div>
        </div>

        <div class="row">
           <div class="col-md-3">
              <div class="form-group text-left">
                <label for="data_nascimento_cliente">Data de nascimento <small>(obrigatório)</small></label>
                <input type="text" class="form-control data campo_obrigatorio" id="data_nascimento_cliente" name="data_nascimento_cliente" placeholder="Data de nascimento do cliente" required>
              </div>          
            </div>

            <div class="col-md-9">
              <div class="form-group text-left">
                <label for="id_representante_financeiro_cliente">Nome do responsável financeiro</label>

                <input type="text" class="form-control" id="representante_financeiro_cliente" name="" placeholder="Nome do responsável financeiro" required>

                <input type="hidden" name="id_representante_financeiro_cliente" class="form-control" id="id_representante_financeiro_cliente" value="">




              </div>          
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
              <div class="form-group text-left">
                <label for="email_cliente">E-mail</label>
                <input type="email" class="form-control" id="email_cliente" name="email_cliente" placeholder="E-mail do cliente">
              </div>          
            </div>

            <div class="col-md-4">
              <div class="form-group text-left">
                <label for="celular_cliente">Celular <small>(obrigatório)</small></label>
                <input type="text" class="form-control celular campo_obrigatorio" id="celular_cliente" name="celular_cliente" placeholder="Celular do cliente" required>
              </div>          
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
              <div class="form-group text-left">
                <label for="cep_cliente">CEP <small>(obrigatório)</small></label>
                <input type="text" class="form-control cep campo_obrigatorio" id="cep_cliente" placeholder="CEP" required name="cep_cliente">
              </div>          
            </div>

            <div class="col-md-6">
              <div class="form-group text-left">
                <label for="logradouro_cliente">Logradouro <small>(obrigatório)</small></label>
                <input type="text" class="form-control campo_obrigatorio" id="logradouro_cliente" name="logradouro_cliente" placeholder="Logradouro do cliente" required>
              </div>          
            </div>

            <div class="col-md-2">
              <div class="form-group text-left">
                <label for="numero_cliente">Número <small>(obrigatório)</small></label>
                <input type="text" class="form-control campo_obrigatorio" id="numero_cliente" name="numero_cliente" placeholder="Número do cliente">
              </div>          
            </div>

            <div class="col-md-2">
              <div class="form-group text-left">
                <label for="complemento_cliente">Complemento</label>
                <input type="text" class="form-control" id="complemento_cliente" name="complemento_cliente" placeholder="Complemento do cliente">
              </div>          
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
              <div class="form-group text-left">
                <label for="bairro_cliente">Bairro <small>(obrigatório)</small></label>
                <input type="text" class="form-control campo_obrigatorio" id="bairro_cliente" name="bairro_cliente" placeholder="Bairro do cliente" required>
              </div>          
            </div>

            <div class="col-md-4">
              <div class="form-group text-left">
                <label for="cidade_cliente">Cidade <small>(obrigatório)</small></label>
                <input type="text" class="form-control campo_obrigatorio" id="cidade_cliente" name="cidade_cliente" placeholder="Cidade do cliente" required>
              </div>          
            </div>

            <div class="col-md-4">
              <div class="form-group text-left">
                <label for="estado_cliente">Estado <small>(obrigatório)</small></label>
                <input type="text" class="form-control campo_obrigatorio" id="estado_cliente" name="estado_cliente" placeholder="Estado do cliente" required>
              </div>          
            </div>
        </div>

        </form>
    </div>
    </main><!-- /.container -->
  </body>
</html>