<?php
require_once 'cnn/config_master.php';
$Buscar_Array = '';
$id_salida=$_GET['salida'];
$id_bus=$_GET['bus'];
$nro_asiento=$_GET['asientos'];
$destino_salida=$_GET['destino'];
$fecha_salida=$_GET['fecha'];
$hora_salida=$_GET['hora'];
$piso=$_GET['p'];
$ruta=$_GET['ruta'];
$origen=$_GET['origen'];
$direccion="asientos=$nro_asiento&p=$piso&origen=$origen&salida=$id_salida&destino=$destino_salida&fecha=$fecha_salida&hora=$hora_salida&ruta=$ruta&bus=$id_bus";
if(isset($_GET['buscar']))
{	
	$buscar=$_POST['buscar'];
	$sql_buscar="SELECT * FROM cliente WHERE nro_documento='$buscar'";
	$db_transporte->query($sql_buscar);
	$Buscar_Array = $db_transporte->get();
}
if (is_array($Buscar_Array) && count($Buscar_Array) > 0)
{
?>
<div class='cliform'>
	<form name="formSearhCliente" method='post' id="formSearhCliente" action='p_form_ventas.php?<?php echo $direccion;?>&buscar'>
			<table>
				<tr>
					<td style="text-align:center;font-weight:bold;">Buscar x Nombre o DNI</td>
					<td>					
						<input name="buscar" type="text" id="buscar" maxlength="8" onkeypress="return handleEnter(this,event);" title="Buscar por Nombre o DNI." style="width:120px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" />
						<input name="btn_Buscard" id="btn_Buscard" type="submit" class="button" value="Buscar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.formSearhCliente.submit();" />
					</td>					
				 </tr>
			</table>		
	</form>

	<!--Formulario de busqueda de clientes-->
	<!--<div class='contactform'>-->
	<form method='post' id="cliente_form" name="cliente_form" action='p_form_ventas_action.php?insertventa'>
		<table>
			<tr>
				<th><span>*</span>Apellidos Nombres:</th>			
				<td colspan="5">
					<input name="txt_Nombre" id="txt_Nombre" type="text" value="<?php echo utf8_decode($Buscar_Array[0][1]); ?>" title="Nombre cliente." style="width:500px; text-align:center;  font-size:110%; font-weight:bold;text-transform:uppercase;">
				</td>
			</tr>
			<tr>
				<th><span>*</span>Tipo Documento: </th>
				<td>
					<input id='t_documento' type='text' name='t_documento' value="<?php if($Buscar_Array[0][2]!=""){echo $Buscar_Array[0][2];}else{echo "DNI";}?>" title="Tipo de Documento." style="width:120px; text-align:center;font-weight:bold;font-size:110%;text-transform:uppercase;">
				</td>
				<th><span>*</span>N° Documento: </th>
				<td>
					<input name="txt_dni" type="text" id="txt_dni" maxlength="8" value="<?php echo $buscar;?>" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Remitente." style="width:100px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off" />
				</td>
				<th><span>*</span>Sexo: </th>
				<td>
					<select name='genero' style="width:120px; text-align:center; font-size:110%;font-weight:bold;">
						<?php if($Buscar_Array[0][7]=="M"){
							echo '<option value="M">Masculino</option>';
							echo '<option value="F">Femenino</option>';
							}else {
								echo '<option value="F">Femenino</option>';
								echo '<option value="M">Masculino</option>';
							}
						?>					
					</select>
				</td>
			</tr>
			<tr>
				<th><span>*</span>Teléfono:</th>
				<td>
					<input name="n_fono" type="text" id="n_fono" maxlength="11" value="<?php echo $Buscar_Array[0][10];?>" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Ingrese Número Telefonico." style="width:120px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"; />
				</td>
				<th><span>*</span>Edad</th>
				<td>
					<input name="txt_edad" type="text" maxlength="2" id="txt_edad" value="<?php echo $Buscar_Array[0][8];?>"onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Ingrese Edad." style="width:100px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"; />
				</td>
				<th><span>*</span>Nacionalidad</th>
				<td>
					<input name="txt_nacionalidad" id="txt_nacionalidad" value="<?php if($Buscar_Array[0][6]!=""){echo $Buscar_Array[0][6];}else{echo "PERUANO";}?>" type="text" value="PERUANO" title="Nacionalidad." style="width:120px; text-align:center; font-size:110%; font-weight:bold;text-transform:uppercase;">
				</td>
			</tr>
			<tr>
				<th>Ruc</th>
				<td>
					<input name="txt_ruc" type="text" maxlength="11" id="txt_ruc" value="<?php echo $Buscar_Array[0][4];?>" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Ingrese Ruc." style="width:120px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"; />
				</td>
				<th>Razon Social</th>
				<td colspan="3">
					<input name="r_social" id="r_social" type="text" value="<?php echo $Buscar_Array[0][5]?>" title="Razon Social." style="width:350px; text-align:center; font-size:110%; font-weight:bold;text-transform:uppercase;">
				</td>
			</tr>
			<tr>
				<th colspan="6" style="text-align:center; height:10px;">(<span>*</span>)Campos Requeridos</th>
			</tr>
			<!-- Botones para guardar datos 
			<tr>
				<td colspan="3" style="text-align:center;font-size:120%;"><input name="btn_guardar_cliente" id="btn_guardar_cliente" type="submit" class="button" value="Guardar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.giro_form.submit();" /></td>
				<td colspan="3" style="text-align:center;font-size:120%;"><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" /></td>
			</tr>
			-->
		</table>
	</form>	
</div>
<?php	
}else {
?>
<div class='cliform'>
	<!--Formulario de ingreso de datos cliente-->
	<form name="buscar_form" method='post' id="buscar_form" action='p_form_ventas.php?<?php echo $direccion;?>&buscar'>
        <table>
            <tr>
                <td style="text-align:center;font-weight:bold;">Buscar x Nombre o DNI</td>
                <td>					
                    <input name="buscar" type="text" maxlength="8" id="buscar" onkeypress="return handleEnter(this,event);" title="Buscar por DNI." style="width:120px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" />
                    <input name="btn_Buscard" id="btn_Buscard" type="submit" class="button" value="Buscar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.buscar_form.submit();" />
                </td>					
             </tr>
        </table>		
	</form>

	<!--Formulario de ingreso de nuevos clientes-->
	<!--<div class='contactform'>-->
	<form method='post' id="cliente_form" name="cliente_form" action='p_form_ventas_action.php?insertventa'>
		<table>
			<tr>
				<th><span>*</span>Apellidos Nombres:</th>			
				<td colspan="5">
					<input name="txt_Nombre" id="txt_Nombre" type="text" value="" title="Nombre cliente." style="width:500px; text-align:center;  font-size:110%; font-weight:bold;text-transform:uppercase;">
				</td>
			</tr>
			<tr>
				<th><span>*</span>Tipo Documento: </th>
				<td>
					<input id='t_documento' type='text' name='t_documento' value="DNI" title="Tipo de Documento." style="width:120px; text-align:center;font-weight:bold;font-size:110%;text-transform:uppercase;">
				</td>
				<th><span>*</span>N° Documento: </th>
				<td>
					<input name="txt_dni" type="text" id="txt_dni" maxlength="8" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Remitente." style="width:100px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off" />
				</td>
				<th><span>*</span>Sexo: </th>
				<td>
					<select name='genero' style="width:120px; text-align:center; font-size:110%;font-weight:bold;">
						<option value='F'>Femenino</option>
						<option value='M'>Masculino</option>					
					</select>
				</td>
			</tr>
			<tr>
				<th><span>*</span>Teléfono:</th>
				<td>
					<input name="n_fono" type="text" id="n_fono" maxlength="11" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Ingrese Número Telefonico." style="width:120px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"; />
				</td>
				<th><span>*</span>Edad</th>
				<td>
					<input name="txt_edad" type="text" maxlength="2" id="txt_edad" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Ingrese Edad." style="width:100px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"; />
				</td>
				<th><span>*</span>Nacionalidad</th>
				<td>
					<input name="txt_nacionalidad" id="txt_nacionalidad" type="text" value="PERUANO" title="Nacionalidad." style="width:120px; text-align:center; font-size:110%; font-weight:bold;text-transform:uppercase;">
				</td>
			</tr>
			<tr>
				<th>Ruc</th>
				<td>
					<input name="txt_ruc" type="text" id="txt_ruc" maxlength="11" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Ingrese Ruc." style="width:120px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"; />
				</td>
				<th>Razon Social</th>
				<td colspan="3">
					<input name="r_social" id="r_social" type="text" title="Razon Social." style="width:350px; text-align:center; font-size:110%; font-weight:bold;text-transform:uppercase;">
				</td>
			</tr>
			<tr>
				<th colspan="6" style="text-align:center; height:10px;">(<span>*</span>)Campos Requeridos</th>
			</tr>			
		</table>
	</form>	
</div>
<!--</div>-->
<?php 
}
?>
