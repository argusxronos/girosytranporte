<!-- B.1 MAIN CONTENT -->
<?php
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	
	/* CODIGO PARA OBTENER LOS CODIGO Y NOMBRES DE LOS DOCUMENTOS ASIGNADOS A ESTA OFICINA */
	if (!isset($_SESSION['DOCUMENTO_ENCOMIENDA']))
	{
		$db_transporte->query("SELECT `nc`.`id`, `nc`.`descripcion_documento`
								FROM `numeracion_documento` AS `nc`
								WHERE `nc`.`idoficina` = '".$_SESSION['ID_OFICINA']."'
								AND `nc`.`tipo_operacion` = 2;");
		$_SESSION['DOCUMENTO_ENCOMIENDA'] = $db_transporte->get();
	}
	$Documentos_Array = $_SESSION['DOCUMENTO_ENCOMIENDA'];
?>
<div class="main-content">
	<!-- Pagetitle -->
	<h1 class="pagetitle">Registrar Nueva Encomienda. <span>RECUERDE INGRESAR PRIMERO LOS APELLIDOS Y LUEGO LOS NOMBRES</span></h1>
	<!-- Content unit - One column -->
	<div class="column1-unit">
	  <div class="contactform">
		<form name="giro_form" id="giro_form" method="post" action="g_envio_action.php?insert" class="">
		  	<table border="0">
			  <tr>
				<th><span>*</span>Fecha : </th>
				<td>
					<input name="txt_fecha" id="txt_fecha" type="text" value="<?php echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly style="width:150px;" tabindex="1" onkeypress="return handleEnter(this, event)" >
					<input type="button" value="Cal" class="button" onClick="displayCalendar(document.forms[0].txt_fecha,'dd/mm/yyyy',this)" style="width:54px;" tabindex="2" onkeypress="return handleEnter(this, event)" ></td>
				<th><span>*</span>Hora : </th>
				<td>
					<input type="text" value="<?php echo date('H\:i'); ?>" readonly name="txt_hora" class="field" onkeypress="return handleEnter(this, event)" ></td>
			  </tr>
              <tr>
				<th><span>*</span>Agencia :</th>
				<td>
					<select name="cmb_agencia_origen" class="combo" title="Agenia de origen del giro.">
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
				<th><span>*</span>Destino : </th>
				<td><select name="cmb_agencia_destino" class="combo" tabindex="3" onkeypress="return handleEnter(this, event)" title="Agencia de Destino del Giro.">
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
                </select></td>
			  </tr>
			  <tr id="DivDocumentoSN">
				<th><span>*</span>Documento : </th>
				<td><select name="cmb_documento" id="cmb_documento" class="combo" tabindex="4" onkeypress="return handleEnter(this, event)" title="Tipo de Documento a emitir." onchange="Get_Numeracion(event, this)"  >
					<?PHP
						if (count($Documentos_Array) == 0)
						{
							echo '<option value="">[ NO HAY DOCUMENTOS...! ]</option>';
						}
						else
						{
							echo '<option value="" selected="selected">[ Seleccione Documento ]</option>';
							for ($fila = 0; $fila < count($Documentos_Array); $fila++)
							{
								echo '<option value="'.$Documentos_Array[$fila][0].'" >'.$Documentos_Array[$fila][1].'</option>';
									$Num_Serie = $Documentos_Array[$fila][3];
									$Num_Boleta = $Documentos_Array[$fila][4];
							}
						}
					?>
					
                </select></td>
                <div id="num_documento">
				<td><input name="txt_serie" id="txt_serie" type="text" 
				value="<?php echo $Num_Serie; ?>" readonly="readonly" 
				title="N&uacute;mero de Serie" style="width:80px; font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" /></td>
			    <td><input name="txt_numero" id="txt_numero" type="text" value="<?php echo $Num_Boleta; ?>" class="field" tabindex="5" title="N&uacute;mero del Documento." style=" font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" onfocus="this.select()" />
              	</td>
                </div>
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
					<input type="text" value="" name="txt_remit" id="txt_remit" class="field" style="width:455px; text-transform:uppercase;" title="Apelldios del Remitente." tabindex="6" onkeypress="return acceptletras(this, event);" onkeyup="ajax_showOptions(this,'getPersonByLetters',event);" autocomplete="off" onfocus="this.select();" />
					<span>-</span>
					<input name="txt_remit_dni" type="text" id="txt_remit_dni" tabindex="7" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Remitente.&#10;Este dato es requerido." style="width:110px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off" />				</td>
			  </tr>
			  <tr>
				<th><span>*</span>Consignatario : </th>
				<td colspan="4">
					<input type="hidden" id="txt_consig_hidden" name="txt_consig_ID" />
					<input type="text" value="" name="txt_consig" class="field" style="width:455px;text-transform:uppercase;" title="Apellidos del Consignatario." tabindex="8" onkeypress="return acceptletras(this, event)" onkeyup="ajax_showOptions(this,'getPersonByLetters',event)" autocomplete="off" onfocus="this.select();" />
					<span>-</span>
					<input name="txt_consig_dni" type="text" id="txt_consig_dni" tabindex="9" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Consignatario.&#10;Este dato no es requerido." style="width:110px; text-align:center; color:#FF0000; font-size:110%; font-weight:bold;" autocomplete="off" />				</td>
			  </tr>
			  <tr>
				<th><span>*</span>Moneda : </th>
				<td>
				<select name="cmb_tipo_moneda" id="cmb_tipo_moneda" class="combo" title="Tipo de Moneda." tabindex="10" onkeypress="return handleEnter(this, event)" onchange="document.giro_form.txt_letras_monto.value = covertirNumLetras(document.giro_form.txt_monto.value, document.giro_form.cmb_tipo_moneda.value), document.giro_form.txt_letras_flete.value = covertirNumLetras(document.giro_form.txt_flete.value, document.giro_form.cmb_tipo_moneda.value)">
                  <option value="1" selected="selected">Soles (S/.)</option>
                  <option value="2">Dolares ($)</option>
                </select></td>
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr>
				<th><span>*</span>Monto del Giro: </th>
				<td><input name="txt_monto" type="text" id="txt_monto" class="field" title="Monto a enviar." tabindex="11" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,2,false); document.giro_form.txt_letras_monto.value = covertirNumLetras(document.giro_form.txt_monto.value, document.giro_form.cmb_tipo_moneda.value);document.giro_form.txt_flete.value = Math.round((this.value * 0.10)*100)/100;document.giro_form.txt_letras_flete.value = covertirNumLetras(document.giro_form.txt_flete.value, document.giro_form.cmb_tipo_moneda.value)"/></td>
				<td colspan="2"><textarea name="txt_letras_monto" id="txt_letras_monto" title="Monto a enviar en letras." cols="" rows="" style="width:345px; height:30px;" readonly="readonly"></textarea></td>
			  </tr>
			  <tr>
				<th><span>*</span>Flete :</th>
				<td><input type="text" value="" name="txt_flete" class="field" title="Monto de retenci&oacute;n por el Giro." tabindex="12" onkeypress="return handleEnter(this,event);"  ONKEYUP="extractNumber(this,2,false); document.giro_form.txt_letras_flete.value = covertirNumLetras(document.giro_form.txt_flete.value, document.giro_form.cmb_tipo_moneda.value)" onfocus="this.select();" /></td>
				<td colspan="2"><textarea name="txt_letras_flete" id="txt_letras_flete" title="Monto de Retenci&oacute;n en letras." cols="" rows="" style="width:345px; height:30px;" readonly="readonly"></textarea></td>
			  </tr>
			  <tr>
				<th title="Especifica el modo de entrega del giro."><span>*</span>Modo/Entrega :</th>
				<td colspan="4">
				  <label title="El Consignatario recoger&aacute; con su D.N.I.">
				  RECOGER&Aacute; <input type="text" value="CON D.N.I." name="txt_observacion" class="field" title="Observaci&oacute;n en caso de no encontrar un modo de entrega." tabindex="13"  style="width:460px; text-transform:uppercase;" onfocus="this.select()" onkeypress = "return handleEnter(this, event);"/>
				  </label>				</td>
			  </tr>
			  <tr>
				<th colspan="5" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos </th>
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
				<th colspan="5" style="height:5px;">&nbsp;</th>
			  </tr>
			  <tr>
				<th colspan="2" style="text-align:right;" id="132">
					<span><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" tabindex="14" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.giro_form.submit();" /></span>				</th>
				<td colspan="2" style="text-align:left; padding-left:40px;">
					<span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="15" /></span>				</td>
			  </tr>
			</table>
		</form>
	  </div>              
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
  
</div>