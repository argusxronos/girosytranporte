<?php
	/*****************************************************
	AUTOR: JONATAN RIVERA C.
	FECHA: 13/01/2012
	DESCRIPCION:
	METODO PARA CAMBIAR LOS ESTADO DE LAS ENCOMIENDAS A 3
	SI ESTA EN ESTADO 2
	*******************************************************/
	// VERIFICAMOS SESION
	session_start();
	require_once("../is_logged.php");

	// OBTENEMOS LAS VARIABLES
	$ID_MOVIMIENTO = 0;
	$ITEM_NUM = 0;
	$TOPER = true;
	if(isset($_GET['IDMOV']) && $_GET['IDMOV'] > 0)
	{
		$ID_MOVIMIENTO = $_GET['IDMOV'];
	}
	if(isset($_GET['INUM']) && $_GET['INUM'] > 0)
	{
		$ITEM_NUM = $_GET['INUM'];
	}
	$TOPER = $_GET['TOPER'];
	// ESTABLECEMOS LA CONEXION CON EL SERVIDOR
	require_once('../config_giro.php');
	// CONSULTA PARA REALIZAR LA MODIFICACION
	if ($TOPER == 'true')
	{
		$sql = "UPDATE `e_mov_detalle`
			SET
			`md_estado` = 3
			WHERE `id_movimiento` = ".$ID_MOVIMIENTO."
			AND `e_num_item` = ".$ITEM_NUM."
			AND `md_estado` = 2";
	}
	else
	{
		$sql = "UPDATE `e_mov_detalle`
			SET
			`md_estado` = 2
			WHERE `id_movimiento` = ".$ID_MOVIMIENTO."
			AND `e_num_item` = ".$ITEM_NUM."
			AND `md_estado` = 3;";
	}
	$db_giro->query($sql);
	if ($db_giro)
	{
		// REGISTRAMOS LA OPERACION
		$tipo_operacion = 2;
		$descripcon = 'Cancelacion de Recepción de Encomienda';
		if ($TOPER == 'true')
		{
			$tipo_operacion = 1;
			$descripcon = 'Recepción de Encomienda';
		}
		$sql = "SELECT COUNT(`e_md_operacion`.`id_movimiento`)
				AS 'EXISTE'
				FROM `e_md_operacion`
				WHERE `e_md_operacion`.`tipo_operacion` = ".$tipo_operacion."
				AND `e_md_operacion`.`id_movimiento` = ".$ID_MOVIMIENTO."
				AND `e_md_operacion`.`e_num_item` = ".$ITEM_NUM.";";
		$db_giro->query($sql);
		$existe = $db_giro->get('EXISTE');
		if ($existe == 0)
		{
			// registramos el movimiento
			$sql = "INSERT INTO `e_md_operacion`
					(`tipo_operacion`,
					`id_movimiento`,
					`e_num_item`,
					`id_usuario`,
					`id_oficina`,
					`mdo_fecha`,
					`mdo_hora`,
					`mdo_detalle`)
					VALUES
					(
					".$tipo_operacion.",
					".$ID_MOVIMIENTO.",
					".$ITEM_NUM.",
					".$_SESSION['ID_USUARIO'].",
					".$_SESSION['ID_OFICINA'].",
					DATE(NOW()),
					TIME(NOW()),
					'".$descripcon."'
					);";
			$db_giro->query($sql);
		}
		else
		{
			// registramos el movimiento
			$sql = "UPDATE `e_md_operacion`
					SET
					`id_usuario` = ".$_SESSION['ID_USUARIO'].",
					`id_oficina` = ".$_SESSION['ID_OFICINA'].",
					`mdo_fecha` = DATE(NOW()),
					`mdo_hora` = TIME(NOW())
					WHERE `id_movimiento` = ".$ID_MOVIMIENTO."
					AND `e_num_item` = ".$ITEM_NUM."
					AND `tipo_operacion` = ".$tipo_operacion.";";
			$db_giro->query($sql);
		}
		$sql = '';
		if ($tipo_operacion == 2)
		{
			$sql = "DELETE FROM `e_md_operacion`
			WHERE `id_movimiento` = ".$ID_MOVIMIENTO."
			AND `e_num_item` = ".$ITEM_NUM."
			AND `tipo_operacion` = 1;";
		}
		else
		{
			$sql = "DELETE FROM `e_md_operacion`
			WHERE `id_movimiento` = ".$ID_MOVIMIENTO."
			AND `e_num_item` = ".$ITEM_NUM."
			AND `tipo_operacion` = 2;";
		}
		$db_giro->query($sql);
	}
	// RETORNAMOS UNA RESPUESTA
	$sql = "SELECT `e_mov_detalle`.`md_estado`
			AS 'ESTADO'
			FROM `e_mov_detalle`
			WHERE `e_mov_detalle`.`id_movimiento` = ".$ID_MOVIMIENTO."
			AND `e_mov_detalle`.`e_num_item` = ".$ITEM_NUM.";";
	$db_giro->query($sql);
	$estado = $db_giro->get('ESTADO');
	if ($estado == 2)
	{
		echo '<input name="cbox_recepcionar" type="checkbox" value="" onClick="E_Recep_Enc('.$ID_MOVIMIENTO.', '.$ITEM_NUM.',true);this.focus();">';
	}
	elseif($estado == 3)
	{
		echo '<input name="cbox_recepcionar" type="checkbox" value="" onClick="E_Recep_Enc('.$ID_MOVIMIENTO.', '.$ITEM_NUM.',false);this.focus();" checked>';
	}
?>