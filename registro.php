<?php
session_start();
require_once "./functions/funciones.php";
require_once "./functions/funcionesUsuario.php";
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ficha']) && validar_ficha($_POST['ficha'])){
  if(!empty($_POST['miel'])){
    return header('Location: index.php');
  }
  $campos = [
    'nombre' => 'Nombre',
    'apellido' => 'Apellido',
    'usuario' => 'Nombre de usuario',
    'email' => 'Correo Electrónico',
    'privilegio' => 'Departamento',
    'clave' => 'Contraseña',
    're_clave' => 'Repetir contraseña',
    'terminos' => 'Términos y condiciones'

  ];

  $errores = validar($campos);
  $errores = array_merge($errores, comparador_claves($_POST['clave'], $_POST['re_clave']));

  if(empty($errores)){
    $errores = registro();
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

$titulo = "Registro | Sistema de control presupuestario";
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
  <div class="container" id="pagina_registro">
    <!--Formulario de Registro-->
    <form method="POST" id="formulario_registro">
      <input type="hidden" name="ficha" value="<?php echo ficha_csrf();?>">
      <input type="hidden" name="miel" value="">
      <div class="row justify-content-center">

        <main role="main3" class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
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
                <h4 class="font-weight-bold"> Registro </h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i data-feather="user"></i></span>
                      </div>
                      <input type="text" class="form-control" name="nombre" value="<?php campo('nombre') ?>" placeholder="Nombre" tabindex="1" maxlength="50" autofocus>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i data-feather="user"></i></span>
                      </div>
                      <input type="text" class="form-control" name="apellido" value="<?php campo('apellido') ?>" placeholder="Apellido" tabindex="2" maxlength="150">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i data-feather="mail"></i></span>
                      </div>
                      <input type="email" class="form-control" name="email" value="<?php campo('email') ?>" placeholder="Correo electrónico" tabindex="3" maxlength="150">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i data-feather="user"></i></span>
                      </div>
                      <input type="text" class="form-control" name="usuario" value="<?php campo('usuario') ?>" placeholder="Nombre de usuario" tabindex="4" maxlength="50">
                    </div>
                  </div>
                  
                  <div class="col-sm-12">
                    <div class="input-group mb-3">
                      <select class="custom-select" name="privilegio" tabindex="5" id="privilegio" >
                        <option value="" selected>Elegir área...</option>
                        <option value="2">Adquisiciones</option>
                        <option value="3">Contabilidad</option>
                        <option value="4">Gerencia</option>
                        <option value="5">Operaciones</option>
                      </select>
                    </div>
                  </div>              
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i data-feather="lock"></i></span>
                      </div>
                      <input type="password" class="form-control" name="clave" placeholder="Contraseña" tabindex="6" id="clave">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i data-feather="lock"></i></span>
                      </div>
                      <input type="password" class="form-control" name="re_clave" placeholder="Repetir contraseña" tabindex="7">
                    </div>
                  </div>
                </div>

                <div class="row">
                 <div class="col-sm-3">
                   <label class="btn btn-primary btn-block" data-toggle="tooltip" data-placement="bottom" title="Al registrarme estoy aceptando los términos y condiciones acordados por esta página, incluyendo el uso de Cookies">
                    Acepto <input type="checkbox" name="terminos" tabindex="8" <?php if(isset($_POST['terminos'])){ echo "checked='checked'";} ?>>  <span class="sr-only">unread messages</span>
                  </label>
                </div>
                <div class="col-sm-9">
                  <button type="submit" class="btn btn-outline-success btn-block" name="registroBtn" tabindex="9">Registrar</button>
                </div>
              </div>
            </div>
            <div class="card-footer text-muted text-center">
              Si tienes cuenta <a href="login.php">Inicia Sesión</a>
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