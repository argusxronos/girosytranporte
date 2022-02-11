<?php
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$id_tripulacion = 'NULL';
	$n_licencia =strtoupper($_POST[n_licencia]);
	$nombres =strtoupper($_POST[nombres]);
	$obs=$_POST[observaciones];
	$usuario=strtoupper($_SESSION['USUARIO']);
	//$buscar=$POST[buscar];
	// VALIDACIONES PARA EL CASO DEL INSERT
	if (isset($_GET['insert']))	
	{
		// VERIFICAMOS SI LA OFICINA ESTA REGISTRADO
		if (isset($_POST['oficina']) && strlen($_POST['oficina']) > 0)
		{
			$esta_regist_remit = 1;
			$id_remit = $_POST['oficina'];
			// NO ES NECESARIO VERIFICAR SI SE INGRESO EL NOMBRE DEL REMITENTE
		}
		else
		{
			$esta_regist_remit = 0;
			// VERIFICAMOS SI SE INGRESO LOS DATOS DEL REMITENTE
			if (!isset($_POST['nombres']) || strlen($_POST['nombres']) == 0)
			{
				MsjErrores('Debe Ingresar el nombre del Tripulante.');
			}
			elseif(strlen($_POST['nombres']) < 5)
			{
				MsjErrores('Nombre del tripulante debe tener mas de 5 caracteres.');
			}
			// esta validacion deberia ser conjuntamente con los nombres
			elseif(strlen($_POST['nombres']) > 50)
			{
				MsjErrores('Nombre del tripulante no debe tener mas de 50 caracteres.');
			}
			else
			{
				$nom_completo_ofi = str_replace("\xF1", "\xD1", $_POST['nombres']);
				$nom_completo_ofi = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_ofi)))));
			}			
		}
		/*
		if (!isset($_POST['direccion']) || strlen($_POST['direccion']) == 0)
			{
				MsjErrores('Debe Ingresar la direccion de la oficina.');
			}
			elseif(strlen($_POST['direccion']) < 5)
			{
				MsjErrores('La dirección de la Oficina debe tener mas de 5 caracteres.');
			}
			// esta validacion deberia ser conjuntamente con los nombres
			elseif(strlen($_POST['direccion']) > 50)
			{
				MsjErrores('La direccion de la oficina no debe tener mas de 50 caracteres.');
			}
			else
			{
				$nom_completo_dir = str_replace("\xF1", "\xD1", $_POST['direccion']);
				$nom_completo_dir = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_dir)))));
			}
			* */
	}
	// OBTENEMOS EL CODIGO DEL LA PAGINA
 	
	
	// PROCEDIMIENTO PARA INGRESAR LOS DATOS SI NO HAY ERRORES
	
		// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
		require_once 'cnn/config_master.php';
		if ($Error == false){
			if (isset($_GET['insert']))
				{
			// otra manera de guardar datos
			// REGISTRAMOS EL REMITENTE Y OBTENEMOS SU ID
					// insertamos los datos
					
					$db_transporte->query("INSERT INTO tripulacion(id_tripulacion,apellidos_nombres,nro_licencia,obs,
					tripulacion.`usuario_creacion`,tripulacion.`fecha_creacion`,tripulacion.`hora_creacion`) 
					VALUES('$id_tripulacion','$nombres','$n_licencia','$obs','$usuario',CURDATE(),CURRENT_TIME())");
					//print "Datos Ingresados";
					if (!$db_transporte)
					{
						MsjErrores('Error al insertar los datos del Tripulante.');
					}
				
				}
		}
		if (isset($_GET['delete']))
		{
			$valor=$_GET[delete];
			//echo $valor;
			$db_transporte->query("DELETE FROM tripulacion WHERE tripulacion.`id_tripulacion`='$valor'");
		}	
		if(isset($_GET['update']))		
		{
			$id_tripulacion =$_POST[txt_codigo];
			$n_licencia =strtoupper($_POST[n_licencia]);
			$nombres =strtoupper($_POST[nombres]);
			$obs=$_POST[observaciones];		
			$db_transporte->query("UPDATE tripulacion SET tripulacion.`apellidos_nombres`='$nombres',tripulacion.`nro_licencia`='$n_licencia',
					tripulacion.`obs`='$obs',tripulacion.`usuario_creacion`='$usuario',tripulacion.`fecha_creacion`=CURDATE(),
					tripulacion.`hora_creacion`=CURRENT_TIME()
					WHERE tripulacion.`id_tripulacion`='$id_tripulacion'");		
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
		echo '<h1 class="pagetitle">Mensaje de Confirmacion</h1>';
		echo '<div class="column1-unit">';
	  	echo '<h1>Operaci&oacute;n Exitosa.</h1>';
	  	echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
		if (isset($_GET['insert']))
		{
			echo '<p>El Registro se guardo; <span>Satisfactoriamente</span>.</p>';
		}
		if (isset($_GET['delete']))
		{
			echo '<p>El Registro se elimino; <span>Satisfactoriamente</span>.</p>';
		}
		if (isset($_GET['update']))
		{
			echo '<p>El Registro se modifico; <span>Satisfactoriamente</span>.</p>';
		}
	  	/*<p class="details">| Posted by <a href="#">SiteAdmin </a> | Categories: <a href="#">General</a> | Comments: <a href="#">73</a> |</p>*/
		// BOTONES PARA REGRESAR A LA VENTENA ANTERIOR
?>
		<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_tripulacion.php'" style="width:170px;" ></p>
<?PHP
	}
?>
</div>
