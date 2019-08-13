<?php
session_start();
require_once "./functions/funciones.php";
require_once "./functions/funcionesUsuario.php";

if(isset($_GET['key'])){
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ficha']) && validar_ficha($_POST['ficha'])){
    if(!empty($_POST['miel'])){
      return header('Location: index.php');
    }
    $campos = [
      'clave' => 'Contraseña',
      're_clave' => 'Repetir contraseña'
    ];

    $errores = validar($campos);
    $errores = array_merge($errores, comparador_claves($_POST['clave'], $_POST['re_clave']));

    if(empty($errores)){
      $errores = restablecerClave();
    }
  }
}else{
  header('Location: login.php');
}

if(isset($_SESSION["usuario"])) {
  if($_SESSION["privilegio"] == 1){
    header('Location: admin.php');
  }
  else{
    header('Location: index.php');
  }  
}

$titulo = "Cambiar contraseña | Sistema de control presupuestario";
require_once "./include/config.php";

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
  <div class="container" id="pagina_resetP">
    <!--Formulario de Registro-->
    <form method="POST" id="formulario_resetP">
      <input type="hidden" name="ficha" value="<?php echo ficha_csrf();?>">
      <input type="hidden" name="miel" value="">
      <div class="row justify-content-center">

        <main role="main4" class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
          <?php
          if(!empty($errores)){
            echo mostrarErrores($errores);
          }
          ?>
          <div class="card">
            <div class="card-header">
              <h5> Cambiar contraseña </h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i data-feather="lock"></i></span>
                    </div>
                    <input type="password" class="form-control" name="clave" placeholder="Contraseña" tabindex="1" id="clave">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i data-feather="lock"></i></span>
                    </div>
                    <input type="password" class="form-control" name="re_clave" placeholder="Repetir Contraseña" tabindex="2">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <button type="submit" class="btn btn-outline-success btn-block" name="registroBtn" tabindex="9">Aceptar</button>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </form>
    <!-- /Formulario de Registro-->
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