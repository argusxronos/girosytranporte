<?php
	//CONEXION CON EL SERVIDOR
	require_once 'cnn/config_trans.php';
	// OBTENEMOS LOS DATOS DE LAS FLOTAS
	$sql = "SELECT `id_bus`, CAST(`flota` AS DECIMAL(6)) AS 'FLOTA'
	FROM `bus`
	GROUP BY `flota`
	ORDER BY 2 ASC;";
	// REALIZAMOS LA CONSULTA A LA BD
	$db_transporte->query($sql);
	$BUS_Array = $db_transporte->get();
?>

<!-- B.1 MAIN CONTENT -->
<div class="main-content"><br />
	<!-- Pagetitle -->
	<h1 class="pagetitle">Liquidaci&oacute;n de Encomienda</h1>
    <!-- Content unit - One column -->
    <!--<h1 class="block">Lista de Agencias Interconectadas al Nuevo Sistema.</h1>-->
    <div class="column1-unit">
    <div class="contactform">
      <form action="./e_liquidacion_action.php" method="POST" name="frm_busqueda" onsubmit="selectAllOptions('list_liquidacion');">
        <table width="100%" border="0">
          <tr>
            <th><span>*</span>Fecha : </th>
            <td><input name="txt_fecha" id="txt_fecha" type="text" value="<?php echo date('d\/m\/Y'); ?>" title="Fecha de envio de la Encomienda." readonly style="width:150px;" onkeypress="return handleEnter(this, event)" />
                <input type="button" value="Cal" class="button" onclick="displayCalendar(document.forms[0].txt_fecha,'dd/mm/yyyy',this)" style="width:54px;" onkeypress="return handleEnter(this, event)" /></td>
            <th>Hora :</th>
            <td><input type="text" value="<?php echo date('H\:i'); ?>" readonly="readonly" name="txt_hora" class="field" onkeypress="return handleEnter(this, event)" /></td>
          </tr>
          <tr>
            <th><span>*</span>Agencia Destino :</th>
            <td><select name="cmb_agencia_destino" id="cmb_agencia_destino" style="width:200px" tabindex="1" onkeypress="return handleEnter(this, event)" title="Seleccione la Agencia de destino Final." >
                <?php
                        if (count($Oficina_Array) == 0)
                        {
                            echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
                        }
                        else
                        {
                            for ($fila = 0; $fila < count($Oficina_Array); $fila++)
                            {
                                    echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
                            }
                        }
                    ?>
            </select></td>
            <th><span>*</span>Num. Guia : </th>
            <td>
                <input type="text" name="txt_num_liquidacion" tabindex="2" class="field" onkeypress="return handleEnter(this, event)" title="N&uacute;mero de Liquidaci&oacute;n de Encomienda" onkeyup="extractNumber(this,0,false);"  ></td>
          </tr>
          </tr>
          <tr>
            <th><span>*</span>Chofer :</th>
            <td><input type="hidden" id="txt_driver_hidden" name="txt_driver_ID" />
                <input type="text" name="txt_driver" id="txt_driver" class="field" style="width:300px; text-transform:uppercase;" title="Apelldios y Nombres del Chofer." tabindex="3" onkeypress="return acceptletras(this, event);" onkeyup="ajax_showOptions(this,'getDriverByLetters',event);" autocomplete="off" onfocus="this.select();" /></td>
            <th><span>*</span>Flota :</th>
            <td><select name="list_flota" id="select" class="combo" onkeypress="return handleEnter(this, event)" tabindex="4">
                <?php
					if (count($BUS_Array)>0)
					{
						echo '<option value="0">[ Seleccione Flota ]</option>';
						for($fila = 0; $fila < count($BUS_Array); $fila++)
						{
							echo '<option value="'.$BUS_Array[$fila][1].'">'.$BUS_Array[$fila][1].'</option>';
						}
					}
					else
					{
						echo '<option value="0">[ Sin Registros ]</option>';
					}
				?>
              </select>
            </td>
          </tr>
          <tr>
            <th><span>*</span>Comisi&oacute;n :</th>
            <td><input type="text" name="txt_comision" id="txt_comision" class="field" value="8" onkeypress="return handleEnter(this, event)" tabindex="5" style="width:50px;" onkeyup="extractNumber(this,0,false);" /> 
              (%)</td>
            <th>Tipo Liquidaci&oacute;n:</th>
            <td><input name="txt_codigo" id="txt_codigo" type="hidden" value="<?php echo ('E' .$_SESSION['ID_OFICINA'] .rand(2000000000,9999999999)); ?>" />
              <label>
              <select name="cmd_tipo_liq" id="cmd_tipo_liq" class="combo" tabindex="6" onkeypress="return handleEnter(this, event)" onchange="ChangeTipoLiquidacion(this.form.list_liquidacion);" >
              	<option value="1" selected="selected">Peque&ntilde;a</option>
                <option value="2">Grande</option>
              </select>
            </label></td>
          </tr>
        </table>
        <table width="100%" border="0">
          <tr height="20">    
                <th width="47%" style="text-align:center; font-size:16px;">Oficinas</th>
                <th width="6%">&nbsp;</th>
                <th width="47%" style="text-align:center; font-size:16px;"><span>*</span>Lista para la Liquidaci&oacute;n</th>
          </tr>
          <tr>
            <td><SELECT Name="list_Oficinas" id="list_Oficinas" size="10" multiple style="width:100%" tabindex="7" onkeypress="return handleEnter(this, event)">
                    <?php
                        if (count($Oficina_Array) == 0)
                        {
                            echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
                        }
                        else
                        {
                            for ($fila = 0; $fila < count($Oficina_Array); $fila++)
                            {
                                    echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
                            }
                        }
                    ?>
                </SELECT>            </td>
            <td style="text-align:center;">
            <input name="btn_Add" type="button" value=" >> " onclick="moveOptions(this.form.list_Oficinas, this.form.list_liquidacion);" tabindex="8" />
            <br/>
            <input tabindex="9" name="btn_Remove" type="button" value=" << " onclick="moveOptions(this.form.list_liquidacion, this.form.list_Oficinas);" /></td>
            <td><SELECT Name="list_liquidacion[]" id="list_liquidacion" size="10" multiple style="width:100%" tabindex="10"> 
                </SELECT> </td>
          </tr>
          <tr>
          	<td colspan="3" style="text-align:left;"><span>Limite de Lineas por Tipo de Liquidaci&oacute;n:<br />Peque&ntilde;a: 22 Lineas<br />Grande: 48 Lineas</span></td>
          </tr>
          <tr>
          	<td colspan="3" style="text-align:center;"><input type="button" name="btn_mostrar_lista" id="btn_mostrar_lista" class="button" value="Mostrar Liquidaci&oacute;n" tabindex="11" style="width:250px;" onclick="OptionsList(this.form.list_liquidacion);"/></td>
          </tr>
        </table>
<div id="div_list_encomiendas">
        <table border="0">
          <tr>
          	<th style="width:10px; text-align:center;">#</th>
            <th style="width:50px; text-align:center;"># GUIAS</th>
            <th style="width:295px;">CONSIGNATARIO</th>
            <th style="width:220px; text-align:center;">CONTENIDO DE LA GUIA</th>
            <th style="width:80px; text-align:center;">CARRERA</th>
            <th style="width:80px; text-align:center;">VALOR</th>
            <th style="width:90px; text-align:center;">ACCI&Oacute;N</th>
          </tr>
          <tr>
            <td colspan="7" style="text-align:center;"><span>No hay registro de encomiendas</span></td>
          </tr>
        </table>   
</div>
		<table width="100%" border="0">
           <tr>
                <th height="30" colspan="4" style="text-align:center;" scope="row"><span>
              <input type="submit" name="btn_print" id="btn_print" class="button" value="Guardar Liquidaci&oacute;n" tabindex="6" style="width:250px;"/>
            </span></th>
           </tr>
        </table>
    </form>
  </div>
  </div>
  <hr class="clear-contentunit" />
</div>
