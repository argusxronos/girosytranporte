<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	//include_once('function/date_add.php');
	$OficinaByID='';
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
  
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/validate_numbers.js"> 
  </script>
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  <title>.::TC Anulaci&oacute;n::.</title>
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

<body <?php if (!isset($_GET['btn_buscar'])) echo 'OnLoad="document.buscar_giro.txt_serie.focus();"'; if(isset($_SESSION['IS_LOGGED'])) echo 'onbeforeunload="ConfirmClose()" onunload="HandleOnClose()"'; ?>>
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
	if (isset($_GET['btn_buscar']))
	{
		// DECLARAMOS LAS VARIABLES PARA LA OPERACION
		$id_oficina = $_SESSION['ID_OFICINA'];
		$doc_serie = '';
		$doc_numero = '';
		$id_mov = 0;
		// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
		$Error = false;
		$MsjError = '';
		// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
		require_once("function/validacion.php");
		// OBTENEMOS LOS DATOS
		if (!isset($_GET['txt_serie']) || strlen($_GET['txt_serie']) == 0)
		{
			MsjErrores('Ingrese el n&uacute;mero de serie.');
		}
		else
		{
			esNumerico($_GET['txt_serie'], 'N&uacute;mero de Serie');
			$doc_serie = intval($_GET['txt_serie']);
		}
		if (!isset($_GET['txt_num_boleta']) || strlen($_GET['txt_num_boleta']) == 0)
		{
			MsjErrores('Ingrese el n&uacute;mero de documento.');
		}
		else
		{
			esNumerico($_GET['txt_num_boleta'], 'N&uacute;mero de Documento');
			$doc_numero = intval($_GET['txt_num_boleta']);
		}
		if ($Error == false)
		{
			// SI NO HAY ERRORES, OBTENEMOS LOS DATOS
			require_once 'cnn/config_giro.php';
			$sql = "SELECT `g_movimiento`.`id_movimiento`, `g_movimiento`.`id_oficina_origen`, `g_movimiento`.`id_oficina_destino`, `REMITENTE`.`per_ape_nom`, 
`CONSIGNATARIO`.`per_ape_nom`, `g_movimiento`.`fecha_emision`, `g_movimiento`.`hora_emision`, `g_movimiento`.`num_serie`,
`g_movimiento`.`num_documento`, CASE `g_movimiento`.`tipo_moneda` 
WHEN 1 THEN 'SOLES'
WHEN 2 THEN 'DOLARES'
END AS `tipo_moneda`, `g_movimiento`.`monto_giro`, `g_movimiento`.`monto_giro_letras`, `g_movimiento`.`flete_giro`, 
`g_movimiento`.`monto_flete_letras`
							FROM `g_movimiento`
							INNER JOIN `g_persona` AS `REMITENTE`
							ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
							INNER JOIN `g_persona` AS `CONSIGNATARIO`
							ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
							WHERE `g_movimiento`.`esta_cancelado` = 0
							AND `g_movimiento`.`esta_anulado` = 0
							AND `g_movimiento`.`num_serie` = '".$doc_serie."'
							AND `g_movimiento`.`num_documento` = '".$doc_numero."'";
			if (isset($_SESSION['TIPO_USUARIO']) && $_SESSION['TIPO_USUARIO'] < 3)
				$sql = $sql ."AND `g_movimiento`.`id_oficina_origen` = '".$_SESSION['ID_OFICINA']."'";
			$sql = $sql ."LIMIT 1;";
			$db_giro->query($sql);
			$Giro_Array = $db_giro->get();
			$id_mov = $Giro_Array[0][0];
			
		}
		
	}
?>
      <!-- START B.1 MAIN CONTENT -->
      <!-- B.1 MAIN CONTENT -->
        <div class="main-content">
<?php
	if (!isset($_GET['btn_buscar']))
	{
?>
            <!-- Pagetitle -->
            <h1 class="pagetitle">Anulaci&oacute;n de Giro.</h1>
        
            <!-- Content unit - One column -->
            <h1 class="block">Zona de Busqueda</h1>
            <form method="get" action="g_anulacion.php" name="buscar_giro" >
                    <table width="100%" border="0">
                        <tr>
                            <th>Serie :</th>
                            <th><input type="text" name="txt_serie" style="width:220px;" value="" onkeypress="return handleEnter(this,event);" tabindex="1" /></th>
                            <th>N&uacute;mero Boleta :</th>
                            <th><input type="text" name="txt_num_boleta" style="width:220px;" value="" onkeypress="return handleEnter(this,event);" tabindex="2" /></th>
                        </tr>
                        <tr>
                            <th colspan="2" style="text-align:right;">
                                <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="3" /></span>
                            </th>
                            <th colspan="2" style="text-align:left; ">
                                <span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="4" style="margin-left:35px;" /></span>
                            </th>
                        </tr>
                        
                    </table>
        
            </form>
            
            <!-- Limpiar Unidad del Contenido -->
            <hr class="clear-contentunit" />
<?php
	}
	else
	{
		if ($Error == true)
		{
					// Mostramos los Errores
					// MOSTRAMOS EL RESULTADO DE LOS ERRORES
					echo '<!-- Pagetitle -->';
					echo '<h1 class="pagetitle">Mensaje de Error</h1>';
					echo '<div class="column1-unit">';
					  echo '<h1>Detalle del o los errores.</h1>   ';                         
					  echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
					  echo '<p>'.$MsjError.'</p>';
?>
					  <p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; javascript:history.back(1)" ></p>
<?PHP
					echo '<!-- Limpiar Unidad del Contenido -->';
					echo '<hr class="clear-contentunit" />';
					
		}
		elseif ($Error == false)
		{
			if (count($Giro_Array) == 0)
			{
						// MOSTRAMOS EL RESULTADO DE LOS ERRORES
						echo '<!-- Pagetitle -->';
						echo '<h1 class="pagetitle">Mensaje de Error</h1>';
						echo '<div class="column1-unit">';
						  echo '<h1>Detalle del o los errores.</h1>   ';                         
						  echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
						  echo '<p>No se encontro detalles del giro.</p>';
				?>
						  <p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; javascript:history.back(1)" ></p>
<?PHP
						echo '<!-- Limpiar Unidad del Contenido -->';
						echo '<hr class="clear-contentunit" />';
			}
			else
			{
					// MOSTRAMOS LOS DATOS DEL GIRO PARA LA CONFIRMACION
?>

            <!-- Pagetitle -->
            <h1 class="pagetitle">Anular Giro</h1>
        
            <h1 class="block">Confirme los datos del giro.</h1>
            <!-- Content unit - One column -->
            <div class="column1-unit">
              <div class="contactform">
                <form name="giro_form" id="anulacion_form" method="post" action="g_anulacion_action.php?insert" class="">
                    <table border="0">
                      <tr>
                        <th>Fecha : </th>
                        <td><?php echo $Giro_Array[0][5]; ?></td>
                        <th>Hora : </th>
                        <td><?php echo $Giro_Array[0][6]; ?></td>
                      </tr>
                      <tr>
                        <th>Agencia :</th>
                        <td><?PHP echo OficinaByID($Giro_Array[0][1]); ?></td>
                        <th>Destino : </th>
                        <td><?PHP echo OficinaByID($Giro_Array[0][2]); ?></td>
                      </tr>
                      <tr id="DivDocumentoSN">
                        <th>Documento : </th>
                        <td>BOLETA</td>
                        <td>SERIE: <span><?php echo $Giro_Array[0][7]; ?></span></td>
                        <td>N&Uacute;MERO: <span><?php echo $Giro_Array[0][8]; ?></span></td>
                      <tr>
                        <th>Remitente : </th>
                        <td colspan="4"><?php echo utf8_encode($Giro_Array[0][3]); ?></td>
                      </tr>
                      <tr>
                        <th>Consignatario : </th>
                        <td colspan="4"><?php echo utf8_encode($Giro_Array[0][4]); ?></td>
                      </tr>
                      <tr>
                        <th>Moneda : </th>
                        <td><?php echo $Giro_Array[0][9]; ?></td>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <th>Monto del Giro: </th>
                        <td><?php echo $Giro_Array[0][10]; ?></td>
                        <td colspan="2"><?php echo $Giro_Array[0][11]; ?></td>
                      </tr>
                      <tr>
                        <th>Flete :</th>
                        <td><?php echo $Giro_Array[0][12]; ?></td>
                        <td colspan="2"><?php echo $Giro_Array[0][13]; ?></td>
                      </tr>
                      <tr>
                        <td colspan="5">&nbsp;</td>
                      </tr>
                      <tr style="height:20px; font-size:80%;">
                        <th>Usuario:</th>
                        <td><span>
                        <?PHP
                            /* MOSTRAMOS EL NOMBRE DEL USURIO QUE REALIZA LA OPERACION */
                            echo strtoupper($_SESSION['USUARIO']);
                        ?>				
                            </span>
                        </td>
                        <th>Agencia : </th>
                        <td><span>
                        <?PHP
                            /* MOSTRAMOS EL NOMBRE DE LA AGENCIA DONDE SE REALIZA LA OPERACION */
                            echo strtoupper($_SESSION['OFICINA']);
                        ?>				
                            </span>
                        </td>
                      </tr>
                      <tr>
                        <th colspan="5"><input name="txt_id_movimiento" type="hidden" value="<?php echo $Giro_Array[0][0]; ?>" readonly="readonly" /></th>
                      </tr>
                      <tr>
                        <th colspan="2" style="text-align:right;" id="132">
                            <span><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Anular" tabindex="19" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.anulacion_form.submit();" /></span>
                        </th>
                        <td colspan="2" style="text-align:left; padding-left:40px;">
                            <span><input type="button" name="btn_regresar" id="btn_regresar" class="button" value="Regresar" tabindex="6" onclick="document.location.href='g_anulacion.php';" /></span>
                        </td>
                      </tr>
                    </table>
                </form>
              </div>              
            </div>
            
            <!-- Limpiar Unidad del Contenido -->
            <hr class="clear-contentunit" />
      
<?php
			}
		}	
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



