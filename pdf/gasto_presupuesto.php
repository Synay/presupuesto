<?php
session_start();
require_once "../include/config.php";
$con = DB::getConn();

$id=$_GET['idPresupuesto'];
$nombre_obra="";

$html = '
<html lang="es">
<head>
<meta charset="utf-8">
<style>
body {font-family: sans-serif;
	font-size: 10pt;
}
p {	margin: 0pt; }
table.items {
	border: 0.1mm solid #000000;
}
td { vertical-align: top; }
.items td {
	border-left: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
}
table thead td { background-color: #EEEEEE;
	text-align: center;
	border: 0.1mm solid #000000;
	font-variant: small-caps;
}
.items td.blanktotal {
	background-color: #EEEEEE;
	border: 0.1mm solid #000000;
	background-color: #FFFFFF;
	border: 0mm none #000000;
	border-top: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
}
.items td.totals {
	text-align: right;
	border: 0.1mm solid #000000;
}
.items td.cost {
	text-align: "." center;
}
</style>
</head>
<body>';
$info = $con -> prepare('SELECT p.nombre_obra AS NombreO, p.fecha_presupuesto AS Fecha, p.fecha_termino AS FechaT, c.nombre_cliente AS NombreC, c.rut_cliente AS RutC, c.email_cliente AS EmailC, c.direccion AS DireccionC, c.contacto AS Contacto, c.contacto2 AS Contacto2, u.nombre AS NombreU, u.apellido AS ApellidoU, u.email AS EmailU, u.privilegio As PrivilegioU, e.nombre_empresa AS NombreE, e.rut_empresa AS RutE, e.direccion_empresa AS Direccion, e.telefono_empresa AS Telefono FROM presupuesto p INNER JOIN clientes c ON p.id_cliente = c.id_cliente INNER JOIN usuarios u ON p.id = u.id INNER JOIN empresas e ON u.id_empresa = e.id_empresa where p.id_presupuesto=:id');
$info->bindParam(':id', $id);
$info->execute();

while($row = $info->fetch(PDO::FETCH_ASSOC)){
	switch($row['PrivilegioU']){
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
	$html2 = '<!--mpdf
	<htmlpageheader name="myheader">
	<table width="100%"><tr>
	<td width="10%"><img style="width: 10%;" src="../img/logo.png" alt="Logo"></td>
	<td width="40%" style="color:#0000BB; vertical-align: middle;">
      <span style="font-weight: bold; font-size: 14pt;">'.$row['NombreE'].'</span><br />'.$row['RutE'].'<br />'.$row['Direccion'].'<br /><span style="font-family:dejavusanscondensed;">&#9742;</span>'.$row['Telefono'].'</td>
	<td width="50%" style="text-align: right; font-size: 14pt;">Gasto del presupuesto.<br /><span style="font-weight: bold; font-size: 16pt;">N° '.str_pad($id, 3, "0", STR_PAD_LEFT).'</span></td>
	</tr></table>
	</htmlpageheader>
	<htmlpagefooter name="myfooter">
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	Página {PAGENO} de {nb}
	</div>
	</htmlpagefooter>
	<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
	<sethtmlpagefooter name="myfooter" value="on" />
	mpdf-->
	<div style="text-align: right;font-family:dejavusanscondensed;">'. strftime("%d de %B del %Y", strtotime($row['Fecha'])) .' al<br>'. strftime("%d de %B del %Y", strtotime($row['FechaT'])) .'</div>
	<table width="100%" style="font-family: sans; color: #555555;" cellpadding="10"><tr>
	<td width="45%" style="border: 0.1mm solid #888888; "><span style="font-size: 7pt; color: #555555; font-family: sans;">DIRIGIDO A:</span><br /><br />'.$row['NombreC'].'<br />'.$row['RutC'].'<br />'.$row['EmailC'].'<br />'.$row['DireccionC'].'<br />'.$row['Contacto'].'<br />'.$row['Contacto2'].'</td>
	<td width="10%">&nbsp;</td>
	<td width="45%" style="border: 0.1mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans;">GESTIONADO POR:</span><br /><br />'.$row['NombreU']." ".$row['ApellidoU'].'<br />Departamento de '.$departamento.'<br />'.$row['EmailU'].'</td>
	</tr></table>
	<br />';
	$nombre_obra=$row['NombreO'];
}

$html3 = '<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
<thead>
<tr>
<td width="20%">DESCRIPCIÓN</td>
<td width="9%">UNIDAD</td>
<td width="8%" style="text-align: right;">CANTIDAD</td>
<td width="11%" style="text-align: right;">PREVISTO ($)</td>
<td width="11%" style="text-align: right;">SUBTOTAL ($)</td>
<td width="11%" style="text-align: right;">REAL ($)</td>
<td width="11%" style="text-align: right;">SUBTOTAL R ($)</td>
<td width="11%" style="text-align: right;">DIFERENCIA ($)</td>
<td width="8%">FECHA</td>
</tr>
</thead>';

$dec = $con->prepare('CALL sp_consultar_presupuesto (:id)');
$dec -> bindParam(':id',$id);
$dec -> execute();

$total_presupuesto = '';
$total_gasto_operacional = '';
$porcentaje_adm = '';
$porcentaje_rent = '';
$nombre_categoria    = '';
$nombre_subcategoria = '';

while ($linea = $dec->fetch(PDO::FETCH_ASSOC)) {
	$total_presupuesto = $linea['Total'];
	$total_gasto_operacional = $linea['GastoReal'];
	$porcentaje_adm = $linea['PorcentajeAdm'];
	$porcentaje_rent = $linea['PorcentajeRent'];
	if ($nombre_categoria !== $linea['Categoria']) {
		$nombre_categoria = $linea['Categoria'];
		$html_sub1 = $html_sub1.'<tbody>
		<tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-'. $linea['IdCategoria'].'" aria-expanded="true" aria-controls="group-of-rows-'. $linea['IdCategoria'].'">
		<td colspan="9">'. $nombre_categoria.'</td>
		</tr>
		</tbody>';
		

	}
	

	if ($nombre_subcategoria !== $linea['SubCategoria']) {
		$nombre_subcategoria = $linea['SubCategoria'];
		$html_sub1 = $html_sub1.'<tbody id="group-of-rows-'. $linea['IdCategoria'].'" class="collapse">
		<tr class="table-active">
		<td colspan="9"><b>'. $nombre_subcategoria.'</b></td>
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
	$total_real= $precio_real*$cantidad;
	$diferencia=$precio-$precio_real;

	if ($precio_real>$precio) {
			$style="color: rgb(244, 101, 36)";
		} elseif ($precio_real<$precio){
			$style="color: rgb(0, 153, 51)";
		}else{
			$style='';
		}
		if(isset($fecha_gasto)){
			$fecha_gasto1 = strftime("%d de %B del %Y", strtotime($fecha_gasto));
		}else{
			$fecha_gasto1= " ";
		}



	$html_sub1 =$html_sub1.'<tbody id="group-of-rows-'. $linea['IdCategoria'].'" class="collapse"><tr style="border:0.1mm solid #000000;">
	<td style="vertical-align:middle;">'. $nombre.'</td>
	<td style="vertical-align:middle;"><center>'. $tipo.'</center></td>  
	<td style="text-align: right; vertical-align:middle;"><b>'. $cantidad.'</b></td>
	<td style="text-align: right; vertical-align:middle;">'. number_format($precio,0,",",".").'</td>
	<td style="text-align: right; vertical-align:middle;">'. number_format($total,0,",",".").'</td>
	<td style="text-align: right; vertical-align:middle;">'. number_format($precio_real,0,",",".").'</td>
	<td style="text-align: right; vertical-align:middle;">'. number_format($total_real,0,",",".").'</td>
	<td style="text-align: right; vertical-align:middle; '.$style.'">'. number_format($diferencia,0,",",".").'</td>
	<td style="text-align: right; vertical-align:middle;">'. $fecha_gasto1.'</td>
	</tr></tbody>';
	
	
}
$porcentaje_adm2 = $total_presupuesto*($porcentaje_adm/100);
$total_porcentaje = $total_presupuesto + $porcentaje_adm2;
$porcentaje_rent2 = $total_porcentaje*($porcentaje_rent/100);
$total_rentabilidad = $total_porcentaje + $porcentaje_rent2;
$total_diferencia = $total_presupuesto-$total_gasto_operacional;

if ($total_gasto_operacional>$total_presupuesto) {
			$style2="color: rgb(244, 101, 36)";
		} elseif ($total_gasto_operacional<$total_presupuesto){
			$style2="color: rgb(0, 153, 51)";
		}else{
			$style2='';
		}

$html4 = $html_sub1.'
<tbody>
<tr style="border: 0;">
<td colspan="5" style="text-align: right;"><b>SUBTOTAL ($)</b></td>
<td style="text-align: right;"><b>'. number_format($total_presupuesto,0,",",".").'</b></td>
<td style="text-align: right;"><b>'. number_format($total_gasto_operacional,0,",",".").'</b></td>
<td style="text-align: right; '.$style2.'"><b>'. number_format($total_diferencia,0,",",".").'</b></td>
<td style="text-align: right;"></td>
</tr>
</tbody>';

$html5 ='</table>
<div style="text-align: center; font-style: italic;">Este presupuesto puede estar sujeto a cambios </div>
</body>
</html>
';

$htmlTotal =  $html.$html2.$html3.$html4.$html5;


require_once '../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
	'allow_charset_conversion' => true,
	'charset_in' => 'UTF-8',
	'margin_left' => 20,
	'margin_right' => 15,
	'margin_top' => 40,
	'margin_bottom' => 25,
	'margin_header' => 10,
	'margin_footer' => 10
]);
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle($nombre_obra);
$mpdf->SetAuthor($_SESSION['id']);
$mpdf->SetDisplayMode('fullpage');
$mpdf->AddPage('L');
$mpdf->WriteHTML($htmlTotal);
$mpdf->Output('gastoPresupuesto'.$id.'.pdf', 'I');