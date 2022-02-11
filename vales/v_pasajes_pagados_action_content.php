<?php
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS	
	require_once 'cnn/config_master.php';
	if(isset($_GET['imprimir'])){
		$id_vale_pagado='NULL';
		$id_record=$_POST[id_record];
		$pasajero=strtoupper($_POST[nombre_pasajero]);
		$fecha_viaje=$_POST[f_viaje];
		$hora_viaje=$_POST[h_viaje];
		$origen=$_POST[origen_viaje];
		$destino=$_POST[destino_viaje];
		$nro_asiento=$_POST[nro_asiento];
		$nro_piso=$_POST[nro_piso];
		$detalle=$_POST[detalle];
		$monto=$_POST[monto];
		$guia_interna=$_POST[nro_guia];
		$nro_pasaje=$_POST[nro_pasaje];		
		$usuario=strtoupper($_SESSION['USUARIO']);
		$agencia=strtoupper($_SESSION['OFICINA']);		

		$db_transporte->query("SELECT*FROM pasaje_pagado WHERE nro_pasaje='$nro_pasaje'");
		$Almacen_Datos=$db_transporte->get();
		if(count($Almacen_Datos)==0){
			$db_transporte->query("INSERT INTO pasaje_pagado(id_pasaje_pagado,id_record,cliente,fecha_creacion,hora_creacion,usuario_crea,
								agencia_crea,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje)
								VALUES('$id_vale_pagado','$id_record','$pasajero',CURRENT_DATE(),CURRENT_TIME(),'$usuario','$agencia',
								'$detalle','$monto','$guia_interna','$fecha_viaje','$hora_viaje','$origen','$destino','$nro_pasaje')");
			$db_transporte->query("SELECT MAX(id_pasaje_pagado) AS id FROM pasaje_pagado");
			$Array=$db_transporte->get();
			$codigo_pasaje=$Array[0][0];
			/*
			echo "<script type='text/javascript'> 
	                window.open('imprimir_pasaje_pagado.php?codigo=".$codigo_pasaje."','Impresion de Pasaje Pagado','scrollbars=no, resizable=yes, width=1000, height=500, status=no, location=no, toolbar=no');
	              </script>";
	        */

	        $db_transporte->query("SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje FROM pasaje_pagado
								WHERE id_pasaje_pagado='$codigo_pasaje'");
		
			$Pasaje_Array = $db_transporte->get();
			
			// VERIFICAMOS SI SE OBTUVO DATOS
			if(count($Pasaje_Array) > 0)
			{		
				// OBTENEMOS LOS VALORES DEL ARRAY
				$cliente=$Pasaje_Array[0][0];
				$detalle=$Pasaje_Array[0][1];
				$monto=$Pasaje_Array[0][2];
				$nro_guia=$Pasaje_Array[0][3];
				$fecha_viaje=$Pasaje_Array[0][4];
				$hora_viaje=$Pasaje_Array[0][5];
				$origen=$Pasaje_Array[0][6];
				$destino=$Pasaje_Array[0][7];
				$nro_pasaje=$Pasaje_Array[0][8];			
			}
		}
		else echo "El boleto ingresado ya esta registrada en la Base de Datos";
		
		
	
	}
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
	<div class="Print_Vale">
		<div class="vale_content">
			<table width="400" border="0">
			<?php
			if(count($Almacen_Datos)==0){
			?>			  
			  <tr>
				<td colspan="2" class="monto" style="height:10px;">S/.<?php echo number_format($monto,2);?></td>
			  </tr>
			  <tr>
				<td style="font-size:7px;width:200px;letter-spacing:3px;">CLIENTE: </td>
				<td class="text_left" style="font-size:95%;width:500px;line-height:150%;"><?php echo $cliente;?></td>
			  </tr>
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">DETALLE:</td>
				<td class="text_left"><?php echo $detalle;?></td>
			  </tr>
			  <!--
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">N° GUIA: </td>
				<td class="text_left"><?php echo $nro_guia;?></td>
			  </tr>
				-->
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">FECHA VIAJE: </td>
				<td class="text_left"><?php echo $fecha_viaje;?> Hora: <?php echo $hora_viaje;?></td>
			  </tr>			  
			  <!--
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">HORA VIAJE: </td>
				<td class="text_left"><?php echo $hora_viaje;?></td>
			  </tr>
				-->
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">ORIGEN: </td>
				<td class="text_left"><?php echo $origen;?></td>
			  </tr>			  			  			  			  
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">DESTINO: </td>
				<td class="text_left"><?php echo $destino;?></td>
			  </tr>			  			  			  			  
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">PASAJE N°: </td>
				<td class="text_left"><?php echo $nro_pasaje;?></td>
			  </tr>		
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">FECHA EM.: </td>
				<td class="text_left"><?php echo date("Y-m-d");?> Hora: <?php echo date(" h:i A");?></td>
			  </tr>
			  <!--		  			  			  			  
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">HORA EM.: </td>
				<td class="text_left"><?php echo date(" h:i A");?></td>
			  </tr>		
			  -->  			  			  			  
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">USUARIO :</td>
				<td class="text_left" style="height:10px;"><?php echo $_SESSION['USUARIO']; ?></td>
			  </tr>
			  <tr><td colspan="2"></td></tr>
			  <tr><td colspan="2"></td></tr>
			  <tr><td colspan="2"></td></tr>			  
			  <tr style="height:5px;">
				<th  colspan="2" class="firma_vale"><HR></th>
			  </tr>
			  <tr>
				<th  colspan="2" class="text_center" style="letter-spacing:4px;height:5px;">RECIBÍ CONFORME	</th>
			  </tr>
			  <tr>
				<th  colspan="2" class="text_center" style="letter-spacing:4px;height:5px;">D.N.I.: </th>
			  </tr>
			  <script language="JavaScript"> 
					window.print();
					window.onfocus = function() 
					{
						/*window.open('','_parent','');*/
						location.href='v_pasajes_pagados.php';
					}
			</script>
			  <?php
			}
			else {
				echo '<tr>
						<td style="font-size:7px;width:200px;letter-spacing:3px;">El numero de boleto ya esta Registrado: </td>						
					  </tr>';					  				
			  ?>
			  <script language="JavaScript"> 					
					window.onfocus = function() 
					{
						alert("El Numero de Boleto ya esta registrado en la Base de Datos")
						location.href='v_pasajes_pagados.php';
					}
			</script>
			<?php
				}
			?>
			</table>
	  </div>		
	</div>

	
</div>

