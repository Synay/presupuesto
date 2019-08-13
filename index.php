<?php
session_start();

if(isset($_SESSION["usuario"])) {
  switch($_SESSION["privilegio"]){
    case 1:
    header('Location: admin.php');
    break;
    case 2:
    header('Location: adquisiciones.php');
    break;
    case 3:
    header('Location: contabilidad.php');
    break;
    case 4:
    header('Location: gerencia.php');
    break;
    case 5:
    header('Location: operaciones.php');
    break;
  }
}
else{
  header('Location: login.php');
}

?>
