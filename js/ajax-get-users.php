<?php
	include_once('../config_master.php');
	$id = $_GET['ID'];
	$TYPE = $_GET['TYPE'];
	if ($id > 0)
	{
		$db_transporte->query("SELECT `tusuario`.`id_usuario`, `tusuario`.`t_usuario`
					FROM `tusuario`
					WHERE `tusuario`.`idoficina` = " .$id ."
					AND (`tusuario`.`c_esta_activo` = 1
					OR `tusuario`.`c_esta_activo` = 4)");
		$List = $db_transporte->get();
		if (count($List) > 0)
		{
?>
			<table border="0">
			  <tr>
				<th <?php if($TYPE == 'G_DERIVADO') echo 'style="width:120px;"'; elseif($TYPE=='E_DERIVADO') echo 'style="width:75px;"'; ?>><span>*</span><strong>Usuario :</strong></th>
				<td colspan="3" style="width:110px;"><select name="cmb_usuario" id="cmb_usuario" class="combo" tabindex="3" onkeypress="return handleEnter(this, event)" title="Tipo de Documento a emitir." style="width:340px;" >
                  <?php
				  	echo '<option value="" selected="selected">[ Seleccione Usuario ]</option>';
					for ($fila = 0; $fila < count($List); $fila++)
					{
					  echo '<option value="'.$List[$fila][0].'" >'.utf8_encode($List[$fila][1]).'</option>';
					}
				  ?>
                </select></td>
			  </tr>
<?php
		}
		else
		{
?>
			<table border="0">
			  <tr>
				<th <?php if($TYPE == 'G_DERIVADO') echo 'style="width:120px;"'; elseif($TYPE=='E_DERIVADO') echo 'style="width:75px;"'; ?>><span>*</span><strong>Usuario :</strong></th>
				<td colspan="3" style="width:110px;"><select name="cmb_usuario" id="cmb_usuario" class="combo" tabindex="3" onkeypress="return handleEnter(this, event)" title="Tipo de Documento a emitir." style="width:340px;" >
                  <option value="" selected="selected">[ No hay Usuarios ]</option>
                </select></td>
			  </tr>
<?php
		}
		$sql = "";
		if ($TYPE == 'G_DERIVADO')
		{
			$sql = "SELECT `nc`.`id`, `nc`.`descripcion_documento`, `nc`.`id_documento`, `nc`.`serie`, (`nc`.`numero_actual` + 1) AS `numero_actual`, `nc`.`tipo_operacion`
			FROM `numeracion_documento` AS `nc`
			WHERE `nc`.`idoficina` = " .$id ."
			AND (`nc`.`tipo_operacion` = 1
			OR `nc`.`tipo_operacion` = 4)
			ORDER BY `nc`.`descripcion_documento`";
		}
		elseif($TYPE == 'E_DERIVADO')
		{
			$sql = "SELECT `nc`.`id`, `nc`.`descripcion_documento`, `nc`.`id_documento`, `nc`.`serie`, (`nc`.`numero_actual` + 1) AS `numero_actual`, `nc`.`tipo_operacion`
			FROM `numeracion_documento` AS `nc`
			WHERE `nc`.`idoficina` = " .$id ."
			AND (`nc`.`tipo_operacion` = 2
			OR `nc`.`tipo_operacion` = 4)
			ORDER BY `nc`.`descripcion_documento`";
		}
		$db_transporte->query($sql);
		$Documentos_Array = $db_transporte->get();
		if (count($List) > 0)
		{
?>
			  <tr id="DivDocumentoSN">
				<th><span>*</span>Documento : </th>
				<td><select name="cmb_documento" id="cmb_documento" class="combo" tabindex="4" onkeypress="return handleEnter(this, event)" title="Tipo de Documento a emitir." onchange="Get_Numeracion(event, this, <?php if($TYPE == 'G_DERIVADO') echo '1'; elseif ($TYPE == 'E_DERIVADO') echo '2'; ?>);" >
				  <?PHP
						if (count($Documentos_Array) == 0)
						{
							echo '<option value="">[ NO HAY DOCUMENTOS...! ]</option>';
						}
						else
						{
							echo '<option value="0" selected="selected">[ Seleccione Documento ]</option>';
							for ($fila = 0; $fila < count($Documentos_Array); $fila++)
							{
								if ($TYPE == 'G_DERIVADO')
								{
									if ($Documentos_Array[$fila][2] == 3)
										if ($TYPE == 'G_DERIVADO')
											echo '<option value="'.$Documentos_Array[$fila][0].'" >'.$Documentos_Array[$fila][1].'</option>';
										else
											echo '<option value="'.$Documentos_Array[$fila][0].'" selected="selected" >'.$Documentos_Array[$fila][1].'</option>';
									else
										echo '<option value="'.$Documentos_Array[$fila][0].'" disabled="disabled" >'.$Documentos_Array[$fila][1].'</option>';
								}
								elseif($TYPE == 'E_DERIVADO')
								{
									echo '<option value="'.$Documentos_Array[$fila][0].'" >'.$Documentos_Array[$fila][1].'</option>';
								}
							}
						}
					?>
			    </select></td>
				<td colspan="2" id="num_documento2"><input name="txt_serie" id="txt_serie" type="text"  readonly="readonly" 
				title="N&uacute;mero de Serie" style="width:90px; font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" /> 
				    - 
				    <input name="txt_numero" id="txt_numero" type="text" class="field" tabindex="5" title="N&uacute;mero del Documento." style=" font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" onfocus="this.select()" /></td>
			  </tr>
            </table>
<?php
		}
		else
		{
?>
			<tr id="DivDocumentoSN">
				<th><span>*</span>Documento : </th>
				<td><select name="cmb_documento" id="cmb_documento" class="combo" tabindex="4" onkeypress="return handleEnter(this, event)" title="Tipo de Documento a emitir." onchange="Get_Numeracion(event, this, <?php if($TYPE == 'G_DERIVADO') echo '1'; elseif ($TYPE == 'E_DERIVADO') echo '2'; ?>);" >
                  <option value="">[ NO HAY DOCUMENTOS...! ]</option>
                </select></td>
				<td colspan="2" id="num_documento2"><input name="txt_serie" id="txt_serie" type="text"  readonly="readonly" 
				title="N&uacute;mero de Serie" style="width:90px; font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" /> 
				    - 
				    <input name="txt_numero" id="txt_numero" type="text" class="field" tabindex="5" title="N&uacute;mero del Documento." style=" font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" onfocus="this.select()" /></td>
			  </tr>
            </table>
<?php
		}
	}
?>
