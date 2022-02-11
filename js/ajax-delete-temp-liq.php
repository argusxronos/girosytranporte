<?php
	session_start();
	require_once("../is_logged.php");
	require_once('../config_giro.php');
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("../function/validacion.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// OBTENEMOS EL ID OFICINA Y EL ID USUARIO
	$ID_OFIC = $_SESSION['ID_OFICINA'];
	$ID_USUARIO = $_SESSION['ID_USUARIO'];
	$ID_MOVIMIENTO = 0;
	$ITEM = 0;
	$CODIGO = '';
	if(isset($_GET['CODIGO']) && strlen($_GET['CODIGO']) > 0)
	{
		$CODIGO = $_GET['CODIGO'];
	}
	else
	{
		MsjErrores('Error al crear la liquidacion, Presione F5 y vuleva a intentalo.');
	}

	if(isset($_GET['ID']) && strlen($_GET['ID']) > 0)
	{
		$ID_MOVIMIENTO = $_GET['ID'];
	}
	else
	{
		MsjErrores('No se puede eliminar esta encomienda, consulte con el administrador.');
	}
	if(isset($_GET['ITEM']) && strlen($_GET['ITEM']) > 0)
	{
		$ITEM = $_GET['ITEM'];
	}
	else
	{
		MsjErrores('No se puede eliminar esta encomienda, consulte con el administrador.');
	}
	$date = $_GET['FECHA'];
	$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
	$fecha_giro = new DateTime($date);
	// INGRESAMOS A LA TABLA MOVIMIENTO
	$db_giro->query("DELETE FROM `bd_giro`.`temp_liq_detalle`
					WHERE `temp_liq_detalle`.`id_codigo` = '".$CODIGO."'
					AND `temp_liq_detalle`.`id_movimiento` = ".$ID_MOVIMIENTO."
					AND `temp_liq_detalle`.`e_num_item` = ".$ITEM."
					AND `temp_liq_detalle`.`id_usuario` = ".$ID_USUARIO."
					AND `temp_liq_detalle`.`id_oficina` = ".$ID_OFIC."
					AND `temp_liq_detalle`.`tld_fecha` = '".$fecha_giro->format("Y-m-d")."'");
?>