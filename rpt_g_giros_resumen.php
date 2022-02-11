<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// CONEXION CON EL SERVIDOR
	require_once 'cnn/config_master.php';
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	
	// FUNCION PAR ABTENER LOS NOMBRES DE LAS OFICINAS POR SU ID
	function OficinaByID($id_ofic)
	{
		$Ofic_Array = $_SESSION['OFICINAS'];
		$Oficina = '';
		for ($fila = 0; $fila < count($Ofic_Array); $fila++)
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
  
  <title>.::TC Resumen::.</title>
  
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
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	if (isset($_GET['btn_buscar']))
	{
		// DECLARAMOS LAS VARIABLES PARA EL REPORTE
		$id_oficina = "";
		// DECLARAMOS LAS VARIABLES PARA EL REPORTE
		$ID_USER = $_SESSION['ID_USUARIO'];
		$ID_OFIC = $_SESSION['ID_OFICINA'];
		$TOTAL_EMITIDOS = 0;
		$TOTAL_EMITIDOS_DOLAR = 0;
		$TOTAL_MONTO_EMITIDOS = 0;
		$TOTAL_FLETE_EMITIDOS = 0;
		$TOTAL_MONTO_EMITIDOS_DOLAR = 0;
		$TOTAL_FLETE_EMITIDOS_DOLAR = 0;
		$TOTAL_RECIBIDOS = 0;
		$TOTAL_RECIBIDOS_DOLAR = 0;
		
		$TOTAL_MONTO_RECIBIDOS = 0;
		$TOTAL_MONTO_RECIBIDOS_DOLAR = 0;
		
		$TOTAL_CANCELADOS = 0;
		$TOTAL_CANCELADOS_DOLAR = 0;
		$TOTAL_MONTO_CANCELADOS = 0;
		$TOTAL_MONTO_CANCELADOS_DOLAR = 0;
		
		// OBTENEMOS LAS FECHAS
		$fecha_inicio = "";
		$fecha_fin = "";
		
		if (isset($_GET['txt_fecha_ini']) && isset($_GET['txt_fecha_fin']))
		{
			$date = $_GET['txt_fecha_ini'];
 			$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
			$fecha_inicio = new DateTime($date);
			$date = $_GET['txt_fecha_fin'];
 			$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
			$fecha_fin = new DateTime($date);
		}
		
		if ($fecha_inicio > $fecha_fin)
		{
			MsjErrores('Fecha de Inicio debe ser menor a la fecha fin.');
		}
		if ($Error == FALSE)
		{
			// OBTENEMOS LOS DATOS PARA EL REPORTE
			$sql_emitidos_soles = "SELECT `g_movimiento`.`id_oficina_origen`, 
								COUNT(`g_movimiento`.`id_movimiento`) AS `Num_Giros`, 
								SUM(`g_movimiento`.`monto_giro`) AS `REALIZADOS`, 
								SUM(`g_movimiento`.`flete_giro`) AS `TFLETE`
								FROM `g_movimiento`
								WHERE `g_movimiento`.`tipo_moneda` = 1";
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_emitidos_soles = $sql_emitidos_soles ." AND `g_movimiento`.`fecha_emision` = '".$fecha_inicio->format("Y-m-d")."' ";
			else
				$sql_emitidos_soles = $sql_emitidos_soles ." AND `g_movimiento`.`fecha_emision` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
			$sql_emitidos_soles = $sql_emitidos_soles ."AND `g_movimiento`.`esta_anulado` = 0
								GROUP BY `g_movimiento`.`id_oficina_origen`;";
						
			$sql_emitidos_dolar = "SELECT `g_movimiento`.`id_oficina_origen`, 
								COUNT(`g_movimiento`.`id_movimiento`) AS `Num_Giros`, 
								SUM(`g_movimiento`.`monto_giro`) AS `REALIZADOS`, 
								SUM(`g_movimiento`.`flete_giro`) AS `TFLETE`
								FROM `g_movimiento`
								WHERE `g_movimiento`.`tipo_moneda` = 2";
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_emitidos_dolar = $sql_emitidos_dolar ." AND `g_movimiento`.`fecha_emision` = '".$fecha_inicio->format("Y-m-d")."' ";
			else
				$sql_emitidos_dolar = $sql_emitidos_dolar ." AND `g_movimiento`.`fecha_emision` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
				
			$sql_emitidos_dolar = $sql_emitidos_dolar ."AND `g_movimiento`.`esta_anulado` = 0
								GROUP BY `g_movimiento`.`id_oficina_origen`;";
			
			// OBTENEMOS LOS DATOS PARA EL REPORTE
			$sql_cancelados_soles = "SELECT `g_entrega`.`ent_id_oficina`, 
									COUNT(`g_movimiento`.`id_movimiento`) AS `Num_Giros`, 
									SUM(`g_movimiento`.`monto_giro`) AS `PAGADOS`
									FROM `g_movimiento`
									INNER JOIN `g_entrega`
									ON `g_movimiento`.`id_movimiento` = `g_entrega`.`id_movimiento`
									WHERE `g_movimiento`.`tipo_moneda` = 1";
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_cancelados_soles = $sql_cancelados_soles ." AND `g_entrega`.`ent_fecha_entrega` = '".$fecha_inicio->format("Y-m-d")."' ";
			else
				$sql_cancelados_soles = $sql_cancelados_soles ." AND `g_entrega`.`ent_fecha_entrega` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
				
			$sql_cancelados_soles = $sql_cancelados_soles ."AND `g_movimiento`.`esta_anulado` = 0
									AND `g_movimiento`.`esta_cancelado` = 1
									GROUP BY `g_entrega`.`ent_id_oficina`;";
						
			$sql_cancelados_dolar = "SELECT `g_entrega`.`ent_id_oficina`, 
									COUNT(`g_movimiento`.`id_movimiento`) AS `Num_Giros`, 
									SUM(`g_movimiento`.`monto_giro`) AS `PAGADOS`
									FROM `g_movimiento`
									INNER JOIN `g_entrega`
									ON `g_movimiento`.`id_movimiento` = `g_entrega`.`id_movimiento`
									WHERE `g_movimiento`.`tipo_moneda` = 2";
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_cancelados_dolar = $sql_cancelados_dolar ." AND `g_entrega`.`ent_fecha_entrega` = '".$fecha_inicio->format("Y-m-d")."' ";
			else
				$sql_cancelados_dolar = $sql_cancelados_dolar ." AND `g_entrega`.`ent_fecha_entrega` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
				
			$sql_cancelados_dolar = $sql_cancelados_dolar ."AND `g_movimiento`.`esta_anulado` = 0
									AND `g_movimiento`.`esta_cancelado` = 1
									GROUP BY `g_entrega`.`ent_id_oficina`;";
			
			// SENTENCIA PARA OBTENER LOS GIROS RECIBIDOS EN UNA DETERMINADA FECHA
			$sql_recibidos_soles = "SELECT `g_movimiento`.`id_oficina_destino`,
								COUNT(`g_movimiento`.`id_oficina_destino`) AS `RECIBIDOS`,
								SUM(`g_movimiento`.`monto_giro`) as `MONTO`
								FROM `g_movimiento`
								WHERE `g_movimiento`.`tipo_moneda` = 1";
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_recibidos_soles = $sql_recibidos_soles ." AND `g_movimiento`.`fecha_emision` = '".$fecha_inicio->format("Y-m-d")."' ";
			else
				$sql_recibidos_soles = $sql_recibidos_soles ." AND `g_movimiento`.`fecha_emision` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
			
			$sql_recibidos_soles = $sql_recibidos_soles ."AND `g_movimiento`.`esta_anulado` = 0
								AND `g_movimiento`.`autorizado` = 1
								GROUP BY `g_movimiento`.`id_oficina_destino`;";
			
			
			
			
			$sql_recibidos_dolar = "SELECT `g_movimiento`.`id_oficina_destino`,
								COUNT(`g_movimiento`.`id_oficina_destino`) AS `RECIBIDOS`,
								SUM(`g_movimiento`.`monto_giro`) as `MONTO`
								FROM `g_movimiento`
								WHERE `g_movimiento`.`tipo_moneda` = 2";
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_recibidos_dolar = $sql_recibidos_dolar ." AND `g_movimiento`.`fecha_emision` = '".$fecha_inicio->format("Y-m-d")."' ";
			else
				$sql_recibidos_dolar = $sql_recibidos_dolar ." AND `g_movimiento`.`fecha_emision` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
				
			$sql_recibidos_dolar = $sql_recibidos_dolar ."AND `g_movimiento`.`esta_anulado` = 0
								AND `g_movimiento`.`autorizado` = 1
								GROUP BY `g_movimiento`.`id_oficina_destino`;";
			
			
			$db_giro->query($sql_emitidos_soles);
			$emitidos_soles_Array = $db_giro->get();
			
			$db_giro->query($sql_emitidos_dolar);
			$emitidos_dolar_Array = $db_giro->get();
			
			
			
			$db_giro->query($sql_recibidos_soles);
			$recibidos_soles_Array = $db_giro->get();
			
			$db_giro->query($sql_recibidos_dolar);
			$recibidos_dolar_Array = $db_giro->get();
			
			
			$db_giro->query($sql_cancelados_soles);
			$cancelados_soles_Array = $db_giro->get();
			
			$db_giro->query($sql_cancelados_dolar);
			$cancelados_dolar_Array = $db_giro->get();
		}
	}
?>
      <!-- B.1 MAIN CONTENT -->
		<div class="main-content">
            <!-- Pagetitle -->
            <div id="zona-busqueda">
			<!-- Content unit - One column -->
            <h1 class="pagetitle">Resumen Giros - Zona de Busqueda</h1>
            <form method="get" action="rpt_g_giros_resumen.php" name="buscar_reporte" >
                    <table width="100%" border="0">
                        <tr>
                            <th width="150">Fecha Inicio:</th>
                            <th width="260"><input name="txt_fecha_ini" id="txt_fecha" type="text" value="<?php if(isset($_GET['btn_buscar'])) echo $fecha_inicio->format("d/m/Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly="readonly" style="width:150px;" tabindex="1" onkeypress="return handleEnter(this, event)">
                              <input name="button1" type="button" class="button" style="width:54px;" tabindex="2" onclick="displayCalendar(document.forms[0].txt_fecha_ini,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal"></th>
                            <th width="80">Fecha Fin  :</th>
                            <th width="270"><input name="txt_fecha_fin" id="txt_fecha2" type="text" value="<?php if(isset($_GET['btn_buscar'])) echo $fecha_fin->format("d/m/Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly="readonly" style="width:150px;" tabindex="3" onkeypress="return handleEnter(this, event)">
                              <input name="button2" type="button" class="button" style="width:54px;" tabindex="4" onclick="displayCalendar(document.forms[0].txt_fecha_fin,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal"></th>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align:center;">
                                <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="19" /></span>                            </th>
                        </tr>
                    </table> 
            </form>
            
    <!-- Limpiar Unidad del Contenido -->
    </div>
    <hr class="clear-contentunit" />
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
		if (isset($_GET['btn_buscar']))
		{
?>
			
				<h1>Reporte Resumen de Giros.</h1>
<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			  <div class="contactform">
			  	<!-- PARA MOSTRAR LOS MOVIMIENTOS EN SOLES -->
			  	<p style="color:#FF0000;">Movimiento en Soles (S/.) desde <span><?php echo $fecha_inicio->format("d/m/Y"); ?></span> hasta <span><?php echo $fecha_fin->format("d/m/Y"); ?></span></p>
			  	<table width="100%" border="0" style="margin:2.0em 0 0.2em 0px; width:100%;">
				  <tr>
                  	<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th colspan="2" style="text-align:center;">Giros Emitidos</th>
                    <th colspan="2" style="text-align:center;">Giros Recibidos</th>
					<th colspan="2" style="text-align:center;">Giros Pagados</th>
				  </tr>
                  <tr>
                  	<!--<th>#</th>-->
					<th>Agencia</th>
					<th style="text-align:center" title="Giros Emitidos">Emitidos</th>
					<th>Monto<br />(S/.)</th>
					<th>Flete<br />(S/.)</th>
                    <th title="Est&aacute; Pagado?" style="text-align:center;">Recibidos</th>
                    <th>Monto</th>
                    <th title="Giros Pagados" style="text-align:center;">Pagados</th>
					<th style="text-align:right;">Monto<br />(S/.)</th>
				  </tr>
<?php
			
		// BUSCAMOS EL REGISTRO DEACUeRDO AL ID OFICINA EN SOLES
		$id_oficina = '';
		$cont = 0;
		for($fila_of = 0; $fila_of < count($Oficina_Array); $fila_of++)
		{
			$cont++;
			$id_oficina = $Oficina_Array[$fila_of][0];
			$encontrado_emitidos = false;
			$encontrado_cancelados = false;
			$encontrado_recibidos = false;
			for($fila =0; $fila < count($emitidos_soles_Array); $fila++)
			{
				if($emitidos_soles_Array[$fila][0] == $id_oficina)
				{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
						/*echo '<td>'.$cont.'</td>';*/
						echo '<td>'.OficinaByID($id_oficina).'</td>';
						echo '<td style="text-align:center">'.$emitidos_soles_Array[$fila][1].'</td>';
						echo '<td style="text-align:right">'.$emitidos_soles_Array[$fila][2].'</td>';
						echo '<td style="text-align:right">'.$emitidos_soles_Array[$fila][3].'</td>';
						$TOTAL_EMITIDOS = $TOTAL_EMITIDOS + $emitidos_soles_Array[$fila][1];
						$TOTAL_MONTO_EMITIDOS = $TOTAL_MONTO_EMITIDOS + $emitidos_soles_Array[$fila][2];
						$TOTAL_FLETE_EMITIDOS = $TOTAL_FLETE_EMITIDOS + $emitidos_soles_Array[$fila][3];
					$encontrado_emitidos = true;
					break;
				}
			}
			
			
			
			for($fila =0; $fila < count($recibidos_soles_Array); $fila++)
			{
				if($recibidos_soles_Array[$fila][0] == $id_oficina)
				{
					if ($encontrado_emitidos == false)
					{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
							/*echo '<td>'.$cont.'</td>';*/
							echo '<td>'.OficinaByID($id_oficina).'</td>';
							echo '<td style="text-align:center">0</td>';
							echo '<td style="text-align:right">0.00</td>';
							echo '<td style="text-align:right">0.00</td>';
						$encontrado_emitidos = true;
					}
					echo '<td style="text-align:center">'.$recibidos_soles_Array[$fila][1].'</td>';
					echo '<td style="text-align:right">'.$recibidos_soles_Array[$fila][2].'</td>';
					$TOTAL_RECIBIDOS = $TOTAL_RECIBIDOS + $recibidos_soles_Array[$fila][1];
					$TOTAL_MONTO_RECIBIDOS = $TOTAL_MONTO_RECIBIDOS + $recibidos_soles_Array[$fila][2];
					$encontrado_recibidos = true;
					break;
				}
			}
			
			
			/*if ($encontrado_emitidos == false)
			{
				echo '<tr>';
					echo '<td>'.$cont.'</td>';
					echo '<td>'.OficinaByID($id_oficina).'</td>';
					echo '<td>0</td>';
					echo '<td>0.00</td>';
					echo '<td>0.00</td>';
			}*/
			for ($fila = 0; $fila < count($cancelados_soles_Array); $fila++)
			{
				if ($cancelados_soles_Array[$fila][0] == $id_oficina)
				{
					if ($encontrado_emitidos == false)
					{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
							/*echo '<td>'.$cont.'</td>';*/
							echo '<td>'.OficinaByID($id_oficina).'</td>';
							echo '<td style="text-align:center">0</td>';
							echo '<td style="text-align:right">0.00</td>';
							echo '<td style="text-align:right">0.00</td>';
					}
					if($encontrado_recibidos == false)
					{
						echo '<td style="text-align:center">0</td>';
						echo '<td style="text-align:right">0.00</td>';
						$encontrado_recibidos = true;
					}
						echo '<td style="text-align:center">'.$cancelados_soles_Array[$fila][1].'</td>';
						echo '<td style="text-align:right">'.$cancelados_soles_Array[$fila][2].'</td>';
					echo '</tr>';
					$TOTAL_CANCELADOS = $TOTAL_CANCELADOS + $cancelados_soles_Array[$fila][1];
					$TOTAL_MONTO_CANCELADOS = $TOTAL_MONTO_CANCELADOS + $cancelados_soles_Array[$fila][2];
					$encontrado_cancelados = true;
					break;
				}
			}
			if ($encontrado_emitidos == true)
			{
				if($encontrado_recibidos == false)
				{
					echo '<td style="text-align:center">0</td>';
					echo '<td style="text-align:right">0.00</td>';
				}
				if ($encontrado_cancelados == false)
				{
						echo '<td style="text-align:center">0</td>';
						echo '<td style="text-align:right">0.00</td>';
					echo '</tr>';
				}
			}
		}
?>
				  <tr>
					<!--<td>&nbsp;</td>-->
					<td style="text-align:right; font-weight:bold;">SUB-TOTALES : S/.</td>
                    <td style="text-align:center"><?php echo $TOTAL_EMITIDOS; ?></td>
					<td style="text-align:right; font-weight:bold;"><?PHP echo number_format ($TOTAL_MONTO_EMITIDOS,2); ?></td>
                    <td style="text-align:right; font-weight:bold;"><?PHP echo number_format ($TOTAL_FLETE_EMITIDOS,2); ?></td>
                    <td style="text-align:center;"><?PHP echo $TOTAL_RECIBIDOS; ?></td>
                    <td style="text-align:center; font-weight:bold;"><?PHP echo number_format($TOTAL_MONTO_RECIBIDOS,2); ?></td>
                    <td style="text-align:center;"><?PHP echo $TOTAL_CANCELADOS; ?></td>
                    <td style="text-align:right; font-weight:bold;"><?PHP echo number_format($TOTAL_MONTO_CANCELADOS,2); ?></td>
				  </tr>
				</table>
<?php
			if (count($cancelados_dolar_Array) > 0 || count($emitidos_dolar_Array) > 0 || count($recibos_dolar_Array) > 0)
			{
						echo '<br />';
						echo '<!-- PARA MOSTRAR LOS MOVIMIENTOS EN DOLARES -->';
						echo '<p style="color:#FF0000;">Movimiento en Dolares ($) desde <span>'.$fecha_inicio->format("d/m/Y").'</span> hasta <span>'.$fecha_fin->format("d/m/Y").'</span></p>';
						echo '<table width="100%" border="0" style="margin:2.0em 0 0.2em 0px; width:100%;">';
						   echo '<tr>';
							/*echo '<th>&nbsp;</th>';*/ 
							echo '<th>&nbsp;</th>';
							echo '<th colspan="3" style="text-align:center;">Rigos Emitidos</th>';
							echo '<th colspan="2" style="text-align:center;">Giros Recibidos</th>';
							echo '<th colspan="2" style="text-align:center;">Giros Pagados</th>';
						  echo '</tr>';
						  echo '<tr>';
							/*echo '<th>#</th>';*/
							echo '<th>Agencia</th>';
							echo '<th title="Giros Emitidos" style="text-align:center;">Emit</th>';
							echo '<th>Monto<br />($)</th>';
							echo '<th>Flete<br />($)</th>';
							echo '<th title = "Giros Recibidos">Recibidos</th>';
							echo '<th style="text-align:center;">Monto</th>';
							echo '<th title="Giros Pagados" style="text-align:center;">Pag</th>';
							echo '<th style="text-align:right;">Monto<br />($)</th>';
						  echo '</tr>';
						// BUSCAMOS EL REGISTRO DEACUeRDO AL ID OFICINA EN DOLARES
						$id_oficina = '';
						$cont = 0;
						for($fila_of = 0; $fila_of < count($Oficina_Array); $fila_of++)
						{
							$cont++;
							$id_oficina = $Oficina_Array[$fila_of][0];
							$encontrado_emitidos = false;
							$encontrado_cancelados = false;
							$encontrado_recibidos = false;
							for($fila =0; $fila < count($emitidos_dolar_Array); $fila++)
							{
								if($emitidos_dolar_Array[$fila][0] == $id_oficina)
								{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
										/*echo '<td>'.$cont.'</td>';*/
										echo '<td>'.OficinaByID($id_oficina).'</td>';
										echo '<td style="text-align:center">'.$emitidos_dolar_Array[$fila][1].'</td>';
										echo '<td style="text-align:right">'.$emitidos_dolar_Array[$fila][2].'</td>';
										echo '<td style="text-align:right">'.$emitidos_dolar_Array[$fila][3].'</td>';
										$TOTAL_EMITIDOS_DOLAR = $TOTAL_EMITIDOS_DOLAR + $emitidos_dolar_Array[$fila][1];
										$TOTAL_MONTO_EMITIDOS_DOLAR = $TOTAL_MONTO_EMITIDOS_DOLAR + $emitidos_dolar_Array[$fila][2];
										$TOTAL_FLETE_EMITIDOS_DOLAR = $TOTAL_FLETE_EMITIDOS_DOLAR + $emitidos_dolar_Array[$fila][3];
									$encontrado_emitidos = true;
									break;
								}
							}
							
							for($fila =0; $fila < count($recibidos_dolar_Array); $fila++)
							{
								if($recibidos_dolar_Array[$fila][0] == $id_oficina)
								{
									if ($encontrado_emitidos == false)
									{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
											/*echo '<td>'.$cont.'</td>';*/
											echo '<td>'.OficinaByID($id_oficina).'</td>';
											echo '<td style="text-align:center">0</td>';
											echo '<td style="text-align:right">0.00</td>';
											echo '<td style="text-align:right">0.00</td>';
										$encontrado_emitidos = true;
									}
									echo '<td style="text-align:center">'.$recibidos_dolar_Array[$fila][1].'</td>';
									echo '<td style="text-align:right">'.$recibidos_dolar_Array[$fila][2].'</td>';
									$TOTAL_RECIBIDOS_DOLAR = $TOTAL_RECIBIDOS_DOLAR + $recibidos_dolar_Array[$fila][1];
									$TOTAL_MONTO_RECIBIDOS_DOLAR = $TOTAL_MONTO_RECIBIDOS_DOLAR + $recibidos_dolar_Array[$fila][2];
									$encontrado_recibidos = true;
									break;
								}
							}
							
							/*if ($encontrado_emitidos == false)
							{
								echo '<tr>';
									echo '<td>'.$cont.'</td>';
									echo '<td>'.OficinaByID($id_oficina).'</td>';
									echo '<td>0</td>';
									echo '<td>0.00</td>';
									echo '<td>0.00</td>';
							}*/
							for ($fila = 0; $fila < count($cancelados_dolar_Array); $fila++)
							{
								if ($cancelados_dolar_Array[$fila][0] == $id_oficina)
								{
									if ($encontrado_emitidos == false)
									{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
										/*echo '<td>'.$cont.'</td>';*/
										echo '<td>'.OficinaByID($id_oficina).'</td>';
										echo '<td style="text-align:center">0</td>';
										echo '<td style="text-align:right">0.00</td>';
										echo '<td style="text-align:right">0.00</td>';
										$encontrado_emitidos = true;
									}
									if($encontrado_recibidos == false)
									{
										echo '<td style="text-align:center">0</td>';
										echo '<td style="text-align:right">0.00</td>';
										$encontrado_recibidos = true;
									}
										echo '<td style="text-align:center">'.$cancelados_dolar_Array[$fila][1].'</td>';
										echo '<td style="text-align:right">'.$cancelados_dolar_Array[$fila][2].'</td>';
									echo '</tr>';
									$TOTAL_CANCELADOS_DOLAR = $TOTAL_CANCELADOS_DOLAR + $cancelados_dolar_Array[$fila][1];
									$TOTAL_MONTO_CANCELADOS_DOLAR = $TOTAL_MONTO_CANCELADOS_DOLAR + $cancelados_dolar_Array[$fila][2];
									$encontrado_cancelados = true;
									break;
								}
							}
							if ($encontrado_emitidos == true)
							{
								if($encontrado_recibidos == false)
								{
									echo '<td style="text-align:center">0</td>';
									echo '<td style="text-align:right">0.00</td>';
									$encontrado_recibidos = TRUE;
								}
								if ($encontrado_cancelados == false)
								{
										echo '<td style="text-align:center">0</td>';
										echo '<td style="text-align:right">0.00</td>';
									echo '</tr>';
								}
							}
						}

						 echo '<tr>';
							/*echo '<td>&nbsp;</td>';*/
							echo '<td style="text-align:right; font-weight:bold;">SUB-TOTALES : S/.</td>';
							echo '<td style="text-align:center">'.$TOTAL_EMITIDOS_DOLAR.'</td>';
							echo '<td style="text-align:right; font-weight:bold;">'.number_format ($TOTAL_MONTO_EMITIDOS_DOLAR,2).'</td>';
							echo '<td style="text-align:right; font-weight:bold;">'.number_format ($TOTAL_FLETE_EMITIDOS_DOLAR,2).'</td>';
							echo '<td style="text-align:center;">'.$TOTAL_RECIBIDOS_DOLAR.'</td>';
							echo '<td style="text-align:right; font-weight:bold;">'.$TOTAL_MONTO_RECIBIDOS_DOLAR.'</td>';
							echo '<td style="text-align:center;">'.$TOTAL_CANCELADOS_DOLAR.'</td>';
							echo '<td style="text-align:right; font-weight:bold;">'.number_format ($TOTAL_MONTO_CANCELADOS_DOLAR,2).'</td>';
						  echo '</tr>';
						  
					echo '</table>';
			}
?>
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