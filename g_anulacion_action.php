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
		// DECLARAMOS LAS VARIABLES PARA LA OPERACION
		$id_oficina = $_SESSION['ID_OFICINA'];
		$id_mov = 0;
		$fecha_actual = new DateTime(date("Y-m-d"));
		$hora_giro = date('G:i:s');
		// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
		require_once("function/validacion.php");
		// OBTENEMOS LOS DATOS DEL ORDENADOR DONDE SE REALIZO LA OPERACION
		$pc_nom_ip = 'HOST: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']) . " - IP: " . getRealIP();
		
		// OBTENEMOS LOS DATOS
		if (!isset($_POST['txt_id_movimiento']) || strlen($_POST['txt_id_movimiento']) == 0)
		{
			MsjErrores('Se produjo un error al intentar Anular el Giro, intentelo de nuevo.');
		}
		else
		{
			esNumerico($_POST['txt_id_movimiento'], 'N&uacute;mero de Serie');
			$id_mov = $_POST['txt_id_movimiento'];
		}
		
		
		if ($Error == false)
		{
			// SI NO HAY ERRORES, OBTENEMOS LOS DATOS
			require_once 'cnn/config_giro.php';
			
			// VERIFICAMOS SI EL GIRO PERTENECE A ESTA AGENCIA, SI NO ESTA ANULADO YA Y SI NO ESTA CANCELADO
			$db_giro->query("SELECT COUNT(`g_movimiento`.`id_movimiento`) AS 'DISPONIBLE'
							FROM `g_movimiento`
							WHERE `g_movimiento`.`id_oficina_origen` = ".$_SESSION['ID_OFICINA']."
							AND `g_movimiento`.`id_movimiento` = ".$_POST['txt_id_movimiento']."
							AND (`g_movimiento`.`esta_cancelado` = 1
							OR `g_movimiento`.`esta_anulado` = 1);");
			$esta_cancelado = $db_giro->get('DISPONIBLE');
			if ($esta_cancelado > 0)
			{
				MsjErrores('<span>No es posible</span> anular el giro.');
			}
			else
			{
				// VERFICAMOS SI NO ESTA REGISTRADO EN EN G_ENVIO
				$db_giro->query("SELECT COUNT(`g_entrega`.`id_movimiento`) AS `ENTREGADO`
								FROM `g_entrega`
								WHERE `g_entrega`.`id_movimiento` = ".$id_mov.";");
				$esta_cancelado = $db_giro->get('ENTREGADO');
				if ($esta_cancelado > 0)
				{
					MsjErrores('El <span>Giro ya fue cancelado</span>, no es posible anularlo.');
				}
				else
				{
					// VERFICAMOS SI NO ESTA ANULADO EN G_ANULADO
					$db_giro->query("SELECT COUNT(`g_anulado`.`id_movimiento`) AS `ANULADO`
									FROM `g_anulado`
									WHERE `g_anulado`.`id_movimiento` = '".$id_mov."';");
					$esta_anulado = $db_giro->get('ANULADO');
					if ($esta_anulado > 0)
					{
						MsjErrores('El Giro ya <span>fue anulado</span>.');
					}
					else
					{
						// SI NO HUBO PROBLEMAS HASTA ESTE PUNTO, REGISTRAMOS LA ANULACION
						$db_giro->query("INSERT INTO `g_anulado` (`id_movimiento`, `id_oficina`, `id_usuario`, `anu_fecha_anulado`, `anu_hora_anulado`, `nom_pc_id`) 
										VALUES (".$id_mov.", ".$_SESSION['ID_OFICINA'].", ".$_SESSION['ID_USUARIO'].", '".$fecha_actual->format("Y-m-d")."', '".$hora_giro."', '".$pc_nom_ip."');");
						if (!$db_giro)
						{
							MsjErrores('Problemas al registrar la anulaci&oacute;n, intentelo de nuevo.');
						}
						else
						{
							// SI NO HAY ERRORES, ACTUALIZAMOS LOS DATOS DE LA TABLA G_MOVIMIENTO
							$sql = "UPDATE `g_movimiento` SET `esta_anulado`= 1, `monto_giro`='00.00', `flete_giro`='00.00'
											WHERE `g_movimiento`.`id_movimiento`='".$id_mov."'
											AND `g_movimiento`.`esta_cancelado` = 0";
							if (isset($_SESSION['TIPO_USUARIO']) && $_SESSION['TIPO_USUARIO'] < 3)
								$sql = $sql ." AND `g_movimiento`.`id_oficina_origen` = ".$_SESSION['ID_OFICINA'];
							$db_giro->query($sql);
							if (!$db_giro)
							{
								// SI NO SE PUEDO ACTUALIZAR ELIMINAMOS EL REGISTRO DE LA TABLA G_ANULADO
								MsjErrores('No se puedo realizar la anulaci&oacute;n.');
								$db_giro->query("DELETE FROM `g_anulado` 
												WHERE `id_movimiento`='".$id_mov."';");
							}
						}
					}
				}
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
		echo '<h1 class="pagetitle">Mensaje de Error</h1>';
		echo '<div class="column1-unit">';
		echo '<h1>Detalle del o los errores.</h1>';
		echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
		echo '<p>'.$MsjError.'</p>';
		echo '<hr class="clear-contentunit" />';
	}
	else
	{
		echo '<h1 class="pagetitle">Anulaci&oacute;n Exitosa</h1>';
		echo '<div class="column1-unit">';
		echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
		echo '<p>La anulaci&oacute;n fue registrada con exito.</p>';
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



