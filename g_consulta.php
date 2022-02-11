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
      
	  <!-- B.1 MAIN CONTENT -->
		<div class="main-content">
			<!-- Pagetitle -->
            <h1 class="pagetitle">Anulaci&oacute;n de Giro.</h1>
        
            <!-- Content unit - One column -->
            <h1 class="block">Zona de Busqueda</h1>
            <form method="get" action="g_anulacion.php" name="buscar_giro" >
                    <table width="100%" border="0">
                        <tr>
                            <th>Serie :</th>
                            <th><input type="text" name="txt_serie" style="width:220px;" value="" /></th>
                            <th>N&uacute;mero Boleta :</th>
                            <th><input type="text" name="txt_num_boleta" style="width:220px;" value="" /></th>
                        </tr>
                        <tr>
                            <th colspan="2" style="text-align:right;">
                                <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="19" /></span>
                            </th>
                            <th colspan="2" style="text-align:left; ">
                                <span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="20" style="margin-left:35px;" /></span>
                            </th>
                        </tr>
                        
                    </table>
        
            </form>
            
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



