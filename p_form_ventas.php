<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	//require_once 'cnn/config_trans.php';	
	session_start();
	require_once("is_logged.php");	
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
  <!--css para configuracion de buses-->
  <link rel="stylesheet" type="text/css" href="./css/stilo.css">
  <!--inicio de estilos para los checkbox como select multiple-->
  <style type="text/css">
   td ul { height: 100px; overflow: auto; width: 250px; border: 0.5px solid #000;}
   td ul { list-style-type: none; margin: 0; padding: 0; overflow-x: hidden; }   
   /*label { display: block; color: WindowText; background-color: Window; margin: 0; padding: 0; width: 100%; }*/
   label { display: block; margin: 0; padding: 0; width: 100%; }
   
  </style>
  <!--Fin de estilos para los checkbox-->
  
  <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>-->
  <script src="js/jquery_tabs.js" type="text/javascript"></script>
  <script type="text/javascript" src="js/tabs.js"></script>
  <!--javascript para los chekbox con select-->
  <script type="text/javascript" src="js/select_multiple.js"></script>
  <!--Convertir numeros a letras-->
  <script type="text/javascript" src="js/numero_to_letras.js"></script>
  
  <title>.:: Ventas de Pasajes ::.</title>
  
  <!-- Start:Ajax para Obtener series y Numeros de los Documentos -->
  <script type="text/javascript" src="js/buscar/ajax.js"></script>
  <script type="text/javascript" src="js/buscar/ajax-dynamic-list.js"></script>
  <!--validacion de formularios-->
  <script type="text/javascript" src="js/js/validarforms.js"> </script>
  
  <!-- Links para el calendario -->
  <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
  <SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118">
  </script>
  <!-- Links para el calendario -->
  
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

  <!--Script para los select multiple de asientos reservados-->
  <link rel="stylesheet" type="text/css" href="js/select_multiple/jquery.multiselect.css" />
  <link rel="stylesheet" type="text/css" href="js/select_multiple/style.css" />
  <link rel="stylesheet" type="text/css" href="js/select_multiple/prettify.css" />
  <link rel="stylesheet" type="text/css" href="js/select_multiple/jquery-ui.css" />
  <script type="text/javascript" src="js/select_multiple/jquery.js"></script>
  <script type="text/javascript" src="js/select_multiple/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/select_multiple/prettify.js"></script>
  <script type="text/javascript" src="js/select_multiple/jquery.multiselect.js"></script>  
  <!--Fin de script para los select multiple-->
  <script type="text/javascript" src="js/turismoJS.js"> </script>
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
  
      <!-- START B.1 MAIN CONTENT -->
      <?php //include_once('content/form-bus.php'); 
      include_once('ventas/p_form_ventas_content.php');?>
	  <!--END B.1 MAIN CONTENT -->
	  
    </div>
	<!-- END B. MAIN -->
      
    <!-- START C. FOOTER AREA -->
    <?php include_once('footer.php'); ?>
	<!-- END C. FOOTER AREA -->
	      
  </div> 
  <!-- END Main Page Container -->
</body>
</html>
