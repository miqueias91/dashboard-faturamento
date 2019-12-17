<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.contrato.php");
    include_once("$base/class/class.mascara.php");

    $cont = new Contrato();
    $m = new Mascara();

    $dados = $cont->buscaContrato();


  include_once 'header.php'; 
?>
    <script type="text/javascript">
        function cadastrarContrato(id_contrato) {
            $("#form").attr('action','cadastrarContrato.php?id_contrato='+id_contrato);
            $("#form").submit();
            return true;
        }
        $( document ).ready(function() {
            $("#titulo").html("Contratos");
            $('#novoContrato').click(function(){
                $("#form").attr('action','cadastrarContrato.php');
                $("#form").submit();
                return true;
            });
        });
    </script>


<form method=post name='form' id='form' enctype='multipart/form-data' action="cadastrarContrato.php">
    <div class="row">
      <div class="col-md-3">
        <button class="btn btn-lg btn-primary btn-block" id="novoContrato" type="button" href="#"><i class="fas fa-plus-circle"></i> Cadastar novo contrato</button>
      </div>
    </div>
    <br>
<table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>#</th>
        <th>AÇÕES</th>
        <th>CÓDIGO</th>
        <th>CPF DO CLIENTE</th>
        <th>NOME DO CLIENTE</th>
        <th>DATA DO CADASTRO</th>
        <th>DATA DE INICIO</th>
        <th>DATA DE TERMINO</th>
        <th>VALOR DA PARCELA</th>
        <th>STATUS</th>
      </tr>
    </thead>
    <tbody>
        <?php
            if ($dados) {
                foreach ($dados as $key => $row) {
        ?>
                  <tr>
                    <td><?=($key+1)?></td>
                    <td>
                        <span style="cursor: pointer;" title="Gerenciar contrato" class="text-center" onclick="cadastrarContrato('<?=$row['id_contrato']?>')"><i style="font-size: 12px" class="fas fa-pen-square"></i></span>                              
                    </td>
                    <td><?=str_pad($row['id_contrato'],7,'0', STR_PAD_LEFT)?></td>
                    <td width="150"><?=$m->OutMascaraCPF($row['cpf_cliente'])?></td>
                    <td width="200" align="left"><?=ucwords(strtolower($row['nome_cliente']))?></td>
                    <td><?=date('d/m/Y', strtotime($row['datacadastro']))?></td>
                    <td><?=date('d/m/Y', strtotime($row['datainicio']))?></td>
                    <td><?=date('d/m/Y', strtotime($row['datafinal']))?></td>
                    <td>R$ <?=number_format($row['valorparcela'],2,",","")?></td>
                    <td><?=ucwords($row['status_contrato'])?></td>
                  </tr>                          
            <?php
                }
            }
            else{
        ?>
            <tr>
                <td colspan="10">Nenhum contrato encontrado.</td>
            </tr>
        <?php                        
            }
        ?>
    </tbody>
  </table>
</form>
<?= include_once 'footer.php'; ?>
