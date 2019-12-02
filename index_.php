<?php
    @ session_start();
    extract($_SESSION);
    include_once("./config/config.php");

    //Verifica se há dados ativos na sessão
    if(isset($_SESSION["token_user"]) && isset($_SESSION["usuario"])){
        echo "<script>window.location.href = './viewInicio.php';</script>";
    }
    else{
?>

<!doctype html>
<html lang="pt-br">
    <head>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Miqueias Matias Caetano">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?=$PATH_CSS;?>/bootstrap.min.css">
        <link rel="stylesheet" href="<?=$PATH_CSS;?>/singin.css">
        <link rel="stylesheet" href="./fontawesome-free-5.6.3-web/css/all.css">

        <!-- Optional Google Fonts -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
        <link rel="stylesheet" href="<?=$PATH_CSS;?>/stylesheet.css">

        <!-- Optional JavaScript -->
        <script src="<?=$PATH_JS;?>/jquery-3.4.1.min.js"></script>
        <script src="<?=$PATH_JS;?>/popper.min.js"></script>
        <script src="<?=$PATH_JS;?>/bootstrap.min.js"></script>
    </head>
    <body>
        <script type="text/javascript">        
            $( document ).ready(function() {
                $('#entrar').click(function(){
                    var retorno = true;

                    if ($('#usuario').val() == '') {
                        $('#usuario').css('border-color','red');
                        retorno = false;
                    }else{
                        $('#usuario').css('border-color','');
                    }

                    if ($('#password').val() == '') {
                        $('#password').css('border-color','red');
                        retorno = false;
                    }else{
                        $('#password').css('border-color','');
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

        <style type="text/css">
            #formContent {
                max-width: 400px;
            }
        </style>
        <?php include_once("./menu.php"); ?>
        <div class="wrapper fadeInDown">
          <div id="formContent">
            <div class="fadeIn first">
                <br>
                <h1>ACESSO RESTRITO</h1>
            </div>
            <form method=post name='form' id='form' enctype='multipart/form-data' action="./entra.php">
                <div class="container">
                    <div class="row">
                        <div class="col-md">
                            <input type="text" id="usuario" class="form-control fadeIn second" name="usuario" placeholder="Digite seu usuário" required>                            
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <input type="password" id="password" class="form-control fadeIn third" name="password" placeholder="Digite sua senha" required>                            
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <button type="button" id="entrar" class="btn btn-lg btn-block btn-primary mb-3 fadeIn fourth">Entrar</button>                            
                        </div>                        
                    </div>
                    
                </div>

                
            </form>
            <div id="formFooter">
                <p>Gerenciado por
              <a style="text-decoration: none;" class="underlineHover" target="T_BLANK" href="https://miqueiasmcaetano.000webhostapp.com/">MIQUEIAS M CAETANO</a></p>
            </div>
          </div>
        </div>
    </body>
</html>











<?php
    }
?>