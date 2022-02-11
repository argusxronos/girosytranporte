<!-- B.1 MAIN CONTENT -->
<?php
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = "";
	//if (isset($_SESSION['OFICINAS']))
	$Oficina_Array = $_SESSION['OFICINAS'];
?>
<div class="main-content">
        
	<!-- Content unit - One column -->
        <h1 class="block">Iniciar Sesi&oacute;n</h1>
        <div class="column1-unit">
          <div class="contactform">
            <form id="session_form" method="post" action="login.php">
              <fieldset style="margin-left:220px; margin-right:220px;"><legend>&nbsp;Detalles de Sesi&oacute;n&nbsp;</legend>
                <p><label for="contact_title" class="left" title="Eliga la oficina en donde Ud. que se encuentra actualmente.">Oficina : </label>
                   <select name="cmb_oficina" id="contact_title" class="combo" title="Eliga la oficina en donde Ud. que se encuentra actualmente." onkeypress="return handleEnter(this, event)" tabindex="1">
                     
                     <?php
					 	if (count($Oficina_Array) == 0)
						{
							echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
						}
						else
						{
							echo '<option value="" selected="selected">[ Seleccione su Oficina ]</option>';
							for ($fila = 0; $fila < count($Oficina_Array); $fila++)
							{
								if(isset($_GET['of']) && $_GET['of'] == $Oficina_Array[$fila][0])
									echo '<option value="'.$Oficina_Array[$fila][0].'" selected="selected"> '.$Oficina_Array[$fila][1].' </option>';
								else
									echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
							}
						}
					 ?>
					</select>
				</p>
                <p>
                  <label for="contact_firstname" class="left" title="Ingrese su USUARIO asignado.">Usuario : </label>
                   <input type="text" name="txt_usuario" id="contact_firstname" class="field_user" 
				   value="<?php 
				   		if (isset($_GET['user']) && $_GET['user'] <> '')
							echo $_GET['user'];
				   ?>" tabindex="2" title="Ingrese su USUARIO asignado." onkeypress="return handleEnter(this, event)" /></p>
                <p>
                  <label for="contact_familyname" class="left" title="Ingrese su Contrase&ntilde;a.">Contrase&ntilde;a:</label>
                   <input type="password" name="txt_contrasenia" id="contact_familyname" class="field" value="" title="Ingrese su Contrase&ntilde;a." tabindex="3" onkeypress="return handleEnter(this, event)" /></p>
                <p><label for="contact_street" class="left"></label>
                  <input type="submit" name="btn_session" id="btn_session" class="button" value="Iniciar Sesi&oacute;n" title="Iniciar Sesi&oacute;n" tabindex="4" onclick="this.disabled = 'true';this.value = 'Enviando...';this.form.submit();" />
                </p>
			  </fieldset>
            </form>
          </div>              
        </div> 
	
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
  	<?PHP
		
		if (isset($_SESSION['INTENTOS_SESION']) && $_SESSION['INTENTOS_SESION'] > 0)
		{
			echo '<!-- Content unit - One column -->';
    			echo '<h1 class="block">Mensaje del Administrador.</h1>';
				echo '<div class="column1-unit">';
          			echo '<h1>Inicio de Sesi&oacute;n no valida.</h1>  ';                          
          			echo '<h3>'.date("j \d\e F \d\e\l Y, g:i a").', por <a href="contact_admin.php?page=log_in">Administrador </a></h3>';
          			echo '<p>Verifique su Usuario, Contrase&ntilde;a y/o Oficina.</p>';
					echo '<br /><p>N&uacute;mero de Intentos: <span>'.$_SESSION['INTENTOS_SESION'].'</span></p>';
				echo '</div>';
		}
		
		if(isset($_SESSION['INTENTOS_SESION']) && $_SESSION['INTENTOS_SESION'] == 3)
		{
			// CODIGO PARA CERRAR LA VENTANA
	?>
		<!--<script language="JavaScript"> 
			if (navigator.appName == "Microsoft Internet Explorer")
			{
				window.onfocus = function() 
				{
					window.open('','_parent','');
					window.close(); 
				}
			}
			else
			{
				window.onfocus = function() 
				{
				window.open('', '_self', '');
				window.close();
				}
			}
		</script>-->
	<?php
		}
	?>
	
</div>