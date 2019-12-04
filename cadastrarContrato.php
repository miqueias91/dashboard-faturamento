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
    <style type="text/css">
      h3 {
        text-align: left;
      }
    </style>
    <script type="text/javascript">
        $( document ).ready(function() {

          $( "#accordion" ).accordion();

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

          $("#cliente").autocomplete({
              source: "./json_busca_cliente.php",
              minLength: 1,
              select: function( event, ui ) {
                  $('#id_cliente').val(ui.item.id);
                  $(this).val(ui.item.value);
              }
          });
        });
    </script>
    <?=include_once("./menu.php");?>
    <main role="main" class="container">
      <div class="starter-template">
        <form action='salvarCliente.php' method='post' name='form' class="" id='form' enctype='multipart/form-data'>
          <div id="accordion">
            <h3>DADOS PRINCIPAIS</h3>
            <div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="id_contrato">Código</label>
                    <input type="text" class="form-control" id="id_contrato" name="id_contrato" placeholder="Código" disabled>
                  </div>          
                </div>

                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="data_inicio">Data de inicio <small>(obrigatório)</small></label>
                    <input type="text" class="form-control data campo_obrigatorio" id="data_inicio" name="data_inicio" placeholder="Data de inicio do contrato" required>
                  </div>          
                </div>

                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="data_final">Data final <small>(obrigatório)</small></label>
                    <input type="text" class="form-control data campo_obrigatorio" id="data_final" name="data_final" placeholder="Data final do contrato" required>
                  </div>          
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group text-left">
                    <label for="id_cliente">Nome do cliente <small>(obrigatório)</small></label>
                    <input type="text" class="form-control campo_obrigatorio" id="cliente" name="" placeholder="Nome do cliente" required>
                    <input type="hidden" name="id_cliente" class="form-control" id="id_cliente" value="">
                  </div>          
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group text-left">
                    <label for="observacao">Observação <small>(obrigatório)</small></label>
                    <textarea id="observacao" name="observacao" class="form-control campo_obrigatorio"></textarea>
                  </div>          
                </div>
              </div>
            </div>

            <h3>DADOS ADCIONAIS</h3>
            <div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="tipo_contrato">Tipo de contrato <small>(obrigatório)</small></label>
                    <select class="form-control campo_obrigatorio" id="tipo_contrato" name="tipo_contrato">
                      <option value="">Selecione</option>
                      <option value="fixo">Fixo</option>
                      <option value="variavel">Variável</option>
                    </select>
                  </div>          
                </div>
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="tipo_pagamento">Tipo de pagamento <small>(obrigatório)</small></label>
                    <select class="form-control campo_obrigatorio" id="tipo_pagamento" name="tipo_pagamento">
                      <option value="">Selecione</option>
                      <option value="boleto">Boleto</option>
                      <option value="cartao">Cartão</option>
                      <option value="outro">Outro</option>
                    </select>
                  </div>          
                </div>

                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="status_contrato">Status do contrato <small>(obrigatório)</small></label>
                    <select class="form-control campo_obrigatorio" id="status_contrato" name="status_contrato">
                      <option value="ativo">Ativo</option>
                      <option value="inativo">Inativo</option>
                    </select>
                  </div>          
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group text-left">
                    <label for="numparcela">Nº de parcelas <small>(obrigatório)</small></label>
                    <input type="text" name="numparcela" class="form-control campo_obrigatorio" id="numparcela" value="" placeholder="Nº de parcelas">
                  </div>          
                </div>
                <div class="col-md-6">
                  <div class="form-group text-left">
                    <label for="valorparcela">Valor da parcela <small>(obrigatório)</small></label>
                    <input type="text" name="valorparcela" class="form-control campo_obrigatorio" id="valorparcela" value="" placeholder="Valor da parcela">
                  </div>          
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="diavencimento">Dia de vencimento do contrato <small>(obrigatório)</small></label>
                    <input type="text" name="diavencimento" class="form-control campo_obrigatorio" id="diavencimento" value="" placeholder="Dia de vencimento do contrato">
                  </div>          
                </div>
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="diavencimentodesconto">Dia de vencimento do desconto <small>(obrigatório)</small></label>
                    <input type="text" name="diavencimentodesconto" class="form-control campo_obrigatorio" id="diavencimentodesconto" value="" placeholder="Dia de vencimento do desconto">
                  </div>          
                </div>
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="compinicial">Competência inicial <small>(obrigatório)</small></label>
                    <input type="text" name="compinicial" class="form-control campo_obrigatorio" id="compinicial" value="<?=date('m/Y')?>" placeholder="Competência inicial">
                  </div>          
                </div>
              </div>
            </div>

            <h3>DESCONTOS</h3>
            <div>
              <div class="row">
                <div class="col-md-1">
                  <div class="form-group text-center">
                      <label>Ação</label>
                      <a class="form-control text-center" href="#" onclick="excluirDesconto()">x</a>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="idclassificacaodesconto">Classificação <small>(obrigatório)</small></label>
                    <select class="form-control campo_obrigatorio" id="idclassificacaodesconto" name="idclassificacaodesconto">
                      <option value="">Selecione</option>
                    </select>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="persiste">Desc. persiste <small>(obrigatório)</small></label>
                    <select class="form-control campo_obrigatorio" id="persiste" name="persiste">
                      <option value="">Selecione</option>
                    </select>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="percentualutilizado">(%) utilizado <small>(obrigatório)</small></label>
                    <input type="text" name="percentualutilizado" class="form-control campo_obrigatorio" id="percentualutilizado" value="" placeholder="Percentual utilizado">
                  </div>          
                </div>

                <div class="col-md-3">
                  <div class="form-group text-left">
                    <label for="valordesconto">valor do desc. <small>(obrigatório)</small></label>
                    <input type="text" name="valordesconto" class="form-control campo_obrigatorio" id="valordesconto" value="" placeholder="valor desconto">
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="compdesconto">Comp. desconto <small>(obrigatório)</small></label>
                    <input type="text" name="compdesconto" class="form-control campo_obrigatorio" id="compdesconto" value="" placeholder="Comp. desconto">
                  </div>          
                </div>
              </div>
              <div class="row">
                <div class="col-md6">
                  <button class="btn btn-lg btn-primary btn-block" id="novoDesconto" type="button" href="#"><i class="fas fa-plus-circle"></i> Incluir desconto</button>
                </div>
              </div>
            </div>

         
   
  
            </div>
          </div>

        </form>
    </div>
    </main><!-- /.container -->
  </body>
</html>