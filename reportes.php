<?php
session_start();
require_once "./functions/funcionesUsuario.php";
require_once "./functions/funcionesCliente.php";
require_once "./functions/funcionesPresupuesto.php";

if(!isset($_SESSION['usuario'])) {
  header('Location: login.php');
}

$titulo = "Administrador | Sistema de control presupuestario"
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
          <h2>Administrar <b>Reportes</b></h2>

        </div>
            <table id="tablaPresupuestosReportes" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Obra</th>
                  <th>Creado Por:</th>
                  <th>Para:</th>
                  <th>Fecha</th>
                  <th>Fecha de término</th>
                  <th>Monto</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php listar_presupuestos_reportes(); 
                ?>
              </tbody>
            </table>
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