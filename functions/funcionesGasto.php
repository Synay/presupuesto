<?php

/**
** Función que obtiene la suma de todo el gasto del mes anterior
**/
function gastoMensualAnterior(){
	require_once "./include/config.php";
	$con = DB::getConn();

	$total_mes_anterior = null;
	$dec = $con -> prepare('SELECT SUM(i.cantidad_insumo*g.precio_real) AS GastoMensual FROM gastos_insumo g RIGHT JOIN insumos i on g.id_insumo=i.id_insumo   WHERE DATE(fecha_gasto) BETWEEN DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) AND LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH));');
	$dec->execute();
	$linea = $dec->fetch(PDO::FETCH_ASSOC);
	if($dec->rowCount() >= 1){
		$total_mes_anterior = $linea['GastoMensual'];
		
	}
	return number_format($total_mes_anterior,0,",",".");

}