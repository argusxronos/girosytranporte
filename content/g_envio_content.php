<!-- B.1 MAIN CONTENT -->
<?php
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	
	/* CODIGO PARA OBTENER LOS CODIGO Y NOMBRES DE LOS DOCUMENTOS ASIGNADOS A ESTA OFICINA */
	$db_transporte->query("SELECT `nc`.`id`
	, `nc`.`descripcion_documento`
	, `nc`.`id_documento`
	, `nc`.`serie`
	, (`nc`.`numero_actual` + 1) AS `numero_actual`
	, `nc`.`tipo_operacion`
	FROM `numeracion_documento` AS `nc`
	WHERE `nc`.`idoficina` = '".$_SESSION['ID_OFICINA']."'
	AND (`nc`.`tipo_operacion` = 1
	OR `nc`.`tipo_operacion` = 4);");
	$Documentos_Array = $db_transporte->get();
?>
<div id="main-content" class="main-content">
	<!-- Pagetitle -->
	<h1 class="pagetitle">Registrar Nuevo Giro. <span>RECUERDE INGRESAR PRIMERO LOS APELLIDOS Y LUEGO LOS NOMBRES</span></h1>
	<!-- Content unit - One column -->
	<div class="column1-unit">
	  <div class="contactform">
		<form name="giro_form" id="giro_form" method="post" action="g_envio_action.php?insert" class="">
		  	<table border="0">
			  <tr>
				<th><span>*</span>Fecha : </th>
				<td>
					<input name="txt_fecha" id="txt_fecha" type="text" value="<?php echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly style="width:150px; font-size:14px; font-weight:bold;" tabindex="2" onkeypress="return handleEnter(this, event)" >
					<input type="button" value="Cal" class="button" onClick="displayCalendar(document.forms[0].txt_fecha,'dd/mm/yyyy',this)" style="width:54px;" onkeypress="return handleEnter(this, event)" ></td>
				<th><span>*</span>Hora : </td>
				<td>
					<input type="text" value="<?php echo date('H\:i'); ?>" readonly name="txt_hora" class="field" onkeypress="return handleEnter(this, event)" >				</td>
			  </tr>
			  <tr>
				<th><span>*</span>Agencia :</th>
				<td>
					<select name="cmb_agencia_origen" id="cmb_agencia_origen" class="combo" title="Agenia de origen del giro.">
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
					</select>				</td>
				<th><span>*</span>Destino : </td>
				<td>
					<select name="cmb_agencia_destino" class="combo" tabindex="3" onkeypress="return handleEnter(this, event)" title="Agencia de Destino del Giro." style="font-size:13px; font-weight:600;">
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
											echo '<option value="'.$Oficina_Array[$fila][0].'" 						 <option disabled="disabled"> '.$Oficina_Array[$fila][1].' </option>';
										else
											echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
									}
								}
							 ?>
					</select>
                </td>
			  </tr>
			  <tr id="DivDocumentoSN">
				<th><span>*</span>Documento : </th>
				<td><select name="cmb_documento" id="cmb_documento" class="combo" tabindex="4" onkeypress="return handleEnter(this, event)" title="Tipo de Documento a emitir." onchange="Get_Numeracion(event, this, 1);" >
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
								if ($Documentos_Array[$fila][2] == 3)
									echo '<option value="'.$Documentos_Array[$fila][0].'" selected="selected" >'.$Documentos_Array[$fila][1].'</option>';
								else
									echo '<option value="'.$Documentos_Array[$fila][0].'" disabled="disabled" >'.$Documentos_Array[$fila][1].'</option>';
							}
						}
					?>
			    </select></td>
				<td colspan="2" id="num_documento2"><input name="txt_serie" id="txt_serie" type="text"  readonly="readonly" 
				title="N&uacute;mero de Serie" style="width:90px; font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" /> 
				    - 
				    <input name="txt_numero" id="txt_numero" type="text" class="field" tabindex="5" title="N&uacute;mero del Documento." style="font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" onfocus="this.select()" /></td>
			  </tr>
			  <tr>
				<td colspan="5" style="height:10px;">
					<span class="advertisement" style="margin-left:190px; text-decoration:blink; font-size:24px;">Apellidos</span>
					<span style="margin-left:30px;">,</span>
					<span class="advertisement" style="margin-left:80px;">Nombres</span>
					<span style="margin-left:115px;">-</span>
					<span class="advertisement" style="margin-left:35px;">D.N.I.</span>				</td>
			  </tr>
			  <tr>
				<th><span>*</span>Remitente : </th>
				<td colspan="4">
					<input type="hidden" id="txt_remit_hidden" name="txt_remit_ID" />
					<input type="text" value="" name="txt_remit" id="txt_remit" class="input_nombres" style="width:455px; text-transform:uppercase; text-align:left;" title="Apelldios del Remitente." tabindex="6" onkeypress="return acceptletras(this, event);" onkeyup="ajax_showOptions(this,'getPersonByLetters',event);" autocomplete="off" onfocus="this.select();" />
					<span>-</span>
					<input name="txt_remit_dni" type="text" id="txt_remit_dni" tabindex="7" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Remitente.&#10;Este dato es requerido." style="width:110px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off" />				
				</td>
			  </tr>
			  <tr>
				<th><span>*</span>Consignatario : </th>
				<td colspan="4">
					<input type="hidden" id="txt_consig_hidden" name="txt_consig_ID" />
					<input type="text" value="" name="txt_consig" class="input_nombres" style="width:455px;text-transform:uppercase; text-align:left;" title="Apellidos del Consignatario." tabindex="8" onkeypress="return acceptletras(this, event)" onkeyup="ajax_showOptions(this,'getPersonByLetters',event)" autocomplete="off" onfocus="this.select();" />
					<span>-</span>
					<input name="txt_consig_dni" type="text" id="txt_consig_dni" tabindex="9" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Consignatario.&#10;Este dato no es requerido." style="width:110px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off" />				</td>
			  </tr>
			  <!-- <tr>
				<th><span>*</span>Moneda : </th>
				<td>
				<select name="cmb_tipo_moneda" id="cmb_tipo_moneda" class="combo" title="Tipo de Moneda." tabindex="10" onkeypress="return handleEnter(this, event)" onchange="document.giro_form.txt_letras_monto.value = covertirNumLetras(document.giro_form.txt_monto.value, document.giro_form.cmb_tipo_moneda.value), document.giro_form.txt_letras_flete.value = covertirNumLetras(document.giro_form.txt_flete.value, document.giro_form.cmb_tipo_moneda.value)">
                  <option value="1" selected="selected">Soles (S/.)</option>
                  <option value="2">Dolares ($)</option>
                </select></td>
				<td colspan="2">&nbsp;</td>
			  </tr> -->
			  <tr>
				<th><span>*</span>Monto del Giro: </th>
				<td><input name="txt_monto" type="text" id="txt_monto" class="field" title="Monto a enviar." tabindex="10" onkeypress="return handleEnter(this,event);"  onkeyup="extractNumber(this,2,false); document.giro_form.txt_letras_monto.value = covertirNumLetras(document.giro_form.txt_monto.value, 1);document.giro_form.txt_flete.value = Math.round((this.value * 0.10)*100)/100;document.giro_form.txt_letras_flete.value = covertirNumLetras(document.giro_form.txt_flete.value, 1)" style="font-size:16px; font-weight:bold;" ></td>
				<td colspan="2"><textarea name="txt_letras_monto" id="txt_letras_monto" title="Monto a enviar en letras." cols="" rows="" style="width:345px; height:30px;" readonly="readonly"></textarea></td>
			  </tr>
			  <tr>
				<th><span>*</span>Flete :</th>
				<td><input type="text" value="" name="txt_flete" class="field" title="Monto de retenci&oacute;n por el Giro." tabindex="11" onkeypress="return handleEnter(this,event);"  ONKEYUP="extractNumber(this,2,false); document.giro_form.txt_letras_flete.value = covertirNumLetras(document.giro_form.txt_flete.value, 1)" onfocus="this.select();" /></td>
				<td colspan="2"><textarea name="txt_letras_flete" id="txt_letras_flete" title="Monto de Retencion en letras." cols="" rows="" style="width:345px; height:30px;" readonly="readonly"></textarea></td>
			  </tr>
			  <tr>
				<th title="Especifica el modo de entrega del giro."><span>*</span>Modo/Entrega :</th>
				<td colspan="4">
				  <input type="text" value="CON D.N.I." name="txt_observacion" class="field" title="Observacion en caso de no encontrar un modo de entrega." tabindex="12"  style="width:460px; text-transform:uppercase; font-size:16px; font-weight:bold;" onfocus="this.select();" onkeypress="return handleEnter(this,event);" />
				  
				</td>
			  </tr>
              <tr>
				<th title="Especifica el modo de entrega del giro."><span>*</span>Clave :</th>
				<td colspan="4">
				  <input type="password" value="" name="txt_clave" id="txt_clave" class="field" title="Clave de Seguridad." tabindex="13"  style="width:150px; text-transform:uppercase; font-size:16px; font-weight:bold;" onfocus="this.select()" maxlength="4" onkeyup="extractNumber(this,2,false);" onkeypress = "return handleEnter(this, event);" onblur="jsf_Empty_Clave(this);" />
				</td>
			  </tr>
			  <tr>
				<th colspan="5" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
			  </tr>
			  <tr>
				<th colspan="5" style="height:5px;"></th>
			  </tr>
			  <tr>
				<th colspan="2" style="text-align:right;" id="132">
					<span><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" tabindex="14" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.giro_form.submit();" /></span>				</th>
				<td colspan="2" style="text-align:left; padding-left:40px;">
					<span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="15" /></span>				</td>
			  </tr>
			</table>
            <script languaje="javascript">
			   Get_Numeracion(event, document.getElementById("cmb_documento"), 1);
			</script>  
		</form>
	  </div>              
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
  
</div>
