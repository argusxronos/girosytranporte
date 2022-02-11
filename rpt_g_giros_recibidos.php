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
  
  <title>.::Rep. Giros Recibidos::.</title>
  
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
		
		$TOTAL_MONTO = 0;
		$TOTAL_FLETE = 0;
		$TOTAL_MONTO_DOLAR = 0;
		$TOTAL_FLETE_DOLAR = 0;
		
		// OBTENEMOS LAS FECHAS
		$fecha_inicio = "";
		$fecha_fin = "";
		
		if(!isset($_GET['cmb_agencia_entrega']) || strlen($_GET['cmb_agencia_entrega']) == 0)
		{
			MsjErrores('Debe seleccionar la Agencia de Destino.');
		}
		else
		{
			$id_oficina = $_GET['cmb_agencia_entrega'];
		}
		
		if ($Error == FALSE)
		{
			// OBTENEMOS LOS DATOS PARA EL REPORTE
			$sql_soles = "SELECT CONCAT(RIGHT(CONCAT('0000',CAST(`g_movimiento`.`num_serie` AS CHAR)),4), '-',
	RIGHT(CONCAT('00000000', CAST(`g_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA', 
	`g_movimiento`.`fecha_emision`, TIME_FORMAT(`g_movimiento`.`hora_emision`, '%r') AS `hora_emision`, `g_persona`.`per_ape_nom`, `g_movimiento`.`monto_giro`, `g_movimiento`.`flete_giro`, IF(`g_movimiento`.`esta_anulado` = 0,'NO','SI') AS `esta_anulado`, `g_movimiento`.`id_usuario`, IF(`g_movimiento`.`esta_cancelado` = 0,'NO','SI') AS `esta_cancelado`, `g_movimiento`.`id_oficina_origen`
						FROM `g_movimiento`
						INNER JOIN `g_persona`
						ON `g_movimiento`.`id_consignatario` = `g_persona`.`id_persona`
						WHERE `g_movimiento`.`id_oficina_destino` = ".$id_oficina;
			
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
			if (isset($_GET['cmb_estado']) && $_GET['cmb_estado'] > 0)
			{
				if ($_GET['cmb_estado'] == 1)
				{
					$sql_soles = $sql_soles ." AND `g_movimiento`.`esta_cancelado` = 1
					AND `g_movimiento`.`esta_anulado` = 0";
				}
				elseif ($_GET['cmb_estado'] == 2)
				{
					$sql_soles = $sql_soles ." AND `g_movimiento`.`esta_cancelado` = 0
					AND `g_movimiento`.`esta_anulado` = 0";
				}
				elseif ($_GET['cmb_estado'] == 3)
				{
					$sql_soles = $sql_soles ." AND `g_movimiento`.`esta_cancelado` = 0
					AND `g_movimiento`.`esta_anulado` = 1";
				}
				
			}
			else
			{
				$sql_soles = $sql_soles ." AND `g_movimiento`.`esta_cancelado` = 0
				AND `g_movimiento`.`esta_anulado` = 0";
			}
			$sql_soles = $sql_soles ." AND `g_movimiento`.`tipo_moneda` = 1
						ORDER BY 1  desc;";
						
			$sql_dolar = "SELECT CONCAT(RIGHT(CONCAT('0000',CAST(`g_movimiento`.`num_serie` AS CHAR)),4), '-',
	RIGHT(CONCAT('00000000', CAST(`g_movimiento`.`num_documento` AS CHAR)),8)) AS 'NUM_BOLETA', 
	`g_movimiento`.`fecha_emision`, TIME_FORMAT(`g_movimiento`.`hora_emision`, '%r') AS `hora_emision`, `g_persona`.`per_ape_nom`, `g_movimiento`.`monto_giro`, `g_movimiento`.`flete_giro`, IF(`g_movimiento`.`esta_anulado` = 0,'NO','SI') AS `esta_anulado`, `g_movimiento`.`id_usuario`, IF(`g_movimiento`.`esta_cancelado` = 0,'NO','SI') AS `esta_cancelado`, `g_movimiento`.`id_oficina_origen`
						FROM `g_movimiento`
						INNER JOIN `g_persona`
						ON `g_movimiento`.`id_consignatario` = `g_persona`.`id_persona`
						WHERE `g_movimiento`.`id_oficina_destino` = ".$id_oficina;
						
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
			if (isset($_GET['cmb_estado']) && $_GET['cmb_estado'] > 0)
			{
				if ($_GET['cmb_estado'] == 1)
				{
					$sql_dolar = $sql_dolar ." AND `g_movimiento`.`esta_cancelado` = 1";
				}
				elseif ($_GET['cmb_estado'] == 2)
				{
					$sql_dolar = $sql_dolar ." AND `g_movimiento`.`esta_cancelado` = 0";
				}
				elseif ($_GET['cmb_estado'] == 3)
				{
					$sql_dolar = $sql_dolar ." AND `g_movimiento`.`esta_cancelado` = 0
					AND `g_movimiento`.`esta_anulado` = 1";
				}
			}
			$sql_dolar = $sql_dolar ." AND `g_movimiento`.`tipo_moneda` = 2
						ORDER BY `g_movimiento`.`num_documento` desc;";
			
			$db_giro->query($sql_soles);
			$G_CanceladoSol_Array = $db_giro->get();
			$db_giro->query($sql_dolar);
			$G_CanceladoDolar_Array = $db_giro->get();
		}
	}
?>
      <!-- B.1 MAIN CONTENT -->
		<div class="main-content">
        	<!-- Pagetitle -->
			<div id="zona-busqueda">
			<!-- Content unit - One column -->
            <h1 class="pagetitle">Giros pendientes - Zona de Busqueda</h1>
<form method="get" action="rpt_g_giros_recibidos.php" name="buscar_reporte" >
                    <table width="100%" border="0">
                        <tr>
                            <th width="150">Ag. Destino: </th>
                            <th width="240"><select name="cmb_agencia_entrega" class="combo" title="Agenia donde se Pag&oacute; del giro." tabindex="3" style="width:220px;" >
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
                            <th width="80">Estado :</th>
                            <th width="270"><label>
                              <select name="cmb_estado" id="cmb_estado" class="combo" style="width:200px;" disabled="disabled">
                              	<option value="0">TODOS</option>
                                <option value="1">Pagados</option>
                                <option value="2" selected="selected">No Pagados</option>
                                <option value="3">Anulados</option>
                              </select>
                            </label></th>
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
			
				<h1>Reporte Giros  pendientes de la Oficina <?php echo OficinaByID($id_oficina); ?>.</h1>
<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			  <div class="contactform">
			  	<!-- PARA MOSTRAR LOS MOVIMIENTOS EN SOLES -->
			  	<p style="color:#FF0000;">Movimiento en Soles (S/.)</p>
			  	<table width="100%" border="0" style="margin:2.0em 0 0.2em 0px; width:100%;">
				  <tr>
                  	<th>#</th>
					<th style="width:80px;">Fecha</th>
					<th>N&deg; Boleta</th>
					<th>Consignatario</th>
                    <th title="Oficina de Destino">Origen</th>
					<th>Usuario</th>
                    <th title="Est&aacute; Pagado?">Pagado?</th>
					<th style="text-align:right;">Monto<br />(S/.)</th>
					<th style="text-align:right;">Flete<br />(S/.)</th>
				  </tr>
<?php
			if (count($G_CanceladoSol_Array) > 0)
			{
				$cont = 1;
				for ($fila = 0; $fila < count($G_CanceladoSol_Array); $fila++ )
				{
					$fecha = $G_CanceladoSol_Array[$fila][1] .'<br />'.$G_CanceladoSol_Array[$fila][2];
					$boleta = $G_CanceladoSol_Array[$fila][0];
					$consignatario = utf8_encode($G_CanceladoSol_Array[$fila][3]);
					$usuario_login = UserByID($G_CanceladoSol_Array[$fila][7]);
					$usuario_name = UserNombreByID($G_CanceladoSol_Array[$fila][7]);
					$esta_cancelado = $G_CanceladoSol_Array[$fila][8];
					$oficina_destino = OficinaByID($G_CanceladoSol_Array[$fila][9]);
					if ($G_CanceladoSol_Array[$fila][6] == 'SI')
					{
?>
				<tr title="Giro Anulado" onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
							echo '<td style="color:#FF0000;">'.$cont.'</td>';
							echo '<td style="color:#FF0000;">'.$fecha.'</td>';
							echo '<td style="color:#FF0000;">'.$boleta.'</td>';
							echo '<td style="color:#FF0000;">'.$consignatario.'</td>';
							echo '<td style="color:#FF0000;">'.$oficina_destino.'</td>';
							echo '<td style="color:#FF0000;" title = "'.$usuario_name.'">'.$usuario_login.'</td>';
							echo '<td style="color:#FF0000;text-align:center;">'.$esta_cancelado.'</td>';
							echo '<td style="text-align:right;color:#FF0000;">'.$G_CanceladoSol_Array[$fila][4].'</td>';
							echo '<td style="text-align:right;color:#FF0000;">'.$G_CanceladoSol_Array[$fila][5].'</td>';
							$TOTAL_MONTO = $TOTAL_MONTO + $G_CanceladoSol_Array[$fila][4];
							$TOTAL_FLETE = $TOTAL_FLETE + $G_CanceladoSol_Array[$fila][5];
						echo '</tr>';
						$cont++;
					}
					else
					{
						if ($esta_cancelado == 'NO')
						{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
								echo '<td>'.$cont.'</td>';
								echo '<td>'.$fecha.'</td>';
								echo '<td>'.$boleta.'</td>';
								echo '<td>'.$consignatario.'</td>';
								echo '<td>'.$oficina_destino.'</td>';
								echo '<td title = "'.$usuario_name.'">'.$usuario_login.'</td>';
								echo '<td style="text-align:center;">'.$esta_cancelado.'</td>';
								echo '<td style="text-align:right;">'.$G_CanceladoSol_Array[$fila][4].'</td>';
								echo '<td style="text-align:right;">'.$G_CanceladoSol_Array[$fila][5].'</td>';
								$TOTAL_MONTO = $TOTAL_MONTO + $G_CanceladoSol_Array[$fila][4];
								$TOTAL_FLETE = $TOTAL_FLETE + $G_CanceladoSol_Array[$fila][5];
							echo '</tr>';
						}
						else
						{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
								echo '<td>'.$cont.'</td>';
								echo '<td>'.$fecha.'</td>';
								echo '<td>'.$boleta.'</td>';
								echo '<td>'.$consignatario.'</td>';
								echo '<td>'.$oficina_destino.'</td>';
								echo '<td title = "'.$usuario_name.'">'.$usuario_login.'</td>';
								echo '<td style="text-align:center;">'.$esta_cancelado.'</td>';
								echo '<td style="text-align:right;">'.$G_CanceladoSol_Array[$fila][4].'</td>';
								echo '<td style="text-align:right;">'.$G_CanceladoSol_Array[$fila][5].'</td>';
								$TOTAL_MONTO = $TOTAL_MONTO + $G_CanceladoSol_Array[$fila][4];
								$TOTAL_FLETE = $TOTAL_FLETE + $G_CanceladoSol_Array[$fila][5];
							echo '</tr>';
						}
						$cont++;
					}
				}
			}
			else
			{
						echo '<td colspan="9" style="text-align:center;">NO HAY REGISTROS.</td>';
			}
?>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
                    <td>&nbsp;</td>
					<td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="text-align:right; font-weight:bold;">TOTAL : S/.</td>
					<td style="text-align:right; font-weight:bold;"><?PHP echo number_format ($TOTAL_MONTO,2); ?></td>
					<td style="text-align:right; font-weight:bold;"><?PHP echo number_format ($TOTAL_FLETE,2); ?></td>
				  </tr>
				</table>
<?php
			if (count($G_CanceladoDolar_Array) > 0)
			{
						echo '<br />';
						echo '<!-- PARA MOSTRAR LOS MOVIMIENTOS EN DOLARES -->';
						echo '<p style="color:#FF0000;">Movimiento en Dolares ($)</p>';
						echo '<table width="100%" border="0" style="margin:2.0em 0 0.2em 0px; width:100%;">';
						 echo '<tr>';
							echo '<th>#</th>';
							echo '<th>Fecha</th>';
							echo '<th>N&deg; Boleta</th>';
							echo '<th>Consignatario</th>';
							echo '<th>Origen</th>';
							echo '<th>Usuario</th>';
							echo '<th title="Est&aacute; Pagado?">Pagado?</th>';
							echo '<th style="text-align:right;">Monto<br />($)</th>';
							echo '<th style="text-align:right;">Flete<br />($)</th>';
						  echo '</tr>';
				$cont = 1;
				for ($fila = 0; $fila < count($G_CanceladoDolar_Array); $fila++ )
				{
					$fecha = $G_CanceladoDolar_Array[$fila][1] .'<br />'.$G_CanceladoDolar_Array[$fila][2];
					$boleta = $G_CanceladoDolar_Array[$fila][0];
					$consignatario = utf8_encode($G_CanceladoDolar_Array[$fila][3]);
					$usuario_login = UserByID($G_CanceladoDolar_Array[$fila][7]);
					$usuario_name = UserNombreByID($G_CanceladoDolar_Array[$fila][7]);
					$esta_cancelado = $G_CanceladoDolar_Array[$fila][8];
					$oficina_destino = OficinaByID($G_CanceladoDolar_Array[$fila][9]);
					if ($G_CanceladoDolar_Array[$fila][6] == 'SI')
					{
?>
				<tr title="Giro Anulado" onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
							echo '<td style="color:#FF0000;">'.$cont.'</td>';
							echo '<td style="color:#FF0000;">'.$fecha.'</td>';
							echo '<td style="color:#FF0000;">'.$boleta.'</td>';
							echo '<td style="color:#FF0000;">'.$consignatario.'</td>';
							echo '<td style="color:#FF0000;">'.$oficina_destino.'</td>';
							echo '<td style="color:#FF0000;" title = "'.$usuario_name.'">'.$usuario_login.'</td>';
							echo '<td style="color:#FF0000;text-align:center;color:#FF0000;">'.$esta_cancelado.'</td>';
							echo '<td style="text-align:right;color:#FF0000;">'.$G_CanceladoSol_Array[$fila][4].'</td>';
							echo '<td style="text-align:right;color:#FF0000;">'.$G_CanceladoSol_Array[$fila][5].'</td>';
							$TOTAL_MONTO_DOLAR = $TOTAL_MONTO_DOLAR + $G_CanceladoDolar_Array[$fila][4];
							$TOTAL_FLETE_DOLAR = $TOTAL_FLETE_DOLAR + $G_CanceladoDolar_Array[$fila][5];
						echo '</tr>';
						$cont++;
					}
					else
					{
						if ($esta_cancelado == 'SI')
						{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
								echo '<td>'.$cont.'</td>';
								echo '<td>'.$fecha.'</td>';
								echo '<td>'.$boleta.'</td>';
								echo '<td>'.$consignatario.'</td>';
								echo '<td>'.$oficina_destino.'</td>';
								echo '<td title = "'.$usuario_name.'">'.$usuario_login.'</td>';
								echo '<td style="text-align:center;">'.$esta_cancelado.'</td>';
								echo '<td style="text-align:right;">'.$G_CanceladoSol_Array[$fila][4].'</td>';
								echo '<td style="text-align:right;">'.$G_CanceladoSol_Array[$fila][5].'</td>';
								$TOTAL_MONTO_DOLAR = $TOTAL_MONTO_DOLAR + $G_CanceladoDolar_Array[$fila][4];
								$TOTAL_FLETE_DOLAR = $TOTAL_FLETE_DOLAR + $G_CanceladoDolar_Array[$fila][5];
							echo '</tr>';
						}
						else
						{
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
								echo '<td style="font-weight:bold;">'.$cont.'</td>';
								echo '<td style="font-weight:bold;">'.$fecha.'</td>';
								echo '<td style="font-weight:bold;">'.$boleta.'</td>';
								echo '<td style="font-weight:bold;">'.$consignatario.'</td>';
								echo '<td style="font-weight:bold;">'.$oficina_destino.'</td>';
								echo '<td style="font-weight:bold;" title = "'.$usuario_name.'">'.$usuario_login.'</td>';
								echo '<td style="font-weight:bold;text-align:center;">'.$esta_cancelado.'</td>';
								echo '<td style="font-weight:bold;text-align:right;">'.$G_CanceladoSol_Array[$fila][4].'</td>';
								echo '<td style="font-weight:bold;text-align:right;">'.$G_CanceladoSol_Array[$fila][5].'</td>';
								$TOTAL_MONTO_DOLAR = $TOTAL_MONTO_DOLAR + $G_CanceladoDolar_Array[$fila][4];
								$TOTAL_FLETE_DOLAR = $TOTAL_FLETE_DOLAR + $G_CanceladoDolar_Array[$fila][5];
							echo '</tr>';
						}
					$cont++;
					}
				}
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
<?php
							echo '<td>&nbsp;</td>';
							echo '<td>&nbsp;</td>';
							echo '<td>&nbsp;</td>';
							echo '<td>&nbsp;</td>';
							echo '<td>&nbsp;</td>';
							echo '<td>&nbsp;</td>';
							echo '<td style="text-align:right; font-weight:bold;">TOTAL : $</td>';
							echo '<td style="text-align:right; font-weight:bold;">'.number_format ($TOTAL_MONTO_DOLAR,2).'</td>';
							echo '<td style="text-align:right; font-weight:bold;">'.number_format ($TOTAL_FLETE_DOLAR,2).'</td>';
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