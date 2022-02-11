<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	$fecha_inicio = "";

	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	
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
	// Verificamos si el usuario ha iniciado sesión
	// Obtenemos los datos de la tabla PAGES
	if(!isset($_SESSION['OFICINAS']))
	{
		$db_transporte->query("SELECT `of`.`idoficina`, `of`.`oficina`
				FROM `oficinas` as `of`
				ORDER BY `of`.`oficina`;");
		$_SESSION['OFICINAS'] = $db_transporte->get();
	}
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	
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
  
  <title>.::Enc. Recepcionadas::.</title>
  
  <!-- Links para el calendario -->
  <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
  <SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118">
  </script>
  <!-- Links para el calendario -->
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  
	<script type="text/javascript"> 
        function abrir_ventana(URL, fecha, agencia)
        { 
			window.open(URL + '?FECHA=' + fecha+"&AGENCIA=" + agencia,'REPORTE','scrollbars=no, resizable=yes, width=1000, height=600, status=no, location=no, toolbar=no');

        } 
    </script> 
  <!-- Script para validar el navegador -->
  <?php
  	if ($_SESSION['TIPO_USUARIO'] == 1)
	{
		echo '<script language="javascript" src="js/navegador.js"></script>';
	}
  ?>
  
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/validacion_textfield.js"> 
  </script>
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
  
      <!-- START B.1 MAIN CONTENT -->
<?php

	$date = date('d\/m\/Y');
	$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
	$fecha_inicio = new DateTime($date);
	if (isset($_GET['btn_buscar']))
	{
		// DECLARAMOS LAS VARIABLES PARA EL REPORTE
		$ID_USER = $_SESSION['ID_USUARIO'];
		$ID_OFIC = $_SESSION['ID_OFICINA'];
		
		// OBTENEMOS LAS FECHAS
		$WHERE = '';
		// CONDICIONAL PARA LA CONSULTA
		if (isset($_GET['txt_fecha_ini']) && strlen($_GET['txt_fecha_ini']) > 0)
		{
			$date = $_GET['txt_fecha_ini'];
 			$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
			$fecha_inicio = new DateTime($date);
		}
		if (isset($_GET['cmb_agencia_origen']) && $_GET['cmb_agencia_origen'] > 0)
		{
			$WHERE = ' AND `e_movimiento`.`id_oficina_origen` = ' .$_GET['cmb_agencia_origen'];
		}
	}
	// CONEXION CON EL SERVIDOR
	require_once 'cnn/config_giro.php';
			
	// OBTENEMOS LOS DATOS PARA EL REPORTE
	$sql = "SELECT
	`e_movimiento`.`id_oficina_origen`
	, CONCAT(RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4), '-'
	, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA'
	, IF(`e_persona`.`per_tipo` = 'PERSONA',`e_persona`.`per_nombre`, `e_persona`.`per_razon_social`) 
	AS `CONSIGNATARIO`
	, CAST(CONCAT(`e_mov_detalle`.`md_cantidad`
	, ' '
	, `e_mov_detalle`.`md_descripcion`) AS CHAR) AS 'DESCRIPCION'
	, `e_mov_detalle`.`md_estado`
	FROM `e_movimiento`
	INNER JOIN `e_persona`
	ON `e_movimiento`.`id_consignatario` = `e_persona`.`id_persona`
	INNER JOIN `e_mov_detalle`
	ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
	INNER JOIN `e_md_operacion`
	ON `e_md_operacion`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
	AND `e_md_operacion`.`e_num_item` = `e_mov_detalle`.`e_num_item`
	WHERE `e_movimiento`.`id_oficina_destino` = ".$_SESSION['ID_OFICINA']."
	AND `e_md_operacion`.`mdo_fecha` = '".$fecha_inicio->format("Y-m-d")."'
	AND `e_md_operacion`.`tipo_operacion` = 1
	AND `e_mov_detalle`.`md_estado` = 3
	".$WHERE." 
	ORDER BY `e_movimiento`.`id_oficina_origen`
	, `e_movimiento`.`num_serie` ASC
	, `e_movimiento`.`num_documento` ASC;";
	$db_giro->query($sql);
	$E_Entregados = $db_giro->get();
?>
      <!-- B.1 MAIN CONTENT -->
		<div class="main-content">
			<div id="zona-busqueda">
            <!-- Content unit - One column -->
            <h1 class="pagetitle">Reporte Encomiendas Recepcionadas - Zona de Busqueda</h1>
            
            <form method="get" action="rpt_e_recepcionadas.php" name="buscar_reporte" >
                    <table width="100%" border="0">
                        <tr>
                        	<th style="text-align:right;">Fecha:</th>
                            <th><input name="txt_fecha_ini" id="txt_fecha" type="text" value="<?php if(isset($_GET['txt_fecha_ini']) && strlen($_GET['txt_fecha_ini']) > 0) echo $fecha_inicio->format("d/m/Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." style="width:150px;" tabindex="1" onkeypress="return handleEnter(this, event)">
                              <input name="button1" type="button" class="button" style="width:54px;" tabindex="2" onclick="displayCalendar(document.forms[0].txt_fecha_ini,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal"></th>
                          <th>Ag. Origen :</th>
                          <td><select name="cmb_agencia_origen" id="cmb_agencia_origen" class="combo" title="Agenia de origen del giro.">
                            <?php
							if (count($Oficina_Array) == 0)
							{
								echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
							}
							else
							{
								echo '<option value="0" selected="selected">[ TODAS LAS OFICINAS ]</option>';
								for ($fila = 0; $fila < count($Oficina_Array); $fila++)
								{
									if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] == $Oficina_Array[$fila][0])
										echo '<option value="'.$Oficina_Array[$fila][0].'" disabled="disabled"> '.$Oficina_Array[$fila][1].'</option>';
									else
										if ($_GET['cmb_agencia_origen'] == $Oficina_Array[$fila][0])
											echo '<option value="'.$Oficina_Array[$fila][0].'" selected="selected"> '.$Oficina_Array[$fila][1].' </option>';
										else
											echo '<option value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
								}
							}
						 ?>
                          </select></td>
                      </tr>
                        <tr>
                            <th colspan="4" style="text-align:center;">
                                <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="19" /></span>                            </th>
                        </tr>
                    </table> 
            </form>
            
            <!-- Limpiar Unidad del Contenido -->
            <hr class="clear-contentunit" />
            </div>
			<div class="column1-unit">
<?php
	if($Error == TRUE)
	{
		echo '<h1>Error de Consulta.</h1>';
		echo '<h3>'.date("l j \d\e F, Y, h:i A").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
		echo '<p>'.$MsjError.'</p>';
		echo '</div>';
		echo '<hr class="clear-contentunit" />';
	}
	else
	{
?>
      <h1>Reporte Encomiendas Recepcinadas de la Oficina <?php echo $_SESSION['OFICINA']; ?>.</h1>
      <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
      <div class="contactform">
      <!-- PARA MOSTRAR LOS MOVIMIENTOS EN SOLES -->
      <table width="100%" border="0">
        <tr>
            <th title="N&uacute;mero de ">N&uacute;m. Boleta</th>
            <th>Consignatario</th>
            <th style="text-align:center;">Descripci&oacute;n</th>
        </tr>
<?php
		if (count($E_Entregados) > 0)
		{
			$CUR_OFICINA = '';
			for ($fila = 0; $fila < count($E_Entregados); $fila++ )
			{
				$ID_OFICINA_ORIGEN = $E_Entregados[$fila][0];
				$guia = $E_Entregados[$fila][1];
				$consig = utf8_encode($E_Entregados[$fila][2]);
				$DESCRIPCION = utf8_encode($E_Entregados[$fila][3]);
				$NOM_OFICINA = OficinaByID($ID_OFICINA_ORIGEN);
				if ($NOM_OFICINA != $CUR_OFICINA)
				{
					echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" >';
						echo '<td colspan="3" style="text-align:center;font-weight:bold;">'.$NOM_OFICINA.'</td>';
					echo '</tr>';
					$CUR_OFICINA = $NOM_OFICINA;
				}
				echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" >';
					echo '<td>'.$guia.'</td>';
					echo '<td>'.$consig.'</td>';
					echo '<td>'.$DESCRIPCION.'</td>';
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr>';
				echo '<td colspan="8" style="text-align:center;">NO HAY REGISTROS.</td>';
			echo '</tr>';
		}
?>
				</table>
			<!--	<table width="100%" border="0">
				  <tr>
				  	<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>Total Egresos </td>
					<td></td>
				  </tr>
				</table> -->
				<table width="100%" border="0">
                    <tr>
                    <th colspan="2" scope="row" style="text-align:center;"><span>
                      <input type="button" name="btn_print" id="btn_print" class="button" value="Rep. Consignatarios"  title="Reporte para la lista de Encomiendas Recibidas."tabindex="6" onclick="abrir_ventana('print_e_lis_consig.php', '<?php echo $fecha_inicio->format("Y-m-d"); ?>','<?PHP echo $_GET['cmb_agencia_origen']; ?>')" style="width:250px;"/>
                    </span></th>
                    <th colspan="2" scope="row" style="text-align:center;"><span>
                      <input type="button" name="btn_print" id="btn_print" class="button" value="Rep. para el Cuaderno" title="Reporte para el Cuaderno de Registro de Encomiendas" tabindex="7" onclick="abrir_ventana('print_e_lis_recepcionadas.php', '<?php echo $fecha_inicio->format("Y-m-d"); ?>','<?PHP echo $_GET['cmb_agencia_origen']; ?>')" style="width:250px;"/>
                    </span></th>
                  </tr>
                </table>
			  </div>
			
			<!-- Limpiar Unidad del Contenido -->
			<hr class="clear-contentunit" />
<?php

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



