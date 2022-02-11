<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged.php");
	$id_movimiento = $_GET['IDMOVIMIENTO'];
	$num_item = $_GET['ITEM'];
	$id_liquidacion = $_GET['IDLIQUIDACION'];
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("../function/validacion.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// OBTENEMOS EL ID OFICINA Y EL ID USUARIO
	$ID_OFIC = $_SESSION['ID_OFICINA'];
	$ID_USUARIO = $_SESSION['ID_USUARIO'];
	// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
	$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();
	// LLAMAMOS AL PROCEDIMIENTO ALMACENADO PARA REGISTRAR LA ELIMINACION
	require_once('../config_giro.php');
	$db_giro->query("CALL `USP_E_DEL_ITEM_LIQ`
					(
						@vERROR
						, @vMSJ_ERROR
						, $id_movimiento
						, $num_item
						, $id_liquidacion
						, $ID_USUARIO
						, $ID_OFIC
						, '$pc_nom_ip');");
	if (!$db_giro)
	{
		MsjErrores('Error en la transacción, Comuniquese con el Administrador.');
	}
	else
	{
		$db_giro->query("SELECT @vERROR AS `ERROR`, @vMSJ_ERROR AS `MSJ_ERROR`;");
		$Error_Array = $db_giro->get();
		$Error = $Error_Array[0][0];
		$MsjError = str_replace("\n", "<br>", $Error_Array[0][1]);
	}
	if ($Error = true)
	{
		echo '<td colspan="6"><span>$MsjError<br />Presione F5 y vuelva a intentarlo o comuniquese con el Adminsitrador.</span></td>';
	}
?>