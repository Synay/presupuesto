<?php
session_start();
require_once "./functions/funciones.php";
require_once "./functions/funcionesUsuario.php";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ficha']) && validar_ficha($_POST['ficha'])){
  if(!empty($_POST['miel'])){
    return header('Location: index.php');    
  }

  $campos = [
    'email' => 'Correo electrónico'
  ];

  $errores = validar($campos);

  if(empty($errores)){
    $errores = enviarClave();
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

$titulo = "Restablecer | Sistema de control presupuestario";
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
  <div class="container" id="pagina_reset">
    <!--Formulario de login-->
    <form method="POST" id="formulario_reset">
      <input type="hidden" name="ficha" value="<?php echo ficha_csrf();?>">
      <input type="hidden" name="miel" value="">
      <div class="row justify-content-center">
        <main role="main4" class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
          <?php
          if(!empty($errores)){
            echo mostrarErrores($errores);
          }
          ?>
          <div class="card">
            <div class="card-header">
              <h5> Restablecimiento de contraseña </h5>
            </div>
            <div class="card-body">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i data-feather="mail"></i></span>
                </div>
                <input type="text" class="form-control" name="email" value="<?php campo('email') ?>" placeholder="Correo electrónico" tabindex="1">
              </div>
              <button type="submit" class="btn btn-outline-info btn-block" name="resetBtn" tabindex="2"><i data-feather="send"></i></button>
            </div>
            <div class="card-footer text-muted text-center">
              O <a href="registro.php">Registrate</a>
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