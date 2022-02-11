<!-- B.1 MAIN CONTENT -->
<?php
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
?>
<div class="main-content">
	<!-- Pagetitle -->
	<h1 class="pagetitle">Registrar Nueva Encomienda. <span>RECUERDE INGRESAR PRIMERO LOS APELLIDOS Y LUEGO LOS NOMBRES</span></h1>
	<!-- Content unit - One column -->
	<div class="column1-unit">
	  <div class="contactform">
		<form name="encomienda_form" id="encomienda_form" method="post" action="e_derivada_action.php?insert" >
		  	<input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ($_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />
            <table border="0">
			  <tr>
				<th><span>*</span>Fecha : </th>
				<td>
					<input name="txt_fecha" id="txt_fecha" type="text" value="<?php echo date('d\/m\/Y'); ?>" title="Fecha de envio." style="width:150px;" tabindex="1" onkeypress="return handleEnter(this, event)" >
					<input type="button" value="Cal" class="button" onClick="displayCalendar(document.forms[0].txt_fecha,'dd/mm/yyyy',this)" style="width:54px;" onkeypress="return handleEnter(this, event)" ></td>
				<th><span>*</span>Hora : </th>
				<td>
					<input type="text" value="<?php echo date('H\:i'); ?>" readonly name="txt_hora" class="field" onkeypress="return handleEnter(this, event)" ></td>
			  </tr>
              <tr>
				<th><span>*</span>Agencia :</th>
				<td><span style="width:200px;">
				  <select name="cmb_agencia_origen" id="cmb_agencia_origen" class="combo" title="Agenia de origen del giro." onchange="Get_Documento_Numeracion_Derivado('E_DERIVADO');" tabindex="2" onkeypress="return handleEnter(this, event)" >
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
									if ($Oficina_Array[$fila][0] == $_SESSION['ID_OFICINA'])
									{
										echo '<option value="'.$Oficina_Array[$fila][0].'"  disabled="disabled" > '.$Oficina_Array[$fila][1].' </option>';
									}
									else
									{
										echo '<option value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
									}
									
								}
							}
						 ?>
                  </select>
				</span></td>
				<th><span>*</span>Destino : </th>
				<td><select name="cmb_agencia_destino" id="cmb_agencia_destino" class="combo" title="Agencia de Destino del Giro." tabindex="2.5">
                  <?php
							if (count($Oficina_Array) == 0)
							{
								echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
							}
							else
							{
								echo '<option value="">[ Seleccione su Oficina ]</option>';
								for ($fila = 0; $fila < count($Oficina_Array); $fila++)
								{
									if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] == $Oficina_Array[$fila][0])
										echo '<option value="'.$Oficina_Array[$fila][0].'" 						 <option selected="selected"> '.$Oficina_Array[$fila][1].' </option>';
									else
										echo '<option value="'.$Oficina_Array[$fila][0].'" disabled="disabled"> '.$Oficina_Array[$fila][1].' </option>';
								}
							}
						 ?>
                </select></td>
			  </tr>
            </table>
          <div id="div_fila_usuario">
            <table border="0">
			  <tr>
				<th style="width:80px;"><span>*</span><strong>Usuario :</strong></th>
				<td colspan="3" style="width:110px;"><select name="cmb_usuario" id="cmb_usuario" class="combo" tabindex="3" onkeypress="return handleEnter(this, event)" title="Tipo de Documento a emitir." style="width:340px;" >
                  <option value="" selected="selected">[ No hay Usuarios ]</option>
                </select></td>
			  </tr>
			  <tr id="DivDocumentoSN">
				<th><span>*</span>Documento : </th>
				<td><select name="cmb_documento" id="cmb_documento" class="combo" tabindex="4" onkeypress="return handleEnter(this, event)" title="Tipo de Documento a emitir." onchange="Get_Numeracion(event, this, 2);" >
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
            </table>
		  </div>
          <table>
              <tr>
                <td colspan="5" style="height:10px;">
                    <span class="advertisement" style="margin-left:190px; text-decoration:blink; font-size:24px;">Apellidos</span>
                    <span style="margin-left:30px;">,</span>
                    <span class="advertisement" style="margin-left:80px;">Nombres</span></td>
              </tr>
              <tr>
                <th title="Consignatario de la Encomienda."><span>*</span>Consig : </th>
                <td colspan="4">
                    <input type="hidden" id="txt_consig_hidden" name="txt_consig_ID" />
                    <input type="text" value="" name="txt_consig" class="input_nombres" style="width:600px" title="Apellidos del Consignatario." tabindex="6" onkeypress="return acceptletras(this, event)" onkeyup="ajax_showOptions(this,'getPersonByLetters',event,'PERSONAS')" autocomplete="off" onfocus="this.select(); ajax_showOptions(this,'getPersonByLetters',event,true);" /></td>
              </tr>
              <tr>
                <th style="text-align:center;"><input type="text" name="txt_cant" id="txt_cant" class="input_cantidad" tabindex="7" onkeyup="extractNumber(this,0,false);" onkeypress="return handleEnter(this,event);" value="1" onfocus="this.select();" /></th>
                <td><input type="text" value=""  name="txt_descripcion" id="txt_descripcion" class="input_descripcion" title="Direcci&oacute;n." tabindex="8" onkeypress="return acceptletras_descripcion2(this, event);" onfocus="this.select();" /></td>
                <td><input type="text" value="0.00" name="txt_flete" id="txt_flete" class="input_importe" title="Direcci&oacute;n." tabindex="9" onkeypress="return handleEnter(this, event);" onfocus="this.select();" onkeyup="extractNumber(this,2,false);" /></td>
                <td style="text-align:center;"><input type="text" value="0.00" name="txt_carrera" id="txt_carrera" class="input_importe" title="Direcci&oacute;n." tabindex="10" onkeypress="return E_Insert_Temp(this, event);" onkeyup="extractNumber(this,2,false);" onfocus="this.select();" /></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th>CANT</th>
                <th style="text-align:center;">DESCRIPCI&Oacute;N <span>( Limite 5 Items )</span></th>
                <th>FLETE</th>
                <th style="text-align:center;">CARRERA</th>
                <th style="text-align:center;">TOTAL</th>
              </tr>
          </table>
          <div id="Div_List_Items"> 
            <table width="725" border="0">
                <tr>
                    <td width="107">&nbsp;</td>
                    <td width="412">&nbsp;</td>
                    <td width="58">&nbsp;</td>
                    <td width="72">&nbsp;</td>
                    <td width="54">&nbsp;</td>
                </tr>
            </table>
          </div>
            <table>
			  <tr>
				<th colspan="5" style="text-align:center; height:10px;">(<span>*</span>) Campos Requeridos</th>
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
					<span><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Guardar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.encomienda_form.submit();" /></span>				</th>
				<td colspan="2" style="text-align:left; padding-left:40px;">
					<span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" /></span>				</td>
			  </tr>
			</table>
		</form>
	  </div>              
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
  
</div>
