<?php
define('aDS', DIRECTORY_SEPARATOR);
define('aROOT', realpath(dirname(__FILE__)) .aDS);
$_id = 0;
if(isset($_GET['id'])){
	$_id = $_GET['id'];
}
$query = "SELECT `salida`.`id_salida`
, `salida`.`fecha`
, `salida`.`hora`
, `oficinas`.`oficina`
, `ruta`.`destino`
FROM `salida`
INNER JOIN `oficinas`
ON `oficinas`.`idoficina` = `salida`.`idoficina`
INNER JOIN `ruta`
ON `salida`.`id_ruta` = `ruta`.`id_ruta`
WHERE `salida`.`id_salida` = '".$_id."';";

require_once(aROOT .'..' .aDS .'..' .aDS .'cnn'. aDS .'config_trans.php');
$db_transporte->query($query);
$_array = $db_transporte->get();
$response = "";
$objeto = '';
/* OBTENEMOS LA SERIE Y NUMERO DE BOLETOS */

if(count($_array)> 0)
{
	$x = 0;
	$objeto = '{"id" : "'.$_array[$x][0].'", "fecha" : "'.$_array[$x][1].'", ';
	$objeto .= '"hora" : "'.$_array[$x][2].'", "oficina" : "'.$_array[$x][3].'", ';
	$objeto .= '"destino" : "'.$_array[$x][4].'"}';
	$response .= $objeto;
}else
{
	$objeto = '';
	$response .= $objeto;
}
$response .= '';
	echo utf8_encode($response);
?>