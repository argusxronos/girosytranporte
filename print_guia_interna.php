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
	$db_giro->query("SELECT `REMITENTE`.`per_nombre` as `REMIT`
					, `CONSIGNATARIO`.`per_nombre` as `CONSIG`
					, `e_movimiento`.`id_oficina_destino`
					, DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d-%m-%y') AS `FECHA`
					, RIGHT(CONCAT('00000', CAST(`e_movimiento`.`num_documento` AS CHAR)),6) AS `NUM_DOC`
					, `e_movimiento`.`id_usuario`
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
					FROM `e_mov_detalle`
					WHERE `e_mov_detalle`.`id_movimiento` = '".$id_boleta."';");
	
	$Mov_Array_List = $db_giro->get();
	// VERIFICAMOS SI SE OBTUVO DATOS
	if(count($Mov_Array) > 0)
	{
		// obtenemos el resto de los datos
		$nom_completo_remit = utf8_encode($Mov_Array[0][0]);
		$nom_completo_consig = utf8_encode($Mov_Array[0][1]);
		$id_agen_destino = $Mov_Array[0][2];
		$fecha = $Mov_Array[0][3];
		$Num_Doc = $Mov_Array[0][4];
		$id_usuario = $Mov_Array[0][5];
		// OBTENEMOS EL NOMBRE DEL USUARIO
		$us_nombre = UserByID($id_usuario);
		$agen_dest_nombre = utf8_encode(OficinaByID($id_agen_destino));
		
		
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
	<link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/guia_interna.css" />
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
    <!-- Script para validar el navegador -->
    <script language="javascript" src="js/navegador.js"> 
    </script>
</head>

<body onload="imprimir();" style="font-family:arial,verdana,sans-serif;"><!--  -->
<!--[if (IE 7) | (IE 8)]>
  <div class="content">
    <div class="marca_agua">
    <table width="100%" border="0">
      <tr>
        <td width="15%"><?php echo $Num_Doc; ?></td>
          <td width="85%" style="padding-left:150px;"><?php echo $us_nombre; ?></td>
      </tr>
    </table>
    </div>
    <table width="100%" border="0">
        <tr>
          <td height="89">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <tr>
          <td width="15%">&nbsp;</td>
          <td width="85%" style="padding-left:170px;"><?php echo $fecha; ?></td>
        </tr>
        <tr>
          <td height="34" colspan="2" style="padding-left:60px; vertical-align:bottom;"><?php echo $nom_completo_remit; ?></td>
        </tr>
        <tr>
          <td height="20" colspan="2" style="padding-left:60px; vertical-align:bottom;"><?php echo $nom_completo_consig; ?></td>
        </tr>
        <tr>
          <td height="20" colspan="2" style="padding-left:60px; vertical-align:bottom;"><?php echo $agen_dest_nombre; ?></td>
        </tr>
        <tr height="60">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        
  <?php 
    if (count($Mov_Array_List) > 0)
    {
      for ($fila = 0; $fila < count($Mov_Array_List); $fila++)
      {
        echo '<tr height="20">';
        echo '<td style="padding-left:0px;">'.$Mov_Array_List[$fila][0].'</td>';
        echo '<td>'.$Mov_Array_List[$fila][1].'</td>';
        echo '</tr>';
      }
    }
  ?>
    </tr>
    </table>
    
   
  </div>
<![endif]-->
<!--[if FF ]>-->
  <div class="content">
    <div class="marca_agua">
    <table width="100%" border="0" style="margin-top: -10px;">
      <tr>
        <td width="15%" style="font-size:10px; "><?php echo $Num_Doc; ?></td>
          <td width="85%" style="padding-left:150px; font-size:10px; "><?php echo $us_nombre; ?></td>
      </tr>
    </table>
    </div>
    <table width="100%" border="0">
        <tr>
          <td height="75">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <tr>
          <td width="15%">&nbsp;</td>
          <td width="85%" style="padding-left:170px; font-size:12px;"><?php echo $fecha; ?></td>
        </tr>
        <tr>
          <td height="32" colspan="2" style="padding-left:60px; vertical-align:bottom; font-size:12px;"><?php echo $nom_completo_remit; ?></td>
        </tr>
        <tr>
          <td height="25" colspan="2" style="padding-left:60px; vertical-align:bottom; font-size:12px;"><?php echo $nom_completo_consig; ?></td>
        </tr>
        <tr>
          <td height="25" colspan="2" style="padding-left:60px; vertical-align:bottom; font-size:12px;"><?php echo $agen_dest_nombre; ?></td>
        </tr>
        <tr height="47">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        
  <?php 
    if (count($Mov_Array_List) > 0)
    {
      for ($fila = 0; $fila < count($Mov_Array_List); $fila++)
      {
        echo '<tr height="20">';
        echo '<td style="padding-left:0px; font-size:12px;">'.$Mov_Array_List[$fila][0].'</td>';
        echo '<td style="font-size:12px;">'.$Mov_Array_List[$fila][1].'</td>';
        echo '</tr>';
      }
    }
  ?>
    </tr>
    </table>
  </div>
<!--[end if]-->
<?php
	//MODIFICAMOS AL MOVIMIENTO COMO IMPRESO
	$db_giro->query("UPDATE `e_movimiento` SET `esta_impreso`= 1
					WHERE `id_movimiento` = '".$id_boleta."'");
?>
</body>
</html>
