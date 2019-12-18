    <?php include_once 'header.php'; ?>

    



      <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>

        <!-- Icon Cards-->
        <div class="row">
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-money-bill-wave-alt"></i>
                </div>
                <div class="mr-5">200,00 Receita</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">Ver detalhe</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-file"></i>
                </div>
                <div class="mr-5">11 Contratos</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">Ver detalhe</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-shopping-cart"></i>
                </div>
                <div class="mr-5">123 Serviços</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">Ver detalhe</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-users"></i>
                </div>
                <div class="mr-5">13 Clientes</div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="#">
                <span class="float-left">Ver detalhe</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
        </div>
    
    <script src="<?=$PATH_JS;?>/Chart.min.js"></script>
    <?= include_once 'footer.php'; ?>
