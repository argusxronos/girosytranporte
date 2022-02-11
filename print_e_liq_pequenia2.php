<?PHP
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	require_once 'cnn/config_master.php';
	/********************************************/
	/* CARGAMOS LOS DATOS DE LA BOLETA DE VENTA */
	/********************************************/
	$id_liquidacion = 0;
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
	// OBTENEMOS EL ID_MOVIMIENTO
	if (isset($_GET['ID']) && strlen($_GET['ID']) > 0)
	{
		$id_liquidacion = $_GET['ID'];
	}
	// OBTENEMOS LOS DATOS DEL MOVIMIENTO
	$db_giro->query("SELECT `e_liquidacion`.`id_oficina_origen`
					, `e_liquidacion`.`id_oficina_destino`
					, `e_liquidacion`.`id_usuario`
					, DATE_FORMAT(`e_liquidacion`.`liq_fecha`,'%d&nbsp; -  %m&nbsp; - %Y') AS 'FECHA'
					, CAST(TIME_FORMAT(`e_liquidacion`.`liq_hora`,'%r') AS CHAR) AS 'HORA'
					, `e_liquidacion`.`liq_chofer`
					, `e_liquidacion`.`liq_pullman`
					, `e_liquidacion`.`liq_total_guias`
					, `e_liquidacion`.`liq_comision`
					, `e_liquidacion`.`liq_total_importe`
					, `e_liquidacion`.`liq_saldo_importe`
					, `e_liquidacion`.`liq_total_carrera`
					, `e_liquidacion`.`liq_saldo_carrera`
					, RIGHT(CONCAT('0000000',CAST(`e_liquidacion`.`liq_num_doc` AS CHAR)),8) AS `DOC`
					FROM `e_liquidacion`
					WHERE `e_liquidacion`.`id_liquidacion` = ".$id_liquidacion."
					LIMIT 1;");
	$Mov_Array = $db_giro->get();


	$db_giro->query("SELECT 
					`e_movimiento`.`id_oficina_destino`
					, CAST(CONCAT(`e_movimiento`.`num_serie`
					, '-'
					, `e_movimiento`.`num_documento`) AS CHAR) AS `GUIA`
					, LEFT(IF(`CONSIG`.`per_tipo` = 'PERSONA', `CONSIG`.`per_nombre`, `CONSIG`.`per_razon_social`), 28	) AS `CONSIGNATARIO`
					, LEFT(CAST(CONCAT(`e_mov_detalle`.`md_cantidad`
					, '-'
					, `e_mov_detalle`.`md_descripcion`) AS CHAR),26) AS `DETALLE`
					, `e_mov_detalle`.`md_carrera`
					, `e_mov_detalle`.`md_flete`
					, `e_liquidacion_detalle`.`id_liquidacion`
					FROM `e_movimiento`
					INNER JOIN `e_persona` AS `CONSIG`
					ON `e_movimiento`.`id_consignatario` = `CONSIG`.`id_persona`
					INNER JOIN `e_mov_detalle`
					ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
					INNER JOIN `e_liquidacion_detalle`
					ON `e_liquidacion_detalle`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
					AND `e_liquidacion_detalle`.`e_num_item` = `e_mov_detalle`.`e_num_item`
					WHERE `e_liquidacion_detalle`.`id_liquidacion` = ".$id_liquidacion."
					GROUP BY `e_movimiento`.`id_oficina_destino`
					, `e_mov_detalle`.`id_movimiento`
					, `e_mov_detalle`.`e_num_item`
					, `e_movimiento`.`num_serie`
					, `e_movimiento`.`num_documento`
					ORDER BY `e_movimiento`.`id_oficina_destino`
					, `e_movimiento`.`num_serie` ASC
					, `e_movimiento`.`num_documento` ASC
					, `e_mov_detalle`.`id_movimiento`
					, `e_mov_detalle`.`e_num_item`;");
	$Mov_Array_List = $db_giro->get();
	// VERIFICAMOS SI SE OBTUVO DATOS
	if(count($Mov_Array) > 0)
	{
		$oficina_origen = $Mov_Array[0][0];
		$oficina_origen_nombre = OficinaByID($oficina_origen);
		$oficina_destino = $Mov_Array[0][1];
		$oficina_destino_nombre = OficinaByID($oficina_destino);
		$id_usuario = $Mov_Array[0][2];
		$nombre_usuario = UserByID($id_usuario);
		$fecha = $Mov_Array[0][3];
		$hora = $Mov_Array[0][4];
		$chofer = $Mov_Array[0][5];
		$pullman = $Mov_Array[0][6];
		$total_guias = $Mov_Array[0][7];
		$comision = $Mov_Array[0][8];
		$total_importe = $Mov_Array[0][9];
		$saldo_importe = $Mov_Array[0][10];
		$total_carrera = $Mov_Array[0][11];
		$saldo_carrera = $Mov_Array[0][12];
		$num_doc = $Mov_Array[0][13];
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
    <meta name="author" content="Jonatan Rivera C." />
    <meta name="distribution" content="global" />
    <meta name="description" content="Your page description here ..." />
    <meta name="keywords" content="Your keywords, keywords, keywords, here ..." />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>.::Liquidaci&oacute;n Encomienda::.</title>
	<!-- Hoja de Estilos -->
	<link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/liq_pequenia.css" />
	<!-- Icono -->
	<link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
    
    
	<!--  ESCRIPT PARA ORDENAR LA IMPRESION EN CUANTO CARGE LA PAGINA -->
	<script language="JavaScript">
		function imprimir()
		{
			window.print();
		}
	</script>
</head>

<body onload="imprimir();" style="font-family:arial,verdana,sans-serif;"><!--  -->
<!--[if (IE 7) | (IE 8)]>

<div class="buttom_doc" style="text-align:left;">
  <table border="0">
    <tr>
      <td style="width:170px; padding-left:100px; height:27px; vertical-align:top"><?php echo $total_guias; ?></td>
      <td style="width:280px;">&nbsp;</td>
      <td style="width:90px; text-align:right;"><?php echo $total_carrera ; ?></td>
      <td style="width:90px; text-align:right;"><?php echo $total_importe; ?></td>
    </tr>
    <tr>
      <td style=" height:27px;">&nbsp;</td>
      <td style="padding-left:200px;"><?php echo $comision.' %'; ?></td>
      <td style="text-align:right">0.00</td>
      <td style="text-align:right"><?php echo round(($total_importe - $saldo_importe),2); ?></td>
    </tr>
    <tr>
      <td style=" height:27px;">&nbsp;</td>
      <td style="vertical-align:top; padding-left:40px;"><?php echo $nombre_usuario; ?></td>
      <td style="text-align:right"><?php echo $saldo_carrera; ?></td>
      <td style="text-align:right"><?php echo $saldo_importe; ?></td>
    </tr>
  </table>
</div>
<div class="content"  style="text-align:left;">
  <div class="content"  style="text-align:left;">
    <table width="100%" border="0">
      <tr>
        <td colspan="3" style="height:65px; vertical-align:bottom; text-align:right; padding-right:220px; padding-bottom:15px;"><?php echo $num_doc; ?></td>
      </tr>
      <tr>
        <td style="width:350px;">&nbsp;</td>
        <td style="width:250px;letter-spacing:4px;"><?php echo $oficina_origen_nombre; ?></td>
        <td style="width:200px;"><?php echo $fecha; ?></td>
      </tr>
      <tr>
        <td height="16" colspan="3" style="">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" style="height:27px; padding-left:40px;"><?php echo $oficina_destino_nombre; ?></td>
        <td width="22%"><?php echo $hora; ?></td>
      </tr>
      <tr>
        <td colspan="2" style="height:27px; padding-left:40px;"><?php echo $chofer; ?></td>
        <td width="22%"><?php echo $pullman; ?></td>
      </tr>
    </table>
    <table border="0">
      <tr style="height:40px;">
        <td style="text-align:left; width:100px; margin-top:2px;">&nbsp;</td>
        <td style="width:240px;text-align:left;">&nbsp;</td>
        <td style="text-align:left;width:235px;">&nbsp;</td>
        <td style="text-align:right; width:60px;">&nbsp;</td>
        <td style="text-align:right; width:90px;">&nbsp;</td>
      </tr>
      <?php
	$total_monto = 0;
	$total_carrera = 0;
	if (count($Mov_Array_List) > 0)
	{
		$Oficina_Actual = 0;
		$guia_actual = '';
		for ($fila = 0; $fila < count($Mov_Array_List); $fila ++)
		{
			$oficina = $Mov_Array_List[$fila][0];
			$guia = $Mov_Array_List[$fila][1];
			$consignatario = $Mov_Array_List[$fila][2];
			$descripcion = $Mov_Array_List[$fila][3];
			$carrera = $Mov_Array_List[$fila][4];
			$importe = $Mov_Array_List[$fila][5];
			$total_carrera = $total_carrera + $carrera;
			$total_monto = $total_monto + $importe;
			
			if ($Oficina_Actual != $oficina)
			{
				echo '<tr>';
					echo '<td colspan="6" style="text-align:center; font-weight:bold; padding-top:px; height:20px; letter-spacing:5px;"><span>'.OficinaByID($oficina).'</span></td>';
				echo '</tr>';
				$Oficina_Actual = $oficina;
			}
			
			echo '<tr id="div_tr_'.$id_movimiento.$num_item.'">';
				if ($guia_actual != $guia)
				{
					echo '<td style="text-align:left; height:16px;">'.$guia.'</td>';
					echo '<td>'.utf8_encode($consignatario).'</td>';
					$guia_actual = $guia;
				}
				else
				{
					echo '<td style="text-align:left; height:16px;">&nbsp;</td>';
					echo '<td>&nbsp;</td>';
				}
				echo '<td>'.utf8_encode($descripcion).'</td>';
				if ($carrera == '0.00')
				{
					echo '<td style="text-align:right;"></td>';
				}
				else
				{
					echo '<td style="text-align:right;">'.$carrera.'</td>';
				}
				if ($importe == '0.00')
				{
					echo '<td style="text-align:right;"></td>';
				}
				else
				{
					echo '<td style="text-align:right;">'.$importe.'</td>';
				}
				
			echo '</tr>';
			}
	}
	else
	{
		echo '<tr>';
			echo '<td colspan="5">No hay registros!</td>';
		echo '</tr>';
	}
?>
    </table>
  </div>
</div>
<![endif]-->
<!--[if FF ]>-->

<div class="buttom_doc" style="text-align:left; letter-spacing: 0px; font-size:16px; ">
  <table border="0">
    <tr>
      <td style="width:170px; padding-left:100px; height:37px; vertical-align:top"><?php echo $total_guias; ?></td>
      <td style="width:280px;">&nbsp;</td>
      <td style="width:90px; text-align:right;"><?php echo $total_carrera ; ?></td>
      <td style="width:90px; text-align:right;"><?php echo $total_importe; ?></td>
    </tr>
    <tr>
      <td style=" height:27px;">&nbsp;</td>
      <td style="padding-left:200px;"><?php echo $comision.' %'; ?></td>
      <td style="text-align:right">0.00</td>
      <td style="text-align:right"><?php echo round(($total_importe - $saldo_importe),2); ?></td>
    </tr>
    <tr>
      <td style=" height:27px;">&nbsp;</td>
      <td style="vertical-align:top; padding-left:40px;"><?php echo $nombre_usuario; ?></td>
      <td style="text-align:right"><?php echo $saldo_carrera; ?></td>
      <td style="text-align:right"><?php echo $saldo_importe; ?></td>
    </tr>
  </table>
</div>
<div class="content"  style="text-align:left; letter-spacing: 0px; font-size:13px; ">
  <div class="content"  style="text-align:left; letter-spacing: 0px; font-size:13px; ">
    <table width="100%" border="0">
      <tr>
        <td colspan="3" style="height:80px; vertical-align:bottom; text-align:right; padding-right:400px; padding-bottom:15px;"><?php echo $num_doc; ?></td>
      </tr>
      <tr>
        <td style="width:350px;">&nbsp;</td>
        <td style="width:250px;letter-spacing:4px;"><?php echo $oficina_origen_nombre; ?></td>
        <td style="width:200px;"><?php echo $fecha; ?></td>
      </tr>
      <tr>
        <td height="16" colspan="3" style="">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" style="height:27px; padding-left:40px;"><?php echo $oficina_destino_nombre; ?></td>
        <td width="22%"><?php echo $hora; ?></td>
      </tr>
      <tr>
        <td colspan="2" style="height:27px; padding-left:40px;"><?php echo $chofer; ?></td>
        <td width="22%"><?php echo $pullman; ?></td>
      </tr>
    </table>
    <table border="0">
      <tr style="height:40px;">
        <td style="text-align:left; width:100px; margin-top:2px;">&nbsp;</td>
        <td style="width:240px;text-align:left;">&nbsp;</td>
        <td style="text-align:left;width:235px;">&nbsp;</td>
        <td style="text-align:right; width:60px;">&nbsp;</td>
        <td style="text-align:right; width:90px;">&nbsp;</td>
      </tr>
      <?php
	$total_monto = 0;
	$total_carrera = 0;
	if (count($Mov_Array_List) > 0)
	{
		$Oficina_Actual = 0;
		$guia_actual = '';
		for ($fila = 0; $fila < count($Mov_Array_List); $fila ++)
		{
			$oficina = $Mov_Array_List[$fila][0];
			$guia = $Mov_Array_List[$fila][1];
			$consignatario = $Mov_Array_List[$fila][2];
			$descripcion = $Mov_Array_List[$fila][3];
			$carrera = $Mov_Array_List[$fila][4];
			$importe = $Mov_Array_List[$fila][5];
			$total_carrera = $total_carrera + $carrera;
			$total_monto = $total_monto + $importe;
			
			if ($Oficina_Actual != $oficina)
			{
				echo '<tr>';
					echo '<td colspan="6" style="text-align:center; font-weight:bold; padding-top:px; height:20px; letter-spacing:5px;"><span>'.OficinaByID($oficina).'</span></td>';
				echo '</tr>';
				$Oficina_Actual = $oficina;
			}
			
			echo '<tr id="div_tr_'.$id_movimiento.$num_item.'">';
				if ($guia_actual != $guia)
				{
					echo '<td style="text-align:left; height:16px;">'.$guia.'</td>';
					echo '<td>'.utf8_encode($consignatario).'</td>';
					$guia_actual = $guia;
				}
				else
				{
					echo '<td style="text-align:left; height:16px;">&nbsp;</td>';
					echo '<td>&nbsp;</td>';
				}
				echo '<td>'.utf8_encode($descripcion).'</td>';
				if ($carrera == '0.00')
				{
					echo '<td style="text-align:right;"></td>';
				}
				else
				{
					echo '<td style="text-align:right;">'.$carrera.'</td>';
				}
				if ($importe == '0.00')
				{
					echo '<td style="text-align:right;"></td>';
				}
				else
				{
					echo '<td style="text-align:right;">'.$importe.'</td>';
				}
				
			echo '</tr>';
			}
	}
	else
	{
		echo '<tr>';
			echo '<td colspan="5">No hay registros!</td>';
		echo '</tr>';
	}
?>
    </table>
  </div>
</div>
<!--[end if]-->
</body>
</html>
