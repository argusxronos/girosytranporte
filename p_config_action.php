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
  
  <!-- Icono -->
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
    	
  <title>.::Configuracion de Asientos :.</title>
  
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  <!-- Script para validar el navegador-->
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
    <?php     
		require_once 'cnn/config_master.php';
		if (isset($_GET['insert']))
			{
				//RECOGIENDO DATOS DE P_CONFIG_BUS-PHP
				$id_config='';
				$id_buses=$_GET['insert'];
				for($x=0,$filas=1;$x<count($_POST['n1']);$x++,$filas++){							
						$fn1=$_POST['n1'][$x];
						$fn2=$_POST['n2'][$x];
						$fn3=$_POST['n3'][$x];
						$fn4=$_POST['n4'][$x];
						$fn5=$_POST['n5'][$x];
						//$filas=$x+1;//muestra el numero de filas que existe
						$db_transporte->query("INSERT INTO configuracion_bus(configuracion_bus.`id_confbus`,configuracion_bus.`id_bus`,
							configuracion_bus.`fila`,configuracion_bus.`piso`,configuracion_bus.`n1`,configuracion_bus.`n2`,
							configuracion_bus.`n3`,configuracion_bus.`n4`,configuracion_bus.`n5`)
							VALUES('$id_config','$id_buses','$filas','1','$fn1','$fn2','$fn3','$fn4','$fn5')");
							
					/*OTRA FORMA DE GUARDAR DATOS 
					 $strquery='insert into necesidades (necesidad, cantidad, descripcion, uso, avances, solicita, responsable) values';
					for($i=0;$i<count(POST['necesidad']);$i++){
					$strquery.="('".POST['necesidad'][$i]."','".POST['cantidad'][$i]."','". POST['descripcion'][$i]."','". POST['uso'][$i]."','". POST['avances'][$i]."','". POST['solicita'][$i]."','". POST['responsable'][$i]."'),";
					} 
					*PAGINA DE EJEMPLO DE GUARDAR VARIOS DATOS A LA VEZ 
					http://www.todoexpertos.com/categorias/tecnologia-e-internet/desarrollo-de-sitios-web/php/respuestas/2038231/insertar-varios-registros-en-la-base-de-datos
					*/
				}
				for($i=0,$filas2=1;$i<count($_POST['p1']);$i++,$filas2++){							
						$fp1=$_POST['p1'][$i];
						$fp2=$_POST['p2'][$i];
						$fp3=$_POST['p3'][$i];
						$fp4=$_POST['p4'][$i];
						$fp5=$_POST['p5'][$i];
						//$filas=$x+1;//muestra el numero de filas que existe
						$db_transporte->query("INSERT INTO configuracion_bus(configuracion_bus.`id_confbus`,configuracion_bus.`id_bus`,
							configuracion_bus.`fila`,configuracion_bus.`piso`,configuracion_bus.`n1`,configuracion_bus.`n2`,
							configuracion_bus.`n3`,configuracion_bus.`n4`,configuracion_bus.`n5`)
							VALUES('$id_config','$id_buses','$filas2','2','$fp1','$fp2','$fp3','$fp4','$fp5')");
					
				}				
						
			}		
	?>
					<!-- B.1 MAIN CONTENT -->
		<div class="main-content">
			<?php
				if ($Error == true)
				{
					echo '<!-- Pagetitle -->';
					echo '<h1 class="pagetitle">Mensaje de Error</h1>';
					echo '<div class="column1-unit">';
					echo '<h1>Detalle del o los errores.</h1>';
					echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
					echo '<p>'.$MsjError.'</p>';
					/*<p class="details">| Posted by <a href="#">SiteAdmin </a> | Categories: <a href="#">General</a> | Comments: <a href="#">73</a> |</p>*/
					// BOTONES PARA REGRESAR A LA VENTENA ANTERIOR
			?>
				<p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; javascript:history.back(1)" ></p>
			<?php
					echo '<!-- Limpiar Unidad del Contenido -->';
					echo '<hr class="clear-contentunit" />';
				}
				else
				{
					// MOSTRAMOS EL MENSAJE DE OPERACION SATISFACTORIA
					echo '<!-- Pagetitle -->';
					
					if (isset($_GET['insert']))
					{
						echo '<h1 class="pagetitle">Mensaje de Confirmación</h1>';
						echo '<div class="column1-unit">';
						echo '<h1>Operación Exitosa.</h1>';
						echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
						echo '<p>El Registro se guardo; <span>Satisfactoriamente</span>.</p>';
					}					
			?>					
					<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_bus.php'" style="width:170px;" ></p>
			<?php
				}
			?>
		</div>
    </div>
	<!-- END B. MAIN -->
      
    <!-- START C. FOOTER AREA -->
    <?php include_once('footer.php'); ?>
	<!-- END C. FOOTER AREA -->
	      
  </div>
  <!-- END Main Page Container -->
  
  
  <!--
  
</div> -->
</body>
</html>
