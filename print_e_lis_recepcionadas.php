<?PHP
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	require_once 'cnn/config_giro.php';
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
	$WHERE = '';
	if (isset($_GET['AGENCIA']) && $_GET['AGENCIA'] > 0)
	{
		$WHERE = 'AND `e_movimiento`.`id_oficina_origen` = ' .$_GET['AGENCIA'];
	}
	$Of_direccion = '';
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	// OBTENEMOS LOS DATOS DEL MOVIMIENTO
	$db_giro->query("SELECT
	`e_movimiento`.`id_oficina_origen`
	, RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4) AS `SERIE`
	, RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8) AS `NUM_BOLETA`
	, LEFT(IF(`e_persona`.`per_tipo` = 'PERSONA',`e_persona`.`per_nombre`, `e_persona`.`per_razon_social`),33) 
	AS `CONSIGNATARIO`
	, LEFT(CAST(CONCAT(`e_mov_detalle`.`md_cantidad`
	, ' '
	, `e_mov_detalle`.`md_descripcion`) AS CHAR),33) AS 'DESCRIPCION'
	FROM `e_movimiento`
	INNER JOIN `e_persona`
	ON `e_movimiento`.`id_consignatario` = `e_persona`.`id_persona`
	INNER JOIN `e_mov_detalle`
	ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
	INNER JOIN `e_md_operacion`
	ON `e_md_operacion`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
	AND `e_md_operacion`.`e_num_item` = `e_mov_detalle`.`e_num_item`
	WHERE `e_movimiento`.`id_oficina_destino` = ".$_SESSION['ID_OFICINA']."
	AND `e_md_operacion`.`mdo_fecha` = '".$_GET['FECHA']."'
	AND `e_movimiento`.`e_documento` != 'GUIA INTERNA'
	AND `e_mov_detalle`.`md_estado` = 3
	AND `e_md_operacion`.`tipo_operacion` = 1
	".$WHERE."
	GROUP BY `e_movimiento`.`num_serie` 
	, `e_movimiento`.`num_documento`
	ORDER BY `e_movimiento`.`id_oficina_origen`
	, `e_movimiento`.`num_serie` ASC
	, `e_movimiento`.`num_documento` ASC");
	$Mov_Array = $db_giro->get();
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
    <title>.::List. Enc. Recepcionadas::.</title>
	<!-- Hoja de Estilos -->
	<link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/print_e_lis_recepcionadas.css" />
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
</head>

<body onload="imprimir();"><!--   -->
<div class="content"  style="text-align:left;">
  <div class="content"  style="text-align:left;">
    <table width="100%" border="1" style="border:1px solid;">
      <tr>
        <th style="width:70px; height:20px;"># GUIA</th>
        <th style="width:270px;">CONSIGNATARIO / ENCOMIENDA</th>
        <th style="width:80px;">ORIGEN</th>
        <th>DNI / DIRECCI&Oacute;N</th>
        <th style="width:180px;">FIRMA</th>
      </tr>
    </table>
<?php
		if (count($Mov_Array) > 0)
		{
			$CUR_SERIE = "";
			$CUR_DOCUMENTO = "";
			$cont = 1;
			for ($fila = 0; $fila < count($Mov_Array); $fila++ )
			{
				$ID_OFICINA_ORIGEN = $Mov_Array[$fila][0];
				$SERIE = $Mov_Array[$fila][1];
				$DOCUMENTO = $Mov_Array[$fila][2];
				$CONSIG = utf8_encode($Mov_Array[$fila][3]);
				$DESCRIPCION = utf8_encode($Mov_Array[$fila][4]);
				$NOM_OFICINA = OficinaByID($ID_OFICINA_ORIGEN);
				echo '<table width="100%" border="0">';
				echo '<tr>';
					echo '<td style="height:23px;width:72px;border-left:1px solid; border-left-width:1px; border-top:1px solid; border-top-width:1px;">'.$SERIE.'</td>';
					echo '<td style="width:270px;border-left:1px solid; border-left-width:1px;border-top:1px solid; border-top-width:1px;">'.$CONSIG.'</td>';
					echo '<td style="width:80px;text-align:center;border-left:1px solid; border-left-width:1px;border-top:1px solid; border-top-width:1px;">'.substr($NOM_OFICINA,0,8).'</td>';
					echo '<td style="border-left:1px solid; border-left-width:1px;border-top:1px solid; border-top-width:1px;">&nbsp;</td>';
					echo '<td style="width:180px;border-left:1px solid; border-left-width:1px;border-top:1px solid; border-top-width:1px;border-right:1px solid; border-right-width:1px;">&nbsp;</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td style="height:23px;width:70px;border-left:1px solid; border-left-width:1px;">'.$DOCUMENTO.'</td>';
					echo '<td style="width:270px;border-left:1px solid; border-left-width:1px;">'.$DESCRIPCION.'</td>';
					echo '<td style="width:80px;text-align:center;border-left:1px solid; border-left-width:1px;">'.substr($NOM_OFICINA,8,8).'</td>';
					echo '<td style="border-left:1px solid; border-left-width:1px;">&nbsp;</td>';
					echo '<td style="width:180px;border-left:1px solid; border-left-width:1px;border-right:1px solid; border-right-width:1px;">&nbsp;</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td style="height:23px;width:70px;border-left:1px solid; border-left-width:1px;border-bottom:solid; border-bottom-width:1px;">&nbsp;</td>';
					echo '<td style="width:270px;border-left:1px solid; border-left-width:1px;border-bottom:solid;  border-bottom-width:1px;"></td>';
					echo '<td style="width:80px; text-align:center;border-left:1px solid; border-left-width:1px;border-bottom:solid;  border-bottom-width:1px;">'.substr($NOM_OFICINA,16,8).'</td>';
					echo '<td style="border-left:1px solid; border-left-width:1px;border-bottom:solid;  border-bottom-width:1px;">&nbsp;</td>';
					echo '<td style="width:180px;border-left:1px solid; border-left-width:1px;border-right:1px solid; border-right-width:1px; border-bottom:solid;  border-bottom-width:1px;">&nbsp;</td>';
				echo '</tr>';
				echo '</table>';
				if ($cont == 14)
				{
					echo '<div class="page-break"></div>';
					$cont == 0;
				}
				$cont++;
			}
		}
		else
		{
			echo '<tr>';
				echo '<td colspan="8" style="text-align:center;">NO HAY REGISTROS.</td>';
			echo '</tr>';
		}
?>
      
    </table>
  </div>
</div>
</body>
</html>
