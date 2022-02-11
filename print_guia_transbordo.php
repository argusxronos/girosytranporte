<?PHP
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	require_once 'cnn/config_master.php';
	/********************************************/
	/* CARGAMOS LOS DATOS DE LA BOLETA DE VENTA */
	/********************************************/
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
	function OficinaByID($id_ofic)
	{
		$Ofic_Array = $_SESSION['OFICINAS'];
		$Oficina = '';
		for ($fila = 0; $fila < count($_SESSION['OFICINAS']); $fila++)
		{
			if($Ofic_Array[$fila][0] == $id_ofic)
			{
				$Oficina = $Ofic_Array[$fila][1];
				break;
			}
		}
		return $Oficina;
	}
	$Of_direccion = '';
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
  $tipDoc=$_GET['tipDoc'];
  $direccion=$_GET['direccion'];
	// OBTENEMOS EL ID_MOVIMIENTO
	if (isset($_GET['ID']) && strlen($_GET['ID']) > 0)
	{
		$id_boleta = $_GET['ID'];
	}
	// OBTENEMOS LOS DATOS DEL MOVIMIENTO
  
	$db_giro->query("SELECT 
   if('boleta'= '".$tipDoc."', REMITENTE.per_nombre, REMITENTE.per_razon_social) as remitente, 
  if('factura' = '".$tipDoc."', REMITENTE.per_ruc, REMITENTE.per_num_dni ) as dni_ruc_remitente
					, `CONSIGNATARIO`.`per_nombre` as `CONSIG`
          , CONSIGNATARIO.per_num_dni as dni_consignatario
					, `e_movimiento`.`id_oficina_origen`
					, `e_movimiento`.`id_oficina_destino`
					, CONCAT(DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d/%m')
					, '&nbsp;&nbsp;&nbsp;&nbsp;'
					, RIGHT(DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%y'),1)) AS `FECHA`
					, TIME_FORMAT(`e_movimiento`.`e_hora_emision`,'%r') AS `HORA`
					, CONCAT(RIGHT(CONCAT('00000', CAST(`e_movimiento`.`num_serie` AS CHAR)),4)
					, '-'
					, RIGHT(CONCAT('0000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS `NUM_GUIA`
					, `e_movimiento`.`e_subtotal`
					, `e_movimiento`.`e_igv`
					, `e_movimiento`.`e_total`
					, `e_movimiento`.`id_usuario`
          , if(`e_movimiento`.`con_carrera`=1, 1, 0) as carrera
					FROM `e_movimiento`
					INNER JOIN `e_persona` AS `REMITENTE`
					ON `e_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
					INNER JOIN `e_persona` AS `CONSIGNATARIO`
					ON `e_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
					WHERE `id_movimiento` = '".$id_boleta."'
					LIMIT 1;");
	$Mov_Array = $db_giro->get();
  

	$db_giro->query("SELECT `e_mov_detalle`.`md_cantidad`
					, `e_mov_detalle`.`md_descripcion`
					, `e_mov_detalle`.`md_flete`
					, `e_mov_detalle`.`md_carrera`
					, `e_mov_detalle`.`md_importe`
					FROM `e_mov_detalle`
					WHERE `e_mov_detalle`.`id_movimiento` = '".$id_boleta."';");
	$Mov_Array_List = $db_giro->get();
	// VERIFICAMOS SI SE OBTUVO DATOS
	if(count($Mov_Array) > 0)
	{
		$nom_completo_remit = utf8_encode($Mov_Array[0][0]);
		$ruc_remit = $Mov_Array[0][1];
		$nom_completo_consig = utf8_encode($Mov_Array[0][2]);
		$ruc_consig = $Mov_Array[0][3];
		$id_agen_origen = $Mov_Array[0][4];
		
		$db_transporte->query("SELECT `oficinas`.`direccion`
						FROM `oficinas`
						WHERE `oficinas`.`idoficina` = '".$id_agen_origen."'");
		$Of_direccion = $db_transporte->get('direccion');
		
		$nom_agen_origen = $Of_direccion;
		$id_agen_destino = $Mov_Array[0][5];
		$nom_agen_destino = OficinaByID($id_agen_destino);
		$fecha = $Mov_Array[0][6];
		$hora = $Mov_Array[0][7];
		$Num_Doc = $Mov_Array[0][8];
		$id_usuario = $Mov_Array[0][12];
		
    if ($Mov_Array[0][13]==1) $con_carrera = '(CON CARRERA) ';
    else $con_carrera = '';
    
		$us_nombre = UserByID($id_usuario) . ' - ' .$hora;
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
	<link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/guia_transbordo.css" />
	<!-- Icono -->
	<link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
    <title>.::GUIA TRANSBORDO::.</title>
	<!--  ESCRIPT PARA ORDENAR LA IMPRESION EN CUANTO CARGE LA PAGINA -->
	<script type="text/javascript">
		function imprimir()
		{
			window.print();
			
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
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>.::TC Impresión GT::.</title>
    <!-- Script para validar el navegador -->
    <script type="text/javascript" src="js/navegador.js"> 
    </script>
</head>

<body onload="imprimir();"><!--  -->
<div class="buttom_doc">
	<table width="100%" border="0">
        <tr>
            <td width="14%"></td>
            <td width="11%"></td>
            <td width="11%"></td>
            <td><?php echo $us_nombre ?></td>
        </tr>
    </table>
</div>
<div class="content">
	
	<table width="100%" border="0">
	  <tr>
		<th height="80" colspan="5"></th>
	  </tr>
      <tr>
		<td height="26" colspan="3" style="padding-left:63px;"><?php echo $fecha;?></td>
        <td width="12%"></td>
        <td width="9%"></td>
	  </tr>
      <tr>

		<td height="26"></td>
        <td colspan="2">Agencia Origen: <?php echo $nom_agen_origen;?></td>
        <td></td>
        <td></td>
	  </tr>
      <tr>
		<td height="30"></td>
        <td colspan="2">Agencia Destino: <?php echo $nom_agen_destino;?></td>
        <td></td>
        <td>;</td>
	  </tr>
    <td width="10%" height="26"></td>
        <td colspan="2">Remitente: <?php echo $nom_completo_remit;?> - DNI/RUC: <?php echo $ruc_remit;?></td>
        <td></td>
        <td></td>
	  </tr>
      <tr>
		<td height="26"></td>
        <td colspan="2">Consignatario: <?php echo $nom_completo_consig;?> - DNI/RUC:<?php echo $ruc_consig;?> </td>
        <td></td>
        <td></td>
	  </tr>
    <td height="26"></td>
        <td colspan="2">Dirección: <?php echo $direccion;?>  <?php echo $con_carrera;?></td>
        <td></td>
        <td></td>
	  </tr>
    <tr height="26">
        <td></td>
        <td width="60%"></td>
        <td width="9%"></td>
        <td></td>
        <td></td>
	  </tr>
<?PHP
	echo '<tr  height="26">';
		echo '<td style="padding-left:25px;"></td>';
		echo '<td></td>';
		echo '<td>FLETE</td>';
		echo '<td>CARRERA</td>';
		echo '<td>TOTAL</td>';
	echo '</tr>';
  $total=0;
	for ($fila = 0; $fila < count($Mov_Array_List); $fila++)
	{
		$cantidad = $Mov_Array_List[$fila][0];
		$descripcion = $Mov_Array_List[$fila][1];
		$flete = $Mov_Array_List[$fila][2];
		$carrera = $Mov_Array_List[$fila][3];
		$importe = $Mov_Array_List[$fila][4];
		echo '<tr height="26">';
			echo '<td style="padding-left:25px;">'.$cantidad.'</td>';
			echo '<td>'.$descripcion.'</td>';
			echo '<td>'.$flete.'</td>';
			echo '<td>'.$carrera.'</td>';
			echo '<td>'.$importe.'</td>';
		echo '</tr>';
    $total+=$importe;
	}
  echo '<tr height="26">
          <td style="padding-left:25px;"></td>
          <td></td>
          <td></td>
          <td></td>
          <td>'.number_format($total,2) .'</td>
        </tr>';
  
?>
  
	</table>
</div>
<?php
	//MODIFICAMOS AL MOVIMIENTO COMO IMPRESO
	$db_giro->query("UPDATE `e_movimiento` SET `esta_impreso`= 1
					WHERE `id_movimiento` = '".$id_boleta."'");
?>
</body>
</html>
