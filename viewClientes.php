<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("$base/class/class.cliente.php");
    include_once("$base/class/class.mascara.php");

    $cli = new Cliente();
    $m = new Mascara();

    $dados = $cli->buscaCliente();

    include_once 'header.php'; 

?>

<script type="text/javascript">
    function editarCliente(id_cliente) {
        $("#form").attr('action','editarCliente.php?id_cliente='+id_cliente);
        $("#form").submit();
        return true;
    }
    $( document ).ready(function() {
        $("#titulo").html("Clientes");

        $('#novoCliente').click(function(){
            $("#form").attr('action','cadastrarCliente.php');
            $("#form").submit();
            return true;
        });
    });
</script>

<form method=post name='form' id='form' enctype='multipart/form-data' action="cadastrarCliente.php">
    <div class="row">
      <div class="col-md-3">
        <button class="btn btn-lg btn-primary btn-block" id="novoCliente" type="button" href="#"><i class="fas fa-plus-circle"></i> Cadastar novo cliente</button>
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
                      <span style="cursor: pointer;" title="Gerenciar cliente" class="text-center" onclick="editarCliente('<?=$row['id_cliente']?>')"><i style="font-size: 12px" class="fas fa-pen-square"></i></span>
                    </td>
                    <td><?=str_pad($row['id_cliente'],7,'0', STR_PAD_LEFT)?></td>
                    <td><?=$m->OutMascaraCPF($row['cpf_cliente'])?></td>
                    <td><?=ucwords(strtolower($row['nome_cliente']))?></td>
                    <td><?=ucwords($row['status_cliente'])?></td>
                  </tr>                          
            <?php
                }
            }
            else{
        ?>
            <tr>
                <td colspan="6">Nenhum cliente encontrado.</td>
            </tr>
        <?php                        
            }
        ?>
    </tbody>
  </table>
</form>
<?= include_once 'footer.php'; ?>
