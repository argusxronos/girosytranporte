<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	$ofici_nombre=$_SESSION['OFICINA'];
	// VERIFICAMOS SI ESTA LOGEADO
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged_niv2.php");
	require_once("is_logged.php");
	// CREAMOS LA CONSULTA DE BUSQUEDA
	if(isset($_GET['buscar']))
	{
		$buscar=$_POST[buscar];
		$ofi=$_POST[cmb_buscar_agencia];
		//echo $ofi;
		$sql = "SELECT salida.`fecha`,ruta.`destino`,salida.`hora`,oficinas.`oficina`,bus.`flota`,bus.`marca`,id_salida
		FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
		INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
		INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
		WHERE oficinas.`idoficina`='$ofi' and salida.`fecha`='$buscar'";
		$sql_rows = "SELECT COUNT(id_salida) AS TOTAL
		FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
		INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
		INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
		WHERE oficinas.`idoficina`='$ofi' and salida.`fecha`='$buscar'";
	}
	else {
		$sql = "SELECT salida.`fecha`,ruta.`destino`,salida.`hora`,oficinas.`oficina`,bus.`flota`,bus.`marca`,id_salida
		FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
		INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
		INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
		WHERE oficinas.`oficina`='$ofici_nombre'AND salida.`fecha`=CURDATE()";
		$sql_rows = "SELECT COUNT(id_salida) AS TOTAL
		FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
		INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
		INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
		WHERE oficinas.`oficina`='$ofici_nombre' AND salida.`fecha`=CURDATE()";
	}	
			
	
	
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 20;
	
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	$sql = $sql ." ORDER BY salida.`fecha` DESC LIMIT $from, $cantidad;";
	
	$sql_rows = $sql_rows .';';
	// OBTEMOS LOS DATOS DE MOVIMIENTOS
	require_once 'cnn/config_master.php';
	// REALIZAMOS LA CONSULTA A LA BD
	$db_transporte->query($sql_rows);
	$totalRows = $db_transporte->get('TOTAL');
	
	$db_transporte->query($sql);
	$Trans_Array = $db_transporte->get();
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Nueva Salida</h1>
    <?php 
	if (!isset($_GET['ID']))
	{
?>

<!-- Script para mensaje de confirmacion de eliminacion de datos -->
	<script>
    function confirmDelete(link) {
        if (confirm("¿Desea eliminar este campo?")) {
            doAjax(link.href, "POST"); // doAjax needs to send the "confirm" field
        }
        return false;
    }
	</script>
<!--fin de script-->

	<!-- Contenido del Formulario -->
	<div class="column1-unit">
		<script type="text/javascript">
			function validar(e) {
				var tecla = (document.all) ? e.keyCode : e.which;
				var contenido = document.getElementById("hr").value;
				if (tecla==8 || tecla==0)
					return true;
				if (contenido == "" || contenido < 2)
					patron =/\d/;
				else if (contenido == 2)
					patron =/[0-4]/;
				else return false;
				te = String.fromCharCode(tecla);
				return patron.test(te);
			}
		</script>
	  <h1>Ingrese Datos de Nueva Salida - <span>RECUERDE INGRESAR BIEN LOS DATOS</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			<!--<legend>Nuevo Registro</legend>-->
			<div class='column1-unit'>
				<div class='contactform'>
					<?php
					//COMIENZO DE SALIDA.PHP?UPDATE
					
						if (isset($_GET['update']))
						{
							$valor=$_GET[update];
							$Mostrar_Datos = "SELECT salida.`idoficina`,oficinas.`oficina`,salida.`fecha`,salida.`id_ruta`,ruta.`destino`,
											ruta.`hora`,salida.`id_bus`,bus.`flota`,bus.`placa_rodaje`,salida.`id_salida` FROM salida
											INNER JOIN bus ON bus.`id_bus`=salida.`id_bus`
											INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
											INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
											WHERE salida.`id_salida`='$valor'";
							$db_transporte->query($Mostrar_Datos);
							$Mostrar_Array = $db_transporte->get();
							
					?>	
					<form name="salida_form" method="post" id="salida_form" action="p_salida_action.php?update">
						<table border="0">
							  <tr>
								<th><span>*</span>Oficina: </th>
									<td>
									<!--<select name="cmb_agencia_origen" class="combo" tabindex="1" onkeypress="return handleEnter(this, event)" title="Ruta de Destino." style="font-size:13px; font-weight:600;" onchange="Get_Oficinas_Numeracion_Derivado('E_DERIVADO');">-->
									<select name="cmb_agencia_origen" id="cmb_agencia_origen" class="combo" title="Agencia de origen del giro." tabindex="1" onkeypress="return handleEnter(this, event)" style="font-size:13px; font-weight:600;" >
									  <?php
												if (count($Oficina_Array) == 0)
												{
													echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
												}
												else
												{
													echo '<option value="'.$Mostrar_Array[0][0].'" selected="selected">'.$Mostrar_Array[0][1].'</option>';
													for ($fila = 0; $fila < count($Oficina_Array); $fila++)
													{
														if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] == $Oficina_Array[$fila][0])
															echo '<option value="'.$Oficina_Array[$fila][0].'"<option> '.$Oficina_Array[$fila][1].' </option>';
														else
															echo '<option value="'.$Oficina_Array[$fila][0].'" disabled="disabled"> '.$Oficina_Array[$fila][1].' </option>';
													}
												}
										?>
									</select>				
									</td>
								<th><span>*</span>Fecha : 
								<td>
									<input type="text" value="<?php echo $Mostrar_Array[0][2]?>" readonly name="txt_fecha" class="" style="width:150px; text-align:center;" onkeypress="return handleEnter(this, event)" >
								</td>
							  </tr>
						</table>
						<div id="div_fila_usuario">
							<table border="0">
							  <tr>
								<th style="width:90px;"><span>*</span><strong>Destino :</strong></th>
								<td colspan="3" style="width:110px;">
									<select name="cmb_destino" id="cmb_destino" class="combo" tabindex="2" onkeypress="return handleEnter(this, event)" title="Destino a dirigirse." style="width:350px;font-size:13px; font-weight:600;" onchange="Get_Datos(event, this, 2);">
									  <?php		
										$ofi_origen=$Mostrar_Array[0][0];
										$db_transporte->query("SELECT ruta.`id_ruta`,ruta.`destino`,ruta.`hora`
														FROM oficinas INNER JOIN ruta ON oficinas.`idoficina`=ruta.`idoficina`
														WHERE oficinas.`idoficina` ='$ofi_origen'");
										$Lista = $db_transporte->get();										
										echo '<option value="'.$Mostrar_Array[0][3].'" selected="selected">'.$Mostrar_Array[0][4].'-----'.$Mostrar_Array[0][5].'</option>';
										for ($fila = 0; $fila < count($Lista); $fila++)
										{										
											if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] ==$Lista[$fila][0])
												echo '<option value="'.$Lista[$fila][0].'"> '.$Lista[$fila][1].'-----'.$Lista[$fila][2].' </option>';
											else
												echo '<option value="'.$Lista[$fila][0].'"> '.$Lista[$fila][1].'-----'.$Lista[$fila][2].' </option>';																													
										}
									  ?>									  									  
									</select>
								</td>
							  </tr>
							  <tr id="DivDocumento">
								<th><span>*</span>Destino : </th>
								<td id="dato2"><input id='txt_destino' disabled="" type='text' name='txt_destino' value="<?php echo $Mostrar_Array[0][4]?>" title="Destino." tabindex="3" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>
								<th><span>*</span>Hora : </th>
								<td><input type ="text" id="dato2" name = "txt_hora" value="<?php echo $Mostrar_Array[0][5]?>" onkeypress="return validar(event)" title="Hora de salida." tabindex="4" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>
								<!--<td id="dato2"><input id='txt_hora'  type='text' name='txt_hora' value="" title="Hora de salida." tabindex="4" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>	-->
							  </tr>							   
							</table>
						</div>
						<table>
						  <tr>
							<th><span>*</span>Bus: </th>
							<td colspan="3" >
							<select name="cmb_bus" class="combo" tabindex="1" onkeypress="return handleEnter(this, event)" title="Buses." style="font-size:13px; width:200px; font-weight:600;">
							<?php							
							$db_transporte->query("SELECT id_bus,flota,marca,placa_rodaje FROM bus ORDER BY flota DESC");								
							$Bus_Array = $db_transporte->get();																																																		
								echo '<option value="'.$Mostrar_Array[0][6].'" selected="selected">'.$Mostrar_Array[0][7].'-----'.$Mostrar_Array[0][8].'</option>';
								for ($fila = 0; $fila < count($Bus_Array); $fila++)
								{										
									if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] ==$Bus_Array[$fila][0])
										echo '<option value="'.$Bus_Array[$fila][0].'"> '.$Bus_Array[$fila][1].'-----'.$Bus_Array[$fila][3].' </option>';
									else
										echo '<option value="'.$Bus_Array[$fila][0].'"> '.$Bus_Array[$fila][1].'-----'.$Bus_Array[$fila][3].' </option>';																													
								}									
							 ?>
							 
							 </select>																								
							</td>
							
							</tr>
						  <tr>
							<th colspan="5" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos</th>
						  </tr>
						  <tr style="height:20px; font-size:80%;">
							<th>Usuario:</th>
							<td><span>
							<?PHP
								/* MOSTRAMOS EL NOMBRE DEL USURIO QUE REALIZA LA OPERACION */
								echo strtoupper($_SESSION['USUARIO']);
							?>				
								</span>				</td>
							<th>Agencia : </th>
							<td><span>
							<?PHP
								/* MOSTRAMOS EL NOMBRE DE LA AGENCIA DONDE SE REALIZA LA OPERACION */
								echo strtoupper($_SESSION['OFICINA']);
							?>				
								</span>				</td>
						  </tr>
						  <tr>
							<th colspan="5" style="height:5px;"><input type="hidden" value="<?php echo $Mostrar_Array[0][9]?>" readonly name="txt_codigo" class="" style="width:150px; text-align:center;" onkeypress="return handleEnter(this, event)" ></th>
						  </tr>
						  <tr>
							<th colspan="2" style="text-align:right;" id="132">
								<span><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Modificar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.salida_form.submit();" /></span>				</th>
							<td colspan="2" style="text-align:left; padding-left:40px;">								
								<span><input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar" onclick="location.href='p_salida.php'" /></span>
							</td>								
						  </tr>
						</table>													
					</form>					
					<?php	
					////FIN DE SALIDA.PHP?UPDATE									
						}											
						//COMIENZO DE SALIDA.PHP SIN MODICICAR
						else {
					?>			
					<form name="oficina_form" method='post' id="buscar_form" action='p_salida.php?buscar'>
						<table>
							<tr>
								<td colspan="2" style="text-align:right;">									
									<!--<select name="cmb_agencia_origen" class="combo" tabindex="1" onkeypress="return handleEnter(this, event)" title="Ruta de Destino." style="font-size:13px; font-weight:600;" onchange="Get_Oficinas_Numeracion_Derivado('E_DERIVADO');">-->
									<!--BUSCARA POR TODAS LAS AGENCIAS QUE ESTAN EN LA EMPRESA-->
									<select name="cmb_buscar_agencia" id="cmb_buscar_agencia" class="combo" title="Buscar por agencia." tabindex="1" onkeypress="return handleEnter(this, event)" style="font-size:13px; font-weight:600;" >
									  <?php
												if (count($Oficina_Array) == 0)
												{
													echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
												}
												else
												{
													echo '<option value="" selected="selected">[ Seleccione su Oficina ]</option>';
													for ($fila = 0; $fila < count($Oficina_Array); $fila++)
													{
														if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] == $Oficina_Array[$fila][0])
															echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
														else
															echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
													}
												}
										?>										
									</select>				
										<!--FIN DE COMBO BOX -->
									<input id='buscar' type='text' name='buscar' value="" title="Buscar por fecha." tabindex="7" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
									<input type="button" value="Cal" class="button" onClick="displayCalendar(document.forms[0].buscar,'yyyy-mm-dd',this)" style="width:54px;" onkeypress="return handleEnter(this, event)" >
									<input name="btn_Buscard" id="btn_Buscard" type="submit" class="button" value="Buscar" tabindex="8" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.oficina_form.submit();" />
								</td>								
							 </tr>
						</table>
					</form>
					<form name="salida_form" method="post" id="salida_form" action="p_salida_action.php?insert">
						<table border="0">
							  <tr>
								<th><span>*</span>Oficina: </th>
									<td>
									<!--<select name="cmb_agencia_origen" class="combo" tabindex="1" onkeypress="return handleEnter(this, event)" title="Ruta de Destino." style="font-size:13px; font-weight:600;" onchange="Get_Oficinas_Numeracion_Derivado('E_DERIVADO');">-->
									<select name="cmb_agencia_origen" id="cmb_agencia_origen" class="combo" title="Agencia de origen." onchange="Get_Oficinas_Numeracion_Derivado('E_DERIVADO');" tabindex="1" onkeypress="return handleEnter(this, event)" style="font-size:13px; font-weight:600;" >
									  <?php
												if (count($Oficina_Array) == 0)
												{
													echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
												}
												else
												{
													echo '<option value="" selected="selected">[ Seleccione su Origen ]</option>';
													for ($fila = 0; $fila < count($Oficina_Array); $fila++)
													{
														if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] == $Oficina_Array[$fila][0])
															echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
														else
															echo '<option value="'.$Oficina_Array[$fila][0].'" disabled="disabled"> '.$Oficina_Array[$fila][1].' </option>';
													}
												}
										?>										
									</select>				
									</td>
								<th><span>*</span>Fecha : 
								<td>
									<input type="text" value="<?php echo date("Y-m-j"); ?>" readonly name="txt_fecha" class="" style="width:150px; text-align:center;" onkeypress="return handleEnter(this, event)" >
								</td>
							  </tr>
						</table>
						<div id="div_fila_usuario">
							<table border="0">
							  <tr>
								<th style="width:90px;"><span>*</span><strong>Destino :</strong></th>
								<td colspan="3" style="width:110px;">
									<select name="cmb_destino" id="cmb_destino" class="combo" tabindex="2" onkeypress="return handleEnter(this, event)" title="Destino a dirigirse." style="width:350px;font-size:13px; font-weight:600;" onchange="Get_Datos(event, this, 2);">
									  <option value="" selected="selected">[ No hay Destinos ]</option>
									</select>
								</td>
							  </tr>
							  <tr id="DivDocumentoSN">
								<th><span>*</span>Destino : </th>
								<td id="dato2"><input id='txt_destino'  type='text' name='txt_destino' value="" title="Destino." tabindex="3" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>
								<th><span>*</span>Hora : </th>
								<td><input type ="text" id="dato2" value="<?php echo date(" h:i:s A "); ?>" name ="txt_hora" onkeypress="return validar(event)" title="Hora de salida." tabindex="4" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>
								<!--<td id="dato2"><input id='txt_hora'  type='text' name='txt_hora' value="" title="Hora de salida." tabindex="4" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>	-->
							  </tr>							   
							</table>
						</div>
						<table>
						  <tr>
							<th><span>*</span>Bus : </th>
							<td colspan="3" >
							<select name="cmb_bus" class="combo" tabindex="1" title="Buses." style="font-size:13px; width:200px; font-weight:600;">
							<?php							
							$db_transporte->query("SELECT id_bus,flota,marca,placa_rodaje FROM bus ORDER BY flota ASC");								
							$Bus_Array = $db_transporte->get();																									
								if (count($Bus_Array) == 0)
								{
									echo '<option value="">[ NO HAY BUSES...! ]</option>';
								}
								else
								{
									echo '<option value="" selected="selected">[ Seleccione su Bus ]</option>';
									for ($fila = 0; $fila < count($Bus_Array); $fila++)
									{		
										if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] ==$Bus_Array[$fila][0])
											echo '<option value="'.$Bus_Array[$fila][0].'"> '.$Bus_Array[$fila][1].'-----'.$Bus_Array[$fila][3].' </option>';
										else
											echo '<option value="'.$Bus_Array[$fila][0].'"> '.$Bus_Array[$fila][1].'-----'.$Bus_Array[$fila][3].' </option>';																			
										//echo '<option value="'.$Bus_Array[$fila][0].'" selected="selected"> '.$Bus_Array[$fila][1].'------'.$Bus_Array[$fila][3].' </option>';
									}									
								}																												
							 ?>
							 </select>																								
							</td>
							
							</tr>
						  <tr>
							<th colspan="5" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos</th>
						  </tr>
						  <tr style="height:20px; font-size:80%;">
							<th>Usuario:</th>
							<td><span>
							<?PHP
								/* MOSTRAMOS EL NOMBRE DEL USURIO QUE REALIZA LA OPERACION */
								echo strtoupper($_SESSION['USUARIO']);
							?>				
								</span>				</td>
							<th>Agencia : </th>
							<td><span>
							<?PHP
								/* MOSTRAMOS EL NOMBRE DE LA AGENCIA DONDE SE REALIZA LA OPERACION */
								echo strtoupper($_SESSION['OFICINA']);
							?>				
								</span>				</td>
						  </tr>
						  <tr>
							<th colspan="5" style="height:5px;">&nbsp;</th>
						  </tr>
						  <tr>
							<th colspan="2" style="text-align:right;" id="132">
								<span><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.salida_form.submit();" /></span></th>
							<td colspan="2" style="text-align:left; padding-left:40px;">
								<span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" /></span></td>
						  </tr>
						</table>													
					</form>
					<!--Fin de condicion de formulario-->
						<?php
							}
						?>
				</div>
			</div>		
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Contenido de las consultas-->
	<div class="column1-unit">		
		
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Trans_Array) > 0)
			{
				echo '<h1>Registro de Salidas</h1>';
				echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; 
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th title="Fecha">Fecha</th>';
						echo '<th title="Hora de salida">Hora</th>';
						echo '<th title="Oficina de salida">Origen</th>';						
						echo '<th title="Destino">Destino</th>';											
						echo '<th title="Flota de Bus">Flota</th>';						
						echo '<th title="Marca de Bus">Marca</th>';	
						echo '<th title="Modificar valores">Edit.</th>';
						echo '<th title="Eliminar Valores">Delete.</th>';						
					echo '</tr>';
		
				for ($fila = 0; $fila < count($Trans_Array); $fila++)
				{					
					$fecha= utf8_encode($Trans_Array[$fila][0]);
					$destino = utf8_encode($Trans_Array[$fila][1]);
					$hora =$Trans_Array[$fila][2];
					$oficina = $Trans_Array[$fila][3];					
					$flota = $Trans_Array[$fila][4];
					$marca = $Trans_Array[$fila][5];
					$id_salida=$Trans_Array[$fila][6];
					echo "<tr>";
						echo "<td>$fecha</td>";
						echo "<td>$hora</td>";
						echo "<td>$oficina</td>";
						echo "<td>$destino</td>";											
						echo "<td>$flota</td>";
						echo "<td>$marca</td>";
						$db_transporte->query("SELECT*FROM record_cliente WHERE id_salida='$id_salida'");
						$Consulta_Array= $db_transporte->get();
						if(count($Consulta_Array)==0){
								echo '<td style="text-align:center;"><a href="p_salida.php?update='.$id_salida.'" ><img src="./images/Symbol-Update.png" width="24" height="24" title="Modificar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
						}else 
								echo '<td style="text-align:center;"><img src="./img/operacion/Symbol-Update.png" width="24" height="24" title="Ya no se puede Modificar." /></td>';
						echo '<td style="text-align:center;"><a href="p_salida_action.php?delete='.$id_salida.'" onclick="return confirmDelete(this);"><img src="./img/operacion/Symbol-Delete.png" width="24" height="24" title="Eliminar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
					echo "</tr>";
				}
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'p_salida.php?';//curPageURL();						
						$back = "&laquo;Atras";
						$next = "Siguiente&raquo;";
						echo '<th colspan="8" style="text-align:center;">';
						$paginacion->generaPaginacion($totalRows, $back, $next, $url);
						echo '</th>';
					echo '</tr>';
					echo '</div>';
				
				echo '</table>';
			}
			else{
				echo '<h1>No existen salidas registradas</h1>';
				echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; 
			}
		?>
	</div>
	
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
