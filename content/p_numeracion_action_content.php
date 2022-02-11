<?php
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$id = 'NULL';
	$serie =$_POST[txt_serie];
	$numero = $_POST[txt_nactual];
	$idoficina =$_POST[cmb_agencia_origen];
	$iddocumento=$_POST[cmb_documento];
	$pc=$_POST[txt_pc];
	$operacion=$_POST[cmb_operacion];
	$detalle=strtoupper($_POST[detalle]);
	$usuario=strtoupper($_SESSION['USUARIO']);
	// VALIDACIONES PARA EL CASO DEL INSERT
	if (isset($_GET['insert']))	
	{
		// VERIFICAMOS SI LA OFICINA ESTA REGISTRADO
		if (isset($_POST['detalle']) && strlen($_POST['detalle']) > 0)
		{
			$esta_regist_remit = 1;
			$id_remit = $_POST['detalle'];
			// NO ES NECESARIO VERIFICAR SI SE INGRESO EL NOMBRE DEL REMITENTE
		}
		else
		{
			$esta_regist_remit = 0;
			// VERIFICAMOS SI SE INGRESO LOS DATOS DEL REMITENTE
			if (!isset($_POST['txt_serie']) || strlen($_POST['txt_serie']) == 0)
			{
				MsjErrores('Debe Ingresar el numero de serie.');
			}
			elseif(strlen($_POST['txt_serie']) < 1)
			{
				MsjErrores('Numero de serie deve tener mas de 1 caracteres.');
			}
			// esta validacion deberia ser conjuntamente con los nombres
			elseif(strlen($_POST['txt_serie']) > 4)
			{
				MsjErrores('Numero de serie no debe tener mas de 4 caracteres.');
			}
			else
			{
				$nom_completo_ofi = str_replace("\xF1", "\xD1", $_POST['txt_serie']);
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
					$docu="SELECT descripcion_documento FROM numeracion_documento WHERE id_documento='$iddocumento'";
					$db_transporte->query($docu);
					$Tipo_Array = $db_transporte->get();					
					$Tipo_documento=$Tipo_Array[0][0];
					$db_transporte->query("INSERT INTO numeracion_documento(numeracion_documento.`id`,numeracion_documento.`idoficina`,numeracion_documento.`id_documento`,
					numeracion_documento.`descripcion_documento`,numeracion_documento.`serie`,numeracion_documento.`numero_actual`,numeracion_documento.`pc`,
					numeracion_documento.`detalle`,numeracion_documento.`editable`,numeracion_documento.`tipo_operacion`,numeracion_documento.`usuario_creacion`,
					numeracion_documento.`fecha_creacion`,numeracion_documento.`hora_creacion`)
					VALUES('$id','$idoficina','$iddocumento','$Tipo_documento','$serie','$numero','$pc','$detalle',null,'$operacion',
					'$usuario',CURDATE(),CURRENT_TIME())");
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
			$db_transporte->query("DELETE FROM numeracion_documento WHERE numeracion_documento.id='$valor'");
		}	
		if(isset($_GET['update']))
		{
			$id = $_POST[txt_codigo];
			$serie =$_POST[txt_serie];
			$numero = $_POST[txt_nactual];
			$idoficina =$_POST[cmb_agencia_origen];
			$iddocumento=$_POST[cmb_documento];
			$pc=$_POST[txt_pc];
			$operacion=$_POST[cmb_operacion];
			$detalle=strtoupper($_POST[detalle]);
			//obtener el tipo de cocumento
			$docu="SELECT descripcion_documento FROM numeracion_documento WHERE id_documento='$iddocumento'";
			$db_transporte->query($docu);
			$Tipo_Array = $db_transporte->get();					
			$Tipo_documento=$Tipo_Array[0][0];
			//////fin de colsulta
			
			$db_transporte->query("UPDATE numeracion_documento SET numeracion_documento.`idoficina`='$idoficina',numeracion_documento.`id_documento`='$iddocumento',
				numeracion_documento.`descripcion_documento`='$Tipo_documento',numeracion_documento.`serie`='$serie',numeracion_documento.`numero_actual`='$numero',
				numeracion_documento.`pc`='$pc',numeracion_documento.`detalle`='$detalle',numeracion_documento.`tipo_operacion`='$operacion',
				numeracion_documento.`usuario_creacion`='$usuario',numeracion_documento.`fecha_creacion`=CURDATE(),numeracion_documento.`hora_creacion`=CURRENT_TIME()
				WHERE numeracion_documento.`id`='$id'");
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
		<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_numeracion.php'" style="width:170px;" ></p>
<?PHP
	}
?>
</div>
