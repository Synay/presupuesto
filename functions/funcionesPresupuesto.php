<?php
/**
** Función que obtiene los ultimos presupuestos registrados
**/
function obtenerUltimoPresupuesto(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	$dec = $con ->  prepare('select LAST_INSERT_ID(id_presupuesto) as last from presupuesto order by id_presupuesto desc limit 0,1;');
	$dec->execute();
	$linea = $dec->fetch(PDO::FETCH_ASSOC);
	$ultimoId = $linea['last'];
	return $ultimoId;

}

/**
** Función que obtiene la cantidad de presupuestos generados
**/
function obtenerNumeroPresupuestos(){
	require_once "./include/config.php";
	$con = DB::getConn();

	$total_presupuestos = null;
	$dec = $con -> prepare('SELECT COUNT(*) as total FROM presupuesto');
	$dec->execute();
	$linea = $dec->fetch(PDO::FETCH_ASSOC);
	if($dec->rowCount() >= 1){
		$total_presupuestos = $linea['total'];
		
	}
	return $total_presupuestos;

}

/**
** Función últimos 15 registros de presupuesto
**/
function listar_ultimos_presupuestos(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	$dec = $con -> prepare('CALL sp_consultar_ultimos_presupuestos()');
	$dec->execute();
	if($dec->rowCount() >= 1){
		while($linea = $dec->fetch()){
			echo '<tr>
			<td>'. $linea['IdP'] .'</td>
			<td>'. $linea['NombreObra'] .'</td>
			<td>'. $linea['NombreCliente'] .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha'])) .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha2'])) .'</td>
			<td>$ '. number_format($linea['Total'],0,",",".").'</td>';
			switch($linea['Estado']){
				case 0:
				echo '<td><span class="badge badge-pill badge-light">Elaborando</span></td>';
				break;
				case 1:
				echo '<td><span class="badge badge-pill badge-warning">Cotizando</span></td>';
				break;
				case 2:
				echo '<td><span class="badge badge-pill badge-danger">Aprobando</span></td>';
				break;
				case 3:
				echo '<td><span class="badge badge-pill badge-info">En control</span></td>';
				break;
			}
			echo '</tr>';
		}
	}else{
		echo '<tr>
		<td colspan="6" class="text-center"> No hay datos para mostrar.</td>
		</tr>';
	}
}
/**
** Función que obtiene si el presupuesto existe o no
**/
function obtener_presupuesto($id){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	$dec = $con -> prepare('SELECT * FROM presupuesto where id_presupuesto=:id');
	$dec->bindParam(':id', $id);
	$dec->execute();

	if($dec->rowCount()>=1){
		return true;
	}
	return false;

}
/**
** Función que obtiene el detalle del presupuesto
**/
function obtener_gestion_presupuesto($id){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	$dec = $con -> prepare('SELECT p.nombre_obra AS NombreO, p.fecha_presupuesto AS Fecha, p.fecha_termino AS FechaT,  u.nombre AS NombreU, u.apellido AS ApellidoU, u.email AS EmailU, u.privilegio As PrivilegioU, e.nombre_empresa AS NombreE, e.rut_empresa AS RutE, e.direccion_empresa AS Direccion, e.telefono_empresa AS Telefono, c.nombre_cliente AS ClienteC FROM presupuesto p INNER JOIN usuarios u ON p.id = u.id INNER JOIN empresas e ON u.id_empresa = e.id_empresa INNER JOIN clientes c ON p.id_cliente=c.id_cliente where p.id_presupuesto=:id');
	$dec->bindParam(':id', $id);
	$dec->execute();

	while($linea = $dec->fetch(PDO::FETCH_ASSOC)){
		$detalle[]=$linea;

	}
	return $detalle;

}

/**
** Función que guarda un nuevo presupuesto
**/
function guardar_presupuesto(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	if(!empty($errores)){
		return $errores;
	}

	if (isset($_POST['guardar'])) {
		$id_presupuesto = obtenerUltimoPresupuesto()+1;
		$nombre_obra = limpiar($_POST['nombre_obra']);
		$fecha_presupuesto = $_POST['fecha_presupuesto'];
		$fecha = DateTime::createFromFormat('d/m/Y', $fecha_presupuesto);
		$fecha = $fecha->format('Y-m-d');
		$fecha_termino = $_POST['fecha_termino'];
		$fecha2 = DateTime::createFromFormat('d/m/Y', $fecha_termino);
		$fecha2 = $fecha2->format('Y-m-d');
		$id_cliente = intval($_POST['id_cliente']);
		$id = $_SESSION['id'];

	    //Inserta un presupuesto nuevo
		$dec = $con ->  prepare('INSERT INTO presupuesto (id_presupuesto, nombre_obra, fecha_presupuesto, fecha_termino, id_cliente, id) VALUES (:id_presupuesto, :nombre_obra, :fecha_presupuesto, :fecha_termino, :id_cliente, :id)');

		
		$dec -> bindParam(':id_presupuesto',$id_presupuesto);
		$dec -> bindParam(':nombre_obra',$nombre_obra);
		$dec -> bindParam(':fecha_presupuesto',$fecha);
		$dec -> bindParam(':fecha_termino',$fecha2);
		$dec -> bindParam(':id_cliente',$id_cliente);
		$dec -> bindParam(':id',$id);
		$dec -> execute();

		if($dec->rowCount() >= 1){
			header('Location: categorias.php?idPresupuesto='. $id_presupuesto.'');
			exit;
		}
		else{
			$errores[] = 'Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.';
		}
	}

	return $errores;

}

/**
** Función que despliega una lista de presupuestos creados
**/
function listar_presupuestos_inicial(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();
	$id = $_SESSION['id'];
	
	$dec = $con -> prepare('SELECT p.id_presupuesto AS IdP, p.nombre_obra AS NombreObra, c.nombre_cliente AS NombreCliente, u.id AS IdUsuario, u.nombre AS Nombre, u.apellido AS Apellido, p.fecha_presupuesto AS Fecha, p.fecha_termino AS Fecha2 FROM presupuesto p INNER JOIN clientes c ON p.id_cliente = c.id_cliente JOIN usuarios u ON p.id = u.id WHERE p.estado=0 ORDER BY p.fecha_presupuesto DESC');
	$dec->bindParam(':id', $id);
	$dec->execute();
	if($dec->rowCount() >= 1){
		while($linea = $dec->fetch()){

			if ($linea['IdUsuario']===$id){
				$nombre = 'Mí';
			}else{
				$nombre = $linea['Nombre'] .' '. $linea['Apellido'];
			}
			echo '<tr>
			<td>'. $linea['IdP'] .'</td>
			<td>'. $linea['NombreObra'] .'</td>		
			<td>'. $nombre .'</td>
			<td>'. $linea['NombreCliente'] .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha'])) .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha2'])) .'</td>
			<td><a href="javascript:void(0)" class="btn btn-outline-secondary" onclick="mostrarDetalles('. $linea['IdP'] .')"><i data-feather="eye" data-toggle="tooltip" data-placement="bottom" name="ver" title="Ver" >&#xE254;</i></a>
			<a href="#cotizarPresupuesto" class="btn btn-outline-success" data-toggle="modal" data-target="#cotizarPresupuesto"  data-id_presupuesto="'.  $linea['IdP'] .'"><i data-feather="check" data-toggle="tooltip" data-placement="bottom" name="validar" title="Validar">&#xE872;</i></a>
			<a href="#eliminarPresupuesto" class="btn btn-outline-danger" data-toggle="modal" data-target="#eliminarPresupuesto"  data-id_presupuesto="'.  $linea['IdP'] .'"><i data-feather="trash-2" data-toggle="tooltip" data-placement="bottom" name="eliminar" title="Eliminar">&#xE872;</i></a>
			</td>
			</tr>';
		}
	}else{
		echo '<tr>
		<td colspan="7" class="text-center"> No hay datos para mostrar.</td>
		</tr>';
	}
}
/**
** Función que despliega una lista de presupuestos para cotización
**/
function listar_presupuestos2(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();
	$id = $_SESSION['id'];
	
	$dec = $con -> prepare('SELECT p.id_presupuesto AS IdP, p.nombre_obra AS NombreObra, c.nombre_cliente AS NombreCliente, u.id AS IdUsuario, u.nombre AS Nombre, u.apellido AS Apellido, p.fecha_presupuesto AS Fecha, p.fecha_termino AS Fecha2 FROM presupuesto p INNER JOIN clientes c ON p.id_cliente = c.id_cliente JOIN usuarios u ON p.id = u.id WHERE p.estado=1 ORDER BY p.fecha_presupuesto DESC');
	$dec->bindParam(':id', $id);
	$dec->execute();
	if($dec->rowCount() >= 1){
		while($linea = $dec->fetch()){
			if ($linea['IdUsuario']===$id){
				$nombre = 'Mí';
			}else{
				$nombre = $linea['Nombre'] .' '. $linea['Apellido'];
			}	
			echo '<tr>
			<td>'. $linea['IdP'] .'</td>
			<td>'. $linea['NombreObra'] .'</td>		
			<td>'. $nombre .'</td>
			<td>'. $linea['NombreCliente'] .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha'])) .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha2'])) .'</td>
			<td><a href="javascript:void(0)" class="btn btn-outline-secondary" onclick="mostrarDetalles2('. $linea['IdP'] .')"><i data-feather="eye" data-toggle="tooltip" data-placement="bottom" name="ver" title="Ver" >&#xE254;</i></a>
			<a href="./precios?idPresupuesto='. $linea['IdP'] .'" class="btn btn-outline-warning" ><i data-feather="dollar-sign" data-toggle="tooltip" data-placement="bottom" name="agregar" title="Agregar valores" >&#xE254;</i></a>
			<a href="#ejecucionPresupuesto" class="btn btn-outline-success" data-toggle="modal" data-target="#ejecucionPresupuesto"  data-id_presupuesto="'.  $linea['IdP'] .'"><i data-feather="check" data-toggle="tooltip" data-placement="bottom" name="validar2" title="Validar">&#xE872;</i></a>
			</td>
			</tr>';
		}
	}else{
		echo '<tr>
		<td colspan="7" class="text-center"> No hay datos para mostrar.</td>
		</tr>';
	}
}

/**
** Función que despliega una lista de presupuestos para la aprobación
**/
function listar_presupuestos3(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();
	$id = $_SESSION['id'];
	
	$dec = $con -> prepare('SELECT p.id_presupuesto AS IdP, p.nombre_obra AS NombreObra, c.nombre_cliente AS NombreCliente, u.id AS IdUsuario, u.nombre AS Nombre, u.apellido AS Apellido, p.fecha_presupuesto AS Fecha, p.fecha_termino AS Fecha2 FROM presupuesto p INNER JOIN clientes c ON p.id_cliente = c.id_cliente JOIN usuarios u ON p.id = u.id WHERE p.estado=2 ORDER BY p.fecha_presupuesto DESC');
	$dec->bindParam(':id', $id);
	$dec->execute();
	if($dec->rowCount() >= 1){
		while($linea = $dec->fetch()){
			if ($linea['IdUsuario']===$id){
				$nombre = 'Mí';
			}else{
				$nombre = $linea['Nombre'] .' '. $linea['Apellido'];
			}		
			echo '<tr>
			<td>'. $linea['IdP'] .'</td>
			<td>'. $linea['NombreObra'] .'</td>		
			<td>'. $nombre .'</td>
			<td>'. $linea['NombreCliente'] .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha'])) .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha2'])) .'</td>
			<td><a href="javascript:void(0)" class="btn btn-outline-secondary" onclick="mostrarDetalles3('. $linea['IdP'] .')"><i data-feather="eye" data-toggle="tooltip" data-placement="bottom" name="ver" title="Ver" >&#xE254;</i></a>
			<a href="./costos?idPresupuesto='. $linea['IdP'] .'" class="btn btn-outline-warning" ><i data-feather="dollar-sign" data-toggle="tooltip" data-placement="bottom" name="agregar" title="Agregar porcentajes" >&#xE254;</i></a>
			<a href="./pdf/presupuesto.php?idPresupuesto='. $linea['IdP'] .'" target="_blank" class="btn btn-outline-dark"><i data-feather="printer" data-toggle="tooltip" data-placement="bottom" name="imprimir" title="Imprimir" >&#xE254;</i></a>
			<a href="#controlarPresupuesto" class="btn btn-outline-success" data-toggle="modal" data-target="#controlarPresupuesto"  data-id_presupuesto="'.  $linea['IdP'] .'"><i data-feather="check" data-toggle="tooltip" data-placement="bottom" name="validar3" title="Validar">&#xE872;</i></a>
			</td>
			</tr>';
		}
	}else{
		echo '<tr>
		<td colspan="7" class="text-center"> No hay datos para mostrar.</td>
		</tr>';
	}
}

/**
** Función que despliega una lista de presupuestos para la aprobación
**/
function listar_presupuestos4(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();
	$id = $_SESSION['id'];
	
	$dec = $con -> prepare('SELECT p.id_presupuesto AS IdP, p.nombre_obra AS NombreObra, c.nombre_cliente AS NombreCliente, u.id AS IdUsuario, u.nombre AS Nombre, u.apellido AS Apellido, p.fecha_presupuesto AS Fecha, p.fecha_termino AS Fecha2 FROM presupuesto p INNER JOIN clientes c ON p.id_cliente = c.id_cliente JOIN usuarios u ON p.id = u.id WHERE p.estado=3 ORDER BY p.fecha_presupuesto DESC');
	$dec->bindParam(':id', $id);
	$dec->execute();
	if($dec->rowCount() >= 1){
		while($linea = $dec->fetch()){
			if ($linea['IdUsuario']===$id){
				$nombre = 'Mí';
			}else{
				$nombre = $linea['Nombre'] .' '. $linea['Apellido'];
			}		
			echo '<tr>
			<td>'. $linea['IdP'] .'</td>
			<td>'. $linea['NombreObra'] .'</td>		
			<td>'. $nombre .'</td>
			<td>'. $linea['NombreCliente'] .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha'])) .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha2'])) .'</td>
			<td><a href="./gasto?idPresupuesto='. $linea['IdP'] .'" class="btn btn-secondary"><i data-feather="eye" data-toggle="tooltip" data-placement="bottom" name="ver" title="Ver" >&#xE254;</i></a>
			<a href="./agregar_gasto?idPresupuesto='. $linea['IdP'] .'" class="btn btn-outline-warning" ><i data-feather="dollar-sign" data-toggle="tooltip" data-placement="bottom" name="agregar" title="Agregar gasto" >&#xE254;</i></a>
			<a href="./pdf/gasto_presupuesto.php?idPresupuesto='. $linea['IdP'] .'" target="_blank" class="btn btn-outline-dark"><i data-feather="printer" data-toggle="tooltip" data-placement="bottom" name="imprimir" title="Imprimir" >&#xE254;</i></a>
			</td>
			</tr>';
		}
	}else{
		echo '<tr>
		<td colspan="7" class="text-center"> No hay datos para mostrar.</td>
		</tr>';
	}
}

/**
** Función que despliega el detalle del presupuesto
**/
function ver_presupuesto(){
	require_once "../include/config.php";
	$con = DB::getConn();

	$id_presupuesto = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$dec = $con->prepare('CALL sp_consultar_presupuesto (:id)');
	$dec -> bindParam(':id',$id_presupuesto);
	$dec -> execute();	
	$cont = 0;

	$nombre_categoria    = '';
	$nombre_subcategoria = '';

	while ($linea = $dec->fetch(PDO::FETCH_ASSOC)) {
		if ($nombre_categoria !== $linea['Categoria']) {
			$nombre_categoria = $linea['Categoria'];
			echo '<tbody>
			<tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-'. $linea['IdCategoria'].'" aria-expanded="true" aria-controls="group-of-rows-'. $linea['IdCategoria'].'">
			<td>+</td>
			<td><b>'. $nombre_categoria.'</b></td>
			<td></td>  
			<td></td>
			</tr>
			</tbody>';
			
		}

		if ($nombre_subcategoria !== $linea['SubCategoria']) {
			$nombre_subcategoria = $linea['SubCategoria'];
			echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'" class="collapse">
			<tr class="table-active">
			<td>-</td>
			<td>'. $nombre_subcategoria.'</td>
			<td></td>  
			<td></td>
			</tr>
			</tbody>';

		}
		
		$nombre  = $linea['Insumo'];
		$tipo  = $linea['Tipo'];
		$cantidad  = $linea['Cantidad'];

		echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'" class="collapse">
		<tr class="table-warning">
		<td></td>
		<td>'. $nombre.'</td>
		<td><center>'. $tipo.'</center></td>  
		<td><center><b>'. $cantidad.'</b></center></td>
		</tr>
		</tbody>';

		$cont++;
	}
	if ($cont == 0) {
		echo '<tbody ">
		<tr>
		<td colspan="4" class="text-center"><p>No hay insumos disponibles para este presupuesto. <a href="presupuestos.php">Haga click aquí para seguir navegando</a></p></td>
		</tr>
		</tbody>';
	}
}
/**
** Función que valida el presupuesto para la cotización
**/
function cotizar_presupuesto(){
	require_once "./include/config.php";

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['cotizar'])){
		$id=intval($_POST['cotizar_id_presupuesto']);

		//Consulta el presupuesto esta completo
		$dec = $con ->  prepare('CALL sp_consultar_presupuesto (:id)');
		$dec -> bindParam(':id',$id);
		$dec -> execute();


		if($dec->rowCount() >= 1){
			$dec = $con ->  prepare('UPDATE presupuesto SET estado=1 WHERE id_presupuesto=:id');
			$dec -> bindParam(':id',$id);
			$dec -> execute();
			$_SESSION['message'] = "Presupuesto validado para la cotización.";
			$_SESSION['message_type'] = 'success';
			header('Location: presupuestos.php');
			exit;
		}
		else{
			$errores[] = 'Lo sentimos, la validación falló. Por favor, verifique que tenga algún insumo asociado al presupuesto.';
		}

	}
	return $errores;
	
}

/**
** Función que valida un presupuesto cambiando el estado para la aprobación
**/
function validar2_presupuesto(){
	require_once "./include/config.php";

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['ejecucion'])){
		$id=intval($_POST['ejecucion_id_presupuesto']);

		//Consulta el presupuesto esta completo
		$dec = $con ->  prepare('CALL sp_consultar_presupuesto (:id)');
		$dec -> bindParam(':id',$id);
		$dec -> execute();
		$linea = $dec->fetch(PDO::FETCH_ASSOC);

		if($dec->rowCount() >= 1){
			if($linea['Total']!=0){
				$dec = $con ->  prepare('UPDATE presupuesto SET estado=2 WHERE id_presupuesto=:id');
				$dec -> bindParam(':id',$id);
				$dec -> execute();
				$_SESSION['message'] = "Presupuesto validado para la aprobación.";
				$_SESSION['message_type'] = 'success';
				header('Location: presupuestos.php');
				exit;
			}else{
				$errores[] = 'Lo sentimos, la validación falló. Por favor, verifique que el monto del presupuesto no sea distinto a cero.';
			}
		}
		else{
			$errores[] = 'Lo sentimos, la validación falló. Por favor, verifique que tenga algún insumo asociado al presupuesto.';
		}

	}
	return $errores;
	
}

/**
** Función que valida un presupuesto cambiando el estado para el control
**/
function validar3_presupuesto(){
	require_once "./include/config.php";

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['controlar'])){
		$id=intval($_POST['controlar_id_presupuesto']);

		//Consulta el presupuesto esta completo
		$dec = $con ->  prepare('CALL sp_consultar_presupuesto (:id)');
		$dec -> bindParam(':id',$id);
		$dec -> execute();
		$linea = $dec->fetch(PDO::FETCH_ASSOC);

		if($dec->rowCount() >= 1){
			if($linea['Total']!=0 && $linea['PorcentajeAdm']!=0 && $linea['PorcentajeRent']!=0){
				$dec = $con ->  prepare('UPDATE presupuesto SET estado=3 WHERE id_presupuesto=:id');
				$dec -> bindParam(':id',$id);
				$dec -> execute();
				$_SESSION['message'] = "Presupuesto validado para su control.";
				$_SESSION['message_type'] = 'success';
				header('Location: presupuestos.php');
				exit;
			}else{
				$errores[] = 'Lo sentimos, la validación falló. Por favor, verifique que los porcentajes sean distinto a cero.';
			}
		}
		else{
			$errores[] = 'Lo sentimos, la validación falló. Por favor, verifique que tenga algún insumo asociado al presupuesto.';
		}

	}
	return $errores;
	
}

/**
** Función que despliega el detalle del presupuesto
**/
function ver_presupuesto1(){
	require_once "../include/config.php";
	$con = DB::getConn();

	$id_presupuesto = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$dec = $con->prepare('CALL sp_consultar_presupuesto (:id)');
	$dec -> bindParam(':id',$id_presupuesto);
	$dec -> execute();	
	$cont = 0;

	$total_presupuesto = '';
	$nombre_categoria    = '';
	$nombre_subcategoria = '';

	while ($linea = $dec->fetch(PDO::FETCH_ASSOC)) {
		$total_presupuesto = $linea['Total'];
		if ($nombre_categoria !== $linea['Categoria']) {
			$nombre_categoria = $linea['Categoria'];
			echo '<tbody>
			<tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-'. $linea['IdCategoria'].'" aria-expanded="true" aria-controls="group-of-rows-'. $linea['IdCategoria'].'">
			<td>+</td>
			<td><b>'. $nombre_categoria.'</b></td>
			<td></td>  
			<td></td>
			<td></td>
			<td></td>
			</tr>
			</tbody>';
			
		}

		if ($nombre_subcategoria !== $linea['SubCategoria']) {
			$nombre_subcategoria = $linea['SubCategoria'];
			echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'" class="collapse">
			<tr class="table-active">
			<td>-</td>
			<td>'. $nombre_subcategoria.'</td>
			<td></td>  
			<td></td>
			<td></td>
			<td></td>
			</tr>
			</tbody>';

		}
		
		$nombre  = $linea['Insumo'];
		$tipo  = $linea['Tipo'];
		$cantidad  = $linea['Cantidad'];
		$precio  = $linea['Precio'];
		$total = $precio*$cantidad; 


		echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'" class="collapse">';
		if($precio==0){
			echo'<tr class="table-warning">
			<td></td>
			<td>'. $nombre.'</td>
			<td><center>'. $tipo.'</center></td>  
			<td style="text-align: right;"><b>'. $cantidad.'</b></td>
			<td style="text-align: right;">'. number_format($precio,0,",",".").'</td>
			<td style="text-align: right;">'. number_format($total,0,",",".").'</td>
			</tr>';

		} else {
			echo'<tr class="table-success">
			<td></td>
			<td>'. $nombre.'</td>
			<td><center>'. $tipo.'</center></td>  
			<td style="text-align: right;"><b>'. $cantidad.'</b></td>
			<td style="text-align: right;">'. number_format($precio,0,",",".").'</td>
			<td style="text-align: right;">'. number_format($total,0,",",".").'</td>
			</tr>';
		}
		echo '</tbody>';

		$cont++;

	}
	echo '<tbody>
	<tr >
	<td></td>
	<td></td>
	<td></td>  
	<td></td>
	<td style="text-align: right;"><b>TOTAL ($)</b></td>
	<td style="text-align: right;"><b>'. number_format($total_presupuesto,0,",",".").'</b></td>
	</tr>
	</tbody>';

	if ($cont == 0) {
		echo '<tbody ">
		<tr>
		<td colspan="5" class="text-center"><p>No hay insumos disponibles para este presupuesto. <a href="presupuestos.php">Haga click aquí para seguir navegando</a></p></td>
		</tr>
		</tbody>';
	}
}

/**
** Función que despliega el detalle del presupuesto
**/
function ver_presupuesto2(){
	require_once "./include/config.php";
	$con = DB::getConn();

	$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
	$dec = $con->prepare('CALL sp_consultar_presupuesto (:id)');
	$dec -> bindParam(':id',$id_presupuesto);
	$dec -> execute();	
	$cont = 0;

	$total_presupuesto = '';
	$nombre_categoria    = '';
	$nombre_subcategoria = '';

	while ($linea = $dec->fetch(PDO::FETCH_ASSOC)) {
		$total_presupuesto = $linea['Total'];
		if ($nombre_categoria !== $linea['Categoria']) {
			$nombre_categoria = $linea['Categoria'];
			echo '<tbody>
			<tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-'. $linea['IdCategoria'].'" aria-expanded="true" aria-controls="group-of-rows-'. $linea['IdCategoria'].'">
			<td>+</td>
			<td><b>'. $nombre_categoria.'</b></td>
			<td></td>  
			<td></td>
			<td></td>
			<td></td>
			</tr>
			</tbody>';
			
		}

		if ($nombre_subcategoria !== $linea['SubCategoria']) {
			$nombre_subcategoria = $linea['SubCategoria'];
			echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'">
			<tr class="table-active">
			<td>-</td>
			<td>'. $nombre_subcategoria.'</td>
			<td></td>  
			<td></td>
			<td></td>
			<td></td>
			</tr>
			</tbody>';

		}
		
		$nombre  = $linea['Insumo'];
		$tipo  = $linea['Tipo'];
		$cantidad  = $linea['Cantidad'];
		$precio  = $linea['Precio'];
		$total = $precio*$cantidad; 


		echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'"><tr class="tabla_insumos">
		<td></td>
		<td>'. $nombre.'</td>
		<td>'. $tipo.'</td>  
		<td style="text-align: right;"><b><div class="input-group mb-3"><input type="text" style="text-align:right;" class="form-control" id="cantidad_o[]" name="cantidad_o[]" value="'. $cantidad .'"/></b></td>
		<td style="text-align: right;"><input type="hidden" id=insumo_id[]" name="insumo_id[]" value="'. $linea['IdInsumo'].'"/><div class="input-group mb-3"><input type="text" style="text-align:right;" class="form-control precioI" id="precio_insumo[]" name="precio_insumo[]" value="'. number_format($precio,0,",",".").'"/></div></td>
		<td style="text-align: right;"><input type="text" style="text-align:right;" class="form-control" id="subtotal[]" name="subtotal[]" value="'. number_format($total,0,",",".").'" /></span></td>
		</tr></tbody>';

		$cont++;

	}
	echo '<tbody>
	<tr >
	<td></td>
	<td></td>
	<td></td>  
	<td></td>
	<td style="text-align: right;"><b>TOTAL ($) </b></td>
	<td style="text-align: right;"><input type="text" style="text-align:right;" class="form-control" id="total" name="total" value="'. number_format($total_presupuesto,0,",",".").'"/></td>
	</tr>
	</tbody>';

	if ($cont == 0) {
		echo '<tbody ">
		<tr>
		<td colspan="5" class="text-center"><p>No hay insumos disponibles para este presupuesto. <a href="presupuestos.php">Haga click aquí para seguir navegando</a></p></td>
		</tr>
		</tbody>';
	}
}
/**
** Función que despliega el detalle del presupuesto
**/
function ver_presupuesto3(){
	require_once "../include/config.php";
	$con = DB::getConn();

	$id_presupuesto = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$dec = $con->prepare('CALL sp_consultar_presupuesto (:id)');
	$dec -> bindParam(':id',$id_presupuesto);
	$dec -> execute();	
	$cont = 0;

	$total_presupuesto = '';
	$porcentaje_adm = '';
	$porcentaje_rent = '';
	$nombre_categoria    = '';
	$nombre_subcategoria = '';

	while ($linea = $dec->fetch(PDO::FETCH_ASSOC)) {
		$total_presupuesto = $linea['Total'];
		$porcentaje_adm = $linea['PorcentajeAdm'];
		$porcentaje_rent = $linea['PorcentajeRent'];		
		if ($nombre_categoria !== $linea['Categoria']) {
			$nombre_categoria = $linea['Categoria'];
			echo '<tbody>
			<tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-'. $linea['IdCategoria'].'" aria-expanded="true" aria-controls="group-of-rows-'. $linea['IdCategoria'].'">
			<td>+</td>
			<td><b>'. $nombre_categoria.'</b></td>
			<td></td>  
			<td></td>
			<td></td>
			<td></td>
			</tr>
			</tbody>';
			
		}

		if ($nombre_subcategoria !== $linea['SubCategoria']) {
			$nombre_subcategoria = $linea['SubCategoria'];
			echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'" class="collapse">
			<tr class="table-active">
			<td>-</td>
			<td>'. $nombre_subcategoria.'</td>
			<td></td>  
			<td></td>
			<td></td>
			<td></td>
			</tr>
			</tbody>';

		}
		
		$nombre  = $linea['Insumo'];
		$tipo  = $linea['Tipo'];
		$cantidad  = $linea['Cantidad'];
		$precio  = $linea['Precio'];
		$total = $precio*$cantidad; 


		echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'" class="collapse">';
		if($precio==0){
			echo'<tr class="table-warning">
			<td></td>
			<td>'. $nombre.'</td>
			<td><center>'. $tipo.'</center></td>  
			<td style="text-align: right;"><b>'. $cantidad.'</b></td>
			<td style="text-align: right;">'. number_format($precio,0,",",".").'</td>
			<td style="text-align: right;">'. number_format($total,0,",",".").'</td>
			</tr>';

		} else {
			echo'<tr class="table-success">
			<td></td>
			<td>'. $nombre.'</td>
			<td><center>'. $tipo.'</center></td>  
			<td style="text-align: right;"><b>'. $cantidad.'</b></td>
			<td style="text-align: right;">'. number_format($precio,0,",",".").'</td>
			<td style="text-align: right;">'. number_format($total,0,",",".").'</td>
			</tr>';
		}
		echo '</tbody>';

		$cont++;

	}
	$porcentaje_adm2 = $total_presupuesto*($porcentaje_adm/100);
	$total_porcentaje = $total_presupuesto + $porcentaje_adm2;
	$porcentaje_rent2 = $total_porcentaje*($porcentaje_rent/100);
	$total_rentabilidad = $total_porcentaje + $porcentaje_rent2;
	echo '<tbody>
	<tr>
	<td colspan="4"></td>
	<td style="text-align: right;"><b>COSTO OPERACIONAL ($)</b></td>
	<td style="text-align: right;"><b>'. number_format($total_presupuesto,0,",",".").'</b></td>
	</tr>
	</tbody><tbody>
	<tr>
	<td colspan="4"></td>
	<td style="text-align: right;">'. $porcentaje_adm .' % DE ADMINISTRACIÓN</td>
	<td style="text-align: right;">'. number_format($porcentaje_adm2,0,",",".").'</td>
	</tr>
	</tbody><tbody>
	<tr>
	<td colspan="4"></td>
	<td style="text-align: right;"><b>COSTO + ADMINISTRACIÓN ($) </b></td>
	<td style="text-align: right;"><b>'. number_format($total_porcentaje,0,",",".").'</b></td>
	</tr>
	</tbody>
	<tbody>
	<tr>
	<td colspan="4"></td>
	<td style="text-align: right;">'. $porcentaje_rent .' % DE UTILIDAD</td>
	<td style="text-align: right;">'. number_format($porcentaje_rent2,0,",",".").'</td>
	</tr>
	</tbody><tbody>
	<tr>
	<td colspan="4"></td>
	<td style="text-align: right;"><b>TOTAL ($) </b></td>
	<td style="text-align: right;"><b>'. number_format($total_rentabilidad,0,",",".").'</b></td>
	</tr>
	</tbody>';

	if ($cont == 0) {
		echo '<tbody ">
		<tr>
		<td colspan="5" class="text-center"><p>No hay insumos disponibles para este presupuesto. <a href="presupuestos.php">Haga click aquí para seguir navegando</a></p></td>
		</tr>
		</tbody>';
	}
}

/**
** Función que despliega el detalle del presupuesto
**/
function ver_presupuesto4(){
	require_once "./include/config.php";
	$con = DB::getConn();

	$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
	$dec = $con->prepare('CALL sp_consultar_presupuesto (:id)');
	$dec -> bindParam(':id',$id_presupuesto);
	$dec -> execute();	
	$cont = 0;

	$total_presupuesto = '';
	$porcentaje_adm = '';
	$porcentaje_rent = '';
	$nombre_categoria    = '';
	$nombre_subcategoria = '';

	while ($linea = $dec->fetch(PDO::FETCH_ASSOC)) {
		$total_presupuesto = $linea['Total'];
		$porcentaje_adm = $linea['PorcentajeAdm'];
		$porcentaje_rent = $linea['PorcentajeRent'];
		if ($nombre_categoria !== $linea['Categoria']) {
			$nombre_categoria = $linea['Categoria'];
			echo '<tbody>
			<tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-'. $linea['IdCategoria'].'" aria-expanded="true" aria-controls="group-of-rows-'. $linea['IdCategoria'].'">
			<td>+</td>
			<td><b>'. $nombre_categoria.'</b></td>
			<td></td>  
			<td></td>
			<td></td>
			<td></td>
			</tr>
			</tbody>';
			
		}

		if ($nombre_subcategoria !== $linea['SubCategoria']) {
			$nombre_subcategoria = $linea['SubCategoria'];
			echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'">
			<tr class="table-active">
			<td>-</td>
			<td>'. $nombre_subcategoria.'</td>
			<td></td>  
			<td></td>
			<td></td>
			<td></td>
			</tr>
			</tbody>';

		}
		
		$nombre  = $linea['Insumo'];
		$tipo  = $linea['Tipo'];
		$cantidad  = $linea['Cantidad'];
		$precio  = $linea['Precio'];
		$total = $precio*$cantidad; 


		echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'"><tr class="tabla_insumos">
		<td></td>
		<td>'. $nombre.'</td>
		<td>'. $tipo.'</td>  
		<td style="text-align: right;"><b>'. $cantidad .'</b></td>
		<td style="text-align: right;">'. number_format($precio,0,",",".").'</td>
		<td style="text-align: right;">'. number_format($total,0,",",".").'</td>
		</tr></tbody>';

		$cont++;

	}
	$porcentaje_adm2 = $total_presupuesto*($porcentaje_adm/100);
	$total_porcentaje = $total_presupuesto + $porcentaje_adm2;
	$porcentaje_rent2 = $total_porcentaje*($porcentaje_rent/100);
	$total_rentabilidad = $total_porcentaje + $porcentaje_rent2;
	echo '
	<tbody>
	<tr>
	<td colspan="5" style="text-align: right;"><b>COSTO OPERACIONAL ($)</b></td>
	<td style="text-align: right;"><input type="text" style="text-align:right;" class="form-control" id="costo" name="costo" value="'. number_format($total_presupuesto,0,",",".").'"/></td>
	</tr>
	</tbody><tbody>
	<tr>
	<td colspan="5" style="text-align: right;"><input type="hidden" class="porcentaje_adm" name="porcentaje_adm" id="porcentaje_adm" value="'. $porcentaje_adm .'" /> % DE ADMINISTRACIÓN</td>
	<td style="text-align: right;"><input type="text" style="text-align:right;" class="form-control" id="porcentaje_adm2" name="porcentaje_adm2" value="'. number_format($porcentaje_adm2,0,",",".").'"/></td>
	</tr>
	</tbody><tbody>
	<tr>
	<td colspan="5" style="text-align: right;"><b>COSTO + ADMINISTRACIÓN ($) </b></td>
	<td style="text-align: right;"><input type="text" style="text-align:right;" class="form-control" id="total_adm" name="total_adm" value="'. number_format($total_porcentaje,0,",",".").'"/></td>
	</tr>
	</tbody>
	<tbody>
	<tr>
	<td colspan="5" style="text-align: right;"><input type="hidden" class="porcentaje_rent" id="porcentaje_rent" name="porcentaje_rent" value="'. $porcentaje_rent .'" /> % DE UTILIDAD</td>
	<td style="text-align: right;"><input type="text" style="text-align:right;" class="form-control" id="porcentaje_rent2" name="porcentaje_rent2" value="'. number_format($porcentaje_rent2,0,",",".").'"/></td>
	</tr>
	</tbody><tbody>
	<tr>
	<td colspan="5" style="text-align: right;"><b>TOTAL ($) </b></td>
	<td style="text-align: right;"><input type="text" style="text-align:right;" class="form-control" id="total_total" name="total_total" value="'. number_format($total_rentabilidad,0,",",".").'"/></td>
	</tr>
	</tbody>';

	if ($cont == 0) {
		echo '<tbody ">
		<tr>
		<td colspan="5" class="text-center"><p>No hay insumos disponibles para este presupuesto. <a href="presupuestos.php">Haga click aquí para seguir navegando</a></p></td>
		</tr>
		</tbody>';
	}
}
/**
** Función que despliega el detalle del presupuesto
**/
function ver_presupuesto5(){
	require_once "./include/config.php";
	$con = DB::getConn();

	$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
	$dec = $con->prepare('CALL sp_consultar_presupuesto (:id)');
	$dec -> bindParam(':id',$id_presupuesto);
	$dec -> execute();	
	$cont = 0;

	$total_presupuesto = '';
	$gasto_real = '';
	$fecha = '';
	$fecha2 = '';
	$nombre_categoria    = '';
	$nombre_subcategoria = '';

	while ($linea = $dec->fetch(PDO::FETCH_ASSOC)) {
		$total_presupuesto = $linea['Total'];
		$gasto_real = $linea['GastoReal'];
		$fecha = $linea['Fecha'];
		$fecha2 = $linea['FechaT'];
		if ($nombre_categoria !== $linea['Categoria']) {
			$nombre_categoria = $linea['Categoria'];
			echo '<tbody>
			<tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-'. $linea['IdCategoria'].'" aria-expanded="true" aria-controls="group-of-rows-'. $linea['IdCategoria'].'">
			<td>+</td>
			<td colspan="8"><b>'. $nombre_categoria.'</b></td>
			</tr>
			</tbody>';
			
		}

		if ($nombre_subcategoria !== $linea['SubCategoria']) {
			$nombre_subcategoria = $linea['SubCategoria'];
			echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'">
			<tr class="table-active">
			<td>-</td>
			<td colspan="8">'. $nombre_subcategoria.'</td>
			</tr>
			</tbody>';

		}
		
		$nombre  = $linea['Insumo'];
		$tipo  = $linea['Tipo'];
		$cantidad  = $linea['Cantidad'];
		$precio  = $linea['Precio'];
		$total = $precio*$cantidad;
		$precio_real = $linea['PrecioR'];
		$fecha_gasto = $linea['FechaG'];
		$total_real = $precio_real*$cantidad;
		$diferencia = $total-$total_real;

		if ($precio_real>$precio) {
			$style="color: rgb(244, 101, 36)";
		} elseif ($precio_real<$precio){
			$style="color: rgb(0, 153, 51)";
		}else{
			$style='';
		}
		if(isset($fecha_gasto)){
			$fecha_gasto1 = date("d/m/Y", strtotime($fecha_gasto));
		}else{
			$fecha_gasto1= date("d/m/Y", strtotime($fecha));
		}


		echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'"><tr class="tabla_insumos">
		<td></td>
		<td>'. $nombre.'</td>
		<td>'. $tipo.'</td>  
		<td style="text-align: right;"><b><div class="input-group mb-3"><input type="text" style="text-align:right;" class="form-control" id="cantidad_r[]" name="cantidad_r[]" value="'. $cantidad .'"/></b></td>
		<td style="text-align: right;">'. number_format($precio,0,",",".").'</td>
		<td style="text-align: right;"><input type="hidden" id=insumo_id[]" name="insumo_id[]" value="'. $linea['IdInsumo'].'"/><input type="text" style="text-align:right;" class="form-control" id="subtotal_prev[]" name="subtotal_prev[]" value="'. number_format($total,0,",",".").'" /></span></td>
		<td style="text-align: right;"><input type="text" style="text-align:right;" class="precioI form-control" id="precio_r[]" name="precio_r[]" value="'. number_format($precio_real,0,",",".").'"/>
		<input id="fecha_gasto[]" name="fecha_gasto[]" value="'. $fecha_gasto1 .'" data-fecha_min="'.$fecha.'" data-fecha_max="'.$fecha2.'"/>
		</span></td>
		<td style="text-align: right;"><input type="text" style="text-align:right;" class="form-control" id="subt_r[]" name="subt_r[]" value="'. number_format($total_real,0,",",".").'"/></span></td>
		<td style="text-align: right;"><input type="text" style="text-align:right; '.$style.'" class="form-control" id="diferencia[]" name="diferencia[]" value="'. number_format($diferencia,0,",",".").'"/></span></td>
		</tr></tbody>';

		$cont++;

	}
	$diferencia = $total_presupuesto -  $gasto_real;
	if ($gasto_real>$total_presupuesto) {
			$style2="color: rgb(244, 101, 36)";
		} elseif ($gasto_real<$total_presupuesto){
			$style2="color: rgb(0, 153, 51)";
		}else{
			$style2='';
		}
	echo '<tbody>
	<tr > 
	<td colspan="5" style="text-align: right;"><b>GASTO PREVISTO ($) </b></td>
	<td style="text-align: right;"><input type="text" style="text-align:right;" class="form-control" id="total" name="total" value="'. number_format($total_presupuesto,0,",",".").'"/></td>
	<td><b>GASTO REAL ($)</b></d>
	<td style="text-align: right;"><input type="text" style="text-align:right;" class="form-control" id="total_real" name="total_real" value="'. number_format($gasto_real,0,",",".").'"/></td>
	<td style="text-align: right; '.$style2.'"><input type="text" style="text-align:right; color: rgb(244, 101, 36);" class="form-control" id="dif" name="dif" value="'. number_format($diferencia,0,",",".").'"/></td>
	</tr>
	</tbody>';

	if ($cont == 0) {
		echo '<tbody ">
		<tr>
		<td colspan="5" class="text-center"><p>No hay insumos disponibles para este presupuesto. <a href="presupuestos.php">Haga click aquí para seguir navegando</a></p></td>
		</tr>
		</tbody>';
	}
}

/**
** Función que despliega el detalle del presupuesto
**/
function ver_presupuesto6(){
	require_once "./include/config.php";
	$con = DB::getConn();

	$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
	$dec = $con->prepare('CALL sp_consultar_presupuesto (:id)');
	$dec -> bindParam(':id',$id_presupuesto);
	$dec -> execute();	
	$cont = 0;

	$total_presupuesto = '';
	$gasto_real = '';
	$porcentaje_adm = '';
	$fecha = '';
	$fecha2 = '';
	$nombre_categoria    = '';
	$nombre_subcategoria = '';

	while ($linea = $dec->fetch(PDO::FETCH_ASSOC)) {
		$total_presupuesto = $linea['Total'];
		$gasto_real = $linea['GastoReal'];
		$porcentaje_adm = $linea['PorcentajeAdm'];
		$fecha = $linea['Fecha'];
		$fecha2 = $linea['FechaT'];
		if ($nombre_categoria !== $linea['Categoria']) {
			$nombre_categoria = $linea['Categoria'];
			echo '<tbody>
			<tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-'. $linea['IdCategoria'].'" aria-expanded="true" aria-controls="group-of-rows-'. $linea['IdCategoria'].'">
			<td>+</td>
			<td colspan="8"><b>'. $nombre_categoria.'</b></td>
			</tr>
			</tbody>';
			
		}

		if ($nombre_subcategoria !== $linea['SubCategoria']) {
			$nombre_subcategoria = $linea['SubCategoria'];
			echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'">
			<tr class="table-active">
			<td>-</td>
			<td colspan="8">'. $nombre_subcategoria.'</td>
			</tr>
			</tbody>';

		}
		
		$nombre  = $linea['Insumo'];
		$tipo  = $linea['Tipo'];
		$cantidad  = $linea['Cantidad'];
		$precio  = $linea['Precio'];
		$total = $precio*$cantidad;
		$precio_real = $linea['PrecioR'];
		$fecha_gasto = $linea['FechaG'];
		$total_real = $precio_real*$cantidad;
		$diferencia = $total-$total_real;

		if ($precio_real>$precio) {
			$style="color: rgb(244, 101, 36)";
		} elseif ($precio_real<$precio){
			$style="color: rgb(0, 153, 51)";
		}else{
			$style='';
		}
		if(isset($fecha_gasto)){
			$fecha_gasto1 = date("d/m/Y", strtotime($fecha_gasto));
		}else{
			$fecha_gasto1= date("d/m/Y", strtotime($fecha));
		}


		echo '<tbody id="group-of-rows-'. $linea['IdCategoria'].'"><tr class="tabla_insumos">
		<td></td>
		<td>'. $nombre.'</td>
		<td>'. $tipo.'</td>  
		<td style="text-align: right;"><b>'. $cantidad .'</b></td>
		<td style="text-align: right;">'. number_format($precio,0,",",".").'</td>
		<td style="text-align: right;"><b>'. number_format($total,0,",",".").'</b></td>
		<td style="text-align: right;">'. number_format($precio_real,0,",",".").'
		<input id="fecha_g[]" name="fecha_g[]" value="'. $fecha_gasto1 .'" readonly="readonly" disabled/>
		</td>
		<td style="text-align: right;"><b>'. number_format($total_real,0,",",".").'</b></td>
		<td style="text-align:right; '.$style.'">'. number_format($diferencia,0,",",".").'</td>
		</tr></tbody>';

		$cont++;

	}
	$porcentaje_adm2 = $total_presupuesto*($porcentaje_adm/100);
	$gasto_estimado = $total_presupuesto + $porcentaje_adm2;
	$gasto_final_total = $gasto_real + $porcentaje_adm2;
	$diferencia = $gasto_estimado-$gasto_final_total;
	if ($gasto_final_total>$gasto_estimado) {
			$style2="color: rgb(244, 101, 36)";
		} elseif ($gasto_final_total<$gasto_estimado){
			$style2="color: rgb(0, 153, 51)";
		}else{
			$style2='';
		}
	echo '<tbody>
	<tr > 
	<td colspan="5" style="text-align: right;"><b>GASTO PREVISTO ($) </b></td>
	<td style="text-align: right;"><b>'. number_format($total_presupuesto,0,",",".").'</b></td>
	<td style="text-align: right;"><b>GASTO REAL ($)</b></d>
	<td style="text-align: right;"><b>'. number_format($gasto_real,0,",",".").'</b></td>
	<td></td>
	</tr>
	</tbody>
	';

	if ($cont == 0) {
		echo '<tbody ">
		<tr>
		<td colspan="9" class="text-center"><p>No hay insumos disponibles para este presupuesto. <a href="presupuestos.php">Haga click aquí para seguir navegando</a></p></td>
		</tr>
		</tbody>';
	}
}

/**
** Función que edita los precios de cada insumo
**/
function agregar_precio(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['guardar'])){
		$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
		$precio_insumo = $_POST['precio_insumo'];
		$precio_insumo2 = str_replace(".","",$precio_insumo); 
		$id_insumo=$_POST['insumo_id'];
		$total = $_POST['total'];
		$total2 = str_replace(".","",$total);
		$count=0;

		foreach($precio_insumo2 as $key => $value){
			$precioInsumo = $value;
			$idInsumo = $id_insumo[$key];
			//Inserta un usuario nuevo
			$dec = $con ->  prepare('UPDATE insumos SET precio_insumo=:precio_insumo WHERE id_insumo=:id_insumo');
			$dec -> bindParam(':precio_insumo',$precioInsumo);
			$dec -> bindParam(':id_insumo',$idInsumo);
			$dec -> execute();

			$count++;
		}

		if($total2 != 0){
			$dec = $con ->  prepare('UPDATE presupuesto SET total=:total WHERE id_presupuesto=:id_presupuesto');
			$dec -> bindParam(':total',$total2);
			$dec -> bindParam(':id_presupuesto',$id_presupuesto);
			$dec -> execute();
		}
		
		if($count >= 1){
			$_SESSION['message'] = "Valores del presupuesto actualizado correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: presupuestos.php');
			exit;
		}
		else{
			$errores[] = 'Lo sentimos, la actualización falló. Por favor, regrese y vuelva a intentarlo.';
		}

	}
	return $errores;
	
}


/**
** Función que elimina presupuesto
**/
function eliminar_presupuesto(){
	require_once "./include/config.php";

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['eliminar'])){
		$id=intval($_POST['eliminar_id_presupuesto']);

		//Eliminar cliente
		$dec = $con ->  prepare('DELETE FROM presupuesto WHERE id_presupuesto=:id');
		$dec -> bindParam(':id',$id);
		$dec -> execute();


		if($dec->rowCount() >= 1){
			$_SESSION['message'] = "Presupuesto eliminado correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: presupuestos.php');
			exit;
		}
		else{
			$errores[] = 'Lo sentimos, la eliminación falló. Por favor, regrese y vuelva a intentarlo.';
		}

	}
	return $errores;
	
}


/**
** Función que edita los costos del presupuesto
**/
function agregar_costo(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['guardar'])){
		$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
		$porcentaje_adm = $_POST['porcentaje_adm'];
		$porcentaje_rent= $_POST['porcentaje_rent'];

		//Actualizar el presupuesto con los porcentajes dados
		$dec = $con ->  prepare('UPDATE presupuesto SET porcentaje_adm=:porcentaje_adm, porcentaje_rent=:porcentaje_rent WHERE id_presupuesto=:id_presupuesto');
		$dec -> bindParam(':porcentaje_adm',$porcentaje_adm);
		$dec -> bindParam(':porcentaje_rent',$porcentaje_rent);
		$dec -> bindParam(':id_presupuesto',$id_presupuesto);
		$dec -> execute();


		if($dec->rowCount() >= 1){
			$_SESSION['message'] = "Valores de los costos presupuestarios actualizado correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: presupuestos.php');
			exit;
		}
		else{
			$errores[] = 'Lo sentimos, la actualización falló. Por favor, regrese y vuelva a intentarlo.';
		}

	}
	return $errores;
	
}


/**
** Función que edita los gastos de cada insumo
**/
function agregar_gasto(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['guardar'])){
		$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
		$precio_real = $_POST['precio_r'];
		$precio_real2 = str_replace(".","",$precio_real); 
		$id_insumo = $_POST['insumo_id'];
		$fecha_gasto = $_POST['fecha_gasto'];
		$gasto_real = $_POST['total_real'];
		$gasto_real2 = str_replace(".","",$gasto_real);
		$count=0;

		foreach($precio_real2 as $key => $value){
			$precioReal = $value;
			$idInsumo = $id_insumo[$key];
			$fechaGasto = $fecha_gasto[$key];
			$fecha = DateTime::createFromFormat('d/m/Y', $fechaGasto);
			$fecha = $fecha->format('Y-m-d');
			//Actualiza el insumo con su precio real
			$dec = $con ->  prepare('CALL sp_insertar_gasto_insumo(:id_insumo, :precio_real, :fecha_gasto)');
			$dec -> bindParam(':id_insumo',$idInsumo);
			$dec -> bindParam(':precio_real',$precioReal);
			$dec -> bindParam(':fecha_gasto',$fecha);
			$dec -> execute();

			$count++;
		}
		if($gasto_real2 != 0){
			$dec = $con ->  prepare('UPDATE presupuesto SET gasto_real=:gasto_real WHERE id_presupuesto=:id_presupuesto');
			$dec -> bindParam(':gasto_real',$gasto_real2);
			$dec -> bindParam(':id_presupuesto',$id_presupuesto);
			$dec -> execute();
		}

		
		if($count >= 1){
			$_SESSION['message'] = "Valores de los gastos agregados correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: gasto?idPresupuesto='.$id_presupuesto.'');
			exit;
		}
		else{
			$errores[] = 'Lo sentimos, la actualización falló. Por favor, regrese y vuelva a intentarlo.';
		}

	}
	return $errores;
	
}

/**
** Función que despliega una lista de presupuestos aprobados y finalizados
**/
function listar_presupuestos_reportes(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();
	$id = $_SESSION['id'];
	
	$dec = $con -> prepare('SELECT p.id_presupuesto AS IdP, p.nombre_obra AS NombreObra, c.nombre_cliente AS NombreCliente, u.id AS IdUsuario, u.nombre AS Nombre, u.apellido AS Apellido, p.fecha_presupuesto AS Fecha, p.fecha_termino AS Fecha2, ROUND((p.total + p.total*p.porcentaje_adm/100)+((p.total + p.total*p.porcentaje_adm/100)*porcentaje_rent/100)) AS Total, p.estado AS Estado FROM presupuesto p INNER JOIN clientes c ON p.id_cliente = c.id_cliente JOIN usuarios u ON p.id = u.id WHERE p.estado>2 ORDER BY p.fecha_presupuesto DESC;
');
	$dec->bindParam(':id', $id);
	$dec->execute();
	if($dec->rowCount() >= 1){
		while($linea = $dec->fetch()){
			echo '<tr>
			<td>'. $linea['IdP'] .'</td>
			<td>'. $linea['NombreObra'] .'</td>		
			<td>'. $linea['Nombre'] .' '. $linea['Apellido'] .'</td>
			<td>'. $linea['NombreCliente'] .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha'])) .'</td>
			<td>'. strftime("%d de %B del %Y", strtotime($linea['Fecha2'])) .'</td>
			<td>$ '. number_format($linea['Total'],0,",",".").'</td>';
			if($linea['Estado']==3){
				echo '<td><span class="badge badge-pill badge-success">Aprobado</span></td>';
			}
			if($linea['Estado']==4){
				echo '<td><span class="badge badge-pill badge-primary">Finalizado</span></td>';
			}
			echo '</tr>';
		}
	}else{
		echo '<tr>
		<td colspan="7" class="text-center"> No hay datos para mostrar.</td>
		</tr>';
	}
}

?>