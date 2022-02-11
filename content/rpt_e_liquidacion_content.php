<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// VERIFICAMOS SI ESTA LOGEADO
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged.php");
	$where = '';
	if (isset($_GET['btn_buscar']) && $_GET['btn_buscar'] != "")
	{
		if (strlen($_GET['txt_fecha'])>0)
		{
			$sql = $sql ." AND `g_movimiento`.`fecha_emision` = '".$_GET['txt_fecha']."'";
		}
		if (isset($_GET['txt_consignatario']) && strlen($_GET['txt_consignatario']) > 0)
		{
			$sql = $sql ." AND `CONSIGNATARIO`.`per_ape_nom` LIKE '".utf8_decode(strtoupper(urldecode($_GET['txt_consignatario'])))."%'";
		}
		if (isset($_GET['txt_Remitente']) && strlen($_GET['txt_Remitente']) > 0)
		{
			$sql = $sql ." AND `REMITENTE`.`per_ape_nom` LIKE '".utf8_decode(strtoupper(urldecode($_GET['txt_Remitente'])))."%'";
		}
		if (isset($_GET['txt_num_liquidacion']) && strlen($_GET['txt_num_liquidacion']) > 0)
		{
			$where = $where ." AND `e_liquidacion`.`liq_num_doc` = '".$_GET['txt_num_liquidacion']."'";
		}
		if (isset($_GET['cmb_agencia_destino']) && $_GET['cmb_agencia_destino'] != 0)
		{
			$where = $where ." AND `e_movimiento`.`id_oficina_destino` = " .$_GET['cmb_agencia_destino'];
		}
	}
	// CREAMOS LA CONSULTA DE BUSQUEDA
	$sql = "SELECT 
	`e_liquidacion`.`id_liquidacion`
	, `e_liquidacion`.`liq_fecha`
	, `e_liquidacion`.`liq_hora`
	, `e_liquidacion`.`liq_num_doc`
	, `e_liquidacion`.`id_oficina_destino`
	, `e_liquidacion`.`id_usuario`
	, `liq_chofer`
	, `liq_pullman`
	, COUNT(`e_liquidacion_detalle`.`id_liquidacion`) AS 'TOTAL_ENC'
	FROM `e_liquidacion`
	INNER JOIN `e_liquidacion_detalle`
	ON `e_liquidacion`.`id_liquidacion` = `e_liquidacion_detalle`.`id_liquidacion`
	INNER JOIN `e_mov_detalle`
	ON `e_mov_detalle`.`id_movimiento` = `e_liquidacion_detalle`.`id_movimiento`
	AND `e_mov_detalle`.`e_num_item` = `e_liquidacion_detalle`.`e_num_item`
	INNER JOIN `e_movimiento`
	ON `e_movimiento`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
	WHERE `e_movimiento`.`id_oficina_origen` = ".$_SESSION['ID_OFICINA']."
	AND `e_liquidacion`.`id_oficina_origen` = ".$_SESSION['ID_OFICINA']."
	AND `e_liquidacion`.`liq_estado` = 1
	$where
	GROUP BY `e_liquidacion`.`id_liquidacion`
	, `e_liquidacion`.`liq_fecha`
	, `e_liquidacion`.`liq_hora`
	, `e_liquidacion`.`liq_num_doc`
	, `e_liquidacion`.`id_oficina_origen`
	, `e_liquidacion`.`id_usuario`
	, `liq_chofer`
	, `liq_pullman`
	HAVING COUNT(`e_liquidacion_detalle`.`id_liquidacion`) > 0
	ORDER BY `e_liquidacion`.`id_liquidacion` DESC
	LIMIT 15;";
	// OBTEMOS LOS DATOS DE MOVIMIENTOS
	require_once 'cnn/config_master.php';
	// REALIZAMOS LA CONSULTA A LA BD
	$db_giro->query($sql);
	$Giros_Array = $db_giro->get();
	
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
	
	  <?php 
	if (!isset($_GET['ID']))
	{
?>
	  <!-- Content unit - One column -->

    <div class="column1-unit">

	  <h1>Reporte de Liquidaciones - Zona de Busqueda</h1>
    <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
		<form method="get" action="rpt_e_liquidacion.php" name="buscar_liquidacion" >
          <table width="100%" border="0">
                    <tr>
                        <th style="text-align:right; width:100px;">Ag. Destino</th>
                        <th><select name="cmb_agencia_destino" id="cmb_agencia_destino" class="combo" title="Agenia donde se Pag&oacute; del giro." tabindex="1" style="width:220px;" onkeypress="return handleEnter(this,event);" >
                          <?php
                                    if (count($Oficina_Array) == 0)
                                    {
                                        echo '<option value="0">[ NO HAY OFICINAS...! ]</option>';
                                    }
                                    else
                                    {
                                        echo '<option value="" selected="selected">[ Seleccione Oficina ]</option>';
                                        for ($fila = 0; $fila < count($Oficina_Array); $fila++)
                                        {
											if ($Oficina_Array[$fila][0] == $_SESSION['ID_OFICINA'])
											{
												echo '<option value="'.$Oficina_Array[$fila][0].'" disabled="disabled" > '.$Oficina_Array[$fila][1].' </option>';
											}
											else
											{
												if (isset($_GET['cmb_agencia_origen']) && $_GET['cmb_agencia_origen'] == $Oficina_Array[$fila][0])
												{
													echo '<option selected="selected" value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
												}
												else
													echo '<option value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
											}
                                        }
										echo '<option value="0" >TODOS</option>';
                                    }
                                 ?>
                        </select></th>
                        <th style="text-align:right; width:150px;">Nro. Liquidaci&oacute;n :</th>
                      	<td><input type="text" name="txt_num_liquidacion" id="txt_num_liquidacion" style="width:220px; text	-transform:uppercase;" value="<?php if (isset($_GET['btn_buscar'])) echo $_GET['txt_num_liquidacion']; ?>" onkeypress="return handleEnter(this,event);" tabindex="2" /></td>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:right;">
                            <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="3" /></span></th>
                        <th colspan="2" style="text-align:left; ">
                            <span><input type="button" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" style="margin-left:35px;" onclick="document.getElementById('txt_num_liquidacion').value = '';document.getElementById('cmb_agencia_origen').selectedIndex = 0;" /></span></th>
                    </tr>
          </table>
          </form>
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Content unit - One column -->
	<div class="column1-unit">

		<h1>Liquidaciones emitidas en esta Oficina</h1>                            
		<?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
			if (count ($Giros_Array) > 0)
			{
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th style="width:70px;text-align:center;" title="Fecha / Hora del Giro">Fecha/Hora</th>';
						echo '<th style="text-align:center;">Liq.</th>';
						echo '<th title="Agencia Destino" style="text-align:left;">Age. Destino <br />por: <span title="Usuario que registr&oacute; la liquidaci&oacute;n.">Usuario</span></th>';
						echo '<th style="text-align:center;">Chofer</th>';
						echo '<th style="text-align:center;">Unidad</th>';
						echo '<th title="Total Encomiendas registradas en la Liquidaci&oacute;n." style="text-align:center;">Enc.</th>';
						echo '<th colspan="2" title="Reimpresi&oacute;n de Liquidaciones." style="text-align:center;">Re-Imprimir</th>';
					echo '</tr>';
		
				for ($fila = 0; $fila < count($Giros_Array); $fila++)
				{
					$id = $Giros_Array[$fila][0];
					$fecha = $Giros_Array[$fila][1];
					$hora = ($Giros_Array[$fila][2]);
					$liquidacion = $Giros_Array[$fila][3];
					$id_oficina_origen = $Giros_Array[$fila][4];
					$oficina_origen = OficinaByID($id_oficina_origen);
					$id_usuario = $Giros_Array[$fila][5];
					$user = UserByID($id_usuario);
					$user_name = UserNombreByID($id_usuario);
					$chofer = utf8_encode($Giros_Array[$fila][6]);
					$unidad = $Giros_Array[$fila][7];
					$encomiendas = $Giros_Array[$fila][8];
					echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
						echo "<td>$fecha<br/>$hora</td>";
						echo "<td>$liquidacion</td>";
						echo "<td>$oficina_origen<br /><span title=\"$user_name\">$user</span></td>";
						echo "<td>$chofer</td>";
						echo "<td style=\"text-align:center;\">$unidad</td>";
						echo "<td style=\"text-align:center;\">$encomiendas</td>";
						if ($encomiendas <= 32)
						{
							echo '<td style="text-align:center;" title="Imprimir una Liquidaci&oacute;n Peque&ntilde;a."><img src="img/operacion/Symbol-Print.png" style="cursor:hand;margin-left:5px;" onClick="reprint_liquidacion(\'print_e_liq_pequenia.php\',\''.$id.'\')" /></td>';
						}
						else
						{
							echo '<td style="text-align:center;" title="No se puede Imprimir una Liquidaci&oacute;n Peque&ntilde;a."><img src="img/operacion/Symbol-Delete.png" style="margin-left:5px;" /></td>';
						}
						if ($encomiendas > 32 && $encomiendas <= 52)
						{
							echo '<td style="text-align:center;" title="Imprimir una Liquidacion Grande."><img src="img/operacion/Symbol-Print2.png" style="cursor:hand;margin-left:5px;" onClick="reprint_liquidacion(\'print_e_liq_grande.php\',\''.$id.'\')" /></td>';
						}
						elseif($encomiendas <= 32)
						{
							echo '<td style="text-align:center;" title="Solo Imprimir una Liquidacion Peque&ntilde;a."><img src="img/operacion/Symbol-Delete.png" style="margin-left:5px;")" /></td>';
						}else
						{
							echo '<td style="text-align:center;" title="Solo Imprimir una Liquidacion Peque&ntilde;a."><img src="img/operacion/Symbol-Delete.png" style="margin-left:5px;")" /></td>';
						}
					echo "</tr>";
				}
				echo '</table>';
			}
			else
				echo '<p>No hay giros registrados para esta Oficina.</p>';
		?>
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
    <div id="div_error">
    </div>
<?PHP
	}
 ?>
	
</div>