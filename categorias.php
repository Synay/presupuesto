<?php
session_start();
require_once "./functions/funcionesEmpresa.php";
require_once "./functions/funcionesCliente.php";
require_once "./functions/funcionesPresupuesto.php";
require_once "./functions/funcionesCategoria.php";

if(isset($_SESSION["usuario"])) {
  if($_SESSION["privilegio"] >=2 && $_SESSION["privilegio"] < 5){
    header('Location: index.php');
  }
}
else{
  header('Location: login.php');
}

if(isset($_GET['idPresupuesto']) && !empty($_GET['idPresupuesto'])){
  $id=$_GET['idPresupuesto'];
  if(obtener_presupuesto($id)===true){
    $detalle = obtener_gestion_presupuesto($id);
  }else{
   header('Location: presupuestos.php');
 }
}else{
  header('Location: index.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['guardar'])){
    if(empty($errores)){
      $errores = guardar_categoria();
    }
  }
}

$titulo = "Categorías | Sistema de control presupuestario"
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
         <h1 class="h2"><b>Presupuesto</b></h1> <h1>N° <?php
         echo $id; ?></h1> 
       </div>
       <div class="container">
        <div class="row">
         <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Datos de la empresa</span>
          </h4>
          <?php
          foreach($detalle as $d){?>
            <ul class="list-group mb-3">
              <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                  <h6 class="text-muted">Nombre</h6>
                </div>
                <span class="my-0"><?php print_r($d['NombreE']);?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                  <h6 class="text-muted">RUT</h6>
                </div>
                <span class="my-0"><?php print_r($d['RutE']);?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                  <h6 class="text-muted">Dirección</h6>
                </div>
                <span class="my-0"><?php print_r($d['Direccion']);?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                  <h6 class="text-muted">Teléfono</h6>
                </div>
                <span class="my-0"><?php print_r($d['Telefono']);?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between bg-light">
                <div class="text-muted">
                  <h6>Gestionado</h6>
                </div>
                <span class="my-0"><?php print_r($d['NombreU']) .print_r("\t"). print_r($d['ApellidoU']);?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between bg-light">
                <div>
                  <h6 class="text-muted">Departamento</h6>
                </div>
                <span class="my-0"><?php switch($d['PrivilegioU']){
                  case 1:
                  echo "Informática";
                  break;
                  case 2:
                  echo"Adquisiciones";
                  break;
                  case 3:
                  echo "Contabilidad";
                  break;
                  case 4:
                  echo "Gerencia";
                  break;
                  case 5:
                  echo "Operaciones";
                  break;
                }?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between bg-light">
                <div>
                  <h6 class="text-muted">Correo electrónico</h6>
                </div>
                <span class="my-0"><?php print_r($d['EmailU']);?></span>
              </li>
            </ul>
          <?php } ?>
        </div>
        <div class="col-md-8 order-md-1">
          <form method="POST" id="formulario_categoria" name="formulario_categoria">
            <h4 class="mb-3">Categorías</h4>
            <div class="row">
              <div class="col-sm-12 mb-3">
               <div id="form_div">
                <table class="table" id="categoria_tabla" align=center>
                 <tr id="row1">
                  <td>
                    <div class="input-group mb-3"><input class="categoria form-control" type="text" name="categoria[]" placeholder="Nombre de la categoría" required>
                    </div></td>
                  </tr>
                </table>
                <button type="button" class="add btn btn-success" onclick="add_row();">Agregar</button>
              </div>
            </div>
          </div>

          <button class="btn btn-primary btn-lg btn-block" name="guardar" type="submit">Siguiente</button>
        </form>
      </div>
    </div>
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