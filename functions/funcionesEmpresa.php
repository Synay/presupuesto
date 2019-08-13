<?php
/**
** Funcion que obtiene la información del usuario mediante su id
**/ 
function obtenerInfoEmpresa($id){
	require_once "./include/config.php";
	$con = DB::getConn();

	$dec = $con -> prepare('SELECT e.id_empresa, e.nombre_empresa, e.rut_empresa, e.direccion_empresa, e.telefono_empresa FROM usuarios u
		INNER JOIN empresas e ON u.id_empresa = e.id_empresa where u.id= :id');
	$dec ->bindParam(':id',$id);
	$dec->execute();

	while($linea = $dec->fetch(PDO::FETCH_ASSOC)){
		$info[]=$linea;

	}
	return $info;

}

/**
** Función que valida los campos de empresa
**/
function validar3($campos){
	$errores = [];
	foreach ($campos as $nombre => $mostrar) {
		if(!isset($_POST[$nombre]) || $_POST[$nombre] == NULL){
			$errores[] = $mostrar .' es un campo requerido.';
		}else{
			$validez = campos3();
			foreach ($validez as $campo => $opcion) {
				if($nombre == $campo){
					if(!preg_match($opcion['patron'], $_POST[$nombre])){
						$errores[] = $opcion['error'];
					}
				}
			}
		}
	}
	return $errores;
}

/**
** Función mediante validación de varios campos
**/
function campos3(){
	$validacion = [
		'rut_empresa' => [
			'patron' => '/\d{1,2}\.\d{3}\.\d{3}[\-][0-9kK]{1}/i',
			'error' => 'El rut no puede quedar vacío.'
		]

	];
	return $validacion;
}

/**
** Función que actualiza la información de la empresa
**/
function editar_empresa(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['actualizar'])){
		$nombre_empresa = limpiar($_POST['editar_nombre_empresa']);
		$rut_empresa = limpiar($_POST['editar_rut_empresa']);
		$direccion = limpiar($_POST['editar_direccion_empresa']);
		$contacto = limpiar($_POST['editar_contacto_empresa']);
		$id_empresa=intval($_POST['editar_id_empresa']);

		//Actualiza la información de la empresa
		$dec = $con ->  prepare('UPDATE empresas SET nombre_empresa=:nombre_empresa, rut_empresa=:rut_empresa, direccion_empresa=:direccion, telefono_empresa=:contacto WHERE id_empresa=:id_empresa');
		$dec -> bindParam(':nombre_empresa',$nombre_empresa);
		$dec -> bindParam(':rut_empresa',$rut_empresa);
		$dec -> bindParam(':direccion',$direccion);
		$dec -> bindParam(':contacto',$contacto);
		$dec -> bindParam(':id_empresa',$id_empresa);
		$dec -> execute();


		if($dec->rowCount() >= 1){
			$_SESSION['message'] = "Configuración actualizada correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: configuracion.php');
			exit;
		}
		else{
			$errores[] = 'No se ha efectuado cambios, verifique que haya cambiado un campo de la configuración.';
		}
	}
	else{
		$errores[] = 'Lo sentimos, la actualización falló. Por favor, regrese y vuelva a intentarlo.';
	}
	return $errores;

}

?>