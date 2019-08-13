<?php

/**
** Función que despliega una lista de clientes
**/
function listar_clientes(){
	require_once "./include/config.php";
	$con = DB::getConn();
	
	$dec = $con -> prepare('SELECT * FROM clientes');
	$dec->execute();
	if($dec->rowCount() >= 1){
		while($linea = $dec->fetch()){
			echo '<tr>
			<td>'. $linea['id_cliente'] .'</td>
			<td>'. $linea['nombre_cliente'] .'</td>
			<td>'. $linea['rut_cliente'] .'</td>
			<td>'. $linea['email_cliente'] .'</td>
			<td>'. $linea['direccion'] .'</td>
			<td>'. $linea['contacto'] .'</td>
			<td>'. $linea['contacto2'] .'</td>
			<td><a href="cliente.php?id='. $linea['id_cliente'] .'" class="btn btn-outline-warning" data-target="#editarCliente" data-toggle="modal"  data-id_cliente="'.  $linea['id_cliente'] .'"  data-nombre_cliente="'.  $linea['nombre_cliente'] .'"  data-rut_cliente="'.  $linea['rut_cliente'] .'" data-email_cliente="'.  $linea['email_cliente'] .'" data-direccion="'.  $linea['direccion'] .'"  data-contacto="'.  $linea['contacto'] .'" data-contacto2="'.  $linea['contacto2'] .'"><i data-feather="edit-3" data-toggle="tooltip" data-placement="bottom" name="editar" title="Editar" >&#xE254;</i></a>';
			if($_SESSION["privilegio"]==1){
				echo ' <a href="#eliminarCliente" class="btn btn-outline-danger" data-toggle="modal" data-target="#eliminarCliente"  data-id_cliente="'.  $linea['id_cliente'] .'"><i data-feather="trash-2" data-toggle="tooltip" data-placement="bottom" name="eliminar" title="Eliminar">&#xE872;</i></a>';
			}
			echo'</td></tr>';
		}
	}
	else{
		echo '<tr>
		<td colspan="10" class="text-center"> No hay datos para mostrar.</td>
		</tr>';
	}
}

/**
** Función que guarda un nuevo cliente
**/
function guardar_cliente(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$errores = duplicacion2($con);

	if(!empty($errores)){
		return $errores;
	}

	if (isset($_POST['agregar'])) {
		$nombre_cliente = limpiar($_POST['nombre_cliente']);
		$rut_cliente = limpiar($_POST['rut_cliente']);
		$email_cliente = limpiar($_POST['email_cliente']);
		$direccion = limpiar($_POST['direccion']);
		$contacto = limpiar($_POST['contacto']);
		$contacto2 = limpiar($_POST['contacto2']);

		$email_cliente = strtolower($_POST['email_cliente']);
	    //Inserta un cliente nuevo
		$dec = $con ->  prepare('INSERT INTO clientes (nombre_cliente, rut_cliente, email_cliente, direccion, contacto, contacto2) VALUES (:nombre_cliente, :rut_cliente, :email_cliente, :direccion, :contacto, :contacto2)');
		$dec -> bindParam(':nombre_cliente',$nombre_cliente);
		$dec -> bindParam(':rut_cliente',$rut_cliente);
		$dec -> bindParam(':email_cliente',$email_cliente);
		$dec -> bindParam(':direccion',$direccion);
		$dec -> bindParam(':contacto',$contacto);
		$dec -> bindParam(':contacto2',$contacto2);
		$dec -> execute();


		if($dec->rowCount() >= 1){
			$_SESSION['message'] = "Cliente agregado correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: clientes.php');
			exit;
		}
		else{
			$errores[] = 'Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.';
		}
	}

	return $errores;

}
/**
** Función que valida un campo específico del cliente
**/
function validar2($campos){
	$errores = [];
	foreach ($campos as $nombre => $mostrar) {
		if(!isset($_POST[$nombre]) || $_POST[$nombre] == NULL){
			$errores[] = $mostrar .' es un campo requerido.';
		}else{
			$validez = campos2();
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
function campos2(){
	$validacion = [
		'rut_cliente' => [
			'patron' => '/\d{1,2}\.\d{3}\.\d{3}[\-][0-9kK]{1}/i',
			'error' => 'El rut no puede quedar vacío.'
		],
		'email_cliente' => [
			'patron' => '/^[a-z]+[\w-\.]{2,}@([\w-]{2,}\.)+[\w-]{2,4}$/i',
			'error' => 'Correo electrónico debe ser en un formato válido.'
		]

	];
	return $validacion;
}
/**
** Función para verificar si no hay duplicación
** de email y rut
**/
function duplicacion2($con){
	$errores = [];

	$rut_cliente = limpiar($_POST['rut_cliente']);

	$dec = $con ->  prepare('SELECT rut_cliente FROM clientes WHERE rut_cliente = :rut_cliente');
	$dec -> bindParam(':rut_cliente', $rut_cliente);
	$dec -> execute();

	$resultado = $dec -> rowCount();

	if($resultado > 0 ){
		$errores[] = 'Este RUT ya esta siendo usado por un cliente.';
	}

	$email_cliente = limpiar($_POST['email_cliente']);

	//Selecciona el cliente mediante su correo electrónico
	$dec = $con ->  prepare('SELECT email_cliente FROM clientes WHERE email_cliente = :email_cliente');
	$dec -> bindParam(':email_cliente', $email_cliente);
	$dec -> execute();

	$resultado = $dec -> rowCount();

	if($resultado > 0 ){
		$errores[] = 'Este correo ya esta siendo usado por otro cliente.';
	}


	$contacto = limpiar($_POST['contacto']);

	if($contacto!=NULL){
		$dec = $con ->  prepare('SELECT contacto FROM clientes WHERE contacto = :contacto');
		$dec -> bindParam(':contacto', $contacto);
		$dec -> execute();

		$resultado = $dec -> rowCount();

		if($resultado > 0 ){
			$errores[] = 'Este telefono ya esta siendo usado por un cliente.';
		}
	}
	return $errores;
}
/**
** Función que edita al cliente
**/
function editar_cliente(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['editar'])){
		$nombre_cliente = limpiar($_POST['editar_nombre_cliente']);
		$rut_cliente = limpiar($_POST['editar_rut_cliente']);
		$email_cliente = limpiar($_POST['editar_email_cliente']);
		$direccion = limpiar($_POST['editar_direccion']);
		$contacto = limpiar($_POST['editar_contacto']);
		$contacto2 = limpiar($_POST['editar_contacto2']);
		$id_cliente=intval($_POST['editar_id_cliente']);

		$email_cliente = strtolower($_POST['editar_email_cliente']);

		//Actualizar un cliente
		$dec = $con ->  prepare('UPDATE clientes SET nombre_cliente=:nombre_cliente, rut_cliente=:rut_cliente, email_cliente=:email_cliente, direccion=:direccion, contacto=:contacto, contacto2=:contacto2 WHERE id_cliente=:id_cliente');
		$dec -> bindParam(':nombre_cliente',$nombre_cliente);
		$dec -> bindParam(':rut_cliente',$rut_cliente);
		$dec -> bindParam(':email_cliente',$email_cliente);
		$dec -> bindParam(':direccion',$direccion);
		$dec -> bindParam(':contacto',$contacto);
		$dec -> bindParam(':contacto2',$contacto2);
		$dec -> bindParam(':id_cliente',$id_cliente);
		$dec -> execute();


		if($dec->rowCount() >= 1){
			$_SESSION['message'] = "Cliente actualizado correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: clientes.php');
			exit;
		}
		else{
			$errores[] = 'No se ha efectuado cambios, verifique que haya cambiado un campo del cliente.';
		}
	}
	else{
		$errores[] = 'Lo sentimos, la actualización falló. Por favor, regrese y vuelva a intentarlo.';
	}

	return $errores;

}
/**
** Función que elimina cliente
**/
function eliminar_cliente(){
	require_once "./include/config.php";

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['eliminar'])){
		$id=intval($_POST['eliminar_id_cliente']);

		//Eliminar cliente
		$dec = $con ->  prepare('DELETE FROM clientes WHERE id_cliente=:id');
		$dec -> bindParam(':id',$id);
		$dec -> execute();


		if($dec->rowCount() >= 1){
			$_SESSION['message'] = "Cliente eliminado correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: clientes.php');
			exit;
		}
		else{
			$errores[] = 'Lo sentimos, la eliminación falló. Por favor, regrese y vuelva a intentarlo.';
		}

	}
	return $errores;

}
/**
** Función que obtiene la cantidad de clientes registrados
**/
function obtenerNumeroClientes(){
	require_once "./include/config.php";
	$con = DB::getConn();

	$total_usuarios = null;
	$dec = $con -> prepare('SELECT COUNT(*) as total FROM clientes');
	$dec->execute();
	$linea = $dec->fetch(PDO::FETCH_ASSOC);
	if($dec->rowCount() >= 1){
		$total_usuarios = $linea['total'];
		echo $total_usuarios;
	}else{
		echo "No hay clientes registrados";
	}	
}

/**
** Función que busca un cliente
**/
function buscar_clientes(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	$search = $_POST['busqueda_cliente'];

	$dec = $con -> prepare("SELECT * FROM clientes WHERE nombre_cliente LIKE :search LIMIT 20");
	$dec -> bindParam(':search',$search);
	$dec->execute();
	$linea = array();
	while($linea = $dec->fetch(PDO::FETCH_ASSOC)){
		$data[] = array('id_cliente' => $linea['id_cliente'], 'nombre_cliente' => $linea['nombre_cliente'],'rut_cliente' => $linea['rut_cliente'], 'contacto' => $linea['contacto']);
	}
	return $data;

}
