<?php
$Oficina_Array = $_SESSION['OFICINAS'];//encuentra los nombres de la oficinas
require_once 'cnn/config_master.php';
$id_salida=$_GET[salida];
$nro_asiento=$_GET[asientos];
$destino_salida=$_GET[destino];
$origen_ofi=$_GET[origen];
$fecha_salida=$_GET[fecha];
$hora_salida=$_GET[hora];
$piso=$_GET[p];
$ruta=$_GET[ruta];
$id_bus=$_GET[bus];
$ruta=$_GET[ruta];
$direccion="asientos=$nro_asiento&p=$piso&origen=$origen_ofi&salida=$id_salida&destino=$destino_salida&fecha=$fecha_salida&hora=$hora_salida&ruta=$ruta";
//$direccion=$_GET[];
?>

<div class='cliform'>
	<form id='reserva_form' name='reserva_form' action="p_form_ventas_action.php?insertreserva" method="post">
		<h3>Tipo: Reserva</h3>
		<table>
			<tr>
				<th>Tipo de Reserva:</th>			
				<td colspan="5">
					<input name="txt_t_reserva" id="txt_t_reserva" type="text" value="" title="Tipo de Reserva." style="width:500px;font-size:110%; font-weight:bold;text-transform:uppercase;" onfocus="copiarDatos()">
				</td>
			</tr>
			<tr>
				<th>Hora de reserva:</th>
				<td>
				<!-- echo date(" h:i:s A "); muestra la hora actual de la maquina-->				
					<input name="txt_hora" id="txt_hora" type="text" value="<?php $h1=$hora_salida;echo date('h:i:s A', strtotime("$h1 - 3 hour"));?>" title="hora de reservación" style="width:200px; text-align:center; font-size:110%;font-weight:bold; text-transform:uppercase;">
				</td>
			</tr>							
		</table>
		<br/>
		<h3>Reserva en Grupos</h3>
		<table>	
			<tr>
				<th colspan="4">Seleccionar el piso e ingresar el número de asiento y hacer click en agregar</th>				
				<th colspan="2">Piso-Asiento:</th>								
				<td><input name="otra_agencia" id="otra_agencia" type="Checkbox" title="reserva de otra Agencia" onclick="deshabilitarcombo()"><span> Reserva de otra Agencia</span></td>				
			</tr>			
			<tr>
				<th style="width:60px;">Piso:</th>
				<td style="width:60px;">
					<select name="cmb_pisos" id="cmb_pisos" style="width:50px;text-align:center;font-size:110%;font-weight:bold;">
						<option value="1">1</option>
						<option value="2">2</option>
					</select>
				</td>
				<th style="width:60px;">Asiento:</th>
				<td style="width:160px;">
					<input name="txt_asientos" id="txt_asientos" type="text" title="Numero de Asiento" style="width:50px;font-size:110%;font-weight:bold;" maxlength="2" onkeyup="extractNumber(this,0,false);" />
					<input name="button" type="button" class="button" style="width:80px;" onclick="add(document.getElementById('cmb_pisos').value +'-'+ document.getElementById('txt_asientos').value)" value="Agregar" />
				</td>
				<td colspan="2" rowspan="3">
					<select name="lista[]" id="a" multiple="multiple" style="width:100px;height:90px; text-align:center;font-size:100%;font-weight:bold;">							
						<?php							
							$db_transporte->query("SELECT*FROM configuracion_bus WHERE id_bus='$id_bus' ORDER BY piso");
							$asientos_bus = $db_transporte->get();

							$db_transporte->query("SELECT piso,asiento FROM record_cliente WHERE id_salida='$id_salida'");
							$asientos_ocupados=$db_transporte->get();	
							if(count($asientos_bus)!=0)	{
								for($var=0;$var<count($asientos_bus);$var++){
									for($vari=0;$vari<count($asientos_ocupados);$vari++){
										if($asientos_ocupados[$vari][1]==$asientos_bus[$var][8] && $asientos_ocupados[$vari][0]==$asientos_bus[$var][3]){
											$existe=$asientos_bus[$var][8];													
										}
										if($asientos_ocupados[$vari][1]==$asientos_bus[$var][7] && $asientos_ocupados[$vari][0]==$asientos_bus[$var][3]){
											$existe2=$asientos_bus[$var][7];													
										}			
										if($asientos_ocupados[$vari][1]==$asientos_bus[$var][6] && $asientos_ocupados[$vari][0]==$asientos_bus[$var][3]){
											$existe3=$asientos_bus[$var][6];													
										}							
										if($asientos_ocupados[$vari][1]==$asientos_bus[$var][5] && $asientos_ocupados[$vari][0]==$asientos_bus[$var][3]){
											$existe4=$asientos_bus[$var][5];													
										}
										if($asientos_ocupados[$vari][1]==$asientos_bus[$var][4] && $asientos_ocupados[$vari][0]==$asientos_bus[$var][3]){
											$existe5=$asientos_bus[$var][4];													
										}
									}
									if($existe!=$asientos_bus[$var][8]){
										if($asientos_bus[$var][8]!="TM" && $asientos_bus[$var][8]!="TI" && $asientos_bus[$var][8]!="TD" && $asientos_bus[$var][8]!="TV" && $asientos_bus[$var][8]!="ES" && $asientos_bus[$var][8]!="TI" && $asientos_bus[$var][8]!="TR" && $asientos_bus[$var][8]!="BA" && $asientos_bus[$var][8]!="" && $asientos_bus[$var][8]!=" "){
											echo '<option value="'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][8].'">'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][8].'</option>';	
										}
									}
									if($existe2!=$asientos_bus[$var][7]){
										if($asientos_bus[$var][7]!="TM" && $asientos_bus[$var][7]!="TI" && $asientos_bus[$var][7]!="TD" && $asientos_bus[$var][7]!="TV" && $asientos_bus[$var][7]!="ES" && $asientos_bus[$var][7]!="TI" && $asientos_bus[$var][7]!="TR" && $asientos_bus[$var][7]!="BA" && $asientos_bus[$var][7]!="" && $asientos_bus[$var][7]!=" "){
											echo '<option value="'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][7].'">'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][7].'</option>';	
										}
									}
									if($existe3!=$asientos_bus[$var][6]){
										if($asientos_bus[$var][6]!="TM" && $asientos_bus[$var][6]!="TI" && $asientos_bus[$var][6]!="TD" && $asientos_bus[$var][6]!="TV" && $asientos_bus[$var][6]!="ES" && $asientos_bus[$var][6]!="TI" && $asientos_bus[$var][6]!="TR" && $asientos_bus[$var][6]!="BA" && $asientos_bus[$var][6]!="" && $asientos_bus[$var][6]!=" "){
											echo '<option value="'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][6].'">'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][6].'</option>';	
										}
									}
									if($existe4!=$asientos_bus[$var][5]){
										if($asientos_bus[$var][5]!="TM" && $asientos_bus[$var][5]!="TI" && $asientos_bus[$var][5]!="TD" && $asientos_bus[$var][5]!="TV" && $asientos_bus[$var][5]!="ES" && $asientos_bus[$var][5]!="TI" && $asientos_bus[$var][5]!="TR" && $asientos_bus[$var][5]!="BA" && $asientos_bus[$var][5]!="" && $asientos_bus[$var][5]!=" "){
											echo '<option value="'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][5].'">'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][5].'</option>';	
										}
									}
									if($existe5!=$asientos_bus[$var][4]){
										if($asientos_bus[$var][4]!="TM" && $asientos_bus[$var][4]!="TI" && $asientos_bus[$var][4]!="TD" && $asientos_bus[$var][4]!="TV" && $asientos_bus[$var][4]!="ES" && $asientos_bus[$var][4]!="TI" && $asientos_bus[$var][4]!="TR" && $asientos_bus[$var][4]!="BA" && $asientos_bus[$var][4]!="" && $asientos_bus[$var][4]!=" "){
											echo '<option value="'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][4].'">'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][4].'</option>';	
										}
									}																	
								}								
							}
							
						?>
					</select>
				</td>				
				<td>
					<select name="cmb_oficina_reserva" id="cmb_oficina_reserva" class="combo" title="Seleccionar oficina a cual reservar" style="font-size:13px;font-weight:600; width:200px;" disabled>
						<?php
							if(count($Oficina_Array)==0){
								echo '<option value="">[NO HAY OFICINAS....!]</option>';
							}else{
								echo '<option value="">[Selecione su Oficina]</option>';
								for($fila=0;$fila<count($Oficina_Array);$fila++){
									echo '<option value="'.$Oficina_Array[$fila][0].'">'.$Oficina_Array[$fila][1].'</option>';
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align:center;font_size:120%;">
					<input name="eliminar_asiento" type="button" class="button" value="Eliminar Asiento Reservado" style="width:250px;" onclick="DeleteSecondListItem();"/>
				</td>	
				<td></td>				
			</tr>
			<tr>
				<th colspan="4">
					<input name="reserva_pagada" id="reserva_pagada"type="Checkbox" title="Reserva pagada de otra Agencia"> Reserva pagada en otra agencia
				</th>				
				<td></td>				
			</tr>	
			<tr>
				<td colspan="7" style="text-align:center; font-size:120%;">
					<input name="btn_guardar_reserva" id="btn_guardar_reserva" type="submit" class="button" value="Guardar Reserva" style="width:180px;" onclick="this.disabled = 'true'; this.value = 'Enviando...';" />
				</td>
			</tr>
			<!--Mostrar los datos de los clientes para realizar su reserva-->
			<input type="hidden" name="txt_nombre" id="txt_nombre">
			<input type="hidden" name="txt_documento" id="txt_documento">
			<input type="hidden" name="txt_ndocumento" id="txt_ndocumento">
			<input type="hidden" name="txt_genero" id="txt_genero">
			<input type="hidden" name="txt_telefono" id="txt_telefono">
			<input type="hidden" name="txt_edad" id="txt_edad">
			<input type="hidden" name="txt_nacionalidad" id="txt_nacionalidad">
			<input type="hidden" name="txt_ruc" id="txt_ruc">
			<input type="hidden" name="txt_razon" id="txt_razon">
			<!--Mostrar los datos de viaje para realizar su reserva-->	
			<input type="hidden" name="txt_fecha_viaje" id="txt_fecha_viaje">		
			<input type="hidden" name="txt_hora_viaje" id="txt_hora_viaje">
			<input type="hidden" name="txt_piso" id="txt_piso">
			<input type="hidden" name="txt_asiento" id="txt_asiento">
			<input type="hidden" name="txt_codigo_salida" id="txt_codigo_salida">
			<input type="hidden" name="txt_bus" id="txt_bus" value="<?php echo $id_bus;?>">
			<input type="hidden" name="txt_ruta" id="txt_ruta" value="<?php echo $ruta;?>">
			<input type="hidden" name="txt_salida" id="txt_salida">
			<input type="hidden" name="txt_origen" id="txt_origen">
		</table>
	</form>
</div>
