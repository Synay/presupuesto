<?php
/**
** Función de registro de usuarios, enviando un email
** para activar
**/
function registro(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	require_once "./functions/phpmailer/mail.php";
	$errores = duplicacion($con);

	//Verifica si hay errores
	if(!empty($errores)){
		return $errores;
	}

	//Limpia los campos
	$nombre = limpiar($_POST['nombre']);
	$apellido = limpiar($_POST['apellido']);
	$usuario = limpiar($_POST['usuario']);
	$email = limpiar($_POST['email']);
	$privilegio = $_POST['privilegio'];
	$clave = limpiar($_POST['clave']);

	//Convierte el campo email a minúsculas
	$email = strtolower($_POST['email']);
	//Genera un hash md5 
	$activo = md5(uniqid(rand(),true));

	//Inserta un usuario nuevo
	$dec = $con ->  prepare('INSERT INTO usuarios (nombre, apellido, usuario, email, privilegio, clave, activo) VALUES (:nombre, :apellido, :usuario, :email,:privilegio, :clave, :activo)');
	$dec -> bindParam(':nombre',$nombre);
	$dec -> bindParam(':apellido',$apellido);
	$dec -> bindParam(':usuario',$usuario);
	$dec -> bindParam(':email',$email);
	$dec -> bindParam(':privilegio',$privilegio);
	$dec -> bindParam(':clave',password_hash($clave, PASSWORD_DEFAULT));
	$dec -> bindParam(':activo',$activo);
	$dec -> execute();

	$resultado = $dec->rowCount();

	if($resultado == 1){
		$id = $con->lastInsertId('id');

	    //Enviar correo electrónico
		$to = $_POST['email'];
		$subject = "Confirmación de registro";
		$body = "<p>Gracias por registrarte al Sistema de pruebas para control de presupuestos marítimos.</p>
		<p>Para activar tu cuenta, por favor pincha en este enlace: <a href='".DIR."activar.php?x=$id&y=$activo'>".DIR."activar.php?x=$id&y=$activo</a></p>
		<p>Agradecimiento del administrador</p>";

		$mail = new Mail();
		$mail->setFrom(SITEEMAIL);
		$mail->addAddress($to);
		$mail->subject($subject);
		$mail->body($body);
		$mail->send();

		$_SESSION['message'] = "<strong>¡Registrado correctamente!</strong> Para activar tu cuenta, por favor ingrese a su correo electrónico.";
		$_SESSION['message_type'] = 'success';

		//Redireccionar al mismo registro
		header('Location: registro.php');
		exit;
	}
	else{
		$errores[] = 'Ooops, estamos experimentando problemas técnicos y no podemos crear tu perfil en este momento. Por favor, intentálo de nuevo más tarde.';
	}

	return $errores;
}

/**
** Función para verificar si no hay duplicación
** de nombre de usuario y correo electrónico
**/
function duplicacion($con){
	require_once "./functions/funciones.php";
	$errores = [];

	//limpia el campo usuario
	$usuario = limpiar($_POST['usuario']);

	//Selecciona el usuario mediante su nombre de usuario
	$dec = $con ->  prepare('SELECT usuario FROM usuarios WHERE usuario = :usuario');
	$dec -> bindParam(':usuario', $usuario);
	$dec -> execute();

	$resultado = $dec -> rowCount();

	if($resultado > 0 ){
		$errores[] = 'Este nombre de usuario no está disponible.';
	}

	//limpia el campo email
	$email = limpiar($_POST['email']);

	//Selecciona el usuario mediante su correo electrónico
	$dec = $con ->  prepare('SELECT email FROM usuarios WHERE email = :email');
	$dec -> bindParam(':email', $email);
	$dec -> execute();

	$resultado = $dec -> rowCount();

	if($resultado > 0 ){
		$errores[] = 'Este email ya esta siendo usado por alguien más.';
	}

	return $errores;
}
/**
** Función para iniciar sesión limitando los intentos
**/
function login(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$errores = [];

	//Limpia los campos
	$usuario = limpiar($_POST['usuarioOemail']);
	$clave = limpiar($_POST['clave']);

	//Selecciona el nombre, apellido, nombre de usuario, privilegio, clave, activo, intento, empresa e id mediante el nombre de usuario y correo electrónico
	$dec = $con ->  prepare('SELECT id, id_empresa, nombre, apellido, email, usuario, privilegio, clave, activo, intento, id, tiempo FROM usuarios WHERE usuario = :usuario OR email = :email');
	$dec -> bindParam(':usuario', $usuario);
	$dec -> bindParam(':email', $usuario);
	$dec -> execute();

	$resultado = $dec -> rowCount();
	$linea = $dec->fetch(PDO::FETCH_ASSOC);

	if($resultado == 1 ){

		//Genera un intento y el tiempo si se ha escrito mal la contraseña de un usuario
		$errores = fuerzaBruta($con, $linea['intento'], $linea['id'], $linea['tiempo']);

		//Verifica si no hay errores
		if(!empty($errores)){
			return $errores;
		}

		//Verifica que la clave sea la correcta
		if(password_verify($clave, $linea['clave'])){

			//Verifica si el usuario ha activado su cuenta
			if($linea['activo'] == "SI"){
				$intento = 0;
				$tiempo = NULL;
				$id = $linea['id'];

				//Actualiza el intento y el tiempo del usuario
				$dec = $con ->  prepare('UPDATE usuarios SET intento = :intento, tiempo = :tiempo where id = :id');
				$dec -> bindParam(':intento', $intento);
				$dec -> bindParam(':tiempo', $ahora);
				$dec -> bindParam(':id', $id);
				$dec -> execute();

				//Devuelve la sesión del usuario
				$_SESSION['usuario'] = $linea['usuario'];
				$_SESSION['nombre'] = $linea['nombre'];
				$_SESSION['apellido'] = $linea['apellido'];
				$_SESSION['privilegio'] = $linea['privilegio'];
				$_SESSION['email'] = $linea['email'];
				$_SESSION['id'] = $linea['id'];
				$_SESSION['idEmpresa'] = $linea['id_empresa'];
				header('Location: index.php');

			}else{
				$errores[] = 'La cuenta no ha sido activada.';
			}
			
		}
		else{
			$errores[] = 'La combinación de (Nombre de usuario o correo electrónico) y contraseña no son válidos.';
		}
	}
	else{
		$errores[] = 'La combinación de (Nombre de usuario o correo electrónico) y contraseña no son válidos.';
	}

	return $errores;
}
/**
** Función de bloqueo para el inicio de sesión
**/
function fuerzaBruta($con, $intento, $id, $tiempo){
	$errores = [];

	$intento = $intento + 1;

	//Actualiza el intento y el tiempo del usuario
	$dec = $con ->  prepare('UPDATE usuarios SET intento = :intento where id = :id');
	$dec -> bindParam(':intento', $intento);
	$dec -> bindParam(':id', $id);
	$dec -> execute();

	//Verifica que el intento sea igual a 5 veces
	if($intento == 5){
		$ahora = date('Y-m-d H:i:s');

		//Actualiza el tiempo del usuario
		$dec = $con ->  prepare('UPDATE usuarios SET tiempo = :tiempo where id = :id');
		$dec -> bindParam(':tiempo', $ahora);
		$dec -> bindParam(':id', $id);
		$dec -> execute();
		$errores[] = 'Esta cuenta ha sido bloqueada por los próximos 15 minutos'; 
	}
	elseif($intento > 5){
		$espera = strtotime(date('Y-m-d H:i:s')) - strtotime($tiempo);
		$minutos = ceil((900 - $espera)/60);
		if($espera < 900 ){
			$errores[] = 'Esta cuenta ha sido bloqueada por los próximos ' .$minutos. ' minutos';
		}
		else{
			$intento = 1;
			$tiempo = NULL;
			$dec = $con ->  prepare('UPDATE usuarios SET intento = :intento, tiempo = :tiempo where id = :id');
			$dec -> bindParam(':intento', $intento);
			$dec -> bindParam(':tiempo', $ahora);
			$dec -> bindParam(':id', $id);
			$dec -> execute();
		}
	}


	return $errores;
}
/**
** Función que valida un campo específico del usuario
**/
function validar($campos){
	$errores = [];
	foreach ($campos as $nombre => $mostrar) {
		if(!isset($_POST[$nombre]) || $_POST[$nombre] == NULL){
			$errores[] = $mostrar .' es un campo requerido.';
		}else{
			$validez = campos();
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
function campos(){
	$validacion = [
		'nombre' => [
			'patron' => '/^[a-zÁÉÍÓÚáéíóúñN\s]{2,50}$/i',
			'error' => 'El nombre solo puede usar letras y un espacio. Además, debe contener entre de 2 a 50 caracteres.'
		],
		'apellido' => [
			'patron' => '/^[a-zÁÉÍÓÚáéíóúñN\s]{2,100}$/i',
			'error' => 'El apellido solo puede usar letras y un espacio. Además, debe contener entre de 2 a 100 caracteres.'
		],
		'usuario' => [
			'patron' => '/^[a-z][\w]{2,30}$/i',
			'error' => 'El nombre de usuario debe tener por lo menos 3 caracteres. Debe comenzar con una letra y solo se puede usar letras y números.'
		],
		'email' => [
			'patron' => '/^[a-z]+[\w-\.]{2,}@([\w-]{2,}\.)+[\w-]{2,4}$/i',
			'error' => 'Correo electrónico debe ser en un formato válido.'
		],
		'usuarioOemail' => [
			'patron' => '/(?=^[a-z]+[\w@\.]{2,50}$)/i',
			'error' => 'Por favor escriba un nombre de usuario o correo electrónico válido.'
		],

	];
	return $validacion;
}
/**
** Función que compara las claves para que coincida
**/ 
function comparador_claves($clave, $reclave){
	$errores = [];
	if($clave !== $reclave){
		$errores[] = 'Las contraseñas proveídas no son iguales.';
	}
	return $errores;
}

/**
** Función que activa un usuario para poder iniciar
** sesión
**/
function activar(){
	require('./include/config.php');
	$errores = [];

	$id = trim($_GET['x']);
	$activo = trim($_GET['y']);

	if(is_numeric($id) && !empty($activo)){

		$dec = $con->prepare("UPDATE usuarios SET activo = 'SI' WHERE id = :id AND activo = :activo");
		$dec -> bindParam(':id', $id);
		$dec -> bindParam(':activo', $activo);			
		$dec -> execute();

		if($dec->rowCount() == 1){
			$_SESSION['message'] = "<strong>¡Activado exitosamente!</strong> Ahora puedes ingresar a tu cuenta.";
			$_SESSION['message_type'] = 'success';
			header('Location: login.php');
			exit;
		} else {
			$errores[] = 'Tu cuenta no ha podido ser activada.'; 			
		}
	}else{
		header('Location: login.php');
	}
	return $errores;
	
}

/**
** Función que envia un enlace para cambiar la
** contraseña usando token
**/
function enviarClave(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	require_once "./functions/phpmailer/mail.php";

	if(!empty($errores)){
		return $errores;
	}

	$email = limpiar($_POST['email']);
	$email = strtolower($_POST['email']);

	$dec = $con ->  prepare('SELECT email FROM usuarios WHERE email = :email');
	$dec -> bindParam(':email', $email);
	$dec -> execute();

	$linea = $dec->fetch(PDO::FETCH_ASSOC);

	if(empty($linea['email'])){
		$errores[] = 'Correo electrónico no se encuentra registrado.';
	}
	else{
		$dec = $con->prepare('SELECT clave, email FROM usuarios WHERE email = :email');
		$dec -> bindParam(':email', $email);
		$dec -> execute();
		$linea = $dec->fetch(PDO::FETCH_ASSOC);

		$token =  md5($email+date('Y-m-d',time()));
		
		try {

			$dec = $con->prepare("UPDATE usuarios SET resetToken = :token, resetComplete='NO' WHERE email = :email");
			$dec->execute(array(
				':email' => $linea['email'],
				':token' => $token
			));

			$to = $linea['email'];
			$subject = "Restablecer contraseña";
			$body = "<p>Alguien solicitó que tu contraseña sea restablecida.</p>
			<p>Si esto fue un error, simplemente ignora este mensaje y no pasará nada.</p>
			<p>Para cambiar tu contraseña, pincha el siguiente enlace: <a href='".DIR."restablecerClave.php?key=$token'>".DIR."restablecerClave.php?key=$token</a></p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject($subject);
			$mail->body($body);
			$mail->send();

			$_SESSION['message'] = "Por favor, verifica tu correo para el cambio de contraseña.";
			$_SESSION['message_type'] = 'success';
			header('Location: login.php');
			exit;

		} catch(PDOException $errores) {
			$errores[] = 'No se pudo restablecer la contraseña';
		}

	}
	return $errores;

}

/**
** Función que restablece la contraseña al usuario
**/
function restablecerClave(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$errores = [];

	if(!empty($errores)){
		return $errores;
	}

	$clave = limpiar($_POST['clave']);

	$token = $_GET['key'];;

	$dec = $con->prepare('SELECT resetToken, resetComplete FROM usuarios WHERE resetToken = :token');
	$dec -> bindParam(':token', $token);
	$dec -> execute();

	$linea = $dec->fetch(PDO::FETCH_ASSOC);

	if(empty($linea['resetToken'])){
		$errores[] = 'Token inválido, por favor usa el enlace que te enviamos al correo electrónico.';
	} 
	elseif($linea['resetComplete'] == 'SI') {
		$errores[] = '¡Tu contraseña ya ha sido cambiada!';
	}
	else{
		try {
			$dec = $con->prepare("UPDATE usuarios SET clave = :claveactualizada, resetComplete = 'SI'  WHERE resetToken = :token");
			$dec -> bindParam(':claveactualizada',password_hash($clave, PASSWORD_DEFAULT));
			$dec -> bindParam(':token', $linea['resetToken']);
			$dec -> execute();

			$_SESSION['message'] = "<strong>¡Contraseña actualizada!</strong> Ahora puedes ingresar a tu cuenta.";
			$_SESSION['message_type'] = 'success';
			header('Location: login.php');
			exit;

		} catch(PDOException $errores) {
			$errores[] = 'No se pudo restablecer la contraseña';
		}
	}
	return $errores;
}

/**
** Función que despliega una lista de usuarios registrados
**/
function listar_usuarios(){
	require_once "./include/config.php";
	$con = DB::getConn();

	$dec = $con -> prepare('SELECT * FROM usuarios');
	$dec->execute();
	if($dec->rowCount() >= 1){
		while($linea = $dec->fetch()){
			switch($linea["privilegio"]){
				case 1:
				$departamento = "Informática";
				break;
				case 2:
				$departamento = "Adquisiciones";
				break;
				case 3:
				$departamento = "Contabilidad";
				break;
				case 4:
				$departamento = "Gerencia";
				break;
				case 5:
				$departamento = "Operaciones";
				break;
			}
			echo '<tr>
			<td>'. $linea['id'] .'</td>
			<td>'. $linea['nombre'] .'</td>
			<td>'. $linea['apellido'] .'</td>
			<td>'. $linea['email'] .'</td>
			<td>'. $linea['usuario'] .'</td>
			<td><span class="badge badge-pill badge-light">'. $departamento .'</span></td>
			<td>'.  strftime("%d de %B del %Y a las %H:%m", strtotime($linea['fecha_registro'])) .'</td>
			<td><a href="usuario.php?id='. $linea['id'] .'" class="btn btn-outline-warning" data-target="#editarUsuario" data-toggle="modal"  data-id="'.  $linea['id'] .'"  data-nombre="'.  $linea['nombre'] .'"  data-apellido="'.  $linea['apellido'] .'"  data-email="'.  $linea['email'] .'" data-usuario="'.  $linea['usuario'] .'" data-privilegio="'.  $linea['privilegio'] .'"><i data-feather="edit-3" data-toggle="tooltip" data-placement="bottom" name="editar" title="Editar" >&#xE254;</i></a>
			<a href="#eliminarUsuario" class="btn btn-outline-danger" data-toggle="modal" data-target="#eliminarUsuario"  data-id="'.  $linea['id'] .'"><i data-feather="trash-2" data-toggle="tooltip" data-placement="bottom" name="eliminar" title="Eliminar">&#xE872;</i></a></td>
			</tr>';
		}
	}else{
		echo '<tr>
		<td colspan="6" class="text-center"> No hay datos para mostrar.</td>
		</tr>';
	}
}

/**
** Función que guarda un nuevo usuario
**/
function guardar_usuario(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";

	$errores = duplicacion($con);

	if(!empty($errores)){
		return $errores;
	}

	if (isset($_POST['agregar'])) {
		$nombre = limpiar($_POST['nombre']);
		$apellido = limpiar($_POST['apellido']);
		$usuario = limpiar($_POST['usuario']);
		$email = limpiar($_POST['email']);
		$privilegio = $_POST['privilegio'];


	//Convierte el campo email a minúsculas
		$email = strtolower($_POST['email']);
		$activo = "SI";

		//$clave = substr(md5(rand()),0,8);
		$clave = 12345;

	//Inserta un usuario nuevo
		$dec = $con ->  prepare('INSERT INTO usuarios (nombre, apellido, usuario, email, privilegio, clave, activo) VALUES (:nombre, :apellido, :usuario, :email,:privilegio, :clave, :activo)');
		$dec -> bindParam(':nombre',$nombre);
		$dec -> bindParam(':apellido',$apellido);
		$dec -> bindParam(':usuario',$usuario);
		$dec -> bindParam(':email',$email);
		$dec -> bindParam(':privilegio',$privilegio);
		$dec -> bindParam(':clave',password_hash($clave, PASSWORD_DEFAULT));
		$dec -> bindParam(':activo',$activo);
		$dec -> execute();


		if($dec->rowCount() >= 1){
			$_SESSION['message'] = "Usuario agregado correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: usuarios.php');
			exit;
		}
		else{
			$errores[] = 'Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.';
		}
	}

	return $errores;

}

/**
** Función que edita usuario
**/
function editar_usuario(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['editar'])){
		$nombre = limpiar($_POST['editar_nombre']);
		$apellido = limpiar($_POST['editar_apellido']);
		$usuario = limpiar($_POST['editar_usuario']);
		$email = limpiar($_POST['editar_email']);
		$privilegio = $_POST['editar_privilegio'];


		//Convierte el campo email a minúsculas
		$email = strtolower($_POST['editar_email']);
		$id=intval($_POST['editar_id']);

		//Inserta un usuario nuevo
		$dec = $con ->  prepare('UPDATE usuarios SET nombre=:nombre, apellido=:apellido, usuario=:usuario, email=:email, privilegio=:privilegio WHERE id=:id');
		$dec -> bindParam(':nombre',$nombre);
		$dec -> bindParam(':apellido',$apellido);
		$dec -> bindParam(':usuario',$usuario);
		$dec -> bindParam(':email',$email);
		$dec -> bindParam(':privilegio',$privilegio);
		$dec -> bindParam(':id',$id);
		$dec -> execute();


		if($dec->rowCount() >= 1){
			$_SESSION['message'] = "Usuario actualizado correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: usuarios.php');
			exit;
		}
		else{
			$errores[] = 'No se ha efectuado cambios, verifique que haya cambiado un campo del usuario.';
		}
	}
	else{
		$errores[] = 'Lo sentimos, la actualización falló. Por favor, regrese y vuelva a intentarlo.';
	}

	return $errores;

}

/**
** Función que elimina usuario
**/
function eliminar_usuario(){
	require_once "./include/config.php";

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['eliminar'])){
		$id=intval($_POST['eliminar_id']);

		//Inserta un usuario nuevo
		$dec = $con ->  prepare('DELETE FROM usuarios WHERE id=:id');
		$dec -> bindParam(':id',$id);
		$dec -> execute();


		if($dec->rowCount() >= 1){
			$_SESSION['message'] = "Usuario eliminado correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: usuarios.php');
			exit;
		}
		else{
			$errores[] = 'Lo sentimos, la eliminación falló. Por favor, regrese y vuelva a intentarlo.';
		}

	}
	return $errores;
	
}

/**
** Función que obtiene la cantidad de usuarios registrados
**/
function obtenerNumeroUsuarios(){
	require_once "./include/config.php";

	$total_usuarios = null;
	$dec = $con -> prepare('SELECT COUNT(*) as total FROM usuarios');
	$dec->execute();
	$linea = $dec->fetch(PDO::FETCH_ASSOC);
	if($dec->rowCount() >= 1){
		$total_usuarios = $linea['total'];
		echo $total_usuarios;
	}else{
		echo "No hay usuarios registrados";
	}	
}



