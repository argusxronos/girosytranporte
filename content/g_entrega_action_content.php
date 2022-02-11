<?php
	/***********************************************/
	/* INICIAMOS EL PROCESO DE VALIDACION DE DATOS */
	/***********************************************/
	
	//INCLUIMOS LA FUNCION PARA MODIFICAR FECHAS
	//include_once('function/date_add.php');
	// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
	require_once 'cnn/config_giro.php';
	// Obtenemos los datos de las oficinas
	if(!isset($_SESSION['OFICINAS']))
	{
		$db_transporte->query("SELECT `of`.`idoficina`, `of`.`oficina`
				FROM `oficinas` as `of`
				ORDER BY `of`.`oficina`;");
		$_SESSION['OFICINAS'] = $db_transporte->get();
	}
	// funcion para obtener nombre de la oficina por ID
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
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	$id_mov = 0;
	$consig_id = '';
	$consig_dni = '';
	$consig_direccion = '';
	$num_vale = '';
	$observ = '';
	$fecha_registro = date("Y-m-d");
	$hora_registro =  date('G:i:s');
	// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
	$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " .getRealIP();
	// OBTENEMOS LOS DATOS
	if (!isset($_POST['txt_mov_id']) && strlen($_POST['txt_mov_id']) == 0)
	{
		MsjErrores('Error con el giro, intentelo de nuevo.');
	}
	else
	{
		$id_mov = $_POST['txt_mov_id'];
	}
	if(!isset($_POST['txt_consig_id']) && strlen($_POST['txt_consig_id']) == 0)
	{
		MsjErrores('Error con el consignatario, consulte con el administrador.');
	}
	else
	{
		$consig_id = $_POST['txt_consig_id'];
	}
	if(isset($_POST['txt_con_dni']) && $_POST['txt_con_dni'] == 1)
	{
		ValicacionDNI($_POST['txt_consig_dni']);
		$consig_dni = $_POST['txt_consig_dni'];
	}
	else
	{
		$consig_dni = $_POST['txt_consig_dni'];
	}
	LimiteCaracteres($_POST['txt_consig_direccion'],'Direcci&oacute;n',3,100);
	
	$consig_direccion = str_replace("\xF1", "\xD1", $_POST['txt_consig_direccion']);
	$consig_direccion = utf8_decode(strtoupper(urldecode(trim($consig_direccion))));
	
	if(strlen($_POST['txt_num_vale']) == 0)
	{
		MsjErrores('Ingrese el <span>n&uacute;mero de Vale.</span>');
	}
	else
	{
		
		$num_vale = intval($_POST['txt_num_vale']);
		/*if ($num_vale = 0)
		{
			MsjErrores('N&uacute;mero de Vale <span>No puede ser Cero.</span>');
		}*/
	}
	if (strlen($_POST['txt_observ']) == 0)
	{
		$observ = '';
	}
	else
	{
		MaxCaracteres ($_POST['txt_observ'], 'Observaci&oacute;n', 60);
		
		$observ = str_replace("\xF1", "\xD1", $_POST['txt_observ']);
		$observ = utf8_decode(strtoupper(urldecode(trim($observ))));
	}
	// VERIFICAMOS SI LA CONTRASEÑA COINCIDE
	$clave = '';
	if(isset($_POST['txt_clave']))
	{
		MaxCaracteres ($_POST['txt_clave'], 'Clave', 4);
		MinCaracteres($_POST['txt_clave'], 'Clave', 4);
		esNumerico($_POST['txt_clave'], 'Clave');
		$clave = md5($_POST['txt_clave']);
	}
	

	// PROCEDIMIENTO PARA INGRESAR LOS DATOS SI NO HAY ERRORES
	if ($Error == false)
	{
		// VERIFICAMOS SI EL DNI FUE INGRESADO, SI NO, ACTUALIZAMOS EL DATO, CASO CONTRARIO, DEBEN DE SER IGUALES
		/*$db_giro->query("SELECT LENGTH(`g_persona`.`per_num_dni`) AS `CON_DNI`
						FROM `g_persona`
						WHERE `g_persona`.`id_persona` = '".$consig_id."';");
		$con_dni = $db_giro->get('CON_DNI');
		if($con_dni == 0)
		{*/
			// YA QUE NO HAY UN DNI REGISTRADO, ACTUALIZAMOS LOS DATOS
			$db_giro->query("UPDATE `g_persona` SET `per_num_dni`='".$consig_dni."' 
							WHERE `id_persona`='".$consig_id."';");
			if (!$db_giro)
			{
				MsjErrores('Problemas con la actualizaci&oacute;n del D.N.I. Intentelo de nuevo.');
			}
		/*}
		else
		{
			// VERIFICAMOS SI EL DNI CONINCIDE CON LO INGRESADO
			$db_giro->query("SELECT COUNT(`g_persona`.`id_persona`) AS `VALIDO`
							FROM `g_persona`
							WHERE `g_persona`.`per_num_dni` = ".$consig_dni."
							AND `g_persona`.`id_persona` = ".$consig_id.";");
			$is_dni_valid = $db_giro->get('VALIDO');
			if ($is_dni_valid == 0)
			{
				MsjErrores('<span>D.N.I. no coincide</span> con el Registro del Consignatario.');
			}
		}*/
		// VERIFICAMOS SI LA DIRECCION ESTA REGISTRADA, CASO CONSTRARIO ACTUALIZAR EL CAMPO
		/*$db_giro->query("SELECT LENGTH(`g_persona`.`per_direccion`) AS 'CON_DIRECCION'
						FROM `g_persona`
						WHERE `g_persona`.`id_persona` = '".$consig_id."';");
		$con_direccion = $db_giro->get('CON_DIRECCION');
		if($con_direccion == 0)
		{*/
			// YA QUE NO HAY UNA DIRECCION REGISTRADA, ACTUALIZAMOS LOS DATOS
			$db_giro->query("UPDATE `g_persona` SET `per_direccion`='".strtoupper($consig_direccion)."' 
							WHERE `id_persona`='".$consig_id."';");
			if (!$db_giro)
			{
				MsjErrores('Problemas con la actualizaci&oacute;n de la Direcci&oacute;n, Intentelo de Nuevo');
			}
		//}
		if ($Error == false)
		{
			// VERIFICAMOS SI EL ID GIRO NO HA SIDO ENTREGADO YA
			$db_giro->query("SELECT count(`g_movimiento`.`id_movimiento`) as `EXISTE`
							FROM `g_movimiento`
							WHERE `g_movimiento`.`id_movimiento` = ".$id_mov."
							AND (`g_movimiento`.`esta_anulado` = 1
							OR `g_movimiento`.`esta_cancelado` = 1
							OR `g_movimiento`.`autorizado` = 0);");
			$giro_valido = $db_giro->get('EXISTE');
			if ($giro_valido > 0)
			{
				MsjErrores('No es posible cancelar este giro, puesto que <span>ya fue cancelado, est&aacute; anulado o requiere ser autorizado.</span>.');
			}
			$db_giro->query("SELECT COUNT(`g_entrega`.`id_movimiento`) AS `ENTREGADO`
							FROM `g_entrega`
							WHERE `g_entrega`.`id_movimiento` = '".$id_mov."';");
			$giro_valido = $db_giro->get('ENTREGADO');
			if ($giro_valido > 0)
			{
				MsjErrores('No es posible cancelar este giro, puesto que <span>ya fue cancelado</span>.');
			}
			// VERIFICAMOS SI EL VALE YA FUE UTILIZADO
			/*$db_giro->query("SELECT COUNT(`g_entrega`.`id_movimiento`) AS `VALE`
							FROM `g_entrega`
							WHERE `g_entrega`.`ent_num_vale` = '".$num_vale."';");
			$vale_valido = $db_giro->get('VALE');
			if ($vale_valido > 0)
			{
				MsjErrores('No es posible cancelar este giro, puesto que el n&uacute;mero de <span>vale ya fue registrado.</span>');
			}*/
			if(isset($_POST['txt_clave']))
			{
				$db_giro->query("SELECT IF(`g_movimiento`.`mov_clave` = '".$clave."', 'SI', 'NO') AS 'ES_IGUAL'
				FROM `g_movimiento`
				WHERE `g_movimiento`.`id_movimiento` = ".$id_mov."
				LIMIT 1;");
				$giro_valido = $db_giro->get('ES_IGUAL');
				if ($giro_valido == 'NO')
				{
					MsjErrores('<span>La Clave no Coincide.</span>');
				}
			}
			if ($Error == false)
			{
				// SI NO SE PRODUJO ERRROR ALGUNO, REGISTRAMOS LA ENTREGA.
				$db_giro->query("INSERT INTO `g_entrega` (`id_movimiento`, `ent_id_usuario`, `ent_id_oficina`, 
	`ent_num_vale`, `ent_observ`, `ent_fecha_entrega`, `ent_hora_entrega`, `nom_pc_ip`) 
								VALUES (".$id_mov.", ".$_SESSION['ID_USUARIO'].", ".$_SESSION['ID_OFICINA'].", '".$num_vale."', '".$observ."', '".$fecha_registro."', '".$hora_registro."', '".$pc_nom_ip."');");
				if (!$db_giro)
				{
					MsjErrores('Problemas al registrar la cancelaci&oacute;n, intentelo de nuevo.');
				}
				if ($Error == false)
				{
					// ACTUALIZAMOS LA TABLA MOVIMIENTO, EL CAMPO ENTREGADO
					$db_giro->query("UPDATE `g_movimiento` SET `esta_cancelado`=1 
									WHERE `id_movimiento`='".$id_mov."';");
					if (!$db_giro)
					{
						MsjErrores('Problemas al registrar la cancelaci&oacute;n, intentelo de nuevo.');
						// ELIMINAMOS EL REGISTRO EN LA TABLA G_ENTREGADO
						$db_giro->query("DELETE FROM `g_entrega` 
										`WHERE `id_movimiento`='".$id_mov."';");
						if (!$db_giro)
						{
							MsjErrores('Error Grave, por favor, comuniquese con el administrador.');
						}
					}
				}
			}
		}
		
	}
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
<?php
	if ($Error == true)
	{
		// MOSTRAMOS EL RESULTADO DE LOS ERRORES
		echo '<!-- Pagetitle -->';
		echo '<h1 class="pagetitle">Mensaje de Error</h1>';
		echo '<div class="column1-unit">';
		  echo '<h1>Detalle del o los errores.</h1>   ';                         
		  echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
		  echo '<p>'.$MsjError.'</p>';
?>
		  <p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; javascript:history.back(1)" ></p>
<?PHP
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
	else
	{
		// MOSTRAMOS LOS RESULTADO PARA LA IMPRESION
		$db_giro->query("SELECT `g_movimiento`.`id_movimiento`,
						CONCAT(RIGHT(CONCAT('0000',CAST(`g_movimiento`.`num_serie` AS CHAR)),4), '-',
						RIGHT(CONCAT('00000000',CAST(`g_movimiento`.`num_documento` AS CHAR)),8)) AS `NUM_BOLETA`,
						CONCAT(`g_entrega`.`ent_num_vale`,
						' - ', DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d/%m/%Y')), 
						`g_movimiento`.`id_oficina_origen`, 
						`CONSIGNATARIO`.`per_ape_nom` AS 'CONSIGNATARIO', `CONSIGNATARIO`.`per_num_dni` AS 'DNI', 
						`CONSIGNATARIO`.`per_direccion` AS 'DIRECCION', 
						CONCAT(DATE_FORMAT(`g_entrega`.`ent_fecha_entrega`,'%d/%m/%Y'), ' - ', 
						CAST(TIME_FORMAT(`g_entrega`.`ent_hora_entrega`,'%r') AS CHAR)) as 'fecha_entrega',
						CONCAT(IF(`g_movimiento`.`tipo_moneda` = '1','S/.','$'), 
						CAST(`g_movimiento`.`monto_giro` AS CHAR)) AS 'MONTO', 
						`g_movimiento`.`monto_giro_letras` AS 'MONTO_LETRAS'
						FROM `g_movimiento`
						INNER JOIN `g_persona` AS `CONSIGNATARIO`
						ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
						INNER JOIN `g_entrega` 
						ON `g_movimiento`.`id_movimiento` = `g_entrega`.`id_movimiento`
						WHERE `g_movimiento`.`id_movimiento` = ".$id_mov."
						LIMIT 1;");
		$Giro_Array = $db_giro->get();
?>
		<div class="Print_Vale">
			<div class="vale_content">
				<table width="400" border="0">
				  <tr>
					<td colspan="2" class="num_vale"><?php echo $Giro_Array[0][2]; ?></td>
				  </tr>
				  <tr>
					<td class="monto" colspan="2"><?php echo $Giro_Array[0][8]; ?></td>
				  </tr>
				  <tr>
					<td class="text_right" style="font-size:7px;">F. PAGO :</td>
					<td class="text_left" style="font-size:95%;"><?php echo $Giro_Array[0][7]; ?></td>
				  </tr>
				  <tr>
					<td class="text_right" style="font-size:7px;"> DESTINO :</td>
					<td class="text_left"><?php echo utf8_encode($_SESSION['OFICINA']); ?></td>
				  </tr>
				  <tr>
					<td class="text_right" style="font-size:7px;">ORIGEN :</td>
					<td class="text_left"><?php echo utf8_encode(OficinaByID($Giro_Array[0][3])); ?></td>
				  </tr>
				  <tr>
					<td class="text_right" style="font-size:7px;">BOLETA :</td>
					<td class="text_left"><?php echo $Giro_Array[0][1]; ?></td>
				  </tr>
				  <tr>
					<td class="text_left" colspan="2"  style="font-size:7px;">DESTINATARIO :</td>
				  </tr>
				  <tr>
					<td class="text_right" colspan="2" style="height:10px;"><?php echo utf8_encode($Giro_Array[0][4]); ?></td>
				  </tr>
				  <tr>
					<td class="text_left" colspan="2"  style="font-size:7px;">IMPORTE EN LETRAS :</td>
				  </tr>
				  <tr>
					<td class="text_right" colspan="2" style="height:40px; vertical-align:top;"><?php echo $Giro_Array[0][9]; ?></td>
				  </tr>
				  <tr>
					<td class="text_right"  style="font-size:7px;">USUARIO :</td>
					<td class="text_left" style="height:10px;"><?php echo $_SESSION['USUARIO']; ?></td>
				  </tr>
				</table>
		  </div>
			<div class="firma">
				<table width="400" border="0">
				  <tr>
					<th class="firma_vale"><HR></th>
				  </tr>
				  <tr>
					<th class="text_center" style="letter-spacing:4px;">RECIB&Iacute; CONFORME	</th>
				  </tr>
				  <tr>
					<th class="text_center" style="letter-spacing:4px;">D.N.I. <?php echo $Giro_Array[0][5]; ?> </th>
				  </tr>
				</table>

			</div>
		</div>
		
		<script language="JavaScript"> 
				window.print();
				window.onfocus = function() 
				{
					/*window.open('','_parent','');*/
					location.href='g_entrega.php';
				}
		</script>
<?PHP
	}
?>  
</div>