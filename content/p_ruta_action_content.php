<?php
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$id_ruta = 'NULL';
	$destino =strtoupper($_POST[destino]);
	$n_certificado = $_POST[n_certificado];
	$obs =trim($_POST[observacion]);
	$idoficina=$_POST[cmb_agencia_origen];
	$hora=$_POST[txt_hora];
	$usuario=strtoupper($_SESSION['USUARIO']);
	// VALIDACIONES PARA EL CASO DEL INSERT
	if (isset($_GET['insert']) || isset($_GET['update']))	
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
			if (!isset($_POST['n_certificado']) || strlen($_POST['n_certificado']) == 0)
			{
				MsjErrores('Debe Ingresar el numero de certificado.');
			}
			elseif(strlen($_POST['n_certificado']) < 3)
			{
				MsjErrores('Numero de certificado deve tener mas de 3 caracteres.');
			}
			// esta validacion deberia ser conjuntamente con los nombres
			elseif(strlen($_POST['n_certificado']) > 10)
			{
				MsjErrores('Numero de certificado no debe tener mas de 10 caracteres.');
			}
			else
			{
				$nom_completo_ofi = str_replace("\xF1", "\xD1", $_POST['n_certificado']);
				$nom_completo_ofi = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($nom_completo_ofi)))));
			}			
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
			// REGISTRAMOS EL REMITENTE Y OBTENEMOS SU ID
					// insertamos los datos
					
					$db_transporte->query("INSERT INTO ruta(ruta.`id_ruta`,ruta.`destino`,ruta.`nro_certificacion`,
					ruta.`obs`,ruta.`idoficina`,ruta.`hora`,ruta.`usuario_creacion`,ruta.`fecha_creacion`,ruta.`hora_creacion`) 
					VALUES('$id_ruta','$destino','$n_certificado','$obs','$idoficina','$hora','$usuario',CURDATE(),CURRENT_TIME())");
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
			$db_transporte->query("DELETE FROM ruta WHERE ruta.`id_ruta`='$valor'");
		}	
		if(isset($_GET['update']))
		{
			$id_ruta =$_POST[txt_codigo];
			$destino =strtoupper($_POST[destino]);
			$n_certificado = $_POST[n_certificado];
			$obs =trim($_POST[observacion]);
			$idoficina=$_POST[cmb_agencia_origen];
			$hora=$_POST[txt_hora];		
			$db_transporte->query("UPDATE ruta SET ruta.`destino`='$destino',ruta.`nro_certificacion`='$n_certificado',
			ruta.`obs`='$obs',ruta.`idoficina`='$idoficina',ruta.`hora`='$hora',ruta.`usuario_creacion`='$usuario',
			ruta.`fecha_creacion`=CURDATE(),ruta.`hora_creacion`=CURRENT_TIME()
			WHERE ruta.`id_ruta`='$id_ruta'");	
				
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
		<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_ruta.php'" style="width:170px;" ></p>
<?PHP
	}
?>
</div>
