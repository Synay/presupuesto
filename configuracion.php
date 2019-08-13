<?php
session_start();
require_once "./functions/funciones.php";
require_once "./functions/funcionesEmpresa.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['actualizar'])){
    $campos = [
      'editar_nombre_empresa' => 'Nombre de la empresa',
      'editar_rut_empresa' => 'RUT',
      'editar_direccion_empresa' => 'Dirección'
    ];

    $errores = validar3($campos);

    if(empty($errores)){
      $errores = editar_empresa();
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
$titulo = "Configuraciones | Sistema de control presupuestario"
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
          <h2>Configuraciones <b>Generales</b></h2>
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
          <h5>Configuración</h5>
          <form method="POST" id="formulario_empresa" name="formulario_empresa">
            <?php if(isset($_SESSION['idEmpresa'])){
              $id=$_SESSION['idEmpresa'];
              $info = obtenerInfoEmpresa($id); 
              foreach($info as $i){?>
                <div class="row">
                 <div class="col-md-8">
                  <input type="hidden" name="editar_id_empresa" id="editar_id_empresa" value="<?php print_r($i['id_empresa']);?>">
                  <div class="row">
                    <div class="col-sm-12">
                      <label>Empresa:</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i data-feather="user"></i></span>
                        </div>
                        <input type="text" class="form-control" name="editar_nombre_empresa" id="editar_nombre_empresa" value="<?php print_r($i['nombre_empresa']);?>" placeholder="Nombre de la empresa" tabindex="1">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <label>RUT:</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i data-feather="hash"></i></span>
                        </div>
                        <input type="text" class="form-control" name="editar_rut_empresa" id="editar_rut_empresa" value="<?php print_r($i['rut_empresa']);?>"  placeholder="RUT" tabindex="2">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <label>Dirección:</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i data-feather="map-pin"></i></span>
                        </div>
                        <input type="text" class="form-control input-medium" name="editar_direccion_empresa" id="editar_direccion_empresa" value="<?php print_r($i['direccion_empresa']);?>" placeholder="Dirección de la empresa" tabindex="3">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <label>Teléfono</label><sub> (Código de área + número. Ejemplo: <b>+56 65 2348911</b>)</sub>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i data-feather="phone"></i></span>
                        </div>
                        <input type="text" class="form-control" name="editar_contacto_empresa" id="editar_contacto_empresa" value="<?php print_r($i['telefono_empresa']);?>" tabindex="4">
                      </div>
                    </div>            
                  </div>
                <?php }} ?>
              </div>
              <div class="col-md-4 d-flex align-items-center">
                <button class="btn btn-info btn-lg btn-block" name="actualizar" type="submit">Actualizar datos</button>
              </div>
            </div>
          </form>
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