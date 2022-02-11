<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	//require_once 'cnn/config_trans.php';	
	session_start();
	require_once("is_logged_niv2.php");
	// FUNCION PARA LA PAGINACION
	require("function/Paginacion.php");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es">

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
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_setup.css" />
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_text.css" />
  <!-- Hoja de Estilos para lista de busqueda de personas-->
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/buscar_persona.css" />
  <!--<link rel="stylesheet" type="text/css" href="./css/stilo.css">-->
	<link rel="stylesheet" type="text/css" href="./css/stilo.css">
  <!-- Icono -->
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
  
  <title>.:: Configuración de Buses ::.</title>
  
  <!--validacion de formularios-->
  <script type="text/javascript" src="js/js/validarforms.js"> </script>
    
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/validate_numbers.js"> 
  </script>
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/validacion_textfield.js"> 
  </script>
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  <!-- Script para validar el navegador -->
  <?php
  	if ($_SESSION['TIPO_USUARIO'] == 1)
	{
		echo '<script language="javascript" src="js/navegador.js"></script>';
	}
  ?>
</head>

<!-- Global IE fix to avoid layout crash when single word size wider than column width -->
<!--[if IE]><style type="text/css"> body {word-wrap: break-word;}</style><![endif]-->

<body <?php if(isset($_SESSION['IS_LOGGED'])) echo 'onbeforeunload="ConfirmClose()" onunload="HandleOnClose()"'; ?>>
  <!-- START Main Page Container -->
  <div class="page-container">

   <!-- For alternative headers START PASTE here -->

    <!-- START A. HEADER -->
	<?php include_once('header.php'); ?>
	<!-- END A. HEADER -->

   <!-- For alternative headers END PASTE here -->

    <!-- START B. MAIN -->
    <div class="main">
		<div class="main-content">
			<h1 class="pagetitle">Configuración de Bus</h1>
			<!--CONTENIDO DEL FORMULARIO DE CONFIGURACION-->
			<div class="column1-unit">
				<h1>Ingrese Número de Filas - <span>Recuerde Ingresar Solo Números</span></h1>
				<div class="column1-unit">
					<div class="contactform2">												
						<div id='formulario'>
							<!--Coge el valor id del bus-->
							<?php $bus=$_GET[ID];
							if(isset($_GET['ID'])){
							?>							
							<form name="config_bus" method="post" id="config_bus" action="p_config_bus.php?configuracion=<?php echo $bus;?>">
								<div style="float:left; width:40%;">
									<p>Ingrese Número de Filas Primer Piso:</p>
									
									<p>Ingrese Número de Filas Segundo Piso:</p>
								</div>
								<div style="float:left; width:40%;">
									<p><input name="piso1" type="text" id="piso1" value="" tabindex="1" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Ingrese Número de Filas Primer Piso" style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></p></br>									
									<p><input name="piso2" type="text" id="piso2" value="" tabindex="2" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Ingrese Número de Filas Segundo Piso" style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></p></br>
									<p><input name="btn_generar" id="btn_generar" type="submit" class="button" value="Generar" tabindex="3" onclick="this.disabled = 'true'; this.value = 'Enviando...';" /></p>
								</div>
							</form>	
							<?php
							}
							?>												
					</div>	
					<?php
					/////////INICIO CONFIGURACION DE PIMER PISO///////////////////
							if (isset($_GET['configuracion'])){	
								$id_buses=$_GET['configuracion'];
								$fila_piso_1=$_POST[piso1];	
								$fila_piso_2=$_POST[piso2];															
							?>		
							<div id='formulario2'>					
								<form name="bus_form" method='post' id="bus_form" action="p_config_action.php?insert=<?php echo $id_buses;?>">																								
										<div id='contenido_izquierdo' style="float:left; width:45%;">
											<p style="text-align:center;">Primer Piso</p>
											<?php
												for($i=0;$i<$fila_piso_1;$i++){
											?>
											<div style="float:left; width:20%;">
												<p><input id='n1' type='text' name="n1[<?php echo $i; ?>]" value="" title="Ingresar Número"  style="width:40px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></p>
											</div>
											<div style="float:left; width:20%;">
												<p><input id='n2' type='text' name="n2[<?php echo $i; ?>]" value="" title="Ingresar Número"  style="width:40px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></p>
											</div>
											<div style="float:left; width:20%;">
												<p><input id='n3' type='text' name="n3[<?php echo $i; ?>]" value="" title="Ingresar Número"  style="width:40px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></p>
											</div>
											<div style="float:left; width:20%;">
												<p><input id='n4' type='text' name="n4[<?php echo $i; ?>]" value="" title="Ingresar Número"  style="width:40px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></p>
											</div>
											<div style="float:left; width:20%;">
												<p><input id='n5' type='text' name="n5[<?php echo $i; ?>]" value="" title="Ingresar Número"  style="width:40px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></p>
											</div>
											<?php
												}
											?>
										</div>
										
										<div id='contenido_derecho' style="float:left; width:45%;">
											<p style="text-align:center;">Segundo Piso</p>
											<?php
												for($i=0;$i<$fila_piso_2;$i++){
											?>
											<div style="float:left; width:20%;">
												<p><input id='p1' type='text' name="p1[<?php echo $i; ?>]" value="" title="Ingresar Número"  style="width:40px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></p>									
											</div>
											<div style="float:left; width:20%;">
												<p><input id='p2' type='text' name="p2[<?php echo $i; ?>]" value="" title="Ingresar Número"  style="width:40px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></p>
											</div>
											<div style="float:left; width:20%;">
												<p><input id='p3' type='text' name="p3[<?php echo $i; ?>]" value="" title="Ingresar Número"  style="width:40px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></p>
											</div>
											<div style="float:left; width:20%;">
												<p><input id='p4' type='text' name="p4[<?php echo $i; ?>]" value="" title="Ingresar Número"  style="width:40px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></p>
											</div>
											<div style="float:left; width:20%;">
												<p><input id='p5' type='text' name="p5[<?php echo $i; ?>]" value="" title="Ingresar Número"  style="width:40px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></p>
											</div>
											<?php
												}
											?>
										</div>
									<table>
										<tr>
											<td colspan="2" style="text-align:center;font-size:140%; background-color:#FFFFFF;" id="132">
												<input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.giro_form.submit();" />
											</td>
										</tr>
									</table>																	
								</form>
							</div>
							<?php
							}
							/////////FIN CONFIGURACION DE PIMER PISO///////////////////
							?>						
						</div>			
					
				</div>
			</div>
		
		</div>
	  
    </div>
	<!-- END B. MAIN -->
      
    <!-- START C. FOOTER AREA -->
    <?php include_once('footer.php'); ?>
	<!-- END C. FOOTER AREA -->
	      
  </div> 
  <!-- END Main Page Container -->
</body>
</html>
