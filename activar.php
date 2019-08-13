<?php
/**
** Página de activación para iniciar sesión
**/
session_start();
require_once "./functions/funciones.php";
require_once "./functions/funcionesUsuario.php";

if(empty($errores)){
	$errores = activar();
}

?>