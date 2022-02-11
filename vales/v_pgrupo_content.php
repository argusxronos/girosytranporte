<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Pasajes Pagados en Grupo</h1>   
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Contenido de las consultas-->
	<div class="column1-unit">		
		<!-- Inicio Contenido del Formulario Ventas-->
		<div class='contactform'>
			<form name="nuevo_vale" id="nuevo_vale" method='post' action="v_pgrupo_action.php?insert" onsubmit="return validacion(this)">
				<table>
					<tr>
						<th><span>*</span>N° Pasaje: </th>
						<td style="text-align:center;">																
							<input id='n_serie1' type='text' name='n_serie1' title="Numero de Serie." tabindex="1" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:60px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"><span> - </span> 
							<input id='n_boleto1' type='text' name='n_boleto1' title="Numero de Boleto." tabindex="2" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:80px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
						</td>
						<th><span>*</span>N° Pasaje: </th>
						<td style="text-align:center;">																
							<input id='n_serie2' type='text' name='n_serie2' title="Numero de Serie." tabindex="3" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:60px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"><span> - </span> 
							<input id='n_boleto2' type='text' name='n_boleto2' title="Numero de Boleto." tabindex="4" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:80px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
						</td>
					</tr>
					<tr>
						<th>N° Pasaje: </th>
						<td style="text-align:center;">																
							<input id='n_serie3' type='text' name='n_serie3' title="Numero de Serie." tabindex="5" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:60px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"><span> - </span> 
							<input id='n_boleto3' type='text' name='n_boleto3' title="Numero de Boleto." tabindex="6" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:80px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
						</td>
						<th>N° Pasaje: </th>
						<td style="text-align:center;">																
							<input id='n_serie4' type='text' name='n_serie4' title="Numero de Serie." tabindex="7" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:60px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"><span> - </span> 
							<input id='n_boleto4' type='text' name='n_boleto4' title="Numero de Boleto." tabindex="8" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:80px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
						</td>
					</tr>
					<tr>
						<th>N° Pasaje: </th>
						<td style="text-align:center;">																
							<input id='n_serie5' type='text' name='n_serie5' title="Numero de Serie." tabindex="9" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:60px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"><span> - </span> 
							<input id='n_boleto5' type='text' name='n_boleto5' title="Numero de Boleto." tabindex="10" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:80px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
						</td>
						<th>N° Pasaje: </th>
						<td style="text-align:center;">																
							<input id='n_serie6' type='text' name='n_serie6' title="Numero de Serie." tabindex="11" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:60px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"><span> - </span> 
							<input id='n_boleto6' type='text' name='n_boleto6' title="Numero de Boleto." tabindex="12" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:80px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
						</td>
					</tr>
					<tr>
						<th>N° Pasaje: </th>
						<td style="text-align:center;">																
							<input id='n_serie7' type='text' name='n_serie7' title="Numero de Serie." tabindex="13" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:60px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"><span> - </span> 
							<input id='n_boleto7' type='text' name='n_boleto7' title="Numero de Boleto." tabindex="14" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:80px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
						</td>
						<th>N° Pasaje: </th>
						<td style="text-align:center;">																
							<input id='n_serie8' type='text' name='n_serie8' title="Numero de Serie." tabindex="15" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:60px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"><span> - </span> 
							<input id='n_boleto8' type='text' name='n_boleto8' title="Numero de Boleto." tabindex="16" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:80px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
						</td>
					</tr>
					<tr>
						<th><span>*</span>N° vale: </th>
						<td colspan="3">																
							<input id='n_vale' type='text' name='n_vale' maxlength="7" title="Numero de Vale." tabindex="17" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:100px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
						</td>						
					</tr>
					<tr>
						<td colspan="4" style="text-align:center;font-size:140%;">
							<input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" onclick="this.value = 'Enviando...';" />
						</td>
					</tr>
				</table>
			</form>			
		</div>		
	</div>
	
	<script type="text/javascript">
	    function validacion(){	    
		    if (document.nuevo_vale.n_serie1.value.length==0){
		       alert("Tiene que ingresar la serie del boleto pagado")
		       document.nuevo_vale.n_serie1.focus()
		       return false;		       
		    }
		    if (document.nuevo_vale.n_boleto1.value.length==0){
		       alert("Para registrar debe ingresar el número de boleto pagado")
		       document.nuevo_vale.n_boleto1.focus()
		       return false;		       
		    }
		    if (document.nuevo_vale.n_vale.value.length==0){
		       alert("Tiene que ingresar el Numero de Vale de la agencia")
		       document.nuevo_vale.n_vale.focus()
		       return false;		       
		    }	    
		} 
    </script> 
	
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />        
</div>
