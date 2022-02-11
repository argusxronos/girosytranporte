<?php
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$id_salida = 'NULL';	
	$fecha_copiar = $_POST[buscar2];
	//$oficina_destino=$_POST[cmb_agencia];
	$origen_copiar=$_POST[txt_origen];
	$fecha_salida=$_POST[txt_fecha_salida];
	//echo $origen_copiar;
	$usuario=strtoupper($_SESSION['USUARIO']);
	
	// VALIDACIONES PARA EL CASO DEL INSERT
	
	if (isset($_GET['insert']))	
	{
		// VERIFICAMOS SI LA OFICINA ESTA REGISTRADO
		if (isset($_POST['buscar2']) && strlen($_POST['buscar2']) > 0)
		{
			$esta_regist_remit = 1;
			$id_remit = $_POST['buscar2'];
			// NO ES NECESARIO VERIFICAR SI SE INGRESO EL NOMBRE DEL REMITENTE
		}
		else
		{
			$esta_regist_remit = 0;
			// VERIFICAMOS SI SE INGRESO LOS DATOS DEL REMITENTE
			if (!isset($_POST['buscar2']) || strlen($_POST['buscar2']) == 0)
			{
				MsjErrores('Debe la fecha a la cual quiere copiar las salidas.');
			}					
		}
		
	}
	
	// PROCEDIMIENTO PARA INGRESAR LOS DATOS SI NO HAY ERRORES
	
		// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
		require_once 'cnn/config_master.php';
		if ($Error == false){
			$consulta="SELECT*FROM salida WHERE fecha='$fecha_copiar' AND idoficina='$origen_copiar'";
			$db_transporte->query($consulta);
			$Consulta_Array= $db_transporte->get();
			if (isset($_GET['insert']))
				{
					//CONSULTA PARA MOSTRAR DATOS, LAS CUALES PODER COPIAR
					$sql = "SELECT*FROM salida WHERE fecha='$fecha_salida' AND idoficina='$origen_copiar'";					
					//$sql2="SELECT COUNT(idoficina) FROM salida WHERE fecha='$fecha_salida' AND idoficina='$origen_copiar'";
					$db_transporte->query($sql);
					$Salidas_Array = $db_transporte->get();					
					$nu=0;
						
					if(count($Consulta_Array)== 0){
						//BUCLE PARA COPIAR SALIDAS
						for($i=1;$i<=count($Salidas_Array);$i++){
							$ruta=$Salidas_Array[$nu][2];
							$rutahora=$Salidas_Array[$nu][3];
							$idoficina=$Salidas_Array[$nu][4];						
							$hora=$Salidas_Array[$nu][5];						
							$id_bus=$Salidas_Array[$nu][6];
							$db_transporte->query("INSERT INTO salida(salida.`id_salida`,salida.`fecha`,salida.`id_ruta`,
							salida.`id_rutahora`,salida.`idoficina`,salida.`hora`,salida.`id_bus`,salida.`cant_tripulacion`,
							salida.`cant_pasajeros`,salida.`cant_asientos`,salida.`usuario_creacion`,salida.`fecha_creacion`,salida.`hora_creacion`)
							VALUES('$id_salida','$fecha_copiar','$ruta','$rutahora','$idoficina','$hora'
							,'$id_bus','','','','$usuario',CURDATE(),CURRENT_TIME())");
							$nu=$nu+1;
						}
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
		<?php
						// MOSTRAMOS EL MENSAJE DE OPERACION SATISFACTORIA						
						echo '<!-- Pagetitle -->';
						echo '<h1 class="pagetitle">Mensaje de Confirmacion</h1>';
						echo '<div class="column1-unit">';
						echo '<h1>Operaci&oacute;n Exitosa.</h1>';
						echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
						echo '<p>El Registro se guardo; <span>Satisfactoriamente</span>.</p>';
		?>	
<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_copiar_salida.php'" style="width:170px;" ></p>							
</div>	
		<?php	
					}
									
					else {	
		?>
<div class="main-content">
		<?php
							
						echo '<h1 class="pagetitle">Aviso</h1>';
						echo '<div class="column1-unit">';
						echo '<h1>Ya existen salidas para la fecha indicada</h1>';
						echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
						echo '<p>Ingrese otra fecha la cual no tenga salidas registradas</span>.</p>';
		?>
<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_copiar_salida.php?buscar'" style="width:170px;" ></p>							
</div>

<?php
					}	

				}
		}			

?>

