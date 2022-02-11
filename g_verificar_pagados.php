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
  
  <title>.::TC Verif Giros Pag.::.</title>
  
  <!-- Links para el calendario -->
  <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
  <SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118">
  </script>
  <!-- Links para el calendario -->

  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  <!-- Script para actualizar un giro -->
  <script language="javascript" src="js/ajax-actualizar-giro.js"> 
  </script>
  <!-- Script para validar el navegador -->
  <?php
  	if ($_SESSION['TIPO_USUARIO'] == 1)
	{
		echo '<script language="javascript" src="js/navegador.js"></script>';
	}
  ?>
  <!-- Script para Actulizar Si esta copiado al cuaderno o no -->
  <script type="text/javascript" src="js/ajax-esta-verificada.js"></script>
  <!-- Script para poder editar el numero de vale -->
  <script type="text/javascript" src="js/ajax-edit-vale-giro.js"></script>
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/validate_numbers.js"> 
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
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	if (isset($_GET['btn_buscar']))
	{
		// DECLARAMOS LAS VARIABLES PARA EL REPORTE
		$id_oficina = "";
		
		$TOTAL_SOLES = 0;
		$TOTAL_FLETE_SOLES = 0;
		$TOTAL_DOLAR = 0;
		$TOTAL_FLETE_DOLAR = 0;
		
		// OBTENEMOS LAS FECHAS
		$fecha_inicio = "";
		$fecha_fin = "";
		
		if(!isset($_GET['cmb_agencia_origen']) || strlen($_GET['cmb_agencia_origen']) == 0)
		{
			MsjErrores('Debe seleccionar una Oficina Origen.');
		}
		else
		{
			$id_oficina = $_GET['cmb_agencia_origen'];
		}
		
		if (isset($_GET['txt_fecha_ini']))
		{
			$date = $_GET['txt_fecha_ini'];
 			$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
			$fecha_inicio = new DateTime($date);
		}
		if ($Error == FALSE)
		{
			// OBTENEMOS LOS DATOS PARA EL REPORTE
			$sql_soles = "SELECT 
							`g_movimiento`.`id_movimiento`
							, DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y') as `fecha_emision`
							, CONCAT(RIGHT(CONCAT('000', CAST(`g_movimiento`.`num_serie` AS CHAR)), 4), '-', 
							RIGHT(CONCAT('00000000',CAST(`g_movimiento`.`num_documento` AS CHAR)),8)  ) AS 'NUM_BOLETO'
							, `g_persona`.`per_ape_nom`
							, `g_movimiento`.`id_usuario`
							, `g_movimiento`.`monto_giro`
							, `g_movimiento`.`flete_giro`
							, IF(`g_movimiento`.`esta_anulado` = 1, 'SI', 'NO') AS `esta_anulado`
							, IF(`g_movimiento`.`verificado` = 1, 'SI', 'NO') AS `verificado`
							, `g_entrega`.`ent_num_vale`
							, DATE_FORMAT(`g_entrega`.`ent_fecha_entrega`,'%d-%m-%Y') as `ent_fecha_entrega`
							, `g_entrega`.`ent_id_usuario`
							, `g_entrega`.`ent_id_oficina`
							, IF(`g_entrega`.`ent_verificada` = 1, 'SI', 'NO') AS `ent_verificada`
							FROM `g_movimiento`
							LEFT JOIN `g_entrega`
							ON `g_movimiento`.`id_movimiento` = `g_entrega`.`id_movimiento`
							INNER JOIN `g_persona`
							ON `g_movimiento`.`id_consignatario` = `g_persona`.`id_persona`
							
							WHERE `g_movimiento`.`id_oficina_origen` = ".$id_oficina ." 
							AND MONTH(`g_movimiento`.`fecha_emision`) = '".$fecha_inicio->format("m")."' 
							AND YEAR(`g_movimiento`.`fecha_emision`) = '".$fecha_inicio->format("Y")."'";
			if (isset($_GET['cmb_agencia_destino']) &&  $_GET['cmb_agencia_destino'] > 0)
				$sql_soles = $sql_soles ." AND `g_movimiento`.`id_oficina_destino` = '".$_GET['cmb_agencia_destino']."' ";
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
			if (isset($_GET['cmb_estado']))
			{
				if ($_GET['cmb_estado'] == 1)
				{
					$sql_soles = $sql_soles . " AND `g_entrega`.`ent_verificada` = 1";
				}
				elseif($_GET['cmb_estado'] == 2)
				{
					$sql_soles = $sql_soles . " AND (`g_entrega`.`ent_verificada` = 0 OR (IF(`g_entrega`.`ent_verificada` = 1, 'SI', 'NO') = 'NO' AND `g_movimiento`.`esta_anulado` = 0))";
				}
			}
							
			// ALGUNAS CONDICIONESA MAS
			
			$sql_soles = $sql_soles ." GROUP BY `g_movimiento`.`num_serie`, `g_movimiento`.`num_documento`, `g_movimiento`.`id_movimiento`
									ORDER BY `g_movimiento`.`num_serie` ASC
									, `g_movimiento`.`num_documento` ASC
									, `g_movimiento`.`fecha_emision` ASC;";

			// CONSULTA PARA DOLARES
			
			$sql_dolar = "SELECT 
							`g_movimiento`.`id_movimiento`
							, DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y') as `fecha_emision`
							, CONCAT(RIGHT(CONCAT('000', CAST(`g_movimiento`.`num_serie` AS CHAR)), 4), '-', 
							RIGHT(CONCAT('00000000',CAST(`g_movimiento`.`num_documento` AS CHAR)),8)  ) AS 'NUM_BOLETO'
							, `g_persona`.`per_ape_nom`
							, `g_movimiento`.`id_usuario`
							, `g_movimiento`.`monto_giro`
							, `g_movimiento`.`flete_giro`
							, IF(`g_movimiento`.`esta_anulado` = 1, 'SI', 'NO') AS `esta_anulado`
							, IF(`g_movimiento`.`verificado` = 1, 'SI', 'NO') AS `verificado`
							, `g_entrega`.`ent_num_vale`
							, DATE_FORMAT(`g_entrega`.`ent_fecha_entrega`,'%d-%m-%Y') as `ent_fecha_entrega`
							, `g_entrega`.`ent_id_usuario`
							, `g_entrega`.`ent_id_oficina`
							, IF(`g_entrega`.`ent_verificada` = 1, 'SI', 'NO') AS `ent_verificada`
							FROM `g_movimiento`
							LEFT JOIN `g_entrega`
							ON `g_movimiento`.`id_movimiento` = `g_entrega`.`id_movimiento`
							INNER JOIN `g_persona`
							ON `g_movimiento`.`id_consignatario` = `g_persona`.`id_persona`
							
							WHERE `g_movimiento`.`id_oficina_origen` = ".$id_oficina ." 
							AND MONTH(`g_movimiento`.`fecha_emision`) = '".$fecha_inicio->format("m")."' 
							AND YEAR(`g_movimiento`.`fecha_emision`) = '".$fecha_inicio->format("Y")."'";
			if (isset($_GET['cmb_agencia_destino']) &&  $_GET['cmb_agencia_destino'] > 0)
				$sql_dolar = $sql_dolar ." AND `g_movimiento`.`id_oficina_destino` = '".$_GET['cmb_agencia_destino']."' ";
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
			if (isset($_GET['cmb_estado']))
			{
				if ($_GET['cmb_estado'] == 1)
				{
					$sql_dolar = $sql_dolar . " AND `g_entrega`.`ent_verificada` = 1";
				}
				elseif($_GET['cmb_estado'] == 2)
				{
					$sql_dolar = $sql_dolar . " AND (`g_entrega`.`ent_verificada` = 0 OR IF(`g_entrega`.`ent_verificada` = 1, 'SI', 'NO') = 'NO')";
				}
			}
							
			// ALGUNAS CONDICIONESA MAS
			
			$sql_dolar = $sql_dolar ." GROUP BY `g_movimiento`.`num_serie`, `g_movimiento`.`num_documento`
									ORDER BY `g_movimiento`.`num_serie` ASC
									, `g_movimiento`.`num_documento` ASC
									, `g_movimiento`.`fecha_emision` ASC;";

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
            <h1 class="pagetitle">Verificar Giros Pagados - Zona de Busqueda</h1>
            
            <form method="get" action="g_verificar_pagados.php" name="buscar_reporte" >
                    <table width="100%" border="0">
                        <tr>
                            <th width="119">Agencia : </th>
<td width="271"><select name="cmb_agencia_origen" class="combo" title="Agenia donde se Pag&oacute; del giro." tabindex="1" style="width:220px;" >
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
                            </select></td>
                          <th width="103">Estado :</th>
<td width="269"><label>
                              <select name="cmb_estado" id="cmb_estado" class="combo" style="width:200px;" tabindex="3">
                              	<option value="0" <?php if(isset($_GET['cmb_estado']) && $_GET['cmb_estado'] == 0) echo 'selected="selected"'; ?> >TODOS</option>
                                <option value="1" <?php if(isset($_GET['cmb_estado']) && $_GET['cmb_estado'] == 1) echo 'selected="selected"'; ?> >Verificados</option>
                                <option value="2" <?php if(isset($_GET['cmb_estado']) && $_GET['cmb_estado'] == 2) echo 'selected="selected"'; elseif (!isset($_GET['cmb_estado'])) echo 'selected="selected"'; ?> >No Verificados</option>
                              </select>
                            </label></td>
                      </tr>
                        <tr>
                            <th>Fecha Inicio:</th>
                            <td><input name="txt_fecha_ini" id="txt_fecha" type="text" value="<?php if(isset($_GET['btn_buscar'])) echo $fecha_inicio->format("d/m/Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly="readonly" style="width:150px;" tabindex="2" onkeypress="return handleEnter(this, event)">
                              <input name="button1" type="button" class="button" style="width:54px;" onclick="displayCalendar(document.forms[0].txt_fecha_ini,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal"></td>
                            <th>&nbsp;</th>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <th width="119">Ag. Destino  : </th>
                        <td width="271"><select name="cmb_agencia_destino" class="combo" title="Agenia donde se Pag&oacute; del giro." style="width:220px;" tabindex="4" >
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
											if (isset($_GET['cmb_agencia_destino']) && $_GET['cmb_agencia_destino'] == $Oficina_Array[$fila][0])
											{
												echo '<option selected="selected" value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
											}
											else
                                            	echo '<option value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
                                        }
										echo '<option value="0" >TODOS</option>';
                                    }
                                 ?>
                            </select></td>
                          <th width="103">Boleta :</th>
                    	  <td width="269"><input type="text" name="txt_serie_doc" id="txt_serie" style="width:60px;" onfocus="this.select();" title="Serie del Documento." tabindex="5" />
                              <span>-</span>
                              <input type="text" name="txt_numero_doc" id="txt_serie" style="width:100px;" title="N&uacute;mero del Documento." tabindex="6" /></td>
                        </tr>
                        <tr>
                            <th>Consignatario :</th>
                            <th colspan="3" ><input name="txt_consignatario" type="text" style="width:455px; text-transform:uppercase;" value="<?php if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0) echo $_GET['txt_consignatario'] ?>" onkeypress="return acceptletras(this, event);" autocomplete="off" onfocus="this.select();" tabindex="7" /></th>
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
			
			  <h1>Verificar Giros Pagados de la Oficina <?php echo OficinaByID($id_oficina)?>.</h1>
			    <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			  <div class="contactform">
			  	<!-- PARA MOSTRAR LOS MOVIMIENTOS EN SOLES -->
			  	<p style="color:#FF0000;">Movimiento en Soles (S/.) del : <span><?php echo $fecha_inicio->format("m/Y"); ?></span></p>
			  	<table border="0" style="margin:2.0em 0 0.2em 0px; width:100%;">
				  <tr>
                  	<th width="6%" style="text-align:center;">#</th>
					<th width="11%">Fecha</th>

					<th width="35%">Consignatario</th>
                    <th width="14%"># Boleta <br /></th>
					<th width="9%" style="text-align:right;">Monto (S/.)</th>
                    <!-- <th width="9%" style="text-align:right;">Flete (S/.)</th> -->
                    
                    <th width="8%" title="Boleta est&aacute; Verificada?"># Vale</th>
                    <th width="8%" title="Vale est&aacute; Verificado?">Val.<br/ >Verf?</th>
				  </tr>
<?php
	
		if (count($G_CanceladoSol_Array) > 0)
		{
			$cont = 1;
			for ($fila = 0; $fila < count($G_CanceladoSol_Array); $fila++ )
			{
				$id = $G_CanceladoSol_Array[$fila][0];
				$fecha = $G_CanceladoSol_Array[$fila][1];
				$boleta = $G_CanceladoSol_Array[$fila][2];
				$consignatario = utf8_encode($G_CanceladoSol_Array[$fila][3]);
				$id_usuario_emisor = $G_CanceladoSol_Array[$fila][4];
				if (strlen($G_CanceladoSol_Array[$fila][4]) > 0)
				{
					$usuario_name_emisor = UserNombreByID($id_usuario_emisor);
				}
				else
				{
					$usuario_name = '---';
				}
				$monto = $G_CanceladoSol_Array[$fila][5];
				$flete = $G_CanceladoSol_Array[$fila][6];
				$anulado = $G_CanceladoSol_Array[$fila][7];
				$bol_verificada = $G_CanceladoSol_Array[$fila][8];
				$vale = $G_CanceladoSol_Array[$fila][9];
				$fecha_entrega = $G_CanceladoSol_Array[$fila][10];
				$id_usuario_receptor = $G_CanceladoSol_Array[$fila][11];
				if (strlen($G_CanceladoSol_Array[$fila][11]) > 0)
				{
					$usuario_name_receptor = UserNombreByID($id_usuario_receptor);
				}
				else
				{
					$usuario_name = '---';
				}
				$id_oficina_entrega = $G_CanceladoSol_Array[$fila][12];
				$oficina_entrega = OficinaByID($G_CanceladoSol_Array[$fila][12]);
				$val_verificada = $G_CanceladoSol_Array[$fila][13];
				
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" id="Div_tr_<?php echo $id; ?>" >
<?php
				if ($anulado == 'NO')
				{
					if ($bol_verificada == 'SI')
					{
						echo '<td style="text-align:center">'.$cont.'</td>';
						echo '<td>'.$fecha.'</td>';
						
						echo '<td style="text-align:left">'.$consignatario.'</td>';
						echo '<td><a title="Giro emitido por: '.$usuario_name_emisor.'">'.$boleta.'</a></td>';
						echo '<td style="text-align:right">'.$monto.'</td>';
						// echo '<td title="'.$usuario_name.'" style="text-align:right">'.$flete.'</td>';
						if (strlen($vale) > 0)
						{
							echo '<td id="td_vale_'.$id.'"><a title="Vale pagado por: '.$usuario_name_receptor.'&#10;En: '.$oficina_entrega.'" class="vale" onClick="Edit_Vale_Giro(' .$vale .', event, '.$id.');" onkeyup = "extractNumber(this,0,false);" >' .$vale .'</a></td>';
						}
						else
						{
							echo '<td id="td_vale_'.$id.'"><input name="txt_vale_"'.$id.' style="width:60px;" type="text" onKeyPress="Update_Giro(this, event, '.$id.', '.$cont.')" onkeyup = "extractNumber(this,0,false);" /></td>';
						}
						if($val_verificada == 'NO')
						{
							if (strlen($vale) == 0)
							{
								echo '<td style="text-align:center; color:#FF0000;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);" disabled="disabled" /></td>';
							}
							else
							{
								echo '<td style="text-align:center;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);this.focus();" /></td>';
							}
						}
						else
						{
							echo '<td style="text-align:center;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);this.focus();" checked /></td>';
						}
					}
					else
					{
						echo '<td style="text-align:center;color:#0033FF;">'.$cont.'</td>';
						echo '<td style="text-align:left;color:#0033FF;">'.$fecha.'</td>';
						
						echo '<td  style="text-align:left;color:#0033FF;">'.$consignatario.'</td>';
						echo '<td style="text-align:left;color:#0033FF;"><a title="Giro emitido por: '.$usuario_name_emisor.'" class="no_verified" >'.$boleta.'</a></td>';
						echo '<td  style="text-align:right;color:#0033FF;">'.$monto.'</td>';
						// echo '<td title="'.$usuario_name.'"  style="text-align:right;color:#0033FF;">'.$flete.'</td>';
						if (strlen($vale) > 0)
						{
							echo '<td id="td_vale_'.$id.'"><a title="Vale pagado por: '.$usuario_name_receptor.'&#10;En: '.$oficina_entrega.'" class="vale" onClick="Edit_Vale_Giro(' .$vale .', event, '.$id.');" onkeyup = "extractNumber(this,0,false);" >' .$vale .'</a></td>';
						}
						else
						{
							echo '<td id="td_vale_'.$id.'"><input name="txt_vale_"'.$id.' style="width:60px;" type="text" onKeyPress="Update_Giro(this, event, '.$id.', '.$cont.')" onkeyup = "extractNumber(this,0,false);" /></td>';
						}
						echo '<td style="text-align:center; color:#FF0000;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);" disabled="disabled" /></td>';
					}
				}
				else
				{
					echo '<td style="text-align:center; color:#FF0000;">'.$cont.'</td>';
					echo '<td style="text-align:left; color:#FF0000;">'.$fecha.'</td>';
					echo '<td style="text-align:left; color:#FF0000;">'.$consignatario.'</td>';
					echo '<td style="text-align:left; color:#FF0000;"><a title="Giro emitido por: '.$usuario_name_emisor.'" class="anulado" href="#">'.$boleta.'</a>' .'</td>';
					echo '<td colspan="2" style="text-align:center; color:#FF0000;">Anulado</td>';
					echo '<td style="text-align:center; color:#FF0000;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);" disabled="disabled" /></td>';
				}
				$TOTAL_SOLES = $TOTAL_SOLES + $monto;
				$TOTAL_FLETE_SOLES = $TOTAL_FLETE_SOLES + $flete;
				
				echo '</tr>';
				$cont++;
			}
		}
		else
		{
					echo '<td colspan="8" style="text-align:center;">NO HAY REGISTROS.</td>';
				echo '</tr>';
		}
?>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
                    
					<td>&nbsp;</td>
					<td style="text-align:right; font-weight:bold;">TOTAL : S/.</td>
                    <td style="text-align:right; font-weight:bold;"><?PHP echo number_format ($TOTAL_SOLES,2); ?></td>
					<td style="text-align:right; font-weight:bold;"><?PHP echo number_format ($TOTAL_FLETE_SOLES,2); ?></td>
                    <td>&nbsp;</td>
				  </tr>
				</table>
<?php
		if (count($G_CanceladoDolar_Array) > 0)
		{
				echo '<br />';
				echo '<!-- PARA MOSTRAR LOS MOVIMIENTOS EN DOLARES -->';
				echo '<p style="color:#FF0000;">Movimiento en Dolares ($)</p>';
				echo '<table width="100%" border="0" style="margin:2.0em 0 0.2em 0px; width:100%;">';
				 echo ' <tr>';
                  	echo '<th width="6%" style="text-align:center;">#</th>';
					echo '<th width="11%">Fecha</th>';
					echo '<th width="14%"># Boleta <br /></th>';
					echo '<th width="35%">Consignatario</th>';
					echo '<th width="9%" style="text-align:right;">Monto (S/.)</th>';
                    echo '<th width="9%" style="text-align:right;">Flete (S/.)</th>';
                    echo '<th width="8%" title="Boleta est&aacute; Verificada?"># Vale</th>';
                    echo '<th width="8%" title="Vale est&aacute; Verificado?">Val.<br/ >
                    Verf?</th>';
				 echo ' </tr>';
			$cont = 1;
			for ($fila = 0; $fila < count($G_CanceladoDolar_Array); $fila++ )
			{
				$id = $G_CanceladoDolar_Array[$fila][0];
				$fecha = $G_CanceladoDolar_Array[$fila][1];
				$boleta = $G_CanceladoDolar_Array[$fila][2];
				$consignatario = utf8_encode($G_CanceladoDolar_Array[$fila][3]);
				$id_usuario_emisor = $G_CanceladoDolar_Array[$fila][4];
				if (strlen($G_CanceladoDolar_Array[$fila][4]) > 0)
				{
					$usuario_name_emisor = UserNombreByID($id_usuario_emisor);
				}
				else
				{
					$usuario_name = '---';
				}
				$monto = $G_CanceladoDolar_Array[$fila][5];
				$flete = $G_CanceladoDolar_Array[$fila][6];
				$anulado = $G_CanceladoDolar_Array[$fila][7];
				$bol_verificada = $G_CanceladoDolar_Array[$fila][8];
				$vale = $G_CanceladoDolar_Array[$fila][9];
				$fecha_entrega = $G_CanceladoDolar_Array[$fila][10];
				$id_usuario_receptor = $G_CanceladoDolar_Array[$fila][11];
				if (strlen($G_CanceladoDolar_Array[$fila][11]) > 0)
				{
					$usuario_name_receptor = UserNombreByID($id_usuario_receptor);
				}
				else
				{
					$usuario_name = '---';
				}
				$id_oficina_entrega = $G_CanceladoDolar_Array[$fila][12];
				$oficina_entrega = OficinaByID($G_CanceladoDolar_Array[$fila][12]);
				$val_verificada = $G_CanceladoDolar_Array[$fila][13];
				
?>
				<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" id="Div_tr_<?php echo $id; ?>" >
<?php
				if ($anulado == 'NO')
				{
					echo '<td style="text-align:center">'.$cont.'</td>';
					echo '<td>'.$fecha.'</td>';
					echo '<td><a title="Giro emitido por: '.$usuario_name_emisor.'" href="#">'.$boleta.'</a></td>';
					echo '<td style="text-align:left">'.$consignatario.'</td>';
					echo '<td style="text-align:right">'.$monto.'</td>';
					echo '<td title="'.$usuario_name.'" style="text-align:right">'.$flete.'</td>';
					if (strlen($vale) > 0)
					{
						echo '<td id="td_vale_'.$id.'"><a title="Vale pagado por: '.$usuario_name_receptor.'&#10;En: '.$oficina_entrega.'" class="vale" onClick="Edit_Vale_Giro(' .$vale .', event, '.$id.');" onkeyup = "extractNumber(this,0,false);" >' .$vale .'</a></td>';
					}
					else
					{
						echo '<td id="td_vale_'.$id.'"><input name="txt_vale_"'.$id.' style="width:60px;" type="text" onKeyPress="Update_Giro(this, event, '.$id.', '.$cont.')" onkeyup = "extractNumber(this,0,false);" /></td>';
					}
					if($val_verificada == 'NO')
					{
						if (strlen($vale) == 0)
						{
							echo '<td style="text-align:center; color:#FF0000;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);" disabled="disabled" /></td>';
						}
						else
						{
							echo '<td style="text-align:center;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);this.focus();" /></td>';
						}
					}
					else
					{
						echo '<td style="text-align:center;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);this.focus();" checked /></td>';
					}
				}
				else
				{
					echo '<td style="text-align:center; color:#FF0000;">'.$cont.'</td>';
					echo '<td style="text-align:center; color:#FF0000;">'.$fecha.'</td>';
					echo '<td style="text-align:left; color:#FF0000;">'.$consignatario.'</td>';
					echo '<td style="text-align:center; color:#FF0000;"><a title="Giro emitido por: '.$usuario_name_emisor.'" class="anulado" href="#">'.$boleta.'</a>' .'</td>';
					echo '<td style="text-align:right; color:#FF0000;">'.$monto.'</td>';
					echo '<td style="text-align:right; color:#FF0000;">'.$flete.'</td>';
					echo '<td style="text-align:center; color:#FF0000;"><input type="checkbox" name="cbox_val_verif_'.$id.'" value="'.$id.'" title="Este Giro no ha sido Verificado.'.($cont + 10).'" tabindex="'.($cont + 10).'" onClick="Update_Verified(event, this);" disabled="disabled" /></td>';
				}
				$TOTAL_DOLAR = $TOTAL_DOLAR + $monto;
				$TOTAL_FLETE_DOLAR = $TOTAL_FLETE_DOLAR + $flete;
				
				echo '</tr>';
				$cont++;
			}
				   echo '<tr>';
					echo '<td>&nbsp;</td>';
					echo '<td>&nbsp;</td>';
                    
					echo '<td>&nbsp;</td>';
					echo '<td style="text-align:right; font-weight:bold;">TOTAL : $</td>';
                    echo '<td style="text-align:right; font-weight:bold;">'.number_format($TOTAL_DOLAR,2).'</td>';
					echo '<td style="text-align:right; font-weight:bold;">'.number_format ($TOTAL_FLETE_DOLAR,2).'</td>';
					echo '<td style="text-align:right; font-weight:bold;">&nbsp;</td>';
					echo '<td style="text-align:right; font-weight:bold;">&nbsp;</td>';
				  echo '</tr>';
                  
			echo '</table>';
		}
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
                      <input type="button" name="btn_print" id="btn_print" class="button" value="Imprimir Reporte"  onclick="window.print()" style="width:250px;"/>
                    </span></th>
                  </tr>
                </table>
			  </div>
			
			<!-- Limpiar Unidad del Contenido -->
			<hr class="clear-contentunit" />
            <div id="div_error">
    		</div>
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



