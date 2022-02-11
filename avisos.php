<?php
	// definir la zona horaria predeterminada a usar. Disponible desde PHP 5.1
	date_default_timezone_set('America/Lima');
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
</head>

<body>
  <!-- START Main Page Container -->
  <div class="page-container">

   <!-- For alternative headers START PASTE here -->

   <!-- A. HEADER --> 
    <div class="header">
    	<div class="header-middle">    
        <!-- Site message -->
        <div class="sitemessage">
          <h1>TURISMO &bull; CENTRAL &bull; S.A.</h1>
          <h2>Bienvenidos a nuestra oficina Virtual.</h2>
          <h3><a href="#">&rsaquo;&rsaquo;&nbsp;M&aacute;s Detalles</a></h3>
        </div>        
    </div>
	</div>
   <!-- For alternative headers END PASTE here -->

    <!-- START B. MAIN -->
    <div class="main">
  
      <!-- B.1 MAIN CONTENT -->
        <div class="main-content">
            <hr class="clear-contentunit" />
            <br />
            <!-- Pagetitle -->
            <h1 class="pagetitle">Normas para el Sistema Giros</h1>
            <!-- Content unit - One column -->
            <!--<h1 class="block">Lista de Agencias Interconectadas al Nuevo Sistema.</h1>-->
            <div class="column1-unit">
              <h1>Gerencia General.</h1>
              <?php echo '<h3>'.date("j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>'; ?>
              <p>A: Todas las Agencias</p>
              <ol>
                <li>Para registrar a una persona se debe ingresar <strong><span>PRIMERO LOS APELLIDOS</span></strong> y luego los Nombres.</li>
                <li>El persona est&aacute; obligado a usar el nuevo sistema de giros, </li>
              </ol>
          </div>
            <!-- Limpiar Unidad del Contenido -->
            <hr class="clear-contentunit" />
          
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



