<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// VERIFICAMOS SI ESTA LOGEADO
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged_niv2.php");
	require_once("is_logged.php");
	// CREAMOS LA CONSULTA DE BUSQUEDA
	$sql = "SELECT bus.flota,bus.tarjeta_habilitacion,bus.marca,bus.carroceria,bus.placa_rodaje,
	bus.nro_pisos,bus.ca1ini,bus.ca2ini,bus.ca1fin,bus.ca2fin,bus.cantasientos,bus.propietario,bus.imagen,bus.obs FROM bus";
	$sql_rows = "SELECT COUNT(bus.id_bus) AS TOTAL FROM bus";
		
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 15;
	
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	
	$sql_rows = $sql_rows .';';
	// OBTEMOS LOS DATOS DE MOVIMIENTOS
	require_once 'cnn/config_master.php';
	// REALIZAMOS LA CONSULTA A LA BD
	$db_transporte->query($sql_rows);
	$totalRows = $db_transporte->get('TOTAL');
	
	$db_transporte->query($sql);
	$Trans_Array = $db_transporte->get();
	/*
	$consu="SELECT*FROM cliente WHERE nombres LIKE '%carlos%'";
	$db_trasporte->query($consu);
	$row = mysql_fetch_array($db_trasporte->get());
	* */
	
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Nuevo Bus</h1>
    <?php 
	if (!isset($_GET['ID']))
	{
?>

	<!-- Contenido del Formulario -->
	<div class="column1-unit">
	
	  <h1>Ingrese Datos Nuevo Bus - <span>RECUERDE INGRESAR PRIMERO LA FLOTA Y TARGETA DE HABILITACION</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			<!--<legend>Nuevo Registro</legend>-->
			<div class='column1-unit'>
				<div class='contactform'>
					<form name="tramite_form" method='post' id="tramite_form" action='p_tramite_action.php?insert'>
						<table border="0">						
							<tr>
								<th><span>*</span>Señor : </th>
								<td  colspan="3">									
									<input id='cliente' type='text' name='cliente' value='<?php echo $row['cliente']; ?>' title="Numero de Flota." tabindex="1" style="width:450px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>
							</tr>
							<tr>
								<th><span>*</span>dirección : 
								<td colspan="3">
								<input id='direccion'  type='text' name='direccion' value="" title="Targeta de habiltacion." tabindex="2" style="width:450px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>							  							  
							</tr>
							<tr>
								<th><span>*</span>Ruc:</th>
								<td><input name="ruc" type="text" id="ruc" tabindex="3" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Inicio de asientos piso 1." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
								
								<th><span>*</span>Fecha/Hora : 
								<td>
									<input type="text" value=" <?php echo date("j \d\e M, Y, h:i A"); ?>" readonly name="txt_hora" class="" style="width:200px; text-align:center;" onkeypress="return handleEnter(this, event)" >
							</td>
							  </tr>
							  <tr>
								<th><span>*</span>Tipo : </th>
								<td><select name='genero' style="width:150px; text-align:center; font-size:130%;" tabindex="4">									
									<option value='factura'>Factura</option>					
									</select>
								</td>
								<th><span>*</span>Serie : 
								<td><input name="ruc" type="text" id="ruc" tabindex="3" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Inicio de asientos piso 1." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td></tr>
							  <tr>
								<th><span>*</span>Numero Correlativo: 
								<td><input name="ruc" type="text" id="ruc" tabindex="3" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Inicio de asientos piso 1." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
								<th><span>*</span>Fecha Emision</th>
								<td>
									<input type="text" value=" <?php echo date("j \d\e M, Y, h:i A"); ?>" readonly name="txt_hora" class="" style="width:200px; text-align:center;" onkeypress="return handleEnter(this, event)" >
								</td>
							  </tr>
							  <tr>
								<th><span>*</span>Monto del Pago:</th>
								<td><input name="ca1fin" type="text" id="ca1fin" tabindex="9" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Final de asientos piso 1." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
								<th><span>*</span>Inporte Retenido: </th>
								<td><input name="ca2fin" type="text" id="ca2fin" tabindex="10" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Final de asientos piso 2." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off"; /></td>
							  </tr>
							  
							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
							  </tr>
							  <tr>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" tabindex="14" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.giro_form.submit();" /></td>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="15" /></td>
							  </tr>
						</table>
						
					</form>
				</div>
			</div>		
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Contenido de las consultas-->
	
	
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
    <div id="div_error">
    </div>
<?PHP
	}
	elseif (isset($_GET['ID']))
	{
		
		/***************************************/
		/* OBTENEMOS LOS DATOS DEL MOVIMIENTOS */
		/***************************************/
		
?>
	
	
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
<?PHP
		
		
			// MOSTRAMOS EL MENSAJE DE ERROR
			echo '<!-- Content unit - One column -->';
			echo '<div class="column1-unit">';
				echo '<h1>Error con la Operaci&oacute;n</h1>';
				echo '<p>'.$MsjError.'</p>';
			echo '</div>';
			echo '<!-- Limpiar Unidad del Contenido -->';
			echo '<hr class="clear-contentunit" />';
		
	}
 ?>
	
</div>
