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
	$fecha_actual = new DateTime(date("Y-m-d"));
	$hora_actual = date('G:i:s');
	$id_agen_origen = 0;
	$id_agen_destino = 0;
	$esta_regist_consig = 0;
	$id_consig = 0;
	$nom_completo_consig = '';
	$observacion = '';
	$pc_nom_ip = '';
	
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	// VALIDACIONES PARA EL CASO DEL INSERT
	if (isset($_GET['insert']))
	{
		// VALIDACIONES PARA EL ID MOVIMIENTO
		if (!isset($_POST['txt_mov_id']) || strlen($_POST['txt_mov_id']) == 0)
		{
			MsjErrores('No se puede Autorizar este Giro, intentelo de nuevo.');
		}
		else
		{
			$id_movimiento = $_POST['txt_mov_id'];
		}
		//VALIDACIONES PARA LA AGENCIA DE DESTINO
		if (!isset($_POST['txt_agencia_origen']) || count($_POST['txt_agencia_origen']) == 0)
		{
			MsjErrores('Error de consulta, intentelo de nuevo.');
		}
		else
		{
			$id_agen_origen = $_POST['txt_agencia_origen'];	
		}
		// LAS AGENCIAS NO DEBEN SER IGUALES
		
		//VALIDACIONES PARA LA AGENCIA DE DESTINO
		if (!isset($_POST['cmb_agencia_destino']) || $_POST['cmb_agencia_destino'] == 0)
		{
			MsjErrores('Seleccione Agencia de Destino.');
		}
		else
		{
			$id_agen_destino = $_POST['cmb_agencia_destino'];	
		}
		// VALIDACIONES PARA LAS OBSERVACIONES
		if (!isset($_POST['txt_observ']) || strlen($_POST['txt_observ']) == 0)
		{
			$observacion = '';
		}
		else
		{
			$observacion = str_replace("\xF1", "\xD1", $_POST['txt_observ']);
			$observacion = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($observacion)))));
		}
	}
	// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
	$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();
	
	// PROCEDIMIENTO PARA INGRESAR LOS DATOS SI NO HAY ERRORES
	if ($Error == false)
	{
		// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
		require_once 'cnn/config_giro.php';
		if (isset($_GET['insert']))
		{
			// PROCEDIMIENTO PARA INSERTAR LOS DATOS EN LAS TABLAS
			if ($Error == false)
			{
				// VERIFICAMOS SI EL GIRO NO HA SIDO CANCELADO
				$db_giro->query("SELECT count(`g_movimiento`.`id_movimiento`) as 'CANCELADO'
								FROM `g_movimiento`
								WHERE `g_movimiento`.`id_movimiento` = ".$id_movimiento."
								AND `g_movimiento`.`esta_cancelado` = 1");
				$ya_existe = $db_giro->get('CANCELADO');
				if ($ya_existe == 0)
				{
					// PROCESO PARA AUTORIZAR EL GIRO
					$db_giro->query("UPDATE `g_movimiento` 
									SET `id_oficina_destino`= ".$id_agen_destino."
									, `esta_copiado` = 0
									WHERE `id_movimiento` = '".$id_movimiento."';");
					if (!$db_giro)
					{
						MsjErrores('Error al Autorizar el giro, intentelo otra vez o consulte con el administrador si el problema persiste.');
					}
					else
					{
						// REGISTRAMOS QUIEN ESTA HACIENDO LA AUTORIZACION 
						$db_giro->query("SELECT COUNT(`g_operacion`.`id_movimiento`) AS `id_movimiento`
										FROM `g_operacion`
										WHERE `g_operacion`.`id_movimiento` = ".$id_movimiento."
										AND `g_operacion`.`ope_tipo_operacion` = 4;");
						$existe_ope = $db_giro->get("id_movimiento");
						if ($existe_ope == 0)
						{
							// INGRESAMOS EL REGISTRO
							$db_giro->query("INSERT INTO `g_operacion`
											(`id_movimiento`, `ope_tipo_operacion`, `id_oficina`, `id_usuario`, `ope_fecha`, `ope_hora`, ope_detalle, `nom_pc_ip`)
											VALUES
											(".$id_movimiento.", 4,".$_SESSION['ID_OFICINA'].", ".$_SESSION['ID_USUARIO'].", '".$fecha_actual->format("Y-m-d")."', '".$hora_actual."','".$observacion."','".$pc_nom_ip."');");
						}
						else
						{
							// SOLO MOODIFICAMOS EL REGISTRO
							$db_giro->query("UPDATE `g_operacion`
							SET
							`id_oficina` = ".$_SESSION['ID_OFICINA'].",
							`id_usuario` = ".$_SESSION['ID_USUARIO'].",
							`ope_fecha` = '".$fecha_actual->format("Y-m-d")."',
							`ope_hora` = '".$hora_actual."',
							`ope_detalle` = '".$observacion."',
							`nom_pc_ip` = '".$pc_nom_ip."'
							WHERE `id_movimiento` = ".$id_movimiento."
							AND `ope_tipo_operacion` = 4;");
						}
					}
				}
				else
				{
					MsjErrores('Este giro ya fue cancelado, no puder ser modificado.');
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
		echo '<!-- Pagetitle -->';
		echo '<h1 class="pagetitle">Mensaje de Error</h1>';
		echo '<div class="column1-unit">';
	  	echo '<h1>Detalle del o los errores.</h1>';
	  	echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
	  	echo '<p>'.$MsjError.'</p>';
	  	/*<p class="details">| Posted by <a href="#">SiteAdmin </a> | Categories: <a href="#">General</a> | Comments: <a href="#">73</a> |</p>*/
		// BOTONES PARA REGRESAR A LA VENTENA ANTERIOR
?>
	<p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; location.href='g_modificar_destino.php'" ></p>
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
	  	echo '<p>La Autorizaci&oacute;n se registr&oacute; <span>Satisfactoriamente</span>.</p>';
	  	/*<p class="details">| Posted by <a href="#">SiteAdmin </a> | Categories: <a href="#">General</a> | Comments: <a href="#">73</a> |</p>*/
		// BOTONES PARA REGRESAR A LA VENTENA ANTERIOR
?>
		<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Modificar otro Giro" style="width:150px;" onclick="this.disabled = 'true'; this.value = 'Enviando...';location.href='g_modificar_destino.php';" ></p>
<?php
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
	
?>
</div>
