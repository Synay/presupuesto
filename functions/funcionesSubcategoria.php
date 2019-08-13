<?php

/**
** Función que guarda las subcategorías de cada categoría creada
**/ 
function guardar_subcategoria(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['guardar'])){
		$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
		$categoria_id=$_POST['categoria_id'];
		$subcategoria=$_POST['subcategoria'];


		$count = 0;
		foreach($subcategoria as $key => $value){
			$nSCat = $value;
			$nCat = $categoria_id[$key];
			if($nSCat!='' && $nCat !=''){
				$dec = $con->prepare('CALL sp_insertar_subcategoria (:id_categoria, :subcategoria)');
				$dec -> bindParam(':id_categoria',$nCat);
				$dec -> bindParam(':subcategoria',$nSCat);
				$dec -> execute();
				$count++;
			}

		}

		if($count >= 1){
			header('Location: insumos.php?idPresupuesto='. $id_presupuesto.'');
			exit;
		}
		$errores[] = 'Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.';

	}
	return $errores;
}

/**
** Función que lista las subcategorías 
**/
function listar_subcategoria(){
	require_once "./include/config.php";
	require_once "./functions/funcionesInsumo.php";
	$con = DB::getConn();

	$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
	$insumo = obtener_insumo();
	$dec = $con->prepare('CALL sp_consultar_subcategorias (:id)');
	$dec -> bindParam(':id',$id_presupuesto);
	$dec -> execute();
	$nombre_categoria = '';
	$nombre_subcategoria = '';	
	$cont = 0;
	

	while ($linea = $dec->fetch(PDO::FETCH_ASSOC)) {
		if ($nombre_categoria !== $linea['Categoria']) {
			$nombre_categoria = $linea['Categoria'];
			echo '<div id="form_div">
			<b>'.$linea['Categoria'].'</b>     
			</div><br>';
			
		}
		if ($nombre_subcategoria !== $linea['SubCategoria']) {
			$nombre_subcategoria = $linea['SubCategoria'];
			echo '<div id="form_div">
			<table class="table" id="insumo_tabla_'.$linea['IdSubcategoria'].'" align=center>
			<tr>'.$linea['SubCategoria'].'</tr>
			<tr id="'.$linea['IdSubcategoria'].'row1">
			<td width="40%"><input type="hidden" name="subcategoria_id[]" id="subcategoria_id[]" value="'.$linea['IdSubcategoria'].'" ><div class="input-group mb-3"><input class="insumo form-control" type="text" name="insumo[]" placeholder="Descripción del insumo" required></div>
			</td>
			<td width="35%">
			<div class="input-group mb-3">
                     <select name="tipo[]" id="tipo[]" class="listarTipo custom-select" required><option value="">Seleccionar unidad</option>'.$insumo.'</select>
                    </div>
                    <script></script>
			</td>
			<td width="25%">
			<div class="input-group mb-3"><input class="cantidad form-control" type="number" min="0.01" step="0.01" name="cantidad[]" placeholder="Cantidad" required></div>
			</td>
			</tr>
			</table>
			<button type="button" class="add btn btn-success" onclick="add_row3('.$linea['IdSubcategoria'].');">Agregar</button>
			</div><br>';

		}
		$cont++;

	}

	if ($cont == 0) {
		echo 'NO HAY SUBCATEGORÍAS';
	}
}

?>