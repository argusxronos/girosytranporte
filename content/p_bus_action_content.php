<?php
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$id_bus = 'NULL';
	$flota =$_POST[flota];
	$thabilitacion = $_POST[thabilitacion];
	$marca =strtoupper($_POST[marca]);
	$carroceria=strtoupper($_POST[carroceria]);
	$placa = strtoupper($_POST[placa]);
	$npisos = $_POST[pisos];
	$ca1ini= $_POST[ca1ini];
	$ca2ini =$_POST[ca2ini];
	$ca1fin = $_POST[ca1fin];
	$ca2fin = $_POST[ca2fin];
	$asientos = $_POST[casientos];
	$propietario = strtoupper($_POST[propietario]);
	$imagen = $_POST[foto];
	$obs = strtoupper($_POST[observacion]);
	$usuario=strtoupper($_SESSION['USUARIO']);
	// VALIDACIONES PARA EL CASO DEL INSERT
	echo $asientos;
	if (isset($_GET['insert']) || isset($_GET['update']))
	{
            if(strlen($flota) == 0)
            {
                MsjErrores('Debe ingresar n&uacute;mero de la flota.');
            }
            if(strlen($thabilitacion) == 0)
            {
            	MsjErrores('Debe ingresar n&uacute;mero de tarjeta de habilitaci&oacute;n.');
            }
            if (strlen($marca) == 0) {
            	MsjErrores('Debe ingresar marca del bus.');
            }
            if (strlen($placa) == 0) {
            	MsjErrores('Debe ingresar placa del Bus.');
            }
            if (strlen($propietario) == 0) {
            	MsjErrores('Ingrese el propietario del Bus.');
            }
            if (!is_numeric($asientos)) {
            	MsjErrores('Cantidad de Asientos debe ser N&uacute;merico.');
            }
            elseif (intval($asientos) == 0) {
            	MsjErrores('Cantidad de Asientos debe ser mayor a cero.');
            }
	}
	// OBTENEMOS EL CODIGO DEL LA PAGINA
 	
	
	// PROCEDIMIENTO PARA INGRESAR LOS DATOS SI NO HAY ERRORES
	
		// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
		require_once 'cnn/config_master.php';
		if ($Error == false){
			if (isset($_GET['insert']))
				{
					// otra manera de guardar datos
					// REGISTRAMOS LOS DATOS DEL BUS Y OBTENEMOS SU ID
					// insertamos los datos
					
					$db_transporte->query("insert into bus (id_bus, flota, tarjeta_habilitacion, marca, carroceria, placa_rodaje,
					nro_pisos, ca1ini, ca2ini, ca1fin, ca2fin, cantasientos, propietario, imagen, obs,estado,usuario_creacion, fecha_creacion,
					hora_creacion)values(null,'$flota','$thabilitacion','$marca','$carroceria','$placa','$npisos','$ca1ini','$ca2ini','$ca1fin',
					'$ca2fin','$asientos','$propietario','$imagen','$obs','','$usuario',CURDATE(),CURRENT_TIME())");					
					if (!$db_transporte)
					{
						MsjErrores('Error al insertar los datos del Bus.');
					}
					// OBTENEMOS EL ID DEL USUARIO RECIEN REGISTRADO
					$db_transporte->query("SELECT id_bus AS 'ID' FROM bus WHERE flota='$flota'");
					$id_bus_reciente = $db_transporte->get("ID");
					//echo $id_bus;
				
				}
			if (isset($_GET['delete']))
				{
					$valor=$_GET[delete];
					//echo $valor;
					$db_transporte->query("DELETE FROM bus WHERE id_bus='$valor'");
				}
			if(isset($_GET['update']))
				{
					$id_bus =$_POST[txt_codigo];	
					$flota =$_POST[flota];
					$thabilitacion = strtoupper($_POST[thabilitacion]);
					$marca =strtoupper($_POST[marca]);
					$carroceria=strtoupper($_POST[carroceria]);
					$placa =strtoupper($_POST[placa]);
					$npisos = $_POST[pisos];
					$ca1ini= $_POST[ca1ini];
					$ca2ini =$_POST[ca2ini];
					$ca1fin = $_POST[ca1fin];
					$ca2fin = $_POST[ca2fin];
					$asientos = $_POST[casientos];
					$propietario = strtoupper($_POST[propietario]);
					$imagen = $_POST[foto];
					$obs = strtoupper($_POST[observacion]);	
					$db_transporte->query("UPDATE bus SET bus.`flota`='$flota',bus.`tarjeta_habilitacion`='$thabilitacion',bus.`marca`='$marca',
						bus.`carroceria`='$carroceria',bus.`placa_rodaje`='$placa',bus.`nro_pisos`='$npisos',bus.`ca1ini`='$ca1ini',
						bus.`ca2ini`='$ca2ini',bus.`ca1fin`='$ca1fin',bus.`ca2fin`='$ca2fin',bus.`cantasientos`='$asientos',bus.`propietario`='$propietario',
						bus.`imagen`='$imagen',bus.`obs`='$obs',bus.`usuario_creacion`='$usuario',bus.`fecha_creacion`=CURDATE(),bus.`hora_creacion`=CURRENT_TIME()
						WHERE bus.`id_bus`='$id_bus'");			
				}	
		}
		
	
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
<?php
	if ($Error == true)
	{
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
	}
	else
	{
		// MOSTRAMOS EL MENSAJE DE OPERACION SATISFACTORIA
		echo '<!-- Pagetitle -->';
		
		if (isset($_GET['insert']))
		{
			echo '<h1 class="pagetitle">Mensaje de Confirmación</h1>';
			echo '<div class="column1-unit">';
			echo '<h1>Operación Exitosa.</h1>';
			echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
			echo '<p>El Registro se guardo; <span>Satisfactoriamente</span>.</p>';
		}
		if (isset($_GET['delete']))
		{
			echo '<h1 class="pagetitle">Mensaje de Eliminación</h1>';
			echo '<div class="column1-unit">';
			echo '<h1>Operación Exitosa.</h1>';
			echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';			
			echo '<p>El Registro se elimino; <span>Satisfactoriamente</span>.</p>';
		}
		if (isset($_GET['update']))
		{
			echo '<h1 class="pagetitle">Mensaje de Modificación</h1>';
			echo '<div class="column1-unit">';
			echo '<h1>Operación Exitosa.</h1>';
			echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';			
			echo '<p>El Registro se modifico; <span>Satisfactoriamente</span>.</p>';
		}
?>
		<!--<p style="text-align:center;"><input name="btn_confi" id="btn_confi" type="button" class="button" name="btn_config" value="Configuración de Bus" tabindex="1" onclick="location.href="p_config.php?id='.$id_bus.'"" style="width:180px;" /></p>-->
		<p style="text-align:center;"><input class="button" type="button" name="btn_config" id="btn_config" value="Configuración Bus" onclick="location.href='p_config_bus.php?ID=<?php echo $id_bus_reciente?>'" style="width:170px;" ><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; location.href='p_bus.php'" ></p>		
<?PHP
		//echo '<p style="text-align:center;"><input name="btn_confi" id="btn_confi" type="button" class="button" name="btn_config" value="Configuración de Bus" tabindex="1" onclick="location.href="p_config.php?id='.$id_bus.'"" style="width:180px;" /></p>';
	}
?>
</div>
