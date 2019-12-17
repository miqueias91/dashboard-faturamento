<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.contrato.php");
    include_once("$base/class/class.servico.php");
    include_once("$base/class/class.mascara.php");

    $cont = new Contrato();
    $ser = new Servico();
    $m = new Mascara();

    function listaMeses($mesinicial, $anoinicial, $mesfinal, $anofinal){
      //Inicializa array
      $meses = array();
      //Percorrendo os anos do periodo solicitado
      for($ano = $anoinicial ; $ano <= $anofinal ; $ano++){
          //Condicao de inicio e parada levando em consideracao o ano inicial e final
          $mes_ = $ano == $anoinicial ?  $mesinicial : 1 ;
          $_mes = $ano == $anofinal ? $mesfinal : 12 ;
          //Percorrendo os meses dentro de cada ano solicitado
          for($mes = $mes_ ; $mes <= $_mes ; $mes++){
              //Coloca em um array o mes/ano percorrido
              array_push($meses, str_pad($mes,2,"0",STR_PAD_LEFT)."/$ano");
          }
      }
      //Retorna
      return $meses ;
    }

    $dataatual = date("d/m/Y");
    $titulo_contrato = "Cadastrar contrato";
    if ($id_contrato) {
      $titulo_contrato = "Editar contrato";
      $dados = $cont->buscaContrato($id_contrato);
      $dado = $dados[0];
      $dadosDesconto = $cont->buscaDescontoContrato($id_contrato);
      $dadosMovimento = $cont->buscaMovimentoContrato($id_contrato);

      $numMovimento = $dadosMovimento ? count($dadosMovimento) : 0;


      $dadosCliente = $dado['cpf_cliente'] ? $m->OutMascaraCPF($dado['cpf_cliente'])." | ".$dado['nome_cliente'] : NULL;

      $options_servico = '<option selected value="'.$dado['id_servico'].'">'.$dado['descricao_servico'].'</option>\\n';

      if (date('d') > $dado['diavencimento']) {
        $datavencimento = date("Y-m-d");
        $auxdatavencimento = date('Y-m-d', strtotime("$datavencimento +1 month"));
        $aux_datavencimento = explode("-", $auxdatavencimento);
        $datavencimento = $dado['diavencimento']."/".$aux_datavencimento[1]."/".$aux_datavencimento[0];
        $datadesconto = $dado['diavencimentodesconto']."/".$aux_datavencimento[1]."/".$aux_datavencimento[0];
      }
      else{
        $datavencimento = $dado['diavencimento']."/".date("m/Y");
        $datadesconto = $dado['diavencimentodesconto']."/".date("m/Y");
      }

      $tmp = explode("/", $dado['compinicial']); // formato mm/YYYY
      $ano = $tmp[1];
      $mes = $tmp[0];
      $dia = '01';
      // Concatenando ano-mes-dia para pegar o inicio da primeira parcela
      $tmp = "$ano-$mes-$dia";
      // Transformando a data inicial da primeira parcela
      $time = strtotime($tmp);
      $num_parcelas = $dado['numparcela']-1;
      // Incrementando a quantidade parcelas(meses) para obter a data final de acordo com o numero de parcelas cadastrado pelo usuario date("Y-m-d", strtotime("+1 month", $time));
      $final = date("Y-m-d", strtotime("+$num_parcelas month", $time));
      // Montando o array com numero de meses obtido entre as datas inicial e final
      $meses_competencia = listaMeses($mes, $ano, substr($final,5,2), substr($final,0,4));
    }

    
    $servicos = $ser->buscaServico(null, null, 'ativo');
    $options_servicos = '' ;
    if($servicos){
      foreach ($servicos as $row) {
          $descricao_servico = $row['descricao_servico'];

          $options_servicos .= '<option value="'.$row['id_servico'].'">'.$descricao_servico.'</option>\\n';
      }
  }
  else $options_servicos.= '<option value="">Nenhum registro encontrado.</option>\\n' ;


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
  else {
    $options_descontos.= '<option value="">Nenhum registro encontrado.</option>\\n' ;
  }

  include_once 'header.php'; 

?>
    <style type="text/css">
      h3 {
        text-align: left;
      }
    </style>
    <script type="text/javascript">
      var aux_desconto = 0;
        //FORÇANDO TODO INPUT QUE TENHA A CLASSE NUMERAL ACEITAR APENAS NUMEROS
        $(document).on("input", ".numeral", function (e) {
          this.value = this.value.replace(/[^0-9]/g, '');
        });

        $( document ).ready(function() {
          $("#titulo").html("<?=$titulo_contrato?>");
          $( "#accordion" ).accordion();

          $( "#divboletos" ).dialog({
              autoOpen: false,
              resizable: false,
              draggable: false,
              height: 500,
              width:800,
              modal:true,

              buttons: {
                  "Fechar": function(){
                      $(this).dialog('close');
                  },
                  "Imprimir": function(){
                      // window.print();
                      $("#iframeboleto").get(0).contentWindow.print();
                  }                     
              }
          });   

          //MASCARAS DOS CAMPOS
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

          $('#tipo_contrato').val('<?= $dado[tipo_contrato] ? $dado[tipo_contrato] : NULL?>');
          $('#tipo_pagamento').val('<?= $dado[tipopagamento] ? $dado[tipopagamento] : NULL?>');
          $('#status_contrato').val('<?= $dado[status_contrato] ? $dado[status_contrato] : NULL?>');
          $('#diavencimento').val('<?= $dado[diavencimento] ? $dado[diavencimento] : NULL?>');
          $('#diavencimentodesconto').val('<?= $dado[diavencimentodesconto] ? $dado[diavencimentodesconto] : NULL?>');
          $('#compinicial').val('<?= $dado[compinicial] ? $dado[compinicial] : NULL?>');

          <?php if ($dado['tipopagamento'] == 'boleto'): ?>
            buscaConfiguracaoArquivo();
            $('#config_boleto').val('<?= $dado[idconfigarquivobancario] ? $dado[idconfigarquivobancario] : NULL?>');
          <?php endif ?>

<?php if ($dadosDesconto): ?>
  <?php foreach ($dadosDesconto as $key => $value): ?>
    criaDescontoContrato('<?=$value[idtipodesconto]?>', '<?=$value[persisteaposvencimento]?>','<?=$value[valordesconto]?>','<?=$value[compdesconto]?>');
  <?php endforeach ?>  
<?php endif ?>


          //CRIANDO AS LINHAS DOS DESCONTOS
          $('#novoDesconto').click(function(){
            criaDescontoContrato();
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

          //PELA DATA INICIAL E FINAL DO CONTRATO CALCULO QUANTAS PARCELAS ELE TERÁ
          $('#data_final').change(function(){
            if ($('#data_inicio').val()) {
              var tmp_data_final = $(this).val().split("/");
              var data_final = tmp_data_final[2]+''+tmp_data_final[1]+''+tmp_data_final[0];
              
              var tmp_data_inicio = $('#data_inicio').val().split("/");
              var data_inicio = tmp_data_inicio[2]+''+tmp_data_inicio[1]+''+tmp_data_inicio[0];

              if (parseInt(data_inicio) > parseInt(data_final)) {
                alert("A data final do contrato não pode ser inferior a data de inicio.");
                $(this).focus();
              }
              else{
                var numparcelas = (parseInt(tmp_data_final[1]) - parseInt(tmp_data_inicio[1]) + 1);
                $('#numparcela').val(numparcelas);
              }
            }
            else{
              alert("Informe a data de inicio do contrato.");
              $(this).val('');
              $('#data_inicio').focus();
            }           
          });

          $('#tipo_pagamento').change(function(){
            if($(this).val() == 'boleto'){
              buscaConfiguracaoArquivo();
            }
            else{
              $('#div_config_boleto').html('');
            }
          });

          $('#salvarContrato, #salvarAlteracao').click(function(){
            if ($('.campo_obrigatorio').val() == '') {
              alert('Existem campos obrigatórios não preenchidos.');
              return false ;
            }
            else if(!verificaDesconto()){
              alert('Verifique os dados dos descontos.');
              return false ;
            }
            else{
              $('.bloqueado').removeAttr('disabled');

              $("#form").submit();
              return true;
            }
          });

          $('#gerar').click(function(){

            if ($('#dataDesconto').val() != '' && $('#dataVencimento').val() != '') {
              var tmp_dataVencimento = $('#dataVencimento').val().split("/");
              var dataVencimento = tmp_dataVencimento[2]+''+tmp_dataVencimento[1]+''+tmp_dataVencimento[0];
              
              var tmp_dataDesconto = $('#dataDesconto').val().split("/");
              var dataDesconto = tmp_dataDesconto[2]+''+tmp_dataDesconto[1]+''+tmp_dataDesconto[0];
            }

            if (parseInt(dataDesconto) > parseInt(dataVencimento)) {
              alert("O vencimento do desconto não pode ser superior ao vencimento do movimento.");
              return false ;
            }              
            else if ($('.campo_obrigatorio').val() == '') {
              alert('Existem campos obrigatórios não preenchidos.');
              return false ;
            }
            else if(!verificaDesconto()){
              alert('Verifique os dados dos descontos.');
              return false ;
            }
            else if ($('#dataVencimento').val() == '' || $('#dataDesconto').val() == '' || $('#compMovimento').val() == '' || $('#valorMovimento').val() == ''){
              alert('Verifique os dados para geração do movimento.');
              return false ;
            }
            else{
              $('.bloqueado').removeAttr('disabled');
              $('#form').attr('action', "./salvarContratoMovimento.php?idcontrato=<?=$id_contrato?>");
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

          $('#modalMovimento').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);            
            var modal = $(this);
            modal.find('.modal-title').text('Gerar movimento ');
          });

        });

        function excluirDesconto(i){
          $('#linhadesconto'+i).remove();
        }

        function verificaDesconto() {
          $('#msgdesconto').html('');
          var campovazio = 0;
          var porcentagemmaior = 0;

          $('.descontotipo').each(function(){
            if($(this).val() == ''){
              campovazio = 1;
            }
          });

          $('.valordesconto').each(function(){
            if($(this).val() == ''){
              campovazio = 1;
            }                   
          });

          $('.compdesconto').each(function(){
            if($(this).val() == ''){
              campovazio = 1;
            }                   
          });

          if (campovazio == 1) {
            $('#msgdesconto').html('Atençao! Existem campos não preenchidos.');
            return false;
          }
          else{
            var valordesconto = 0;                
            var vetorDadosDescontos = new Array();

            $('.valordesconto').each(function(){
              valordesconto += parseFloat($(this).val().replace(',','.'));
              aux = $(this).attr('linha');
              var porcentagem = (parseFloat($(this).val().replace(',','.')) * 100) / parseFloat($('#valorparcela').val().replace(',','.'));             
              if (porcentagem.toFixed(2) > 100) {
                porcentagemmaior = 1;
              }

              //CRIANDO UM ARRAY PARA COMPARAÇÕES NO AJAX
              vetorDadosDescontos.push({ 
                'descontotipo' : $('#iddescontotipo'+aux).val() ,
                'valordesconto' : parseFloat($(this).val().replace(',','.')), 
                'valorparcela' : parseFloat($('#valorparcela').val().replace(',','.')) ,
                'percentualmaximo' : '100' ,
                'compdesconto' : $('#compdesconto'+aux).val()
              });
            });

            $.ajax({
              url: "ajaxContratoDesconto.php",
              dataType: 'html',
              type: 'post',
              data: {
                'verificaLinhasDescontos': 'sim',
                'vetorDadosDescontos': vetorDadosDescontos
              },
              beforeSend: function(a) {
                a.overrideMimeType("text/plain;charset=\"iso-8859-1\"");
              },
              error: function() {
                alert("Houve falha ao verificar as informações dos descontos. Gentileza informar o Suporte.");
              },
              success: function(ajaxResposta) {
                $("#verificaLinhasDescontos").val(ajaxResposta);
                $('#msgdesconto').html(ajaxResposta);
              },
            });

            if ($("#verificaLinhasDescontos").val() != '') {
              return false;
            }         
            else{
              return true;
            }
          }  
        }

        function buscaConfiguracaoArquivo() {
            $.ajax({
              url: "ajaxConfiguracaoArquivo.php",
              dataType: 'html',
              type: 'post',
              data: {
                'buscaConfiguracaoArquivo': 'sim'
              },
              beforeSend: function(a) {
                a.overrideMimeType("text/plain;charset=\"iso-8859-1\"");
              },
              error: function() {
                alert("Houve falha ao verificar as informações dos arquivos. Gentileza informar o Suporte.");
              },
              success: function(ajaxResposta) {
                $('#div_config_boleto').html(
                  '<label for="config_boleto">Configuração de boleto <br><small>(obrigatório)</small>'+
                  '</label>'+
                    '<select class="form-control campo_obrigatorio" id="config_boleto" name="config_boleto">'+ajaxResposta+'</select>');
              },
            });
        }

        function criaDescontoContrato(idtipodesconto, persisteaposvencimento, valordesconto, compdesconto) {
            //RETIRANDO MASCARA
            $('.competencia').unmask();
            //INCLUIND HTML
            $('#todosdescontos').append(
              '<div class="row" id="linhadesconto'+aux_desconto+'">'+
              '  <div class="col-md-1">'+
              '    <div class="btn-group btn-group-sm text-center">'+
              '        <a title="Excluir" class="form-control-sm text-center" href="#" onclick="excluirDesconto('+aux_desconto+')"><i class="fas fa-trash-alt"></i></a>'+
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
              '      <input type="text" name="persiste[]" class="form-control campo_obrigatorio persiste text-center bloqueado" id="persiste'+aux_desconto+'" value="" placeholder="N/I" disabled  linha="'+aux_desconto+'">'+
              '    </div>'+
              '  </div>'+

              '  <div class="col-md-2">'+
              '    <div class="form-group text-left">'+                
              '      <input type="text" name="percentualutilizado[]" class="form-control campo_obrigatorio percentual text-center linha'+aux_desconto+' bloqueado" linha="'+aux_desconto+'" id="percentualutilizado'+aux_desconto+'" value="" placeholder="Percentual utilizado" disabled>'+
              '    </div>'+
              '  </div>'+

              '  <div class="col-md-3">'+
              '    <div class="form-group text-left">'+
              '      <input type="text" name="valordesconto[]" class="form-control campo_obrigatorio valor linha'+aux_desconto+' valordesconto bloqueado" id="valordesconto'+aux_desconto+'" value="" placeholder="valor desconto" style="text-align: right;" disabled  linha="'+aux_desconto+'">'+
              '    </div>'+
              '  </div>'+

              '  <div class="col-md-2">'+
              '    <div class="form-group text-left">'+
              '      <input type="text" name="compdesconto[]" class="form-control campo_obrigatorio competencia compdesconto text-center linha'+aux_desconto+' bloqueado" id="compdesconto'+aux_desconto+'" value="" placeholder="Comp. desconto" disabled  linha="'+aux_desconto+'">'+
              '    </div>'+          
              '  </div>'+
              '</div>'
            );

            if (parseInt(idtipodesconto) > 0) {
              $("#iddescontotipo"+aux_desconto).val(idtipodesconto);
              $("#persiste"+aux_desconto).val(persisteaposvencimento.toUpperCase());
              var percentualutilizado = (parseFloat(valordesconto.replace(',','.')) * 100) / parseFloat($('#valorparcela').val().replace(',','.'));    

              $("#percentualutilizado"+aux_desconto).val(percentualutilizado.toFixed(2));
              $("#valordesconto"+aux_desconto).val(valordesconto.replace('.',','));
              $("#compdesconto"+aux_desconto).val(compdesconto);
              $('.linha'+aux_desconto).removeAttr("disabled");
            }

            //INCREMENTANDO VARIAVEL
            aux_desconto++ ;

            //INCLUINDO MASCARA
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

            //AO ALTERAR O TIPO DE DESCONTO CARREGO O CAMPO DE PESISTE APOS VENCIMENTO E DESABILITO O RESTANTE DOS INPUT'S
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
              //VERIFICO SE O VALOR DO CONTRATO NÃO ESTÁ VAZIO
              if(parseInt($('#valorparcela').val()) == 0 || $('#valorparcela').val() == ''){
                alert('Preencha o VALOR DA PARCELA nos DADOS ADICIONAIS antes de informar um percentual.');
                $(this).val('');
                $(this).focus();
                return false;
              }              
              //VERIFICO SE FOI DIGITADO UMA PORCENTAGEM MAIOR QUE 100%
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
        }

        function downloadMovimento(idparcela) {
            $('#iframeboleto').attr('src', 'imprimirBoleto.php?idboleto='+idparcela);
            $('#divboletos').dialog('open');
        }












    </script>
        <form action='salvarContrato.php' method='post' name='form' class="" id='form' enctype='multipart/form-data'>
          <input type="hidden" id="verificaLinhasDescontos" value="">
          
          <div id='divboletos' style="display:none;width:800px;height:500px;overflow-y: hidden;" class="dialogo" title='Impressão de boletos'  align='center' scrolling="no">
            <iframe src="" name="iframeboleto" id='iframeboleto' style="background: white;" width='100%' height='100%' frameborder='0'></iframe>
          </div> 
          <div class="modal fade" id="modalMovimento" tabindex="-1" role="dialog" aria-labelledby="modalMovimentoLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalMovimentoLabel">Gerar movimento</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form>
                    <div class="row">
                      <div class="col-md-4">                        
                        <div class="form-group  text-left">
                          <label for="dataEmissao">Emissão</label>
                          <input type="text" class="form-control data" id="dataEmissao" value="<?=$dataatual?>" disabled>
                        </div>
                      </div>
                      <div class="col-md-4">                        
                        <div class="form-group  text-left">
                          <label for="dataVencimento">Vencimento</label>
                          <input style="text-align: center;" type="text" class="form-control data" id="dataVencimento" name="datavencimento_movimento" value="<?=$datavencimento?>">
                        </div>
                      </div>
                      <div class="col-md-4">                        
                        <div class="form-group  text-left">
                          <label for="dataDesconto">Desconto</label>
                          <input style="text-align: center;" type="text" class="form-control data" id="dataDesconto" name="datavencimento_desconto" value="<?=$datadesconto?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">                        
                        <div class="form-group  text-left">
                          <label for="compMovimento">Comp.</label>
                          <input style="text-align: center;" type="text" class="form-control competencia" id="compMovimento" name="competencia" value="<?=$meses_competencia[$numMovimento]?>">
                        </div>
                      </div>                      
                      <div class="col-md-6">                        
                        <div class="form-group  text-left">
                          <label for="valorMovimento">Valor do movimento</label>
                          <?php if ($dado['tipo_contrato'] == 'fixo'){
                          ?>
                            <input style="text-align: right;" type="text" class="form-control" id="valorMovimento" value="<?= $dado[valorparcela] ? number_format($dado['valorparcela'],2,',','') : NULL?>" disabled>
                            
                          <?php }else{
                          ?>
                            <input style="text-align: right;" type="text" class="form-control" id="valorMovimento" name="valormovimento" value="<?= $dado[valorparcela] ? number_format($dado['valorparcela'],2,',','') : NULL?>">
                          <?php                            
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                  <button type="button" class="btn btn-primary" id="gerar">Gerar</button>
                </div>
              </div>
            </div>
          </div>

          <div id="accordion">
            <h3>DADOS PRINCIPAIS</h3>
            <div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="id_contrato">Código</label>
                    <input type="text" class="form-control bloqueado" id="id_contrato" name="id_contrato" placeholder="Código" disabled value="<?= $dado['id_contrato'] ? str_pad($dado['id_contrato'],7,'0', STR_PAD_LEFT) : NULL?>">
                  </div>          
                </div>

                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="data_inicio">Data de inicio <br><small>(obrigatório)</small></label>
                    <input type="text" class="form-control data campo_obrigatorio text-center" id="data_inicio" name="data_inicio" placeholder="Data de inicio do contrato" required value="<?=$dado['datainicio'] ? date('d/m/Y', strtotime($dado['datainicio'])) : NULL?>">
                  </div>          
                </div>

                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="data_final">Data final <br><small>(obrigatório)</small></label>
                    <input type="text" class="form-control data campo_obrigatorio text-center" id="data_final" name="data_final" placeholder="Data final do contrato" required value="<?=$dado['datafinal'] ? date('d/m/Y', strtotime($dado['datafinal'])) : NULL?>">
                  </div>          
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group text-left">
                    <label for="id_cliente">Nome do cliente <br><small>(obrigatório)</small></label>
                    <input type="text" class="form-control campo_obrigatorio" id="cliente" name="" placeholder="Nome do cliente" required value="<?=$dadosCliente?>">
                    <input type="hidden" name="id_cliente" class="form-control" id="id_cliente" value="<?=$dado['id_cliente']?>">
                  </div>          
                </div>
                <div class="col-md-6">
                  <div class="form-group text-left">
                    <label for="id_servico">Serviço <br><small>(obrigatório)</small></label>
                    <select class="form-control campo_obrigatorio" id="id_servico" name="id_servico">
                      <option value="">Selecione</option>
                      <?=$options_servico?>
                      <?=$options_servicos?>
                    </select>
                  </div>         
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group text-left">
                    <label for="observacao">Observação <br><small>(obrigatório)</small></label>
                    <textarea id="observacao" name="observacao" class="form-control campo_obrigatorio"><?=$dado['observacao'] ? $dado['observacao'] : NULL?></textarea>
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
                  <div class="form-group text-left" id="div_config_boleto">
                    
                  </div>          
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="numparcela">Nº de parcelas<br><small>&nbsp;</small></label>
                    <input type="text" name="numparcela" class="form-control numeral text-center bloqueado" id="numparcela" value="<?= $dado[numparcela] ? $dado[numparcela] : NULL?>" placeholder="Nº de parcelas" disabled>
                  </div>          
                </div>
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="valorparcela">Valor da parcela <br><small>(obrigatório)</small></label>
                    <input type="text" name="valorparcela" class="form-control campo_obrigatorio valor text-right" id="valorparcela" value="<?= $dado[valorparcela] ? number_format($dado['valorparcela'],2,',','') : NULL?>" placeholder="Valor da parcela">
                  </div>          
                </div>
                <div class="col-md-4">
                  <div class="form-group text-left">
                    <label for="status_contrato">Status do contrato <br><small>(obrigatório)</small></label>
                    <select class="form-control campo_obrigatorio" id="status_contrato" name="status_contrato">
                      <option value="Ativo">Ativo</option>
                      <option value="Inativo">Inativo</option>
                    </select>
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

            <h3>DESCONTOS <span id="msgdesconto" style="color: red; font-weight: normal;"></span></h3>
            <div>
            <div id="todosdescontos">
              <div class="row" id="linhadesconto">
                <div class="col-md-1">
                  <div class="form-group text-center">
                      <label>Ação</label>
                      <!-- <a class="form-control text-center" href="#" onclick="excluirDesconto('0')">x</a> -->
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="iddescontotipo">Tipo de desconto <br><small>(obrigatório)</small></label>                    
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="persiste">Desc. persiste <br><small>(obrigatório)</small></label>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="percentualutilizado">(%) utilizado <br><small>(obrigatório)</small></label>
                  </div>          
                </div>

                <div class="col-md-3">
                  <div class="form-group text-left">
                    <label for="valordesconto">valor do desc. <br><small>(obrigatório)</small></label>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-left">
                    <label for="compdesconto">Comp. desconto <br><small>(obrigatório)</small></label>
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
            <?php
              if ($id_contrato) {
            ?>
            <h3>LANÇAMENTOS</h3>
            <div>
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group text-center">
                      <label>Ação</label>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-center">
                    <label for="">Nº Movimento</label>                    
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-center">
                    <label for="">Documento</label>
                  </div>          
                </div>

                <div class="col-md-3">
                  <div class="form-group text-left">
                    <label for="">Histórico</label>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-right">
                    <label for="">valor</label>
                  </div>          
                </div>

                <div class="col-md-1">
                  <div class="form-group text-center">
                    <label for="">Comp</label>
                  </div>          
                </div>
              </div> 
              <?php
              if ($dadosMovimento) {
                foreach ($dadosMovimento as $key => $value) {
              ?> 
              <div class="row">
                <div class="col-md-2">
                  <div class="btn-group btn-group-sm text-center">
                        <a title="Excluir" class="form-control-sm text-center" href="#" onclick="excluirMovimento('<?=$value[idmovimento]?>')"><i class="fas fa-trash-alt"></i></a>

                        <a title="Abrir" class="form-control-sm text-center" href="#" onclick="abrirMovimento('<?=$value[idmovimento]?>')"><i class="fas fa-folder-open"></i></a>

                        <a title="Download" class="form-control-sm text-center" href="#" onclick="downloadMovimento('<?=$value[id_movimento_parcela]?>')"><i class="fas fa-download"></i></a>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-center">
                    <?=str_pad($value['idmovimento'],7,'0', STR_PAD_LEFT)?>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-center">
                    <?=$value['documento']?>
                  </div>          
                </div>

                <div class="col-md-3">
                  <div class="form-group text-left">
                    <?=$value['descricaohistorico']?>
                  </div>          
                </div>

                <div class="col-md-2">
                  <div class="form-group text-right">
                    <?=$value['valortotal']?>
                  </div>          
                </div>

                <div class="col-md-1">
                  <div class="form-group text-center">
                    <?=$value['comp']?>
                  </div>          
                </div>
              </div> 
              <?php
                }
              }else{
              ?> 
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group text-center">
                    <a>Nenhum lançamento encontrado.</a>
                  </div>          
                </div>
              </div>
              <?php
              }
              ?>   
            </div> 
            <?php
              }
            ?>
          </div>

          <div class="row" style="margin-top: 20px;">
            <div class="col-sm-12">
              <?php
                if ($id_contrato) {
              ?>
                <div class="text-center">
                  <button class="btn btn-primary" id="salvarAlteracao" type="button"><i class="fas fa-save"></i> Salvar alterações</button>
                  <?php
                    if ($numMovimento < $dado['numparcela']) {
                  ?>
                      <button class="btn btn-primary" id="gerarMovimento" type="button" data-toggle="modal" data-target="#modalMovimento" data-whatever=""><i class="fas fa-barcode"></i> Gerar movimento</button>
                  <?php
                    }
                  ?>

                </div>
              <?php
                }else{
              ?>
              <div class="text-center">
                <button class="btn btn-primary" id="salvarContrato" type="button"><i class="fas fa-save"></i> Salvar contrato</button>
              </div>
              <?php
                }
              ?>
            </div>
          </div>
        </form>
<?= include_once 'footer.php'; ?>
