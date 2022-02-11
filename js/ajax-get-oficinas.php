<?php
	include_once('../config_master.php');
	$id = $_GET['ID'];
	$TYPE = $_GET['TYPE'];
	$Oficina_Array = $_SESSION['OFICINAS'];
	if ($id > 0)
	{
		$db_transporte->QUERY("SELECT DISTINCT oficinas.`idoficina`,oficinas.`oficina`,ruta.`destino`,ruta.`hora`,id_ruta
						FROM oficinas INNER JOIN ruta ON oficinas.`idoficina`=ruta.`idoficina`
						WHERE oficinas.`idoficina` = " .$id ."");
		$List = $db_transporte->get();
		$db_transporte->query("SELECT id_bus,flota,marca,placa_rodaje FROM bus ORDER BY flota DESC");								
		$Bus_Array = $db_transporte->get();		
																	
		if (count($List) > 0)
		{
?>			
		<table border="0">
			  <tr>		
				<th style="width:90px;"><span>*</span><strong>Destino :</strong></th>
				<td colspan="3" style="width:110px;">
					<select name="cmb_destino" id="cmb_destino" class="combo" tabindex="3" onkeypress="return handleEnter(this, event)" title="Destino." style="width:350px;font-size:13px; font-weight:600;" onchange="Get_Datos_Destino('E_DERIVADO');" >
					  <?php
						if (count($List) == 0)
						{
							echo '<option value="">[ NO HAY DESTINOS...! ]</option>';
						}
						else {
							echo '<option value="" selected="selected">[ Seleccione Destino ]</option>';
							for ($fila = 0; $fila < count($List); $fila++)
							{
							  echo '<option value="'.$List[$fila][4].'" >'.utf8_encode($List[$fila][2]).'-----'.utf8_encode($List[$fila][3]).'</option>';						  						  
							}
						}
						
						//echo '<td id="dato2"><input id="txt_destino"  type="text" name="txt_destino" value="'.$List[$fila][3].'" title="Destino." tabindex="3" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>';
					  ?>
					</select>
                </td>
			  </tr>
		</table>
		<table>
			<tr id="DivDocumento">				
				<th><span>*</span>Destino : </th>
				<td id="Datos"><input id='txt_destino'  type='text' name='txt_destino' value="" title="Destino." tabindex="3" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>
				<th><span>*</span>Hora : </th>
				<td id="Datos2"><input id='txt_hora'  type='text' name='txt_hora' value="" tabindex="4" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>							  
		   </tr>
		</table>
			  			 		
						
<?php
		}
		
	}
?>
