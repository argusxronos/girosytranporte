<?php
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$id_salida = 'NULL';
	$origen =$_POST[cmb_agencia_origen];
	$fecha = $_POST[txt_fecha];
	$destino =$_POST[cmb_destino];
	$bus=$_POST[cmb_bus];
	$hora=$_POST[txt_hora];
	$usuario=strtoupper($_SESSION['USUARIO']);
	//$hora=$_POST[txt_hora];
	// VALIDACIONES PARA EL CASO DEL INSERT
	/*
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
		
	}*/
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
					
					$db_transporte->query("INSERT INTO salida(salida.`id_salida`,salida.`fecha`,salida.`id_ruta`,
					salida.`id_rutahora`,salida.`idoficina`,salida.`hora`,salida.`id_bus`,salida.`cant_tripulacion`,
					salida.`cant_pasajeros`,salida.`cant_asientos`,salida.`usuario_creacion`,salida.`fecha_creacion`,salida.`hora_creacion`)
					VALUES('$id_salida','$fecha','$destino','$destino','$origen','$hora','$bus','','','','$usuario',CURDATE(),CURRENT_TIME())");
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
				$consulta="SELECT*FROM record_cliente WHERE id_salida='$valor'";
				$db_transporte->query($consulta);
				$Consulta_Array= $db_transporte->get();
				$record_usuario=count($Consulta_Array);
				//echo $record_usuario;
				//echo $valor;
				if($record_usuario==0){
					$db_transporte->query("DELETE FROM salida WHERE id_salida='$valor'");
				}
				
				//
			}		
		if(isset($_GET['update']))
		{
			$variable=$_POST[txt_codigo];
			//echo $variable;
			$consulta2="SELECT*FROM record_cliente WHERE id_salida='$variable'";
			$db_transporte->query($consulta2);
			$Consulta2_Array= $db_transporte->get();
			$record2_usuario=count($Consulta2_Array);
			if ($record2_usuario==0){
				$id =$_POST[txt_codigo];			
				$origen =$_POST[cmb_agencia_origen];			
				$fecha = $_POST[txt_fecha];					
				$destino =$_POST[cmb_destino];			
				$bus=$_POST[cmb_bus];			
				$hora=$_POST[txt_hora];			
				$db_transporte->query("UPDATE salida SET salida.`fecha`='$fecha',salida.`id_ruta`='$destino',salida.`id_rutahora`='$destino',
				salida.`idoficina`='$origen',salida.`hora`='$hora',salida.`id_bus`='$bus',salida.`usuario_creacion`='$usuario',
				salida.`fecha_creacion`=CURDATE(),salida.`hora_creacion`=CURRENT_TIME()
				WHERE id_salida='$id'");			
			}
			
		}
	
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
<?php
	if ($Error == true )
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
	  	if(isset($_GET['insert'])){
			echo '<h1>Operaci&oacute;n Exitosa.</h1>';
			echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';		
			echo '<p>El Registro se guardo; <span>Satisfactoriamente</span>.</p>';
		}
		if(isset($_GET['delete'])){
			if($record_usuario==0){
				echo '<h1>Operación Exitosa.</h1>';
				echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';					
				echo '<p>El Registro se elimino; <span>Satisfactoriamente</span>.</p>';
			}
			else {
				echo '<h1>Aviso</h1>';
				echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';					
				echo '<p>El Registro no se puede eliminar devido a que la salida ya cuenta con pasajeron para dicha salida</span>.</p>';
			}
			
		}
		if(isset($_GET['update'])){
			if($record2_usuario==0){
				echo '<h1>Operación Exitosa.</h1>';
				echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';					
				echo '<p>El Registro se Modifico; <span>Satisfactoriamente</span>.</p>';
			}
			else {
				echo '<h1>Aviso</h1>';
				echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';					
				echo '<p>El Registro no se puede Modificar devido a que ya cuenta con pasajeron para dicha salida</span>.</p>';
			}
		}
	  		  	
?>

<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_salida.php'" style="width:170px;" ></p>
<?PHP
	}
?>
</div>
