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
		$sql = "SELECT `g_movimiento`.`id_movimiento`, `g_movimiento`.`id_oficina_origen`, 
`CONSIGNATARIO`.`per_ape_nom` AS 'CONSIGNATARIO', 
`REMITENTE`.`per_ape_nom` AS 'REMITENTE', CASE `g_movimiento`.`esta_cancelado`
			WHEN 0 THEN 'NO'
			ELSE 'SI'
			END AS 'ESTADO', DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y') AS `fecha_emision`, TIME_FORMAT(`g_movimiento`.`hora_emision`, '%r') AS `hora_emision`, 
			CONCAT(RIGHT(CONCAT('0000',CAST(`g_movimiento`.`num_serie` AS CHAR)),4), '-',
RIGHT(CONCAT('00000000', CAST(`g_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA', CONCAT(IF(`g_movimiento`.`tipo_moneda` = '1','S/.','$'), CAST(`g_movimiento`.`monto_giro` AS CHAR)) AS 'MONTO', `g_movimiento`.`id_usuario`, `g_movimiento`.`id_oficina_destino`
			FROM `g_movimiento`
			INNER JOIN `g_persona` AS `CONSIGNATARIO`
			ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
			INNER JOIN `g_persona` AS `REMITENTE`
			ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
			WHERE `g_movimiento`.`esta_anulado` = 0
			AND `g_movimiento`.`esta_cancelado` = 0
			AND `g_movimiento`.`autorizado` = 1";
		if ($_SESSION['TIPO_USUARIO'] == 1)
		{
			$sql = $sql ." AND `g_movimiento`.`id_oficina_origen` = '".$_SESSION['ID_OFICINA']."'";
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
		if (isset($_GET['txt_serie_doc']) && strlen($_GET['txt_serie_doc']) > 0)
		{
			esNumerico($_GET['txt_serie_doc'], 'Serie Boleta');
			$sql = $sql ." AND `g_movimiento`.`num_serie` = '".$_GET['txt_serie_doc']."'";
		}
		if (isset($_GET['txt_numero_doc']) && strlen($_GET['txt_numero_doc']) > 0)
		{
			esNumerico($_GET['txt_numero_doc'], 'N&uacute;mero Boleta');
			$sql = $sql ." AND `g_movimiento`.`num_documento` = '".$_GET['txt_numero_doc']."'";
		}
		$sql = $sql ." ORDER BY `g_movimiento`.`fecha_emision` DESC, `CONSIGNATARIO`.`per_ape_nom`
LIMIT 30;";
		if ($Error == false)
		{
			
			// REALIZAMOS LA CONSULTA A LA BD
			
			$db_giro->query($sql);
			$Giros_Array = $db_giro->get();
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
	<h1 class="pagetitle">Modificar Destino <span>( Solo puedes modificar los giros emitidos en esta Agencia )</span></h1>
    <?php 
	if (!isset($_GET['ID']))
	{
?>
	<!-- Content unit - One column -->
	<div class="column1-unit">

	  <h1>Zona de Busqueda - <span>RECUERDE INGRESAR PRIMERO LOS APELLIDOS Y LUEGO LOS NOMBRES</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
		<form method="get" action="g_modificar_destino.php" name="buscar_giro" >
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
 	<!-- Content unit - One column -->
	<div class="column1-unit">

		<h1>Giros Pendientes</h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (isset($_GET['btn_buscar']) && count($Giros_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th style="width:70px;" title="Fecha / Hora del Giro">Fecha/Hora</th>';
						echo '<th>Consignatario</th>';
						echo '<th>Remitente&nbsp;</th>';
						echo '<th title="Agencia Origien / Usuario que realiz&oacute; el giro">Origen</th>';
						echo '<th title="Agencia Destino">Destino</th>';
						echo '<th>Monto</th>';
						echo '<th title="N&uacute;mero de Boleta">N&uacute;m. Boleta</th>';
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
					$agen_dest = OficinaByID($Giros_Array[$fila][10]);
					echo "<tr>";

							echo "<td>$fecha<br/>$hora</td>";
							echo "<td>$consig</td>";
							echo "<td>$remit</td>";
							echo "<td>$agen_orig<br /><span title='$user_name'>$user</span></td>";
							echo "<td>$agen_dest</td>";
							echo "<td>$monto</td>";
							echo '<td style="text-align:center;">'.$num_boleta.'</td>';
							if ($estado == 'NO')
							{
								echo '<td style="text-align:center;"><a href="g_modificar_destino.php?ID='.$id.'" ><img src="./img/operacion/Symbol-Update.png" width="24" height="24" title="Modificar Giro." /></a></td>';
							}
					echo "</tr>";
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
<?PHP
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
		
		$db_giro->query("SELECT count(`g_movimiento`.`id_movimiento`) AS 'EXISTE'
						FROM `g_movimiento`
						WHERE `g_movimiento`.`id_movimiento` = ".$id_mov."
						AND `g_movimiento`.`esta_anulado` = 0
						AND `g_movimiento`.`esta_cancelado` = 0;");
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
			$db_giro->query("SELECT `g_movimiento`.`id_movimiento`, `g_movimiento`.`id_oficina_origen`, `g_movimiento`.`id_oficina_destino`, `REMITENTE`.`per_ape_nom` AS 'REMITENTE', `g_movimiento`.`id_consignatario` AS 'ID_COSIGNATARIO',
`CONSIGNATARIO`.`per_ape_nom` AS 'CONSIGNATARIO', CONCAT(DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y'), ' - ', TIME_FORMAT(`g_movimiento`.`hora_emision`,'%r')) as 'fecha_emision',
CONCAT(IF(`g_movimiento`.`tipo_moneda` = '1','S/.','$'), CAST(`g_movimiento`.`monto_giro` AS CHAR) , ' ( ', `g_movimiento`.`monto_giro_letras`, ' )') AS 'MONTO'
, `g_movimiento`.`num_serie`
, `g_movimiento`.`num_documento`
							FROM `g_movimiento`
							INNER JOIN `g_persona` AS `CONSIGNATARIO`
							ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
							INNER JOIN `g_persona` AS `REMITENTE`
							ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
							WHERE `g_movimiento`.`id_movimiento` = ".$id_mov."
							AND `g_movimiento`.`esta_anulado` = 0
							AND `g_movimiento`.`esta_cancelado` = 0
							LIMIT 1;");
			$Giro_Array = $db_giro->get();
			// MOSTRAMOS LOS DATOS
?>
	<!-- Content unit - One column -->
	<div class="column1-unit">
		<div class="contactform">
		<form action="g_modificar_destino_action.php?insert" method="post" name="entrega_form" target="_self">
			<table width="100%" border="0">
              <tr>
				<th>Documento :</th>
				<td colspan="3">
                	<span><?php echo $Giro_Array[0][8]; ?>
                - 
                <?php echo $Giro_Array[0][9]; ?></span></td>
			  </tr>
			  <tr>
				<th style="width:110px;">Agen. Origen : </th>
				<td><input name="txt_mov_id" type="hidden" value="<?PHP echo $_GET['ID']; ?>" />
				<input name="txt_agencia_origen" type="hidden" value="<?PHP echo $Giro_Array[0][1]; ?>" /><?PHP echo OficinaByID($Giro_Array[0][1]); ?></td>
				<th style="width:80px;">Fecha Giro : </th>
				<td style="width:160px;"><?PHP echo $Giro_Array[0][6]; ?></td>
			  </tr>
              <tr>
				<th style="width:110px;">Agen. Dest : </th>
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
									if ($Oficina_Array[$fila][0] == $Giro_Array[0][2])
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
			    </select></td>
				<th colspan="2" style="width:80px;">&nbsp;</th>
			  </tr>
			  <tr>
				<th scope="row">Remitente : </th>
				<td colspan="3" ><?PHP echo utf8_encode($Giro_Array[0][3]); ?></td>
			  </tr>
			  <tr>
				<th colspan="4" style="height:5px;"></th>
			  </tr>
			  <tr>
				<th>Monto Giro </th>
				<td colspan="3"><?PHP echo $Giro_Array[0][7]; ?></td>
			  </tr>
			  <tr>
				<th>Consignatario:</th>
				<td  colspan="3"><input type="hidden" id="txt_consig_hidden" name="txt_consig_ID" value="<?php echo $Giro_Array[0][4]; ?>" />
					<span><?php echo utf8_encode($Giro_Array[0][5]); ?></span></td>
			  </tr>
			  <tr>
				<th colspan="4" style="height:5px;"></th>
			  </tr>
			  <tr>
				<th>Observaci&oacute;n:</th>
				<td colspan="4">MODIFICADO POR: <input type="text" name="txt_observ" onkeypress="return handleEnter(this,event);" onfocus="" style="width:80%;text-transform:uppercase;" tabindex="2" /></td>
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
				  <input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" tabindex="5" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.entrega_form.submit();" />
				  <input type="button" name="btn_regresar" id="btn_regresar" class="button" value="Regresar" tabindex="6" onclick="document.location.href='g_modificar_destino.php';" />
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