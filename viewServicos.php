<?php
  include_once("./verifica.php");
  include_once("./config/config.php");
  include_once("$base/class/class.servico.php");

  $ser = new Servico();

  $dados = $ser->buscaServico();
  include_once 'header.php'; 
?>
<script type="text/javascript">
    function editarServico(id_servico) {
        $("#form").attr('action','editarServico.php?id_servico='+id_servico);
        $("#form").submit();
        return true;
    }
    $( document ).ready(function() {
        $("#titulo").html("Serviços");
        $('#novoServico').click(function(){
            $("#form").attr('action','cadastrarServico.php');
            $("#form").submit();
            return true;
        });
    });
</script>
<form method=post name='form' id='form' enctype='multipart/form-data' action="cadastrarServico.php">
    <div class="row">
      <div class="col-md-3">
        <button class="btn btn-lg btn-primary btn-block" id="novoServico" type="button" href="#"><i class="fas fa-plus-circle"></i> Cadastar novo serviço</button>
      </div>
    </div>
    <br>
    <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>#</th>
        <th>AÇÕES</th>
        <th>CÓDIGO</th>
        <th>DESCRIÇÃO</th>
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
                      <span style="cursor: pointer;" title="Gerenciar serviço" class="text-center" onclick="editarServico('<?=$row['id_servico']?>')"><i style="font-size: 12px" class="fas fa-pen-square"></i></span>
                    </td>
                    <td><?=str_pad($row['id_servico'],7,'0', STR_PAD_LEFT)?></td>
                    <td><?=ucwords($row['descricao_servico'])?></td>
                    <td><?=ucwords($row['status_servico'])?></td>
                  </tr>                          
            <?php
                }
            }
            else{
        ?>
            <tr>
                <td colspan="5">Nenhum serviço encontrado.</td>
            </tr>
        <?php                        
            }
        ?>
    </tbody>
  </table>
</form>
<?= include_once 'footer.php'; ?>
