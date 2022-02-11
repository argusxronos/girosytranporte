<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// CREAMOS LA CONSULTA DE BUSQUEDA
	if(isset($_GET['buscar']))
	{
		$buscar=$_POST[buscar];
		$sql = "SELECT numeracion_documento.`id`, numeracion_documento.`serie`,numeracion_documento.`numero_actual` AS NroDocumento,lista_documentos.`documento`,
		oficinas.`oficina` AS PC, lista_documentos.`detalle`,oficinas.`oficina`,numeracion_documento.`id`
		FROM oficinas INNER JOIN numeracion_documento ON oficinas.`idoficina`=numeracion_documento.`idoficina`
		INNER JOIN lista_documentos ON numeracion_documento.`id_documento`=lista_documentos.`id_lista_documento`
		WHERE numeracion_documento.`serie`='$buscar'";
		$sql_rows = "SELECT COUNT(id) AS TOTAL
		FROM oficinas INNER JOIN numeracion_documento ON oficinas.`idoficina`=numeracion_documento.`idoficina`
		INNER JOIN lista_documentos ON numeracion_documento.`id_documento`=lista_documentos.`id_lista_documento`
		WHERE numeracion_documento.`serie`='$buscar'";
	}
	else {
		$sql = "SELECT numeracion_documento.`id`, numeracion_documento.`serie`,numeracion_documento.`numero_actual` AS NroDocumento,lista_documentos.`documento`,
		oficinas.`oficina` AS PC, lista_documentos.`detalle`,oficinas.`oficina`,numeracion_documento.`id`
		FROM oficinas INNER JOIN numeracion_documento ON oficinas.`idoficina`=numeracion_documento.`idoficina`
		INNER JOIN lista_documentos ON numeracion_documento.`id_documento`=lista_documentos.`id_lista_documento`";
		$sql_rows = "SELECT COUNT(id) AS TOTAL
		FROM oficinas INNER JOIN numeracion_documento ON oficinas.`idoficina`=numeracion_documento.`idoficina`
		INNER JOIN lista_documentos ON numeracion_documento.`id_documento`=lista_documentos.`id_lista_documento`";
	}	
	
	
			
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 15;
	
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	$sql = $sql ." ORDER BY numeracion_documento.`serie` ASC LIMIT $from, $cantidad;";
	
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
	<h1 class="pagetitle">Numeraciones</h1>
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
	
	  <h1>Ingrese Datos de la Numeración- <span>RECUERDE INGRESAR BIEN LOS DATOS</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			<!--<legend>Nuevo Registro</legend>-->
			<div class='column1-unit'>
				<div class='contactform'>
					<?php
						//COMIENZO DE UPDATE NUMERACIONES
						if(isset($_GET['update']))
						{
							$valor=$_GET[update];
							$Datos_Numeracion="SELECT numeracion_documento.`id`,oficinas.`idoficina`,oficinas.`oficina`,
								lista_documentos.`id_lista_documento`,lista_documentos.`documento`,
								numeracion_documento.`serie`,numeracion_documento.`numero_actual` AS NroDocumento,oficinas.`oficina` AS PC, 
								lista_documentos.`detalle`,numeracion_documento.`tipo_operacion`
								FROM oficinas INNER JOIN numeracion_documento ON oficinas.`idoficina`=numeracion_documento.`idoficina`
								INNER JOIN lista_documentos ON numeracion_documento.`id_documento`=lista_documentos.`id_lista_documento`
								WHERE numeracion_documento.`id`='$valor'";
							$db_transporte->query($Datos_Numeracion);
							$Datos_Array = $db_transporte->get();
					?>
					<form name="numeracion_form" method='post' id="numeracion_form" action='p_numeracion_action.php?update'>
					<!--Para codigo-->
					<!--<input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ($_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />-->
						<table border="0">
								<tr>
									<th><span>*</span>Serie : </th>
									<td>									
										<input name="txt_serie" maxlength="4" value="<?php echo $Datos_Array[0][5];?>" type="text" id="txt_serie" tabindex="1" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Serie de documento." style="width:200px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" />
									</td>
									<th><span>*</span>Número Actual : </th>
									<td>									
										<input name="txt_nactual" maxlength="4" value="<?php echo $Datos_Array[0][6];?>"type="text" id="txt_nactual" tabindex="2" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Número actual de documento." style="width:200px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" />
									</td>
								</tr>
							  <tr>
								<th><span>*</span>Oficina: </th>
									<td>
									<select name="cmb_agencia_origen" class="combo" tabindex="3" onkeypress="return handleEnter(this, event)" title="Ruta de Destino." style="font-size:13px; font-weight:600;">
									  <?php
												if (count($Oficina_Array) == 0)
												{
													echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
												}
												else
												{
													echo '<option value="'.$Datos_Array[0][1].'" selected="selected">'.$Datos_Array[0][2].'</option>';
													for ($fila = 0; $fila < count($Oficina_Array); $fila++)
													{
														if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] == $Oficina_Array[$fila][0])
															echo '<option value="'.$Oficina_Array[$fila][0].'"<option> '.$Oficina_Array[$fila][1].' </option>';
														else
															echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
													}
												}
											 ?>
									</select>				
									</td>
								<th><span>*</span>Documento: </th>
									<td>
									<select name="cmb_documento" class="combo" tabindex="4" onkeypress="return handleEnter(this, event)" title="Documento." style="font-size:13px; font-weight:600;">
									  <?php
											$documento="SELECT id_lista_documento,documento FROM lista_documentos";
											$db_transporte->query($documento);
											$Documentos_Array = $db_transporte->get();
											if (count($Documentos_Array) == 0)
											{
												echo '<option value="">[ NO HAY DOCUMENTOS...! ]</option>';
											}
											else
											{
												echo '<option value="'.$Datos_Array[0][3].'" selected="selected">'.$Datos_Array[0][4].'</option>';
												for ($fila = 0; $fila < count($Documentos_Array); $fila++)
												{
													if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] ==$Documentos_Array[$fila][0])
														echo '<option value="'.$Documentos_Array[$fila][0].'"<option> '.$Documentos_Array[$fila][1].' </option>';
													else
														echo '<option value="'.$Documentos_Array[$fila][0].'"> '.$Documentos_Array[$fila][1].' </option>';
												}
											}
											 ?>
									</select>				
									</td>
							  </tr>
							  <tr>
								<th><span>*</span>PC : </th>
								<td>									
									<input id='txt_pc' type='text' name='txt_pc' value="<?php echo $Datos_Array[0][7];?>" title="Nombre de la oficina." tabindex="5" style="width:250px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>
								<th><span>*</span>Operación : </th>
								<td>
									<select name='cmb_operacion' style="width:200px; text-align:ringht; font-size:130%;" tabindex="8">
									<?php 
									if($Datos_Array[0][9]=='0'){
										$tipo_docu='No Asignado';
									}	
									if($Datos_Array[0][9]=='1'){
										$tipo_docu='Giros';
									}
									if($Datos_Array[0][9]=='2'){
										$tipo_docu='Encomiendas';
									}
									if($Datos_Array[0][9]=='3'){
										$tipo_docu='Pasajes';
									}	
									if($Datos_Array[0][9]=='4'){
										$tipo_docu='Encomiendas y Pasajes';
									}							
									echo '<option value="'.$Datos_Array[0][9].'" selected="selected">'.$tipo_docu.'</option>';
									?>
									<option value='0'>No Asignado</option>
									<option value='1'>Giros</option>					
									<option value='2'>Encomiendas</option>					
									<option value='3'>Pasajes</option>					
									<option value='4'>Encomiendas y Pasajes</option>					
									</select>
								</td>											  							
							  </tr>							  
							  <tr>
								<th><span>*</span>Detalles: </th>
								<td colspan="3">
									<textarea name="detalle" id="detalle" tabindex="7" title="Observaciones de cliente." style="width:450px; height:60px;font-weight:bold;text-transform:lowercase;"><?php echo $Datos_Array[0][8];?></textarea>
								</td>															  							
							  </tr>
							  							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos <input type="hidden" value="<?php echo $Datos_Array[0][0];?>" readonly name="txt_codigo" class="" style="width:150px; text-align:center;" onkeypress="return handleEnter(this, event)" ></th>
							  </tr>
							  <tr>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Modificar" tabindex="5" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.numeracion_form.submit();" /></td>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar"  tabindex="6" onclick="location.href='p_numeracion.php'" /></td>
							  </tr>
						</table>						
					</form>
					<?php
						//FIN DE NUMERACION UPDATE
						}
						else {
							//INICIO DE P_NUMERACION.PHP SIN UPDATE												
					?>
					<form name="formSearchNumeracion" method='post' id="buscar_form" action='p_numeracion.php?buscar'>
						<table>
							<tr>																					
								<td colspan="4" style="text-align:right;">									
									<input id='buscar' type='text' name='buscar' value="" title="Buscar por Número de Serie." tabindex="7" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
									<input name="btn_Buscard" id="btn_Buscard" type="submit" class="button" value="Buscar" tabindex="8" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.formSearchNumeracion.submit();" />
								</td>								
							 </tr>
						</table>
					</form>
					<form name="formAddNumeracion" method='post' id="ruta_form" action='p_numeracion_action.php?insert'>
					<!--Para codigo-->
					<!--<input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ($_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />-->
						<table border="0">
								<tr>
									<th><span>*</span>Serie : </th>
									<td>									
										<input name="txt_serie" type="text" maxlength="4" id="txt_serie" tabindex="1" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Serie de documento." style="width:200px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" />
									</td>
									<th><span>*</span>Número Actual : </th>
									<td>									
										<input name="txt_nactual" maxlength="4" type="text" id="txt_nactual" tabindex="2" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Número actual de documento." style="width:200px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" />
									</td>
								</tr>
							  <tr>
								<th><span>*</span>Oficina: </th>
									<td>
									<select name="cmb_agencia_origen" class="combo" tabindex="3" onkeypress="return handleEnter(this, event)" title="Ruta de Destino." style="font-size:13px; font-weight:600;">
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
															echo '<option value="'.$Oficina_Array[$fila][0].'" selected="selected"> '.$Oficina_Array[$fila][1].' </option>';
														else
															echo '<option value="'.$Oficina_Array[$fila][0].'" disabled="disabled"> '.$Oficina_Array[$fila][1].' </option>';
													}
												}
											 ?>
									</select>				
									</td>
								<th><span>*</span>Documento: </th>
									<td>
									<select name="cmb_documento" class="combo" tabindex="4" onkeypress="return handleEnter(this, event)" title="Documento." style="font-size:13px; font-weight:600;">
									  <?php
											$documento="SELECT id_lista_documento,documento FROM lista_documentos";
											$db_transporte->query($documento);
											$Documentos_Array = $db_transporte->get();
											if (count($Documentos_Array) == 0)
											{
												echo '<option value="">[ NO HAY DOCUMENTOS...! ]</option>';
											}
											else
											{
												echo '<option value="" selected="selected">[ Seleccione Documento ]</option>';
												for ($fila = 0; $fila < count($Documentos_Array); $fila++)
												{
													if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] ==$Documentos_Array[$fila][0])
														echo '<option value="'.$Documentos_Array[$fila][0].'"<option> '.$Documentos_Array[$fila][1].' </option>';
													else
														echo '<option value="'.$Documentos_Array[$fila][0].'"> '.$Documentos_Array[$fila][1].' </option>';
												}
											}
											 ?>
									</select>				
									</td>
							  </tr>
							  <tr>
								<th><span>*</span>PC : </th>
								<td>									
									<input id='txt_pc' type='text' name='txt_pc' value="" title="Nombre de la oficina." tabindex="5" style="width:250px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>
								<th><span>*</span>Operación : </th>
								<td><select name='cmb_operacion' style="width:200px; text-align:ringht; font-size:130%;" tabindex="8">
									<option value='0'>No Asignado</option>
									<option value='1'>Giros</option>					
									<option value='2'>Encomiendas</option>					
									<option value='3'>Pasajes</option>					
									<option value='4'>Encomiendas y Pasajes</option>					
									</select>
								</td>											  							
							  </tr>							  
							  <tr>
								<th><span>*</span>Detalles: </th>
								<td colspan="3">
									<textarea name="detalle" id="detalle" tabindex="7" title="Observaciones de cliente." style="width:450px; height:60px;font-weight:bold;text-transform:lowercase;"></textarea>
								</td>															  							
							  </tr>
							  							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
							  </tr>
							  <tr>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" tabindex="5" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.formAddNumeracion.submit();" /></td>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="6" /></td>
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

		<h1>Registro de las Numeraciones</h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Trans_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th title="ID Documento" style="text-align:center;">ID</th>';
						echo '<th title="Serie Documento" style="text-align:center;">Serie</th>';
						echo '<th title="Numero de Documento" style="text-align:center;">Nro. Documento</th>';
						echo '<th title="Documento" style="text-align:center;">Documento</th>';
						echo '<th title="PC" style="text-align:center;">PC</th>';
						echo '<th title="Detalle del Documento" style="text-align:center;">Detalles</th>';						
						echo '<th title="Oficina" style="text-align:center;">Oficina</th>';
						echo '<th style="text-align:center;" colspan="2" title="Acci&oacute;n">Acci&oacute;n</th>';
					echo '</tr>';
		
				for ($fila = 0; $fila < count($Trans_Array); $fila++)
				{					
					$id = $Trans_Array[$fila][0];
					$serie = utf8_encode($Trans_Array[$fila][1]);
					$n_documento = utf8_encode($Trans_Array[$fila][2]);
					$documento =$Trans_Array[$fila][3];
					$pc = $Trans_Array[$fila][4];
					$detalle = $Trans_Array[$fila][5];					
					$oficina = $Trans_Array[$fila][6];
					$id_numeracion=$Trans_Array[$fila][7];
					echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
						echo "<td>$id</td>";
						echo "<td>$serie</td>";
						echo "<td>$n_documento</td>";
						echo "<td>$documento</td>";
						echo "<td>$pc</td>";
						echo "<td>$detalle</td>";
						echo "<td>$oficina</td>";
						echo '<td style="text-align:center;width:20px;"><a href="p_numeracion.php?update='.$id_numeracion.'" ><img src="./images/Symbol-Update.png" width="15" height="10" title="Modificar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
						echo '<td style="text-align:center;width:20px;"><a href="p_numeracion_action.php?delete='.$id_numeracion.'" onclick="return confirmDelete(this);"><img src="./img/operacion/Symbol-Delete.png" width="15" height="10" title="Eliminar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';										
					echo "</tr>";
				}
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'p_numeracion.php?';//curPageURL();
						/*if (strlen($_GET['btn_buscar']) > 0)
							$url = $url .'&';
						else
							$url = $url .'?';*/
						$back = "&laquo;Atras";
						$next = "Siguiente&raquo;";
						echo '<th colspan="9" style="text-align:center;">';
						$paginacion->generaPaginacion($totalRows, $back, $next, $url);
						echo '</th>';
					echo '</tr>';
					echo '</div>';
				
				echo '</table>';
			}
			else
				echo '<p>No hay Numeraciones Registradas.</p>';
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
