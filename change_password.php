<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("function/validacion.php");
	// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
	require_once 'cnn/config_trans.php';
	
	if(isset($_GET['change']))
	{
		// Variables para la OPERACION 
		$id_usuario = $_SESSION['ID_USUARIO'];
		$old_pass = '';
		$new_pass = '';
		$conf_pass = '';
		// OBTENEMOS LOS DATOS
		if (!isset($_POST['txt_old_pass']) || strlen($_POST['txt_old_pass']) == 0)
		{
			MsjErrores('Debe ingresar su contrasea actual.');
		}
		else
		{
			MinCaracteres($_POST['txt_old_pass'], 'Su Contrasea anterior',5);
			$old_pass = $_POST['txt_old_pass'];
			
		}
		
		if (!isset($_POST['txt_new_pass']) || strlen($_POST['txt_new_pass']) == 0)
		{
			MsjErrores('Debe ingresar su nueva contrasea.');
		}
		else
		{
			MinCaracteres($_POST['txt_new_pass'], 'Su Nueva Contrasea',5);
			$new_pass = $_POST['txt_new_pass'];
			
		}
		
		if (!isset($_POST['txt_conf_pass']) || strlen($_POST['txt_conf_pass']) == 0)
		{
			MsjErrores('Debe ingresar la confirmaci&oacute;n de su nueva contrase&ntilde;a.');
		}
		else
		{
			$conf_pass = $_POST['txt_conf_pass'];
			if ($conf_pass != $new_pass)
			{
				MsjErrores('Su contrase&ntilde;a nueva no coincide con la confirmaci&oacute;n.');
			}
		}
		if ($old_pass == $new_pass)
		{
			MsjErrores('La nueva Contrase&ntilde;a debe ser diferente a la anterior.');
		}
		// Verificamos si el usuario esta activo
		// VERIFICAMOS QUE EL REMITENTE REALMENTE NO ESTE REGISTRADO EN LA BD
		$db_transporte->query("SELECT COUNT(`tusuario`.`id_usuario`) AS 'ACTIVO' 
						FROM `tusuario` 
						WHERE `tusuario`.`id_usuario`='".$id_usuario."' 
						AND `tusuario`.`c_esta_activo` = 1;");		
		$esta_activo = $db_transporte->get('ACTIVO');
		if ($esta_activo == 0)
		{
			MsjErrores('Este Usuario no est&aacute;tivo.');
		}
		// VERIFICAMOS SI COINCIDE SU CONTRASEA ANTERIOR
		$db_transporte->query("SELECT COUNT(`tusuario`.`id_usuario`) AS 'ACTIVO'
						FROM `tusuario`
						WHERE `tusuario`.`id_usuario`='".$id_usuario."'
						AND `tusuario`.`c_esta_activo` = 1
						AND `tusuario`.`c_password_web` = PASSWORD('".$old_pass."');");		
		$esta_activo = $db_transporte->get('ACTIVO');
		if ($esta_activo == 0)
		{
			MsjErrores('Su contrase&ntilde;a anterior no coincide.');
		}
		// SI NO HAY ERRORES, ACTUALIZAR LA NUEVA CONTRASEA
		
		if($Error == false)
		{
			$db_transporte->query("UPDATE `tusuario` 
							SET `c_password_web`=PASSWORD('".$new_pass."') 
							WHERE `id_usuario`='".$id_usuario."'
							AND `tusuario`.`c_esta_activo` = 1;");
			if (!$db_transporte)
			{
				MsjErrores('No se pudo actualizar su contrase&ntilde;a, cierre sesi&oacute;n y vuelva a intentarlo.');
			}
			
		}
	}
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
  
  <title>.::TC - Cambiar Contrase&ntilde;a::.</title>
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/validate_numbers.js"> 
  </script>
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  <?php
  	if ($_SESSION['TIPO_USUARIO'] == 1)
	{
		echo '<script language="javascript" src="js/navegador.js"></script>';
	}
  ?>

</head>

<!-- Global IE fix to avoid layout crash when single word size wider than column width -->
<!--[if IE]><style type="text/css"> body {word-wrap: break-word;}</style><![endif]-->

<body  OnLoad="document.form_change_pass.txt_old_pass.focus();" <?php if(isset($_SESSION['IS_LOGGED'])) echo 'onbeforeunload="ConfirmClose()" onunload="HandleOnClose()"'; ?>>
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
	  <?php 
	  	if (!isset($_GET['change']))
		{
	  ?>
      <!-- B.1 MAIN CONTENT -->
		<div class="main-content">
			<!-- Pagetitle -->
			<h1 class="pagetitle">Cambiar su Contrase&ntilde;a</h1>
			<div class="column1-unit">
				<div class="contactform">
					<form id="session_form" method="post" action="change_password.php?change" name="form_change_pass">
						<fieldset style="margin-left:180px; margin-right:180px;"><legend>&nbsp;Detalles de Sesi&oacute;n&nbsp;</legend>
						<p>
						  <label for="contact_title" class="left" title="Eliga la oficina en donde Ud. que se encuentra actualmente." style="width:180px; margin-top:15px;">Antigua Contrase&ntilde;a  : </label>
						  <input type="password" name="txt_old_pass" id="txt_usuario" class="field_user" 
						value="" tabindex="1" title="Ingrese su USUARIO asignado." onkeypress="return handleEnter(this, event)" style="margin-top:15px;" />
						</p>
						<p>
						<label for="contact_firstname" class="left" title="Ingrese su USUARIO asignado." style="width:180px;">Nueva Contrase&ntilde;a : </label>
						<input type="password" name="txt_new_pass" id="contact_firstname" class="field_user" 
						value="" tabindex="2" title="Ingrese su USUARIO asignado." onkeypress="return handleEnter(this, event)" /></p>
						<p>
						<label for="contact_familyname" class="left" title="Ingrese su Contrase&ntilde;a." style="width:180px;">Confirme Contrase&ntilde;a :</label>
						<input type="password" name="txt_conf_pass" id="contact_familyname" class="field" value="" title="Ingrese su Contrasea." tabindex="3" onkeypress="return handleEnter(this, event)" /></p>
						<p><label for="contact_street" class="left"></label>
						<input type="submit" name="btn_session" id="btn_session" class="button" value="Cambiar Contrase&ntilde;a" title="Cambiar Contrase&ntilde;a" tabindex="4" onclick="this.disabled = 'true';this.value = 'Enviando...';this.form.submit();" style="margin-left:20px; width:200px;" />
						</p>
						</fieldset>
					</form>
				</div>
				<hr class="clear-contentunit" />
			</div>
			<!-- Pagetitle -->
			<br />
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
			  <!-- Limpiar Unidad del Contenido -->
			  <hr class="clear-contentunit" />
		  	</div>
		</div>
	  <!--END B.1 MAIN CONTENT -->
	  <?php
	  	}
		else
		{
			if ($Error == true)
			{
			  echo '<!-- B.1 MAIN CONTENT -->';
				echo '<div class="main-content">';
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
				echo '</div>';
			  echo '<!--END B.1 MAIN CONTENT -->';
	  		}
			else
			{
				echo '<!-- B.1 MAIN CONTENT -->';
				echo '<div class="main-content">';
					echo '<!-- Pagetitle -->';
					echo '<h1 class="pagetitle">Su Contrase&ntilde;a se actualiz&oacute; con exito.</h1>';
					echo '<div class="column1-unit">';
					echo '<h1>La Operaci&oacute;n se realiz&oacute; con exito.</h1>';
					$_SESSION['LAST_SESSION'] = date("Y-m-d");
					echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
					echo '<p>Recuerda cambiar su contrase&ntilde;a periodicamente para mayor seguridad.</p>';
					/*<p class="details">| Posted by <a href="#">SiteAdmin </a> | Categories: <a href="#">General</a> | Comments: <a href="#">73</a> |</p>*/
			?>
				<p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="IR A LA P&Aacute;GINA DE INICIO" onclick="this.disabled = 'true'; this.value = 'Enviando...'; location.href = 'index.php';" style="width:300px;" ></p>
			<?php
					echo '<!-- Limpiar Unidad del Contenido -->';
					echo '<hr class="clear-contentunit" />';
				echo '</div>';
			  echo '<!--END B.1 MAIN CONTENT -->';
			}
	  	}
	  ?>
    </div>
	<!-- END B. MAIN -->
      
    <!-- START C. FOOTER AREA -->
    <?php include_once('footer.php'); ?>
	<!-- END C. FOOTER AREA -->
	      
  </div> 
  <!-- END Main Page Container -->
</body>
</html>



