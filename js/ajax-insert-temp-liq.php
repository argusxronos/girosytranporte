<?php
	session_start();
	require_once("../is_logged.php");
	require_once('../config_giro.php');
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("../function/validacion.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
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
	$Error = false;
	$MsjError = '';
	// OBTENEMOS EL ID OFICINA Y EL ID USUARIO
	$ID_OFIC = $_SESSION['ID_OFICINA'];
	$ID_USUARIO = $_SESSION['ID_USUARIO'];
	$List_Oficinas = '';
	$codigo = '';
	if(isset($_GET['DESTINOS']) && strlen($_GET['DESTINOS']) > 0)
	{
		$List_Oficinas = $_GET['DESTINOS'];
	}
	else
	{
		MsjErrores('Debe ingresar los destinos para crear la liquidaci&oacute;n.');
	}
	if(isset($_GET['CODIGO']) && strlen($_GET['CODIGO']) > 0)
	{
		$codigo = $_GET['CODIGO'];
	}
	else
	{
		MsjErrores('Error al crear la liquidacion, Presione F5 y vuleva a intentalo.');
	}
	$date = $_GET['FECHA'];
	$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
	$fecha_giro = new DateTime($date);
	$tipo_liq = $_GET['TLIQ'];
	$num_oficinas = $_GET['NUMOF'];
	// INGRESAMOS A LA TABLA MOVIMIENTO
	$db_giro->query("CALL `USP_E_INSERT_TEMP_DLIQ`
					(
					@vError
					, @vMSJ_ERROR
					, '".$codigo."'
					, ".$ID_USUARIO."
					, ".$ID_OFIC."
					, '".$fecha_giro->format("Y-m-d")."'
					, '".$List_Oficinas."'
					, '".$tipo_liq."'
					, '".$num_oficinas."'
					)");
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
	if ($Error == false)
	{
		// REALIZAMOS LA CONSULTA
		$db_giro->query("SELECT `e_mov_detalle`.`id_movimiento`
						, `e_mov_detalle`.`e_num_item`
						, `e_movimiento`.`id_oficina_destino`
						, CONCAT(RIGHT(CONCAT('00000', CAST(`e_movimiento`.`num_serie` AS CHAR)),4)
						, '-'
						, RIGHT(CONCAT('0000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS `NUM_GUIA`
						, IF(`CONSIG`.`per_tipo` = 'PERSONA', `CONSIG`.`per_nombre`, `CONSIG`.`per_razon_social`) AS `CONSIGNATARIO`
						, CAST(CONCAT(`e_mov_detalle`.`md_cantidad`
						, ' - '
						, `e_mov_detalle`.`md_descripcion`) AS CHAR) AS `CONTENIDO`
						, `e_mov_detalle`.`md_carrera`
						, `e_mov_detalle`.`md_flete`
						FROM `temp_liq_detalle`
						INNER JOIN `e_mov_detalle`
						ON `temp_liq_detalle`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
						AND `temp_liq_detalle`.`e_num_item` = `e_mov_detalle`.`e_num_item`
						INNER JOIN `e_movimiento`
						ON `e_mov_detalle`.`id_movimiento` = `e_movimiento`.`id_movimiento`
						INNER JOIN `e_persona` AS `CONSIG`
						ON `CONSIG`.`id_persona` = `e_movimiento`.`id_consignatario` 
						WHERE `temp_liq_detalle`.`id_codigo` = '".$codigo."'
						AND `temp_liq_detalle`.`id_usuario` = ".$ID_USUARIO."
						AND `temp_liq_detalle`.`id_oficina` = ".$ID_OFIC."
						AND `temp_liq_detalle`.`tld_fecha` = '".$fecha_giro->format("Y-m-d")."'
						GROUP BY `e_mov_detalle`.`id_movimiento`
						, `e_mov_detalle`.`e_num_item`
						, `e_movimiento`.`id_oficina_destino`
						, `e_movimiento`.`num_serie`
						, `e_movimiento`.`num_documento`
						ORDER BY `e_movimiento`.`id_oficina_destino`
						, `e_movimiento`.`num_serie` ASC
						, `e_movimiento`.`num_documento` ASC
						, `e_mov_detalle`.`id_movimiento`
						, `e_mov_detalle`.`e_num_item`;");
		$Array_liquidacion_list = $db_giro->get();
		if (count($Array_liquidacion_list) > 0)
		{
		/* SI NO HAY ERRORES EN LA TRANSACCION MOSTRAMOS LA LISTA */
			echo '<table border="0">';
			if (strlen($MsjError) > 0)
			{
				echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
					echo '<td colspan="7" style="text-align:center; height:50px;"><span style="font-size:18px;">'.$MsjError.'</span></td>';
				echo '</tr>';
			}
				echo '<tr>';
					echo '<th style="width:10px; text-align:center;" title="N&uacute;mero de Lineas">#</th>';
					echo '<th style="width:50px; text-align:center;"># GUIAS</th>';
					echo '<th style="width:325px;">CONSIGNATARIO</th>';
					echo '<th style="width:300px; text-align:center;">CONTENIDO DE LA GUIA</th>';
					echo '<th style="width:40px; text-align:center;">CARRERA</th>';
					echo '<th style="width:40px; text-align:center;">VALOR</th>';
					echo '<th style="width:40px; text-align:center;">ACCI&Oacute;N</th>'; 	  
				echo '</tr>';
			$Oficina_Actual = 0;
			$guia_actual = '';
			$cont = 0;
          	for ($fila = 0; $fila < count($Array_liquidacion_list); $fila ++)
			{
				$cont++;
				$id_movimiento = $Array_liquidacion_list[$fila][0];
				$num_item = $Array_liquidacion_list[$fila][1];
				$oficina = $Array_liquidacion_list[$fila][2];
				$guia = $Array_liquidacion_list[$fila][3];
				$consignatario = utf8_encode($Array_liquidacion_list[$fila][4]);
				$descripcion = utf8_encode($Array_liquidacion_list[$fila][5]);
				$carrera = $Array_liquidacion_list[$fila][6];
				$importe = $Array_liquidacion_list[$fila][7];
				if ($Oficina_Actual != $oficina)
				{
					echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
						echo '<td style="text-align:center;">'.$cont.'</td>';
						$cont++;
						echo '<td colspan="6" style="text-align:center;"><span>'.OficinaByID($oficina).'</span></td>';
					echo '</tr>';
					$Oficina_Actual = $oficina;
				}
				
				echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" id="div_tr_'.$id_movimiento.$num_item.'">';
					echo '<td style="text-align:center;">'.$cont.'</td>';
					if ($guia_actual != $guia)
					{
						echo '<td style="text-align:center;">'.$guia.'</td>';
						echo '<td>'.$consignatario.'</td>';
						$guia_actual = $guia;
					}
					else
					{
						echo '<td style="text-align:center;">&nbsp;</td>';
						echo '<td>&nbsp;</td>';
					}
					echo '<td>'.$descripcion.'</td>';
					echo '<td style="text-align:right;">'.$carrera.'</td>';
					echo '<td style="text-align:right;">'.$importe.'</td>';
					echo '<td style="text-align:center;"><a style="cursor: hand;" onclick="Delete_temp_DLiq('.$id_movimiento.', '.$num_item.')"><img src="./img/operacion/Symbol-Delete.png" width="24" height="24" style="margin-left:16px;" title="Eliminar esta encomienda de la lista." style="text-align:center;" /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		else
		{
			/* MOSTRAMO EL MENSAJE DE ERROR EN UNA TABLA */
			echo '<table border="0">';
			  echo '<tr>';
				echo '<th style="width:50px; text-align:center;"># GUIAS</th>';
				echo '<th style="width:295px;">CONSIGNATARIO</th>';
				echo '<th style="width:220px; text-align:center;">CONTENIDO DE LA GUIA</th>';
				echo '<th style="width:80px; text-align:center;">CARRERA</th>';
				echo '<th style="width:80px; text-align:center;">VALOR</th>';
				echo '<th style="width:90px; text-align:center;">ACCI&Oacute;N</th>'; 	  
			  echo '</tr>';
			  echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
					echo '<td colspan="6" style="text-align:center;"><span>No hay encomiendas Pendientes</span></td>';
			  echo '</tr>';
			echo '</table>';
		}
	}
	else
	{
		/* MOSTRAMO EL MENSAJE DE ERROR EN UNA TABLA */
		echo '<table border="0">';
          echo '<tr>';
            echo '<th style="width:50px; text-align:center;"># GUIAS</th>';
            echo '<th style="width:295px;">CONSIGNATARIO</th>';
            echo '<th style="width:220px; text-align:center;">CONTENIDO DE LA GUIA</th>';
            echo '<th style="width:80px; text-align:center;">CARRERA</th>';
            echo '<th style="width:80px; text-align:center;">VALOR</th>';
            echo '<th style="width:90px; text-align:center;">ACCI&Oacute;N</th>'; 	  
          echo '</tr>';
          echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
            echo '<td colspan="6" style="text-align:center;"><span>'.$MsjError.'</span></td>';
          echo '</tr>';
        echo '</table>';
	}
?>