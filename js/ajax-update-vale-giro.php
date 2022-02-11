<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged_niv2.php");
	require_once('../config_giro.php');
	
	// definir la zona horaria predeterminada a usar. Disponible desde PHP 5.1
	date_default_timezone_set('America/Lima');
	$id = $_GET['ID'];
	$num_vale = $_GET['value'];
	if (strlen($num_vale) == 0)
	{
		$num_vale = 0;
	}
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('../function/validacion.php');
	// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
	$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();
	// Verificamos que el giro no este pagado
	$db_giro->query("SELECT COUNT(`g_entrega`.`id_movimiento`) AS `EXISTE`
					FROM `g_entrega`
					WHERE `g_entrega`.`id_movimiento` = " .$id);
	$existe_entrega = $db_giro->get("EXISTE");
	if ($existe_entrega == 1)
	{
		// ACTUALIZAMOS EL REGISTRO DE LA TABLA MOVIMIENTO
		$db_giro->query("UPDATE `g_entrega` SET `ent_num_vale`= ".$num_vale." WHERE `id_movimiento`='".$id."';");
		if($db_giro)
		{
			if($db_giro)
			{
				// REGISTRAMOS LA OPERACION EN LA TABLA MOVIMIENTO
                                $db_giro->query("SELECT COUNT(`g_operacion`.`id_movimiento`) AS `id_movimiento`
								FROM `g_operacion`
								WHERE `g_operacion`.`id_movimiento` = ".$id."
								AND `g_operacion`.`ope_tipo_operacion` = 8;");
				$existe_ope = $db_giro->get("id_movimiento");
                                
				if ($existe_ope == 0)
				{
					// INGRESAMOS EL REGISTRO
					$db_giro->query("INSERT INTO `g_operacion`
									(`id_movimiento`, `ope_tipo_operacion`, `id_oficina`, `id_usuario`, `ope_fecha`, `ope_hora`, ope_detalle, `nom_pc_ip`)
									VALUES
									(".$id.", 8,".$_SESSION['ID_OFICINA'].", ".$_SESSION['ID_USUARIO'].", CURDATE(), CURTIME(), 'Actualizaci�n de GIRO PAGADO','".$pc_nom_ip."');");
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
					`ope_detalle` = 'Actualización de GIRO PAGADO',
					`nom_pc_ip` = '".$pc_nom_ip."'
					WHERE `id_movimiento` = ".$id."
					AND `ope_tipo_operacion` = 8;");
				}
			}
		}
	}
	
	echo '<a class="vale" onClick="Edit_Vale_Giro(' .$num_vale .', event, '.$id.');">' .$num_vale .'</a>';
?>