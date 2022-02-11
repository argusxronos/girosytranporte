<?PHP
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	/********************************************/
	/* CARGAMOS LOS DATOS DE LA BOLETA DE VENTA */
	/********************************************/
	
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$fecha_giro = '';
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
	$id_boleta = 0;
	// OBTENEMOS EL ID_MOVIMIENTO
	if (isset($_GET['ID']))
	{
		$id_boleta = $_GET['ID'];
	}
	
	// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
	require_once 'cnn/config_master.php';
	
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
					AND `esta_impreso` = 0
					LIMIT 1;");
	
	$Mov_Array = $db_giro->get();
	
	// VERIFICAMOS SI SE OBTUVO DATOS
	if(count($Mov_Array) > 0)
	{
		// OBTENEMOS EL ID DEL USUARIO
		$id_usuario = $Mov_Array[0][0];
		// OBTENEMOS EL NOMBRE DEL USUARIO
		$db_transporte->query("SELECT `tusuario`.`c_login` AS `USER`
						FROM `tusuario`
						WHERE `tusuario`.`id_usuario` = ".$id_usuario."
						LIMIT 1;");
		$us_nombre = utf8_encode(strtoupper($db_transporte->get('USER'))) . ' - ' .$Mov_Array[0][10];
		// OBTENEMOS EL ID DE LA AGENCIA DE ORIGEN
		/*$id_agen_origen = $Mov_Array[0][1];
		// OBTENEMOS EL NOMBRE DE LA AGENCIA DE ORIGEN
		$db_transporte->query("SELECT `oficinas`.`oficina` AS `ORIGEN`
						FROM `oficinas`
						WHERE `oficinas`.`idoficina` = ".$id_agen_origen."
						LIMIT 1;");
		$agen_orig_nombre = $db_transporte->get("ORIGEN");*/
		// OBTENEMOS EL ID DE LA OFICINA DE DESTINO
		$id_agen_destino = $Mov_Array[0][1];
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
		// OBTENEMOS EL NOMBRE DE LA OFICINA DE DESTINO
		/*$db_transporte->query("SELECT `oficinas`.`oficina` AS `DESTINO`
						FROM `oficinas`
						WHERE `oficinas`.`idoficina` = ".$id_agen_destino."
						LIMIT 1;");*/
		//$agen_dest_nombre = utf8_decode($db_transporte->get('DESTINO'));
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
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
	<link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/boleta.css" />
	<!-- Icono -->
	<link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
	<!--  SCRIPT PARA ORDENAR LA IMPRESION EN CUANTO CARGE LA PAGINA -->
	<script language="JavaScript">
		function imprimir()
		{
			window.print();
			
			/*if (navigator.appName=="Netscape"){
			
				{   
			}*/
			if (navigator.appName == "Microsoft Internet Explorer")
			{
				window.onfocus = function() 
				{
					window.open('','_parent','');
					window.close(); 
				}
			}
			else
			{
				window.onfocus = function() 
				{
				window.open('', '_self', '');
				window.close();
				}
			}
		}
	</script>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>.::TC Impresi&oacute;n Boleta::.</title>
    <!-- Script para validar el navegador -->
    <script language="javascript" src="js/navegador.js"> 
    </script>
</head>

<body onload="imprimir();"><!--  -->
<div class="marca_agua">
	GIRO TELEF&Oacute;NICO</div>
<div class="content">
	
	<table width="100%" border="0">
	  <tr>
		<th height="51" colspan="2">&nbsp;</th>
		<td colspan="3" style="vertical-align:top; padding-top:10px;"><?php echo $fecha_giro;?></td>
		<td width="57">&nbsp;</td>
	  </tr>
	  <tr>
		<th width="80" class="left_row">&nbsp;</th>
		<td width="349">&nbsp;</td>
		<td colspan="3" style="font-size:9px;"><?php echo $num_boleta;?></td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<th height="16" class="left_row"></th>
		<td><?php echo $nom_completo_remit;?></td>
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
		<td><?php echo $agen_dest_nombre;?></td>
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
		<th height="130" class="right_align" style="padding-right:60px;"><?php echo $cantidad;?></th>
		<td class="left_align"><?php echo $monto_giro_letras;?></td>
		<td>&nbsp;</td>
		<td><?php echo $flete;?></td>
		<td>&nbsp;</td>
		<td><?php echo $total;?></td>
	  </tr>
	  
	  <tr>
		<th height="26" class="left_row">&nbsp;</th>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td style="font-size:16px;"><?php echo $total;?></td>
	  </tr>
	  <tr>
		<th height="33" class="left_row">&nbsp;</th>
		<td>&nbsp;</td>
		<td colspan="3" class="right_left" style="padding-left:30px;"><?php echo $us_nombre;?></td>
		<td>&nbsp;</td>
	  </tr>
	</table>
</div>
<?php
	//MODIFICAMOS AL MOVIMIENTO COMO IMPRESO
	$db_giro->query("UPDATE `g_movimiento` SET `esta_impreso`= 1
					WHERE `id_movimiento` = '".$id_boleta."'");
?>
</body>
</html>
