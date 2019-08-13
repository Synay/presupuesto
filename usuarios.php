<?php
session_start();
require_once "./functions/funciones.php";
require_once "./functions/funcionesUsuario.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['agregar'])){
    $campos = [
      'nombre' => 'Nombre',
      'apellido' => 'Apellido',
      'usuario' => 'Nombre de usuario',
      'email' => 'Correo Electrónico',
      'privilegio' => 'Departamento'
    ];

    $errores = validar($campos);

    if(empty($errores)){
      $errores = guardar_usuario();
    }

  }
  if(isset($_POST['editar'])){
    $campos = [
      'editar_nombre' => 'Nombre',
      'editar_apellido' => 'Apellido',
      'editar_usuario' => 'Nombre de usuario',
      'editar_email' => 'Correo Electrónico',
      'editar_privilegio' => 'Departamento'
    ];

    $errores = validar($campos);

    if(empty($errores)){
      $errores = editar_usuario();
    }

  }
  if(isset($_POST['eliminar'])){
    if(empty($errores)){
      $errores = eliminar_usuario();
    }
  }
}


if(isset($_SESSION["usuario"])) {
  if($_SESSION["privilegio"] >= 2){
    header('Location: index.php');
  }
}
else{
  header('Location: login.php');
}

$titulo = "Usuarios | Sistema de control presupuestario"
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
          <h2>Administrar <b>Usuarios</b></h2>
          <div class="btn-toolbar mb-2 mb-md-0">
            <a href="#agregarUsuario" data-toggle="modal" data-target="#agregarUsuario" name="agregar" class="btn btn-sm btn-success">
              <span data-feather="plus-circle" data-toggle="tooltip" data-placement="top" title="Agregar nuevo usuario"></span>
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

          <table id="tablaUsuarios" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
              <tr>
                <th>Id</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Correo Electrónico</th>
                <th>Usuario</th>
                <th>Departamento</th>  
                <th>Fecha Registro</th>      
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php listar_usuarios();
              ?>
            </tbody>
          </table>
        </main>
      </div>
    </div>
    <!-- /Contenedor principal (cuerpo de la página)-->
    <!-- Contenedor de paginas modal-->
    <?php include('./usuarios/agregar.php'); ?>
    <?php include('./usuarios/editar.php'); ?>
    <?php include('./usuarios/eliminar.php'); ?>
    <!-- /Contenedor de paginas modal-->
    <!-- Contenedor del pie de página-->
    <?php include './include/footer.php'; ?>
    <!-- /Contenedor del pie de página-->
    <!-- Contenedor de los script-->
    <?php include './include/script.php'; ?>
    <!-- /Contenedor de los script-->
  </body>
  </html>