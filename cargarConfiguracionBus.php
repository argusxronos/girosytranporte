<?php
$_idSalida = '';
if (isset($_GET['idS'])) {
	# code...
	$_idSalida = $_GET['idS'];
}
session_start();
require_once('is_logged.php');
$query = "SELECT `configuracion_bus`.`fila`
, `configuracion_bus`.`piso`
, `configuracion_bus`.`n1`
, `configuracion_bus`.`n2`
, `configuracion_bus`.`n3`
, `configuracion_bus`.`n4`
, `configuracion_bus`.`n5`
, (SELECT `record_cliente`.`estado` 
FROM `record_cliente` 
WHERE `configuracion_bus`.`piso` = `record_cliente`.`piso` 
AND `configuracion_bus`.`n1` = `record_cliente`.`asiento`
AND `salida`.`id_salida` = `record_cliente`.`id_salida`
AND `record_cliente`.`estado` <> 8) AS 'en1'
, (SELECT `record_cliente`.`estado` 
FROM `record_cliente` 
WHERE `configuracion_bus`.`piso` = `record_cliente`.`piso` 
AND `configuracion_bus`.`n2` = `record_cliente`.`asiento`
AND `salida`.`id_salida` = `record_cliente`.`id_salida`
AND `record_cliente`.`estado` <> 8) AS 'en2'
, (SELECT `record_cliente`.`estado` 
FROM `record_cliente` 
WHERE `configuracion_bus`.`piso` = `record_cliente`.`piso` 
AND `configuracion_bus`.`n3` = `record_cliente`.`asiento`
AND `salida`.`id_salida` = `record_cliente`.`id_salida`
AND `record_cliente`.`estado` <> 8) AS 'en3'
, (SELECT `record_cliente`.`estado` 
FROM `record_cliente` 
WHERE `configuracion_bus`.`piso` = `record_cliente`.`piso` 
AND `configuracion_bus`.`n4` = `record_cliente`.`asiento`
AND `salida`.`id_salida` = `record_cliente`.`id_salida`
AND `record_cliente`.`estado` <> 8) AS 'en4'
, (SELECT `record_cliente`.`estado` 
FROM `record_cliente` 
WHERE `configuracion_bus`.`piso` = `record_cliente`.`piso` 
AND `configuracion_bus`.`n5` = `record_cliente`.`asiento`
AND `salida`.`id_salida` = `record_cliente`.`id_salida`
AND `record_cliente`.`estado` <> 8) AS 'en5'
FROM `salida`
INNER JOIN `bus`
ON `salida`.`id_bus` = `bus`.`id_bus`
INNER JOIN `configuracion_bus`
ON `salida`.`id_bus` = `configuracion_bus`.`id_bus`
WHERE `salida`.`id_salida` = '".$_idSalida."'
ORDER BY `salida`.`id_salida` DESC, `flota` DESC, `piso` ASC, `fila` ASC;";

require_once('config_trans.php');
$db_transporte->query($query);
$_arrayBus = $db_transporte->get();
$response = "[";
$objeto = '';
if(count($_arrayBus)> 0)
{
	for($x = 0; $x < count($_arrayBus); $x++)
	{
		$objeto = '{
			"fila" : "'.$_arrayBus[$x]['fila'].'", 
			"piso" : "'.$_arrayBus[$x]['piso'].'", 
			"n1" : "'.$_arrayBus[$x]['n1'].'", 
			"n2" : "'.$_arrayBus[$x]['n2'].'", 
			"n3" : "'.$_arrayBus[$x]['n3'].'", 
			"n4" : "'.$_arrayBus[$x]['n4'].'", 
			"n5" : "'.$_arrayBus[$x]['n5'].'",
			"en1" : "'.$_arrayBus[$x]['en1'].'", 
			"en2" : "'.$_arrayBus[$x]['en2'].'", 
			"en3" : "'.$_arrayBus[$x]['en3'].'", 
			"en4" : "'.$_arrayBus[$x]['en4'].'", 
			"en5" : "'.$_arrayBus[$x]['en5'].'"
		}';
		if($x < count($_arrayBus) - 1)
			$response .= $objeto. ", ";
		else
			$response .= $objeto;
	}
	$response .= ']';
	echo utf8_encode($response);
}

?>