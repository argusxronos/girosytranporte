<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// VERIFICAMOS SI ESTA LOGEADO
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged.php");
	$where = '';
	if (isset($_GET['btn_buscar']) && $_GET['btn_buscar'] != "")
	{
		if (strlen($_GET['txt_fecha'])>0)
		{
			$sql = $sql ." AND `g_movimiento`.`fecha_emision` = '".$_GET['txt_fecha']."'";
		}
		if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0)
		{
			$sql = $sql ." AND `CONSIGNATARIO`.`per_ape_nom` LIKE '".utf8_decode(strtoupper(urldecode($_GET['txt_consignatario'])))."%'";
		}
		if (isset($_GET['txt_Remitente']) && strlen($_GET['txt_Remitente']) > 0)
		{
			$sql = $sql ." AND `REMITENTE`.`per_ape_nom` LIKE '".utf8_decode(strtoupper(urldecode($_GET['txt_Remitente'])))."%'";
		}
		if (isset($_GET['txt_num_liquidacion']) && strlen($_GET['txt_num_liquidacion']) > 0)
		{
			$where = $where ." AND `e_liquidacion`.`liq_num_doc` = '".$_GET['txt_num_liquidacion']."'";
		}
		if (isset($_GET['cmb_agencia_origen']) && $_GET['cmb_agencia_origen'] != 0)
		{
			$where = $where ." AND `e_movimiento`.`id_oficina_origen` = " .$_GET['cmb_agencia_origen'];
		}
	}
	// CREAMOS LA CONSULTA DE BUSQUEDA
	$sql = "SELECT 
	`e_liquidacion`.`id_liquidacion`
	, `e_liquidacion`.`liq_fecha`
	, `e_liquidacion`.`liq_hora`
	, `e_liquidacion`.`liq_num_doc`
	, `e_liquidacion`.`id_oficina_origen`
	, `e_liquidacion`.`id_usuario`
	, `liq_chofer`
	, `liq_pullman`
	, COUNT(`e_liquidacion_detalle`.`id_liquidacion`) AS 'TOTAL_ENC'
	FROM `e_liquidacion`
	INNER JOIN `e_liquidacion_detalle`
	ON `e_liquidacion`.`id_liquidacion` = `e_liquidacion_detalle`.`id_liquidacion`
	INNER JOIN `e_mov_detalle`
	ON `e_mov_detalle`.`id_movimiento` = `e_liquidacion_detalle`.`id_movimiento`
	AND `e_mov_detalle`.`e_num_item` = `e_liquidacion_detalle`.`e_num_item`
	INNER JOIN `e_movimiento`
	ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
	WHERE `e_movimiento`.`id_oficina_destino` = ".$_SESSION['ID_OFICINA']."
	AND `e_liquidacion`.`liq_estado` = 1
	AND `e_mov_detalle`.`md_estado` = 2
	$where
	GROUP BY `e_liquidacion`.`id_liquidacion`
	, `e_liquidacion`.`liq_fecha`
	, `e_liquidacion`.`liq_hora`
	, `e_liquidacion`.`liq_num_doc`
	, `e_liquidacion`.`id_oficina_origen`
	, `e_liquidacion`.`id_usuario`
	, `liq_chofer`
	, `liq_pullman`
	HAVING COUNT(`e_liquidacion_detalle`.`id_liquidacion`) > 0
	ORDER BY `e_liquidacion`.`id_oficina_origen`
	, `e_liquidacion`.`id_liquidacion` DESC
	LIMIT 15;";
	// OBTEMOS LOS DATOS DE MOVIMIENTOS
	require_once 'cnn/config_master.php';
	// REALIZAMOS LA CONSULTA A LA BD
	$db_giro->query($sql);
	$Giros_Array = $db_giro->get();
	
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
	
	function UserByID($id_user)
	{
		$Users_Array = $_SESSION['USERS'];
		$Usuario = '';
		for ($fila = 0; $fila < count($Users_Array); $fila++)
		{
			if($Users_Array[$fila][0] == $id_user)
			{
				$Usuario = $Users_Array[$fila][1];
				break;
			}
		}
		
		return $Usuario;
	}
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
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	
	  <?php 
	if (!isset($_GET['ID']))
	{
?>
	  <!-- Content unit - One column -->

    <div class="column1-unit">

	  <h1>Recepci&oacute;n de Encomiendas - Zona de Busqueda</h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
		<form method="get" action="e_recepcion.php" name="buscar_liquidacion" >
          <table width="100%" border="0">
                    <tr>
                        <th style="text-align:right; width:100px;">Ag. Origen</th>
                        <th><select name="cmb_agencia_origen" id="cmb_agencia_origen" class="combo" title="Agenia donde se Pag&oacute; del giro." tabindex="1" style="width:220px;" onkeypress="return handleEnter(this,event);" >
                          <?php
                            if (count($Oficina_Array) == 0)
                            {
                                echo '<option value="0">[ NO HAY OFICINAS...! ]</option>';
                            }
                            else
                            {
                                echo '<option value="" selected="selected">[ Seleccione Oficina ]</option>';
                                for ($fila = 0; $fila < count($Oficina_Array); $fila++)
                                {
									if ($Oficina_Array[$fila][0] == $_SESSION['ID_OFICINA'])
									{
										echo '<option value="'.$Oficina_Array[$fila][0].'" disabled="disabled" > '.$Oficina_Array[$fila][1].' </option>';
									}
									else
									{
										if (isset($_GET['cmb_agencia_origen']) && $_GET['cmb_agencia_origen'] == $Oficina_Array[$fila][0])
										{
											echo '<option selected="selected" value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
										}
										else
											echo '<option value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
									}
                                }
								echo '<option value="0" >TODOS</option>';
                            }
                          ?>
                        </select></th>
                        <th style="text-align:right; width:150px;">Nro. Liquidaci&oacute;n :</th>
                      	<td><input type="text" name="txt_num_liquidacion" id="txt_num_liquidacion" style="width:220px; text	-transform:uppercase;" value="<?php if (isset($_GET['btn_buscar'])) echo $_GET['txt_num_liquidacion']; ?>" onkeypress="return handleEnter(this,event);" tabindex="2" /></td>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:right;">
                            <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="3" /></span></th>
                        <th colspan="2" style="text-align:left; ">
                            <span><input type="button" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" style="margin-left:35px;" onclick="document.getElementById('txt_num_liquidacion').value = '';document.getElementById('cmb_agencia_origen').selectedIndex = 0;" /></span></th>
                    </tr>
          </table>
          </form>
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Content unit - One column -->
	<div class="column1-unit">

		<h1>Liquidaciones para esta Oficina</h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Giros_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th style="width:70px;text-align:center;" title="Fecha / Hora del Giro">Fecha/Hora</th>';
						echo '<th style="text-align:center;">Liq.</th>';
						echo '<th title="Agencia Origen" style="text-align:left;">Age. Origen <br />por: <span title="Usuario que registr&oacute; la liquidaci&oacute;n.">Usuario</span></th>';
						echo '<th style="text-align:center;">Chofer</th>';
						echo '<th style="text-align:center;">Unidad</th>';
						echo '<th title="Encomiendas por Recepcionar." style="text-align:center;">Enc.</th>';
						echo '<th title="Recepcionar las Encomiendas." style="text-align:center;">Acci&oacute;n</th>';
					echo '</tr>';
		
				for ($fila = 0; $fila < count($Giros_Array); $fila++)
				{
					$id = $Giros_Array[$fila][0];
					$fecha = $Giros_Array[$fila][1];
					$hora = ($Giros_Array[$fila][2]);
					$liquidacion = $Giros_Array[$fila][3];
					$id_oficina_origen = $Giros_Array[$fila][4];
					$oficina_origen = OficinaByID($id_oficina_origen);
					$id_usuario = $Giros_Array[$fila][5];
					$user = UserByID($id_usuario);
					$user_name = UserNombreByID($id_usuario);
					$chofer = $Giros_Array[$fila][6];
					$unidad = $Giros_Array[$fila][7];
					$encomiendas = $Giros_Array[$fila][8];
					echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
						echo "<td>$fecha<br/>$hora</td>";
						echo "<td>$liquidacion</td>";
						echo "<td>$oficina_origen<br /><span title=\"$user_name\">$user</span></td>";
						echo "<td>$chofer</td>";
						echo "<td style=\"text-align:center;\">$unidad</td>";
						echo "<td style=\"text-align:center;\">$encomiendas</td>";
						echo '<td style="text-align:center;"><a href="e_recepcion.php?ID='.$id.'"><img src="img/operacion/Symbol-Check.png" style="margin-left:18px;" /></a></td>';
					echo "</tr>";
				}
				echo '</table>';
			}
			else
				echo '<p>No hay giros registrados para esta Oficina.</p>';
		?>
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
    <div id="div_error">
    </div>
<?PHP
	}
	elseif (isset($_GET['ID']))
	{
		$id_liquidacion = $_GET['ID'];
		// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
		$Error = false;
		$MsjError = '';
		
		// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
		include_once('function/validacion.php');
		/***********************************************************************/
		/* VERIFICAMOS SI EL IDMOVIMIENTO EXISTE Y NO ESTA CANCELADO Y ANULADO */
		/***********************************************************************/
		
		$db_giro->query("SELECT COUNT(`e_liquidacion`.`id_liquidacion`)  
		AS 'EXISTE'
		FROM `e_liquidacion`
		WHERE `e_liquidacion`.`id_liquidacion` = ".$id_liquidacion."
		AND `e_liquidacion`.`liq_estado` = 1;");
		$existe_mov = $db_giro->get('EXISTE');
		if ($existe_mov == 0)
		{
			MsjErrores('Liquidación no encontrada, intentelo de nuevo o consulte con el administrador.');
		}
		/***************************************/
		/* OBTENEMOS LOS DATOS DEL MOVIMIENTOS */
		/***************************************/
		if ($Error == false)
		{
			// OBTENEMOS LOS DATOS DE LA LIQUIDACION
			$db_giro->query("SELECT
			`e_liquidacion`.`id_liquidacion`,
			`e_liquidacion`.`id_usuario`,
			`e_liquidacion`.`id_oficina_origen`,
			`e_liquidacion`.`id_oficina_destino`,
			`e_liquidacion`.`liq_fecha`,
			`e_liquidacion`.`liq_hora`,
			`e_liquidacion`.`liq_chofer`,
			`e_liquidacion`.`liq_pullman`
			FROM `bd_giro`.`e_liquidacion`
			WHERE `e_liquidacion`.`id_liquidacion` = ".$id_liquidacion."
			AND `e_liquidacion`.`liq_estado` = 1
			ORDER BY `e_liquidacion`.`id_liquidacion` DESC
			LIMIT 1;
			");
			$LIQ_Array = $db_giro->get();
			// MOSTRAMOS LOS DATOS
			if (count($LIQ_Array) > 0)
			{
				//OBTENEMOS LOS DATOS EN LAS VARIABLES
				$id_liquidacion = $LIQ_Array[0][0];
				$id_usuario = $LIQ_Array[0][1];
				$id_oficina_origen = $LIQ_Array[0][2];
				$id_oficina_destino = $LIQ_Array[0][3];
				$fecha = $LIQ_Array[0][4];
				$Hora = $LIQ_Array[0][5];
				$chofer = $LIQ_Array[0][6];
				$pullman = $LIQ_Array[0][7];
?>
	<h1>Recepci&oacute;n de Encomiendas</h1> 
	<div class="column1-unit">
      <div class="contactform">
        <form name="giro_form" id="anulacion_form" method="post" action="e_cancelar_action.php?insert" class="">
            <table border="0">
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th style="width:120px;">Fecha : </th>
                <td><?php echo $fecha; ?></td>
                <th style="width:120px;">Hora : </th>
                <td><?php echo $Hora; ?></td>
              </tr>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th title="Agencia Origen de la Encomienda">Ag. Origen:</th>
                <td><?PHP echo OficinaByID($id_oficina_origen); ?></td>
                <th title="Agencia Destino de la Encomienda">Ag. Destino : </th>
                <td><?PHP echo OficinaByID($id_oficina_destino); ?></td>
              </tr>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th>Chofer : </th>
                <td colspan="4"><?php echo utf8_encode($chofer); ?></td>
              </tr>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th>Unidad : </th>
                <td><?php echo $pullman; ?></td>
                <th>Documento  : </th>
                <td><span><?php echo $num_liquidacion; ?></span></td>
              </tr>
              </table>
<?php
				$db_giro->query("SELECT `e_mov_detalle`.`id_movimiento`
			, `e_mov_detalle`.`e_num_item`
			, `e_movimiento`.`id_oficina_origen`
			, CONCAT(RIGHT(CONCAT('00000', CAST(`e_movimiento`.`num_serie` AS CHAR)),4)
			, '-'
			, RIGHT(CONCAT('0000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS `NUM_GUIA`
			, IF(`CONSIG`.`per_tipo` = 'PERSONA', `CONSIG`.`per_nombre`, `CONSIG`.`per_razon_social`) AS `CONSIGNATARIO`
			, CAST(CONCAT(`e_mov_detalle`.`md_cantidad`
			, ' - '
			, `e_mov_detalle`.`md_descripcion`) AS CHAR) AS `CONTENIDO`
			, `e_mov_detalle`.`md_carrera`
			, `e_mov_detalle`.`md_flete`
			, `e_mov_detalle`.`md_estado`
			FROM `e_liquidacion_detalle`
			INNER JOIN `e_mov_detalle`
			ON `e_liquidacion_detalle`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
			AND `e_liquidacion_detalle`.`e_num_item` = `e_mov_detalle`.`e_num_item`
			INNER JOIN `e_movimiento`
			ON `e_mov_detalle`.`id_movimiento` = `e_movimiento`.`id_movimiento`
			INNER JOIN `e_persona` AS `CONSIG`
			ON `CONSIG`.`id_persona` = `e_movimiento`.`id_consignatario`
			WHERE `e_liquidacion_detalle`.`id_liquidacion` = ".$id_liquidacion."
			AND `e_liquidacion_detalle`.`ld_estado` = 1
			AND (`e_mov_detalle`.`md_estado` = 2
			OR `e_mov_detalle`.`md_estado` = 3)
			AND `e_movimiento`.`id_oficina_destino` = ".$_SESSION['ID_OFICINA']."
			GROUP BY `e_movimiento`.`id_oficina_destino`
			, `e_mov_detalle`.`id_movimiento`
			, `e_mov_detalle`.`e_num_item`
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
				echo '<tr>';
					echo '<th style="width:50px; text-align:center;"># GUIAS</th>';
					echo '<th style="width:325px;">CONSIGNATARIO</th>';
					echo '<th style="width:300px; text-align:center;">CONTENIDO DE LA GUIA</th>';
					echo '<th style="width:40px; text-align:center;">CARRERA</th>';
					echo '<th style="width:40px; text-align:center;">VALOR</th>';
					echo '<th style="width:40px; text-align:center;">ACCI&Oacute;N</th>'; 	  
				echo '</tr>';
			$Oficina_Actual = 0;
			$guia_actual = '';
          	for ($fila = 0; $fila < count($Array_liquidacion_list); $fila ++)
			{
				$id_movimiento = $Array_liquidacion_list[$fila][0];
				$num_item = $Array_liquidacion_list[$fila][1];
				$oficina = $Array_liquidacion_list[$fila][2];
				$guia = $Array_liquidacion_list[$fila][3];
				$consignatario = utf8_encode($Array_liquidacion_list[$fila][4]);
				$descripcion = utf8_encode($Array_liquidacion_list[$fila][5]);
				$carrera = $Array_liquidacion_list[$fila][6];
				$importe = $Array_liquidacion_list[$fila][7];
				$estado  = $Array_liquidacion_list[$fila][8];
				if ($Oficina_Actual != $oficina)
				{
					echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
						echo '<td colspan="6" style="text-align:center;"><span>'.OficinaByID($oficina).'</span></td>';
					echo '</tr>';
					$Oficina_Actual = $oficina;
				}
				
				echo '<tr id="div_tr_'.$id_movimiento.$num_item.'"  onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
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
					if ($estado == 2)
					{
						echo '<td style="text-align:center;" id="div_td_'.$id_movimiento. $num_item.'"><input name="cbox_recepcionar" type="checkbox" value="" onClick="E_Recep_Enc('.$id_movimiento.', '.$num_item.',true);this.focus();"></td>';
					}
					elseif ($estado == 3)
					{
						echo '<td style="text-align:center;" id="div_td_'.$id_movimiento. $num_item.'"><input name="cbox_recepcionar" type="checkbox" value="" onClick="E_Recep_Enc('.$id_movimiento.', '.$num_item.',false);this.focus();" checked></td>';
					}
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
			  echo '<tr>';
					echo '<td colspan="6" style="text-align:center;"><span>No hay encomiendas Pendientes</span></td>';
			  echo '</tr>';
			echo '</table>';
		}
?>
              </table>
              <table>
              <tr style="height:20px; font-size:80%;">
                <th>Usuario:</th>
                <td><span>
                <?PHP
                    /* MOSTRAMOS EL NOMBRE DEL USURIO QUE REALIZA LA OPERACION */
                    echo strtoupper($_SESSION['USUARIO']);
                ?>				
                    </span>                        </td>
                <th>Agencia : </th>
                <td><span>
                <?PHP
                    /* MOSTRAMOS EL NOMBRE DE LA AGENCIA DONDE SE REALIZA LA OPERACION */
                    echo strtoupper($_SESSION['OFICINA']);
                ?>				
                    </span>                        </td>
              </tr>
              <tr>
                <th colspan="5"><input name="txt_id_liquidacion" id="txt_id_liquidacion" type="hidden" value="<?php echo $id_liquidacion; ?>" readonly="readonly" /></th>
              </tr>
              <tr>
              	<td colspan="4" style="text-align:center; padding-left:40px;">
                    <span><input type="button" name="btn_regresar" id="btn_regresar" class="button" style="width:220px;" value="Regresar" tabindex="6" onclick="document.location.href='e_recepcion.php';" /></span>                        </td>
              </tr>
            </table>
        </form>
      </div>              
    </div>
<?php
			}
		}
	}
 ?>
	
</div>