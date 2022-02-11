<?php
$_idOficina = 0;
$_fechaBuscar = '';
if(isset($_GET['idO'])){
	$_idOficina = $_GET['idO'];
}
if(isset($_GET['fechaBuscar'])){
	$_fechaBuscar = $_GET['fechaBuscar'];
}
$_fechaBuscar = substr($_fechaBuscar,6,4) . "-" . substr($_fechaBuscar,3,2) . "-" .substr($_fechaBuscar,0,2);
$query = "SELECT `salida`.`id_salida`
,`salida`.`hora`
, `ruta`.`destino`
,`bus`.`flota`
FROM `salida` 
INNER JOIN `bus` 
ON `bus`.`id_bus`=`salida`.`id_bus` 
INNER JOIN `ruta` 
ON `salida`.`id_ruta`=`ruta`.`id_ruta` 
INNER JOIN `oficinas` 
ON `salida`.`idoficina`=`oficinas`.`idoficina`
WHERE `salida`.`idoficina`= ".$_idOficina."
AND `salida`.`fecha` = '".$_fechaBuscar."'
ORDER BY `salida`.`hora` ASC;";
session_start();
require_once('is_logged.php');
require_once('config_trans.php');
$db_transporte->query($query);
$_array = $db_transporte->get();
$response = "[";
$objeto = '';
if(count($_array)> 0)
{
	for($x = 0; $x < count($_array); $x++)
	{
		$objeto = '{"id" : "'.$_array[$x][0].'", "hora" : "'.$_array[$x][1].'", ';
		$objeto .= '"destino" : "'.$_array[$x][2].'", "flota" : "'.$_array[$x][3].'"}';
		if($x < count($_array) - 1)
			$response .= $objeto. ", ";
		else
			$response .= $objeto;
	}
	$response .= ']';
	echo utf8_encode($response);
}
?>