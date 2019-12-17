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


    include_once 'header.php'; 
?>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#titulo").html("Editar serviço");

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
<form action="salvarServico.php?id_servico=<?=$id_servico?>" method='post' name='form' class="" id='form' enctype='multipart/form-data'>
<div class="row">
  <div class="col-md-3">
    <button class="btn btn-lg btn-primary btn-block" id="salvarAlteracaoServico" type="button">Salvar alteração</button>
  </div>
</div>
<br>
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
<?php include_once 'footer.php'; ?>
