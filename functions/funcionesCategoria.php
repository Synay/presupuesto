<?php
/**
** Función que lista las categorías
**/
function listar_categoria(){
	require_once "./include/config.php";
	$con = DB::getConn();

	$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
	$dec = $con->prepare('CALL sp_consultar_categorias (:id)');
	$dec -> bindParam(':id',$id_presupuesto);
	$dec -> execute();	
	$cont = 0;

	while ($linea = $dec->fetch(PDO::FETCH_ASSOC)) {
		echo '<div id="form_div">
		<table class="table" id="subcategoria_tabla_'.$linea['IdCategoria'].'" align=center>
		<tr><b>'.$linea['Categoria'].'</b></tr>
		<tr id="'.$linea['IdCategoria'].'row1">
		<td><input type="hidden" name="categoria_id[]" id="categoria_id[]" value="'.$linea['IdCategoria'].'" ><div class="input-group mb-3"><input class="form-control" type="text" name="subcategoria[]" placeholder="Nombre de la subcategoría" required></div></td>
		</tr>
		</table>
		<button type="button" class="add btn btn-success" onclick="add_row2('.$linea['IdCategoria'].');">Agregar</button>
		</div><br>';

		$cont++;

	}

	if ($cont == 0) {
		echo 'NO HAY CATEGORÍAS';
	}


}

/**
** Función que guarda la categoría
**/
function guardar_categoria(){
	require_once "./include/config.php";
	require_once "./functions/funciones.php";
	$con = DB::getConn();

	if(!empty($errores)){
		return $errores;
	}

	if(isset($_POST['guardar'])){
		$id_presupuesto = isset($_GET['idPresupuesto']) ? intval($_GET['idPresupuesto']) : 0;
		$categoria=$_POST['categoria'];

		$count = 0;
		foreach($categoria as $key => $value){
			$nCat = $value;
			if($nCat!=''){
				$dec = $con->prepare('CALL sp_insertar_categoria (:id_presupuesto, :categoria)');
				$dec -> bindParam(':id_presupuesto',$id_presupuesto);
				$dec -> bindParam(':categoria',$nCat);
				$dec -> execute();
				$count++;
			}
		}

		if($count >= 1){
			header('Location: subcategorias.php?idPresupuesto='. $id_presupuesto.'');
			exit;
		}
		$errores[] = 'Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.';

	}
	return $errores;
}
?>