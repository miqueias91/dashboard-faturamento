<?php
include_once 'config/config.php';

  if (isset($_SESSION) && !empty($_SESSION)) {
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <!--<a class="navbar-brand" href="#">FATURAMENTO</a>-->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      
      <li class="nav-item">
        <a class="nav-link" href="viewInicio.php"><i class="fas fa-home"></i> Início <span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="#"><i class="fas fa-barcode"></i> Faturar</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="viewServicos.php"><i class="fas fa-dolly"></i> Serviços</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="viewClientes.php"><i class="fas fa-address-card"></i> Clientes</a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="contratos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-sticky-note"></i> Contratos</a>
        <div class="dropdown-menu" aria-labelledby="contratos">
          <a class="dropdown-item" href="cadastrarContrato.php">Incluir novo contrato</a>
          <a class="dropdown-item" href="viewGerenciarContrato.php">Gerenciar contrato</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="conta" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cogs"></i> Conta</a>
        <div class="dropdown-menu" aria-labelledby="conta">
          <a class="dropdown-item" href="#">Dados da empresa</a>
          <a class="dropdown-item" href="#">Alterar senha</a>
          <a class="dropdown-item" href="desloga.php" id="deslogar">Sair</a>
        </div>
      </li>

    </ul>
    <!--<form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
      <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
    </form>-->
  </div>
</nav>
  <?php
    }
  ?>