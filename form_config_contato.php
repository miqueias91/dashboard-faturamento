<?php
    include_once("./verifica.php");
    include_once("./config/config.php");
    include_once("./class/class.destinatario.php");

    include_once("./menu.php");

    $des = new Destinatario();
    $pagina = 10;
    $inicio = isset($inicio) ? $inicio : '0';
    $final = isset($final) ? $final : $pagina;

    $quantContatos = $des->buscaDestinatario(null, null, null, null, $_SESSION["token_user"], null);

    $destinatarios = $des->buscaDestinatario(null, null, null, null, $_SESSION["token_user"], null, true, $inicio, $final);
?>

<!doctype html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Página Inicio">
    <meta name="author" content="Miqueias Matias Caetano">
    <meta name="keywords" content="Página Inicio">
    <meta content="pt-br, pt, en" http-equiv="Content-Language">
    <meta name="revised" content="2019-01-28">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="stylesheet" href="fontawesome-free-5.6.3-web/css/all.css">

    <!-- Optional Google Fonts -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">

    <!-- Optional JavaScript -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
        function removeDestinatario(id_destinatario) {
            if(confirm('Deseja realmente excluir?')){
                $("#form").attr('action','delete_contato.php?id_destinatario='+id_destinatario);
                $("#form").submit();
                return true;
            }
            return false;
        }
        function editDestinatario(id_destinatario) {
            $("#form").attr('action','edit_contato.php?id_destinatario='+id_destinatario+'&token_user=<?=$_SESSION["token_user"]?>');
            $("#form").submit();
            return true;
        }
        $( document ).ready(function() {
            $('#inserir').click(function(){
                $("#form").attr('action','insert_contato.php');
                $("#form").submit();
                return true;
            });
        });
    </script>

    <title>MESSENGER HERMES</title>
  </head>
  <body>
    <div id="cabecalho_titulo">
        <div id="titulos">            
            GERENCIAR&nbsp;CONTATOS
        </div>            
    </div>
    <br>

    <div id="conteudo_sistema">
        <button style="cursor:pointer; float: right;" title="Incluir Contato" class="pequeno_botao" id="inserir" type="button"><i class="fas fa-plus-square"></i> Incluir Contato</button>
        <br>
        <form method=post name='form' id='form' enctype='multipart/form-data' action="form_config_contato.php">
                    <br>

            <input type="hidden" name="token_user" value="<?=$_SESSION['token_user']?>">
        <?php
            if ($destinatarios) {
        ?>

                <table width="100%" align="center">
                    <tr style="background:grey;">
                        <td align="center"><a class="minimo" style="color:white">AÇÃO</a></td>
                        <td align="center"><a class="minimo" style="color:white">CÓDIGO CONTATO</a></td>
                        <td align="center"><a class="minimo" style="color:white">NOME</a></td>
                        <td align="center"><a class="minimo" style="color:white">E-MAIL</a></td>
                        <td align="center"><a class="minimo" style="color:white">TELEFONE</a></td>
                    </tr>
        <?php
                    foreach ($destinatarios as $row) {
        ?>    
                        <tr style="background: white; height: 20px;" >
                          <td width="200px" align="center" style="border: 1px solid #F1F1F1">
                            <button onClick="removeDestinatario('<?=$row["id_destinatario"]?>')" style="cursor:pointer" title="Excluir" class="pequeno_botao" type="button"><i class="fas fa-trash-alt"></i> Excluir</button> 
                            <button onClick="editDestinatario('<?=$row["id_destinatario"]?>')" style="cursor:pointer" title="Editar" class="pequeno_botao" type="button"><i class="fas fa-pen-square"></i> Editar</button>
                        </td>
                          <td width="100px" align="center" title="<?= str_pad($row['id_destinatario'],7,'0', STR_PAD_LEFT)?>" style="border: 1px solid #F1F1F1"><?= str_pad($row['id_destinatario'],7,'0', STR_PAD_LEFT)?></td>

                          <td width="200px" align="center" title="<?=$row['nome_destinatario']?>" style="border: 1px solid #F1F1F1"><?=$row['nome_destinatario']?></td>
                          
                          <td align="center" title="<?=$row['email_destinatario']?>" style="border: 1px solid #F1F1F1"><?=$row['email_destinatario']?></td>
                          <td width="100px" align="center" title="<?=$row['telefone']?>" style="border: 1px solid #F1F1F1"><?=$row['telefone']?></td>
                        </tr>                      
        <?php
          
                    }
        ?>
                </table>
                <br>
                <table align="center" width="100%">
                    <tr>
                        <td align="center">
                            <?php
                                $registro_final = $inicio+$pagina > sizeof($quantContatos) ? sizeof($quantContatos) : $inicio+$pagina;
                            ?>
                            <a class="minimo">
                                Listando de <?=$inicio;?> a <?=$registro_final;?> |
                            <?= sizeof($quantContatos); ?> Registros | 
                            </a>   

                            <?php
                                if ($inicio > 0) {
                            ?>
                                <a title="Anterior" class="minimo" href="./form_config_contato.php?inicio=<?=($inicio-$pagina)?>&final=<?=$pagina?>">&laquo; Anterior</a>
                            <?php
                                }else{
                            ?>
                                <a style="opacity:0.5" title="Anterior" class="minimo" href="#">&laquo; Anterior</a>
                            <?php                                    
                                }
                            ?>

                            <?php                                    
                                if ($inicio+$pagina > sizeof($quantContatos)) {
                            ?>
                                <a style="opacity:0.5" title="Próximo" class="minimo" href="#"> | Próximo &raquo;</a>
                            <?php
                                    
                                }else{
                            ?>
                                <a title="Próximo" class="minimo" href="./form_config_contato.php?inicio=<?=($inicio+$pagina)?>&final=<?=$pagina?>"> | Próximo &raquo;</a>
                            <?php
                                    
                                }
                            ?>
                        </td>                        
                    </tr>
                </table>
        <?php
          
            }else{

        ?>
	    <div style="margin-top:50px" class="alert alert-danger" role="alert">
	    	<center>	    		
				Nenhum contato cadastrado! Deseja <a href="./insert_contato.php">cadastrar?</a>
	    	</center>
		</div>
        <?php
          
            }           
        ?>
    	
        </form>
    </div>


  </body>
</html>