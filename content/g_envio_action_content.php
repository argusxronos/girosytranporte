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
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	
	$id_movimiento = 0;
	$hora_giro = '';
	$id_agen_origen = 0;
	$id_agen_destino = 0;
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
	$clave = 0;
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
			if (isset($_SESSION['TIPO_USUARIO']) && ($_SESSION['TIPO_USUARIO'] != 9))
			{
				if ($fecha_giro < $fecha_actual)
				{
					MsjErrores('La fecha del Giro <span>'.$_POST['txt_fecha'].'</span> debe ser igual o superior al d&iacute;a de Hoy.');
				}
			}
		}
		// OBTENEMOS LA HORA DEL GIRO
		$hora_giro = date('G:i:s');
		
		$hora_giro2 = date('h:i:s A');
		//VALIDACIONES PARA LA AGENCIA DE ORIGEN
		if (!isset($_POST['cmb_agencia_origen']) || $_POST['cmb_agencia_origen'] == 0)
		{
			MsjErrores('Seleccione Agencia de Origen.');
		}
		else
		{
			$id_agen_origen = $_POST['cmb_agencia_origen'];	
		}
		
		//VALIDACIONES PARA LA AGENCIA DE DESTINO
		if (!isset($_POST['cmb_agencia_destino']) || $_POST['cmb_agencia_destino'] == 0)
		{
			MsjErrores('Seleccione Agencia de Destino.');
		}
		else
		{
			$id_agen_destino = $_POST['cmb_agencia_destino'];
			if ($id_agen_destino == $_SESSION['ID_OFICINA'])
			{
				MsjErrores('NO PUEDE ENVIAR UN GIRO A SU PROPIA OFICINA. Rerporte de intento enviado al administrador.');
			}
		}
		if ($id_agen_origen == $id_agen_destino)
		{
			MsjErrores('La Agencia de Origen y la de Destino no pueden ser iguales');
		}
		
		// VALIDACIONES ARA EL TIPO DE DOCUMENTO
		if (!isset($_POST['cmb_documento']) || $_POST['cmb_documento'] == '')
		{
			MsjErrores('Seleccione documento a emitir.');
		}
		else
		{
			$id_documento = $_POST['cmb_documento'];
		}
		
		// VALIDACIONES PARA LA SERIE
		if (!isset($_POST['txt_serie']) || strlen($_POST['txt_serie']) == 0)
		{
			MsjErrores('Consulte con el Administrador sobre los documentaci&oacute;n que puede emitir.');
		}
		else
		{
			esNumerico($_POST['txt_serie'], 'Serie');
			$doc_serie = $_POST['txt_serie'];
		}
		// VALIDACIONES PARA EL NUMERO DEL DOCUMENTO
		if (!isset($_POST['txt_numero']))
		{
			MsjErrores('Ingrese el n&uacute;mero del documento.');
		}
		else
		{
			esNumerico($_POST['txt_numero'], 'N&uacute;mero Boleta');
			$doc_numero = $_POST['txt_numero'];
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
			if (!isset($_POST['txt_remit_dni']) && strlen(trim($_POST['txt_remit_dni'])) > 0)
			{
				/*MsjErrores('Ingrese D.N.I. del Remitente.');
			}
			else
			{*/
				if (!is_numeric($_POST['txt_remit_dni']))
				{
					MsjErrores('D.N.I. Remitente debe ser numerico.');
				}
				elseif(strlen($_POST['txt_remit_dni']) < 8 || strlen($_POST['txt_remit_dni']) > 8)
				{
					MsjErrores('D.N.I. Remitente debe tener 8 caracteres.');
				}
				else
				{
					if (strlen($_POST['txt_remit_dni'])==0)
					{
						$dni_consig = NULL;
					}
					else
					{
						$dni_remit = $_POST['txt_remit_dni'];
					}
				}
			}
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
				if (strlen($_POST['txt_consig_dni']) == 0)
				{
					$dni_consig = NULL;
				}
				else
				{
					if (!is_numeric($_POST['txt_consig_dni']))
					{
						MsjErrores('D.N.I. Consignatario debe ser numerico.');
					}
					elseif(strlen($_POST['txt_consig_dni']) < 8 && $_POST['txt_consig_dni'] > 8)
					{
						MsjErrores('D.N.I. Consignatario debe tener 8 caracteres.');
					}
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
					MsjErrores('Debe especificar observaci&oacute;n.');
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
			$sql = "CALL `USP_G_ENVIO` (
					@vERROR
					, @vMSJ_ERROR
					, @ID_MOVIMINETO
					, ".$id_remit."
					, '".$nom_completo_remit."'
					, '".$dni_remit."'
					, ".$id_consig."
					, '".$nom_completo_consig."'
					, '".$dni_consig."'
					, ".$id_agen_origen ."
					, ".$id_agen_destino."
					, ".$id_documento."
					, ".$doc_serie."
					, ".$doc_numero."
					, ".$tipo_moneda."
					, ".$monto_giro."
					, '".$monto_giro_letras."'
					, ".$flete."
					, '".$flete_letras."'
					, '".$observacion."'
					, '".$clave."'
					, '".$fecha_giro->format("Y-m-d")."'
					, '".$hora_giro."'
					, ".$_SESSION['ID_USUARIO']."
					, '".$pc_nom_ip."')";
			$db_giro->query($sql);
			if (!$db_giro)
			{
				MsjErrores('Error en la transacción, Comuniquese con el Administrador.');
			}
			else
			{
				$db_giro->query("SELECT @vERROR AS `ERROR`, @vMSJ_ERROR AS `MSJ_ERROR`, @ID_MOVIMINETO AS `ID_MOVIMIENTO`;");
				$Error_Array = $db_giro->get();
				$Error = $Error_Array[0][0];
				$MsjError = str_replace("\n", "<br>", $Error_Array[0][1]);
				$id_movimiento = $Error_Array[0][2];
			}
			if ($Error == false)
			{
				$db_transporte->query("UPDATE `numeracion_documento` SET `numero_actual`= ".$doc_numero." WHERE `numeracion_documento`.`id`='".$id_documento."';");
			}
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
		<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Nuevo Giro" onclick="this.disabled = 'true'; this.value = 'Enviando...';location.href='g_envio.php'" ></p>
<?php
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
?>
		<script languaje="javascript">
           window.open('print_boleta.php?ID=<?PHP echo $id_movimiento; ?>','BOLETA','scrollbars=no, resizable=yes, width=1000, height=500, status=no, location=no, toolbar=no');
        </script>        
<?PHP
	}
?>
</div>

