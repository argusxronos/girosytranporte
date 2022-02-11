<?php
	/***********************************************/
	/* INICIAMOS EL PROCESO DE VALIDACION DE DATOS */
	/***********************************************/
	
	// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
	require_once 'cnn/config_giro.php';
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	$id_mov = 0;
	$id_usuario = $_SESSION['ID_USUARIO'];
	$id_oficina = $_SESSION['ID_OFICINA'];
	// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
	$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " .getRealIP();
	// OBTENEMOS LOS DATOS
	if (!isset($_POST['txt_id_movimiento']) && strlen($_POST['txt_id_movimiento']) == 0)
	{
		MsjErrores('Error en el C&oacute;digo, intentelo de nuevo.');
	}
	else
	{
		$id_mov = $_POST['txt_id_movimiento'];
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
		// YA QUE NO HAY UNA DIRECCION REGISTRADA, ACTUALIZAMOS LOS DATOS
		$db_giro->query("CALL USP_E_ENTREGA (
		@vERROR,
		@vMSJ_ERROR,
		".$id_mov.",
		".$id_usuario.",
		".$id_oficina.",
		'".$pc_nom_ip."',
		'".$clave."'
		);");
		if (!$db_giro)
		{
			MsjErrores('Problemas con la actualizaci&oacute;n de los registros, Intentelo de Nuevo');
		}
		else
		{
			$db_giro->query("SELECT @vERROR AS `ERROR`, @vMSJ_ERROR AS `MSJ_ERROR`;");
			$Error_Array = $db_giro->get();
			$Error = $Error_Array[0][0];
			$MsjError = str_replace("\n", "<br>", $Error_Array[0][1]);
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
		  echo '<p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = \'true\'; this.value = \'Enviando...\'; javascript:history.back(1)" ></p>';
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
	else
	{
		// MOSTRAMOS EL RESULTADO DE LOS ERRORES
		echo '<!-- Pagetitle -->';
		echo '<h1 class="pagetitle">Mensaje</h1>';
		echo '<div class="column1-unit">';
		  echo '<h1>Operaci&oacute;n Exitosa.</h1>   ';                         
		  echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
		  echo '<p>La Entrega se Registr&oacute; Correctamente.	</p>';
		  echo '<p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = \'true\'; this.value = \'Enviando...\'; document.location.href=\'e_entrega.php\';" ></p>';
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
?>  
</div>