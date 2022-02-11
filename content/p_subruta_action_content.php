<?php
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$id_sr = 'NULL';
	$id_ruta =$_POST[cmb_ruta];
	$localidad =strtoupper($_POST[localidad]);
	$p1=$_POST[p1];
	$p2=$_POST[p2];
	//$obs=$POST[obs];
	$principal=$_POST[seleccion];
	$usuario=strtoupper($_SESSION['USUARIO']);
	//echo $principal;
	// VALIDACIONES PARA EL CASO DEL INSERT
	if (isset($_GET['insert']))	
	{
		// VERIFICAMOS SI LA OFICINA ESTA REGISTRADO
		if (isset($_POST['localidad']) && strlen($_POST['localidad']) > 0)
		{
			$esta_regist_remit = 1;
			$id_remit = $_POST['localidad'];
			// NO ES NECESARIO VERIFICAR SI SE INGRESO EL NOMBRE DEL REMITENTE
		}
		else
		{
			$esta_regist_remit = 0;
			// VERIFICAMOS SI SE INGRESO LOS DATOS DEL REMITENTE
			if (!isset($_POST['localidad']) || strlen($_POST['localidad']) == 0)
			{
				MsjErrores('Debe Ingresar el nombre de la localidad.');
			}
			elseif(strlen($_POST['nombres']) < 5)
			{
				MsjErrores('Nombre de la localidad debe tener mas de 5 caracteres.');
			}
			// esta validacion deberia ser conjuntamente con los nombres
			elseif(strlen($_POST['localidad']) > 50)
			{
				MsjErrores('Nombre de la localidad no debe tener mas de 50 caracteres.');
			}
			else
			{
				$nom_completo_ofi = str_replace("\xF1", "\xD1", $_POST['localidad']);
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
					
					$db_transporte->query("INSERT INTO sub_rutas(id_sr,id_rutahora,localidad,precio_p1,precio_p2,abrev,principal,
					sub_rutas.`usuario_creacion`,sub_rutas.`fecha_creacion`,sub_rutas.`hora_creacion`) 
					VALUES('$id_sr','$id_ruta','$localidad','$p1','$p2','','$principal','$usuario',CURDATE(),CURRENT_TIME())");
					//print "Datos Ingresados";
					if (!$db_transporte)
					{
						MsjErrores('Error al insertar los datos de la Sub Ruta.');
					}				
				}
		}
		if (isset($_GET['delete']))
		{
			$valor=$_GET[delete];
			//echo $valor;
			$db_transporte->query("DELETE FROM sub_rutas WHERE sub_rutas.`id_sr`='$valor'");
		}	
		if(isset($_GET['update']))
		{
			$id_sr = $_POST[txt_codigo];
			$id_ruta =$_POST[cmb_ruta];
			$localidad =strtoupper($_POST[localidad]);
			$p1=$_POST[p1];
			$p2=$_POST[p2];
			//$obs=$POST[obs];
			$principal=$_POST[seleccion];		
			$db_transporte->query("UPDATE sub_rutas SET sub_rutas.`id_rutahora`='$id_ruta',sub_rutas.`localidad`='$localidad',
					sub_rutas.`precio_p1`='$p1',sub_rutas.`precio_p2`='$p2',sub_rutas.`principal`='$principal',sub_rutas.`usuario_creacion`='$usuario',
					sub_rutas.`fecha_creacion`=CURDATE(),sub_rutas.`hora_creacion`=CURRENT_TIME()
					WHERE sub_rutas.`id_sr`='$id_sr'");			
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
		<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_subruta.php'" style="width:170px;" ></p>
<?PHP
	}
?>
</div>
