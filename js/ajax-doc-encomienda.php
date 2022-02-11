<?php
$TipoDoc = $_GET['TD'];
/* CODIGO PARA LOS TIPOS DE DOCUMENTO
3	BOLETA
4	FACTURA
5	GUIA REMISION
6	GUIA INTERNA
9 GUIA TRANSBORDO*/
session_start();
require_once('../config_giro.php');
$db_giro->query("truncate table temp_mov_detalle;");

if($TipoDoc == 'GUIA INTERNA')
{
?>
<!-- INICIO: GUIA INTERNA -->
<table>
  <tr>
    <td colspan="5" style="height:10px;">
      <span class="advertisement" style="margin-left:190px; text-decoration:blink; font-size:24px;">Apellidos</span>
      <span style="margin-left:30px;">,</span>
      <span class="advertisement" style="margin-left:80px;">Nombres</span></td>
  </tr>
  <tr>
    <th><span>*</span>Remite : </th>
    <td colspan="4">
        <input type="hidden" id="txt_remit_hidden" name="txt_remit_ID" />
        <input type="text" value="<?php echo $_SESSION['OFICINA']; ?>" name="txt_remit" id="txt_remit" class="input_nombres" style="width:600px" title="Apelldios del Remitente." onkeypress="return acceptletras(this, event);" onkeyup="ajax_showOptions(this,'getPersonByLetters',event, 'PERSONAS');" autocomplete="off" onfocus="this.select();" tabindex="5.5" /></td>
  </tr>
  <tr>
    <th><span>*</span>Para : </th>
    <td colspan="4">
        <input type="hidden" id="txt_consig_hidden" name="txt_consig_ID" />
        <input type="text" value="" name="txt_consig" class="input_nombres" style="width:600px" title="Apellidos del Consignatario." tabindex="6" onkeypress="return acceptletras(this, event)" onkeyup="ajax_showOptions(this,'getPersonByLetters',event,'PERSONAS')" autocomplete="off" onfocus="this.select(); ajax_showOptions(this,'getPersonByLetters',event,true);" /></td>
  </tr>
  <tr>
    <th style="text-align:center;"><input type="text" name="txt_cant" id="txt_cant" class="input_cantidad" tabindex="7" onkeyup="extractNumber(this,0,false);" onkeypress="return handleEnter(this,event);" value="1" onfocus="this.select();" /></th>
    <td><input type="text" value=""  name="txt_descripcion" id="txt_descripcion" class="input_descripcion" title="Descripcion" tabindex="8" onkeypress="return acceptletras_descripcion2(this, event);" onfocus="this.select();" onkeyup="ajax_showOptions(this,'getDescripcion',event, 'DESCRIPCION');" /></td>
    <td><input type="text" value="0.00" name="txt_flete" id="txt_flete" class="input_importe" title="Direcci&oacute;n." tabindex="9" onkeypress="return handleEnter(this, event);" onfocus="this.select();" onkeyup="extractNumber(this,2,false);" /></td>
    <td style="text-align:center;"><input type="text" value="0.00" name="txt_carrera" id="txt_carrera" class="input_importe" title="Direcci&oacute;n." tabindex="10" onkeypress="return E_Insert_Temp(this, event);" onkeyup="extractNumber(this,2,false);" onfocus="this.select();" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th>CANT</th>
    <th style="text-align:center;">DESCRIPCIÓN <span>( Limite 5 Items )</span></th>
    <th>FLETE</th>
    <th style="text-align:center;">CARRERA</th>
    <th style="text-align:center;">TOTAL</th>
  </tr>
</table>
<div id="Div_List_Items"> 
<table width="725" border="0">
    <tr>
        <td width="108"></td>
        <td></td>
        <td width="54"></td>
        <td width="72"></td>
        <td width="56"></td>
    </tr>
    <tr>
        <th colspan="4" style="text-align:right;"><span>Total</span></th>
        <th style="text-align:right;"><span>0.00</span></th>
    </tr>
</table>
</div>
<!-- FIN: GUIA INTERNA -->
<?php
	}
	elseif ($TipoDoc == 'BOLETA')
	{
?>
<!-- INICIO: BOLETA -->
<table>
  <tr>
    <td colspan="6" style="height:10px;">
        <span style="margin-left:150px;">D.N.I.</span>
        <span class="advertisement" style="margin-left:150px; text-decoration:blink; font-size:24px;">Apellidos</span>
        <span style="margin-left:30px;">,</span>
        <span class="advertisement" style="margin-left:80px;">Nombres</span>    </td>
  </tr>
  <tr>
    <th><span>*</span>Remite : </th>
    <td colspan="4">
        <input type="hidden" id="txt_remit_hidden" name="txt_remit_ID" />
        <input name="txt_remit_dni" type="text" id="txt_remit_dni" tabindex="6" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Remitente.
Este dato es requerido." class="input_documento" autocomplete="off" onblur="getClientData('BOLETA', 'REMITENTE');" onfocus="this.select();" maxlength="8" /> 
        - 
        <input type="text" value="" name="txt_remit" id="txt_remit" class="input_nombres" title="Apelldios del Remitente." tabindex="7" onkeypress="return acceptletras(this, event);" onkeyup="ajax_showOptions(this,'getPersonByLetters',event, 'PERSONAS');" autocomplete="off" onfocus="this.select();" onblur="getClientDataById('BOLETA','REMITENTE');" /></td>
  </tr>
  <tr>
    <th><span>*</span>Para : </th>
    <td colspan="4">
        <input type="hidden" id="txt_consig_hidden" name="txt_consig_ID" />
        <input name="txt_consig_dni" type="text" id="txt_consig_dni" tabindex="8" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Consignatario.
Este dato no es requerido." class="input_documento" autocomplete="off" onblur="getClientData('BOLETA', 'CONSIGNATARIO');" maxlength="8" /> 
        - 
        <input type="text" value="" id="txt_consig" name="txt_consig" class="input_nombres" title="Apellidos del Consignatario." tabindex="9" onkeypress="return acceptletras(this, event)" onkeyup="ajax_showOptions(this,'getPersonByLetters',event, 'PERSONAS')" autocomplete="off" onfocus="this.select(); ajax_showOptions(this,'getPersonByLetters',event,'FRECUENTES');" onblur="getClientDataById('BOLETA','CONSIGNATARIO');" /></td>
  </tr>
  <tr>
    <th><span>*</span>Direcci&oacute;n : </th>
    <td colspan="4">
    <input name="cbox_carrera" id="cbox_carrera" type="checkbox" value="1" tabindex="10" onkeypress="return handleEnter(this, event);" onClick="if(this.checked == true) {document.getElementById('txt_consig_direccion').readOnly = false; document.getElementById('txt_consig_direccion').select(); document.getElementById('txt_consig_direccion').focus();} else { document.getElementById('txt_consig_direccion').readOnly = true; }" />
     <label style="font-weight:bold;">CON CARRERA ?</label>
    <input type="text" value="AGENCIA" name="txt_consig_direccion" id="txt_consig_direccion" class="input_direccion" title="Direcci&oacute;n." tabindex="11" onkeypress="return handleEnter(this, event);" onfocus="this.select();" readonly="readonly" /></td>
  </tr>
  <!--<tr>
    <th><span>*</span>Clave : </th>
    <td colspan="4"><input type="password" value="" name="txt_clave" id="txt_clave" class="field" title="Clave de Seguridad." tabindex="12"  style="width:150px; text-transform:uppercase; font-size:16px; font-weight:bold;" onfocus="this.select()" maxlength="4" onkeyup="extractNumber(this,2,false);" onkeypress = "return handleEnter(this, event);" onblur="jsf_Empty_Clave(this);" /></td>
  </tr>-->
  <tr>
    <th style="text-align:center;">
		<input type="text" name="txt_cant" id="txt_cant" class="input_cantidad" tabindex="12" onkeyup="extractNumber(this,0,false);" onkeypress="return handleEnter(this,event);" value="1" onfocus="this.select();" />	</th>
    <td>
		<input type="text" value=""  name="txt_descripcion" id="txt_descripcion" class="input_descripcion" title="Descripcion" tabindex="13" onkeypress="return acceptletras_descripcion2(this, event);" onfocus="this.select();" onkeyup="ajax_showOptions(this,'getDescripcion',event, 'DESCRIPCION');" />	</td>
    <td>
		<input type="text" value="0.00" name="txt_flete" id="txt_flete" class="input_importe" title="Dirección." tabindex="14" onkeypress="return handleEnter(this, event);" onfocus="this.select();" onkeyup="extractNumber(this,2,false);" />	</td>
    <td style="text-align:center;">
		<input type="text" value="0.00" name="txt_carrera" id="txt_carrera" class="input_importe" title="Dirección." tabindex="15" onkeypress="return E_Insert_Temp(this, event);" onkeyup="extractNumber(this,2,false);" onfocus="this.select();" />	</td>
    <td>    </td>
  </tr>
  <tr>
    <th>CANT</th>
    <th style="text-align:center;">DESCRIPCIÓN <span>( Limite 5 Items )</span></th>
    <th>FLETE</th>
    <th style="text-align:center;">CARRERA</th>
    <th style="text-align:center;">TOTAL</th>
  </tr>
</table>
<div id="Div_List_Items"> 

<table width="725" border="0">
    <tr>
        <td width="108"></td>
        <td></td>
        <td width="54"></td>
        <td width="72"></td>
        <td width="56"></td>
    </tr>
    <tr>
        <th colspan="4" style="text-align:right;"><span>Total</span></th>
        <th style="text-align:right;"><span>0.00</span></th>
    </tr>
</table>
</div>
<!-- FIN: BOLETA -->
<?PHP
	}
	elseif ($TipoDoc == 'FACTURA')
	{
?>
<!-- INICIO: FACTURA -->
<table>
  <tr>
    <td colspan="6" style="height:10px;"><span style="margin-left:150px;">R.U.C.</span> <span class="advertisement" style="margin-left:150px; text-decoration:blink; font-size:24px;">Raz&oacute;n Social</span></td>
  </tr>
  <tr>
    <th><span>*</span>Remite : </th>
    <td colspan="4">
        <input type="hidden" id="txt_remit_hidden" name="txt_remit_ID" />
        <input name="txt_remit_dni" type="text" id="txt_remit_dni" tabindex="6" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Remitente.
Este dato es requerido." class="input_documento" autocomplete="off" onblur="getClientData('FACTURA', 'REMITENTE');" maxlength="11" /> 
      - 
  <input type="text" 	="" name="txt_remit" id="txt_remit" class="input_nombres" title="Apelldios del Remitente." tabindex="7" onkeypress="return acceptletras(this, event);" onkeyup="ajax_showOptions(this,'getPersonByLetters',event, 'EMPRESA');" autocomplete="off" onfocus="this.select();" onblur="getClientDataById('FACTURA','REMITENTE');" /></td>
  </tr>
  <tr>
    <th><span>*</span>Direcci&oacute;n : </th>
    <td colspan="5"><input type="text" name="txt_remit_direccion" id="txt_remit_direccion" class="input_direccion" title="Direcci&oacute;n." tabindex="8" onkeypress="return handleEnter(this, event);" onfocus="this.select();" /></td>
  </tr>
  <tr>
    <td colspan="6" style="height:10px;"><span style="margin-left:150px;">D.N.I.</span> <span class="advertisement" style="margin-left:150px; text-decoration:blink; font-size:24px;">Apellidos</span> <span style="margin-left:30px;">,</span> <span class="advertisement" style="margin-left:80px;">Nombres</span> </td>
  </tr>
  <tr>
    <th><span>*</span>Para : </th>
    <td colspan="4">
        <input type="hidden" id="txt_consig_hidden" name="txt_consig_ID" />
        <input name="txt_consig_dni" type="text" id="txt_consig_dni" tabindex="9" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Consignatario.
Este dato no es requerido." class="input_documento" autocomplete="off" onblur="getClientData('FACTURA', 'CONSIGNATARIO');" /> 
      -  
      <input type="text" value="" name="txt_consig" id="txt_consig" class="input_nombres" title="Apellidos del Consignatario." tabindex="10" onkeypress="return acceptletras(this, event)" onkeyup="ajax_showOptions(this,'getPersonByLetters',event, 'PERSONAS')" autocomplete="off" onfocus="this.select(); ajax_showOptions(this,'getPersonByLetters',event,'FRECUENTES');" onblur="getClientDataById('BOLETA','CONSIGNATARIO');" /></td>
  </tr>
  <tr>
    <th><span>*</span>Direcci&oacute;n : </th>
    <td colspan="4">
    <input name="cbox_carrera" type="checkbox" value="1" tabindex="11" onkeypress="return handleEnter(this, event);" onClick="if(this.checked == true) {document.getElementById('txt_consig_direccion').readOnly = false; document.getElementById('txt_consig_direccion').select(); document.getElementById('txt_consig_direccion').focus();} else { document.getElementById('txt_consig_direccion').readOnly = true; }" />
     CON CARRERA ?</label>&nbsp;&nbsp;
     <input type="text" value="AGENCIA" name="txt_consig_direccion" id="txt_consig_direccion" class="input_direccion" title="Direcci&oacute;n." tabindex="12" onkeypress="return handleEnter(this, event);" onfocus="this.select();" /></td>
  </tr>
  <!--<tr>
    <th><span>*</span>Clave : </th>
    <td colspan="4"><input type="password" value="" name="txt_clave" id="txt_clave" class="field" title="Clav e de Seguridad." tabindex="13"  style="width:150px; text-transform:uppercase; font-size:16px; font-weight:bold;" onfocus="this.select()" maxlength="4" onkeyup="extractNumber(this,2,false);" onkeypress = "return handleEnter(this, event);" onblur="jsf_Empty_Clave(this);" /></td>
  </tr>-->
  <tr>
    <th style="text-align:center;"><input type="text" name="txt_cant" id="txt_cant" class="input_cantidad" tabindex="13" onkeyup="extractNumber(this,0,false);" onkeypress="return handleEnter(this,event);" value="1" onfocus="this.select();" /></th>
    <td><input type="text" value=""  name="txt_descripcion" id="txt_descripcion" class="input_descripcion" title="Descripcion" tabindex="14" onkeypress="return acceptletras_descripcion2(this, event);" onfocus="this.select();" onkeyup="ajax_showOptions(this,'getDescripcion',event, 'DESCRIPCION');" /></td>
    <td><input type="text" value="0.00" name="txt_flete" id="txt_flete" class="input_importe" title="Direcci&oacute;n." tabindex="15" onkeypress="return handleEnter(this, event);" onfocus="this.select();" onkeyup="extractNumber(this,2,false);" /></td>
    <td style="text-align:center;"><input type="text" value="0.00" name="txt_carrera" id="txt_carrera" class="input_importe" title="Carrera." tabindex="16" onkeypress="return E_Insert_Temp(this, event);" onkeyup="extractNumber(this,2,false);" onfocus="this.select();" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th>CANT</th>
    <th style="text-align:center;">DESCRIPCIÓN <span>( Limite 5 Items )</span></th>
    <th>FLETE</th>
    <th style="text-align:center;">CARRERA</th>
    <th style="text-align:center;">TOTAL</th>
  </tr>
</table>
<div id="Div_List_Items"> 
<table width="725" border="0">
    <tr>
        <td width="108"></td>
        <td></td>
        <td width="54"></td>
        <td width="72"></td>
        <td width="56"></td>
    </tr>
    <tr>
        <th colspan="4" style="text-align:right;"><span>Total</span></th>
        <th style="text-align:right;"><span>0.00</span></th>
    </tr>
</table>
</div>
<!-- FIN: FACTURA -->
<?PHP	
	}
	elseif ($TipoDoc == 'GUIA REMISION')
	{
?>
<!-- INICIO: GUIA DE REMISION -->
<table>
  <tr>
    <td colspan="6" style="height:10px;">
    	<span style="margin-left:150px;">R.U.C.</span>
        <span class="advertisement" style="margin-left:150px; text-decoration:blink; font-size:24px;">Apellidos</span>
        <span style="margin-left:30px;">,</span>
        <span class="advertisement" style="margin-left:80px;">Nombres</span>        </td>
  </tr>
  <tr>
    <th width="144"><span>*</span>Remite : </th>
<td colspan="5">
        <input type="hidden" id="txt_remit_hidden" name="txt_remit_ID" />
        <input name="txt_remit_dni" type="text" id="txt_remit_dni" tabindex="6" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Remitente.&#10;Este dato es requerido." class="input_documento" autocomplete="off" onblur="getClientData('GUIA REMISION', 'REMITENTE');" maxlength="11" />
    -        
        <input type="text" value="" name="txt_remit" id="txt_remit" class="input_nombres" title="Apelldios del Remitente." tabindex="7" onkeypress="return acceptletras(this, event);" onkeyup="ajax_showOptions(this,'getPersonByLetters',event, 'EMPRESA');" autocomplete="off" onfocus="this.select();" onblur="getClientDataById('GUIA REMISION','REMITENTE');" /></td>
  </tr>
    <tr>
    <th><span>*</span>Direcci&oacute;n : </th>
    <td colspan="5"><input type="text" name="txt_remit_direccion" id="txt_remit_direccion" class="input_direccion" title="Direcci&oacute;n." tabindex="8" onkeypress="return handleEnter(this, event);" onfocus="this.select();" /></td>
  </tr>

  <tr>
    <th><span>*</span>Consignado : </th>
    <td colspan="5">
        <input type="hidden" id="txt_consig_hidden" name="txt_consig_ID" />
        <input name="txt_consig_dni" type="text" id="txt_consig_dni" tabindex="9" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" title="D.N.I. del Consignatario.
Este dato no es requerido." class="input_documento" autocomplete="off" onblur="getClientData('GUIA REMISION', 'CONSIGNATARIO');" maxlength="11" /> 
        -    
        <input type="text" value="" name="txt_consig" id="txt_consig" class="input_nombres" title="Apellidos del Consignatario." tabindex="10" onkeypress="return acceptletras(this, event)" onkeyup="ajax_showOptions(this,'getPersonByLetters',event, 'EMPRESA')" autocomplete="off" onfocus="this.select(); ajax_showOptions(this,'getPersonByLetters',event,'EMPRESA_FRECUENTES');" onblur="getClientDataById('GUIA REMISION','CONSIGNATARIO');" /></td>
  </tr>
  <tr>
    <th><span>*</span>Direcci&oacute;n : </th>
    <td colspan="5">
    <input name="cbox_carrera" type="checkbox" value="1" tabindex="11" onkeypress="return handleEnter(this, event);" onClick="if(this.checked == true) {document.getElementById('txt_consig_direccion').readOnly = false; document.getElementById('txt_consig_direccion').select(); document.getElementById('txt_consig_direccion').focus();} else { document.getElementById('txt_consig_direccion').readOnly = true; }" />
     CON CARRERA ?</label>&nbsp;&nbsp;
     <input type="text" value="AGENCIA" name="txt_consig_direccion" id="txt_consig_direccion" class="input_direccion" title="Direcci&oacute;n." tabindex="12" onkeypress="return handleEnter(this, event);" onfocus="this.select();" /></td>
  </tr>
  <!--<tr>
    <th><span>*</span>Clave : </th>
    <td colspan="4"><input type="password" value="" name="txt_clave" id="txt_clave" class="field" title="Clave de Seguridad." tabindex="13"  style="width:150px; text-transform:uppercase; font-size:16px; font-weight:bold;" onfocus="this.select()" maxlength="4" onkeyup="extractNumber(this,2,false);" onkeypress = "return handleEnter(this, event);" onblur="jsf_Empty_Clave(this);" /></td>
  </tr>-->
  <tr>
    <th style="text-align:center;"><input type="text" name="txt_cant" id="txt_cant" class="input_cantidad" tabindex="13" onkeyup="extractNumber(this,0,false);" onkeypress="return handleEnter(this,event);" value="1" onfocus="this.select();" /></th>
    <td width="341"><input type="text" value=""  name="txt_descripcion" id="txt_descripcion" class="input_descripcion" title="Descripcion" tabindex="14" onkeypress="return acceptletras_descripcion2(this, event);" onfocus="this.select();" onkeyup="ajax_showOptions(this,'getDescripcion',event, 'DESCRIPCION');" /></td>
    <td width="144"><input type="text" value="0.00" name="txt_flete2" id="txt_flete" class="input_importe" title="Direcci&oacute;n." tabindex="15" onkeypress="return handleEnter(this, event);" onfocus="this.select();" onkeyup="extractNumber(this,2,false);" /></td>
    <td width="144" style="text-align:center;"><input type="text" value="0.00" name="txt_carrera" id="txt_carrera" class="input_importe" title="Direcci&oacute;n." tabindex="16" onkeypress="return E_Insert_Temp(this, event);" onkeyup="extractNumber(this,2,false);" onfocus="this.select();" /></td>
    <td width="57">&nbsp;</td>
  </tr>
  <tr>
    <th>CANT</th>
    <th style="text-align:center;">DESCRIPCIÓN <span>( Limite 5 Items )</span></th>
    <th>FLETE</th>
    <th style="text-align:center;">CARRERA</th>
    <th style="text-align:center;">IMPORTE</th>
  </tr>
</table>
<div id="Div_List_Items"> 
<table width="725" border="0">
    <tr>
        <td width="108"></td>
        <td></td>
        <td width="54"></td>
        <td width="72"></td>
        <td width="56"></td>
    </tr>
    <tr>
        <th colspan="4" style="text-align:right;"><span>Total</span></th>
        <th style="text-align:right;"><span>0.00</span></th>
    </tr>
</table>
</div>

<!-- FIN: DE REMISION -->
<?PHP	
}
elseif ($TipoDoc == 'GUIA TRANSBORDO')
{
?>

<!-- INICIO: GUIA TRANSBORDO /BV -->
<table>
  <tr>
    <td colspan="6" style="height:10px; text-align:center">
      <input type="hidden" id="txt_id_movimiento" name="txt_id_movimiento" />
      <input type="radio" name="documento" value="boleta" tabindex="6" onkeypress="return handleEnter(this, event);"  checked > 
      <span class="advertisement" style=" text-decoration:blink; font-size:20px;">BOLETA</span>
      <input type="radio" onkeypress="return handleEnter(this, event);"  name="documento" value="factura"  style="margin-left:150px;" tabindex="7" > 
      <span class="advertisement" style=" text-decoration:blink; font-size:20px;">FACTURA</span>
    </td>
  </tr>
  <tr>
    <td colspan="6" style="height:10px; text-align:center;">
    	<span  class="advertisement" style="margin:0px 10px 0px 0px ;">*Serie</span> 
      <input name="serie_ing" id="serie_ing" type="text" tabindex="8" title="Número de Serie" style="width:90px; font-size:140%; 
      color:#FF0000; font-weight:bold; text-align:center;" onkeypress="return handleEnter(this, event);" 
      onkeyup="extractNumber(this,0,false);" onfocus="this.select()"/> 
  
      <span class="advertisement" style="margin-left:20px;  ">*Número </span>
      <input name="numero_ing" value= "" id="numero_ing" type="text" class="field" tabindex="9" title="Número del Documento."
      style=" font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" onkeypress="return Get_Trasbordo(event, this); " 
      onkeyup="extractNumber(this,0,false);" onfocus="this.select()" />
      
    </td>
  </tr>
</table>
<div id='doc_transbordo'></div>
<!-- FIN: DE TRANSBORDO -->
<?PHP
	}
?>
