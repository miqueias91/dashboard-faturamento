<?php
    include_once("./verifica.php");
	include_once("./config/config.php");
    include_once("./class/class.grupo.php");
    include_once("./menu.php");

    $gpo = new Grupo();

    $grupos = $gpo->buscaGrupo(null, null, $token_user);
    $options_gpo = "";      
    if($grupos){
        foreach ($grupos as $row) {
            $options_gpo .= '<option value="'.$row['id_grupo'].'">'.$row['nome_grupo'].'</option>\\n';
        }            
    }
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

    <link rel="stylesheet" href="css/jquery-ui.css">

    <!-- Optional Google Fonts -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">

    <!-- Optional JavaScript -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.mask.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>

    <script type="text/javascript">
        function removeGrupo(i){
            $('#destinatario_grupo'+i).remove();
        }
        $( document ).ready(function() {
            $('#telefone').mask('(99) 9 9999-9999');

            var num_grupo = 0;
            $('#maisgrupo').click(function(){
                num_grupo++;
                $('#grupoDestinatario').append(
                    '<div class="input-group mb-3" id="destinatario_grupo'+num_grupo+'">'+
                      '<div class="input-group-prepend">'+
                        '<button onClick="removeGrupo('+num_grupo+')" style="cursor:pointer" title="Excluir" class="btn btn-outline-secondary" type="button"><i class="fas fa-trash-alt"></i></button>'+
                      '</div>'+
                      '<select name="grupo[]" class="custom-select grupo" id="grupo'+num_grupo+'" aria-label="Example select with button addon">'+
                        '<option value="" selected>SELECIONE</option>'+
                        '<?=$options_gpo?>'+
                      '</select>'+
                    '</div>'
                );
            });

            $('#salvar').click(function(){
                var retorno = true;

                $(".grupo").each(function(){
                    value = $(this).val();
                    if(value == ""){
                        $(this).css('border-color','red');
                        retorno = false;                   
                    }else{
                        $(this).css('border-color','');
                    }
                });

                if ($('#nome_destinatario').val() == '') {
                    $('#nome_destinatario').css('border-color','red');
                    retorno = false;
                }else{
                    $('#nome_destinatario').css('border-color','');
                }

                if ($('#email_destinatario').val() == '') {
                    $('#email_destinatario').css('border-color','red');
                    retorno = false;
                }else{
                    $('#email_destinatario').css('border-color','');
                }

                if ($('#telefone').val() == '') {
                    $('#telefone').css('border-color','red');
                    retorno = false;
                }else{
                    $('#telefone').css('border-color','');
                }

            

                if (!retorno) {
                    alert('Existem campos não preenchidos!');
                }
                else{
                    if(confirm('Deseja realmente salvar?')){
                        $("#form").submit();
                        return true;
                    }
                    return false;
                }
            });
        });
    </script>


    <title>MESSENGER HERMES</title>
  </head>
  <body>
    <div id="cabecalho_titulo">
        <div id="titulos">            
            CADASTRAR&nbsp;CONTATO
        </div>            
    </div>
    <br>

    <div id="conteudo_sistema">
        <form method=post name='form' id='form' enctype='multipart/form-data' action="register_contato.php">
            <input type="hidden" name="token_user" value="<?=$_SESSION['token_user']?>">

            <div class="form-group">
                <label for="nome_destinatario"><a class="minimo">Nome</a></label>
                <input name="nome_destinatario" type="text" class="form-control" id="nome_destinatario" placeholder="">
            </div>

            <div class="form-group">
                <label for="email_destinatario"><a class="minimo">E-Mail</a></label>
                <input type="email" name="email_destinatario" class="form-control" id="email_destinatario" placeholder="name@example.com">
            </div>

            <div class="form-group">
                <label for="telefone"><a class="minimo">Telefone</a></label>
                <input type="text" name="telefone" class="form-control" id="telefone">
            </div> 

            <div id="grupoDestinatario">
                <label for="grupo0"><a class="minimo">Grupo</a></label>

                <div class="input-group mb-3">
                  <select name="grupo[]" class="custom-select grupo" id="grupo0">
                    <option value="" selected>SELECIONE</option>
                    <?=$options_gpo?>
                  </select>
                </div>
            </div>

            <center>                
                <button style="margin-top: 10px" title="Adicionar Grupo" id="maisgrupo" class="pequeno_botao" type="button"><i class="fas fa-plus-circle"></i> Adicionar Grupo</button>
                <button style="margin-top: 10px" title="Salvar" id="salvar" class="pequeno_botao" type="button"><i class="fas fa-check-circle"></i> Salvar</button>
            </center>

        </form>            	
    </div>
    <br>
    <br>
    <br>



  </body>
</html>