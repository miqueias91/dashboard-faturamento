<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.contrato.php");

    $cont = new Contrato();

  //Montando lista de Tipos descontos
  $descontos = $cont->buscaDescontoTipo();
  $options_descontos = '' ;

  if($descontos){
      foreach ($descontos as $row) {
          $tipo = strtoupper($row['descricaotipodesconto']);
          $cod_tipo = str_pad($row['id_descontotipo'],5,'0', STR_PAD_LEFT);

          $options_descontos .= '<option persisteapos="'.$row['persisteaposvencimento'].'" value="'.$row['id_descontotipo'].'">'.$cod_tipo.' - '.strtoupper($tipo).'</option>\\n';
      }
  }
  else $options_descontos.= '<option value="">Nenhum registro encontrado.</option>\\n' ;
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
        <script src="<?=$PATH_JS;?>/maskMoney.js" language="javascript"></script>
        <script src="js/jquery-ui.js"></script>


  </head>
  <body>
    <style type="text/css">
      h3 {
        text-align: left;
      }
    </style>
    <script type="text/javascript">
      var aux_desconto = 1;
        $(document).on("input", ".numeral", function (e) {
          this.value = this.value.replace(/[^0-9]/g, '');
        });

        $( document ).ready(function() {
          $( "#accordion" ).accordion();

          $('.data').mask('00/00/0000');
          $('.cep').mask('00000-000');
          $('.celular').mask('(00) 00000-0000');
          $('.cpf').mask('000.000.000-00', {reverse: true});
          $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
          $('.cep').mask('99.999-999');
          $('.competencia').mask('99/9999');

          $('.valor').maskMoney({
              allowNegative: true,
              thousands: '',
              decimal: ',',
              affixesStay: false
          }); 

          $('.percentual').maskMoney({
              thousands:'',
              decimal:'.',
              precision: 2,
              affixesStay: false
          });

          $('#novoDesconto').click(function(){
            $('.competencia').unmask();
            $('#todosdescontos').append(
              '<div class="row" id="linhadesconto'+aux_desconto+'">'+
              '  <div class="col-md-1">'+
              '    <div class="form-group text-center">'+
              '        <a class="form-control text-center" href="#" onclick="excluirDesconto('+aux_desconto+')"><i class="fas fa-trash-alt"></i></a>'+
              '    </div>'+
              '  </div>'+

              '  <div class="col-md-2">'+
              '    <div class="form-group text-left">'+
              '      <select class="form-control campo_obrigatorio descontotipo" id="iddescontotipo'+aux_desconto+'" name="iddescontotipo[]" linha="'+aux_desconto+'">'+
              '        <option value="">Selecione</option>'+
              '       <?=$options_descontos;?>'+
              '      </select>'+
              '    </div>'+
              '  </div>'+

              '  <div class="col-md-2">'+
              '    <div class="form-group text-left">'+
              '      <input type="text" name="percentualutilizado[]" class="form-control campo_obrigatorio persiste text-center" id="percentualutilizado'+aux_desconto+'" value="" placeholder="N/I" disabled  linha="'+aux_desconto+'">'+
              '    </div>'+
              '  </div>'+

              '  <div class="col-md-2">'+
              '    <div class="form-group text-left">'+                
              '      <input type="text" name="percentualutilizado[]" class="form-control campo_obrigatorio percentual text-center linha'+aux_desconto+'" linha="'+aux_desconto+'" id="percentualutilizado'+aux_desconto+'" value="" placeholder="Percentual utilizado" disabled>'+
              '    </div>'+
              '  </div>'+

              '  <div class="col-md-3">'+
              '    <div class="form-group text-left">'+
              '      <input type="text" name="valordesconto[]" class="form-control campo_obrigatorio valor linha'+aux_desconto+' valordesconto" id="valordesconto'+aux_desconto+'" value="" placeholder="valor desconto" style="text-align: right;" disabled  linha="'+aux_desconto+'">'+
              '    </div>'+
              '  </div>'+

              '  <div class="col-md-2">'+
              '    <div class="form-group text-left">'+
              '      <input type="text" name="compdesconto[]" class="form-control campo_obrigatorio competencia text-center linha'+aux_desconto+'" id="compdesconto'+aux_desconto+'" value="" placeholder="Comp. desconto" disabled  linha="'+aux_desconto+'">'+
              '    </div>'+          
              '  </div>'+
              '</div>'
            );

            aux_desconto++ ;

            $('.competencia').mask('99/9999');

            $('.valor').maskMoney({
              allowNegative: true,
              thousands: '',
              decimal: ',',
              affixesStay: false
            }); 

            $('.percentual').maskMoney({
              thousands:'',
              decimal:'.',
              precision: 2,
              affixesStay: false
            }); 

            $('.descontotipo').change(function(){
              var linha = $(this).attr('linha');
              if ($(this).val() != '') {
                var id = $(this).attr('id');
                var persisteapos = $('#'+id+' option:selected').attr('persisteapos');
                $('#persiste'+linha).val(persisteapos.toUpperCase());
                $('.linha'+linha).removeAttr("disabled");
              }
              else{
                $('.linha'+linha).attr("disabled","disabled");
                $('#persiste'+linha).val('N/I');
                $('#percentualutilizado'+linha).val('');
                $('#valordesconto'+linha).val('');
                $('#compdesconto'+linha).val('');
              }
            });

            $('.percentual').change(function(){
              if(parseInt($('#valorparcela').val()) == 0 || $('#valorparcela').val() == ''){
                alert('Preencha o VALOR DA PARCELA nos DADOS ADICIONAIS antes de informar um percentual.');
                $(this).val('');
                $(this).focus();
                return false;
              }              
              else if(parseFloat($(this).val()) > 100){
                alert("O percentual utilizado não pode ultrapassar 100%.");
                $(this).val('');
                $(this).focus();
                return false;
              }
              else{
                var valor_desconto = (parseFloat($("#valorparcela").val()) * parseFloat($(this).val())) / 100;
                valor_desconto = valor_desconto.toFixed(2);
                valor_desconto = valor_desconto.replace('.',',');

                $("#valordesconto"+$(this).attr('linha')).val(valor_desconto);
                $("#valordesconto"+$(this).attr('linha')).attr('value',valor_desconto);
              }
            });
            
            $('.valordesconto').change(function(){
              if(parseFloat($(this).val()) > parseFloat($("#valorparcela").val())){
                alert("O valor de desconto não pode ser superior ao valor do contrato.");
                $('#percentualutilizado'+$(this).attr('linha')).val('');
                $(this).val('');
                $(this).focus();
                return false;
              }
              else{
                var porc_desconto = (parseFloat($(this).val().replace(',','.')) * 100) / parseFloat($("#valorparcela").val().replace(',','.'));
                porc_desconto = porc_desconto.toFixed(2);
                porc_desconto = porc_desconto.replace(',','.');            
                $("#percentualutilizado"+$(this).attr('linha')).val(porc_desconto);
                $("#percentualutilizado"+$(this).attr('linha')).attr('value',porc_desconto);
              }
            });






          });

          $('.descontotipo').change(function(){
            var linha = $(this).attr('linha');
            if ($(this).val() != '') {
              var id = $(this).attr('id');
              var persisteapos = $('#'+id+' option:selected').attr('persisteapos');
              $('#persiste'+linha).val(persisteapos.toUpperCase());
              $('.linha'+linha).removeAttr("disabled");
            }
            else{
              $('.linha'+linha).attr("disabled","disabled");
              $('#persiste'+linha).val('N/I');
              $('#percentualutilizado'+linha).val('');
              $('#valordesconto'+linha).val('');
              $('#compdesconto'+linha).val('');
            }
          }); 

          $('.percentual').change(function(){
            if(parseInt($('#valorparcela').val()) == 0 || $('#valorparcela').val() == ''){
              alert('Preencha o VALOR DA PARCELA nos DADOS ADICIONAIS antes de informar um percentual.');
              $(this).val('');
              $(this).focus();
              return false;
            }              
            else if(parseFloat($(this).val()) > 100){
              alert("O percentual utilizado não pode ultrapassar 100%.");
              $(this).val('');
              $(this).focus();
              return false;
            }
            else{
              var valor_desconto = (parseFloat($("#valorparcela").val()) * parseFloat($(this).val())) / 100;
              valor_desconto = valor_desconto.toFixed(2);
              valor_desconto = valor_desconto.replace('.',',');

              $("#valordesconto"+$(this).attr('linha')).val(valor_desconto);
              $("#valordesconto"+$(this).attr('linha')).attr('value',valor_desconto);
            }
          });

          $('.valordesconto').change(function(){
            if(parseFloat($(this).val()) > parseFloat($("#valorparcela").val())){
              alert("O valor de desconto não pode ser superior ao valor do contrato.");
              $('#percentualutilizado'+$(this).attr('linha')).val('');
              $(this).val('');
              $(this).focus();
              return false;
            }
            else{
              var porc_desconto = (parseFloat($(this).val().replace(',','.')) * 100) / parseFloat($("#valorparcela").val().replace(',','.'));
              porc_desconto = porc_desconto.toFixed(2);
              porc_desconto = porc_desconto.replace(',','.');            
              $("#percentualutilizado"+$(this).attr('linha')).val(porc_desconto);
              $("#percentualutilizado"+$(this).attr('linha')).attr('value',porc_desconto);
            }
          });

          $('#salvarContrato').click(function(){
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
        function excluirDesconto(i){
          $('#linhadesconto'+i).remove();
        }
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
                    <label for="data_inicio">Data de inicio <br><small>(obrigatório)</small></label>
                    <input type="text" class="form-control data campo_obrigatorio text-center" id="data_inicio" name="data_inicio" placeholder="Data de inicio do contrato" required>
                  </div>          
                </div>

                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="data_final">Data final <br><small>(obrigatório)</small></label>
                    <input type="text" class="form-control data campo_obrigatorio text-center" id="data_final" name="data_final" placeholder="Data final do contrato" required>
                  </div>          
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group text-left">
                    <label for="id_cliente">Nome do cliente <br><small>(obrigatório)</small></label>
                    <input type="text" class="form-control campo_obrigatorio" id="cliente" name="" placeholder="Nome do cliente" required>
                    <input type="hidden" name="id_cliente" class="form-control" id="id_cliente" value="">
                  </div>          
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group text-left">
                    <label for="observacao">Observação <br><small>(obrigatório)</small></label>
                    <textarea id="observacao" name="observacao" class="form-control campo_obrigatorio"></textarea>
                  </div>          
                </div>
              </div>
            </div>

            <h3>DADOS ADICIONAIS</h3>
            <div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="tipo_contrato">Tipo de contrato <br><small>(obrigatório)</small></label>
                    <select class="form-control campo_obrigatorio" id="tipo_contrato" name="tipo_contrato">
                      <option value="">Selecione</option>
                      <option value="fixo">Fixo</option>
                      <option value="variavel">Variável</option>
                    </select>
                  </div>          
                </div>
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="tipo_pagamento">Tipo de pagamento <br><small>(obrigatório)</small></label>
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
                    <label for="status_contrato">Status do contrato <br><small>(obrigatório)</small></label>
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
                    <label for="numparcela">Nº de parcelas <br><small>(obrigatório)</small></label>
                    <input type="text" name="numparcela" class="form-control campo_obrigatorio numeral text-center" id="numparcela" value="" placeholder="Nº de parcelas">
                  </div>          
                </div>
                <div class="col-md-6">
                  <div class="form-group text-left">
                    <label for="valorparcela">Valor da parcela <br><small>(obrigatório)</small></label>
                    <input type="text" name="valorparcela" class="form-control campo_obrigatorio valor text-right" id="valorparcela" value="" placeholder="Valor da parcela">
                  </div>          
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="diavencimento">Dia de vencimento do contrato <br><small>(obrigatório)</small></label>
                    <input type="text" name="diavencimento" class="form-control campo_obrigatorio numeral text-center" id="diavencimento" value="" placeholder="Dia de vencimento do contrato" maxlength="2">
                  </div>          
                </div>
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="diavencimentodesconto">Dia de vencimento do desconto <br><small>(obrigatório)</small></label>
                    <input type="text" name="diavencimentodesconto" class="form-control campo_obrigatorio numeral text-center" id="diavencimentodesconto" value="" placeholder="Dia de vencimento do desconto" maxlength="2">
                  </div>          
                </div>
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="compinicial">Competência inicial <br><small>(obrigatório)</small></label>
                    <input type="text" name="compinicial" class="form-control campo_obrigatorio competencia text-center" id="compinicial" value="<?=date('m/Y')?>" placeholder="Competência inicial">
                  </div>          
                </div>
              </div>
            </div>

            <h3>DESCONTOS</h3>
            <div>
            <div id="todosdescontos">
              <div class="row" id="linhadesconto0">
                <div class="col-md-1">
                  <div class="form-group text-center">
                      <label>Ação</label>
                      <!-- <a class="form-control text-center" href="#" onclick="excluirDesconto('0')">x</a> -->
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="iddescontotipo">Tipo de desconto <br><small>(obrigatório)</small></label>
                    <select class="form-control campo_obrigatorio descontotipo" linha="0" id="iddescontotipo0" name="iddescontotipo[]">
                      <option value="">Selecione</option>
                      <?=$options_descontos?>
                    </select>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="persiste">Desc. persiste <br><small>(obrigatório)</small></label>               
                    <input type="text" name="persiste[]" class="form-control campo_obrigatorio persiste text-center" linha="0" id="persiste0" value="" placeholder="N/I" disabled>

                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="percentualutilizado">(%) utilizado <br><small>(obrigatório)</small></label>
                    <input type="text" name="percentualutilizado[]" class="form-control campo_obrigatorio percentual text-center linha0" linha="0" id="percentualutilizado0" value="" placeholder="Percentual utilizado" disabled>
                  </div>          
                </div>

                <div class="col-md-3">
                  <div class="form-group text-left">
                    <label for="valordesconto">valor do desc. <br><small>(obrigatório)</small></label>
                    <input type="text" name="valordesconto[]" class="form-control campo_obrigatorio valor linha0 valordesconto" linha="0" id="valordesconto0" value="" placeholder="valor desconto" style="text-align: right;" disabled>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="compdesconto">Comp. desconto <br><small>(obrigatório)</small></label>
                    <input type="text" name="compdesconto[]" class="form-control campo_obrigatorio competencia text-center linha0" linha="0" id="compdesconto0" value="" placeholder="Comp. desconto" disabled>
                  </div>          
                </div>
              </div>
         
            </div>  
              <div class="row">
                <div class="col-sm-12">
                  <div class="text-center">
                    <button class="btn btn-primary" id="novoDesconto" type="button" href="#"><i class="fas fa-plus-circle"></i> Incluir desconto</button>
                  </div>
                </div>
              </div>
            </div>  
          </div>

          <div class="row" style="margin-top: 20px;">
            <div class="col-sm-12">
              <div class="text-center">
                <button class="btn btn-primary" id="salvarContrato" type="button" href="#"><i class="fas fa-save"></i> Salvar contrato</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </main><!-- /.container -->
  </body>
</html>