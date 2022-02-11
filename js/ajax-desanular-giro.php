<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged_niv2.php");
	require_once('../config_giro.php');
	function UserNombreByID($id_user)
	{
		$Users_Array = $_SESSION['USERS'];
		$UserName = '';
		for ($fila = 0; $fila < count($Users_Array); $fila++)
		{
			if($Users_Array[$fila][0] == $id_user)
			{
				$UserName = utf8_encode($Users_Array[$fila][2]);
				break;
			}
		}
		return $UserName;
	}
	function OficinaByID($id_ofic)
	{
		$Ofic_Array = $_SESSION['OFICINAS'];
		$Oficina = '';
		for ($fila = 0; $fila < count($_SESSION['OFICINAS']); $fila++)
		{
			if($_SESSION['OFICINAS'][$fila][0] == $id_ofic)
			{
				$Oficina = $_SESSION['OFICINAS'][$fila][1];
				break;
			}
		}
		return $Oficina;
	}
	// definir la zona horaria predeterminada a usar. Disponible desde PHP 5.1
	date_default_timezone_set('America/Lima');
	$id = $_GET['ID'];
	$monto = $_GET['MONTO'];
	$flete = $_GET['FLETE'];
	$cont = $_GET['cont'];
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('../function/validacion.php');
	// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
	$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();
	// Verificamos que el giro no este pagado
	$db_giro->query("SELECT COUNT(`g_movimiento`.`id_movimiento`) AS `EXISTE`
					FROM `g_movimiento`
					WHERE `g_movimiento`.`id_movimiento` = " .$id ."
					AND `g_movimiento`.`esta_cancelado` = 0
					AND `g_movimiento`.`esta_anulado` = 1;");
	$existe_entrega = $db_giro->get("EXISTE");
	if ($existe_entrega == 1)
	{
		// ACTUALIZAMOS EL REGISTRO DE LA TABLA MOVIMIENTO
		$db_giro->query("UPDATE `g_movimiento` 
						SET `esta_anulado`= 0
						, `de_administracion` = 1
						, `autorizado` = 0
						, `monto_giro` = ".$monto."
						, `flete_giro` = ".$flete."
						WHERE `id_movimiento`= '".$id."';");
		if($db_giro)
		{
			// ELIMINAR DE LA TABLA G_ANULADO EL REGISTRO DEL GIRO ANULADO
			$db_giro->query("DELETE FROM `g_anulado` 
							WHERE `id_movimiento`='".$id."';");
			if($db_giro)
			{
			
				// OBTENEMOS LOS DATOS DEL GIRO
				$db_giro->query("SELECT 
							`g_movimiento`.`id_movimiento`
							, DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y') as `fecha_emision`
							, CONCAT(RIGHT(CONCAT('000', CAST(`g_movimiento`.`num_serie` AS CHAR)), 4), '-', 
							RIGHT(CONCAT('00000000',CAST(`g_movimiento`.`num_documento` AS CHAR)),8)  ) AS 'NUM_BOLETO'
							, `g_persona`.`per_ape_nom`
							, `g_movimiento`.`id_usuario`
							, `g_movimiento`.`monto_giro`
							, `g_movimiento`.`flete_giro`
							, IF(`g_movimiento`.`esta_anulado` = 1, 'SI', 'NO') AS `esta_anulado`
							, IF(`g_movimiento`.`verificado` = 1, 'SI', 'NO') AS `verificado`
							FROM `g_movimiento`
							INNER JOIN `g_persona`
							ON `g_movimiento`.`id_consignatario` = `g_persona`.`id_persona`
							
							WHERE `g_movimiento`.`id_movimiento` = " .$id);
				$G_CanceladoSol_Array = $db_giro->get();
				
				$id = $G_CanceladoSol_Array[0][0];
				$fecha = $G_CanceladoSol_Array[0][1];
				$boleta = $G_CanceladoSol_Array[0][2];
				$consignatario = utf8_encode($G_CanceladoSol_Array[0][3]);
				$id_usuario_emisor = $G_CanceladoSol_Array[0][4];
				if (strlen($G_CanceladoSol_Array[0][4]) > 0)
				{
					$usuario_name_emisor = UserNombreByID($id_usuario_emisor);
				}
				else
				{
					$usuario_name = '---';
				}
				$monto = $G_CanceladoSol_Array[0][5];
				$flete = $G_CanceladoSol_Array[0][6];
				$anulado = $G_CanceladoSol_Array[0][7];
				$bol_verificada = $G_CanceladoSol_Array[0][8];
				
				if ($anulado == 'NO')
				{
					echo '<td style="text-align:center">'.$cont.'</td>';
					echo '<td>'.$fecha.'</td>';
					echo '<td style="text-align:left">'.$consignatario.'</td>';
					echo '<td><a title="Giro emitido por: '.$usuario_name_emisor.'">'.$boleta.'</a></td>';
					echo '<td style="text-align:right">'.$monto.'</td>';
					echo '<td title="'.$usuario_name.'" style="text-align:right">'.$flete.'</td>';
					if($bol_verificada == 'SI')
					{
						echo '<td style="text-align:center;"><input type="checkbox" name="cbox_copiado_'.$id.'" value="'.$id.'" checked="checked" title="Este Giro YA FUE VERFICADO." tabindex="'.($cont + 20).'" onClick="Update_Verified_Emit(event, this);" onclick="this.focus();" /></td>';
					}
					else
					{
						echo '<td style="text-align:center;"><input type="checkbox" name="cbox_copiado_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado." onclick="this.focus();" tabindex="'.($cont + 20).'" onClick="Update_Verified_Emit(event, this);" /></td>';
					}
				}
				else
				{
					echo '<td style="text-align:center; color:#FF0000;">'.$cont.'</td>';
					echo '<td style="text-align:left; color:#FF0000;">'.$fecha.'</td>';
					echo '<td style="text-align:left; color:#FF0000;">'.$consignatario.'</td>';
					echo '<td style="text-align:left; color:#FF0000;"><a title="Giro emitido por: '.$usuario_name_emisor.'" class="anulado" href="#">'.$boleta.'</a>' .'</td>';
					echo '<td colspan="2" id="Div_td_actgiro_'.$id.'" style="text-align:center; color:#FF0000;"><a title="Clic para actviar el Giro" onclick="Edit_Giro_Anulado(this, event, '.$id.')" class="anulado">Giro Anulado</a></td>';
					echo '<td style="text-align:center; color:#FF0000;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado." tabindex="'.($cont + 20).'" onClick="Update_Verified_Emit(event, this);" /></td>';
				}
			
				// REGISTRAMOS LA OPERACION EN LA TABLA MOVIMIENTO
				$db_giro->query("SELECT COUNT(`g_operacion`.`id_movimiento`) AS `id_movimiento`
								FROM `g_operacion`
								WHERE `g_operacion`.`id_movimiento` = ".$id."
								AND `g_operacion`.`ope_tipo_operacion` = 10;");
				$existe_ope = $db_giro->get("id_movimiento");
				if ($existe_ope == 0)
				{
					// INGRESAMOS EL REGISTRO
					$db_giro->query("INSERT INTO `g_operacion`
									(`id_movimiento`, `ope_tipo_operacion`, `id_oficina`, `id_usuario`, `ope_fecha`, `ope_hora`, ope_detalle, `nom_pc_ip`)
									VALUES
									(".$id.", 10,".$_SESSION['ID_OFICINA'].", ".$_SESSION['ID_USUARIO'].", CURDATE(), CURTIME(), 'Desarutorización de GIRO','".$pc_nom_ip."');");
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
					`ope_detalle` = 'Desarutorización de GIRO',
					`nom_pc_ip` = '".$pc_nom_ip."'
					WHERE `id_movimiento` = ".$id."
					AND `ope_tipo_operacion` = 10;");
				}
			}
		}
	}
?>