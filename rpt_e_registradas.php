<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// FUNCION PARA LA PAGINACION
	require("function/Paginacion.php");
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
	function UserNombreByID($id_user)
	{
		$Users_Array = $_SESSION['USERS'];
		$UserName = '';
		for ($fila = 0; $fila < count($Users_Array); $fila++)
		{
			if($Users_Array[$fila][0] == $id_user)
			{
				$UserName = utf8_encode($Users_Array[$fila][2]);
				break;
			}
		}
		return $UserName;
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
  
  <title>.::Rep. Enc. Registradas::.</title>
  
  <!-- Links para el calendario -->
  <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
  <SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118">
  </script>
  <!-- Links para el calendario -->
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
	$WHERE = '';
	$date = date('d\/m\/Y');
	$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
	$fecha_inicio = new DateTime($date);
	if (!isset($_GET['txt_fecha_inicio']) && !isset($_GET['txt_fecha_fin']))
	{
		$WHERE = $WHERE ." AND `e_movimiento`.`e_fecha_emision` = '" .$fecha_inicio->format("Y-m-d") ."'";
	}
	if (isset($_GET['btn_buscar']))
	{
		// DECLARAMOS LAS VARIABLES PARA EL REPORTE
		$ID_USER = $_SESSION['ID_USUARIO'];
		$ID_OFIC = $_SESSION['ID_OFICINA'];
		
		$TOTAL = 0;
		$TOTAL_DOLAR = 0;
		
		// OBTENEMOS LAS FECHAS
		$fecha_inicio = "";
		$fecha_fin = "";
		$fecha_inicio = "";
		$fecha_fin = "";
		$WHERE = '';
		
		// CONDICIONAL PARA LA CONSULTA
		if (isset($_GET['txt_fecha_ini']) && strlen($_GET['txt_fecha_ini']) > 0)
		{
			$date = $_GET['txt_fecha_ini'];
 			$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
			$fecha_inicio = new DateTime($date);
		}
		if (isset($_GET['txt_fecha_fin']) && strlen($_GET['txt_fecha_fin']) > 0)
		{
			$date = $_GET['txt_fecha_fin'];
 			$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
			$fecha_fin = new DateTime($date);
		}
		if (strlen($_GET['txt_fecha_fin']) == 0)
		{
			$WHERE = $WHERE ." AND `e_movimiento`.`e_fecha_emision` = '" .$fecha_inicio->format("Y-m-d") ."'";
		}
		if (isset($_GET['txt_fecha_fin']) 
		&& strlen($_GET['txt_fecha_fin']) > 0
		&& strlen($_GET['txt_fecha_ini']) == 0)
		{
			$WHERE = $WHERE ." AND `e_movimiento`.`e_fecha_emision` = '" .$fecha_fin->format("Y-m-d") ."'";
		}
		if 
		(
			strlen($_GET['txt_fecha_fin']) > 0
			&& strlen($_GET['txt_fecha_ini']) > 0
		)
		{
			if ($fecha_inicio > $fecha_fin)
			{
				MsjErrores('Fecha de Inicio debe ser menor a la fecha fin.');
			}
			$WHERE = $WHERE ." AND `e_movimiento`.`e_fecha_emision` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."'";
		}

		if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0)
		{
			$consignatario = utf8_decode(strtoupper(urldecode($_GET['txt_consignatario'])));
			$consignatario = str_replace(" ", "", $consignatario);
			
			$WHERE = $WHERE ." AND (REPLACE(`CONSIG`.`per_nombre`, ' ' , '') LIKE '%" .$consignatario ."%'
			OR REPLACE(`CONSIG`.`per_razon_social`, ' ' , '') LIKE '%" .$consignatario ."%')";
		}
		if (isset($_GET['cbox_misgiros']) && $_GET['cbox_misgiros'] == 1)
		{
			$WHERE = $WHERE ." AND `e_movimiento`.`id_usuario` = " .$_SESSION['ID_USUARIO'];
		}
	}
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 15;
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	// CONEXION CON EL SERVIDOR
	require_once 'cnn/config_giro.php';
			
	// OBTENEMOS LOS DATOS PARA EL REPORTE
	$sql = "SELECT `e_movimiento`.`id_movimiento`
	, DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d-%m-%Y') AS `fecha`
	, TIME_FORMAT(`e_movimiento`.`e_hora_emision`, '%r') AS `hora`

	, CONCAT(RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4)
	, '-'
	, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA'
	, IF(`CONSIG`.`per_tipo` = 'PERSONA', `CONSIG`.`per_nombre`, `CONSIG`.`per_razon_social`)
	AS `CONSIGNATARIO`
	, `e_movimiento`.`id_oficina_destino`
	, `e_movimiento`.`id_usuario`
	, `e_mov_detalle`.`md_estado`
	, `e_movimiento`.`e_documento`

	FROM `e_movimiento`
	INNER JOIN `e_persona` as `CONSIG`
	ON `e_movimiento`.`id_consignatario` = `CONSIG`.`id_persona`
	INNER JOIN `e_mov_detalle`
	ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
	WHERE `e_movimiento`.`id_oficina_origen` = ".$_SESSION['ID_OFICINA']."
	".$WHERE."
	GROUP BY `e_movimiento`.`num_serie`, `e_movimiento`.`num_documento`
	ORDER BY `e_movimiento`.`e_fecha_emision` DESC
	, `e_movimiento`.`num_serie` DESC
	, `e_movimiento`.`num_documento` DESC";
	$sql_rows = "SELECT COUNT(`e_movimiento`.`id_movimiento`) AS 'TOTAL'
	FROM `e_movimiento`
	INNER JOIN `e_persona` as `CONSIG`
	ON `e_movimiento`.`id_consignatario` = `CONSIG`.`id_persona`
	INNER JOIN `e_mov_detalle`
	ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
	WHERE `e_movimiento`.`id_oficina_origen` = ".$_SESSION['ID_OFICINA']."
	".$WHERE."
	ORDER BY `e_movimiento`.`e_fecha_emision` DESC
	, `e_movimiento`.`num_serie` DESC
	, `e_movimiento`.`num_documento` DESC";
	
	$from = $paginacion->getFrom();
	
	if (isset($_GET['btn_buscar']) && $_GET['btn_buscar'] != "")
		$sql = $sql ." LIMIT 30;";
	else
		$sql = $sql ." LIMIT $from, $cantidad;";

	$db_giro->query($sql_rows);
	$totalRows = $db_giro->get('TOTAL');
	
	$db_giro->query($sql);
	$E_Entregados = $db_giro->get();
?>
      <!-- B.1 MAIN CONTENT -->
		<div class="main-content">
			<div id="zona-busqueda">
            <!-- Content unit - One column -->
            <h1 class="pagetitle">Reporte Encomiendas Registradas - Zona de Busqueda</h1>
            
            <form method="get" action="rpt_e_registradas.php" name="buscar_reporte" >
                    <table width="100%" border="0">
                        <tr>
                            <th>Fecha Inicio:</th>
                            <th><input name="txt_fecha_ini" id="txt_fecha" type="text" value="<?php if(isset($_GET['txt_fecha_ini']) && strlen($_GET['txt_fecha_ini']) > 0) echo $fecha_inicio->format("d/m/Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." style="width:150px;" tabindex="1" onkeypress="return handleEnter(this, event)">
                              <input name="button1" type="button" class="button" style="width:54px;" tabindex="2" onclick="displayCalendar(document.forms[0].txt_fecha_ini,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal"></th>
                            <th>Fecha Fin  :</th>
                            <th><input name="txt_fecha_fin" id="txt_fecha2" type="text" value="<?php if(isset($_GET['txt_fecha_fin']) && strlen($_GET['txt_fecha_fin']) > 0) echo $fecha_fin->format("d/m/Y");  ?>" title="Fecha de envio." style="width:150px;" tabindex="3" onkeypress="return handleEnter(this, event)">
                              <input name="button2" type="button" class="button" style="width:54px;" tabindex="4" onclick="displayCalendar(document.forms[0].txt_fecha_fin,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal"></th>
                        </tr>
                        <tr>
                            <th>Consignatario :</th>
                            <th colspan="3" ><input name="txt_consignatario" type="text" style="width:455px; text-transform:uppercase;" value="<?php if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0) echo $_GET['txt_consignatario'] ?>" onkeypress="return acceptletras(this, event);" autocomplete="off" onfocus="this.select();"  /></th>
                        </tr>
                        <tr>
                            <th>Mis Giros :</th>
                            <th colspan="3" ><label><input name="cbox_misgiros" type="checkbox" value="1" <?PHP if (isset($_GET['cbox_misgiros']) && $_GET['cbox_misgiros'] == 1) echo 'checked="checked"' ;?> />
                            Mostrar solo mis Encomiendas.</label></th>
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
      <h1>Reporte Encomiendas Registradas de la Oficina <?php echo $_SESSION['OFICINA']; ?>.</h1>
      <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
      <div class="contactform">
      <!-- PARA MOSTRAR LOS MOVIMIENTOS EN SOLES -->
      <table width="100%" border="0">
        <tr>
            <th title="N&uacute;mero de ">N&uacute;m. Boleta</th>
            <th style="width:70px;" title="Fecha / Hora de Entrega">Fecha/Hora</th>
            <th>Consignatario</th>
            <th title="Agencia Origien donde se registr&oacute; la encomienda.">Usuario</th>
            <th style="width:20px;" title="Usuario que entreg&oacute; la encomienda">Destino</th>
            <th style="width:20px;" title="Estado de la Encomienda">Estado</th>
        </tr>
<?php
		if (count($E_Entregados) > 0)
		{
			for ($fila = 0; $fila < count($E_Entregados); $fila++ )
			{
				$ID_MOVIMIENTO = $E_Entregados[$fila][0];
				$fecha = $E_Entregados[$fila][1] . '<br />' . $E_Entregados[$fila][2];
				$guia = $E_Entregados[$fila][3];
				$consig = utf8_encode($E_Entregados[$fila][4]);
				$ID_OFICINA_ORIGEN = $E_Entregados[$fila][5];
				$ID_USUARIO_ORIGEN = $E_Entregados[$fila][6];
				$NOM_OFICINA = OficinaByID($ID_OFICINA_ORIGEN);
				$USUARIO_ORIGEN = UserByID($ID_USUARIO_ORIGEN);
				$NOMBRE_U_ORIGEN = utf8_encode(UserNombreByID($ID_USUARIO_ORIGEN));
				$ESTADO = $E_Entregados[$fila][7];
				$DOCUMENTO = $E_Entregados[$fila][8];
				$URL = '';
				echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" >';
					echo '<td>'.$guia.'</td>';
					echo '<td>'.$fecha.'</td>';
					echo '<td>'.$consig.'</td>';
					echo '<td><span title="'.$NOMBRE_U_ORIGEN.'">'.$USUARIO_ORIGEN.'</span></td>';
					echo '<td>'.$NOM_OFICINA.'</td>';
					
					switch ($DOCUMENTO)
					{
						case 'BOLETA':
							$URL = 'print_boleta2.php?ID=' .$ID_MOVIMIENTO;
							break;
						case 'FACTURA':
							$URL = 'print_factura.php?ID=' .$ID_MOVIMIENTO;
							break;
						case 'GUIA INTERNA':
							$URL = 'print_guia_interna.php?ID=' .$ID_MOVIMIENTO;
							break;
						case 'GUIA REMISION':
							$URL = 'print_guia_remision.php?ID=' .$ID_MOVIMIENTO;
							break;
					}
					switch ($ESTADO) {
					case 1:
						echo '<td style="text-align:center;"><a href="'.$URL.'"  target="_blank"><img style="margin-left:13px;"  src="img/estados/recibido.png" alt="Estado" title="Encomienda en Almacen." /></a></td>';
						break;
					case 2:
						echo '<td style="text-align:center;"><img style="margin-left:13px;"  src="img/estados/enviado.png" alt="Estado" title="Encomienda entregada al Bus." /></td>';
						break;
					case 3:
						echo '<td style="text-align:center;"><img style="margin-left:13px;"  src="img/estados/pendiente.png" alt="Estado" title="Encomienda en la Agencia de Destino." /></td>';
						break;
					case 4:
						echo '<td style="text-align:center;"><img style="margin-left:13px;"  src="img/estados/entregado.png" alt="Estado" title="Encomienda entregada al consignatario." /></td>';
						break;
					case 8:
						echo '<td style="text-align:center;"><img style="margin-left:13px;"  src="img/estados/sin_estado.png" alt="Estado" title="Esta Encomienda est&aacute; Anulada." /></td>';
						break;
				}
				echo '</tr>';
			}
		}
		else
		{
			echo '<tr>';
				echo '<td colspan="8" style="text-align:center;">NO HAY REGISTROS.</td>';
			echo '</tr>';
		}
		if (!isset($_GET['btn_buscar']))
		{
			echo '<tr>';
				$url = 'rpt_e_registradas.php?';//curPageURL();
				/*if (strlen($_GET['btn_buscar']) > 0)
					$url = $url .'&';
				else
					$url = $url .'?';*/
				$back = "&laquo;Atras";
				$next = "Siguiente&raquo;";
				echo '<th colspan="8" style="text-align:center;">';
				$paginacion->generaPaginacion($totalRows, $back, $next, $url);
				echo '</th>';
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
                    <th colspan="4" scope="row" style="text-align:center;"><span>
                      <input type="button" name="btn_print" id="btn_print" class="button" value="Imprimir Reporte" tabindex="6" onclick="window.print()" style="width:250px;"/>
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



