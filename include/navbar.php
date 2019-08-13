<!-- Contenedor de la barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #2c3e50;">
  <!--Logo y boton de expander y colapsar los enlaces-->
  <a class="navbar-brand" href="./index.php">
    <img src="./img/logo.png" class="img-fluid" width="70" height="90" alt=""> <b style="color:rgba(255, 255, 255, 0.5);">Sistema de Control Presupuestario</b>
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#enlaces" aria-controls="enlaces" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <!-- /Logo y boton de expander y colapsar los enlaces-->
  <!-- Enlaces de navegación-->
  <div id="enlaces" class="navbar-collapse collapse">
    <ul class="navbar-nav ml-auto align-items-end">
      <li class="nav-item dropdown">
        <?php if(isset($_SESSION['usuario'])) { ?>
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php
        echo $_SESSION["nombre"] ." ". $_SESSION["apellido"]; ?>
        <span class="badge badge-info">
          <?php if($_SESSION["privilegio"] ==1){
            echo 'Administrador';
          }
          if($_SESSION["privilegio"] >=2){
            echo 'Usuario';
          }
        
          ?>
        </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <?php if($_SESSION["privilegio"] ==1){
            echo '<a class="dropdown-item" href="usuarios.php"><i data-feather="users"></i> Usuarios</a>
            <a class="dropdown-item" href="configuracion.php"><i data-feather="settings"></i> Configuración</a><div class="dropdown-divider"></div>';
          
        }?>
        
        <a class="dropdown-item" href="logout.php"><i data-feather="log-out"></i> Salir</a>
        </div>
        </li>
        <?php }else{?>
        <li class="nav-item">
        <a class="nav-link" href="login.php"><i data-feather="log-in"></i> Acceder</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="registro.php"><i data-feather="edit"></i> Registrar</a>
        </li>
      <?php }?>
    </ul>
  </div>
  <!-- /Enlaces de navegación-->
</nav>
<!-- /Contenedor de la barra de navegación -->