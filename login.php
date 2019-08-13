<?php
session_start();
require_once "./functions/funciones.php";
require_once "./functions/funcionesUsuario.php";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ficha']) && validar_ficha($_POST['ficha'])){
  if(!empty($_POST['miel'])){
    return header('Location: index.php');    
  }

  $campos = [
    'usuarioOemail' => 'Nombre de usuario o correo electrónico',
    'clave' => 'Contraseña'
  ];

  $errores = validar($campos);

  if(empty($errores)){
    $errores = login();
  }
}

if(isset($_SESSION["usuario"])) {
  if($_SESSION["privilegio"] == 1){
    header('Location: admin.php');
  }
  else{
    header('Location: index.php');
  }  
}

$titulo = "Acceder | Sistema de control presupuestario";
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
  <div class="container" id="pagina_login">
    <!--Formulario de login-->
    <form method="POST" id="formulario_login">
      <input type="hidden" name="ficha" value="<?php echo ficha_csrf();?>">
      <input type="hidden" name="miel" value="">
      <div class="row justify-content-center">
        <main role="main2" class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
          <?php
          if(!empty($errores)){
            echo mostrarErrores($errores);
          } 

          if (isset($_SESSION['message'])) { ?>
            <div class="alert alert-<?= $_SESSION['message_type']?> alert-dismissible fade show" role="alert">
              <?= $_SESSION['message']?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php unset($_SESSION['message']); } ?>
          <div class="card">
            <div class="card-header text-center">
              <h4 class="font-weight-bold"> Acceder </h4>
            </div>
            <div class="card-body">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i data-feather="mail"></i></span>
                </div>
                <input type="text" class="form-control" name="usuarioOemail" value="<?php campo('usuarioOemail') ?>" placeholder="Nombre de usuario o correo electrónico" tabindex="1">
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i data-feather="key"></i></span>
                </div>
                <input type="password" class="form-control" name="clave" placeholder="Contraseña" tabindex="2">
              </div> 
              <button type="submit" class="btn btn-outline-success btn-block" name="loginBtn" tabindex="3">Entrar</button>
            </div>
            <div class="card-footer text-muted text-center">
              <div class="row">
                <div class="col-md-12">  <a href="restablecer.php">¿Olvidaste tu contraseña?</a>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">  
              o <a href="registro.php">Registrate</a></div>
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