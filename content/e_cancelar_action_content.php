<?php
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	// VALIDACIONES PARA EL CASO DEL INSERT
	$ID_LIQUIDACION = 0;
	$ID_USUARIO = $_SESSION['ID_USUARIO'];
	$ID_OFIC = $_SESSION['ID_OFICINA'];
	if (isset($_GET['insert']))
	{
		
		// OBTENEMOS EL CODIGO DE LA PAGINA
		if(isset($_POST['txt_id_liquidacion']) && strlen($_POST['txt_id_liquidacion']) > 0)
		{
			$ID_LIQUIDACION = $_POST['txt_id_liquidacion'];
		}
		// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
		$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();
	}
	// PROCEDIMIENTO PARA INGRESAR LOS DATOS SI NO HAY ERRORES
	if ($Error == false)
	{
		// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
		require_once 'cnn/config_giro.php';
		if (isset($_GET['insert']))
		{
			// PROCEDIMIENTO PARA INSERTAR LOS DATOS EN LAS TABLAS
			$sql = "CALL `USP_E_ANULAR_LIQ`
					(
						@vERROR,
						@vMSJ_ERROR,
						".$ID_LIQUIDACION.",
						".$ID_USUARIO.",
						".$ID_OFIC.",
						'".$pc_nom_ip."');";
			$db_giro->query($sql);
			if (!$db_giro)
			{
				MsjErrores('Error en la transacción, Comuniquese con el Administrador.');
			}
			else
			{
				$db_giro->query("SELECT @vERROR AS `ERROR`, @vMSJ_ERROR AS `MSJ_ERROR`, @vID_MOVIMIENTO AS `ID_MOVIMIENTO`,  @TDOC_PRINT;");
				$Error_Array = $db_giro->get();
				$Error = $Error_Array[0][0];
				$MsjError = str_replace("\n", "<br />", $Error_Array[0][1]);
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
	  	echo '<p>La Anulacion de la Liquidaci&oacute;n se registr&oacute; <span>Satisfactoriamente</span>.</p>';
		// BOTONES PARA REGRESAR A LA VENTENA ANTERIOR
?>
		<p style="text-align:center;"><input type="button" name="btn_regresar" id="btn_regresar" value="Cancelar otro Envio" class="button" style="width:200px;" onclick="this.disabled = 'true'; this.value = 'Enviando...';location.href='e_cancelar.php'" ></p>
<?php
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
?>
</div>

