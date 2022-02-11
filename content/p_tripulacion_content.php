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
		$sql = "SELECT nro_licencia,apellidos_nombres,id_tripulacion FROM tripulacion
				WHERE apellidos_nombres LIKE '%$buscar%'";
		$sql_rows = "SELECT COUNT(nro_licencia) AS TOTAL FROM tripulacion
				WHERE apellidos_nombres LIKE '%$buscar%'";
	}
	else {
		$sql = "SELECT nro_licencia,apellidos_nombres,id_tripulacion FROM tripulacion";
		$sql_rows = "SELECT COUNT(nro_licencia) AS TOTAL FROM tripulacion";
	}	
	
	
	
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 20;
	
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	
	$sql = $sql ." ORDER BY apellidos_nombres ASC LIMIT $from, $cantidad;";
	
	$sql_rows = $sql_rows .';';
	// OBTEMOS LOS DATOS DE MOVIMIENTOS
	require_once 'cnn/config_master.php';
	// REALIZAMOS LA CONSULTA A LA BD
	$db_transporte->query($sql_rows);
	$totalRows = $db_transporte->get('TOTAL');
	
	$db_transporte->query($sql);
	$Trans_Array = $db_transporte->get();
	/*
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
	* */
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Nuevos Tripulantes</h1>
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
	
	  <h1>Ingrese Datos de Nuevo Tripulante - <span>RECUERDE INGRESAR BIEN LOS DATOS</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			<!--<legend>Nuevo Registro</legend>-->
			<div class='column1-unit'>
				<div class='contactform'>
					<?php
						//COMIENZO DE UPDATE TRIPULACION
						if(isset($_GET['update']))
						{
							$valor=$_GET[update];
							$Datos_Tripulantes="SELECT id_tripulacion,apellidos_nombres,nro_licencia,obs
								FROM tripulacion
								WHERE tripulacion.`id_tripulacion`='$valor'";
							$db_transporte->query($Datos_Tripulantes);
							$Datos_Array = $db_transporte->get();
							
					?>
					<form name="formUpdateTripulacion" method='post' id="oficina_form" action='p_tripulacion_action.php?update'>
					<!--Para codigo-->
					<!--<input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ($_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />-->
						<table border="0">	
							  <tr>
								<th><span>*</span>Número de Licencia : </th>
								<td colspan="3">									
									<input id='n_licencia' type='text' name='n_licencia' value="<?php echo $Datos_Array[0][2];?>" title="Número de Licencia." tabindex="1" style="width:250px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>
							  </tr>
							  <tr>
								<th><span>*</span>Apellidos y Nombres: </th>
								<td colspan="3">									
									<input id='nombres' type='text' name='nombres' value="<?php echo $Datos_Array[0][1];?>" title="Nombres y Apellidos del Tripulante." tabindex="2" style="width:450px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>								
							  </tr>
							  <tr>
								<th><span>*</span>Observaciones : </th>
								<td colspan="3">
									<textarea name="observaciones" id="observaciones" tabindex="3" title="Observaciones del Tripulante." style="width:450px; height:60px;font-weight:bold;text-transform:lowercase;"><?php echo $Datos_Array[0][3];?></textarea>
								</td>								
							  </tr>
							  							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos <input type="hidden" value="<?php echo $Datos_Array[0][0];?>" readonly name="txt_codigo" class="" style="width:150px; text-align:center;" onkeypress="return handleEnter(this, event)" ></th>
							  </tr>
							  <table>
								<tr>
									<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Modificar" tabindex="5" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.formUpdateTripulacion.submit();" /></td>
									<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar"  tabindex="6" onclick="location.href='p_tripulacion.php'" /></td>
								</tr>
							  </table>
						</table>
						
					</form>
					<?php
						//FIN DE TRIPULACION UPDATE
						}
						else {
							//INICIO DE P_TRIPULACION.PHP SIN UPDATE												
					?>
					<form name="form_searchTripulacion" method='post' id="form_searchTripulacion" action='p_tripulacion.php?buscar'>
						<table>
							<tr>																					
								<td colspan="4" style="text-align:right;">									
									<input id='buscar' type='text' name='buscar' value="" title="Buscar por Nombres o Apellidos del Tripulante." tabindex="7" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
									<input name="btn_Buscard" id="btn_Buscard" type="submit" class="button" value="Buscar" tabindex="8" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.form_searchTripulacion.submit();" />
								</td>								
							 </tr>
						</table>
					</form>
					<form name="oficina_form" method='post' id="oficina_form" action='p_tripulacion_action.php?insert'>
					<!--Para codigo-->
					<!--<input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ($_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />-->
						<table border="0">							  
							  <tr>
								<th><span>*</span>Número de Licencia : </th>
								<td colspan="3">									
									<input id='n_licencia' type='text' name='n_licencia' value="" title="Número de Licencia." tabindex="1" style="width:250px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>
							  </tr>
							  <tr>
								<th><span>*</span>Apellidos y Nombres: </th>
								<td colspan="3">									
									<input id='nombres' type='text' name='nombres' value="" title="Nombres y Apellidos del Tripulante." tabindex="2" style="width:450px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								</td>								
							  </tr>
							  <tr>
								<th><span>*</span>Observaciones : </th>
								<td colspan="3">
									<textarea name="observaciones" id="observaciones" tabindex="3" title="Observaciones del Tripulante." style="width:450px; height:60px;font-weight:bold;text-transform:lowercase;"></textarea>
								</td>								
							  </tr>
							  							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
							  </tr>
							  <table>
								<tr>
									<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" tabindex="5" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.giro_form.submit();" /></td>
									<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="6" /></td>
								</tr>
							  </table>
						</table>
						
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

		<h1>Registro de Tripulantes</h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
		<!--Mostrando datos con el boton busqueda-->
		
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Trans_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th style="width:90px;" title="Numero de Licencia">Nro. Licencia</th>';
						echo '<th title="tripulante">Tripulante</th>';
						echo '<th style="text-align:center;" colspan="2" title="Acci&oacute;n" style="text-align:center;">Acci&oacute;n</th>';	
						echo '</tr>';
		
				for ($fila = 0; $fila < count($Trans_Array); $fila++)
				{					
					$n_licencia= utf8_encode($Trans_Array[$fila][0]);
					$tripulante= utf8_encode($Trans_Array[$fila][1]);
					$id_tripulacion=$Trans_Array[$fila][2];
					echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
						echo "<td>$n_licencia</td>";
						echo "<td>$tripulante</td>";
						echo '<td style="text-align:center;width:18px;"><a href="p_tripulacion.php?update='.$id_tripulacion.'" ><img src="./images/Symbol-Update.png" width="15" height="15" title="Modificar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
						echo '<td style="text-align:center;width:18px;"><a href="p_tripulacion_action.php?delete='.$id_tripulacion.'" onclick="return confirmDelete(this);"><img src="./img/operacion/Symbol-Delete.png" width="15" height="15" title="Eliminar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';					
											
					echo "</tr>";
				}
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'p_tripulacion.php?';//curPageURL();
						/*if (strlen($_GET['btn_buscar']) > 0)
							$url = $url .'&';
						else
							$url = $url .'?';*/
						$back = "Atras";
						$next = "Siguiente;";
						echo '<th colspan="8" style="text-align:center;">';
						$paginacion->generaPaginacion($totalRows, $back, $next, $url);
						echo '</th>';
					echo '</tr>';
					echo '</div>';
				
				echo '</table>';
			}
			else
				echo '<p>No hay tripulantes registrados.</p>';
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
				echo '<h1>Error con la Operacion</h1>';
				echo '<p>'.$MsjError.'</p>';
			echo '</div>';
			echo '<!-- Limpiar Unidad del Contenido -->';
			echo '<hr class="clear-contentunit" />';
		
	}
 ?>
	
</div>
