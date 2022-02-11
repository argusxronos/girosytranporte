<?php
require_once 'cnn/config_master.php';
$id_salida=$_GET['salida'];
$id_bus=$_GET['bus'];
$nro_asiento=$_GET['asientos'];
$destino_salida=$_GET['destino'];
$fecha_salida=$_GET['fecha'];
$hora_salida=$_GET['hora'];
$piso=$_GET['p'];
$ruta=$_GET['ruta'];
$origen=$_GET['origen'];
$direccion="&asientos=$nro_asiento&p=$piso&origen=$origen&salida=$id_salida&destino=$destino_salida&fecha=$fecha_salida&hora=$hora_salida&ruta=$ruta&bus=$id_bus";
//$direccion=$_GET[];
?>
<!--Copiar los datos del formulario cliente-->
<script type="text/javascript" src="js/copiar_datos.js"></script>
<!--Fin Copiar los datos del formulario cliente-->

<div class='cliform'>
	<form id='venta_form' name="venta_form" method='post' action='p_form_ventas_action.php?insertventa' >
		<h3>Tipo: Venta</h3>
		<table>
			<?php
				$subrutas="SELECT id_sr,localidad, precio_p1,precio_p2, principal FROM sub_rutas WHERE id_rutahora='$ruta' ORDER BY principal DESC";		
				$db_transporte->query($subrutas);						
				$Rutas_Array = $db_transporte->get();				
			?>
			<tr>
				<th>Destino:</th>			
				<td colspan="5">
					<input name="txt_destino" id="txt_destino" onfocus="copiarDatos()" tabindex="1" type="text" readonly value="<?php echo $Rutas_Array[0][1];?>" title="Destino a viajar." style="width:500px; text-align:center;  font-size:110%; font-weight:bold;text-transform:uppercase;">
				</td>
			</tr>
			<tr>
				<th>Importe:</th>
				<td colspan="5">
					<?php 
					if($piso=='1'){
						echo '<input name="txt_importe" id="txt_importe" type="text" tabindex="2" value="'.$Rutas_Array[0][2].'" title="Importe a pagar" onkeypress="return handleEnter(this,event);"  ONKEYUP="extractNumber(this,2,false); document.venta_form.txt_importe_letras.value = covertirNumLetras(document.venta_form.txt_importe.value, 1);" style="width:110px; text-align:center; font-size:110%; font-weight:bold; text-transform:uppercase;"><span> -</span>
							<input name="txt_importe_letras" id="txt_importe_letras" readonly type="text" value="" title="Importe a pagar" style="width:490px; text-align:center; font-size:110%; font-weight:bold; text-transform:uppercase;">';
					}
					if($piso=='2'){
						echo '<input name="txt_importe" id="txt_importe" type="text" tabindex="2" value="'.$Rutas_Array[0][3].'" title="Importe a pagar" onkeypress="return handleEnter(this,event);"  ONKEYUP="extractNumber(this,2,false); document.venta_form.txt_importe_letras.value = covertirNumLetras(document.venta_form.txt_importe.value, 1);" style="width:110px; text-align:center; font-size:110%; font-weight:bold; text-transform:uppercase;"><span> -</span>
							<input name="txt_importe_letras" id="txt_importe_letras" readonly type="text" value="" title="Importe a pagar" style="width:490px; text-align:center; font-size:110%; font-weight:bold; text-transform:uppercase;">';
					}
					?>					
				</td>			
			</tr>
			<tr id="DivDocumento">
				<th>DSCTO</th>
				<td>				
					<input name="txt_descuento" id="txt_descuento" type="text" value="" tabindex='3' onkeypress="return handleEnter(this,event);"  title="Descuento" style="width:110px; text-align:center; font-size:110%; font-weight:bold; text-transform:uppercase;">
				</td>
				<th>Sub Rutas</th>	
				<td>
					<select name="cmb_ruta" class="combo" title="Sub Rutas de salida" tabindex="4" style="font-size:110%; width:200px; font-weight:bold;" onchange="precios();" onfocus="copiarDatos()">
						<?php																																									
							if (count($Rutas_Array) == 0)
							{
								echo '<option value="">[ NO HAY RUTAS...! ]</option>';
							}
							else
							{
								echo '<option value="'.$Rutas_Array[0][0].'">'.$Rutas_Array[0][1].' '.$Rutas_Array[0][2].' '.$Rutas_Array[0][3].'</option>';
								for ($fila = 0; $fila < count($Rutas_Array); $fila++)										
								{
									if($Rutas_Array[$fila][0]!=$Rutas_Array[0][0])
									{
										echo '<option value="'.$Rutas_Array[$fila][0].'" >'.$Rutas_Array[$fila][1].' '.$Rutas_Array[$fila][2].' '.$Rutas_Array[$fila][3].'</option>';										
									}																			
								}
							}																													
						 ?>
					</select>	
					
				</td>		
				<th>P1<input name="txt_precio1" id="txt_precio1" type="text" readonly value="<?php echo $Rutas_Array[0][2]?>" title="Precio 1" style="width:40px; text-align:center; font-size:110%; font-weight:bold; text-transform:uppercase;"></th>
				<th>P2<input name="txt_precio2" id="txt_precio2" type="text" readonly value="<?php echo $Rutas_Array[0][3]?>" title="Precio 2" style="width:40px; text-align:center; font-size:110%; font-weight:bold; text-transform:uppercase;"></th>
			</tr>
			<tr>
				<th> Total a Pagar:</th>
  <td colspan="5">
					<input name="txt_importe_pagar" id="txt_importe_pagar" tabindex="5" readonly type="text" value="" title="Importe a pagar" onkeypress="return handleEnter(this,event);"  ONKEYUP="extractNumber(this,2,false);document.form_ventas_pasajes.txt_importe_total.value = document.venta_form.txt_importe_pagar.value;" style="width:110px; text-align:center; font-size:110%; font-weight:bold; text-transform:uppercase;"><span> -</span>
					<input name="txt_importe_pagar_letras" id="txt_importe_pagar_letras" tabindex="6" readonly type="text" value="" title="Importe a pagar" style="width:490px; text-align:center; font-size:110%; font-weight:bold; text-transform:uppercase;">				
				</td>			
			</tr>
			<!--Almacenar los datos del formulario cliente en cajas de texto-->
			<input type="text" name="txt_nombre" id="txt_nombre" />
			<input type="text" name="txt_documento" id="txt_documento"/>
			<input type="text" name="txt_ndocumento" id="txt_ndocumento"/>
			<input type="text" name="txt_genero" id="txt_genero"/>
			<input type="text" name="txt_telefono" id="txt_telefono"/>
			<input type="text" name="txt_edad" id="txt_edad"/>
			<input type="text" name="txt_nacionalidad" id="txt_nacionalidad"/>
			<input type="text" name="txt_ruc" id="txt_ruc"/>
			<input type="text" name="txt_razon" id="txt_razon"/>
			<!--Fin de almacenar los datos del los clientes-->
			<!--Almacenar Datos de Salidas -->
			<input type="text" name="txt_origen" id="txt_origen"/>
			<input type="text" name="txt_salida" id="txt_salida"/>
			<input type="text" name="txt_fecha_viaje" id="txt_fecha_viaje"/>
			<input type="text" name="txt_hora_viaje" id="txt_hora_viaje"/>
			<input type="text" name="txt_boleto" id="txt_boleto"/>
			<input type="text" name="txt_serie_boleto" id="txt_serie_boleto"/>
			<input type="text" name="txt_piso" id="txt_piso"/>
			<input type="text" name="txt_asiento" id="txt_asiento"/>
			<input type="text" name="txt_importe_final" id="txt_importe_final"/>	
			<input type="text" name="txt_codigo_salida" id="txt_codigo_salida" value="<?php echo $id_salida; ?>"/>
			<input type="text" name="txt_bus" id="txt_bus" value="<?php echo $id_bus;?>"/>
			<input type="text" name="txt_ruta" id="txt_ruta" value="<?php echo $ruta;?>"/>			
			<!--Fin de almacenar los datos de salidas-->
			<tr>
				<td colspan="6" style="text-align:center; font-size:120%;">
					<input name="btn_guardar_venta" id="btn_guardar_venta" type="submit" class="button" tabindex="7" value="Grabar Venta"/>
				</td>
			</tr>
			
		</table>
	</form>
</div>
