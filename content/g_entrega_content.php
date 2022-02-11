<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// VERIFICAMOS SI ESTA LOGEADO
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged.php");
	// CREAMOS LA CONSULTA DE BUSQUEDA
	$sql = "SELECT `g_movimiento`.`id_movimiento`
			, `g_movimiento`.`id_oficina_origen`
			, `CONSIGNATARIO`.`per_ape_nom` AS 'CONSIGNATARIO'
			, `REMITENTE`.`per_ape_nom` AS 'REMITENTE'
			, CASE `g_movimiento`.`esta_cancelado`
			WHEN 0 THEN 'NO'
			ELSE 'SI'
			END AS 'ESTADO'
			, DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y') AS `fecha_emision`
			, TIME_FORMAT(`g_movimiento`.`hora_emision`, '%r') AS `hora_emision`
			, CONCAT(RIGHT(CONCAT('0000',CAST(`g_movimiento`.`num_serie` AS CHAR)),4), '-'
			, RIGHT(CONCAT('00000000', CAST(`g_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA'
			, CONCAT(IF(`g_movimiento`.`tipo_moneda` = '1','S/.','$')
			, CAST(`g_movimiento`.`monto_giro` AS CHAR)) AS 'MONTO'
			, `g_movimiento`.`id_usuario`
			, IF(`g_movimiento`.`esta_copiado` = 1,'SI','NO') AS 'COPIADO'
			, IF(`g_movimiento`.`autorizado` = 1,'SI','NO') AS `autorizado`
			, IF(`g_movimiento`.`esta_anulado` = 1,'SI','NO') AS `esta_anulado`
			, IF(`g_movimiento`.`copiado_pagina` = 0, '0'
			, CAST(`g_movimiento`.`copiado_pagina` AS CHAR)) AS `copiado_pagina`
			FROM `g_movimiento`
			INNER JOIN `g_persona` AS `CONSIGNATARIO`
			ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
			INNER JOIN `g_persona` AS `REMITENTE`
			ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
			WHERE `g_movimiento`.`id_oficina_destino` = " .$_SESSION['ID_OFICINA'] ."
			AND `g_movimiento`.`fecha_emision` <= CURDATE()
			AND `g_movimiento`.`esta_anulado` = 0
			AND `g_movimiento`.`de_administracion` = 0";
	$sql_rows = "SELECT COUNT(`g_movimiento`.`id_movimiento`) AS 'TOTAL'
			FROM `g_movimiento`
			INNER JOIN `g_persona` AS `CONSIGNATARIO`
			ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
			INNER JOIN `g_persona` AS `REMITENTE`
			ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
			WHERE `g_movimiento`.`id_oficina_destino` = " .$_SESSION['ID_OFICINA'] ."
			AND `g_movimiento`.`esta_anulado` = 0
			AND `g_movimiento`.`de_administracion` = 0";
	if (!isset($_GET['cbox_cancelado']) || strlen($_GET['cbox_cancelado']) == 0)
	{
		$sql = $sql ." AND `g_movimiento`.`esta_cancelado` = 0";
		$sql_rows = $sql_rows ." AND `g_movimiento`.`esta_cancelado` = 0";
	}
	
	if (isset($_GET['btn_buscar']) && $_GET['btn_buscar'] != "")
	{
		
		if (strlen($_GET['txt_fecha'])>0)
		{
			$sql = $sql ." AND `g_movimiento`.`fecha_emision` = '".$_GET['txt_fecha']."'";
			$sql_rows = $sql_rows ." AND `g_movimiento`.`fecha_emision` = '".$_GET['txt_fecha']."'";
		}
		if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0)
		{
			$consignatario = utf8_decode(strtoupper(urldecode($_GET['txt_consignatario'])));
			$consignatario = str_replace(" ", "", $consignatario);
			
			$sql = $sql ." AND REPLACE(`CONSIGNATARIO`.`per_ape_nom`, ' ' , '') LIKE '%".$consignatario."%'";
			$sql_rows = $sql_rows ." AND REPLACE(`CONSIGNATARIO`.`per_ape_nom`, ' ' , '') LIKE '%".$consignatario."%'";
		}
		if (isset($_GET['txt_Remitente']) && strlen($_GET['txt_Remitente']) > 0)
		{
			$remitente = utf8_decode(strtoupper(urldecode($_GET['txt_Remitente'])));
			$remitente = str_replace(" ", "", $remitente);
			
			$sql = $sql ." AND REPLACE(`REMITENTE`.`per_ape_nom`, ' ' , '') LIKE '%".$remitente."%'";
			$sql_rows = $sql_rows ." AND REPLACE(`REMITENTE`.`per_ape_nom`, ' ' , '') LIKE '%".$remitente."%'";
		}
		if (isset($_GET['cmb_agencia_origen']) && $_GET['cmb_agencia_origen'] != 0)
		{
			$sql = $sql ." AND `g_movimiento`.`id_oficina_origen` = " .$_GET['cmb_agencia_origen'];
			$sql_rows = $sql_rows ." AND `g_movimiento`.`id_oficina_origen` = " .$_GET['cmb_agencia_origen'];
		}
	}
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 10;
	$fecha_actual = new DateTime(date("Y-m-d"));
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	if (isset($_GET['btn_buscar']) && $_GET['btn_buscar'] != "")
		$sql = $sql ." ORDER BY `g_movimiento`.`fecha_emision` DESC, `g_movimiento`.`hora_emision` DESC, `CONSIGNATARIO`.`per_ape_nom`
LIMIT 50;";
	else
		$sql = $sql ." ORDER BY `g_movimiento`.`fecha_emision` DESC, `g_movimiento`.`hora_emision` DESC, `CONSIGNATARIO`.`per_ape_nom`
LIMIT $from, $cantidad;";
	
	$sql_rows = $sql_rows .';';
	// OBTEMOS LOS DATOS DE MOVIMIENTOS
	require_once 'cnn/config_master.php';
	// REALIZAMOS LA CONSULTA A LA BD
	$db_giro->query($sql_rows);
	$totalRows = $db_giro->get('TOTAL');
	
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
	<h1 class="pagetitle">Pagar Giro</h1>
    <?php 
	if (!isset($_GET['ID']))
	{
?>
	<!-- Content unit - One column -->
	<div class="column1-unit">
        <div id="zona-busqueda">
          <h1>Zona de Busqueda - <span>RECUERDE INGRESAR PRIMERO LOS APELLIDOS Y LUEGO LOS NOMBRES</span></h1>
          <?php echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>'; ?>
            <form method="get" action="g_entrega.php" name="buscar_giro" >
                <table width="100%" border="0">
                    <tr>
                        <th width="150">Consignatario :</th>
                        <th width="240"><input type="text" name="txt_consignatario" style="width:220px; text-transform:uppercase;" value="" /></th>
                        <th width="80">Fecha : </th>
                        <th width="270">
                            <input name="txt_fecha" id="txt_fecha" type="text" value="" title="Fecha de envio." style="width:100px;" readonly="readonly" >
                            <input type="button" value="Cal" class="button" onClick="displayCalendar(document.forms[0].txt_fecha,'yyyy/mm/dd',this)" style="width:54px;" ></th>
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
                        <th colspan="2">&nbsp;</th>
                        <th>Cancelados?</th>
                        <th><input type="checkbox" name="cbox_cancelado" value="1" /></th>
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

		<h1>Giros de <?php echo $_SESSION['OFICINA']; ?></h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Giros_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th title="El Giro ha sido copiado al Cuarderno y en que P&aacute;gina?">P&aacute;g?</th>';
						echo '<th style="width:70px;" title="Fecha / Hora del Giro">Fecha/Hora</th>';
						echo '<th>Consignatario</th>';
						echo '<th>Remitente&nbsp;</th>';
						echo '<th title="Agencia Origien / Usuario que realiz&oacute; el giro">Origen</th>';
						echo '<th>Monto</th>';
						echo '<th title="N&uacute;mero de ">N&uacute;m. Boleta</th>';
						echo '<th style="width:20px;">Ope.</th>';
					echo '</tr>';
		
				for ($fila = 0; $fila < count($Giros_Array); $fila++)
				{
					$id = $Giros_Array[$fila][0];
					$consig = utf8_encode($Giros_Array[$fila][2]);
					$remit = utf8_encode($Giros_Array[$fila][3]);
					$agen_orig = OficinaByID($Giros_Array[$fila][1]);
					$estado = $Giros_Array[$fila][4];
					$fecha = $Giros_Array[$fila][5];
					$hora = ($Giros_Array[$fila][6]);
					$num_boleta = $Giros_Array[$fila][7];
					$monto = $Giros_Array[$fila][8];
					$user = UserByID($Giros_Array[$fila][9]);
					$user_name = UserNombreByID($Giros_Array[$fila][9]);
					$copiado = $Giros_Array[$fila][10];
					$autorizado = $Giros_Array[$fila][11];
					$copiado_pagina = $Giros_Array[$fila][13];
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
						if ($copiado == 'SI')
						{
							echo '<td style="text-align:center;" id="div_td_pg_'.$id.'"><input type="checkbox" name="txt_copiado_'.$id.'" value="'.$id.'" onClick="Update_Uncopy(event, this, '.$id.');" checked="checked" title="P&aacute;gina: '.$copiado_pagina.'."  />/<span title="N&uacute;mero de la p&aacute;gina en la que fue copiado el Giro">'.$copiado_pagina.'<span></td>'; 
						}
						else
						{
							echo '<td style="text-align:center;" id="div_td_pg_'.$id.'"><input type="text" name="txt_copiado_'.$id.'" id="txt_copiado_'.$id.'" value="'.$copiado_pagina.'" title="Ingrese el n&uacute;mero de p&aacute;gina del cuaderno en la que fue copiado el Giro y presione ENTER." style="width:30px;text-align:center;"  onkeypress="Update_Copy(event, this, '.$id.');" onkeyup = "extractNumber(this,0,false);" onfocus="this.select();" /></td>';
						}
						echo "<td>$fecha<br/>$hora</td>";
						echo "<td>$consig</td>";
						echo "<td>$remit</td>";
						echo "<td>$agen_orig<br /><span title='$user_name'>$user</span></td>";
						echo "<td>$monto</td>";
						echo '<td style="text-align:center;">'.$num_boleta.'</td>';
						if ($estado == 'NO')
						{
							if ($autorizado == 'SI')
							{
								echo '<td style="text-align:center;"><a href="g_entrega.php?ID='.$id.'" ><img src="./img/operacion/Symbol-Check.png" width="24" height="24" title="Cancelar este Giro." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
							}
							else
							{
								echo '<td style="text-align:center;"><img src="./img/operacion/Warning-Shield.png" width="24" height="24" title="Para poder pagar este Giro, debe ser autorizado." /></td>';
							}
						}
						else
						{
							echo '<td style="text-align:center;"><img src="./img/operacion/Symbol-Delete.png" width="24" height="24" title="No puedes cancelar este giro." /></td>';
						}
					echo "</tr>";
				}
				if (!isset($_GET['btn_buscar']))
				{
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'g_entrega.php?';//curPageURL();
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
		
		$db_giro->query("SELECT count(`g_movimiento`.`id_movimiento`) AS 'EXISTE'
						FROM `g_movimiento`
						WHERE `g_movimiento`.`id_movimiento` = ".$id_mov."
						AND `g_movimiento`.`esta_anulado` = 0
						AND `g_movimiento`.`esta_cancelado` = 0
						AND `g_movimiento`.`id_oficina_destino` = ".$_SESSION['ID_OFICINA'].";");
		$existe_mov = $db_giro->get('EXISTE');
		if ($existe_mov == 0)
		{
			MsjErrores('El giro no puede ser <span>cancelado</span>, consulte con el administrador.');
		}
		/***************************************/
		/* OBTENEMOS LOS DATOS DEL MOVIMIENTOS */
		/***************************************/
		if ($Error == false)
		{
		$db_giro->query("SELECT `g_movimiento`.`id_movimiento`
		, `g_movimiento`.`id_oficina_origen`
		, `REMITENTE`.`per_ape_nom` AS 'REMITENTE'
		, `g_movimiento`.`id_consignatario` AS 'ID_COSIGNATARIO'
		, `CONSIGNATARIO`.`per_ape_nom` AS 'CONSIGNATARIO'
		, `CONSIGNATARIO`.`per_num_dni` AS 'DNI'
		, `CONSIGNATARIO`.`per_direccion` AS 'DIRECCION'
		, CONCAT(DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y')
		, ' - '
		, TIME_FORMAT(`g_movimiento`.`hora_emision`,'%r')) as 'fecha_emision'
		, CONCAT(IF(`g_movimiento`.`tipo_moneda` = '1','S/.','$')
		, CAST(`g_movimiento`.`monto_giro` AS CHAR) 
		, ' ( '
		, `g_movimiento`.`monto_giro_letras`, ' )') AS 'MONTO'
		, `g_movimiento`.`forma_entrega`
		, `g_movimiento`.`esta_cancelado`
		, IFNULL(`g_movimiento`.`mov_clave`,'') AS 'CLAVE'
		FROM `g_movimiento`
		INNER JOIN `g_persona` AS `CONSIGNATARIO`
		ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
		INNER JOIN `g_persona` AS `REMITENTE`
		ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
		WHERE `g_movimiento`.`id_movimiento` = ".$id_mov."
		AND `g_movimiento`.`esta_anulado` = 0
		AND `g_movimiento`.`esta_cancelado` = 0
		AND `g_movimiento`.`autorizado` = 1
		AND `g_movimiento`.`id_oficina_destino` = ".$_SESSION['ID_OFICINA']."
		AND `g_movimiento`.`de_administracion` = 0
		LIMIT 1;");
			$Giro_Array = $db_giro->get();
			// MOSTRAMOS LOS DATOS
			if (count($Giro_Array) > 0)
			{
?>
	<!-- Content unit - One column -->
	<div class="column1-unit">
		<div class="contactform">
		<form action="g_entrega_action.php" method="post" name="entrega_form" target="_self">
			<table width="100%" border="0">
			  <tr>
				<th style="width:110px;">Agen. Origen : </th>
				<td><input name="txt_mov_id" type="hidden" value="<?PHP echo $_GET['ID']; ?>" /><?PHP echo OficinaByID($Giro_Array[0][1]); ?></td>
				<th style="width:80px;">Fecha Giro : </th>
				<td style="width:160px;"><?PHP echo $Giro_Array[0][7]; ?></td>
			  </tr>
			  <tr>
				<th scope="row">Remitente : </th>
				<td colspan="3" ><?PHP echo utf8_encode($Giro_Array[0][2]); ?></td>
			  </tr>
			  <tr>
				<th colspan="4" style="height:5px;"></th>
			  </tr>
			  <tr>
				<th>Monto Giro </th>
				<td colspan="3"><?PHP echo $Giro_Array[0][8]; ?></td>
			  </tr>
<?php
	if (($Giro_Array[0][9]) != 'RECOGERÁ CON D.N.I.')
	{
			  echo '<tr>';
				echo '<th>Forma Entrega:</th>';
				echo '<td colspan="3"><input name="txt_con_dni" type="hidden" value = 1 /><span>'.strtoupper(utf8_encode($Giro_Array[0][9])).'</span></td>';
			  echo '</tr>';
	}
?>
			  <tr>
				<th>Consignatario:</th>
				<td  colspan="3"><input name="txt_consig_id" type="hidden" value="<?PHP echo utf8_decode($Giro_Array[0][3]); ?>" /><?PHP echo utf8_encode($Giro_Array[0][4]); ?></td>
			  </tr>
			  <tr>
				<th scope="row"><span>*</span>D.N.I.</th>
				<td><input type="text" name="txt_consig_dni" id="txt_consig_dni" value="<?PHP
				if (($Giro_Array[0][9]) != 'RECOGERÁ CON D.N.I.')
				{
					echo '00000000';
				}
				else
				{
					echo $Giro_Array[0][5]; 
				} ?>" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" onfocus="this.select()" tabindex="1" maxlength="8" /><input name="btn_validar_dni" type="button" value="Validar D.N.I." style="margin-left:20px;width:120px;" onclick="window.open('https://cel.reniec.gob.pe/valreg/valreg.do?accion=ini','Title','resizable=yes,width=600,height=500, top=10, left=10');" /></td>
				<td colspan="2"></td>
			  </tr>
			  <tr>
				<th scope="row"><span>*</span>Direcci&oacute;n:</th>
				<td colspan="4"><input type="text" name="txt_consig_direccion" value="<?PHP echo ($Giro_Array[0][6]); ?>" style="width:98%;text-transform:uppercase;" title="Apelldios del Remitente." tabindex="2" onkeypress="return handleEnter(this, event);" /></td>
			  </tr>
			  <tr>
				<th colspan="4" style="height:5px;"></th>
			  </tr>
			  <tr>
				<th><span>*</span>N&deg; Vale:</th>
				<td><input type="text" name="txt_num_vale" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" onfocus="this.select()" tabindex="3" /></td>
				<td colspan="2"></td>
			  </tr>
<?php
	if (strlen($Giro_Array[0][11]) > 0)
	{
?>
              <tr>
				<th><span>*</span>Clave:</th>
				<td><input type="password" name="txt_clave" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" onfocus="this.select()" tabindex="4" maxlength="4" /></td>
				<td colspan="2"></td>
			  </tr>
<?php
	}
?>
			  <tr>
				<th>Observaci&oacute;n:</th>
				<td colspan="4"><input type="text" name="txt_observ" onkeypress="return handleEnter(this,event);" onfocus="this.select()" style="width:98%;text-transform:uppercase;" <?php if (strlen($Giro_Array[0][11]) > 0)echo 'tabindex="5"'; else echo 'tabindex="4"'; ?> /></td>
			  </tr>
			  <tr>
				<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
			  </tr>
			  <tr style="height:20px; font-size:80%;">
				<th colspan="4" style="text-align:center;">
				  <?PHP
					/* MOSTRAMOS EL NOMBRE DEL USURIO QUE REALIZA LA OPERACION */
					echo 'Usuario Actual: <span style="margin-right:30px;">' .strtoupper($_SESSION['USUARIO']) .'</span>Agencia: <span>' .strtoupper($_SESSION['OFICINA']) .'</span>';
				?></th>
			  </tr>
			  <tr>
				<th colspan="4" style="height:5px;"></th>
			  </tr>
			  <tr>
				<th colspan="4" scope="row" style="text-align:center;"><span>
				  <input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" <?php if (strlen($Giro_Array[0][11]) > 0)echo 'tabindex="6"'; else echo 'tabindex="5"'; ?> onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.entrega_form.submit();" />
				  <input type="button" name="btn_regresar" id="btn_regresar" class="button" value="Regresar" tabindex="6" onclick="document.location.href='g_entrega.php';" />
				</span></th>
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
				MsjErrores('Este giro no puede ser Pagado, consulte con el Administrador');
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