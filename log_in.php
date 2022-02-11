<?php
	// ARCHIVO NECESARIO PARA LAS CONSULTAS
	session_start();
	require_once 'cnn/config_trans.php';
	
	// Colocar la zona horario por defento
	date_default_timezone_set('America/Lima');
	
	// Obtenemos los datos de la tabla PAGES
	if(!isset($_SESSION['OFICINAS']))
	{
		$db_transporte->query("SELECT oficinas.`idoficina`,oficinas.`oficina`
		FROM `oficinas` AS oficinas ORDER BY oficinas.`oficina`");
		$_SESSION['OFICINAS'] = $db_transporte->get();
	}
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es">	

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
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_setup.css" />
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_text.css" />
  <!-- Icono -->
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
  
  <title>.::T.C. - Inicio Sesi&oacute;n::.</title>
  
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/validate_numbers.js"> 
  </script>
  
  <!-- Script para validar el navegador 
  <script language="javascript" src="js/navegador.js"> 
  </script>-->
  
  <script language="JavaScript1.2">
	window.moveTo(0,0);
	if (document.all) {
		top.window.resizeTo(screen.availWidth,screen.availHeight);
	}
	else if (document.layers||document.getElementById) {
		if (top.window.outerHeight<screen.availHeight||top.window.outerWidth<screen.availWidth){
			top.window.outerHeight = screen.availHeight;
			top.window.outerWidth = screen.availWidth;
		}
	}
  </script>
</head>

<!-- Global IE fix to avoid layout crash when single word size wider than column width -->
<!--[if IE]><style type="text/css"> body {word-wrap: break-word;}</style><![endif]-->

<body>
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
      <?php include_once('content/log_in_content.php'); ?>
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



