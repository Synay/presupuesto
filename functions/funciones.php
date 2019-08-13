<?php
/**
** Función de seguridad (inyección sql) 
**/
function limpiar($datos){
	//elimina espacio u otros tipos de caracteres del inicio al final de una cadena
	$datos = trim($datos);
	//quita las barras de un string con comillas escapadas
	$datos = stripslashes($datos);
	//convierte caracteres especiales en entidades HTML
	$datos = htmlspecialchars($datos);
	return $datos;

}
/**
** Función que muestra los errores como alertas
**/
function mostrarErrores($errores){
	$resultado = '<div class="alert alert-danger alert-dismissible fade show errores" role="alert">
	<ul>';
	foreach ($errores as $error) {
		$resultado .= '<li>'.htmlspecialchars($error) .'</li>';
	}
	$resultado .= '</ul></div>';
	return $resultado;

}

/**
** Función en contra de los ataques de CSRF
**/
function ficha_csrf(){
	$ficha = bin2hex(random_bytes(32));
	return $_SESSION['ficha'] = $ficha;
}

/**
** Función que valida una ficha
**/
function validar_ficha($ficha){
	if(isset($_SESSION['ficha']) && hash_equals($_SESSION['ficha'], $ficha)){
		unset($_SESSION['ficha']);
		return true;
	}
	return false;
}


/**
** Función que nombra al campo dado
**/
function campo($nombre){
	echo $_POST[$nombre] ?? '';
}
?>