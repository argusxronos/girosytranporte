<?PHP
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	require_once 'cnn/config_giro.php';
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
	if (isset($_GET['ID']) && strlen($_GET['ID']) > 0)
	{
		$id_boleta = $_GET['ID'];
	}
	// OBTENEMOS LOS DATOS DEL MOVIMIENTO
	$db_giro->query("SELECT `REMITENTE`.`per_nombre` as `REMIT`
					, `CONSIGNATARIO`.`per_nombre` as `CONSIG`
					, IF(`e_movimiento`.`con_carrera` = 1,IF(LENGTH(`CONSIGNATARIO`.`per_direccion`) = 0, 'AGENCIA', `CONSIGNATARIO`.`per_direccion`),'AGENCIA') as `DIRECCION`
					, `e_movimiento`.`id_oficina_destino`
					, DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d-%m-%Y') AS `FECHA`
					, TIME_FORMAT(`e_movimiento`.`e_hora_emision`,'%r') AS `HORA`
					, CONCAT(RIGHT(CONCAT('00000', CAST(`e_movimiento`.`num_serie` AS CHAR)),4)
					, '-'
					, RIGHT(CONCAT('00000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS `NUM_GUIA`
					, `e_movimiento`.`e_total`
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
					, `e_mov_detalle`.`md_flete`
					, `e_mov_detalle`.`md_carrera`
					, `e_mov_detalle`.`md_importe`
					FROM `e_mov_detalle`
					WHERE `id_movimiento` = '".$id_boleta."';");
	$Mov_Array_List = $db_giro->get();
	// VERIFICAMOS SI SE OBTUVO DATOS
	if(count($Mov_Array) > 0)
	{
		$nom_completo_remit = utf8_encode($Mov_Array[0][0]);
		$nom_completo_consig = utf8_encode($Mov_Array[0][1]);
		$direccion =  utf8_encode($Mov_Array[0][2]);
		$id_agen_destino = $Mov_Array[0][3];
		$agen_dest_nombre = utf8_encode(OficinaByID($id_agen_destino));
		$fecha = $Mov_Array[0][4];
		$hora = $Mov_Array[0][5];
		$Num_Doc = $Mov_Array[0][6];
		$total_monto = $Mov_Array[0][7];
		$id_usuario = $Mov_Array[0][8];
		$us_nombre = UserByID($id_usuario) . '<br />' .$hora;
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
    <title>.::BOLETA::.</title>
	<!--  ESCRIPT PARA ORDENAR LA IMPRESION EN CUANTO CARGE LA PAGINA -->
		<script language="JavaScript">
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
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>.::TC Impresi&oacute;n Boleta::.</title>
    <!-- Script para validar el navegador -->
    <script language="javascript" src="js/navegador.js"> 
    </script>
</head>

<body onload="imprimir();" style="font-family:arial,verdana,sans-serif;"><!--  -->

<!--[if (IE 7) | (IE 8)]>
      <div class="buttom_doc" >

      <table width="100%" border="0">
            <tr>
                <th height="21" class="left_row">&nbsp;</th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="font-size:14px; font-weight:bold; text-align:right; padding-left: 40px;"><?php echo $total_monto;?></td>
            </tr>
            <tr>
                <th height="33" class="left_row">&nbsp;</th>
                <td>&nbsp;</td>
                <td colspan="3" class="right_left" style="padding-left:200px; vertical-align:top;"><?php echo $us_nombre;?></td>
                <td>&nbsp;</td>
            </tr>
        </table>

    </div>
    <div class="content">
      <table width="100%" border="0">
        <tr>
        <th height="46" colspan="6" style="padding-left:150px;"><?php echo $fecha; ?></th>
        </tr>
        <tr>
        <th height="26" colspan="6" style="padding-left:180px; vertical-align:top;"><?php echo $Num_Doc; ?></th>
        </tr>
        <tr>
        <th width="80" height="16" class="left_row"></th>
        <td width="454"><?php echo $nom_completo_remit;?></td>
        <td colspan="2">&nbsp;</td>
        <td width="86">&nbsp;</td>
        <td width="64">&nbsp;</td>
        </tr>
        <tr>
        <th height="16" class="left_row"></th>
        <td><?php echo $nom_completo_consig;?></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <th height="16" class="left_row"></th>
        <td><?php echo $direccion; ?></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <th height="16" class="left_row"></th>
        <td><?php echo $agen_dest_nombre;?></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <th height="30" class="left_row">&nbsp;</th>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
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
        echo '<tr>';
          echo '<th class="right_align" style="padding-right:60px; padding-bottom: 10px;">'.$cantidad.'</th>';
          echo '<td class="left_align"  style="padding-bottom: 10px; ">'.$descripcion.'</td>';
          echo '<td colspan="2" style="padding-bottom: 10px; ">'.$flete.'</td>';
          echo '<td style="padding-bottom: 10px; ">'.$carrera.'</td>';
          echo '<td style="padding-bottom: 10px; ">'.$importe.'</td>';
        echo '</tr>';
      }
    ?>
      </table>
    </div>
<![endif]-->

<!--[if FF ]>-->
    <div class="buttom_doc" style="letter-spacing: 0px; font-size:15px; " >

      <table width="100%" border="0">
            <tr>
                <th height="21" class="left_row">&nbsp;</th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="font-size:24px; font-weight:bold; text-align:right; padding-left: 40px;"><?php echo $total_monto;?></td>
            </tr>
            <tr>
                <th height="33" class="left_row">&nbsp;</th>
                <td>&nbsp;</td>
                <td colspan="3" class="right_left" style="padding-left:290px; vertical-align:top; line-height:14px; "><?php echo $us_nombre;?></td>
                <td>&nbsp;</td>
            </tr>
        </table>

    </div>
    <div class="content" style="letter-spacing: 0px; font-size:15px;" >
      <table width="100%" border="0">
        <tr>
        <th height="46" colspan="6" style="padding-left:150px;"><?php echo $fecha; ?></th>
        </tr>
        <tr>
        <th height="20" colspan="6" style="padding-left:180px; vertical-align:top;"><?php echo $Num_Doc; ?></th>
        </tr>
        <tr style="line-height: 17px;">
        <th width="80" height="16" class="left_row"></th>
        <td width="454" ><?php echo $nom_completo_remit;?></td>
        <td colspan="2">&nbsp;</td>
        <td width="86">&nbsp;</td>
        <td width="64">&nbsp;</td>
        </tr>
        <tr style="line-height: 17px;">
        <th height="16" class="left_row"></th>
        <td ><?php echo $nom_completo_consig;?></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <tr style="line-height: 17px;">
        <th height="16" class="left_row"></th>
        <td ><?php echo $direccion; ?></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <tr style="line-height: 17px;">
        <th height="16" class="left_row"></th>
        <td><?php echo $agen_dest_nombre;?></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <th height="27" class="left_row">&nbsp;</th>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
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
        echo '<tr>';
          echo '<th class="right_align" style="padding-right:60px; padding-bottom: 10px;">'.$cantidad.'</th>';
          echo '<td class="left_align"  style="padding-bottom: 10px; ">'.$descripcion.'</td>';
          echo '<td colspan="2" style="padding-bottom: 10px; ">'.$flete.'</td>';
          echo '<td style="padding-bottom: 10px; ">'.$carrera.'</td>';
          echo '<td style="padding-bottom: 10px; ">'.$importe.'</td>';
        echo '</tr>';
      }
    ?>
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
