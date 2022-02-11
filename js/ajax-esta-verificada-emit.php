<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged_niv2.php");
	require_once('../config_giro.php');
	
	// definir la zona horaria predeterminada a usar. Disponible desde PHP 5.1
	date_default_timezone_set('America/Lima');
	$id = $_GET['ID'];
	// Modificamos el estado copiado a 0
	$db_giro->query("UPDATE `g_movimiento` SET `verificado`= IF(`verificado` = 0, 1, 0) 
					WHERE `id_movimiento`='".$id."';");
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('../function/validacion.php');
	// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
	$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();
	// INGRESAMOS A LA TABLA MOVIMIENTO QUIEN ESTA REALIZANDO LA VERIFICACION
	$db_giro->query("SELECT COUNT(`g_operacion`.`id_movimiento`) AS `id_movimiento`
					FROM `g_operacion`
					WHERE `g_operacion`.`id_movimiento` = ".$id."
					AND `g_operacion`.`ope_tipo_operacion` = 6;");
	$existe_ope = $db_giro->get("id_movimiento");
	if ($existe_ope == 0)
	{
		// INGRESAMOS EL REGISTRO
		$db_giro->query("INSERT INTO `g_operacion`
						(`id_movimiento`, `ope_tipo_operacion`, `id_oficina`, `id_usuario`, `ope_fecha`, `ope_hora`, ope_detalle, `nom_pc_ip`)
						VALUES
						(".$id.", 6,".$_SESSION['ID_OFICINA'].", ".$_SESSION['ID_USUARIO'].", CURDATE(), CURTIME(),'Verificación de GIRO EMITIDO','".$pc_nom_ip."');");
	}
	else
	{
		// SOLO MOODIFICAMOS EL REGISTRO
		$db_giro->query("UPDATE `g_operacion`
		SET
		`id_oficina` = ".$_SESSION['ID_OFICINA'].",
		`id_usuario` = ".$_SESSION['ID_USUARIO'].",
		`ope_fecha` = CURDATE(),
		`ope_hora` = CURTIME(),
		`ope_detalle` = 'Verificación de GIRO EMITIDO',
		`nom_pc_ip` = '".$pc_nom_ip."'
		WHERE `id_movimiento` = ".$id."
		AND `ope_tipo_operacion` = 6;");
	}
?>