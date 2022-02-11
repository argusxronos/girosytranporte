<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
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
  
  <title>.::Rep. Giros Pagados::.</title>
  
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
		// SI NO HAY ERRORES, OBTENEMOS LOS DATOS
		require_once 'cnn/config_giro.php';
				
		// OBTENEMOS LOS DATOS PARA EL REPORTE
		$sql_soles = "SELECT `g_entrega`.`ent_num_vale`, DATE_FORMAT(`g_entrega`.`ent_fecha_entrega`,'%d-%m-%Y') as `ent_fecha_entrega`, 
	TIME_FORMAT(`g_entrega`.`ent_hora_entrega`, '%r') AS `ent_hora_entrega`,
	CONCAT(RIGHT(CONCAT('000', CAST(`g_movimiento`.`num_serie` AS CHAR)), 4), '-', 
	RIGHT(CONCAT('00000000',CAST(`g_movimiento`.`num_documento` AS CHAR)),8)  ) AS 'NUM_BOLETO',
	`g_persona`.`per_ape_nom`, `g_movimiento`.`monto_giro`, `g_entrega`.`ent_id_usuario`
						FROM `g_movimiento`
						INNER JOIN `g_entrega`
						ON `g_movimiento`.`id_movimiento` = `g_entrega`.`id_movimiento`
						INNER JOIN `g_persona`
						ON `g_movimiento`.`id_consignatario` = `g_persona`.`id_persona`
						WHERE  `g_entrega`.`ent_id_oficina` = ".$_SESSION['ID_OFICINA'];
			if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0)
			{
				$nom_completo_consig = str_replace("\xF1", "\xD1", $_GET['txt_consignatario']);
				$nom_completo_consig = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_consig)))));
				$sql_soles = $sql_soles ." AND `g_persona`.`per_ape_nom` LIKE '%".$nom_completo_consig."%' ";
			}
			if (isset($_GET['cbox_misgiros']) && $_GET['cbox_misgiros'] == 1)
			{
				$sql_soles = $sql_soles ." AND `g_movimiento`.`id_usuario` = '".$_SESSION['ID_USUARIO']."' ";
			}
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_soles = $sql_soles ." AND `g_entrega`.`ent_fecha_entrega` = '".$fecha_inicio->format("Y-m-d")."'";
			else
				$sql_soles = $sql_soles ." AND `g_entrega`.`ent_fecha_entrega` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
			$sql_soles = $sql_soles ." AND `g_movimiento`.`tipo_moneda` = 1";
		
		$sql_soles = $sql_soles ." ORDER BY `g_entrega`.`ent_fecha_entrega` DESC, `g_entrega`.`ent_hora_entrega` DESC;";
		
		
		$sql_dolar = "SELECT `g_entrega`.`ent_num_vale`, DATE_FORMAT(`g_entrega`.`ent_fecha_entrega`,'%d-%m-%Y') as `ent_fecha_entrega`, 
	TIME_FORMAT(`g_entrega`.`ent_hora_entrega`, '%r') AS `ent_hora_entrega`,
	CONCAT(RIGHT(CONCAT('000', CAST(`g_movimiento`.`num_serie` AS CHAR)), 3), '-', 
	RIGHT(CONCAT('00000000',CAST(`g_movimiento`.`num_documento` AS CHAR)),8)  ) AS 'NUM_BOLETO',
	`g_persona`.`per_ape_nom`, `g_movimiento`.`monto_giro`, `g_entrega`.`ent_id_usuario`
					FROM `g_movimiento`
					INNER JOIN `g_entrega`
					ON `g_movimiento`.`id_movimiento` = `g_entrega`.`id_movimiento`
					INNER JOIN `g_persona`
					ON `g_movimiento`.`id_consignatario` = `g_persona`.`id_persona`
					WHERE `g_entrega`.`ent_id_usuario` = ".$_SESSION['ID_USUARIO']."
					AND `g_entrega`.`ent_id_oficina` = ".$_SESSION['ID_OFICINA'];
			if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0)
			{
				$nom_completo_consig = str_replace("\xF1", "\xD1", $_GET['txt_consignatario']);
				$nom_completo_consig = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_consig)))));
				$sql_dolar = $sql_dolar ." AND `g_persona`.`per_ape_nom` LIKE '%".$nom_completo_consig."%' ";
			}
			if (isset($_GET['cbox_misgiros']) && $_GET['cbox_misgiros'] == 1)
			{
				$sql_dolar = $sql_dolar ." AND `g_movimiento`.`id_usuario` = '".$_SESSION['ID_USUARIO']."' ";
			}
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_dolar = $sql_dolar ." AND `g_entrega`.`ent_fecha_entrega` = '".$fecha_inicio->format("Y-m-d")."'";
			else
				$sql_dolar = $sql_dolar ." AND `g_entrega`.`ent_fecha_entrega` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
				$sql_dolar = $sql_dolar ." AND `g_movimiento`.`tipo_moneda` = 2";
		
		$sql_dolar = $sql_dolar ." ORDER BY `g_entrega`.`ent_fecha_entrega` DESC, `g_entrega`.`ent_hora_entrega` DESC;";
		
		$db_giro->query($sql_soles);
		$G_CanceladoSol_Array = $db_giro->get();
		$db_giro->query($sql_dolar);
		$G_CanceladoDolar_Array = $db_giro->get();
	}
?>
      <!-- B.1 MAIN CONTENT -->
		<div class="main-content">
			<div id="zona-busqueda">
            <!-- Content unit - One column -->
            <h1 class="pagetitle">Reporte Giros Cancelados - Zona de Busqueda</h1>
            
            <form method="get" action="rpt_g_cancelados.php" name="buscar_reporte" >
                    <table width="100%" border="0">
                        <tr>
                            <th>Fecha Inicio:</th>
                            <th><input name="txt_fecha_ini" id="txt_fecha" type="text" value="<?php if(isset($_GET['btn_buscar'])) echo $fecha_inicio->format("d-m-Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly="readonly" style="width:150px;" tabindex="1" onkeypress="return handleEnter(this, event)">
                              <input name="button1" type="button" class="button" style="width:54px;" tabindex="2" onclick="displayCalendar(document.forms[0].txt_fecha_ini,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal"></th>
                            <th>Fecha Fin  :</th>
                            <th><input name="txt_fecha_fin" id="txt_fecha2" type="text" value="<?php if(isset($_GET['btn_buscar'])) echo $fecha_fin->format("d-m-Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly="readonly" style="width:150px;" tabindex="3" onkeypress="return handleEnter(this, event)">
                              <input name="button2" type="button" class="button" style="width:54px;" tabindex="4" onclick="displayCalendar(document.forms[0].txt_fecha_fin,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal"></th>
                        </tr>
                        <tr>
                            <th>Consignatario :</th>
                            <th colspan="3" ><input name="txt_consignatario" type="text" style="width:455px; text-transform:uppercase;" value="<?php if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0) echo $_GET['txt_consignatario'] ?>" onkeypress="return acceptletras(this, event);" autocomplete="off" onfocus="this.select();"  /></th>
                        </tr>
                        <tr>
                            <th>Mis Giros :</th>
                            <th colspan="3" ><label><input name="cbox_misgiros" type="checkbox" value="1" <?PHP if (isset($_GET['cbox_misgiros']) && $_GET['cbox_misgiros'] == 1) echo 'checked="checked"' ;?> />
                            Mostrar solo mis Giros.</label></th>
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
		if (isset($_GET['btn_buscar']))
		{
?>
			
				<h1>Reporte Giros Cancelados de la Oficina <?php echo $_SESSION['OFICINA']; ?>.</h1>
			  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			  <div class="contactform">
			  	<!-- PARA MOSTRAR LOS MOVIMIENTOS EN SOLES -->
			  	<p style="color:#FF0000;">Movimiento en Soles (S/.) desde <span><?php echo $fecha_inicio->format("d/m/Y"); ?></span> hasta <span><?php echo $fecha_fin->format("d/m/Y"); ?></span></p>
			  	<table width="100%" border="0">
				  <tr>
					<th>N&deg; Vale </th>
					<th width="60">Fecha</th>
					<th>Hora</th>
					<th>N&deg; Doc. </th>
					<th>Consignatario</th>
                    <th>Pagado por</th>
					<th style="text-align:right;">Monto (S/.)</th>
				  </tr>
<?php
	
		if (count($G_CanceladoSol_Array) > 0)
		{
			for ($fila = 0; $fila < count($G_CanceladoSol_Array); $fila++ )
			{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" >
<?php
						echo '<td>'.$G_CanceladoSol_Array[$fila][0].'</td>';
						echo '<td>'.$G_CanceladoSol_Array[$fila][1].'</td>';
						echo '<td>'.$G_CanceladoSol_Array[$fila][2].'</td>';
						echo '<td>'.$G_CanceladoSol_Array[$fila][3].'</td>';
						echo '<td>'.utf8_encode($G_CanceladoSol_Array[$fila][4]).'</td>';
						echo '<td title="'.UserNombreByID($G_CanceladoSol_Array[$fila][6]).'">'.UserByID($G_CanceladoSol_Array[$fila][6]).'</td>';
						echo '<td style="text-align:right;" >'.$G_CanceladoSol_Array[$fila][5].'</td>';
						$TOTAL = $TOTAL + $G_CanceladoSol_Array[$fila][5];
					echo '</tr>';
			}
		}
		else
		{
						echo '<td colspan="7" style="text-align:center;">NO HAY REGISTROS.</td>';
		}
?>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
                    <td>&nbsp;</td>
					<td style="text-align:right; font-weight:bold;">TOTAL : S/.</td>
					<td style="text-align:right; font-weight:bold;"><?PHP echo number_format ($TOTAL,2); ?></td>
				  </tr>
				</table>
<?php
		if (count($G_CanceladoDolar_Array) > 0)
		{
					echo '<br />';
					echo '<!-- PARA MOSTRAR LOS MOVIMIENTOS EN DOLARES -->';
					echo '<p style="color:#FF0000;">Movimiento en Dolares ($) desde <span>'.$fecha_inicio->format("d/m/Y").'</span> hasta <span>'.$fecha_fin->format("d/m/Y").'</span></p>';
					echo '<table width="100%" border="0">';
					  echo '<tr>';
						echo '<th>N&deg; Vale </th>';
						echo '<th>Fecha</th>';
						echo '<th>Hora</th>';
						echo '<th>N&deg; Doc. </th>';
						echo '<th>Consignatario</th>';
						echo '<th>Pagado por:</th>';
						echo '<th style="text-align:right;">Monto ($)</th>';
					 echo ' </tr>';
	
			for ($fila = 0; $fila < count($G_CanceladoDolar_Array); $fila++ )
			{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" title="Giro cancelado por: <?php echo UserNombreByID($G_CanceladoDolar_Array[$fila][6]);?>">
<?php
						echo '<td>'.$G_CanceladoDolar_Array[$fila][0].'</td>';
						echo '<td>'.$G_CanceladoDolar_Array[$fila][1].'</td>';
						echo '<td>'.$G_CanceladoDolar_Array[$fila][2].'</td>';
						echo '<td>'.$G_CanceladoDolar_Array[$fila][3].'</td>';
						echo '<td>'.utf8_encode($G_CanceladoDolar_Array[$fila][4]).'</td>';
						echo '<td title="'.UserNombreByID($G_CanceladoDolar_Array[$fila][6]).'">'.UserByID($G_CanceladoDolar_Array[$fila][6]).'</td>';
						echo '<td style="text-align:right;">'.$G_CanceladoDolar_Array[$fila][5].'</td>';
						$TOTAL_DOLAR = $TOTAL_DOLAR + $G_CanceladoDolar_Array[$fila][5];
					echo '</tr>';
			}
		
	
					  echo '<tr>';
						echo '<td>&nbsp;</td>';
						echo '<td>&nbsp;</td>';
						echo '<td>&nbsp;</td>';
						echo '<td>&nbsp;</td>';
						echo '<td style="text-align:right; font-weight:bold;">TOTAL : $</td>';
						echo '<td style="text-align:right; font-weight:bold;">'.number_format ($TOTAL_DOLAR,2).'</td>';
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



