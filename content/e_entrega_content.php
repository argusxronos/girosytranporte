<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// VERIFICAMOS SI ESTA LOGEADO
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged.php");
	// CREAMOS LA CONSULTA DE BUSQUEDA
	$sql = "SELECT `e_movimiento`.`id_movimiento`
			, DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d-%m-%Y') AS `fecha_emision`
			, TIME_FORMAT(`e_movimiento`.`e_hora_emision`, '%r') AS `hora_emision`
			, CONCAT(RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4), '-'
			, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA'
			, IF(`CONSIG`.`per_tipo` = 'PERSONA', `CONSIG`.`per_nombre`, `CONSIG`.`per_razon_social`)
			AS `CONSIGNATARIO`
			, IF(`REMIT`.`per_tipo` = 'PERSONA', IFNULL(`REMIT`.`per_nombre`,'TURISMO CENTRAL'), IFNULL(`REMIT`.`per_razon_social`,'TURISMO CENTRAL'))
			AS `REMITENTE`
			, `e_movimiento`.`id_oficina_origen`
			, `e_movimiento`.`id_usuario`
			FROM `e_movimiento`
			INNER JOIN `e_persona` as `CONSIG`
			ON `e_movimiento`.`id_consignatario` = `CONSIG`.`id_persona`
			LEFT JOIN `e_persona` as `REMIT`
			ON `e_movimiento`.`id_remitente` = `REMIT`.`id_persona`
			INNER JOIN `e_mov_detalle`
			ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
			WHERE `e_movimiento`.`id_oficina_destino` = " .$_SESSION['ID_OFICINA'] ."
			AND `e_movimiento`.`e_fecha_emision` <= CURDATE()
			AND `e_mov_detalle`.`md_estado` = 3";
	$sql_rows = "SELECT (count(`e_movimiento`.`id_movimiento`))
				FROM `e_movimiento`
				INNER JOIN `e_persona` as `CONSIG`
				ON `e_movimiento`.`id_consignatario` = `CONSIG`.`id_persona`
				INNER JOIN `e_persona` as `REMIT`
				ON `e_movimiento`.`id_remitente` = `REMIT`.`id_persona`
				INNER JOIN `e_mov_detalle`
				ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
				WHERE `e_mov_detalle`.`md_estado` = 3
				GROUP BY `e_movimiento`.`id_movimiento`;";
	if (isset($_GET['btn_buscar']) && $_GET['btn_buscar'] != "")
	{
		
		if (strlen($_GET['txt_fecha'])>0)
		{
			$sql = $sql ." AND `e_movimiento`.`e_fecha_emision` = '".$_GET['txt_fecha']."'";
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
			
			$sql = $sql ." AND (REPLACE(`REMIT`.`per_nombre`, ' ' , '') LIKE '%".$remitente."%'
			OR REPLACE(`REMIT`.`per_razon_social`, ' ' , '') LIKE '%".$remitente."%')";
		}
		if (isset($_GET['cmb_agencia_origen']) && $_GET['cmb_agencia_origen'] != 0)
		{
			$sql = $sql ." AND `e_movimiento`.`id_oficina_origen` = " .$_GET['cmb_agencia_origen'];
		}
	}
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 10;
	$fecha_actual = new DateTime(date("Y-m-d"));
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	if (isset($_GET['btn_buscar']) && $_GET['btn_buscar'] != "")
		$sql = $sql ." GROUP BY CONCAT(RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4), '-'
, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) 
 ORDER BY `e_movimiento`.`e_fecha_emision` DESC, `e_movimiento`.`e_hora_emision` DESC
LIMIT 50;";
	else
		$sql = $sql ." GROUP BY CONCAT(RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4), '-'
, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) 
 ORDER BY `e_movimiento`.`e_fecha_emision` DESC, `e_movimiento`.`e_hora_emision` DESC
LIMIT $from, $cantidad;";
	// OBTEMOS LOS DATOS DE MOVIMIENTOS
	require_once 'cnn/config_master.php';
	// REALIZAMOS LA CONSULTA A LA BD
	$db_giro->query($sql_rows);
	$totalRows = $db_giro->get('TOTAL');
	
	$db_giro->query($sql);
	$Encomiendas_Array = $db_giro->get();
	
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
	<h1 class="pagetitle">Entregar Encomienda</h1>
    <?php 
	if (!isset($_GET['ID']))
	{
?>
	<!-- Content unit - One column -->
	<div class="column1-unit">
        <div id="zona-busqueda">
          <h1>Zona de Busqueda - <span>RECUERDE INGRESAR PRIMERO LOS APELLIDOS Y LUEGO LOS NOMBRES</span></h1>
          <?php echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>'; ?>
            <form method="get" action="e_entrega.php" name="buscar_encomienda"  >
                <table width="100%" border="0">
                    <tr>
                        <th width="150">Consignatario :</th>
                        <th width="270"><input type="text" name="txt_consignatario" style="width:220px; text-transform:uppercase;" value="" /></th>
                        <th width="80">Fecha : </th>
                  <td width="270"><input name="txt_fecha" id="txt_fecha" type="text" value="" title="Fecha de envio." readonly="readonly" />
                    <input type="button" value="Cal" class="button" onclick="displayCalendar(document.forms[0].txt_fecha,'yyyy/mm/dd',this)" style="width:54px;" /></td>
                  </tr>
                    <tr>
                        <th>Remitente : </th>
                        <th><input type="text" name="txt_Remitente" style="width:220px;text-transform:uppercase;" /></th>
                        <th>Agencia<br />Origen : </th>
                        <th>
                            <select name="cmb_agencia_origen" class="combo" title="Agencia de origen del giro.">
                                <?php
                                    if (count($Oficina_Array) == 0)
                                    {
                                        echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
                                    }
                                    else
                                    {
                                        echo '<option value="" selected="selected">[ Seleccione Oficina ]</option>';
                                        for ($fila = 0; $fila < count($Oficina_Array); $fila++)
                                        {
                                            echo '<option value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
                                        }
                                    }
                                 ?>
                            </select>					</th>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:right;">
                            <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="19" /></span>					</th>
                        <th colspan="2" style="text-align:left; ">
                            <span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="20" style="margin-left:35px;" /></span>					</th>
                    </tr>
                </table>
    
          </form>
        </div>
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Content unit - One column -->
	<div class="column1-unit">

		<h1>Encomiendas por Entregar de <?php echo $_SESSION['OFICINA']; ?></h1>                            
  <?php echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Encomiendas_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						/*echo '<th title="La Encomienda ha sido copiado al Cuarderno y en que P&aacute;gina?">P&aacute;g?</th>';*/
						echo '<th title="N&uacute;mero de ">N&uacute;m. Boleta</th>';
						echo '<th style="width:70px;" title="Fecha / Hora del Giro">Fecha/Hora</th>';
						echo '<th>Consignatario</th>';
						echo '<th>Remitente&nbsp;</th>';
						echo '<th title="Agencia Origien / Usuario que realiz&oacute; el giro">Origen</th>';
						echo '<th style="width:20px;">Ope.</th>';
					echo '</tr>';
		
				for ($fila = 0; $fila < count($Encomiendas_Array); $fila++)
				{
					$id = $Encomiendas_Array[$fila][0];
					$fecha = $Encomiendas_Array[$fila][1];
					$hora = ($Encomiendas_Array[$fila][2]);
					$num_boleta = $Encomiendas_Array[$fila][3];
					$consig = utf8_encode($Encomiendas_Array[$fila][4]);
					$remit = utf8_encode($Encomiendas_Array[$fila][5]);
					$agen_orig = OficinaByID($Encomiendas_Array[$fila][6]);
					$user = UserByID($Encomiendas_Array[$fila][7]);
					$user_name = UserNombreByID($Encomiendas_Array[$fila][8]);

?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
						/*if ($copiado == 'SI')
						{
							echo '<td style="text-align:center;" id="div_td_pg_'.$id.'"><input type="checkbox" name="txt_copiado_'.$id.'" value="'.$id.'" onClick="" checked="checked" title="P&aacute;gina: '.$copiado_pagina.'."  />/<span title="N&uacute;mero de la p&aacute;gina en la que fue copiado el Giro">'.$copiado_pagina.'<span></td>'; 
						}
						else
						{
							echo '<td style="text-align:center;" id="div_td_pg_'.$id.'"><input type="text" name="txt_copiado_'.$id.'" id="txt_copiado_'.$id.'" value="'.$copiado_pagina.'" title="Ingrese el n&uacute;mero de p&aacute;gina del cuaderno en la que fue copiado el Giro y presione ENTER." style="width:30px;text-align:center;"  onkeypress="" onkeyup = "extractNumber(this,0,false);" onfocus="this.select();" /></td>';
						}*/
						echo '<td style="text-align:center;">'.$num_boleta.'</td>';
						echo "<td>$fecha<br/>$hora</td>";
						echo "<td>$consig</td>";
						echo "<td>$remit</td>";
						echo "<td>$agen_orig<br /><span title='$user_name'>$user</span></td>";
						echo '<td style="text-align:center;"><a href="e_entrega.php?ID='.$id.'" ><img src="./img/operacion/Symbol-Check.png" width="24" height="24" title="Cancelar este Giro." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
					echo "</tr>";
				}
				if (!isset($_GET['btn_buscar']))
				{
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'e_entrega.php?';//curPageURL();
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
				echo '<p>No hay encomiedas pendientes en esta Oficina.</p>';
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
		// MOSTRAMOS EL GIRO A CANCELAR
		$id_mov = $_GET['ID'];
		// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
		$Error = false;
		$MsjError = '';
		
		// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
		include_once('function/validacion.php');
		/***********************************************************************/
		/* VERIFICAMOS SI EL IDMOVIMIENTO EXISTE Y NO ESTA CANCELADO Y ANULADO */
		/***********************************************************************/
		
		$db_giro->query("SELECT `e_movimiento`.`id_movimiento` AS 'EXISTE'
				FROM `e_movimiento`
				INNER JOIN `e_mov_detalle`
				ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
				WHERE `e_mov_detalle`.`md_estado` = 3
				AND `e_movimiento`.`id_movimiento` = " .$id_mov . ";");
		$existe_mov = $db_giro->get('EXISTE');
		if ($existe_mov == 0)
		{
			MsjErrores('La Encomienda no puede ser <span>Entregada</span>, consulte con el administrador.');
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
			, IF(LENGTH(IFNULL(`e_movimiento`.`mov_clave`,'')) = 0,'NO','SI') 
			AS 'CON_CLAVE'
			FROM `e_movimiento`
			INNER JOIN `e_persona` as `CONSIG`
			ON `e_movimiento`.`id_consignatario` = `CONSIG`.`id_persona`
			LEFT JOIN `e_persona` as `REMIT`
			ON `e_movimiento`.`id_remitente` = `REMIT`.`id_persona`
			INNER JOIN `e_mov_detalle`
			ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
			WHERE `e_mov_detalle`.`md_estado` = 3
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
				$CON_CLAVE = $Enc_Array[0][8];

?>
	<!-- Content unit - One column -->
	<div class="column1-unit">
		<div class="contactform">
		<form name="entrega_form" id="entrega_form" method="post" action="e_entrega_action.php?insert" class="">
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
                <td><?PHP echo OficinaByID($id_oficina_destino); ?></td>
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
<?php
			  if ($CON_CLAVE == 'SI')
			  {
?>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th>Clave : </th>
                <td><input type="password" value="" name="txt_clave" id="txt_clave" class="field" title="Clave de Seguridad." tabindex="1"  style="width:150px; text-transform:uppercase; font-size:16px; font-weight:bold;" onfocus="this.select()" maxlength="4" onkeyup="extractNumber(this,2,false);" onkeypress = "return handleEnter(this, event);" onblur="jsf_Empty_Clave(this);" /></td>
                <td></td>
                <td></td>
              </tr>
<?PHP
			  }
?>
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
					echo '<th style="width:50px; text-align:center;">CANT.</th>';
					echo '<th style="width:300px; text-align:center;">CONTENIDO DE LA GUIA</th>';
					echo '<th style="width:40px; text-align:center;">ESTADO</th>';
				echo '</tr>';
          	for ($fila = 0; $fila < count($Array_list); $fila ++)
			{
				$cantidad = $Array_list[$fila][0];
				$descripcion = utf8_encode($Array_list[$fila][1]);
				$estado  = $Array_list[$fila][2];
				echo '<tr id="div_tr_'.$id_mov.'" onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
					echo '<td style="text-align:center;">'.$cantidad.'</td>';
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
					case 8:
						echo '<td style="text-align:center;"><img style="margin-left:13px;"  src="img/estados/sin_estado.png" alt="Estado" title="Esta Encomienda est&aacute; Anulada." /></td>';
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
                    <span><input type="submit" name="btn_entregar" id="btn_entregar" class="button" style="width:220px;" value="Entregar" title="clic para registrar la entrega de la(s) Encomienda(s)." onclick="document.location.href='e_recepcion.php';" tabindex="2" /></span></td>
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
				MsjErrores('Esta Encomienda no puede ser entergada, consulte con el Administrador');
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