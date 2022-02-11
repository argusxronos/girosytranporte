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
	function Dias_Semana($int)
	{
		switch($int)
		{
			case '01':
				return 'Domingo';
				break;
			case '02':
				return 'Lunes';
				break;
			case '03':
				return 'Martes';
				break;
			case '04':
				return 'Miercoles';
				break;
			case '05':
				return 'Jueves';
				break;
			case '06':
				return 'Viernes';
				break;
			case '07':
				return 'Sabado';
				break;
		}
	}
	
	function Mes_Anio($int)
	{
		switch ($int)
		{
			case '01':
				return "Enero";
				break;
			case '02':
				return "Febrero";
				break;
			case '03':
				return "Marzo";
				break;
			case '04':
				return "Abril";
				break;
			case '05':
				return "Mayo";
				break;
			case '06':
				return "Junio";
				break;
			case '07':
				return "Julio";
				break;
			case '08':
				return "Agosto";
				break;
			case '09':
				return "Setiembre";
				break;
			case '10':
				return "Octubre";
				break;
			case '11':
				return "Noviembre";
				break;
			case '12':
				return "Diciembre";
				break;
		}
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
	, LEFT(IF(`e_persona`.`per_tipo` = 'PERSONA'
	,`e_persona`.`per_nombre`, `e_persona`.`per_razon_social`)
	,33) 
	AS `CONSIGNATARIO`
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
	GROUP BY  IF(`e_persona`.`per_tipo` = 'PERSONA'
	,`e_persona`.`per_nombre`, `e_persona`.`per_razon_social`)
	ORDER BY `e_movimiento`.`id_oficina_origen`
	, IF(`e_persona`.`per_tipo` = 'PERSONA',`e_persona`.`per_nombre`, `e_persona`.`per_razon_social`)
	, IF(`e_persona`.`per_tipo` = 'PERSONA',`e_persona`.`per_nombre`, `e_persona`.`per_razon_social`) ASC");
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
    <title>.::List. Consignatarios::.</title>
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
 	<table width="100%" border="0">
    	<tr>
        	<td style="text-align:center; font-size:18px; letter-spacing:4px;">
			<?php 
				$date = $_GET['FECHA'];
				
				$fecha = new DateTime($date);
				echo Dias_Semana($fecha->format("d")) . ' ' .$fecha->format("d"). ' de ' .Mes_Anio($fecha->format("m")). ' del ' .$fecha->format("Y"); 
			?></td>
        </tr>
    </table>
    <br />
<?php
		if (count($Mov_Array) > 0)
		{
			$CUR_OFICINA = '';
			for ($fila = 0; $fila < count($Mov_Array); $fila++ )
			{
				$ID_OFICINA_ORIGEN = $Mov_Array[$fila][0];
				$CONSIG = utf8_encode($Mov_Array[$fila][1]);
				$NOM_OFICINA = OficinaByID($ID_OFICINA_ORIGEN);
				echo '<table width="100%" border="0">';
				if ($NOM_OFICINA != $CUR_OFICINA)
				{
					echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" >';
						echo '<td colspan="3"  style="text-align:center; font-weight:bold;border-bottom:solid;  border-bottom-width:1px; letter-spacing:4px;">'.$NOM_OFICINA.'</td>';
					echo '</tr>';
					$CUR_OFICINA = $NOM_OFICINA;
				}
				
				echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
					echo '<td style="width:60px;height:18px;">&nbsp;</td>';
					echo '<td style="width:360px;">* '.$CONSIG.'</td>';
					$ID_OFICINA_ORIGEN = $Mov_Array[$fila + 1][0];
					$CONSIG = utf8_encode($Mov_Array[$fila + 1][1]);
					$NOM_OFICINA = OficinaByID($ID_OFICINA_ORIGEN);
					if ($NOM_OFICINA == $CUR_OFICINA)
					{
						echo '<td style="width:360px;">* '.$CONSIG.'</td>';
						$fila++;
					}
					else
					{
						echo '<td style="width:360px;">&nbsp;</td>';
					}
				echo '</tr>';
				echo '</table>';
			}
		}
		else
		{
			echo '<table>';
				echo '<tr>';
					echo '<td colspan="2" style="text-align:center;">NO HAY REGISTROS.</td>';
				echo '</tr>';
			echo '</table>';
		}
?>
      
    </table>
  </div>
</div>
</body>
</html>
