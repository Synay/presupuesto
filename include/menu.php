  <nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link <?= ($activePage == 'index' || $activePage == 'operaciones' || $activePage == 'adquisiciones' || $activePage == 'contabilidad' || $activePage == 'gerencia' || $activePage == 'admin') ? 'active':''; ?>" href="index.php">
            <span data-feather="home"></span>
            Inicio <span class="sr-only"></span>
          </a>
        </li>
        <?php if($_SESSION["privilegio"] !=4){?>
        <li class="nav-item">
          <a class="nav-link <?= ($activePage == 'presupuestos' || $activePage == 'presupuesto' || $activePage == 'categorias' || $activePage == 'subcategorias' || $activePage == 'insumos' || $activePage == 'precios' || $activePage == 'costos' || $activePage == 'gasto' || $activePage == 'agregar_gasto')  ? 'active':''; ?>" href="presupuestos.php">
            <span data-feather="file"></span>
            Presupuestos
          </a>
        </li>
      <?php }?>
        <li class="nav-item">
          <a class="nav-link <?= ($activePage == 'clientes') ? 'active':''; ?>" href="clientes.php">
            <span data-feather="users"></span>
            Clientes
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($activePage == 'reportes') ? 'active':''; ?>" href="reportes.php">
            <span data-feather="bar-chart-2"></span>
            Reportes
          </a>
        </li>

        <?php if($_SESSION["privilegio"] ==1){?>
          <li class="nav-item">
          <a class="nav-link <?= ($activePage == 'usuarios') ? 'active':''; ?>" href="usuarios.php">
            <span data-feather="users"></span>
            Usuarios
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($activePage == 'configuracion') ? 'active':''; ?>" href="configuracion.php">
            <span data-feather="settings"></span>
            Configuración
          </a>
        </li>
          
        <?php }?>
      </ul>
    </div>
  </nav>