<?php
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
	$codigo = '';
	$tipo_doc = '';
	$hora_giro = '';
	$id_agen_origen = 0;
	$id_agen_destino = 0;
	$id_documento = 0;
	$doc_serie = 0;
	$doc_numero = 0;
	$esta_regist_consig = 0;
	$id_consig = 0;
	$nom_completo_consig = '';
	$dni_consig = 'NULL';
	$consig_direccion = 'NULL';
	$tipo_moneda = 1;
	$pc_nom_ip = '';
	$vTDOC_PRINT = '';
	$vCARRERA = 0;
	$CLAVE = '';
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	// VALIDACIONES PARA EL CASO DEL INSERT
	// OBTENEMOS EL CODIGO DEL LA PAGINA
	if(isset($_POST['txt_codigo']) && strlen($_POST['txt_codigo']) > 0)
	{
		$codigo = $_POST['txt_codigo'];
	}
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
			elseif(strlen($_POST['txt_consig']) < 3)
			{
				MsjErrores('Nombre del Consignatario debe tener m&aacute;s de 5 caracteres.');
			}
			// esta validacion deberia ser conjuntamente con los nombres
			elseif(strlen($_POST['txt_consig']) > 180)
			{
				MsjErrores('Nombre del Consignatario no debe tener m&aacute;s de 50 caracteres.');
			}
			else
			{
				$nom_completo_consig = str_replace("\xF1", "\xD1", $_POST['txt_consig']);
				$nom_completo_consig = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_consig)))));
			}
			// No es necesario verificar el DNI del consignatario, este ser� registrado cuando se entregue el giro.
			if (isset($_POST['txt_consig_dni']))
			{
				if (strlen($_POST['txt_consig_dni']) == 0)
				{
					$dni_consig = 'NULL';
				}
				else
				{
					if (!is_numeric($_POST['txt_consig_dni']))
					{
						MsjErrores('D.N.I. Consignatario debe ser numerico.');
					}
					$dni_consig = "'".$_POST['txt_consig_dni']."'";
				}
			}
		}
		if(isset($_POST['txt_consig_direccion']) && strlen($_POST['txt_consig_direccion']) > 0)
		{
			$consig_direccion = str_replace("\xF1", "\xD1", $_POST['txt_consig_direccion']);
			$consig_direccion = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($consig_direccion)))));
			$consig_direccion = "'".$consig_direccion."'";
		}
		if(isset($_POST['cbox_carrera']))
		{
			$vCARRERA = $_POST['cbox_carrera'];
		}
		$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();
	}
	// PROCEDIMIENTO PARA INGRESAR LOS DATOS SI NO HAY ERRORES
	if ($Error == false)
	{
		// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
		require_once 'cnn/config_master.php';
		if (isset($_GET['insert']))
		{
			// OBTENEMOS EL TIPO DE DOCUMENTO
			$db_transporte->query("SELECT `numeracion_documento`.`descripcion_documento`
								FROM `numeracion_documento`
								WHERE `numeracion_documento`.`id` = '".$id_documento."';");
			$tipo_doc = $db_transporte->get('descripcion_documento');
			
			// PROCEDIMIENTO PARA INSERTAR LOS DATOS EN LAS TABLAS
			$sql = "CALL `USP_ENCOMIEDA_DERIVADA`
					(
						@vERROR,
						@vMSJ_ERROR,
						@vID_MOVIMIENTO,
						@TDOC_PRINT,
						'".$codigo."',
						'".$tipo_doc."',
						".$id_consig .",
						'".$nom_completo_consig."',
						".$id_agen_origen.",
						".$id_agen_destino.",
						".$id_documento.",
						".$doc_serie.",
						".$doc_numero.",
						".$tipo_moneda.",
						'".$fecha_giro->format("Y-m-d")."',
						'".$hora_giro."',
						".$_SESSION['ID_USUARIO'].",
						'".$pc_nom_ip."',
						".$vCARRERA."
					);";
			$db_giro->query($sql);
			if (!$db_giro)
			{
				MsjErrores('Error en la transacci�n, Comuniquese con el Administrador.');
			}
			else
			{
				$db_giro->query("SELECT @vERROR AS `ERROR`, @vMSJ_ERROR AS `MSJ_ERROR`, @vID_MOVIMIENTO AS `ID_MOVIMIENTO`,  @TDOC_PRINT;");
				$Error_Array = $db_giro->get();
				$Error = $Error_Array[0][0];
				$MsjError = str_replace("\n", "<br >", $Error_Array[0][1]);
				$id_movimiento = $Error_Array[0][2];
				$vTDOC_PRINT = $Error_Array[0][3];
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
	  	echo '<p>'.utf8_encode($MsjError).'</p>';
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
	  	echo '<p>La Encomienda se registr&oacute; <span>Satisfactoriamente</span>.</p>';
		// BOTONES PARA REGRESAR A LA VENTENA ANTERIOR
?>
		<p style="text-align:center;"><input type="button" name="btn_regresar" id="btn_regresar" value="Nueva Encomienda" class="button" style="width:200px;" onclick="this.disabled = 'true'; this.value = 'Enviando...';location.href='e_envio.php'" ></p>
        
<?php
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
	if ($Error == false)
	{
?>
		<script languaje="javascript">
			setTimeout ("window.location.href='e_derivada.php'", 1000); 
        </script>
<?php
	}
?>
</div>

