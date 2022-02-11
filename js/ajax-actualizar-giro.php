<?php
/*				

echo '<td style="text-align:center; color:#FF0000;">-</td>';
echo '<td style="text-align:center; color:#FF0000;">-</td>';
echo '<td style="text-align:center; color:#FF0000;">-</td>';
echo '<td style="text-align:left; color:#FF0000;">-</td>';
echo '<td style="text-align:right; color:#FF0000;">-</td>';
echo '<td style="text-align:center; color:#FF0000;">---</td>';
echo '<td style="text-align:center; color:#FF0000;">-</td>';
*/

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
$num_vale = $_GET['value'];
// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
include_once('../function/validacion.php');
// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();


// Verificamos que el giro no este pagado
$db_giro->query("SELECT COUNT(`g_movimiento`.`id_movimiento`) AS `EXISTE`
                                FROM `g_movimiento`
                                WHERE `g_movimiento`.`id_movimiento` = " .$id ."
                                AND `g_movimiento`.`esta_cancelado` = 0
                                AND `g_movimiento`.`esta_anulado` = 0;");
$existe_entrega = $db_giro->get("EXISTE");
       
        if ($existe_entrega == 1)
	{
		// ACTUALIZAMOS EL REGISTRO DE LA TABLA MOVIMIENTO
		$db_giro->query("UPDATE `g_movimiento` SET `esta_cancelado`=1 WHERE `id_movimiento`='".$id."';");
		if($db_giro)
		{
			// INSERTAMOS DATOS EN LA TABLA ENTREGA
			$db_giro->query("INSERT INTO `g_entrega`
							(`id_movimiento`,
							`ent_id_usuario`,
							`ent_id_oficina`,
							`ent_num_vale`,
							`ent_observ`,
							`ent_fecha_entrega`,
							`ent_hora_entrega`,
							`id_foto`,
							`nom_pc_ip`)
							VALUES
							(".$id.",
							".$_SESSION['ID_USUARIO'].",
							".$_SESSION['ID_OFICINA'].",
							".$num_vale.",
							'Actualización de giro por: ".$_SESSION['USUARIO']."',
							CURDATE(),
							CURTIME(),
							NULL,
							'".$pc_nom_ip."');");
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
							, `g_entrega`.`ent_num_vale`
							, DATE_FORMAT(`g_entrega`.`ent_fecha_entrega`,'%d-%m-%Y') as `ent_fecha_entrega`
							, `g_entrega`.`ent_id_usuario`
							, `g_entrega`.`ent_id_oficina`
							, IF(`g_entrega`.`ent_verificada` = 1, 'SI', 'NO') AS `ent_verificada`
							FROM `g_movimiento`
							LEFT JOIN `g_entrega`
							ON `g_movimiento`.`id_movimiento` = `g_entrega`.`id_movimiento`
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
				$vale = $G_CanceladoSol_Array[0][9];
				$fecha_entrega = $G_CanceladoSol_Array[0][10];
				$id_usuario_receptor = $G_CanceladoSol_Array[0][11];
				if (strlen($G_CanceladoSol_Array[0][11]) > 0)
				{
					$usuario_name_receptor = UserNombreByID($id_usuario_receptor);
				}
				else
				{
					$usuario_name = '---';
				}
				$id_oficina_entrega = $G_CanceladoSol_Array[0][12];
				$oficina_entrega = OficinaByID($G_CanceladoSol_Array[0][12]);
				$val_verificada = $G_CanceladoSol_Array[0][13];
				
				
				if ($anulado == 'NO')
				{
					if ($bol_verificada == 'SI')
					{
						echo '<td style="text-align:center">'.$cont.'</td>';
						echo '<td>'.$fecha.'</td>';
						echo '<td style="text-align:left">'.$consignatario.'</td>';
						echo '<td><a title="Giro emitido por: '.$usuario_name_emisor.'">'.$boleta.'</a></td>';
						echo '<td style="text-align:right">'.$monto.'</td>';
						if (strlen($vale) > 0)
						{
							echo '<td id="td_vale_'.$id.'"><a title="Vale pagado por: '.$usuario_name_receptor.'&#10;En: '.$oficina_entrega.'" class="vale" onClick="Edit_Vale_Giro(' .$vale .', event, '.$id.');" onkeyup = "extractNumber(this,0,false);" >' .$vale .'</a></td>';
						}
						else
						{
							echo '<td id="td_vale_'.$id.'"><input name="txt_vale_"'.$id.' style="width:60px;" type="text" onKeyPress="Update_Giro(this, event, '.$id.', '.$cont.')" onkeyup = "extractNumber(this,0,false);" /></td>';
						}
						if($val_verificada == 'NO')
						{
							if (strlen($vale) == 0)
							{
								echo '<td style="text-align:center; color:#FF0000;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);" disabled="disabled" /></td>';
							}
							else
							{
								echo '<td style="text-align:center;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);this.focus();" /></td>';
							}
						}
						else
						{
							echo '<td style="text-align:center;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);this.focus();" checked /></td>';
						}
					}
					else
					{
						echo '<td style="text-align:center;color:#0033FF;">'.$cont.'</td>';
						echo '<td style="text-align:left;color:#0033FF;">'.$fecha.'</td>';
						echo '<td  style="text-align:left;color:#0033FF;">'.$consignatario.'</td>';
						echo '<td style="text-align:left;color:#0033FF;"><a title="Giro emitido por: '.$usuario_name_emisor.'" class="no_verified" >'.$boleta.'</a></td>';
						echo '<td  style="text-align:right;color:#0033FF;">'.$monto.'</td>';
						if (strlen($vale) > 0)
						{
							echo '<td id="td_vale_'.$id.'"><a title="Vale pagado por: '.$usuario_name_receptor.'&#10;En: '.$oficina_entrega.'" class="vale" onClick="Edit_Vale_Giro(' .$vale .', event, '.$id.');" onkeyup = "extractNumber(this,0,false);" >' .$vale .'</a></td>';
                                                        
                                                        
                                                }
						else
						{
							echo '<td id="td_vale_'.$id.'"><input name="txt_vale_"'.$id.' style="width:60px;" type="text" onKeyPress="Update_Giro(this, event, '.$id.', '.$cont.')" onkeyup = "extractNumber(this,0,false);" /></td>';
                                                                                                            
                                                }
						echo '<td style="text-align:center; color:#FF0000;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);" disabled="disabled" /></td>';
					}
				}
				else
				{
					echo '<td style="text-align:center; color:#FF0000;">'.$cont.'</td>';
					echo '<td style="text-align:center; color:#FF0000;">'.$fecha.'</td>';
					echo '<td style="text-align:center; color:#FF0000;"><a title="Giro emitido por: '.$usuario_name_emisor.'" class="anulado" href="#">'.$boleta.'</a>' .'</td>';
					echo '<td style="text-align:left; color:#FF0000;">'.$consignatario.'</td>';
					echo '<td style="text-align:right; color:#FF0000;">'.$monto.'</td>';
					echo '<td style="text-align:center; color:#FF0000;">---</td>';
					echo '<td style="text-align:center; color:#FF0000;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);" disabled="disabled" /></td>';
				}
				
				
			}
		}
	}
	
	
	
?>