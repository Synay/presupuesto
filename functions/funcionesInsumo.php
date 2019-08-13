<?php

/**
** Función que obtiene los insumos en un conjunto de opciones para un select
**/
function obtener_insumo()
{
 require_once "./include/config.php";
 require_once "./functions/funciones.php";
 $con = DB::getConn();

 $output = '';
 $dec = $con -> prepare('SELECT * FROM tipos ORDER BY id_tipo ASC');
 $dec->execute();
 $lineas = $dec->fetchAll(PDO::FETCH_ASSOC);
 foreach($lineas as $linea)
 {
  $output .= '<option value="'.$linea['id_tipo'].'">'.$linea['nombre_tipo'].'</option>';
 }
 return $output;
}

/**
** Función que guarda los insumos de cada subcategoría creada
**/
function guardar_insumo(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['guardar'])){
		$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
		$subcategoria_id=$_POST['subcategoria_id'];
		$insumo=$_POST['insumo'];
		$tipo = $_POST['tipo'];
		$cantidad = $_POST['cantidad'];


		$count = 0;
		foreach($insumo as $key => $value){
			$nIns = $value;
			$nSCat = $subcategoria_id[$key];
			$nTipo = $tipo[$key];
			$nCan = $cantidad[$key];
			if($nIns!='' && $nSCat !='' && $nTipo !='' && $nCan !=''){
				$dec = $con->prepare('CALL sp_insertar_insumo (:id_subcategoria, :insumo, :tipo, :cantidad)');
				$dec -> bindParam(':id_subcategoria',$nSCat);
				$dec -> bindParam(':insumo',$nIns);
				$dec -> bindParam(':tipo',$nTipo);
				$dec -> bindParam(':cantidad',$nCan);
				$dec -> execute();
				$count++;
			}

		}

		if($count >= 1){
			$_SESSION['message'] = "Presupuesto finalizado y agregado correctamente.";
			$_SESSION['message_type'] = 'success';
			header('Location: presupuestos.php');
			exit;
		}
		$errores[] = 'Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.';

	}
	return $errores;
}