<?php
session_start();
require_once "./functions/funcionesUsuario.php";
require_once "./functions/funcionesCliente.php";
require_once "./functions/funcionesPresupuesto.php";
require_once "./functions/funcionesGasto.php";

if(!isset($_SESSION['usuario'])) {
  header('Location: login.php');
}

$titulo = "Operaciones | Sistema de control presupuestario"
?>
<!DOCTYPE html>
<html lang="es">
<!-- Contenedor de la cabecera-->

<?php include_once 'include/header.php'; ?>
<!-- /Contenedor de la cabecera-->
<body>
  <!-- Contenedor de la barra de navegación-->
  <?php include_once 'include/navbar.php'; ?>
  <!-- /Contenedor de la barra de navegación-->
  <!-- Contenedor principal (cuerpo de la página)-->
  <div class="container-fluid">
    <div class="row">
      <!-- Contenedor del menú-->
      <?php include_once 'include/menu.php'; ?>
      <!-- /Contenedor del menú-->

      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h2><b>Inicio</b></h2>
        </div>

        <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">


        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Clientes</div>
                  <div class="h5 mb-0 font-weight-bold"><?php obtenerNumeroClientes()?></div>
                </div>
                <div class="col-auto">
                 <i data-feather="users" id="my-user"stroke-width="3"></i>
               </div>
             </div>
           </div>
         </div>
       </div>

       <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Presupuestos</div>
                <div class="h5 mb-0 font-weight-bold"><?php echo obtenerNumeroPresupuestos()?></div>
              </div>
              <div class="col-auto">
               <i data-feather="clipboard" id="my-user"stroke-width="3"></i>
             </div>
           </div>
         </div>
       </div>
     </div>

     <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Gasto Mensual anterior</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">$ <?php echo gastoMensualAnterior()?></div>
            </div>
            <div class="col-auto">
               <i data-feather="activity" id="my-user" stroke-width="3"></i>
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
   <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">
  <table id="tablaUltimosPresupuestos" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
    <thead>
      <tr>
        <th>Id</th>
        <th>Obra</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Fecha de término</th>
      </tr>
    </thead>
    <tbody>
      <?php listar_ultimos_presupuestos();
      ?>
    </tbody>
  </table>
</div>
</div>

</main>
</div>
</div>
<!-- /Contenedor principal (cuerpo de la página)-->
<!-- Contenedor del pie de página-->
<?php include_once 'include/footer.php'; ?>
<!-- /Contenedor del pie de página-->
<!-- Contenedor de los script-->
<?php include_once 'include/script.php'; ?>
<!-- /Contenedor de los script-->
</body>
</html>