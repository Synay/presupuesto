<?php
ini_set('default_charset', 'UTF-8');
require_once "../include/config.php";
$con = DB::getConn();
$term = '%'.trim(strip_tags($_GET['q'])).'%';
 
$dec = $con -> prepare("SELECT * FROM clientes WHERE nombre_cliente LIKE :term LIMIT 10");
$dec ->bindValue(':term',$term);
$dec->execute();

$json = [];
while($row = $dec->fetch(PDO::FETCH_ASSOC)){
     $json[] = ['id'=>$row['id_cliente'], 'text'=>$row['nombre_cliente']];
}
echo json_encode($json);