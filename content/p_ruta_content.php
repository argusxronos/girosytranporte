<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// VERIFICAMOS SI ESTA LOGEADO
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged_niv2.php");
	require_once("is_logged.php");
	// CREAMOS LA CONSULTA DE BUSQUEDA
	if(isset($_POST['buscar']))
	{
		$buscar=$_POST[buscar];
		$sql = "SELECT oficinas.`oficina`,ruta.`destino`,ruta.`hora`,ruta.`nro_certificacion`,ruta.`id_ruta`
			FROM ruta INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
			WHERE oficinas.`oficina` LIKE '%$buscar%'";
		$sql_rows = "SELECT COUNT(oficinas.`idoficina`) AS TOTAL 
			FROM ruta INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
			WHERE oficinas.`oficina` LIKE '%$buscar%'";
	}
	else {
		$sql = "SELECT oficinas.`oficina`,ruta.`destino`,ruta.`hora`,ruta.`nro_certificacion`,ruta.`id_ruta`
		FROM ruta INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`";
		$sql_rows = "SELECT COUNT(oficinas.`idoficina`) AS TOTAL FROM ruta 
			INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`";
	}	
	
	
			
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 15;
	
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	$sql = $sql ." ORDER BY oficina DESC LIMIT $from, $cantidad;";
	
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
	<h1 class="pagetitle">Nueva Ruta</h1>
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
	
	  <h1>Ingrese Datos de Nueva Ruta - <span>RECUERDE INGRESAR BIEN LOS DATOS</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			<!--<legend>Nuevo Registro</legend>-->
			<div class='column1-unit'>
				<div class='contactform'>
					<?php
						if(isset($_GET['update'])){
							$valor=$_GET[update];
							$Datos_Rutas="SELECT ruta.`id_ruta`,oficinas.`idoficina`,oficinas.`oficina`,ruta.`destino`,
							ruta.`hora`,ruta.`nro_certificacion`,ruta.`obs` FROM ruta 
							INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
							WHERE ruta.`id_ruta`='$valor'";
							$db_transporte->query($Datos_Rutas);
							$Datos_Array = $db_transporte->get();	
					// INICIO DE UPDATE P_RUTA.PHP										
					?>
					<form name="ruta_form" method='post' id="ruta_form" action='p_ruta_action.php?update'>
					<!--Para codigo-->
					<!--<input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ($_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />-->
						<table border="0">
							  <tr>
								<th><span>*</span>Oficina: </th>
									<td>
									<select name="cmb_agencia_origen" class="combo" tabindex="1" onkeypress="return handleEnter(this, event)" title="Ruta de Destino." style="font-size:13px; font-weight:600;">
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
								<th><span>*</span>Destino : 
								<td>
									<input id='destino'  type='text' name='destino' value="<?php echo $Datos_Array[0][3];?>" title="Tipo de Docuemento." tabindex="2" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>
							  </tr>
							  <tr>
								<th><span>*</span>Hora : </th>
								<td>
									<input type="text" value="<?php echo $Datos_Array[0][4];?>" name="txt_hora" class="" style="width:100px; text-align:center;" onkeypress="return handleEnter(this, event)" tabindex="3">
								</td>
								<th><span>*</span>Número de Certificado : </th>
								<td>									
									<input name="n_certificado" type="text" maxlength="15" id="n_certificado" value="<?php echo $Datos_Array[0][5];?>" tabindex="4" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Inicio de asientos piso 1." style="width:200px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" />
								</td>											  							
							  </tr>							  
							  <tr>
								<th><span>*</span>Observaciones: </th>
								<td colspan="3">
									<textarea name="observacion" id="observacion" tabindex="5" title="Observaciones de cliente." style="width:450px; height:60px;font-weight:bold;text-transform:lowercase;"><?php echo $Datos_Array[0][6];?></textarea>
								</td>							  							
							  </tr>
							  							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos <input type="hidden" value="<?php echo $Datos_Array[0][0]?>" readonly name="txt_codigo" class="" style="width:150px; text-align:center;" onkeypress="return handleEnter(this, event)" ></th>
							  </tr>
							  <tr>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Modificar" tabindex="6" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.ruta_form.submit();" /></td>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar"  tabindex="7" onclick="location.href='p_ruta.php'" /></td>
							  </tr>
						</table>						
					</form>
					<?php 
					//FIN DE UPDATE P_RUTA.PHP
						}
						else
						{
					// COMIENZO DE FORMULARIO SIN UPDATE
					?>
					<form name="searchRuta_form" method='post' id="buscar_form" action='p_ruta.php?buscar'>
						<table>
							<tr>																					
								<td colspan="4" style="text-align:right;">									
									<input id='buscar' type='text' name='buscar' value="" title="Buscar por Oficinas." tabindex="7" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
									<input name="btn_Buscard" id="btn_Buscard" type="submit" class="button" value="Buscar" tabindex="8" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.searchRuta_form.submit();" />
								</td>								
							 </tr>
						</table>
					</form>
					<form name="addRuta_form" method='post' id="addRuta_form" action='p_ruta_action.php?insert'>
					<!--Para codigo-->
					<!--<input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ($_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />-->
						<table border="0">
							  <tr>
								<th><span>*</span>Oficina: </th>
									<td>
									<select name="cmb_agencia_origen" class="combo" tabindex="1" onkeypress="return handleEnter(this, event)" title="Ruta de Destino." style="font-size:13px; font-weight:600;">
									  <?php
											//Mostrar solo la oficina de origen
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
															echo '<option value="'.$Oficina_Array[$fila][0].'" selected="selected"> '.$Oficina_Array[$fila][1].' </option>';
														else
															echo '<option value="'.$Oficina_Array[$fila][0].'" disabled="disabled"> '.$Oficina_Array[$fila][1].' </option>';
													}
												}
												/* Mostrar Todas las oficinas
												if (count($Oficina_Array) == 0)
												{
													echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
												}
												else
												{
													echo '<option value="" selected="selected">[ Seleccione su Destino ]</option>';
													for ($fila = 0; $fila < count($Oficina_Array); $fila++)
													{
														if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] == $Oficina_Array[$fila][0])
															echo '<option value="'.$Oficina_Array[$fila][0].'"<option> '.$Oficina_Array[$fila][1].' </option>';
														else
															echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
													}
												}*/
											 ?>
									</select>				
									</td>
								<th><span>*</span>Destino : 
								<td>
									<input id='destino'  type='text' name='destino' value="" title="Tipo de Docuemento." tabindex="2" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>
							  </tr>
							  <tr>
								<th><span>*</span>Hora : </th>
								<td>
									<input type="text" value="<?php echo date(" h:i:s A "); ?>" readonly name="txt_hora" class="" style="width:100px; text-align:center;" onkeypress="return handleEnter(this, event)" >
								</td>
								<th><span>*</span>Número de Certificado : </th>
								<td>									
									<input name="n_certificado" type="text" maxlength="15" id="n_certificado" tabindex="3" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Inicio de asientos piso 1." style="width:200px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" />
								</td>											  							
							  </tr>							  
							  <tr>
								<th><span>*</span>Observaciones: </th>
								<td colspan="3">
									<textarea name="observacion" id="observacion" tabindex="4" title="Observaciones de cliente." style="width:450px; height:60px;font-weight:bold;text-transform:lowercase;"></textarea>
								</td>							  							
							  </tr>
							  							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
							  </tr>
							  <tr>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" tabindex="5" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.addRuta_form.submit();" /></td>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="6" /></td>
							  </tr>
						</table>						
					</form>
					<?php
					// FIN DE FORMULARIO SIN UPDATE 
						}
					?>
				</div>
			</div>

		
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Contenido de las consultas-->
	<div class="column1-unit">

		<h1>Registro de Rutas</h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Trans_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th title="Oficina de Origen">Oficina</th>';
						echo '<th title="Destino de origen">Destino</th>';
						echo '<th title="Hora de ruta">Hora</th>';
						echo '<th title="Numero de certificado">N° Certificado</th>';
						echo '<th style="text-align:center;" title="Acci&oacute;n" colspan="2">Acci&oacute;n</th>';						
					echo '</tr>';
		
				for ($fila = 0; $fila < count($Trans_Array); $fila++)
				{					
					$oficina = utf8_encode($Trans_Array[$fila][0]);
					$destino = utf8_encode($Trans_Array[$fila][1]);
					$hora =$Trans_Array[$fila][2];
					$n_certificado = $Trans_Array[$fila][3];	
					$ruta_id=$Trans_Array[$fila][4];				
					echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
						echo "<td>$oficina</td>";
						echo "<td>$destino</td>";
						echo "<td>$hora</td>";
						echo "<td>$n_certificado</td>";
						echo '<td style="text-align:center;"><a href="p_ruta.php?update='.$ruta_id.'" ><img src="./images/Symbol-Update.png" width="25" height="25" title="Modificar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
						echo '<td style="text-align:center;"><a href="p_ruta_action.php?delete='.$ruta_id.'" onclick="return confirmDelete(this);"><img src="./img/operacion/Symbol-Delete.png" width="25" height="25" title="Eliminar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';										
					echo "</tr>";
				}
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'p_ruta.php?';//curPageURL();
						/*if (strlen($_GET['btn_buscar']) > 0)
							$url = $url .'&';
						else
							$url = $url .'?';*/
						$back = "&laquo;Atras";
						$next = "Siguiente&raquo;";
						echo '<th colspan="8" style="text-align:center;">';
						$paginacion->generaPaginacion($totalRows, $back, $next, $url);
						echo '</th>';
					echo '</tr>';
					echo '</div>';
				
				echo '</table>';
			}
			else
				echo '<p>No hay Rutas registradas.</p>';
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
