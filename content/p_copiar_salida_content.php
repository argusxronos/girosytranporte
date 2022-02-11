<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	$ofici_nombre=$_SESSION['OFICINA'];
	//echo $ofici_id;
	// CREAMOS LA CONSULTA DE BUSQUEDA
	if(isset($_GET['buscar']))
	{
		$buscar=$_POST[buscar];
		$agencia=$_POST[cmb_agencia];		
		$sql = "SELECT salida.`fecha`,ruta.`destino`,salida.`hora`,oficinas.`oficina`,bus.`flota`,bus.`marca`,id_salida,oficinas.`idoficina`
		FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
		INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
		INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
		WHERE salida.`fecha`='$buscar' and oficinas.`idoficina`='$agencia'";
		$sql_rows = "SELECT COUNT(id_salida) AS TOTAL
		FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
		INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
		INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
		WHERE salida.`fecha`='$buscar' and oficinas.`idoficina`='$agencia'";
	}		
	else {
		$sql = "SELECT salida.`fecha`,ruta.`destino`,salida.`hora`,oficinas.`oficina`,bus.`flota`,bus.`marca`,id_salida
		FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
		INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
		INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
		WHERE fecha=CURDATE() AND oficinas.`oficina`='$ofici_nombre'";
		$sql_rows = "SELECT COUNT(id_salida) AS TOTAL
		FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
		INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
		INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
		WHERE fecha=CURDATE() AND oficinas.`oficina`='$ofici_nombre'";
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
	<h1 class="pagetitle">Crear Salidas Automaticas</h1>
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
	  <h1>Buscar Salidas- <span>INGRESE CORRECTAMENTE LA FECHA</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			<!--<legend>Nuevo Registro</legend>-->
			<div class='column1-unit'>
				<div class='contactform'>					
					<form name="buscar_copiar_salida_form" method='post' id="buscar_copiar_salida_form" action='p_copiar_salida.php?buscar'>
						<table>
							<tr>
								<th><span>*</span>Oficina: </th>
									<td>
									<!--<select name="cmb_agencia_origen" class="combo" tabindex="1" onkeypress="return handleEnter(this, event)" title="Ruta de Destino." style="font-size:13px; font-weight:600;" onchange="Get_Oficinas_Numeracion_Derivado('E_DERIVADO');">-->
									<select name="cmb_agencia" id="cmb_agencia" class="combo" title="Agencia de origen." tabindex="1" onkeypress="return handleEnter(this, event)" style="font-size:13px; font-weight:600;" >
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
															echo '<option value="'.$Oficina_Array[$fila][0].'"<option> '.$Oficina_Array[$fila][1].' </option>';
														else
															echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
													}
												}
										?>										
									</select>									</td>
								<td colspan="2" style="text-align:right;">									
									<input id='buscar' type='text' name='buscar' value="<?php echo date("Y-m-d"); ?>" title="Buscar Salida por Fecha." tabindex="1" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
									<input type="button" value="Cal" class="button" onclick="displayCalendar(document.forms[0].buscar,'yyyy-mm-dd',this)" style="width:54px;" onkeypress="return handleEnter(this, event)" />
								  <input name="btn_Buscar" id="btn_Buscard" type="submit" class="button" value="Buscar" tabindex="8" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.buscar_copiar_salida_form.submit();" />								</td>
							</tr>							
						</table>
				  </form>
					<form name="copiar_salida_form" method="post" id="copiar_salida_form" action="p_copiar_salida_action.php?insert">
						<table border="0">
								<?php
									if (isset($_GET['buscar'])){
								?>
							  <tr>							  									
								<th>Fecha a Copiar:</th>
								<td>
									<input id='buscar2' type='text' name='buscar2' value="" title="Buscar Salida por Fecha." tabindex="1" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
									<input type="button" value="Cal" class="button" onClick="displayCalendar(document.forms[1].buscar2,'yyyy-mm-dd',this)" style="width:54px;" onkeypress="return handleEnter(this, event)" >									
								</td>							
							   </tr>							   							  
						</table>
						
						<table>						  						  
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
							<td colspan="2" style="text-align:center;"><input id='txt_origen' readonly type='text' name='txt_origen' value="<?php echo $Trans_Array[0][7]?>" title="Origen de Salida." tabindex="1" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>
							<td colspan="2" style="text-align:center;"><input id='txt_fecha_salida' readonly type='text' name='txt_fecha_salida' value="<?php echo $Trans_Array[0][0]?>" title="Fecha de salida." tabindex="1" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>
						  </tr>
						  <tr>
							<th colspan="4" style="text-align:center;" id="132">
							<span><input name="btn_copiar" id="btn_copiar" type="submit" class="button" value="Crear Salidas" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.copiar_salida_form.submit();" /></span></th>
							<?php
									}
							?>
						</table>													
					</form>				
				</div>
			</div>		
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Contenido de las consultas-->
	<div class="column1-unit">		
		
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
	    //if(isset($_GET['buscar'] )){
			if (count ($Trans_Array) > 0)
			{
				echo '<h1>Registro de Salidas</h1>';
				echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>';
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th title="Fecha">Fecha</th>';
						echo '<th title="Hora de salida">Hora</th>';
						//echo '<th title="Oficina de salida">Origen</th>';						
						echo '<th title="Destino">Destino</th>';											
						echo '<th title="Flota de Bus">Flota</th>';						
						echo '<th title="Marca de Bus">Marca</th>';	
						//echo '<th title="Modificar valores">Edit.</th>';
						//echo '<th title="Eliminar Valores">Delete.</th>';						
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
					echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
						echo "<td>$fecha</td>";
						echo "<td>$hora</td>";
						//echo "<td>$oficina</td>";
						echo "<td>$destino</td>";											
						echo "<td>$flota</td>";
						echo "<td>$marca</td>";
						//echo '<td style="text-align:center;"><a href="p_salida.php?update='.$id_salida.'" ><img src="./img/operacion/Symbol-Update.png" width="24" height="24" title="Modificar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
						//echo '<td style="text-align:center;"><a href="p_salida_action.php?delete='.$id_salida.'" onclick="return confirmDelete(this);"><img src="./img/operacion/Symbol-Delete.png" width="24" height="24" title="Eliminar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
					echo "</tr>";
				}
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'p_copiar_salida.php?';//curPageURL();						
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
			
		//}	
		
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
