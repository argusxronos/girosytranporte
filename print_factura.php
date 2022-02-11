<?PHP
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
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
			if($_SESSION['OFICINAS'][$fila][0] == $id_ofic)
			{
				$Oficina = $_SESSION['OFICINAS'][$fila][1];
				break;
			}
		}
		return $Oficina;
	}
	// INCLUIMOS LA FUNCION QUE CONVIERTE EL MONTO EN LETRAS
	include_once('./function/numero_a_letras.php');
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
	require_once 'cnn/config_giro.php';
	
	// OBTENEMOS LOS DATOS DEL MOVIMIENTO
	$db_giro->query("SELECT 
					`REMITENTE`.`per_razon_social` as `REMIT`
					, `REMITENTE`.`per_ruc` as `REMIT_RUC`
					, IF(LENGTH(IFNULL(`e_direccion`.`dir_text`,'')) = 0, 'SIN DIRECCI&Oacute;N',`e_direccion`.`dir_text`) AS `REMIT_DIRECCION`
					, `CONSIGNATARIO`.`per_nombre` as `CONSIG`
					, IF(`e_movimiento`.`con_carrera` = 1,`CONSIGNATARIO`.`per_direccion`,'AGENCIA') as `REMIT_DIRECCION`
					, `e_movimiento`.`id_oficina_origen`
					, `e_movimiento`.`id_oficina_destino`
					, DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d-%m-%Y') AS `FECHA`
					, TIME_FORMAT(`e_movimiento`.`e_hora_emision`,'%r') AS `HORA`
					, CONCAT(RIGHT(CONCAT('00000', CAST(`e_movimiento`.`num_serie` AS CHAR)),4)
					, '-'
					, RIGHT(CONCAT('0000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS `NUM_GUIA`
					, `e_movimiento`.`e_subtotal`
					, `e_movimiento`.`e_igv`
					, `e_movimiento`.`e_total`
					, `e_movimiento`.`id_usuario`
					FROM `e_movimiento`
					INNER JOIN `e_persona` AS `REMITENTE`
					ON `e_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
					LEFT JOIN `e_direccion`
					ON `REMITENTE`.`id_persona` = `e_direccion`.`id_persona`
					AND `e_movimiento`.`id_oficina_origen` = `e_direccion`.`id_oficina`
					INNER JOIN `e_persona` AS `CONSIGNATARIO`
					ON `e_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
					WHERE `e_movimiento`.`id_movimiento` = '".$id_boleta."'
					LIMIT 1;");
	
	$Mov_Array = $db_giro->get();
	$db_giro->query("SELECT `e_mov_detalle`.`md_cantidad`
					, LEFT(`e_mov_detalle`.`md_descripcion`,43)
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
		$direccion_remit = utf8_encode($Mov_Array[0][2]);
		$nom_completo_consig = utf8_encode($Mov_Array[0][3]);
		$direccion_consig = utf8_encode($Mov_Array[0][4]);
		$id_agen_origen = $Mov_Array[0][5];
		$id_agen_destino = $Mov_Array[0][6];
		$agen_dest_nombre = utf8_encode(OficinaByID($id_agen_destino));
		$fecha = $Mov_Array[0][7];
		$hora = $Mov_Array[0][8];
		$Num_Doc = $Mov_Array[0][9];
		$subtotal = $Mov_Array[0][10];
		$igv = $Mov_Array[0][11];
		$total = $Mov_Array[0][12];
		$id_usuario = $Mov_Array[0][13];
		$us_nombre = UserByID($id_usuario);
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
	<link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/factura.css" />
	<!-- Icono -->
	<link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
	<!--  ESCRIPT PARA ORDENAR LA IMPRESION EN CUANTO CARGE LA PAGINA -->
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
    <!-- Script para validar el navegador-->
    <script language="javascript" src="js/navegador.js"> 
    </script> 
</head>

<body onload="imprimir();" style="font-family:arial,verdana,sans-serif;"><!--  -->
<!--[if (IE 7) | (IE 8)]>
  <div class="marca_agua">
      <table width="100%" border="0">
        <tr height="40">
          <td width="66%" style="padding-left:20px;"><?php echo convertir_numeros_a_letras($total);?></td>
          <td width="12%">&nbsp;</td>
          <td width="12%">&nbsp;</td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr height="40">
          <td width="66%">&nbsp;</td>
          <td width="12%" style="font-size:12px; font-weight:bold; padding-left: 5px; "><?php echo $subtotal; ?></td>
          <td width="12%" style="font-size:12px; font-weight:bold; padding-left: 10px;"><?php echo $igv; ?></td>
          <td width="10%" style="font-size:12px; font-weight:bold; padding-left: 15px;"><?php echo $total; ?></td>
        </tr>
        <tr height="30">
          <td width="66%">&nbsp;</td>
          <td colspan="3"><?php echo $hora; ?></td>
        </tr>
      </table>
  </div>
  <div class="content">
    
    <table width="100%" border="0">
      <tr>
      <td height="110" colspan="6">&nbsp;</td>
        </tr>
        <tr>
      <td width="10%" height="16">&nbsp;</td>
          <td colspan="2"><?php echo $nom_completo_remit; ?></td>
          <td width="12%">&nbsp;</td>
          <td width="13%">&nbsp;</td>
          <td width="10%">&nbsp;</td>
      </tr>
        <tr>
      <td height="16">&nbsp;</td>
          <td colspan="2"><?php echo $ruc_remit; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
        <tr>
      <td height="16">&nbsp;</td>
          <td colspan="2"><?php echo $direccion_remit; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
        <tr>
      <td height="16">&nbsp;</td>
          <td colspan="2"><?php echo $nom_completo_consig; ?></td>
          <td colspan="3" style="padding-left:50px;"><?php echo $fecha; ?></td>
        </tr>
        <tr>
      <td height="16">&nbsp;</td>
          <td colspan="2"><?php echo $direccion_consig; ?></td>
          <td colspan="3" style="padding-left:50px;"><?php echo $Num_Doc; ?></td>
        </tr>
        <tr>
      <td height="16">&nbsp;</td>
          <td width="28%"><?PHP echo $agen_dest_nombre; ?></td>
          <td width="27%" style="padding-left:20px;"><?php echo $us_nombre; ?></td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr height="35">
      <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
  <?php
    for ($fila = 0; $fila < count($Mov_Array_List); $fila++)
    {
      $cantidad = $Mov_Array_List[$fila][0];
      $descripcion = $Mov_Array_List[$fila][1];
      $flete = $Mov_Array_List[$fila][2];
      $carrera = $Mov_Array_List[$fila][3];
      $importe = $Mov_Array_List[$fila][4];
      echo '<tr height="20">';
        echo '<td style="padding-left:25px; padding-bottom: 10px;">'.$cantidad.'</td>';
        echo '<td colspan="2" style="padding-bottom: 10px; ">'.$descripcion.'</td>';
        if ($importe == '0.00')
        {
          echo '<td style="padding-bottom: 10px; ">&nbsp;</td>';
        }
        else
        {
          echo '<td style="padding-bottom: 10px; ">'.$flete.'</td>';
        }
        if($carrera == '0.00')
        {
          echo '<td style="padding-bottom: 10px; ">&nbsp;</td>';
        }
        else
        {
          echo '<td style="padding-bottom: 10px; ">'.$carrera.'</td>';
        }
        if ($importe == '0.00')
        {
          echo '<td style="padding-bottom: 10px; ">&nbsp;</td>';
        }
        else
        {
          echo '<td style="padding-bottom: 10px; ">'.$importe.'</td>';
        }
      echo '</tr>';
    }
  ?>
    </table>
  </div>
<![endif]-->
<!--[if FF ]>-->
  <div class="marca_agua" style="letter-spacing: 0px; font-size:15px;">
      <table width="100%" border="0">
        <tr height="40">
          <td width="66%" style="padding-left:20px; "><?php echo convertir_numeros_a_letras($total);?></td>
          <td width="12%">&nbsp;</td>
          <td width="12%">&nbsp;</td>
          <td width="10%">&nbsp;</td>
        </tr>
        <tr height="40">
          <td width="66%">&nbsp;</td>
          <td width="12%" style="font-size:24px; font-weight:bold; padding-left: 5px; "><?php echo $subtotal; ?></td>
          <td width="12%" style="font-size:24px; font-weight:bold; padding-left: 10px;"><?php echo $igv; ?></td>
          <td width="10%" style="font-size:24px; font-weight:bold; padding-left: 15px;"><?php echo $total; ?></td>
        </tr>
        <tr height="30">
          <td width="66%">&nbsp;</td>
          <td colspan="3"><?php echo $hora; ?></td>
        </tr>
      </table>
  </div>
  <div class="content" style="letter-spacing: 0px; font-size:15px;">
    
    <table width="100%" border="0">
      <tr>
      <td height="98" colspan="6">&nbsp;</td>
        </tr>
      <tr style="line-height: 17px;">
          <td width="10%" height="16">&nbsp;</td>
          <td colspan="2" ><?php echo $nom_completo_remit; ?></td>
          <td width="12%">&nbsp;</td>
          <td width="13%">&nbsp;</td>
          <td width="10%">&nbsp;</td>
      </tr>
      <tr style="line-height: 17px;">
          <td height="16">&nbsp;</td>
          <td colspan="2"><?php echo $ruc_remit; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
      <tr style="line-height: 17px;">
          <td height="16">&nbsp;</td>
          <td colspan="2"><?php echo $direccion_remit; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
     <tr style="line-height: 17px;">
          <td height="16">&nbsp;</td>
          <td colspan="2" ><?php echo $nom_completo_consig; ?></td>
          <td colspan="3" style="padding-left:50px;"><?php echo $fecha; ?></td>
        </tr>
     <tr style="line-height: 17px;">
            <td height="16">&nbsp;</td>
          <td colspan="2"><?php echo $direccion_consig; ?></td>
          <td colspan="3" style="padding-left:50px;"><?php echo $Num_Doc; ?></td>
        </tr>
     <tr style="line-height: 17px;">
          <td height="16">&nbsp;</td>
          <td width="28%"><?PHP echo $agen_dest_nombre; ?></td>
          <td width="27%" style="padding-left:20px;"><?php echo $us_nombre; ?></td>
          <td colspan="3">&nbsp;</td>
        </tr>
     <tr height="35">
          <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
  <?php
    for ($fila = 0; $fila < count($Mov_Array_List); $fila++)
    {
      $cantidad = $Mov_Array_List[$fila][0];
      $descripcion = $Mov_Array_List[$fila][1];
      $flete = $Mov_Array_List[$fila][2];
      $carrera = $Mov_Array_List[$fila][3];
      $importe = $Mov_Array_List[$fila][4];
      echo '<tr height="20">';
        echo '<td style="padding-left:25px; padding-bottom: 10px;">'.$cantidad.'</td>';
        echo '<td colspan="2" style="padding-bottom: 10px; ">'.$descripcion.'</td>';
        if ($importe == '0.00')
        {
          echo '<td style="padding-bottom: 10px; ">&nbsp;</td>';
        }
        else
        {
          echo '<td style="padding-bottom: 10px; ">'.$flete.'</td>';
        }
        if($carrera == '0.00')
        {
          echo '<td style="padding-bottom: 10px; ">&nbsp;</td>';
        }
        else
        {
          echo '<td style="padding-bottom: 10px; ">'.$carrera.'</td>';
        }
        if ($importe == '0.00')
        {
          echo '<td style="padding-bottom: 10px; ">&nbsp;</td>';
        }
        else
        {
          echo '<td style="padding-bottom: 10px; ">'.$importe.'</td>';
        }
      echo '</tr>';
    }
  ?>
    </table>
  </div>
<!--[end if]-->
<?php
	//MODIFICAMOS AL MOVIMIENTO COMO IMPRESO
	$db_giro->query("UPDATE `e_movimiento` 
                        SET `esta_impreso`= 1
                        WHERE `id_movimiento` = '".$id_boleta."'");
?>
</body>
</html>
