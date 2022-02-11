<?php 
	// INICIAMOS LAS SESIONES
	session_start();
	// Verificamos si el usuario ha iniciado sesin
	require_once("is_logged.php");
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
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/print_vale.css" />
  <!-- Icono -->
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
  
  <title>.::TC Pagar Giro::.</title>
	<script language="JavaScript">
		function imprimir()
		{
			window.print();
			window.onfocus = function() 
			{
				window.open('','_parent','');
	
				window.close;
			}
		}
	</script>
    <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  
  <!-- Script para validar el navegador -->
  <script language="javascript" src="js/navegador.js"> 
  </script>
</head>

<!-- Global IE fix to avoid layout crash when single word size wider than column width -->
<!--[if IE]><style type="text/css"> body {word-wrap: break-word;}</style><![endif]-->

<body  <?php if(isset($_SESSION['IS_LOGGED'])) echo 'onbeforeunload="ConfirmClose()" onunload="HandleOnClose()"'; ?>>
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
      <?php include_once('content/g_entrega_action_content.php'); ?>
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



