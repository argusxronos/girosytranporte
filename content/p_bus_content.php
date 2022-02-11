<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// VERIFICAMOS SI ESTA LOGEADO
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged_niv2.php");
	require_once("is_logged.php");
	// CREAMOS LA CONSULTA DE BUSQUEDA
	if(isset($_GET['buscar'])){
		$buscar=$_POST[buscar];
		//echo $buscar;
		$sql = "SELECT bus.flota,bus.tarjeta_habilitacion,bus.marca,bus.carroceria,bus.placa_rodaje,
		bus.nro_pisos,bus.ca1ini,bus.ca2ini,bus.ca1fin,bus.ca2fin,bus.cantasientos,bus.propietario,bus.imagen,bus.obs,bus.`id_bus`
		FROM bus WHERE flota LIKE '%$buscar%'" ;
		$sql_rows = "SELECT COUNT(bus.id_bus) AS TOTAL FROM bus WHERE flota LIKE '%$buscar%'";		
	}
	else{
		$sql = "SELECT bus.flota,bus.tarjeta_habilitacion,bus.marca,bus.carroceria,bus.placa_rodaje,
		bus.nro_pisos,bus.ca1ini,bus.ca2ini,bus.ca1fin,bus.ca2fin,bus.cantasientos,bus.propietario,bus.imagen,bus.obs,bus.`id_bus`
		FROM bus";
		$sql_rows = "SELECT COUNT(bus.id_bus) AS TOTAL FROM bus";	
	}
	
			/*	
	if (isset($_GET['btn_buscar']) && $_GET['btn_buscar'] != "")
	{
		if (strlen($_GET['txt_fecha'])>0)
		{
			$sql = $sql ." AND `g_movimiento`.`fecha_emision` = '".$_GET['txt_fecha']."'";
			$sql_rows = $sql_rows ." AND `g_movimiento`.`fecha_emision` = '".$_GET['txt_fecha']."'";
		}
		if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0)
		{
			$sql = $sql ." AND `CONSIGNATARIO`.`per_ape_nom` LIKE '".utf8_decode(strtoupper(urldecode($_GET['txt_consignatario'])))."%'";
			$sql_rows = $sql_rows ." AND `CONSIGNATARIO`.`per_ape_nom` LIKE '".utf8_decode(strtoupper(urldecode($_GET['txt_consignatario'])))."%'";
		}
		if (isset($_GET['txt_Remitente']) && strlen($_GET['txt_Remitente']) > 0)
		{
			$sql = $sql ." AND `REMITENTE`.`per_ape_nom` LIKE '".utf8_decode(strtoupper(urldecode($_GET['txt_Remitente'])))."%'";
			$sql_rows = $sql_rows ." AND `REMITENTE`.`per_ape_nom` LIKE '".(utf8_decode(strtoupper(urldecode($_GET['txt_Remitente']))))."%'";
		}
		if (isset($_GET['txt_serie_doc']) && strlen($_GET['txt_serie_doc']) > 0)
		{
			$sql = $sql ." AND `g_movimiento`.`num_serie` = '".$_GET['txt_serie_doc']."'";
			$sql_rows = $sql_rows ." AND `g_movimiento`.`num_serie` = '".$_GET['txt_serie_doc']."'";
		}
		if (isset($_GET['txt_numero_doc']) && strlen($_GET['txt_numero_doc']) > 0)
		{
			$sql = $sql ." AND `g_movimiento`.`num_documento` = '".$_GET['txt_numero_doc']."'";
			$sql_rows = $sql_rows ." AND `g_movimiento`.`num_documento` = '".$_GET['txt_numero_doc']."'";
		}
		if (isset($_GET['cmb_agencia_origen']) && $_GET['cmb_agencia_origen'] != 0)
		{
			$sql = $sql ." AND `g_movimiento`.`id_oficina_origen` = " .$_GET['cmb_agencia_origen'];
			$sql_rows = $sql_rows ." AND `g_movimiento`.`id_oficina_origen` = " .$_GET['cmb_agencia_origen'];
		}
		if (isset($_GET['cmb_agencia_destino']) && $_GET['cmb_agencia_destino'] != 0)
		{
			$sql = $sql ." AND `g_movimiento`.`id_oficina_destino` = " .$_GET['cmb_agencia_destino'];
			$sql_rows = $sql_rows ." AND `g_movimiento`.`id_oficina_destino` = " .$_GET['cmb_agencia_destino'];
		}
	}
	* */
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 15;
	
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	$sql = $sql ." ORDER BY flota DESC LIMIT $from, $cantidad;";
	
	$sql_rows = $sql_rows .';';
	// OBTEMOS LOS DATOS DE MOVIMIENTOS
	require_once 'cnn/config_master.php';
	// REALIZAMOS LA CONSULTA A LA BD
	$db_transporte->query($sql_rows);
	$totalRows = $db_transporte->get('TOTAL');
	
	$db_transporte->query($sql);
	$Trans_Array = $db_transporte->get();
	
	function OficinaByID($id_ofic)
	{
		$Ofic_Array = $_SESSION['OFICINAS'];
		$Oficina = '';
		for ($fila = 0; $fila < count($_SESSION['OFICINAS']); $fila++)
		{
			if($_SESSION['OFICINAS'][$fila][0] == $id_ofic)
			{
				$Oficina = $_SESSION['OFICINAS'][$fila][1];
				break;
			}
		}
		return $Oficina;
	}
	
	function UserByID($id_user)
	{
		$Users_Array = $_SESSION['USERS'];
		$Usuario = '';
		for ($fila = 0; $fila < count($Users_Array); $fila++)
		{
			if($Users_Array[$fila][0] == $id_user)
			{
				$Usuario = $Users_Array[$fila][1];
				break;
			}
		}
		
		return $Usuario;
	}
	function UserNombreByID($id_user)
	{
		$Users_Array = $_SESSION['USERS'];
		$UserName = '';
		for ($fila = 0; $fila < count($Users_Array); $fila++)
		{
			if($Users_Array[$fila][0] == $id_user)
			{
				$UserName = utf8_encode($Users_Array[$fila][2]);
				break;
			}
		}
		return $UserName;
	}
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Nuevo Bus</h1>
    <?php 
	if (!isset($_GET['ID']))
	{
?>
<!-- Script para mensaje de confirmacion de eliminacion de datos -->
	<script>
    function confirmDelete(link) {
        if (confirm("¿Esta seguro de eliminar este campo?")) {
            doAjax(link.href, "POST"); // doAjax needs to send the "confirm" field
        }
        return false;
    }
	</script>
<!--fin de script-->

	<!-- Contenido del Formulario -->
	<div class="column1-unit">
	
	  <h1>Ingrese Datos Nuevo Bus - <span>RECUERDE INGRESAR PRIMERO LA FLOTA Y TARGETA DE HABILITACION</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			<!--<legend>Nuevo Registro</legend>-->
			<div class='column1-unit'>
				<div class='contactform'>
					<?php
					//COMIENZO DE UPDATE de buses
					if(isset($_GET['update'])){
						$valor=$_GET[update];
						$Datos_buses="SELECT*FROM bus WHERE id_bus='$valor'";
						$db_transporte->query($Datos_buses);
						$Datos_Array = $db_transporte->get();
					?>
					<form name="bus_form" method='post' id="bus_form" action='p_bus_action.php?update'>
						<table border="0">
							  <tr id="DivDocumentoSN">							  							  								
								<td colspan="4" style="text-align:right;">
									<input type="text" value="<?php echo $Datos_Array[0][0];?>" tabindex="1" readonly name="txt_codigo" class="" style="width:150px; text-align:center;" onkeypress="return handleEnter(this, event)" >
								</td>
							  </tr>
							  <tr>
								<th><span>*</span>Flota : </th>
								<td>									
									<input id='flota' type='text' name='flota' value="<?php echo $Datos_Array[0][1];?>" title="Numero de Flota." tabindex="2" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" />
									</td>
								<th><span>*</span>Targeta de Habilitación: 
								<td><input id='thabilitacion'  type='text' name='thabilitacion' value="<?php echo $Datos_Array[0][2];?>" title="Targeta de habiltacion." tabindex="3" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" /></td>
							  </tr>
							  <tr>
								<th><span>*</span>Marca : </th>
								<td>									
									<input id='marca' type='text' name='marca' value="<?php echo $Datos_Array[0][3];?>" title="Marca de bus." tabindex="4" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" />
									</td>
								<th><span>*</span>Carroceria : 
								<td><input id='carroceria'  type='text' name='carroceria' value="<?php echo $Datos_Array[0][4];?>" title="Tipo de Docuemento." tabindex="5" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" /></td>
							  </tr>
							  <tr>
								<th><span>*</span>Placa : </th>
								<td><input id='placa' type='text' name='placa' value="<?php echo $Datos_Array[0][5];?>" title="Placa de bus." tabindex="6" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" /></td>
								<th><span>*</span>Numero de Pisos: </th>
								<td><input name="pisos" type="text" maxlength="2" id="pisos" value="<?php echo $Datos_Array[0][6];?>" tabindex="7" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Numero de pisos." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off"; /></td>
							  </tr>
							  <tr>
								<th><span>*</span>Inicio asientos Piso 1:</th>
								<td><input name="ca1ini" type="text" maxlength="2" id="ca1ini" value="<?php echo $Datos_Array[0][7];?>" tabindex="8" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Inicio de asientos piso 1." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
								<th><span>*</span>Inicio asientos Piso 2: </th>
								<td><input name="ca2ini" type="text" maxlength="2" id="ca2ini" value="<?php echo $Datos_Array[0][8];?>" tabindex="9" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Inicio de asientos piso 2." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off"; /></td>
							  </tr>
							  <tr>
								<th><span>*</span>Final asientos Piso 1:</th>
								<td><input name="ca1fin" type="text" maxlength="2" id="ca1fin" value="<?php echo $Datos_Array[0][9];?>" tabindex="10" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Final de asientos piso 1." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
								<th><span>*</span>Final asientos Piso 2: </th>
								<td><input name="ca2fin" type="text" maxlength="2" id="ca2fin" value="<?php echo $Datos_Array[0][10];?>" tabindex="11" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Final de asientos piso 2." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off"; /></td>
							  </tr>
							  
							  <tr id="">
								<th><span>*</span>Cantidad de Asientos:</th>
								<td><input name="casientos" type="text" maxlength="2" id="casientos" value="<?php echo $Datos_Array[0][11];?>" tabindex="12" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Cantidad de Asientos." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
								<th><span>*</span>Propietario: </th>
								<td><input name="propietario" id="propietario" type="text" value="<?php echo $Datos_Array[0][12];?>" title="Propietario del Bus." tabindex="13" style="width:240px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>				
							  </tr>
							  
							  <tr>								
								<td colspan="2"><p>Imagen bus : </p><input name="foto" id="foto" type="file" value="Cal" class="button" style="width:25px;" ></td></td>
								<th><span>*</span>Observaciones: </th>
								<td><textarea name="observacion" id="observacion" tabindex="14" title="Observaciones de cliente." style="width:240px; height:50px;font-weight:bold;text-transform:lowercase;"><?php echo $Datos_Array[0][14];?></textarea></td>
							  </tr>
							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
							  </tr>							  
							  <tr>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Modificar" tabindex="15" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.bus_form.submit();" /></td>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar"  tabindex="16" onclick="location.href='p_bus.php'" /></td>
							  </tr>
						</table>						
					</form>
					<?php
						//FIN DE BUS UPDATE
						}
						else {
							//INICIO DE P_BUS.PHP SIN UPDATE	
					?>
					
					<form name="bus_search_form" method='post' id="buscar_form" action='p_bus.php?buscar'>
						<table>
							<tr>																					
								<td colspan="4" style="text-align:right;">	
									N&uacute;mero Flota: 								
									<input id='buscar' type='text' name='buscar' value="" title="Buscar por Número de Flota." tabindex="1" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeyup="extractNumber(this,0,false);">
									<input name="btn_Buscar" id="btn_Buscar" type="submit" class="button" value="Buscar" tabindex="2" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.bus_search_form.submit();" />
								</td>								
							 </tr>
						</table>
					</form>
					<form name="bus_form" method='post' id="bus_form" action='p_bus_action.php?insert'>
						<table border="0">
							  <tr>
								<th><span>*</span>Flota : </th>
								<td>									
									<input id='flota' type='text' name='flota' value="" title="Numero de Flota." tabindex="3" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" />
									</td>
								<th><span>*</span>Targeta de Habilitación: 
								<td><input id='thabilitacion'  type='text' name='thabilitacion' value="" title="Targeta de habiltacion." tabindex="4" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" /></td>
							  </tr>
							  <tr>
								<th><span>*</span>Marca : </th>
								<td>									
									<input id='marca' type='text' name='marca' value="" title="Marca de bus." tabindex="5" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" />
									</td>
								<th><span>*</span>Carroceria : 
								<td><input id='carroceria'  type='text' name='carroceria' value="" title="Tipo de Docuemento." tabindex="6" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" /></td>
							  </tr>
							  <tr>
								<th><span>*</span>Placa : </th>
								<td><input id='placa' type='text' name='placa' value="" title="Placa de bus." tabindex="7" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" /></td>
								<th><span>*</span>Numero de Pisos: </th>
								<td><input name="pisos" type="text" maxlength="2" id="pisos" tabindex="8" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Numero de pisos." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off"; /></td>
							  </tr>
							  <tr>
								<th><span>*</span>Inicio asientos Piso 1:</th>
								<td><input name="ca1ini" type="text" maxlength="2" id="ca1ini" tabindex="9" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Inicio de asientos piso 1." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
								<th><span>*</span>Inicio asientos Piso 2: </th>
								<td><input name="ca2ini" type="text" maxlength="2" id="ca2ini" tabindex="10" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Inicio de asientos piso 2." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off"; /></td>
							  </tr>
							  <tr>
								<th><span>*</span>Final asientos Piso 1:</th>
								<td><input name="ca1fin" type="text" maxlength="2" id="ca1fin" tabindex="11" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Final de asientos piso 1." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
								<th><span>*</span>Final asientos Piso 2: </th>
								<td><input name="ca2fin" type="text" maxlength="2" id="ca2fin" tabindex="12" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Final de asientos piso 2." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off"; /></td>
							  </tr>
							  
							  <tr id="">
								<th><span>*</span>Cantidad de Asientos:</th>
								<td><input name="casientos" type="text" maxlength="2" id="casientos" tabindex="13" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Cantidad de Asientos." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
								<th><span>*</span>Propietario: </th>
								<td><input name="propietario" id="propietario" type="text" value="" title="Propietario del Bus." tabindex="14" style="width:240px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>				
							  </tr>
							  
							  <tr>								
								<td colspan="2"><p>Imagen bus : </p><input name="foto" id="foto"type="file" value="Cal" class="button" style="width:300px;" ></td></td>
								<th><span>*</span>Observaciones: </th>
								<td><textarea name="observacion" id="observacion" tabindex="15" title="Observaciones de cliente." style="width:240px; height:50px;font-weight:bold;text-transform:lowercase;"></textarea></td>
							  </tr>
							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
							  </tr>							  
							  <tr>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input namer="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" tabindex="16" onclick="this.disabled = 'true'; this.value = 'Enviando...';document.bus_form.submit();" /></td>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="17" /></td>
							  </tr>
						</table>
						<!--<div>
							<ul><small>Flota: <span id='asterisco'>*</span></small><input id='campo' type='text' name='flota'></ul>
							<ol><small>Flota: <span id='asterisco'>*</span></small><input id='campo' type='text' name='flota'></ol>
							<ul><small>Targeta de Habilitación:<span id='asterisco'>*</span></small><input id='campo'  type='text' name='thabilitacion'></ul>
						</div>
						-->
					<!--
							
					
										
					Foto:<input id='campo' type='file' name='foto'><br>
					<small>Observaciónes:<span id='asterisco'>*</span></small><br><textarea id='campotextarea' name='observacion'></textarea><br>
								
					<input id='btn' type='submit' value='Ingresar' name="boton_envio">
					<input id='btn' type='reset' name='Restablecer'>
					<input id='btn' type='submit' value='Editar' action=''>
					-->
					</form>
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

		<h1>Registro de Buses</h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Trans_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th style="width:70px;" title="Flota / Flota de bus">Flota</th>';
						echo '<th title="Targeta de habilitacion">Targeta</th>';
						echo '<th>Marca</th>';
						echo '<th title="Carroceria">Carroceria</th>';
						echo '<th title="Placa Rodaje">Placa</th>';
						echo '<th title="Numero de Pisos">N° Pisos</th>';
						echo '<th title="Numero de Asientos">N° Asie.</th>';
						echo '<th style="width:20px;">Propie.</th>';
						echo '<th colspan="2" style="width:20px;text-align:center;">Acci&oacute;n</th>';
					echo '</tr>';
		
				for ($fila = 0; $fila < count($Trans_Array); $fila++)
				{					
					$flota = utf8_encode($Trans_Array[$fila][0]);
					$thabilitacion = utf8_encode($Trans_Array[$fila][1]);
					$marca =$Trans_Array[$fila][2];
					$carroceria = $Trans_Array[$fila][3];
					$placa = $Trans_Array[$fila][4];
					$npisos = ($Trans_Array[$fila][5]);
					$ca1ini = $Trans_Array[$fila][6];
					$ca2ini = $Trans_Array[$fila][7];
					$ca1fin = $Trans_Array[$fila][8];
					$ca2fin = $Trans_Array[$fila][9];
					$casientos=$Trans_Array[$fila][10];
					$propietario=$Trans_Array[$fila][11];
					$foto=$Trans_Array[$fila][12];
					$obs=$Trans_Array[$fila][13];
					$id_bus=$Trans_Array[$fila][14];
					echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
						echo "<td>$flota</td>";
						echo "<td>$thabilitacion</td>";
						echo "<td>$marca</td>";
						echo "<td>$carroceria</td>";
						echo "<td>$placa</td>";
						echo "<td>$npisos</td>";
						echo "<td>$casientos</td>";
						echo "<td>$propietario</td>";
						echo '<td style="text-align:center;"><a href="p_bus.php?update='.$id_bus.'" ><img src="./images/Symbol-Update.png" width="25" height="25" title="Modificar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
						echo '<td style="text-align:center;"><a href="p_bus_action.php?delete='.$id_bus.'" onclick="return confirmDelete(this);"><img src="./img/operacion/Symbol-Delete.png" width="25" height="25" title="Eliminar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';										
					echo "</tr>";
				}
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'p_bus.php?';//curPageURL();
						/*if (strlen($_GET['btn_buscar']) > 0)
							$url = $url .'&';
						else
							$url = $url .'?';*/
						$back = "&laquo;Atras";
						$next = "Siguiente&raquo;";
						echo '<th colspan="10" style="text-align:center;">';
						$paginacion->generaPaginacion($totalRows, $back, $next, $url);
						echo '</th>';
					echo '</tr>';
					echo '</div>';
				
				echo '</table>';
			}
			else
				echo '<p>No hay buses registrados.</p>';
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
