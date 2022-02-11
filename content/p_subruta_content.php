<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// VERIFICAMOS SI ESTA LOGEADO
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged_niv2.php");
	require_once("is_logged.php");
	// CREAMOS LA CONSULTA DE BUSQUEDA
	if(isset($_GET['buscar']))
	{
		$buscar=$_POST[buscar];
		$sql = "SELECT oficinas.`oficina`,ruta.`destino`,ruta.`hora`,sub_rutas.`localidad`,sub_rutas.`precio_p1`,sub_rutas.`precio_p2`,sub_rutas.`id_sr`
			FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
			WHERE oficinas.`oficina` LIKE '%$buscar%'";
		$sql_rows = "SELECT COUNT(oficinas.`idoficina`) AS TOTAL 
			FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
			WHERE oficinas.`oficina` LIKE'%$buscar%'";
	}
	else {
		$sql = "SELECT oficinas.`oficina`,ruta.`destino`,ruta.`hora`,sub_rutas.`localidad`,sub_rutas.`precio_p1`,sub_rutas.`precio_p2`,sub_rutas.`id_sr`
		FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`";
		$sql_rows = "SELECT COUNT(oficinas.`idoficina`) AS TOTAL 
		FROM sub_rutas INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta` INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`";
	}
	
				
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 15;
	
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	$sql = $sql ." ORDER BY oficinas.`oficina` ASC LIMIT $from, $cantidad;";
	
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
	<h1 class="pagetitle">Nueva Sub Ruta</h1>
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
	
	  <h1>Ingrese Datos de Nueva Sub Ruta - <span>RECUERDE INGRESAR BIEN LOS DATOS</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			<!--<legend>Nuevo Registro</legend>-->
			<div class='column1-unit'>
				<div class='contactform'>
					<?php
						//COMIENZO DEL UPDATE P_SUBRUTA.PHP?UPDATE
						if(isset($_GET['update'])){
							$valor=$_GET[update];
							$Datos_SubRutas="SELECT sub_rutas.`id_sr`,ruta.`id_ruta`,oficinas.`oficina`,ruta.`destino`,ruta.`hora`,sub_rutas.`localidad`,sub_rutas.`principal`,
								sub_rutas.`precio_p1`,sub_rutas.`precio_p2`,sub_rutas.`abrev` FROM sub_rutas
								INNER JOIN ruta ON sub_rutas.`id_rutahora`=ruta.`id_ruta`
								INNER JOIN oficinas ON ruta.`idoficina`=oficinas.`idoficina`
								WHERE sub_rutas.`id_sr`='$valor'";
							$db_transporte->query($Datos_SubRutas);
							$Datos_Array = $db_transporte->get();						
					?>
					<form name="ruta_form" method='post' id="ruta_form" action='p_subruta_action.php?update'>
					<!--Para codigo-->
					
						<table border="0">
							  <tr>
								<th><span>*</span>Oficina: </th>
								<td colspan="3">
								<select name="cmb_ruta" class="combo" tabindex="1" title="Oficina de Origen." style="font-size:13px; width:600px; font-weight:600;">
								<?php								
								$subrutas="SELECT DISTINCT ruta.`id_ruta`,oficinas.`oficina`,ruta.`destino`,ruta.`hora` 
										FROM oficinas INNER JOIN ruta ON ruta.`idoficina`=oficinas.`idoficina`
										ORDER BY oficinas.`oficina` DESC";		
								$db_transporte->query($subrutas);						
								$Rutas_Array = $db_transporte->get();																										
																																				
									if (count($Rutas_Array) == 0)
									{
										echo '<option value="">[ NO HAY RUTAS...! ]</option>';
									}
									else
									{
										echo '<option value="'.$Datos_Array[0][1].'">'.$Datos_Array[0][2].'-----'.$Datos_Array[0][3].'-----'.$Datos_Array[0][4].'</option>';
										for ($fila = 0; $fila < count($Rutas_Array); $fila++)										
										{										
											if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] ==$Rutas_Array[$fila][0])
												echo '<option value="'.$Rutas_Array[$fila][0].'"> '.$Rutas_Array[$fila][1].'-----'.$Rutas_Array[$fila][2].'-----'.$Rutas_Array[$fila][3].' </option>';
											else
												echo '<option value="'.$Rutas_Array[$fila][0].'"> '.$Rutas_Array[$fila][1].'-----'.$Rutas_Array[$fila][2].'-----'.$Rutas_Array[$fila][3].' </option>';
											//echo '<option value="'.$Rutas_Array[$fila][0].'" selected="selected"> '.$Rutas_Array[$fila][1].'----'.$Rutas_Array[$fila][2].'-----'.$Rutas_Array[$fila][3].' </option>';
										}									
									}																													
                                 ?>
								</select>															 								
								</td>
							</tr>
							 
							  <tr>
								<th><span>*</span>Localidad: 
								<td>
									<input id='localidad'  type='text' name='localidad' value="<?php echo $Datos_Array[0][5];?>" title="Localidad de sub ruta." tabindex="2" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>								
								<th><span>*</span>Principal: </th>
								<td>
									<input type='checkbox' name='seleccion' value='1' <?php if ($Datos_Array[0][6] == '1') {echo 'checked = "checked"';}?> tabindex="3" />
								</td>											  							
							  </tr>							  
							  <tr>
								<th><span>*</span>Precio 1 : </th>
								<td>									
									<input name="p1" type="text" id="p1" value="<?php echo $Datos_Array[0][7];?>" tabindex="4" onkeypress="return handleEnter(this,event);" ONKEYUP="extractNumber(this,2,false);" title="Inicio de asientos piso 1." style="width:200px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" />
								</td>
								<th><span>*</span>Precio 2 : </th>
								<td>									
									<input name="p2" type="text" id="p2" maxlength="3" value="<?php echo $Datos_Array[0][8];?>" tabindex="5" onkeypress="return handleEnter(this,event);" ONKEYUP="extractNumber(this,2,false);" title="Inicio de asientos piso 1." style="width:200px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" />
								</td>							  							
							  </tr>
							  							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos <input type="hidden" value="<?php echo $Datos_Array[0][0];?>" readonly name="txt_codigo" class="" style="width:150px; text-align:center;" onkeypress="return handleEnter(this, event)" ></th>
							  </tr>
							  <tr>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Modificar" tabindex="5" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.ruta_form.submit();" /></td>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar"  tabindex="6" onclick="location.href='p_subruta.php'" /></td>
							  </tr>
						</table>
						
					</form>
					<?php
						//FIN DE SUB RUTAS UPDATE
						}
						else {
							//INICIO DE P_SUBRUTAS.PHP SIN UPDATE												
					?>
				
					<form name="Subruta_form" method='post' id="buscar_form" action='p_subruta.php?buscar'>
						<table>
							<tr>																					
								<td colspan="4" style="text-align:right;">									
									<input id='buscar' type='text' name='buscar' value="" title="Buscar por Ruta." tabindex="7" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
									<input name="btn_Buscard" id="btn_Buscard" type="submit" class="button" value="Buscar" tabindex="8" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.giro_form.submit();" />
								</td>								
							 </tr>
						</table>
					</form>
					<form name="ruta_form" method='post' id="ruta_form" action='p_subruta_action.php?insert'>
					<!--Para codigo-->
					
						<table border="0">
							  <tr>
								<th><span>*</span>Oficina: </th>
								<td colspan="3">
								<select name="cmb_ruta" class="combo" tabindex="1" title="Oficina de Origen." style="font-size:13px; width:600px; font-weight:600;">
								<?php								
								$subrutas="SELECT DISTINCT ruta.`id_ruta`,oficinas.`oficina`,ruta.`destino`,ruta.`hora`,oficinas.`idoficina` 
										FROM oficinas INNER JOIN ruta ON ruta.`idoficina`=oficinas.`idoficina`
										ORDER BY oficinas.`oficina` DESC";		
								$db_transporte->query($subrutas);						
								$Rutas_Array = $db_transporte->get();																										
																																				
									if (count($Rutas_Array) == 0)
									{
										echo '<option value="">[ NO HAY RUTAS...! ]</option>';
									}
									else
									{
										echo '<option value="" selected="selected">[ Seleccione su Ruta ]</option>';
										for ($fila = 0; $fila < count($Rutas_Array); $fila++)										
										{										
											if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] ==$Rutas_Array[$fila][4])												
												echo '<option value="'.$Rutas_Array[$fila][0].'"> '.$Rutas_Array[$fila][1].'-----'.$Rutas_Array[$fila][2].'-----'.$Rutas_Array[$fila][3].' </option>';
											else												
												echo '<option value="'.$Rutas_Array[$fila][0].'" disabled=""> '.$Rutas_Array[$fila][1].'-----'.$Rutas_Array[$fila][2].'-----'.$Rutas_Array[$fila][3].' </option>';
											//echo '<option value="'.$Rutas_Array[$fila][0].'" selected="selected"> '.$Rutas_Array[$fila][1].'----'.$Rutas_Array[$fila][2].'-----'.$Rutas_Array[$fila][3].' </option>';
										}									
									}																													
                                 ?>
								</select>															 								
								</td>
							</tr>
							 
							  <tr>
								<th><span>*</span>Localidad: 
								<td>
									<input id='localidad'  type='text' name='localidad' value="" title="Localidad de sub ruta." tabindex="2" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>								
								<th><span>*</span>Principal: </th>
								<td>
									<input type="checkbox" name="seleccion" value="1" tabindex="3">
								</td>											  							
							  </tr>							  
							  <tr>
								<th><span>*</span>Precio 1 : </th>
								<td>									
									<input name="p1" type="text" id="p1" tabindex="4" onkeypress="return handleEnter(this,event);" ONKEYUP="extractNumber(this,2,false);" title="Inicio de asientos piso 1." style="width:200px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" />
								</td>
								<th><span>*</span>Precio 2 : </th>
								<td>									
									<input name="p2" type="text"  id="p2" tabindex="5" onkeypress="return handleEnter(this,event);" ONKEYUP="extractNumber(this,2,false);" title="Inicio de asientos piso 1." style="width:200px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;"/>
								</td>							  							
							  </tr>
							  							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
							  </tr>
							  <tr>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" tabindex="6" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.ruta_form.submit();" /></td>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="7" /></td>
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

		<h1>Registro de Sub Rutas</h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Trans_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th title="Oficina de Origen" style="text-align:center;">Oficinas</th>';
						echo '<th title="Destino de origen" style="text-align:center;">Destino</th>';
						echo '<th title="Hora de ruta" style="text-align:center;">Hora</th>';
						echo '<th title="localidad" style="text-align:center;">Localidad</th>';						
						echo '<th title="p1" style="text-align:center;">Precio P1</th>';						
						echo '<th title="p2" style="text-align:center;">Precio P2</th>';
						echo '<th colspan="2" title="Acci&oacute;n" style="text-align:center;">Acci&oacute;n</th>';
					echo '</tr>';
		
				for ($fila = 0; $fila < count($Trans_Array); $fila++)
				{					
					$oficina = utf8_encode($Trans_Array[$fila][0]);
					$destino = utf8_encode($Trans_Array[$fila][1]);
					$hora =$Trans_Array[$fila][2];
					$localidad = $Trans_Array[$fila][3];					
					$p1 = $Trans_Array[$fila][4];
					$p2 = $Trans_Array[$fila][5];
					//$abv = $Trans_Array[$fila][6];
					$id_sr=$Trans_Array[$fila][6];
					echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
						echo "<td>$oficina</td>";
						echo "<td>$destino</td>";
						echo "<td>$hora</td>";						
						echo "<td>$localidad</td>";
						echo "<td>$p1</td>";
						echo "<td>$p2</td>";
						echo '<td style="text-align:center;"><a href="p_subruta.php?update='.$id_sr.'" ><img src="./images/Symbol-Update.png" width="15" height="15" title="Modificar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
						echo '<td style="text-align:center;"><a href="p_subruta_action.php?delete='.$id_sr.'" onclick="return confirmDelete(this);"><img src="./img/operacion/Symbol-Delete.png" width="15" height="15" title="Eliminar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';																						
					echo "</tr>";
				}
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'p_subruta.php?';//curPageURL();
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
				echo '<p>No hay Sub Rutas Registradas.</p>';
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
