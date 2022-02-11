<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	if (!isset($_SESSION['INTENTOS_SESION']))
	{
		//VARIABLE PARA CONTROLAR LOS INTENTOS DE SESION
		$_SESSION['INTENTOS_SESION'] = 0;
	}
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
  <!-- Icono -->
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
  
  <title>.::Turismo Central::.</title>
  
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  <!-- Script para validar el navegador -->
  <script language="javascript" src="js/navegador.js"> 
  </script>
  
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
      <!-- B.1 MAIN CONTENT -->
        <div class="main-content">
            <h1 class="pagetitle">Consejos y Sugerencias</h1>
			<!-- Content unit - One column -->
			<h1 class="block">Como crear una contrase&ntilde;a segura.</h1>
			<div class="column1-unit">
			  <h1>M&eacute;todo Acr&oacute;stico</h1>
			  <h2>Paso 1 :</h2>                             
			  <p>Piense en una frase, la que ud. recuerda con mas frecuencia o una frase que mensione frecuentemente.</p>
			  <h3>Ejem.</h3>
			  <p>La vida sigue su curso, t&uacute; toma parte de ella.</p>
			  <h2>Paso 2 :</h2> 
			  <p>Separe solo la primera letra de cada palabra.</p>
			  <h3>Ejem.</h3>
			  <p><span style="font-size:22px;">L</span>a <span style="font-size:22px;">V</span>ida <span style="font-size:22px;">S</span>igue <span style="font-size:22px;">S</span>u <span style="font-size:22px;">C</span>urso, <span style="font-size:22px;">T</span>&uacute; <span style="font-size:22px;">T</span>oma <span style="font-size:22px;">P</span>arte <span style="font-size:22px;">D</span>e <span style="font-size:22px;">E</span>lla.</p>
			  <h2>Paso 3 :</h2>
			  <p>Junte las letras y obtendra su nueva contrase&ntilde;a</p>
			  <h3>Ejem.</h3>
			  <p><span style="font-size:22px;">LVSSCTTPDE</span></p>
			  <p>Entonces, cada vez que quiera recordar su contrase&ntilde;a, solo recuerde la frase y contruya su contrase&ntilde;a. </p>
            </div>
            <!-- Limpiar Unidad del Contenido -->
            <hr class="clear-contentunit" />
          
        </div>
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



