<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged_niv2.php");
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
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	if (isset($_GET['btn_buscar']))
	{
		// DECLARAMOS LAS VARIABLES PARA EL REPORTE
		$id_oficina = "";
		
		$TOTAL = 0;
		$TOTAL_DOLAR = 0;
		
		// OBTENEMOS LAS FECHAS
		$fecha_inicio = "";
		$fecha_fin = "";
		
		if(!isset($_GET['cmb_agencia_entrega']) || strlen($_GET['cmb_agencia_entrega']) == 0)
		{
			MsjErrores('Debe seleccionar una Oficina.');
		}
		else
		{
			$id_oficina = $_GET['cmb_agencia_entrega'];
		}
		
		if(!isset($_GET['cmb_agencia_origen']) || strlen($_GET['cmb_agencia_origen']) == 0)
		{
			MsjErrores('Debe seleccionar una Oficina Origen.');
		}
		else
		{
			$id_ofic_origen = $_GET['cmb_agencia_origen'];
		}
		
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
			$sql_soles = "SELECT `g_entrega`.`ent_num_vale`, DATE_FORMAT(`g_entrega`.`ent_fecha_entrega`,'%d-%m-%Y') as `ent_fecha_entrega`, 
		TIME_FORMAT(`g_entrega`.`ent_hora_entrega`, '%r') AS `ent_hora_entrega`,
		CONCAT(RIGHT(CONCAT('000', CAST(`g_movimiento`.`num_serie` AS CHAR)), 4), '-', 
		RIGHT(CONCAT('00000000',CAST(`g_movimiento`.`num_documento` AS CHAR)),8)  ) AS 'NUM_BOLETO',
		`g_persona`.`per_ape_nom`, `g_movimiento`.`monto_giro`, `g_movimiento`.`id_oficina_origen`, `g_entrega`.`ent_id_usuario`
							FROM `g_movimiento`
							INNER JOIN `g_entrega`
							ON `g_movimiento`.`id_movimiento` = `g_entrega`.`id_movimiento`
							INNER JOIN `g_persona`
							ON `g_movimiento`.`id_consignatario` = `g_persona`.`id_persona`
							WHERE `g_entrega`.`ent_id_oficina` = ".$id_oficina;
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_soles = $sql_soles ." AND `g_entrega`.`ent_fecha_entrega` = '".$fecha_inicio->format("Y-m-d")."' ";
			else
				$sql_soles = $sql_soles ." AND `g_entrega`.`ent_fecha_entrega` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
			if (isset($_GET['cmb_agencia_origen']) &&  $_GET['cmb_agencia_origen'] > 0)
				$sql_soles = $sql_soles ." AND `g_movimiento`.`id_oficina_origen` = '".$_GET['cmb_agencia_origen']."' ";
			if (isset($_GET['txt_serie_doc']) && strlen($_GET['txt_serie_doc']) > 0)
			{
				$sql_soles = $sql_soles ." AND `g_movimiento`.`num_serie` = '".$_GET['txt_serie_doc']."'";
			}
			if (isset($_GET['txt_numero_doc']) && strlen($_GET['txt_numero_doc']) > 0)
			{
				$sql_soles = $sql_soles ." AND `g_movimiento`.`num_documento` = '".$_GET['txt_numero_doc']."'";
			}
			if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0)
			{
				$nom_completo_consig = str_replace("\xF1", "\xD1", $_GET['txt_consignatario']);
				$nom_completo_consig = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_consig)))));
				$sql_soles = $sql_soles ." AND `g_persona`.`per_ape_nom` LIKE '%".$nom_completo_consig."%' ";
			}
			$sql_soles = $sql_soles ." AND `g_movimiento`.`tipo_moneda` = 1";
			// ALGUNAS CONDICIONESA MAS
			
			$sql_soles = $sql_soles ." ORDER BY `g_entrega`.`ent_num_vale` DESC;";
			
			
			$sql_dolar = "SELECT `g_entrega`.`ent_num_vale`, DATE_FORMAT(`g_entrega`.`ent_fecha_entrega`,'%d-%m-%Y') as `ent_fecha_entrega`, 
		TIME_FORMAT(`g_entrega`.`ent_hora_entrega`, '%r') AS `ent_hora_entrega`,
		CONCAT(RIGHT(CONCAT('000', CAST(`g_movimiento`.`num_serie` AS CHAR)), 3), '-', 
		RIGHT(CONCAT('00000000',CAST(`g_movimiento`.`num_documento` AS CHAR)),8)  ) AS 'NUM_BOLETO',
		`g_persona`.`per_ape_nom`, `g_movimiento`.`monto_giro`,  `g_movimiento`.`id_oficina_origen`, `g_entrega`.`ent_id_usuario`
						FROM `g_movimiento`
						INNER JOIN `g_entrega`
						ON `g_movimiento`.`id_movimiento` = `g_entrega`.`id_movimiento`
						INNER JOIN `g_persona`
						ON `g_movimiento`.`id_consignatario` = `g_persona`.`id_persona`
						WHERE `g_entrega`.`ent_id_oficina` = ".$id_oficina;
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_dolar = $sql_dolar ." AND `g_movimiento`.`fecha_emision` = '".$fecha_inicio->format("Y-m-d")."' ";
			else
				$sql_dolar = $sql_dolar ." AND `g_movimiento`.`fecha_emision` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
			if (isset($_GET['cmb_agencia_origen']) &&  $_GET['cmb_agencia_origen'] > 0)
				$sql_dolar = $sql_dolar ." AND `g_movimiento`.`id_oficina_origen` = '".$_GET['cmb_agencia_origen']."' ";
			if (isset($_GET['txt_serie_doc']) && strlen($_GET['txt_serie_doc']) > 0)
			{
				$sql_dolar = $sql_dolar ." AND `g_movimiento`.`num_serie` = '".$_GET['txt_serie_doc']."'";
			}
			if (isset($_GET['txt_numero_doc']) && strlen($_GET['txt_numero_doc']) > 0)
			{
				$sql_dolar = $sql_dolar ." AND `g_movimiento`.`num_documento` = '".$_GET['txt_numero_doc']."'";
			}
			if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0)
			{
				$nom_completo_consig = str_replace("\xF1", "\xD1", $_GET['txt_consignatario']);
				$nom_completo_consig = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_consig)))));
				$sql_dolar = $sql_dolar ." AND `g_persona`.`per_ape_nom` LIKE '%".$nom_completo_consig."%' ";
			}
			$sql_dolar = $sql_dolar ." AND `g_movimiento`.`tipo_moneda` = 2";
			// ALGUNAS CONDICIONES MAS
			
			$sql_dolar = $sql_dolar ." ORDER BY `g_movimiento`.`id_oficina_origen`, `g_entrega`.`ent_num_vale` DESC;";
			
			$db_giro->query($sql_soles);
			$G_CanceladoSol_Array = $db_giro->get();
			$db_giro->query($sql_dolar);
			$G_CanceladoDolar_Array = $db_giro->get();
		}
	}
?>
      <!-- B.1 MAIN CONTENT -->
		<div class="main-content">
			<div id="zona-busqueda">
            <!-- Content unit - One column -->
            <h1 class="pagetitle">Reporte Giros Entregados - Zona de Busqueda</h1>
            
            <form method="get" action="rpt_g_giros_entregados.php" name="buscar_giro" >
                <table width="100%" border="0">
                    <tr>
                        <th width="117">Agencia : </th>
                <th width="264"><select name="cmb_agencia_entrega" class="combo" title="Agenia donde se Pag&oacute; del giro." tabindex="3" style="width:220px;" >
                          <?php
                                    if (count($Oficina_Array) == 0)
                                    {
                                        echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
                                    }
                                    else
                                    {
                                        echo '<option value="" selected="selected">[ Seleccione Oficina ]</option>';
                                        for ($fila = 0; $fila < count($Oficina_Array); $fila++)
                                        {
											if (isset($_GET['cmb_agencia_entrega']) && $_GET['cmb_agencia_entrega'] == $Oficina_Array[$fila][0])
											{
												echo '<option selected="selected" value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
											}
											else
                                            	echo '<option value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
                                        }
										echo '<option value="0" >TODOS</option>';
                                    }
                                 ?>
                      </select></th>
                      <th width="100">&nbsp;</th>
                      <th width="281">&nbsp;</th>
                  </tr>
                    <tr>
                    	<th>Fecha Inicio :</th>
                        <td><input name="txt_fecha_ini" id="txt_fecha" type="text" value="<?php if(isset($_GET['btn_buscar'])) echo $fecha_inicio->format("d/m/Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly="readonly" style="width:150px;" tabindex="1" onkeypress="return handleEnter(this, event)">
                        <input name="button1" type="button" class="button" style="width:54px;" tabindex="2" onclick="displayCalendar(document.forms[0].txt_fecha_ini,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal" /></td>
                        <th>Fecha Fin :</th>
                        <td><input name="txt_fecha_fin" id="txt_fecha2" type="text" value="<?php if(isset($_GET['btn_buscar'])) echo $fecha_fin->format("d/m/Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly="readonly" style="width:150px;" tabindex="3" onkeypress="return handleEnter(this, event)">
                        <input name="button2" type="button" class="button" style="width:54px;" tabindex="4" onclick="displayCalendar(document.forms[0].txt_fecha_fin,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal" /></td>
                    </tr>
                    <tr>
                        <th width="117">Ag. Origen  : </th>
                    	<th width="264"><select name="cmb_agencia_origen" class="combo" title="Agenia donde se Pag&oacute; del giro." style="width:220px;" >
                          <?php
                                if (count($Oficina_Array) == 0)
                                {
                                    echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
                                }
                                else
                                {
                                    echo '<option value="0" selected="selected">[ Seleccione Oficina ]</option>';
                                    for ($fila = 0; $fila < count($Oficina_Array); $fila++)
                                    {
                                        if (isset($_GET['cmb_agencia_origen']) && $_GET['cmb_agencia_origen'] == $Oficina_Array[$fila][0])
                                        {
                                            echo '<option selected="selected" value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
                                        }
                                        else
                                            echo '<option value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
                                    }
                                    echo '<option value="0" >TODOS</option>';
                                }
                             ?>
                      </select></th>
                      	<th width="100">Boleta :</th>
                		<th width="281"><input type="text" name="txt_serie_doc" id="txt_serie" style="width:60px;" onfocus="this.select();" title="Serie del Documento." tabindex="5" />
                          <span>-</span>
                      <input type="text" name="txt_numero_doc" id="txt_serie" style="width:100px;" title="N&uacute;mero del Documento." tabindex="6" /></th>
                  	</tr>
                  	<tr>
                        <th>Consignatario :</th>
                        <th colspan="3" ><input name="txt_consignatario" type="text" style="width:455px; text-transform:uppercase;" value="<?php if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0) echo $_GET['txt_consignatario'] ?>" onkeypress="return acceptletras(this, event);" autocomplete="off" onfocus="this.select();"  /></th>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:right;">
                            <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="8" /></span>					</th>
                        <th colspan="2" style="text-align:left; ">
                            <span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="9" style="margin-left:35px;" /></span></th>
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
			
				<h1>Reporte Giros Pagados de la Oficina <?php echo OficinaByID($id_oficina)?>.</h1>
			  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			  <div class="contactform">
			  	<!-- PARA MOSTRAR LOS MOVIMIENTOS EN SOLES -->
			  	<p style="color:#FF0000;">Movimiento en Soles (S/.) desde <span><?php echo $fecha_inicio->format("d/m/Y"); ?></span> hasta <span><?php echo $fecha_fin->format("d/m/Y"); ?></span></p>
			  	<table border="0" style="margin:2.0em 0 0.2em 0px; width:100%;">
				  <tr>
                  	<th style="text-align:center">#</th>
					<th>Fecha / <br />Hora</th>
					<th># Boleta / <br /># Vale</th>
					<th>Consignatario</th>
					<th>Agen. Origen. </th>
					<th>Usuario</th>
					<th style="text-align:right;">Monto (S/.)</th>
				  </tr>
<?php
	
		if (count($G_CanceladoSol_Array) > 0)
		{
			$cont = 1;
			$current_serie = '';
			$sum_serie = 0;
			$sum_flete = 0;
			$sum_carrera = 0;
			for ($fila = 0; $fila < count($G_CanceladoSol_Array); $fila++ )
			{
				$serie = utf8_encode($G_CanceladoSol_Array[$fila][4]);
				$consignatario = utf8_encode($G_CanceladoSol_Array[$fila][4]);
				$fecha = $G_CanceladoSol_Array[$fila][1] .'<br />' .$G_CanceladoSol_Array[$fila][2];
				$boleta = $G_CanceladoSol_Array[$fila][3] .'<br />:<span>' .$G_CanceladoSol_Array[$fila][0] .'</span>';
				$oficina_origen = OficinaByID($G_CanceladoSol_Array[$fila][6]);
				$usuario_login = UserByID($G_CanceladoSol_Array[$fila][7]);
				$usuario_name = UserNombreByID($G_CanceladoSol_Array[$fila][7]);
				$flete = UserNombreByID($G_CanceladoSol_Array[$fila][7]);
				$carrera = UserNombreByID($G_CanceladoSol_Array[$fila][7]);
				
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
					
					echo '<td style="text-align:center">'.$cont.'</td>';
					echo '<td>'.$fecha.'</td>';
					echo '<td>'.$boleta.'</td>';
					echo '<td>'.$consignatario.'</td>';
					echo '<td>'.$oficina_origen.'</td>';
					echo '<td title="'.$usuario_name.'">'.$usuario_login.'</td>';
					echo '<td style="text-align:right;">'.$G_CanceladoSol_Array[$fila][5].'</td>';
					$TOTAL = $TOTAL + $G_CanceladoSol_Array[$fila][5];
				echo '</tr>';
				$cont++;
				if ($current_serie == $serie)
				{
					$sum_flete = $sum_flete + $flete;
					$sum_carrera = $sum_carrera + $carrera;
				}
				else
				{
					echo '<tr>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td>'.$sum_flete.'</td>';
					$sum_flete = 0;
					$sum_carrera = 0;
					$current_serie = $serie;
				}
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
				echo '<table width="100%" border="0" style="margin:2.0em 0 0.2em 0px; width:100%;">';
				  echo '<tr>';
					echo '<tr>';
                  	echo '<th style="text-align:center">#</th>';
					echo '<th>Fecha / <br />Hora</th>';
					echo '<th># Boleta / <br /># Vale</th>';
					echo '<th>Consignatario</th>';
					echo '<th>Agen. Origen. </th>';
					echo '<th>Usuario</th>';
					echo '<th style="text-align:right;">Monto (S/.)</th>';
				  echo '</tr>';
				 echo ' </tr>';
			$cont = 1;
			for ($fila = 0; $fila < count($G_CanceladoDolar_Array); $fila++ )
			{
				$consignatario = utf8_encode($G_CanceladoDolar_Array[$fila][4]);
				$fecha = $G_CanceladoDolar_Array[$fila][1] .'<br />' .$G_CanceladoDolar_Array[$fila][2];
				$boleta = $G_CanceladoDolar_Array[$fila][3] .'<br />con :<span>' .$G_CanceladoDolar_Array[$fila][0] .'</span>';
				$oficina_origen = OficinaByID($G_CanceladoDolar_Array[$fila][6]);
				$usuario_login = ($G_CanceladoDolar_Array[$fila][7]);
				$usuario_name = ($G_CanceladoDolar_Array[$fila][7]);
?>
				<tr title="Giro Anulado" onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
					echo '<td style="text-align:center">'.$cont.'</td>';
					echo '<td>'.$fecha.'</td>';
					echo '<td>'.$boleta.'</td>';
					echo '<td>'.$consignatario.'</td>';
					echo '<td>'.$oficina_origen.'</td>';
					echo '<td title="'.$usuario_name.'">'.$usuario_login.'</td>';
					echo '<td style="text-align:right;">'.$G_CanceladoSol_Array[$fila][5].'</td>';
					$TOTAL_DOLAR = $TOTAL_DOLAR + $G_CanceladoSol_Array[$fila][5];
				echo '</tr>';
				$cont++;
			}
		
	
					  echo '<tr>';
						echo '<td>&nbsp;</td>';
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



