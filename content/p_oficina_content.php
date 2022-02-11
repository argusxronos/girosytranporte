<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged.php");
	// CREAMOS LA CONSULTA DE BUSQUEDA
	if(isset($_POST['buscar']))
	{
		$buscar=$_POST['buscar'];
		$sql = "SELECT oficinas.nro_ip,oficinas.oficina,oficinas.serie,oficinas.direccion,oficinas.`idoficina` FROM oficinas
				WHERE oficinas.`oficina` LIKE '%$buscar%'";
		$sql_rows = "SELECT COUNT(oficinas.`idoficina`) AS TOTAL FROM oficinas
				WHERE oficinas.`oficina` LIKE '%$buscar%'";
	}
	else {
		$sql = "SELECT oficinas.nro_ip,oficinas.oficina,oficinas.serie,oficinas.direccion,oficinas.`idoficina` FROM oficinas";
		$sql_rows = "SELECT COUNT(oficinas.idoficina) AS TOTAL FROM oficinas";
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
	}*/
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Nueva Oficina</h1>
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
	
	  <h1>Ingrese Datos Nueva Oficina - <span>RECUERDE INGRESAR BIEN LOS DATOS</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			<!--<legend>Nuevo Registro</legend>-->
			<div class='column1-unit'>
				<div class='contactform'>
					<?php
						//COMIENZO DE UPDATE OFICINAS
						if(isset($_GET['update']))
						{
							$valor=$_GET[update];
							$Datos_Oficinas="SELECT oficinas.`idoficina`,oficinas.`nro_ip`,oficinas.`serie`,
											oficinas.`oficina`,oficinas.`direccion` FROM oficinas
											WHERE oficinas.`idoficina`='$valor'";
							$db_transporte->query($Datos_Oficinas);
							$Datos_Array = $db_transporte->get();
							
						
					?>
					<form name="oficina_form" method='post' id="oficina_form" action='p_oficina_action.php?update'>
					<!--Para codigo-->
					<!--<input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ($_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />-->
						<table border="0">
							  <tr>
								<th><span>*</span>N° IP : </th>
								<td>									
									<input id='ip' type='text' name='ip' value="<?php echo $Datos_Array[0][1];?>" title="Numero de IP." tabindex="1" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" >
									</td>
								<th><span>*</span>Serie : 
								<td><input name="serie" type="text" id="serie" maxlength="6" value="<?php echo $Datos_Array[0][2]; ?>" tabindex="2" onkeypress="return handleEnter(this,event);"  onkeyup="extractNumber(this,2,false);" title="Serie de Boletos que usa la Oficina" style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
							  </tr>
							  <tr>
								<th><span>*</span>Oficina : </th>
								<td colspan="3">									
									<input id='oficina' type='text' name='oficina' value="<?php echo $Datos_Array[0][3];?>" title="Oficina." tabindex="3" style="width:400px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;" onkeypress="return handleEnter(this, event)" >
									</td>								
							  </tr>
							  <tr>
								<th><span>*</span>Dirección : </th>
								<td colspan="3">									
									<input id='direccion' type='text' name='direccion' value="<?php echo $Datos_Array[0][4];?>" title="direccion de oficina." tabindex="4" style="width:400px; text-align:center; font-size:120%; font-weight:bold;text-transform:lowercase;" onkeypress="return handleEnter(this, event)" >
									</td>								
							  </tr>
							  							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos <input type="hidden" value="<?php echo $Datos_Array[0][0];?>" readonly name="txt_codigo" class="" style="width:150px; text-align:center;"></th>
							  </tr>
							  <tr>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Modificar" tabindex="5" /></td>
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar"  tabindex="6" onclick="location.href='p_oficina.php'" /></td>
							  </tr>
						</table>						
					</form>
					<?php
						//FIN DE OFICINAS UPDATE
						}
						else {
							//INICIO DE P_OFICINAS.PHP SIN UPDATE												
					?>
					<form name="oficina_form" method='post' id="buscar_form" action='p_oficina.php?buscar'>
						<table>
							<tr>																					
								<td colspan="4" style="text-align:right;">									
									<input id='buscar' type='text' name='buscar' value="" title="Buscar por Oficina." tabindex="7" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
									<input name="btn_Buscard" id="btn_Buscard" type="submit" class="button" value="Buscar" tabindex="8"/>
								</td>								
							 </tr>
						</table>
					</form>
					<form name="addOficina_form" method='post' id="addOficina_form" action='p_oficina_action.php?insert'>
					<!--Para codigo-->
					<!--<input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ($_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />-->
						<table border="0">							  
							  <tr>
								<th><span>*</span>N° IP : </th>
								<td>									
									<input id='ip' type='text' name='ip' value="" title="Numero de IP." tabindex="1" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
									</td>
								<th><span>*</span>Serie : 
								<td><input name="serie" type="text" maxlength="3" id="serie" tabindex="2" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="Inicio de asientos piso 1." style="width:150px; text-align:center; color:#FF0000; font-size:120%; font-weight:bold;" autocomplete="off" /></td>
							  </tr>
							  <tr>
								<th><span>*</span>Oficina : </th>
								<td colspan="3">									
									<input id='oficina' type='text' name='oficina' value="" title="Oficina." tabindex="3" style="width:400px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
									</td>								
							  </tr>
							  <tr>
								<th><span>*</span>Dirección : </th>
								<td colspan="3">									
									<input id='direccion' type='text' name='direccion' value="" title="direccion de oficina." tabindex="4" style="width:400px; text-align:center; font-size:120%; font-weight:bold;text-transform:lowercase;">
									</td>								
							  </tr>
							  							  
							  <tr>
								<th colspan="4" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
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
								<td colspan="2" style="text-align:center;font-size:140%;" id="132"><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" tabindex="5" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.addOficina_form.submit();" /></td>
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

		<h1>Registro de Oficinas</h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Trans_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th style="width:70px;" title="numero ip">N° IP</th>';
						echo '<th title="Oficina">Oficina</th>';
						echo '<th title="serie">Serie</th>';
						echo '<th title="direccion">Direccion</th>';	
						echo '<th style="text-align:center;" colspan="2" title="Acciones">Acci&oacute;n.</th>';	
					echo '</tr>';
		
				for ($fila = 0; $fila < count($Trans_Array); $fila++)
				{					
					$ip = utf8_encode($Trans_Array[$fila][0]);
					$oficina = utf8_encode($Trans_Array[$fila][1]);
					$serie =$Trans_Array[$fila][2];
					$direccion = $Trans_Array[$fila][3];
					$codigo_oficina=$Trans_Array[$fila][4];	
					//falata la variable del codigo ejemplo:id_salida				
					echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
						echo "<td>$ip</td>";
						echo "<td>$oficina</td>";
						echo "<td>$serie</td>";
						echo "<td>$direccion</td>";
						echo '<td style="text-align:center;"><a href="p_oficina.php?update='.$codigo_oficina.'" ><img src="./images/Symbol-Update.png" width="25" height="25" title="Modificar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
						echo '<td style="text-align:center;"><a href="p_oficina_action.php?delete='.$codigo_oficina.'" onclick="return confirmDelete(this);"><img src="./img/operacion/Symbol-Delete.png" width="25" height="25" title="Eliminar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';					
					echo "</tr>";
				}
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'p_oficina.php?';//curPageURL();						
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
				echo '<p>No hay Oficinas Registradas.</p>';
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
