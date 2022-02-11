<?php
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
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
		$fecha = new DateTime($date);
		if (isset($_SESSION['TIPO_USUARIO']) && ($_SESSION['TIPO_USUARIO'] != 9))
		{
			if ($fecha < $fecha_actual)
			{
				MsjErrores('La fecha de la Encomienda <span>'.$_POST['txt_fecha'].'</span> debe ser igual o superior al d&iacute;a de Hoy.');
			}
		}
	}
	$hora = date('G:i:s');
	$codigo = '';
	$id_agen_origen = 0;
	$id_agen_destino = 0;
	$num_documento = '';
	$chofer = '';
	$pullman = '';
	$comision = 0;
	$list_destino = '';
	if(isset($_POST['txt_codigo']) && strlen($_POST['txt_codigo']) > 0)
	{
		$codigo = $_POST['txt_codigo'];
	}
	else
	{
		MsjErrores('Error, no se puede crear la Liquidaci&oacute;n, vulva a intentarlo.');
	}
	// VALIDACIONES PARA LA AGENCIA DE ORIGEN
	if(isset($_SESSION['ID_OFICINA']))
	{
		$id_agen_origen = $_SESSION['ID_OFICINA'];
	}
	else
	{
		MsjErrores('Error de Usuario, Cierre Sesi&oacute;n e ingrese de nuevo.');
	}
	if(isset($_POST['txt_comision']) && strlen($_POST['txt_comision']) > 0)
	{
		$comision = $_POST['txt_comision'];
	}
	else
	{
		MsjErrores('Ingrese una comisi&oacute;n para crear la liquidaci&oacute;n.');
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
			MsjErrores('NO PUEDE ENVIAR UNA ENCOMIENDA A SU PROPIA OFICINA. Rerporte de intento enviado al administrador.');
		}
	}
	if ($id_agen_origen == $id_agen_destino)
	{
		MsjErrores('La Agencia de Origen y la de Destino no pueden ser iguales');
	}
	// VALIDACIONES PARA EL NUMERO DEL DOCUMENTO
	if (!isset($_POST['txt_num_liquidacion']))
	{
		MsjErrores('Ingrese el n&uacute;mero del documento.');
	}
	else
	{
		esNumerico($_POST['txt_num_liquidacion'], 'N&uacute;mero Liquidaci&oacute;n de Encomienda');
		$num_documento = $_POST['txt_num_liquidacion'];
	}
	// VALIDACIONES PARA EL CHOFER
	if (!isset($_POST['txt_driver']) || strlen($_POST['txt_driver']) == 0)
	{
		MsjErrores('Ingrese el Nombre del Chofer.');
	}
	else
	{
		
		
		ValidacionNombrePersona($_POST['txt_driver'], 'Nombre del Chofer');
		$chofer = str_replace("\xF1", "\xD1", $_POST['txt_driver']);
		$chofer = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($chofer)))));
	}
	// VALIDACIONES PARA EL PULLMAN
	if (!isset($_POST['list_flota']))
	{
		MsjErrores('Ingrese Pullman.');
	}
	else
	{
		$pullman = $_POST['list_flota'];
	}
	$test=$_POST['list_liquidacion'];
	if ($test){
		foreach ($test as $t)
		{
			$list_destino = $list_destino .'('.$t.')';
		}
	}
	else
	{
		MsjErrores('Debe seleccionar agencias para crear la liquidaci&oacute;n.');
	}
	// SI NO HAY ERRORES LLAMAMOS AL PROCEDIMIENTO ALMACENADO
	if ($Error == false)
	{
		// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
		require_once 'cnn/config_master.php';
		// PROCEDIMIENTO PARA INSERTAR LOS DATOS EN LAS TABLAS
		$sql = "CALL `USP_E_INSERT_LIQUIDACION` (
				@vERROR
				, @vMSJ_ERROR
				, @ID_MOVIMINETO
				, '".$codigo."'
				, ".$_SESSION['ID_USUARIO']."
				, ".$id_agen_origen ."
				, ".$id_agen_destino."
				, ".$num_documento."
				, '".$fecha->format("Y-m-d")."'
				, '".$hora."'
				, '".$chofer."'
				, '".$pullman."'
				, '".$list_destino."'
				, ".$comision."
				);";
		$db_giro->query($sql);
		if (!$db_giro)
		{
			MsjErrores('Error en la transacción, Comuniquese con el Administrador.');
		}
		else
		{
			// OBTENEMOS EL ID_MOVIMIENTO PARA EL REPORTE
			$db_giro->query("SELECT @vERROR AS `ERROR`, @vMSJ_ERROR AS `MSJ_ERROR`, @ID_MOVIMINETO AS `ID_MOVIMIENTO`;");
			$Error_Array = $db_giro->get();
			$Error = $Error_Array[0][0];
			$MsjError = str_replace("\n", "<br>", $Error_Array[0][1]);
			$id_movimiento = $Error_Array[0][2];
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
		echo '<p style="text-align:center;"><input class="button" type="button" style="text-align:center;width:300px;" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="this.disabled = \'true\'; this.value = \'Enviando...\';javascript:history.back(1)" ></p>';
		echo '</div>';
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
	else
	{
		echo '<!-- Pagetitle -->';
		echo '<h1 class="pagetitle">Mensaje</h1>';
		echo '<div class="column1-unit">';
	  	echo '<h1>Operaci&oacute;n Exitosa.</h1>';
	  	echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
	  	echo '<p>Liquidaci&oacute;n creada con exito.</p>';
	  	echo '</div>';
		echo '<p style="text-align:center;"><input class="button" type="button" style="text-align:center;width:300px;" class="button"  name="btn_regresar" id="btn_regresar" value="Nueva Liquidaci&oacute;n" onclick="this.disabled = \'true\'; this.value = \'Enviando...\';location.href=\'e_liquidacion.php\'" ></p>';
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
		if ($_POST['cmd_tipo_liq'] == 1)
		{
			// MOSTRAMOS LA LISTA DE IMPRESION EN FORMATO PEQUEÑO
?>
		<script languaje="javascript">
           window.open('print_e_liq_pequenia.php?ID=<?PHP echo $id_movimiento; ?>','BOLETA','scrollbars=no, resizable=yes, width=1000, height=700, status=no, location=no, toolbar=no');
        </script>   
<?php
		}
		else
		{
?>
		<script languaje="javascript">
           window.open('print_e_liq_grande.php?ID=<?PHP echo $id_movimiento; ?>','BOLETA','scrollbars=no, resizable=yes, width=1000, height=500, status=no, location=no, toolbar=no');
        </script>   
<?php
		}
	}
?>
</div>