<?php 
	// INICIAMOS LAS SESIONES
	session_start();
	// Verificamos si el usuario ha iniciado sesión
	require_once("is_logged.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
  <!-- Hoja de Estilos para la impresion de la Boleta 
	<link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/boleta.css" />-->
  <!-- Hoja de Estilos para lista de busqueda de personas-->
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/buscar_persona.css" />
  <!-- Icono -->
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
  
  <!-- Links para el calendario -->
  <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
  <SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118">
  </script>
  <!-- Links para el calendario -->
	
  <title>.::TC - Modificar Giro::.</title>
  
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  <!-- Script para validar el navegador -->
  <script language="javascript" src="js/navegador.js"> 
  </script>
 
  <!--  ESCRIPT PARA ORDENAR LA IMPRESION EN CUANTO CARGE LA PAGINA -->
	<script language="JavaScript">
		function imprimir()
		{
			window.print();
		}
	</script>
</head>

<!-- Global IE fix to avoid layout crash when single word size wider than column width -->
<!--[if IE]><style type="text/css"> body {word-wrap: break-word;}</style><![endif]-->
<!-- onload="imprimir();" ESTO VA EN BODY -->
<body   <?php if(isset($_SESSION['IS_LOGGED'])) echo 'onbeforeunload="ConfirmClose()" onunload="HandleOnClose()"'; ?>>
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
      <?php include_once('content/e_derivada_action_content.php'); ?>
	  <!--END B.1 MAIN CONTENT -->
    </div>
	<!-- END B. MAIN -->
      
    <!-- START C. FOOTER AREA -->
    <?php include_once('footer.php'); ?>
	<!-- END C. FOOTER AREA -->
	      
  </div>
  <!-- END Main Page Container -->
  <?php
  
  // CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
/*	$fecha_giro = '';
	$id_usuario = 0;
	$us_nombre = '';
	$id_agen_origen = 0;
	$agen_orig_nombre = '';
	$id_agen_destino = 0;
	$agen_dest_nombre = '';
	$nom_completo_remit = '';
	$nom_completo_consig = '';
	$cantidad = 0;
	$monto_giro_letras = '';
	$flete = 0;
	$total = 0;
	$id_boleta = $id_movimiento;
	
	// OBTENEMOS LOS DATOS DEL MOVIMIENTO
	$db_giro->query("SELECT `g_movimiento`.`id_usuario`, `g_movimiento`.`id_oficina_destino`, `REMITENTE`.`per_ape_nom`, `CONSIGNATARIO`.`per_ape_nom`, DATE_FORMAT(`g_movimiento`.`fecha_emision`,'%d-%m-%Y'), '1' as `CANT`, 
CONCAT('GIRO POR: ', IF (`g_movimiento`.`tipo_moneda` = 1, 'S/. ', '$ '), CAST(`g_movimiento`.`monto_giro` AS CHAR), ' (', `g_movimiento`.`monto_giro_letras`,')') as `DESCRIP`,
`g_movimiento`.`flete_giro`, (`flete_giro` * 1) as `TOTAL`, CONCAT(RIGHT(CONCAT('0000',CAST(`g_movimiento`.`num_serie` AS CHAR)),4), '-', RIGHT(CONCAT('00000000',CAST(`g_movimiento`.`num_documento` AS CHAR)),8)) AS `NUM_BOLETA`, TIME_FORMAT(`g_movimiento`.`hora_emision`,'%r')
					FROM `g_movimiento`
					INNER JOIN `g_persona` AS `REMITENTE`
					ON `g_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
					INNER JOIN `g_persona` AS `CONSIGNATARIO`
					ON `g_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
					WHERE `id_movimiento` = '".$id_boleta."'
					LIMIT 1;");
	
	$Mov_Array = $db_giro->get();
	
	*/
	
	
	
	// OBTENEMOS EL NOMBRE DE LA OFICINA DE DESTINO
	/*$db_transporte->query("SELECT `oficinas`.`oficina` AS `DESTINO`
					FROM `oficinas`
					WHERE `oficinas`.`idoficina` = ".$id_agen_destino."
					LIMIT 1;");*/
	//$agen_dest_nombre = utf8_decode($db_transporte->get('DESTINO'));
	
	/*
	
	
	$Num_Doc = $Mov_Array[0][9];
	$agen_dest_nombre = utf8_encode(OficinaByID($id_agen_destino));
	// obtenemos el resto de los datos
	$nom_completo_remit = utf8_encode($Mov_Array[0][2]);
	$nom_completo_consig = utf8_encode($Mov_Array[0][3]);
	$fecha_giro = $Mov_Array[0][4];
	$cantidad = $Mov_Array[0][5];
	$monto_giro_letras = utf8_encode($Mov_Array[0][6]);
	$flete = $Mov_Array[0][7];
	$total = $Mov_Array[0][8];
	$num_boleta = $Mov_Array[0][9];
	
	
	
	
	*/
  ?>
  
  <!--
  <div class="marca_agua">
	GIRO TELEF&Oacute;NICO</div>
<div class="Div_Boleta" id="Div_Boleta">
    <table width="100%" border="0">
      <tr>
        <th height="51" colspan="2">&nbsp;</th>
        <td colspan="3" style="vertical-align:top; padding-top:10px;"><?php //echo $fecha_giro;?></td>
        <td width="57">&nbsp;</td>
      </tr>
      <tr>
        <th width="80" class="left_row">&nbsp;</th>
        <td width="349">&nbsp;</td>
        <td colspan="3" style="font-size:9px;"><?php echo right('0000' .$doc_serie, 4) .'-' .right('00000000' .$doc_numero, 8);?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <th height="16" class="left_row"></th>
        <td><?php echo $nom_completo_remit; ?></td>
        <td width="85">&nbsp;</td>
        <td width="45">&nbsp;</td>
        <td width="158">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <th height="16" class="left_row"></th>
        <td><?php echo $nom_completo_consig;?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <th height="16" class="left_row"></th>
        <td>AGENCIA</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <th height="16" class="left_row"></th>
        <td><?php echo OficinaByID($id_agen_destino); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <th height="38" class="left_row">&nbsp;</th>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr style="vertical-align:top;">
        <th height="130" class="right_align" style="padding-right:60px;">1</th>
        <td class="left_align"><?php echo $monto_giro_letras;?></td>
        <td>&nbsp;</td>
        <td><?php echo $flete; ?></td>
        <td>&nbsp;</td>
        <td><?php echo $flete; ?></td>
      </tr>
      
      <tr>
        <th height="26" class="left_row">&nbsp;</th>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="font-size:16px;"><?php echo $flete;?></td>
      </tr>
      <tr>
        <th height="33" class="left_row">&nbsp;</th>
        <td>&nbsp;</td>
        <td colspan="3" class="right_left" style="padding-left:30px;"><?php echo UserByID($_SESSION['ID_USUARIO']) .' - ' .$hora_giro2	; ?></td>
        <td>&nbsp;</td>
      </tr>
    </table>
</div> -->
</body>
</html>
