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
								AND (`nc`.`tipo_operacion` = 2
								OR `nc`.`tipo_operacion` = 4)
								ORDER BY `nc`.`descripcion_documento`;");
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
		<form name="encomienda_form" id="encomienda_form" method="post" action="e_envio_action.php?insert" >
		  	<input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ($_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />
            <table border="0">
			  <tr>
				<th><span>*</span>Fecha : </th>
				<td>
					<input name="txt_fecha" id="txt_fecha" type="text" value="<?php echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly style="width:150px;" tabindex="2" onkeypress="return handleEnter(this, event)" >
					<input type="button" value="Cal" class="button" onClick="displayCalendar(document.forms[0].txt_fecha,'dd/mm/yyyy',this)" style="width:54px;" onkeypress="return handleEnter(this, event)" ></td>
				<th><span>*</span>Hora : </th>
				<td>
					<input type="text" value="<?php echo date('H\:i'); ?>" readonly name="txt_hora" class="field" onkeypress="return handleEnter(this, event)" ></td>
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
					</select>                </td>
				<th><span>*</span>Destino : </th>
				<td><select name="cmb_agencia_destino" id="cmb_agencia_destino" class="combo" tabindex="3" onkeypress="return handleEnter(this, event)" title="Agencia de Destino del Giro.">
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
				<td><select name="cmb_documento" id="cmb_documento" class="combo" tabindex="4" onkeypress="return handleEnter(this, event)" title="Tipo de Documento a emitir." onchange="Get_Documento_Numeracion(event, this);" >
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
								echo '<option value="'.$Documentos_Array[$fila][0].'" >'.$Documentos_Array[$fila][1].'</option>';
							}
						}
					?>
                </select></td>
				<div id="num_documento">
				  <td colspan="2" id="num_documento2"><input name="txt_serie" id="txt_serie" type="text"  readonly="readonly" 
				title="N&uacute;mero de Serie" style="width:90px; font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" /> 
				    - 
				    <input name="txt_numero" id="txt_numero" type="text" class="field" tabindex="5" title="N&uacute;mero del Documento." style=" font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" onfocus="this.select()" /></td>
                </div>
              </tr>
            </table>
          <div id="Div_Documento_Content">
            	
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