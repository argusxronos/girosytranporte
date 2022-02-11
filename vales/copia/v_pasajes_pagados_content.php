<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Pasajes Pagados</h1>
    <?php 
    	require_once 'cnn/config_master.php';
		if (isset($_GET['buscar'])) {
			$nro_serie=$_POST[nro_serie];
			$nro_boleto=$_POST[nro_boleto];
			if($nro_boleto !="" && $nro_serie!=""){
				$sql_buscar_pasaje="SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
					record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
					record_cliente.`importe`,record_cliente.`id_record`,
					CONCAT(record_cliente.`serie_boleto`,' - ',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
					INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
					WHERE record_cliente.`serie_boleto`='$nro_serie' AND record_cliente.`numero_boleto`='$nro_boleto'";
				$db_transporte->query($sql_buscar_pasaje);
				$Pasajes_Array= $db_transporte->get();			
			}
		}
	?>
	
	
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Contenido de las consultas-->
	<div class="column1-unit">		
		<!-- Inicio Contenido del Formulario Ventas-->
		<div class='contactform'>
			<form name="pasajes_pagados" id="pasajes_pagados" method='post' action="v_pasajes_pagados.php?buscar">
				<table>
					<tr>
						<th><span>*</span>Ingrese Número de Serie y Número de boleto: </th>
						<td colspan="2" style="text-align:right;">									
							<input id='nro_serie' type='text' name='nro_serie' value="" title="Número de Serie." onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:100px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off"> - 
							<input id='nro_boleto' type='text' name='nro_boleto' value="" title="Número de Boleto." onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:100px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
							<input name="btn_Buscar" id="btn_Buscar" type="submit" class="button" value="Buscar" onclick="this.value = 'Enviando...';" />
						</td>
					</tr>					
				</table>
			</form>			
		</div>		
	</div>
	
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
    
    <?php
    if (isset($_GET['buscar'])) {
    	if(count($Pasajes_Array)>0){
    ?>		     
		    <div class="column1-unit">
		    	<div class="contactform">
			    	<form name="resultado_pasaje" id="resultado_pasaje" method="post" action="v_pasajes_pagados_action.php?imprimir" onsubmit="return validacion(this)">
			    		<table>
			    			<tr>
			    				<th>Nombre Pasajero:</th>
			    				<td colspan="3">
			    					<input id="nombre_pasajero" name="nombre_pasajero" type="text" readonly value="<?php echo utf8_encode($Pasajes_Array[0][2]);?>" title="Nombre y Apellido de Pasajero" style="width:500px; text-align:center;font-size:110%; font-weight:bold;text-transform:uppercase;">
			    				</td>
			    			</tr>
			    			<tr>
			    				<th>Fecha Viaje:</th>
			    				<td>
			    					<input id="f_viaje" name="f_viaje" type="text" readonly value="<?php echo utf8_encode($Pasajes_Array[0][3]);?>" title="Fecha de Viaje" style="width:200px; text-align:center;font-size:110%; font-weight:bold;text-transform:uppercase;">
			    				</td>
			    				<th>Hora Viaje:</th>
			    				<td>
			    					<input id="h_viaje" name="h_viaje" type="text" readonly value="<?php echo utf8_encode($Pasajes_Array[0][4]);?>" title="Hora de Viaje" style="width:200px; text-align:center;font-size:110%; font-weight:bold;text-transform:uppercase;">
			    				</td>
			    			</tr>
			    			<tr>
			    				<th>Origen Viaje:</th>
			    				<td>
			    					<input id="origen_viaje" name="origen_viaje" type="text" readonly value="<?php echo utf8_encode($Pasajes_Array[0][5]);?>" title="Oreigen de Viaje" style="width:200px; text-align:center;font-size:110%; font-weight:bold;text-transform:uppercase;">
			    				</td>
			    				<th>Destino Viaje:</th>
			    				<td>
			    					<input id="destino_viaje" name="destino_viaje" type="text" readonly value="<?php echo utf8_encode($Pasajes_Array[0][6]);?>" title="Destino Viaje" style="width:200px; text-align:center;font-size:110%; font-weight:bold;text-transform:uppercase;">
			    				</td>
			    			</tr>
			    			<tr>
			    				<th>Número de Asiento:</th>
			    				<td>
			    					<input id="nro_asiento" name="nro_asiento" type="text" readonly value="<?php echo $Pasajes_Array[0][0];?>" title="Número de Asiento" style="width:100px; text-align:center;font-size:110%; font-weight:bold;text-transform:uppercase;">
			    				</td>
			    				<th>Número de Piso</th>
			    				<td>
			    					<input id="nro_piso" name="nro_piso" type="text" readonly value="<?php echo $Pasajes_Array[0][1];?>" title="Número de Piso" style="width:100px; text-align:center;font-size:110%; font-weight:bold;text-transform:uppercase;">
			    				</td>
			    			</tr>
			    			<tr>
			    				<th>Detalles</th>
			    				<td colspan="3">
			    					<input id="detalle" name="detalle" type="text" readonly value="<?php echo utf8_encode($Pasajes_Array[0][7])?>" title="Detalle de Reserva Pagada" style="width:500px; text-align:center;font-size:110%; font-weight:bold;text-transform:uppercase;">
			    				</td>
			    			</tr>
			    			<tr>
			    				<th>Monto:</th>
			    				<td>
			    					<input id='monto' type='text' name='monto' title="Monto." readonly value="<?php echo utf8_encode($Pasajes_Array[0][8]);?>"onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,2,false);" style="width:200px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
			    				</td>
			    				<th>N° Guia Interna:</th>
			    				<td>
			    					<input id='nro_guia' type='text' name='nro_guia' title="Número de Guia Interna." onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" style="width:200px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off">
			    				</td>			    				
			    			</tr>
			    			<input id='id_record' type='hidden' name='id_record' value="<?php echo $Pasajes_Array[0][9];?>">
			    			<input id='nro_pasaje' type='hidden' name='nro_pasaje' value="<?php echo $Pasajes_Array[0][10];?>">
			    			<tr>
			    				<td colspan="4" style="text-align:center;font-size:140%;">
			    					<input name="btn_Buscar" id="btn_Buscar" type="submit" class="button" value="Imprimir">
			    				</td>
			    			</tr>
			    		</table>
			    	</form>
		    	</div>
		    </div>
		<script type="text/javascript">
	    function validacion(){	    
		    if (document.resultado_pasaje.nro_guia.value.length==0){
		       alert("Tiene que ingresar el numero de guia")
		       document.resultado_pasaje.nro_guia.focus()
		       return false;		       
		    }	    
		} 
    </script>  
    <?php
    	}
    }    
    ?>
	
</div>
