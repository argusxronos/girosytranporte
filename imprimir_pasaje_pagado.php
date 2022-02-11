<?PHP
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	/********************************************/
	/* CARGAMOS LOS DATOS DEL VALE PASAJE PAGADO */
	/********************************************/
		
	// OBTENEMOS EL CODIGO DE LA TABLA PASAJE PAGADO
	if (isset($_GET['codigo']))
	{
		$codigo_pasaje = $_GET['codigo'];		
	}
		
	require_once 'cnn/config_master.php';
	
	// OBTENEMOS LOS DATOS DE LA TABLA PASAJE_PAGADO PARA REALIZAR LA IMPRESION
	$db_transporte->query("SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje FROM pasaje_pagado
							WHERE id_pasaje_pagado='$codigo_pasaje'");
	
	$Pasaje_Array = $db_transporte->get();
	
	// VERIFICAMOS SI SE OBTUVO DATOS
	if(count($Pasaje_Array) > 0)
	{		
		// OBTENEMOS LOS VALORES DEL ARRAY
		$cliente=$Pasaje_Array[0][0];
		$detalle=$Pasaje_Array[0][1];
		$monto=$Pasaje_Array[0][2];
		$nro_guia=$Pasaje_Array[0][3];
		$fecha_viaje=$Pasaje_Array[0][4];
		$hora_viaje=$Pasaje_Array[0][5];
		$origen=$Pasaje_Array[0][6];
		$destino=$Pasaje_Array[0][7];
		$nro_pasaje=$Pasaje_Array[0][8];
		$usuario=strtoupper($_SESSION['USUARIO']);		
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
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
	<link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/vale_pasaje.css" />
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
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>.::TC Impresión de Vale de Pasaje Pagado::.</title>
    <!-- Script para validar el navegador -->
    <script language="javascript" src="js/navegador.js"> 
    </script>
</head>

<body onload="imprimir();"><!--  -->
<div class="content">	
	<div class="monto">S/.<?php echo number_format($monto,2);?></div>
	<div class="cliente">Cliente: </div>
	<div class="nombre"><?php echo $cliente;?></div>
	<div class="detalle">Detalle: </div>
	<div class="detalle_pasaje"><?php echo $detalle;?></div>
	<div class="nro_guia">N° Guia: </div>
	<div class="nro_guia_pasaje"><?php echo $nro_guia;?></div>
	<div class="f_viaje">Fecha Viaje: </div>
	<div class="fecha_viaje"><?php echo $fecha_viaje;?></div>
	<div class="h_viaje">Hora Viaje: </div>
	<div class="hora_viaje"><?php echo $hora_viaje;?></div>
	<div class="origen">Origen: </div>
	<div class="origen_pasaje"><?php echo $origen;?></div>
	<div class="destino">Destino: </div>
	<div class="destino_pasaje"><?php echo $destino;?></div>
	<div class="nro_pasaje">Pasaje N°: </div>
	<div class="nro_pasaje_boleto"><?php echo $nro_pasaje;?></div>
	<div class="fecha">Fecha Em.: </div>
	<div class="fecha_hoy"><?php echo date("Y-m-d");?></div>
	<div class="hora">Hora Em.: </div>
	<div class="hora_hoy"><?php echo date(" h:i A");?></div>
	<div class="usuario">Usuario: </div>
	<div class="trabajador"><?php echo $usuario;?></div>
	<div class="firma">____________________</div>
</div>

</body>
</html>
