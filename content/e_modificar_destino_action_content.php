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
		if (!isset($_POST['txt_id_movimiento']) || strlen($_POST['txt_id_movimiento']) == 0)
		{
			MsjErrores('No se puede MODIFICAR esta Encomienda, intentelo de nuevo.');
		}
		else
		{
			$id_movimiento = $_POST['txt_id_movimiento'];
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
				// VERIFICAMOS SI LA ENCOMIENDA TIENE EL ESTADO ADECUADO
				$db_giro->query("SELECT `id_movimiento`
				, SUM(CASE WHEN `md_estado` > 1
				THEN 1 ELSE 0 END) AS 'CAMBIO'
				FROM `e_mov_detalle`
				WHERE `id_movimiento` = ".$id_movimiento."
				GROUP BY `id_movimiento`");
				$ya_existe = $db_giro->get('CAMBIO');
				if ($ya_existe == 0)
				{
					// PROCESO PARA AUTORIZAR LA ENCOMIENDA
					$db_giro->query("UPDATE `e_movimiento` 
					SET `id_oficina_destino`= ".$id_agen_destino."
					, `esta_copiado` = 0
					WHERE `id_movimiento` = '".$id_movimiento."';");
					if (!$db_giro)
					{
						MsjErrores('Error al Autorizar la Encomienda, intentelo otra vez o consulte con el administrador si el problema persiste.');
					}
					else
					{
						// REGISTRAMOS QUIEN ESTA HACIENDO LA AUTORIZACION 
						$db_giro->query("SELECT 
						COUNT(`e_operacion`.`id_movimiento`) AS `id_movimiento`
						FROM `e_operacion`
						WHERE `e_operacion`.`id_movimiento` = ".$id_movimiento."
						AND `e_operacion`.`ope_tipo` = 4;");
						$existe_ope = $db_giro->get("id_movimiento");
						if ($existe_ope == 0)
						{
							// INGRESAMOS EL REGISTRO
							$db_giro->query("INSERT INTO `e_operacion`
							(`id_movimiento`,
							`ope_tipo`,
							`id_oficina`,
							`id_usuario`,
							`ope_fecha`,
							`ope_hora`,
							`ope_detalle`,
							`nom_pc_ip`)
							VALUES
							(".$id_movimiento.",
							4,
							".$_SESSION['ID_OFICINA'].",
							".$_SESSION['ID_USUARIO'].", 
							'".$fecha_actual->format("Y-m-d")."', 
							'".$hora_actual."',
							'Modificacion de Destino',
							'".$pc_nom_ip."');");
						}
						else
						{
							// SOLO MOODIFICAMOS EL REGISTRO
							$db_giro->query("UPDATE `e_operacion`
							SET
							`id_oficina` = ".$_SESSION['ID_OFICINA'].",
							`id_usuario` = ".$_SESSION['ID_USUARIO'].",
							`ope_fecha` = '".$fecha_actual->format("Y-m-d")."',
							`ope_hora` = '".$hora_actual."',
							`ope_detalle` = 'Actualizacion de Modificacion de Destino',
							`nom_pc_ip` = '".$pc_nom_ip."'
							WHERE `id_movimiento` = ".$id_movimiento."
							AND `ope_tipo` = 4;");
						}
					}
				}
				else
				{
					MsjErrores('Esta Encomienda ya fue enviada, no puder ser modificada.');
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
	<p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; location.href='e_modificar_destino.php'" ></p>
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
	  	echo '<p>El Cambio de Destino se registr&oacute; <span>Satisfactoriamente</span>.</p>';
	  	/*<p class="details">| Posted by <a href="#">SiteAdmin </a> | Categories: <a href="#">General</a> | Comments: <a href="#">73</a> |</p>*/
		// BOTONES PARA REGRESAR A LA VENTENA ANTERIOR
?>
		<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Modificar otro Giro" style="width:150px;" onclick="this.disabled = 'true'; this.value = 'Enviando...';location.href='e_modificar_destino.php';" ></p>
<?php
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
	
?>
</div>
