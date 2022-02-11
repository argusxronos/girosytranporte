<?php
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$id_oficina = 'NULL';
	$ip =$_POST[ip];
	$serie = $_POST[serie];
	$oficina =strtoupper($_POST[oficina]);
	$direccion=strtoupper($_POST[direccion]);
	$usuario=strtoupper($_SESSION['USUARIO']);
	// VALIDACIONES PARA EL CASO DEL INSERT
	//VALIDACION DE ENTRADAS DE TEXTO
	if (isset($_GET['insert']) || isset($_GET['update']))	
	{
		// VERIFICAMOS SI LA OFICINA ESTA REGISTRADO
		if (isset($_POST['SERIE']) && strlen($_POST['oficina']) > 0)
		{
			$esta_regist_remit = 1;
			$id_remit = $_POST['oficina'];
		}
		else
		{
			$esta_regist_remit = 0;
			// VERIFICAMOS SI SE INGRESO LOS DATOS DE LA OFICINA
			if (!isset($_POST['oficina']) || strlen($_POST['oficina']) == 0)
			{
				MsjErrores('Debe Ingresar el nombre de la oficina.');
			}
			elseif(strlen($_POST['oficina']) < 5)
			{
				MsjErrores('Nombre de la Oficina debe tener mas de 5 caracteres.');
			}
			// esta validacion deberia ser conjuntamente con los nombres
			elseif(strlen($_POST['oficina']) > 50)
			{
				MsjErrores('Nombre de la oficina no debe tener mas de 50 caracteres.');
			}
			else
			{
				$nom_completo_ofi = str_replace("\xF1", "\xD1", $_POST['oficina']);
				$nom_completo_ofi = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_ofi)))));
			}			
		}
		// VERIFICAMOS SI SE INGRESO LOS DATOS DE DIRECCION
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
		// VERIFICAMOS SI SE INGRESO LOS DATOS DE DIRECCION
		if (!isset($_POST['serie']) || strlen($_POST['serie']) == 0)
		{
			MsjErrores('Debe Ingresar la serie de la oficina.');
		}
		elseif(strlen($_POST['serie']) < 3)
		{
			MsjErrores('La serie de la Oficina debe tener menos de 3 caracteres.');
		}
		// esta validacion deberia ser conjuntamente con los nombres
		elseif(strlen($_POST['serie']) > 6)
		{
			MsjErrores('La serie de la oficina no debe tener mas de 6 caracteres.');
		}
		else
		{
			$nom_completo_dir = str_replace("\xF1", "\xD1", $_POST['direccion']);
			$nom_completo_dir = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_dir)))));
		}
	}
	//FIN DE VALIDACION DE CAJAS DE TEXTO
	
	// PROCEDIMIENTO PARA INGRESAR LOS DATOS SI NO HAY ERRORES
	
		// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
		require_once 'cnn/config_master.php';
		if ($Error == false)
		{
			if (isset($_GET['insert']))
			{
		// otra manera de guardar datos
		// REGISTRAMOS EL REMITENTE Y OBTENEMOS SU ID
				// insertamos los datos				
				$db_transporte->query("INSERT INTO oficinas(oficinas.idoficina,oficinas.oficina,oficinas.nro_ip,
				oficinas.data,oficinas.`color_red`,oficinas.`color_green`,oficinas.`color_blue`,oficinas.`ver`,
				oficinas.`serie`,oficinas.`icono`,oficinas.`retrazo`,oficinas.`direccion`,oficinas.`usuario_creacion`,
				oficinas.`fecha_creacion`,oficinas.`hora_creacion`) VALUES('$id_oficina','$oficina',
				'$ip','','','','','','$serie','','','$direccion','$usuario',CURDATE(),CURRENT_TIME())");
				//print "Datos Ingresados";
				if (!$db_transporte)
				{
					MsjErrores('Error al insertar los datos del Remitente.');
				}			
			}
		}
		if (isset($_GET['delete']))
		{
			$valor=$_GET[delete];
			//echo $valor;
			$db_transporte->query("DELETE FROM oficinas WHERE oficinas.`idoficina`='$valor'");
		}
		if ($Error == false)
		{	
			if(isset($_GET['update']))
			{
				$id_oficina =$_POST[txt_codigo];			
				$ip =$_POST[ip];
				$serie = $_POST[serie];
				$oficina =strtoupper($_POST[oficina]);
				$direccion=strtoupper($_POST[direccion]);		
				$db_transporte->query("UPDATE oficinas SET oficinas.`nro_ip`='$ip',oficinas.`serie`='$serie',
				oficinas.`oficina`='$oficina',oficinas.`direccion`='$direccion',oficinas.`usuario_creacion`='$usuario',
				oficinas.`fecha_creacion`=CURDATE(),oficinas.`hora_creacion`=CURRENT_TIME()
				WHERE oficinas.`idoficina`='$id_oficina'");			
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
		<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_oficina.php'" style="width:170px;" ></p>
<?PHP
	}
?>
</div>
