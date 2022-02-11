<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Venta de Pasajes</h1>
    <?php 
		//llamar a la conexion
		require_once 'cnn/config_master.php';
		/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
		$usuario=$_SESSION['ID_USUARIO'];//id de usuario que esta ejecutando la operacion
		$id_oficina=$_SESSION['ID_OFICINA'];//id de la oficina que se esta realizando la operacion	
		require_once("is_logged_niv2.php");
		require_once("is_logged.php");	
		//Muestra el codigo del usuario que esta usando el sistema
		$id_salida=$_GET['salida'];
		$nro_asiento=$_GET['asientos'];
		$destino_salida=$_GET['destino'];
		$fecha_salida=$_GET['fecha'];
		$hora_salida=$_GET['hora'];
		$piso=$_GET['p'];		
		$ruta=$_GET['ruta'];		
		$origen=$_GET['origen'];
		$archivo= '';
		// file('C://serie.txt'); //archivo de texto para coger el numero de serie de cada agencia
		$texto_serie='153';
		
		//$archivo[0];//coge el valor del archivo de texto serie.txt				
		
		
		$db_transporte->query("SELECT id,serie,numero_actual FROM numeracion_documento WHERE idoficina='$id_oficina' AND id_documento=1 AND serie='$texto_serie'");
		$Numeracion_boleto= $db_transporte->get();
		$numero_boleto=$Numeracion_boleto[0][2]+1;
		//echo $numero_boleto;
		//echo $Numeracion_boleto[0][0],'-',$Numeracion_boleto[0][1],'-',$Numeracion_boleto[0][2];
	?>
	
	
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Contenido de las consultas-->
	<div class="column1-unit">		
		<!-- Inicio Contenido del Formulario Ventas-->
		<div class='contactform'>
			<form name="form_ventas_pasajes" id="form_ventas_pasajes">
				<table>
					<tr>
						<th>Origen: </th>
						<td colspan="2"><input id='txt_origen' type='text' readonly name='txt_origen' value="<?php echo $origen;?>" title="Origen de Salida." style="width:220px; text-align:center;font-weight:bold;"></td>
						<th>Destino: </th>
						<td colspan="2"><input id='txt_destino' type='text' readonly name='txt_destino' value="<?php echo $destino_salida;?>" title="Destino de Viaje" style="width:220px; text-align:center;font-weight:bold;"></td>					
					</tr>
					<tr>
						<th>Fecha de Viaje: </th>
						<td><input id='txt_fecha' type='text' readonly name='txt_fecha' value="<?php echo $fecha_salida;?>" title="Fecha de Salida." style="width:120px; text-align:center;font-weight:bold;"></td>
						<th>Hora de Viaje: </th>
						<td><input id='txt_hora' type='text' readonly name='txt_hora' value="<?php echo $hora_salida;?>" title="Hora de viaje." style="width:120px; text-align:center;font-weight:bold;"></td>
						<th>N°Boleto: </th>
						<td>
							<input id='txt_serie_boleto' type='text' readonly name='txt_serie_boleto' value="<?php echo $Numeracion_boleto[0][1];?>" title="Serie del boleto" style="width:30px; text-align:center;font-weight:bold;"><span>-</span>
							<input id='txt_boleto' type='text' readonly name='txt_boleto' value="<?php echo $numero_boleto;?>" title="Numero de boleto" style="width:85px; text-align:center;font-weight:bold;">
						</td>
					</tr>
					<tr>
						<th>Piso: </th>
						<td><input id='txt_piso' type='text' readonly name='txt_piso' value="<?php echo $piso;?>" title="Piso" style="width:50px; text-align:center;font-weight:bold;"></td>
						<th>Asiento: </th>
						<td><input id='txt_asiento' type='text' readonly name='txt_asiento' value="<?php echo $nro_asiento;?>" title="Número de asiento" style="width:50px; text-align:center;font-weight:bold;"></td>
						<th>Importe: </th>
						<td><input id='txt_importe_total' type='text' readonly name='txt_importe_total' value="" title="Importe del viaje" style="width:130px; text-align:center;font-weight:bold;"></td>
					</tr>
					<input type="hidden" value="<?php echo $id_salida;?>" name="txt_id_salida" id="txt_id_salida"/>
				</table>
			</form>			
		</div>	
	<!-- Fin Contenido del Formulario Ventas-->
		
		<ul class="tabs">
			<li><a href="#tab1" id="tabCliente">Datos Personales del Cliente</a></li>
			<li><a href="#tab2" id="tabVenta">Ventas</a></li>
			<li><a href="#tab3" id="tabReserva">Reservas</a></li>
			<li><a href="#tab4" id="tabVentaOtraAgencia">V. Otra Agencia</a></li>			
		</ul>

		<div class="tab_container">
			<div id="tab1" class="tab_content">								
				<?php include_once('ventas/p_form_cliente.php');?>				 				
			</div>
			<div id="tab2" class="tab_content" >
				<?php include_once('ventas/p_form_venta.php');?>
			</div>
			<div id="tab3" class="tab_content">
			   <?php include_once('ventas/p_form_reserva.php');?>
			</div>
			<div id="tab4" class="tab_content">
			   <p style="display:none;">tmr</p>
			</div>			
		</div>		
		
	</div>
	
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
    <div id="div_error">
    </div>
	
</div>
