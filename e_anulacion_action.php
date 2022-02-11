<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="expires" content="3600" />
  <meta name="revisit-after" content="2 days" />
  <meta name="robots" content="index,follow" />
  <meta name="publisher" content="Your publisher infos here ..." />
  <meta name="copyright" content="Your copyright infos here ..." />
  <meta name="author" content="Design: Wolfgang (www.1-2-3-4.info) / Modified: Your Name" />
  <meta name="distribution" content="global" />
  <meta name="description" content="Your page description here ..." />
  <meta name="keywords" content="Your keywords, keywords, keywords, here ..." />
  <!-- Hoja de Estilos -->
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_setup.css" />
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_text.css" />
  <!-- Icono -->
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
  
  <title>.::Turismo Central::.</title>
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  <!-- Script para validar el navegador -->
  <?php
  	if ($_SESSION['TIPO_USUARIO'] == 1)
	{
		echo '<script language="javascript" src="js/navegador.js"></script>';
	}
  ?>
</head>

<!-- Global IE fix to avoid layout crash when single word size wider than column width -->
<!--[if IE]><style type="text/css"> body {word-wrap: break-word;}</style><![endif]-->

<body <?php if(isset($_SESSION['IS_LOGGED'])) echo 'onbeforeunload="ConfirmClose()" onunload="HandleOnClose()"'; ?>>
  <!-- START Main Page Container -->
  <div class="page-container">

   <!-- For alternative headers START PASTE here -->

    <!-- START A. HEADER -->
	<?php include_once('header.php'); ?>
	<!-- END A. HEADER -->

   <!-- For alternative headers END PASTE here -->

    <!-- START B. MAIN -->
    <div class="main">
<?php

	if (isset($_GET['insert']))
	{
		// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
		$Error = false;
		$MsjError = '';
		
		// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
		include_once('function/validacion.php');
		$IDMOVIMIENTO = '';
		$id_agen_origen = 0;
		if(isset($_POST['txt_id_movimiento']) && strlen($_POST['txt_id_movimiento']) > 0)
		{
			$IDMOVIMIENTO = $_POST['txt_id_movimiento'];
		}
		else
		{
			MsjErrores('Error al intentar anular la encomienda, intentelo de nuevo.');
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
		// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
		$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();
		// SI NO HAY ERRORES LLAMAMOS AL PROCEDIMIENTO ALMACENADO
		if ($Error == false)
		{
			// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
			require_once 'cnn/config_giro.php';
			// PROCEDIMIENTO PARA INSERTAR LOS DATOS EN LAS TABLAS
			$sql = "CALL `USP_E_ANULAR_ENCOMIENDA` (
					@vERROR
					, @vMSJ_ERROR
					, ".$IDMOVIMIENTO."
					, ".$_SESSION['ID_USUARIO']."
					, ".$id_agen_origen ."
					, '".$pc_nom_ip."');";
			$db_giro->query($sql);
			if (!$db_giro)
			{
				MsjErrores('Error en la transaccin, Comuniquese con el Administrador.');
			}
			else
			{
				// OBTENEMOS EL ID_MOVIMIENTO PARA EL REPORTE
				$db_giro->query("SELECT @vERROR AS `ERROR`, @vMSJ_ERROR AS `MSJ_ERROR`;");
				$Error_Array = $db_giro->get();
				$Error = $Error_Array[0][0];
				$MsjError = str_replace("\n", "<br>", $Error_Array[0][1]);
			}
		}
	}
?>
      <!-- START B.1 MAIN CONTENT -->
      <!-- B.1 MAIN CONTENT -->
        <div class="main-content">
<?PHP
	if ($Error == true)
	{
		echo '<!-- Pagetitle -->';
		echo '<h1 class="pagetitle">Mensaje de Error</h1>';
		echo '<div class="column1-unit">';
	  	echo '<h1>Detalle del o los errores.</h1>';
	  	echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
	  	echo '<p>'.$MsjError.'</p>';
	  	echo '</div>';
		echo '<p style="text-align:center;"><input class="button" type="button" style="text-align:center;width:220px;" class="button" name="btn_regresar" id="btn_regresar" value="Anular otra Encomienda" onclick="this.disabled = \'true\'; this.value = \'Enviando...\';location.href=\'e_anulacion.php\'" ></p>';

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
	  	echo '<p>Anulaci&oacute;n registrada con exito.</p>';
	  	echo '</div>';
		echo '<p style="text-align:center;"><input class="button" type="button" style="text-align:center;width:220px;" class="button" name="btn_regresar" id="btn_regresar" value="Anular otra Encomienda" onclick="this.disabled = \'true\'; this.value = \'Enviando...\';location.href=\'e_anulacion.php\'" ></p>';
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
?>
      	</div>
	  <!--END B.1 MAIN CONTENT -->
    </div>
	<!-- END B. MAIN -->
      
    <!-- START C. FOOTER AREA -->
    <?php include_once('footer.php'); ?>
	<!-- END C. FOOTER AREA -->
	      
  </div> 
  <!-- END Main Page Container -->
</body>
</html>



