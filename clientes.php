<?php
session_start();
require_once "./functions/funciones.php";
require_once "./functions/funcionesCliente.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['agregar'])){
    $campos = [
      'nombre_cliente' => 'Nombre de la empresa',
      'rut_cliente' => 'RUT',
      'email_cliente' => 'Correo Electrónico',
      'direccion' => 'Dirección'
    ];

    $errores = validar2($campos);

    if(empty($errores)){
      $errores = guardar_cliente();
    }

  }
  if(isset($_POST['editar'])){
    $campos = [
      'editar_nombre_cliente' => 'Nombre de la empresa',
      'editar_rut_cliente' => 'RUT',
      'editar_email_cliente' => 'Correo Electrónico',
      'editar_direccion' => 'Dirección'
    ];

    $errores = validar2($campos);

    if(empty($errores)){
      $errores = editar_cliente();
    }

  }
  if(isset($_POST['eliminar'])){
    if(empty($errores)){
      $errores = eliminar_cliente();
    }

  }
}


if(!isset($_SESSION['usuario'])) {
  header('Location: login.php');
}

$titulo = "Clientes | Sistema de control presupuestario"
?>
<!DOCTYPE html>
<html lang="es">
<!-- Contenedor de la cabecera-->

<?php include './include/header.php'; ?>
<!-- /Contenedor de la cabecera-->
<body>
  <!-- Contenedor de la barra de navegación-->
  <?php include './include/navbar.php'; ?>
  <!-- /Contenedor de la barra de navegación-->
  <!-- Contenedor principal (cuerpo de la página)-->
  <div class="container-fluid">
    <div class="row">
      <!-- Contenedor del menú-->
      <?php include_once './include/menu.php'; ?>
      <!-- /Contenedor del menú-->


      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">
          <h2>Administrar <b>Clientes</b></h2>
          <div class="btn-toolbar mb-2 mb-md-0">
            <a href="#agregarCliente" data-toggle="modal" data-target="#agregarCliente" name="agregar" class="btn btn-sm btn-success">
              <span data-feather="plus-circle" data-toggle="tooltip" data-placement="top" title="Agregar nuevo cliente"></span>
              Agregar
            </a>
          </div>
        </div>
        <?php
        if (isset($_SESSION['message'])) { ?>
          <div class="alert alert-<?= $_SESSION['message_type']?> alert-dismissible" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php  unset($_SESSION['message']);}

          if(!empty($errores)){
            echo mostrarErrores($errores);
          }


          ?>

          <table id="tablaClientes" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
              <tr>
                <th>Id</th>
                <th>Clientes</th>
                <th>RUT</th>
                <th>Correo Electrónico</th>
                <th>Dirección</th> 
                <th>Teléfono</th>
                <th>Celular</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php listar_clientes();
              ?>
            </tbody>
          </table>
        </main>
      </div>
    </div>
    <!-- /Contenedor principal (cuerpo de la página)-->
    <!-- Contenedor de paginas modal-->
    <?php include('./clientes/agregar.php'); ?>
    <?php include('./clientes/editar.php'); ?>
    <?php include('./clientes/eliminar.php'); ?>
    <!-- /Contenedor de paginas modal-->
    <!-- Contenedor del pie de página-->
    <?php include './include/footer.php'; ?>
    <!-- /Contenedor del pie de página-->
    <!-- Contenedor de los script-->
    <?php include './include/script.php'; ?>
    <!-- /Contenedor de los script-->
  </body>
  </html>