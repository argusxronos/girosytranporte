<?php
	/***********************************************/
	/* INICIAMOS EL PROCESO DE VALIDACION DE DATOS */
	/***********************************************/
	
	//INCLUIMOS LA FUNCION PARA MODIFICAR FECHAS
	//include_once('function/date_add.php');
	
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$id_movimiento = 0;
	$hora_giro = '';
	$id_agen_origen = 0;
	$id_agen_destino = 0;
	$id_usuario = 0;
	$id_documento = 0;
	$doc_serie = 0;
	$doc_numero = 0;
	$esta_regist_remit = 0;
	$id_remit = 0;
	$nom_completo_remit = '';
	$dni_remit = '';
	$esta_regist_consig = 0;
	$id_consig = 0;
	$nom_completo_consig = '';
	$dni_consig = '';
	$tipo_moneda = 1;
	$monto_giro = 0;
	$monto_giro_letras = '';
	$flete = 0;
	$flete_letras = '';
	$observacion = '';
	$pc_nom_ip = '';
	
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	// VALIDACIONES PARA EL CASO DEL INSERT
	if (isset($_GET['insert']))
	{
		// VALIDACIONES PARA LA FECHA
		if (!isset($_POST['txt_fecha']))
		{
			MsjErrores('Debe ingresar fecha del Giro.');
		}
		else
		{
			
			$fecha_actual = new DateTime(date("Y-m-d"));
			$date = $_POST['txt_fecha'];
 			$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
			$fecha_giro = new DateTime($date);
			if (isset($_SESSION['TIPO_USUARIO']) && ($_SESSION['TIPO_USUARIO'] == 1))
			{
				if (strtotime($fecha_giro->format("Y-m-d")) < strtotime($fecha_actual->format("Y-m-d")))
				{
					MsjErrores('La fecha del Giro <span>'.$_POST['txt_fecha'].'</span> debe ser igual o superior al d&iacute;a de Hoy.');
				}
			}
		}
		// OBTENEMOS LA HORA DEL GIRO
		$hora_giro = date('G:i:s');
		//VALIDACIONES PARA LA AGENCIA DE ORIGEN
		if (!isset($_POST['cmb_agencia_origen']) || $_POST['cmb_agencia_origen'] == 0)
		{
			MsjErrores('Seleccione Agencia de Origen.');
		}
		else
		{
			$id_agen_origen = $_POST['cmb_agencia_origen'];
			if ($id_agen_origen == $_SESSION['ID_OFICINA'] && $_SESSION['TIPO_USUARIO'] == 1)
			{
				MsjErrores('NO PUEDE DERIVAR UN GIRO DE SU PROPIA OFICINA. Rerporte de intento enviado al administrador.');
			}
		}
		
		//VALIDACIONES PARA LA AGENCIA DE DESTINO
		if (!isset($_POST['cmb_agencia_destino']) || $_POST['cmb_agencia_destino'] == 0)
		{
			MsjErrores('Seleccione Agencia de Destino.');
		}
		else
		{
			$id_agen_destino = $_POST['cmb_agencia_destino'];	
		}
		if ($id_agen_origen == $id_agen_destino)
		{
			MsjErrores('La Agencia de Origen y la de Destino no pueden ser iguales');
		}
		if ($id_agen_origen == $_SESSION['ID_OFICINA'] && $_SESSION['TIPO_USUARIO'] == 1)
		{
			MsjErrores('No pueder derivar un Giro de tu <span>Propia Agencia</span>');
		}
		// VALIDACIONES AREA EL TIPO DE DOCUMENTO
		if (!isset($_POST['cmb_documento']) || $_POST['cmb_documento'] == '')
		{
			MsjErrores('Seleccione documento a emitir.');
		}
		else
		{
			$id_documento = $_POST['cmb_documento'];
		}
		// VALIDACIONES PARA EL USUARIO QUE ENVIA EL GIRO
		if (!isset($_POST['cmb_usuario']) || strlen($_POST['cmb_usuario']) == 0)
		{
			MsjErrores('Seleccione usuario de la Oficina Origen.');
		}
		else
		{
			$id_usuario = $_POST['cmb_usuario'];
		}
		// VALIDACIONES PARA LA SERIE
		if (!isset($_POST['txt_serie']) || strlen($_POST['txt_serie']) == 0)
		{
			MsjErrores('Consulte con el Administrador sobre los documentacion que puede emitir.');
		}
		else
		{
			$doc_serie = $_POST['txt_serie'];
		}
		// VALIDACIONES PARA EL NUMERO DEL DOCUMENTO
		if (!isset($_POST['txt_numero']))
		{
			MsjErrores('Ingrese el n&uacute;mero del documento.');
		}
		else
		{
			if (is_int($_POST['txt_numero']))
			{
				MsjErrores('N&uacute;mero del documento debe ser n&uacute;merico.');
			}
			else
			{
				$doc_numero = $_POST['txt_numero'];
			}
		}
		// VERIFICAMOS SI EL REMITENTE ESTA REGISTRADO
		if (isset($_POST['txt_remit_ID']) && strlen($_POST['txt_remit_ID']) > 0)
		{
			$esta_regist_remit = 1;
			$id_remit = $_POST['txt_remit_ID'];
			// NO ES NECESARIO VERIFICAR SI SE INGRESO EL NOMBRE DEL REMITENTE
		}
		else
		{
			$esta_regist_remit = 0;
			// VERIFICAMOS SI SE INGRESO LOS DATOS DEL REMITENTE
			if (!isset($_POST['txt_remit']) || strlen($_POST['txt_remit']) == 0)
			{
				MsjErrores('Debe Ingresar el nombre del remitente.');
			}
			elseif(strlen($_POST['txt_remit']) < 5)
			{
				MsjErrores('Nombre del Remitente debe tener m&aacute;s de 5 caracteres.');
			}
			// esta validacion deberia ser conjuntamente con los nombres
			elseif(strlen($_POST['txt_remit']) > 50)
			{
				MsjErrores('Nombre del Remitente no debe tener m&aacute;s de 50 caracteres.');
			}
			else
			{
				$nom_completo_remit = str_replace("\xF1", "\xD1", $_POST['txt_remit']);
				$nom_completo_remit = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_remit)))));
			}
			/*if (!isset($_POST['txt_remit_dni']) || strlen($_POST['txt_remit_dni']) == 0)
			{
				MsjErrores('Ingrese D.N.I. del Remitente.');
			}
			else
			{
				if (!is_numeric($_POST['txt_remit_dni']))
				{
					MsjErrores('D.N.I. debe ser numerico.');
				}
				elseif(strlen($_POST['txt_remit_dni']) < 8 && $_POST['txt_remit_dni'] > 8)
				{
					MsjErrores('D.N.I. debe tener 8 caracteres.');
				}
				else
				{*/
					if (strlen($_POST['txt_remit_dni'])==0)
					{
						$dni_consig = NULL;
					}
					else
					{
						$dni_remit = $_POST['txt_remit_dni'];
					}
					
				/*}
			}*/
		}
		// VERIFICAMOS SI EL COSIGNATARIO ESTA REGISTRADO
		if (isset($_POST['txt_consig_ID']) && strlen($_POST['txt_consig_ID']) > 0)
		{
			$esta_regist_consig = 1;
			$id_consig = $_POST['txt_consig_ID'];
		}
		else
		{
			$esta_regist_consig = 0;
			// VERIFICAMOS SI SE INGRESO LOS DATOS DEL COSIGNATARIO
			if (!isset($_POST['txt_consig']) || strlen($_POST['txt_consig']) == 0)
			{
				MsjErrores('Debe Ingresar el nombre del Consignatario.');
			}
			elseif(strlen($_POST['txt_consig']) < 5)
			{
				MsjErrores('Nombre del Consignatario debe tener m&aacute;s de 5 caracteres.');
			}
			// esta validacion deberia ser conjuntamente con los nombres
			elseif(strlen($_POST['txt_consig']) > 50)
			{
				MsjErrores('Nombre del Consignatario no debe tener m&aacute;s de 50 caracteres.');
			}
			else
			{
				$nom_completo_consig = str_replace("\xF1", "\xD1", $_POST['txt_consig']);
				$nom_completo_consig = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_consig)))));
			}
			// No es necesario verificar el DNI del consignatario, este será registrado cuando se entregue el giro.
			if (isset($_POST['txt_consig_dni']))
			{
				if (strlen($_POST['txt_consig_dni'])==0)
				{
					$dni_consig = NULL;
				}
				else
				{
					$dni_consig = $_POST['txt_consig_dni'];
				}
			}
		}
		// VALIDACIONES PARA EL TIPO DE MONEDA
		/*if (isset($_POST['cmb_tipo_moneda']))
		{
			$tipo_moneda = $_POST['cmb_tipo_moneda'];
		}
		else
		{
			MsjErrores('Debe seleccionar el tipo de moneda.');
		}*/
		// VALIDACIONES PARA EL MONTO DEL GIRO
		if (!isset($_POST['txt_monto']) || strlen($_POST['txt_monto']) == 0)
		{
			MsjErrores('Debe ingresar un monto para realizar el Giro.');
		}
		else
		{
			if (is_numeric($_POST['txt_monto']))
			{
				$monto_giro = $_POST['txt_monto'];
				if ($monto_giro < 5)
				{
					MsjErrores('El monto m&iacute;nimo para realizar un giro es de S/. 5 (CINCO CON 00/100 NUEVOS SOLES).');
				}
				elseif($monto_giro > 9000)
				{
					MsjErrores('El monto m&aacute;ximo para realizar un giro es de S/. 9000 (NUEVE MIL CON 00/100 NUEVOS SOLES).');
				}
				$monto_giro_letras = trim($_POST['txt_letras_monto']);
			}
			else
			{
				MsjErrores('Monto del giro debe ser numerico.');
			}
		}
		
		// VALIDACIONES PARA EL FLETE DEL GIRO
		if (!isset($_POST['txt_flete']) || strlen($_POST['txt_flete']) == 0)
		{
			MsjErrores('Debe ingresar el flete para realizar el Giro.');
		}
		else
		{
			if (is_numeric($_POST['txt_flete']))
			{
				$flete = $_POST['txt_flete'];
				if ($flete < 1)
				{
					MsjErrores('El monto m&iacute;nimo del flete para realizar un giro es de S/. 1 (UN NUEVO SOL).');
				}
				elseif($flete > round(($monto_giro * 0.20)*100)/100 && $monto_giro >= 10)
				{
					MsjErrores('El monto m&aacute;ximo para el flete es del 10% del Monto del Giro.');
				}
				$flete_letras = trim($_POST['txt_letras_flete']);
			}
			else
			{
				MsjErrores('Flete del giro debe ser numerico.');
			}
		}
		// VALIDACIONES PARA LAS OBSERVACIONES
		/*if(isset($_POST['opc_observ']))
		{
			if ($_POST['opc_observ'] == 'Otro')
			{*/
				if (!isset($_POST['txt_observacion']) || strlen($_POST['txt_observacion']) == 0)
				{
					MsjErrores('Debe especificar observaciones.');
				}
				else
				{
					$observacion = str_replace("\xF1", "\xD1", $_POST['txt_observacion']);
					$observacion = 'RECOGERÁ ' .utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($observacion)))));
				}
			/*}
			else
			{
				$observacion = (utf8_encode('RECOGERÁ ') .strtoupper($_POST['opc_observ']));
			}
		}
		else
		{
			MsjErrores('Verifique Observaci&oacute;n');
		}*/
		// VALIDACIONES PARA LA CLAVE DE SEGURIDAD
		if (!isset($_POST['txt_clave']) || strlen($_POST['txt_clave']) == 0)
		{
			MsjErrores('Debe ingresar la Clave de Seguridad del Giro.');
		}
		else
		{
			esNumerico($_POST['txt_clave'], 'Clave de Seguridad');
			$clave = md5($_POST['txt_clave']);
		}
	}
	elseif (isset($_GET['update']))
	{
		// VALIDACIONES PARA EL CASO DE UN UPDATE
	}
	// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
	$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();
	
	// PROCEDIMIENTO PARA INGRESAR LOS DATOS SI NO HAY ERRORES
	if ($Error == false)
	{
		// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
		require_once 'cnn/config_master.php';
		if (isset($_GET['insert']))
		{
			// PROCEDIMIENTO PARA INSERTAR LOS DATOS EN LAS TABLAS
			// SI EL REMITENTE NO ESTA REGISTRADO
			if ($esta_regist_remit == 0)
			{
			
				// VERIFICAMOS QUE EL REMITENTE REALMENTE NO ESTE REGISTRADO EN LA BD
				$db_giro->query("SELECT count(`g_persona`.`id_persona`) as 'esta_registrado'
								FROM `g_persona`
								WHERE `g_persona`.`per_ape_nom` = UPPER('".$nom_completo_remit."');");
				
				$esta_regist_remit = $db_giro->get('esta_registrado');
				if ($esta_regist_remit == 0)
				{
					// REGISTRAMOS EL REMITENTE Y OBTENEMOS SU ID
					// insertamos los datos
					$db_giro->query("INSERT INTO `g_persona` (`per_ape_nom`, `per_num_dni`, `per_fecha_registro`, `per_hora_registro`, `id_usuario`, `id_oficina`, `per_estado`) 
									VALUES (UPPER('".$nom_completo_remit."'), '".$dni_remit."', '".$fecha_giro->format("Y-m-d")."', '".$hora_giro."', ".$_SESSION['ID_USUARIO'].", ".$_SESSION['ID_OFICINA'].", 1);");
					if (!$db_giro)
					{
						MsjErrores('Error al insertar los datos del Remitente.');
					}
					
				}
				// OBTENEMOS EL ID DEL USUARIO RECIEN REGISTRADO
				$db_giro->query("SELECT `g_persona`.`id_persona` as 'ID'
								FROM `g_persona`
								WHERE `g_persona`.`per_ape_nom` = UPPER('".$nom_completo_remit."');");
				$id_remit = $db_giro->get("ID");
			}
			// SI EL CONSIGNATARIO NO ESTA REGISTRADO
			if ($esta_regist_consig == 0)
			{
				// VERIFICAMOS QUE EL CONSIGNATARIO REALMENTE NO ESTE REGISTRADO EN LA BD
				$db_giro->query("SELECT count(`g_persona`.`id_persona`) as 'esta_registrado'
								FROM `g_persona`
								WHERE `g_persona`.`per_ape_nom` = UPPER('".$nom_completo_consig."');");
				
				$esta_regist_consig = $db_giro->get('esta_registrado');
				if ($esta_regist_consig == 0)
				{
					// REGISTRAMOS AL CONSIGNATARIO EN LA BD
					$db_giro->query("INSERT INTO `g_persona` (`per_ape_nom`, `per_num_dni`, `per_fecha_registro`, `per_hora_registro`, `id_usuario`, `id_oficina`, `per_estado`) 
									VALUES (UPPER('".$nom_completo_consig."'), '".$dni_consig."', '".$fecha_giro->format("Y-m-d")."', '".$hora_giro."', ".$_SESSION['ID_USUARIO'].", ".$_SESSION['ID_OFICINA'].", 1);");
					if (!$db_giro)
					{
						MsjErrores('Error al insertar los datos del Consignatario.');
					}
				}
				// OBTENEMOS EL ID DEL USUARIO RECIEN REGISTRADO
				$db_giro->query("SELECT `g_persona`.`id_persona` as 'ID'
								FROM `g_persona`
								WHERE `g_persona`.`per_ape_nom` = UPPER('".$nom_completo_consig."');");
				$id_consig = $db_giro->get("ID");
			}
			if ($id_consig == $id_remit)
			{
				MsjErrores('No es posible enviar un giro a la misma persona.');
			}
			if ($Error == false)
			{
				// VERIFICAMOS EL EL NUMERO DE DOCUMENTO NO FUE INGRESADO AUN
				$db_giro->query("SELECT count(`g_movimiento`.`id_movimiento`) as 'EXISTEN'
								FROM `g_movimiento`
								WHERE `g_movimiento`.`id_num_doc` = ".$id_documento."
								AND `g_movimiento`.`num_serie` = ".$doc_serie."
								AND `g_movimiento`.`num_documento` = ".$doc_numero.";");
				$ya_existe = $db_giro->get('EXISTEN');
				if ($ya_existe == 0)
				{
					// PROCESO PARA REGISTRAR EL MOVIMIENTO
					$sql = "INSERT INTO `g_movimiento` (
					`id_usuario`
					, `id_oficina_origen`
					, `id_oficina_destino`
					, `id_remitente`
					, `id_consignatario`
					, `fecha_emision`
					, `hora_emision`
					, `id_num_doc`
					, `num_serie`
					, `num_documento`
					, `tipo_moneda`
					, `monto_giro`
					, `monto_giro_letras`
					, `flete_giro`
					, `monto_flete_letras`
					, `forma_entrega`
					, `fecha_registro`
					, `esta_cancelado`
					, `esta_anulado`
					, `esta_impreso`
					, `otra_agencia`
					, `nom_pc_ip`
					, `mov_clave`";
					if (isset($_SESSION['TIPO_USUARIO']) && ($_SESSION['TIPO_USUARIO'] == 3))
					{
						$sql = $sql .", `de_administracion`, `autorizado`";
					}
					$sql = $sql .")
					VALUES (
					".$id_usuario."
					, ".$id_agen_origen."
					, ".$id_agen_destino."
					, ".$id_remit."
					, ".$id_consig."
					, '".$fecha_giro->format("Y-m-d")."'
					, '".$hora_giro."'
					, ".$id_documento."
					, ".$doc_serie."
					, ".$doc_numero."
					, ".$tipo_moneda."
					, '".$monto_giro."'
					, '".$monto_giro_letras."'
					, '".$flete."'
					, '".$flete_letras."'
					, UPPER('".$observacion."')
					, '".date("Y-m-d")."'
					, 0
					, 0
					, 0
					,1
					, '".$pc_nom_ip."'
					, '".$clave."'";
					if (isset($_SESSION['TIPO_USUARIO']) && ($_SESSION['TIPO_USUARIO'] == 3))
					{
						$sql = $sql .", 1, 0";
					}
					$sql = $sql .");";
					$db_giro->query($sql);
					if (!$db_giro)
					{
						MsjErrores('Error al Registrar el giro, intentelo otra vez o consulte con el administrador si el problema persiste.');
					}
					else
					{
						// ACTUALIZAMOS EL CONTADOR DE NUMERO ACTUAL DE BOLETO EN LA BASE DE DATOS BDTRANSPORTENUEVO 
						if (isset($_SESSION['TIPO_USUARIO']) && ($_SESSION['TIPO_USUARIO'] == 1))
						{
							// PROCESO PARA REGISTRAR EL MOVIMIENTO
							$db_transporte->query("UPDATE `numeracion_documento` SET `numero_actual`= ".$doc_numero." WHERE `numeracion_documento`.`id`='".$id_documento."';");
							if (!$db_transporte)
							{
								MsjErrores('Error: no se puedo actualizar el n&uacute;mero del documento');
							}
						}
						// OBTENEMOS EL ID DEL MOVIMIENTO REGISTRADO PARA LA IMPRESION
						$db_giro->query("SELECT `g_movimiento`.`id_movimiento` AS 'ID'
										FROM `g_movimiento`
										WHERE `g_movimiento`.`id_usuario` = ".$id_usuario."
										AND `g_movimiento`.`id_oficina_origen` = ".$id_agen_origen."
										AND `g_movimiento`.`id_oficina_destino` = ".$id_agen_destino."
										AND `g_movimiento`.`id_remitente` = ".$id_remit."
										AND `g_movimiento`.`id_consignatario` = ".$id_consig."
										AND `g_movimiento`.`num_serie` = ".$doc_serie."
										AND `g_movimiento`.`num_documento` = ".$doc_numero.";");
						$id_movimiento = $db_giro->get('ID');
						// VERIFICAMOS SI NO HAY UN REGISTRO CON ESTE ID
						
						// REGISTRAMOS QUIEN ESTA HACIENDO LA DERIVACION 
						$db_giro->query("SELECT COUNT(`g_operacion`.`id_movimiento`) AS `id_movimiento`
										FROM `g_operacion`
										WHERE `g_operacion`.`id_movimiento` = ".$id_movimiento."
										AND `g_operacion`.`ope_tipo_operacion` = 1;");
						$existe_ope = $db_giro->get("id_movimiento");
						if ($existe_ope == 0)
						{
							// INGRESAMOS EL REGISTRO
							$db_giro->query("INSERT INTO `g_operacion`
											(`id_movimiento`, `ope_tipo_operacion`, `id_oficina`, `id_usuario`, `ope_fecha`, `ope_hora`, `ope_detalle`, `nom_pc_ip`)
											VALUES
											(".$id_movimiento.", 1,".$_SESSION['ID_OFICINA'].", ".$_SESSION['ID_USUARIO'].", '".$fecha_giro->format("Y-m-d")."', '".$hora_giro."', 'Derivación de GIRO', '".$pc_nom_ip."');");
						}
						else
						{
							// SOLO MOODIFICAMOS EL REGISTRO
							$db_giro->query("UPDATE `g_operacion`
							SET
							`id_oficina` = ".$_SESSION['ID_OFICINA'].",
							`id_usuario` = ".$_SESSION['ID_USUARIO'].",
							`ope_fecha` = '".$fecha_giro->format("Y-m-d")."',
							`ope_hora` = '".$hora_giro."',
							`ope_detalle` = 'Derivación de GIRO'
							`nom_pc_ip` = '".$pc_nom_ip."'
							WHERE `id_movimiento` = ".$id_movimiento."
							AND `ope_tipo_operacion` = 1;");
						}
					}
				}
				else
				{
					MsjErrores('El numero del <span>documento ya esta; registrado!</span><br />Consulte con el administrador por este giro.');
				}
			}
		}
		elseif (isset($_GET['update']))
		{
			// PROCEDIMIENTO PARA ACTUALIZAR LOS REGISTROS
			
		}
	}
	

	
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
<?php
	if ($Error == true)
	{
		echo '<!-- Pagetitle -->';
		echo '<h1 class="pagetitle">Mensaje de Error</h1>';
		echo '<div class="column1-unit">';
	  	echo '<h1>Detalle del o los errores.</h1>';
	  	echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
	  	echo '<p>'.$MsjError.'</p>';
	  	/*<p class="details">| Posted by <a href="#">SiteAdmin </a> | Categories: <a href="#">General</a> | Comments: <a href="#">73</a> |</p>*/
		// BOTONES PARA REGRESAR A LA VENTENA ANTERIOR
?>
	<p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; javascript:history.back(1)" ></p>
<?php
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
	else
	{
		// MOSTRAMOS EL MENSAJE DE OPERACION SATISFACTORIA
		echo '<!-- Pagetitle -->';
		echo '<h1 class="pagetitle">Mensaje de Confirmaci&oacute;n</h1>';
		echo '<div class="column1-unit">';
	  	echo '<h1>Operaci&oacute;n Exitosa.</h1>';
	  	echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
		/*echo 'ID REMITENTE: ' .$id_remit  ."<br />";
		echo 'Remitente: ' .$nom_completo_remit ."<br />";
		echo 'ID Consignatario: ' .$id_consig ."<br />";
		echo 'Consignatario: ' .$nom_completo_consig ."<br />";
		*/
	  	echo '<p>El Giro se registr&oacute; <span>Satisfactoriamente</span>.</p>';
	  	/*<p class="details">| Posted by <a href="#">SiteAdmin </a> | Categories: <a href="#">General</a> | Comments: <a href="#">73</a> |</p>*/
		// BOTONES PARA REGRESAR A LA VENTENA ANTERIOR
?>
		<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Nuevo Giro Derivado" onclick="location.href='g_derivar.php'" style="width:170px;" ></p>
<?PHP
	}
?>
</div>
