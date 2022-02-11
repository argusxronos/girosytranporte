<?php
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged.php");
	// OBTEMOS LOS DATOS DE MOVIMIENTOS
	require_once 'cnn/config_master.php';
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	if(!isset($_SESSION['OFICINAS']))
	{
		$db_transporte->query("SELECT `of`.`idoficina`, `of`.`oficina`
				FROM `oficinas` as `of`
				ORDER BY `of`.`oficina`;");
		$_SESSION['OFICINAS'] = $db_transporte->get();
	}
	$Oficina_Array = $_SESSION['OFICINAS'];
	// CREAMOS LA CONSULTA DE BUSQUEDA
	$sql = '';
	if (isset($_GET['btn_buscar']) && $_GET['btn_buscar'] != "")
	{
		// VALIDACIONES
		
		
		// PROCEDIMIENTO
		$sql = "SELECT `e_movimiento`.`id_movimiento`
			, DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d-%m-%Y') 
			AS `fecha_emision`
			, TIME_FORMAT(`e_movimiento`.`e_hora_emision`, '%r')
			AS `hora_emision`
			, CONCAT(RIGHT(CONCAT('0000',
			CAST(`e_movimiento`.`num_serie` AS CHAR)),4), '-'
			, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento`
			AS CHAR)),8)) AS 'NUM_BOLETA'
			, IF(`CONSIG`.`per_tipo` = 'PERSONA',
			`CONSIG`.`per_nombre`, `CONSIG`.`per_razon_social`)
			AS `CONSIGNATARIO`
			, IF(`REMIT`.`per_tipo` = 'PERSONA',
			`REMIT`.`per_nombre`, `REMIT`.`per_razon_social`)
			AS `REMITENTE`
			, `e_movimiento`.`id_oficina_origen`
			, `e_movimiento`.`id_usuario`
			FROM `e_movimiento`
			INNER JOIN `e_persona` as `CONSIG`
			ON `e_movimiento`.`id_consignatario` = `CONSIG`.`id_persona`
			INNER JOIN `e_persona` as `REMIT`
			ON `e_movimiento`.`id_remitente` = `REMIT`.`id_persona`
			WHERE 1";
		if ($_SESSION['TIPO_USUARIO'] == 1)
		{
			$sql = $sql ." AND `e_movimiento`.`id_oficina_origen` = '".$_SESSION['ID_OFICINA']."'";
		}
		if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0)
		{
			$consignatario = utf8_decode(strtoupper(urldecode($_GET['txt_consignatario'])));
			$consignatario = str_replace(" ", "", $consignatario);
			
			$sql = $sql ." AND (REPLACE(`CONSIG`.`per_nombre`, ' ' , '') LIKE '%".$consignatario."%'
			OR REPLACE(`CONSIG`.`per_razon_social`, ' ' , '') LIKE '%".$consignatario."%')";
		}
		if (isset($_GET['txt_Remitente']) && strlen($_GET['txt_Remitente']) > 0)
		{
			$remitente = utf8_decode(strtoupper(urldecode($_GET['txt_Remitente'])));
			$remitente = str_replace(" ", "", $remitente);
			
			$sql = $sql ." AND REPLACE(`REMIT`.`per_nombre`, ' ' , '') LIKE '%".$remitente."%'
			OR REPLACE(`CONSIG`.`per_razon_social`, ' ' , '') LIKE '%".$remitente."%')";
		}
		if (isset($_GET['txt_serie_doc']) && strlen($_GET['txt_serie_doc']) > 0)
		{
			esNumerico($_GET['txt_serie_doc'], 'Serie Boleta');
			$sql = $sql ." AND `e_movimiento`.`num_serie` = '".$_GET['txt_serie_doc']."'";
		}
		if (isset($_GET['txt_numero_doc']) && strlen($_GET['txt_numero_doc']) > 0)
		{
			esNumerico($_GET['txt_numero_doc'], 'N&uacute;mero Boleta');
			$sql = $sql ." AND `e_movimiento`.`num_documento` = '".$_GET['txt_numero_doc']."'";
		}
		$sql = $sql ." ORDER BY `e_movimiento`.`e_fecha_emision` DESC, 
		IF(`CONSIG`.`per_tipo` = 'PERSONA', `CONSIG`.`per_nombre`, 
		`CONSIG`.`per_razon_social`) 
		LIMIT 30;";
		if ($Error == false)
		{
			// REALIZAMOS LA CONSULTA A LA BD
			$db_giro->query($sql);
			$E_Array = $db_giro->get();
		}
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
	<h1 class="pagetitle">Modificar Destino <span>( Solo puedes modificar las Encomiendas Registradas en esta Agencia )</span></h1>
	<!-- Content unit - One column -->
	<div class="column1-unit">

	  <h1>Zona de Busqueda - <span>RECUERDE INGRESAR PRIMERO LOS APELLIDOS Y LUEGO LOS NOMBRES</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
		<form method="get" action="e_modificar_destino.php" name="buscar_encomienda" >
			<table width="100%" border="0">
				<tr>
					<th width="150">Boleta :</th>
					<th width="240"><input type="text" name="txt_serie_doc" id="txt_serie" style="width:60px;" onfocus="this.select();" title="Serie del Documento." tabindex="1" onkeypress="return handleEnter(this,event);" />
                      <span>-</span>
                      <input type="text" name="txt_numero_doc" id="txt_serie" style="width:100px;" title="N&uacute;mero del Documento." tabindex="2" /></th>
					<th>&nbsp;</th>
			  </tr>
                <tr>
					<th width="150">Consignatario :</th>
					<th colspan="2"><input type="text" name="txt_consignatario" style="width:100%;" /></th>
			  </tr>
				<tr>
					<th>Remitente : </th>
					<th colspan="2"><input type="text" name="txt_Remitente" style="width:100%;" /></th>
			  </tr>
				<tr>
					<th colspan="2" style="text-align:right;">
						<span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" /></span>					</th>
					<th style="text-align:left; ">
						<span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" style="margin-left:35px;" /></span>                    </th>
				</tr>
			</table>
	  </form>
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
<?php
	if (!isset($_GET['ID']))
	{
?>
    <!-- Content unit - One column -->
	<div class="column1-unit">
		
		<h1>Modificar Destino de Encomienda</h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (isset($_GET['btn_buscar']) && count($E_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th title="N&uacute;mero de ">N&uacute;m. Boleta</th>';
						echo '<th style="width:70px;" title="Fecha / Hora de Entrega">Fecha/Hora</th>';
						echo '<th>Consignatario<br /><span>Re: </span>Remitente</th>';
						echo '<th title="Agencia Origien donde se registr&oacute; la encomienda.">Usuario</th>';
						echo '<th style="width:20px;" title="Usuario que entreg&oacute; la encomienda">Destino</th>';
						echo '<th style="width:20px;" title="Estado de la Encomienda">Estado</th>';
					echo '</tr>';
		
				for ($fila = 0; $fila < count($E_Array); $fila++)
				{
					$ID_MOVIMIENTO = $E_Array[$fila][0];
					$fecha = $E_Array[$fila][1] . '<br />' . $E_Array[$fila][2];
					$guia = $E_Array[$fila][3];
					$consig = utf8_encode($E_Array[$fila][4]);
					$remit = utf8_encode($E_Array[$fila][5]);
					$ID_OFICINA_ORIGEN = $E_Array[$fila][6];
					$ID_USUARIO_ORIGEN = $E_Array[$fila][7];
					$NOM_OFICINA = OficinaByID($ID_OFICINA_ORIGEN);
					$USUARIO_ORIGEN = UserByID($ID_USUARIO_ORIGEN);
					$NOMBRE_U_ORIGEN = utf8_encode(UserNombreByID($ID_USUARIO_ORIGEN));
					echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" >';
						echo '<td>'.$guia.'</td>';
						echo '<td>'.$fecha.'</td>';
						echo '<td>'.$consig.'<br/><span title="Remitente">Re: </span>'.$remit.'</td>';
						echo '<td><span title="'.$NOMBRE_U_ORIGEN.'">'.$USUARIO_ORIGEN.'</span></td>';
						echo '<td>'.$NOM_OFICINA.'</td>';
						echo '<td style="text-align:center;"><a href="e_modificar_destino.php?ID='.$ID_MOVIMIENTO.'"><img style="margin-left:13px;"  src="img/operacion/Symbol-Update.png" alt="Estado" title="Encomienda en Almacen." /></a></td>';
					echo '</tr>';
				}
				if (!isset($_GET['btn_buscar']))
				{
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'g_modificar.php?';//curPageURL();
						/*if (strlen($_GET['btn_buscar']) > 0)
							$url = $url .'&';
						else
							$url = $url .'?';*/
						$back = "&laquo;Atras";
						$next = "Siguiente&raquo;";
						echo '<th colspan="8" style="text-align:center;">';
						$paginacion->generaPaginacion($totalRows, $back, $next, $url);
						echo '</th>';
					echo '</tr>';
					echo '</div>';
				}
				echo '</table>';
			}
			else
				echo '<p>No hay giros registrados o no ha realizado la busqueda.</p>';
		?>
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
    <div id="div_error">
    </div>
<?php
	}
	elseif (isset($_GET['ID']))
	{
		// MOSTRAMOS EL GIRO A CANCELAR
		$id_mov = $_GET['ID'];
		// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
		$Error = false;
		$MsjError = '';
		
		/***********************************************************************/
		/* VERIFICAMOS SI EL IDMOVIMIENTO EXISTE Y NO ESTA CANCELADO Y ANULADO */
		/***********************************************************************/
		
		$db_giro->query("SELECT COUNT(`e_movimiento`.`id_movimiento`) AS 'EXISTE'
				FROM `e_movimiento`
				INNER JOIN `e_persona` as `CONSIG`
				ON `e_movimiento`.`id_consignatario` = `CONSIG`.`id_persona`
				INNER JOIN `e_persona` as `REMIT`
				ON `e_movimiento`.`id_remitente` = `REMIT`.`id_persona`
				INNER JOIN `e_mov_detalle`
				ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
				WHERE `e_mov_detalle`.`md_estado` > 1
				AND `e_movimiento`.`id_movimiento` = " .$id_mov . ";");
		$existe_mov = $db_giro->get('EXISTE');
		if ($existe_mov > 0)
		{
			MsjErrores('La Encomienda no puede ser <span>Modificada</span>, consulte con el administrador.');
		}
		/***************************************/
		/* OBTENEMOS LOS DATOS DEL MOVIMIENTOS */
		/***************************************/
		if ($Error == false)
		{
			$db_giro->query("SELECT 
			DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d-%m-%Y') AS `fecha_emision`
			, TIME_FORMAT(`e_movimiento`.`e_hora_emision`, '%r') AS `hora_emision`
			, CONCAT(RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4), '-'
			, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA'
			, IF(`CONSIG`.`per_tipo` = 'PERSONA', `CONSIG`.`per_nombre`, `CONSIG`.`per_razon_social`)
			AS `CONSIGNATARIO`
			, IF(`REMIT`.`per_tipo` = 'PERSONA', `REMIT`.`per_nombre`, `REMIT`.`per_razon_social`)
			AS `REMITENTE`
			, `e_movimiento`.`id_oficina_origen`
			, `e_movimiento`.`id_oficina_destino`
			, `e_movimiento`.`id_usuario`
			FROM `e_movimiento`
			INNER JOIN `e_persona` as `CONSIG`
			ON `e_movimiento`.`id_consignatario` = `CONSIG`.`id_persona`
			INNER JOIN `e_persona` as `REMIT`
			ON `e_movimiento`.`id_remitente` = `REMIT`.`id_persona`
			INNER JOIN `e_mov_detalle`
			ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
			WHERE `e_mov_detalle`.`md_estado` = 1
			AND `e_movimiento`.`id_movimiento` = " .$id_mov . "
			GROUP BY CONCAT(RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4), '-'
			, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) 
			ORDER BY 1 DESC;");
			$Enc_Array = $db_giro->get();
			// MOSTRAMOS LOS DATOS
			if (count($Enc_Array) > 0)
			{
				//OBTENEMOS LOS DATOS EN LAS VARIABLES
				$fecha = $Enc_Array[0][0];
				$Hora = $Enc_Array[0][1];
				$guia = $Enc_Array[0][2];
				$consignatario = $Enc_Array[0][3];
				$remitente = $Enc_Array[0][4];
				$id_oficina_origen = $Enc_Array[0][5];
				$id_oficina_destino = $Enc_Array[0][6];
				$id_usuario = $Enc_Array[0][7];
				$user = UserByID($id_usuario);
				$user_name = UserNombreByID($id_usuario);
			
?>
	<!-- Content unit - One column -->
	<div class="column1-unit">
		<div class="contactform">
		<form action="e_modificar_destino_action.php?insert" method="post" name="entrega_form" target="_self">
			<table border="0">
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th style="width:120px;">Fecha : </th>
                <td><?php echo $fecha; ?></td>
                <th style="width:120px;">Hora : </th>
                <td><?php echo $Hora; ?></td>
              </tr>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th>Agencia :</th>
                <td><?PHP echo OficinaByID($id_oficina_origen); ?></td>
                <th>Destino : </th>
                <td><select name="cmb_agencia_destino" class="combo" tabindex="1" onkeypress="return handleEnter(this, event)" title="Agencia de Destino del Giro.">
                  <?php
							if (count($Oficina_Array) == 0)
							{
								echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
							}
							else
							{
								echo '<option value="" selected="selected">[ Seleccione su Oficina ]</option>';
								for ($fila = 0; $fila < count($Oficina_Array); $fila++)
								{
									if ($Oficina_Array[$fila][0] == $id_oficina_destino)
									{
										echo '<option value="'.$Oficina_Array[$fila][0].'" selected="selected"> '.$Oficina_Array[$fila][1].' </option>';
									}
									else
									{
										echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
									}
								}
							}
						 ?>
                </select>
              </tr>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th>Remitente : </th>
                <td colspan="4"><?php echo utf8_encode($remitente); ?></td>
              </tr>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th>Consignatario : </th>
                <td colspan="4"><span><?php echo utf8_encode($consignatario); ?></span></td>
              </tr>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th>Usuario : </th>
                <td title="<?php echo $user_name; ?>"><?php echo $user; ?></td>
                <th>Documento  : </th>
                <td><span><?php echo $guia; ?></span></td>
              </tr>
              </table>
<?php
				$db_giro->query("SELECT
				`e_mov_detalle`.`md_cantidad`
				, `e_mov_detalle`.`md_descripcion`
				, `e_mov_detalle`.`md_estado`
				FROM `e_mov_detalle`
				WHERE `e_mov_detalle`.`id_movimiento` = " .$id_mov . ";");
				$Array_list = $db_giro->get();
				if (count($Array_list) > 0)
				{
				/* SI NO HAY ERRORES EN LA TRANSACCION MOSTRAMOS LA LISTA */
					echo '<table>';
						echo '<tr>';
							echo '<th style="width:50px; text-align:center;"># GUIAS</th>';
							echo '<th style="width:300px; text-align:center;">CONTENIDO DE LA GUIA</th>';
							echo '<th style="width:40px; text-align:center;">ESTADO</th>';
						echo '</tr>';
					for ($fila = 0; $fila < count($Array_list); $fila ++)
					{
						$cantidad = $Array_list[$fila][0];
						$descripcion = utf8_encode($Array_list[$fila][1]);
						$estado  = $Array_list[$fila][2];
						echo '<tr id="div_tr_'.$id_mov.'"  onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
							echo '<td>'.$cantidad.'</td>';
							echo '<td>'.$descripcion.'</td>';
							
							switch ($estado) {
							case 1:
								echo '<td style="text-align:center;"><img style="margin-left:13px;"  src="img/estados/recibido.png" alt="Estado" title="Encomienda en Almacen." /></td>';
								break;
							case 2:
								echo '<td style="text-align:center;"><img style="margin-left:13px;"  src="img/estados/enviado.png" alt="Estado" title="Encomienda entregada al Bus." /></td>';
								break;
							case 3:
								echo '<td style="text-align:center;"><img style="margin-left:13px;"  src="img/estados/pendiente.png" alt="Estado" title="Encomienda en la Agencia de Destino." /></td>';
								break;
							case 4:
								echo '<td style="text-align:center;"><img style="margin-left:13px;"  src="img/estados/entregado.png" alt="Estado" title="Encomienda entregada al consignatario." /></td>';
								break;
							default:
								echo '<td style="text-align:center;"><img style="margin-left:13px;"  src="img/estados/sin_estado.png" alt="Estado" title="Esta encomienda no tiene estado definido." /></td>';
								break;
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
						echo '<th style="width:220px; text-align:center;">CONTENIDO DE LA GUIA</th>';
						echo '<th style="width:90px; text-align:center;">ESTADO</th>'; 	  
					  echo '</tr>';
					  echo '<tr>';
							echo '<td colspan="3" style="text-align:center;"><span>No hay encomiendas por Entregar.</span></td>';
					  echo '</tr>';
					echo '</table>';
				}
			}
			else
			{
				// MOSTRAMOS EL MENSAJE DE ERROR
				echo '<!-- Content unit - One column -->';
				echo '<div class="column1-unit">';
					echo '<h1>Error con la Operaci&oacute;n</h1>';
					echo '<p>'.$MsjError.'</p>';
				echo '</div>';
				echo '<!-- Limpiar Unidad del Contenido -->';
				echo '<hr class="clear-contentunit" />';
			}
?>
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
                <th colspan="5"><input name="txt_id_movimiento" id="txt_id_liquidacion" type="hidden" value="<?php echo $id_mov; ?>" readonly="readonly" /></th>
              </tr>
              <tr>
              	<td colspan="2" style="text-align:center; padding-left:40px;">
                    <span><input type="submit" name="btn_entregar" id="btn_entregar" class="button" style="width:220px;" value="Modificar" title="clic para registrar la entrega de la(s) Encomienda(s)." onclick="document.location.href='e_recepcion.php';" /></span></td>
              	<td colspan="2" style="text-align:center; padding-left:40px;">
                    <span><input type="button" name="btn_regresar" id="btn_regresar" class="button" style="width:220px;" value="Regresar" tabindex="6" onclick="document.location.href='e_entrega.php';" /></span></td>
              </tr>
            </table>
		</form>
		</div>
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
<?PHP
		}
		else
		{
			// MOSTRAMOS EL MENSAJE DE ERROR
			echo '<!-- Content unit - One column -->';
			echo '<div class="column1-unit">';
				echo '<h1>Error con la Operaci&oacute;n</h1>';
				echo '<p>'.$MsjError.'</p>';
			echo '</div>';
			echo '<!-- Limpiar Unidad del Contenido -->';
			echo '<hr class="clear-contentunit" />';
		}
	}
 ?>
	
</div>