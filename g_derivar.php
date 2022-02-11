<?php 
	require_once 'cnn/config_trans.php';
	// Verificamos si el usuario ha iniciado sesión
	session_start();
	require_once "is_logged.php";
	// Obtenemos los datos de la tabla PAGES
	if(!isset($_SESSION['OFICINAS']))
	{
		$db_transporte->query("SELECT `of`.`idoficina`, `of`.`oficina`
				FROM `oficinas` as `of`
				ORDER BY `of`.`oficina`;");
		$_SESSION['OFICINAS'] = $db_transporte->get();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

<head>
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
  <!-- Hoja de Estilos para lista de busqueda de personas-->
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/buscar_persona.css" />
  <!-- Icono -->
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
  
  <!-- Links para el calendario -->
  <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
  <SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118">
  </script>
  <!-- Links para el calendario -->
	
  <title>.::TC Derivar Giro::.</title>
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/validacion_textfield.js"> 
  </script>
  
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/validate_numbers.js"> 
  </script>
  
  <!-- Script para Separar los Apellidos y Nombres -->
  <script language="javascript" src="js/numero_to_letras.js"> 
  </script>
  
  <!-- Start:Ajax para Obtener series y Numeros de los Documentos -->
  <script type="text/javascript" src="ajax/ajax_tipo_doc.js">
  </script>
  <!-- End:Ajax para Obtener series y Numeros de los Documentos -->
  
  <!-- Start:Ajax para Obtener series y Numeros de los Documentos -->
  <script type="text/javascript" src="js/buscar/ajax.js"></script>
  <script type="text/javascript" src="js/buscar/ajax-dynamic-list.js"></script>
  <script type="text/javascript" src="js/ajax-get-users.js"></script>
  <script type="text/javascript" src="js/ajax-get-documento.js"></script>
  <!-- Start:Ajax para Obtener series y Numeros de los Documentos -->
  <!--[if lt IE 9]> <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script> <![endif]-->
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

<body OnLoad="document.giro_form.txt_fecha.focus();"  <?php if(isset($_SESSION['IS_LOGGED'])) echo 'onbeforeunload="ConfirmClose()" onunload="HandleOnClose()"'; ?>>
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
      <?php include_once('content/g_derivar_content.php'); ?>
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