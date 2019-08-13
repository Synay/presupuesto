<?php
session_start();
require_once "./functions/funciones.php";
require_once "./functions/funcionesPresupuesto.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['cotizar'])){
    if(empty($errores)){
      $errores = cotizar_presupuesto();
    }
  }
  if(isset($_POST['ejecucion'])){
    if(empty($errores)){
      $errores = validar2_presupuesto();
    }
  }
  if(isset($_POST['controlar'])){
    if(empty($errores)){
      $errores = validar3_presupuesto();
    }
  }
  if(isset($_POST['eliminar'])){
    if(empty($errores)){
      $errores = eliminar_presupuesto();
    }
  }
}

if(isset($_SESSION["usuario"])) {
  if($_SESSION["privilegio"] == 4){
    header('Location: index.php');
  }
}
else{
  header('Location: login.php');
}


$titulo = "Presupuestos | Sistema de control presupuestario"
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
          <h2>Administrar <b>Presupuestos</b></h2>
          <?php if($_SESSION["privilegio"]==1 || $_SESSION["privilegio"]==5){?>
            <div class="btn-toolbar mb-2 mb-md-0">
              <a href="./presupuesto.php" name="agregar" class="btn btn-sm btn-success">
                <span data-feather="plus-circle" data-toggle="tooltip" data-placement="top" title="Agregar presupuesto"></span>
                Agregar
              </a>
            </div>
          <?php }?>
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
          <?php if($_SESSION["privilegio"]==1 || $_SESSION["privilegio"]==5){?>
            <h4>Elaboración del presupuesto</h4>
            <table id="tablaPresupuestos1" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Obra</th>
                  <th>Creado Por:</th>
                  <th>Para:</th>
                  <th>Fecha</th>
                  <th>Fecha de término</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php listar_presupuestos_inicial(); 
                ?>
              </tbody>
            </table>
            <hr>
          <?php } 
          if($_SESSION["privilegio"]==1 || $_SESSION["privilegio"]==2){
            ?>
            <h4>Cotización del presupuesto</h4>
            <table id="tablaPresupuestos2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Obra</th>
                  <th>Creado Por:</th>
                  <th>Para:</th>
                  <th>Fecha</th>
                  <th>Fecha de término</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php listar_presupuestos2(); 
                ?>
              </tbody>
            </table>
            <hr>
          <?php } 
          if($_SESSION["privilegio"]==1 || $_SESSION["privilegio"]==5){
            ?>
            <h4>Aprobación del presupuesto</h4>
            <table id="tablaPresupuestos3" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Obra</th>
                  <th>Creado Por:</th>
                  <th>Para:</th>
                  <th>Fecha</th>
                  <th>Fecha de término</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php listar_presupuestos3(); 
                ?>
              </tbody>
            </table>
            <hr>
          <?php } 
          if($_SESSION["privilegio"]==1 || $_SESSION["privilegio"]==3){
            ?>
            <h4>Control presupuestal</h4>
            <table id="tablaPresupuestos4" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Obra</th>
                  <th>Creado Por:</th>
                  <th>Para:</th>
                  <th>Fecha</th>
                  <th>Fecha de término</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php listar_presupuestos4(); 
                ?>
              </tbody>
            </table>
            <hr>
          <?php }?>
        </main>
      </div>
    </div>
    <!-- /Contenedor principal (cuerpo de la página)-->
    <div id="divPresupuesto"></div>
    <div id="divPresupuesto2"></div>
    <div id="divPresupuesto3"></div>
    <?php include('./presupuestos/cotizarPresupuesto.php'); ?>
    <?php include('./presupuestos/ejecucionPresupuesto.php'); ?>
    <?php include('./presupuestos/controlarPresupuesto.php'); ?>
    <?php include('./presupuestos/eliminar.php'); ?>
    <!-- Contenedor del pie de página-->
    <?php include './include/footer.php'; ?>
    <!-- /Contenedor del pie de página-->
    <!-- Contenedor de los script-->
    <?php include './include/script.php'; ?>
    <!-- /Contenedor de los script-->
  </body>
  </html>