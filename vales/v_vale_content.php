<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Nuevo Vale</h1>   
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Contenido de las consultas-->
	<div class="column1-unit">		
		<!-- Inicio Contenido del Formulario Ventas-->
		<div class='contactform'>
			<form name="nuevo_vale" id="nuevo_vale" method='post' action="v_vale_action.php?insert" onsubmit="return validacion(this)">
				<table>
					<tr>
						<th><span>*</span>Monto: </th>
						<td style="text-align:center;">																
							<input id='monto_vale' type='text' name='monto_vale' title="Monto a Entregar." tabindex="1" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,2,false);" style="width:120px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">							
						</td>
						<th>Fecha: </th>
						<td style="text-align:center;">
							<input id="fecha_vale" name="fecha_vale" title="Fecha" readonly value="<?php echo date("d/m/Y");?>" style="width:120px; text-align:center;font-size:110%; font-weight:bold;">
						</td>
					</tr>
					<tr>
						<th><span>*</span>Motivo: </th>
						<td colspan="3">
							<textarea name="motivo" id="motivo" title="Motivo para crear nuevo vale" tabindex="2" style="width:500px;height:40px;font-weight:bold;text-transform:uppercase;"></textarea>							
						</td>						
					</tr>	
					<tr>
						<th><span>*</span>Solicita: </th>
						<td colspan="3" >
							<input id="empleado" name="empleado" title="Nombre del Solicitante del Vale" tabindex="3" style="width:500px; text-align:center;font-size:110%; font-weight:bold;text-transform:uppercase;">
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
		    if (document.nuevo_vale.monto_vale.value.length==0){
		       alert("Tiene que ingresar el monto para realizar el vale")
		       document.nuevo_vale.monto_vale.focus()
		       return false;		       
		    }
		    if (document.nuevo_vale.motivo.value.length==0){
		       alert("Tiene que ingresar el motivo para realizar el vale")
		       document.nuevo_vale.motivo.focus()
		       return false;		       
		    }
		    if (document.nuevo_vale.empleado.value.length==0){
		       alert("Tiene que ingresar el Nombre del Empleado")
		       document.nuevo_vale.empleado.focus()
		       return false;		       
		    }	    
		} 
    </script> 
	
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />        
</div>
