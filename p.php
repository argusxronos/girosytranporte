<?php
session_start();
// Obtenemos los datos de la tabla PAGES
if(!isset($_SESSION['OFICINAS']))
{
  $db_transporte->query("SELECT oficinas.`idoficina`,oficinas.`oficina`
  FROM `oficinas` AS oficinas ORDER BY oficinas.`oficina`");
  $_SESSION['OFICINAS'] = $db_transporte->get();
}
$listOficinas = $_SESSION['OFICINAS'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <!-- Hoja de Estilos -->
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_setup.css" />
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_text.css" />
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
  <title>.::Venta Pasajes::.</title>
  <script language="javascript" src="js/turismoJS.js"> </script>
  <!-- Links para el calendario -->
  <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
  <SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118">
  </script>
  <!-- Links para el calendario -->
  <script type="text/javascript" src="js/json.js"></script>
  <script type="text/javascript" src="js/motorAjax.js"></script>
</head>
<!-- Global IE fix to avoid layout crash when single word size wider than column width -->
<!--[if IE]><style type="text/css"> body {word-wrap: break-word;}</style><![endif]-->
<body>
  <div id="divBloqueador"></div>
  <!-- START Main Page Container -->
  <div class="page-container">

   <!-- For alternative headers START PASTE here -->

    <!-- START A. HEADER -->
	<?php include_once('header.php'); ?>
	<!-- END A. HEADER -->

   <!-- For alternative headers END PASTE here -->

    <!-- START B. MAIN -->
    <div class="main">
      <div class="main-content">
        <h1 class="pagetitle">Venta Pasajes</h1>
        <div class="column1-unit">
          <form method="get" action="e_entrega.php" name="buscar_encomienda"  >
            <table width="100%" border="0">
              <tr>
                <td>Tipo :</td>
                <td><select id="cmbTipoOperacion" name="cmbTipoOperacion" title="Seleccione Tipo de Operaci&oacute;n">
                  <option value="1">Venta</option>
                  <option value="2">Reserva</option>
                  <option value="3">Venta de Otra Agencia</option>
                </select></td>
                <td>Oficina</td>
                <td>
                  <select id="cmbOficina" name="cmbOficina" class="combo" title="Oficina">
                  <?php if(count($listOficinas) > 0): ?>
                  <?php for($fila = 0; $fila < count($listOficinas); $fila++): ?>
                    <option value="<?php echo $listOficinas[$fila][0]; ?>" <?php if ($_SESSION['ID_OFICINA'] == $listOficinas[$fila][0]) echo 'selected = "selected"'; ?>>
                    <?php echo $listOficinas[$fila][1]; ?></option>
                  <?php endfor; ?>
                  <?php endif; ?>
                  </select>
                </td>
                <td>Fecha</td>
                <td><input name="txt_fecha" id="txt_fecha" type="text" value="<?php echo date('d/m/Y')?>" title="Fecha" style="width:100px;" readonly="readonly" >
                    <input type="button" value="Cal" class="button" onClick="displayCalendar(document.forms[0].txt_fecha,'dd/mm/yyyy',this)" style="width:54px;" >
                </td>
              </tr>
              <tr>
                <td colspan="6" style="text-align: center;"><input type="button" id="btnBuscarSalidas" name="btnBuscarSalidas" class="button" value="Buscar" /></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
      <div class="columnBus-unit">
        <div class="columnBus-unit-left" id="columnBus-unit-left">
          <table>
            <tr>
              <th>HORA</th>
              <th>Destino</th>
              <th style="width:50px;">FLOTA</th>
            </tr>
            <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
              <td colspan="3">NO SE A REALIZADO LA BUSQUEDA</td>
            </tr>
          </table>
        </div>
        <div class="columnBus-unit-right" id="columnBus-unit-right">
          <h1 style="text-align:center;margin:2px 0 10px 0;">Seleccione Asiento</h1>
          <div class="unidad_left">


            <h3 style="text-align:center;margin:10px 0 20px 0;">PRIMER PISO</h3>
            <!-- primera columna 4-->
            <table>
              <tr>
                <td onclick="operaionTransporte()">
                  <div id="tripulacion">
                    &nbsp;
                  </div>
                </td>                        
                <td>
                  <div id="libre">
                    <span>44</span>
                  </div>
                </td>               
                <td>
                  <div id="vendidoM">
                    <span>44</span>
                  </div>
                </td>                
                <td>
                  <div id="vendidoF">
                    <span>44</span>
                  </div>
                </td>                
                <td>
                  <div id="reservado">
                    <span>44</span>
                  </div>
                </td>              
              </tr>
              <tr>
                <td>
                  <div id="tripulacion">
                    &nbsp;
                  </div>
                </td>                        
                <td>
                  <div id="tv">
                    &nbsp;
                  </div>
                </td>               
                <td>
                  <div id="banio">
                    <span>44</span>
                  </div>
                </td>                
                <td>
                  <div id="escalera">
                    &nbsp;
                  </div>
                </td>                
                <td>
                  <div id="escalera">
                    &nbsp;
                  </div>
                </td>              
              </tr>
            </table>


          </div>
          <div class="unidad_right">

            <h3 style="text-align:center;margin:10px 0 20px 0;">SEGUNDO PISO</h3>
            <!-- primera columna 4-->
            <table>
              <tr>
                <td onclick="operaionTransporte()">
                  <div id="tripulacion">
                    &nbsp;
                  </div>
                </td>                        
                <td>
                  <div id="libre">
                    <span>44</span>
                  </div>
                </td>               
                <td>
                  <div id="vendidoM">
                    <span>44</span>
                  </div>
                </td>                
                <td>
                  <div id="vendidoF">
                    <span>44</span>
                  </div>
                </td>                
                <td>
                  <div id="reservado">
                    <span>44</span>
                  </div>
                </td>              
              </tr>             
              </tr>
              <tr>
                <td onclick="operaionTransporte()">
                  <div id="tripulacion">
                    &nbsp;
                  </div>
                </td>                        
                <td>
                  <div id="reservado">
                    <span>44</span>
                  </div>
                </td>               
                <td>
                  <div id="reservaPagada">
                    <span>44</span>
                  </div>
                </td>                
                <td>
                  <div id="reservaPagada">
                    <span>44</span>
                  </div>
                </td>                
                <td>
                  <div id="reservado">
                    <span>44</span>
                  </div>
                </td>              
              </tr>             
              </tr>
            </table>


          </div>
        </div>
      </div>
    </div>
	<!-- END B. MAIN -->
      
    <!-- START C. FOOTER AREA -->
    <?php include_once('footer.php'); ?>
	<!-- END C. FOOTER AREA -->
	      
  </div> 
  <!-- END Main Page Container -->
  <!-- FORMULARIO DE INGRESO DE DATOS -->
  <div id="divVenta" class="oculto"><!-- -->
      <h2>NUEVO PASAJE</h2>
      <hr />
      <form action="persona_ingreso.php" method="post" name="form_IngPersona" id="form_IngPersona">
        <fieldset>
          <legend>Venta de Pasajes</legend>
          <table>
            <tr>
              <td style="width:70px;">ORIGEN:</td>
              <td colspan="2"><input type="text" id="txtOrigen" class="inputLargo" readonly="true" /></td>
              <td style="width:105px;text-align:right;padding-right:15px;">DESTINO:</td>
              <td colspan="2"><input type="text" id="txtDestino" class="inputLargo" readonly="true" /></td>
              <td colspan="4" style="text-align: center;">BOLETO</td>
            </tr>
            <tr>
              <td>FECHA:</td>
              <td><input type="text" id="txtFechaViaje" class="inputLargo" readonly="true" /></td>
              <td></td>
              <td style="width:105px;text-align:right;padding-right:15px;">HORA:</td>
              <td><input type="text" id="txtHora" class="inputLargo" readonly="true" /></td>
              <td></td>
              <td>Nro:</td>
              <td><input type="text" id="txtSerie" class="inputSerie" readonly="true" /></td>
              <td>-</td>
              <td><input type="text" id="txtBoleto" class="inputGuia" readonly="true" /></td>
            </tr>
            <tr>
              <td>PISO:</td>
              <td colspan="2">
                <input type="text" id="txtPiso" class="inputPequenio" readonly="true" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ASIENTO:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="txtAsiento" class="inputPequenio" readonly="true" />
              </td>
              <td></td>
              <td></td>
              <td colspan="5" style="padding-left:10px;"><input id="chboxImprimir" type="checkbox" value="1" checked="checked" />&nbsp;&nbsp;Imprimir Boleto?</td>
            </tr>
          </table>
        </fieldset>
        <hr />
        <fieldset>
          <legend>Detalle del Cliente</legend>
          
          <table>
            <tr>
              <td>Nombre o DNI:<input type="field" id="txtBusqueda_hidden" name="txtBusqueda_hidden" class="inputSerie" readonly="true" />
              <input type="text" id="txtBusqueda" class="fieldBusqueda" onkeyup="ajax_showOptions(this,'getPersonByLetters',event);" autocomplete="off" /></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </table>
          <hr />
          <p class="right_text parrafoCerrar">
          <a id="linkCerrar_venta" href="#"><img src="./img/operacion/remove.png"
          width="32" height="32" alt="Cerrar" title="Cerrar"  /></a></p>
          <input name="send" id="send" type="submit" class="submit"  tabindex="25" value="Guardar" />
          <input type="reset" class="reset" value="Limpiar" />
        </fieldset>
      </form>
  </div>

  <!-- FORMULARIO DE INGRESO DE DATOS -->
  <div id="divReserva" class="oculto">
      <form action="persona_ingreso.php" method="post" name="form_IngPersona" id="form_IngPersona">
          <fieldset>
              <legend>Reserva de Pasajes</legend>
              <div class="claveValor">
        <label for="txt_codigo">C&oacute;digo:</label>
        <input type="text" name="txt_codigo" id="txt_codigo" class="pequenio" disabled="disabled" />
      </div>
              <div class="claveValor">
        <label for="txt_nombre">Nombre: *</label>
        <input type="text" name="txt_nombre" id="txt_nombre" maxlength="250" class="campoMedioNormal" tabindex="20" />
      </div>
              <div class="claveValor">
        <label for="txt_dni">DNI: *</label>
        <input type="text" name="txt_dni" id="txt_dni" maxlength="8" class="madioPequenio" tabindex="21" />
      </div>
              <div class="claveValor">
        <label for="txt_direccion">Direcci&oacute;n: </label>
        <input type="text" name="txt_direccion" id="txt_direccion" maxlength="250" class="campoMedioNormal" tabindex="22" />
      </div>
              <div class="claveValor">
        <label for="txt_detalle">Detalle: </label>
        <input type="text" name="txt_detalle" id="txt_detalle" maxlength="250" class="campoNormal" tabindex="23" />
      </div>
      <div class="claveValor">
                  <label for="cmb_estado">Estado: </label>
                  <select id="cmb_estado" name="cmb_estado" class="campoNormal" tabindex="24">
                    <option value="0">Inactivo</option>
                    <option value="1" selected="selected">Activo</option>
                  </select>
      </div>
      <hr />
            <p class="right_text parrafoCerrar">
              <a id="linkCerrar_reserva" href="#"><img src="imagenes/maquetado/remove.png"
              width="32" height="32" alt="Cerrar" title="Cerrar"  /></a></p>
              <input name="send" id="send" type="submit" class="submit"  tabindex="25" value="Guardar" />
              <input type="reset" class="reset" value="Limpiar" />
          </fieldset>
      </form>
  </div>

  <!-- FORMULARIO DE INGRESO DE DATOS -->
  <div id="divDerivada" class="oculto">
      <form action="persona_ingreso.php" method="post" name="form_IngPersona" id="form_IngPersona">
          <fieldset>
              <legend>Venta Otra Agencia</legend>
              <div class="claveValor">
        <label for="txt_codigo">C&oacute;digo:</label>
        <input type="text" name="txt_codigo" id="txt_codigo" class="pequenio" disabled="disabled" />
      </div>
              <div class="claveValor">
        <label for="txt_nombre">Nombre: *</label>
        <input type="text" name="txt_nombre" id="txt_nombre" maxlength="250" class="campoMedioNormal" tabindex="20" />
      </div>
              <div class="claveValor">
        <label for="txt_dni">DNI: *</label>
        <input type="text" name="txt_dni" id="txt_dni" maxlength="8" class="madioPequenio" tabindex="21" />
      </div>
              <div class="claveValor">
        <label for="txt_direccion">Direcci&oacute;n: </label>
        <input type="text" name="txt_direccion" id="txt_direccion" maxlength="250" class="campoMedioNormal" tabindex="22" />
      </div>
              <div class="claveValor">
        <label for="txt_detalle">Detalle: </label>
        <input type="text" name="txt_detalle" id="txt_detalle" maxlength="250" class="campoNormal" tabindex="23" />
      </div>
      <div class="claveValor">
                  <label for="cmb_estado">Estado: </label>
                  <select id="cmb_estado" name="cmb_estado" class="campoNormal" tabindex="24">
                    <option value="0">Inactivo</option>
                    <option value="1" selected="selected">Activo</option>
                  </select>
      </div>
      <hr />
            <p class="right_text parrafoCerrar">
              <a id="linkCerrar_derivada" href="#"><img src="imagenes/maquetado/remove.png"
              width="32" height="32" alt="Cerrar" title="Cerrar"  /></a></p>
              <input name="send" id="send" type="submit" class="submit"  tabindex="25" value="Guardar" />
              <input type="reset" class="reset" value="Limpiar" />
          </fieldset>
      </form>
  </div>

  <!-- FORMULARIO DE INGRESO DE DATOS -->
  <div id="divMensaje" class="oculto">
      <form action="persona_ingreso.php" method="post" name="form_IngPersona" id="form_IngPersona">
          <fieldset>
              <legend>Datos - Persona</legend>
              <div class="claveValor">
        <label for="txt_codigo">C&oacute;digo:</label>
        <input type="text" name="txt_codigo" id="txt_codigo" class="pequenio" disabled="disabled" />
      </div>
              <div class="claveValor">
        <label for="txt_nombre">Nombre: *</label>
        <input type="text" name="txt_nombre" id="txt_nombre" maxlength="250" class="campoMedioNormal" tabindex="20" />
      </div>
              <div class="claveValor">
        <label for="txt_dni">DNI: *</label>
        <input type="text" name="txt_dni" id="txt_dni" maxlength="8" class="madioPequenio" tabindex="21" />
      </div>
              <div class="claveValor">
        <label for="txt_direccion">Direcci&oacute;n: </label>
        <input type="text" name="txt_direccion" id="txt_direccion" maxlength="250" class="campoMedioNormal" tabindex="22" />
      </div>
              <div class="claveValor">
        <label for="txt_detalle">Detalle: </label>
        <input type="text" name="txt_detalle" id="txt_detalle" maxlength="250" class="campoNormal" tabindex="23" />
      </div>
      <div class="claveValor">
                  <label for="cmb_estado">Estado: </label>
                  <select id="cmb_estado" name="cmb_estado" class="campoNormal" tabindex="24">
                    <option value="0">Inactivo</option>
                    <option value="1" selected="selected">Activo</option>
                  </select>
      </div>
      <hr />
            <p class="right_text parrafoCerrar">
              <a id="linkCerrar" href="#"><img src="imagenes/maquetado/remove.png"
              width="32" height="32" alt="Cerrar" title="Cerrar"  /></a></p>
              <input name="send" id="send" type="submit" class="submit"  tabindex="25" value="Guardar" />
              <input type="reset" class="reset" value="Limpiar" />
          </fieldset>
      </form>
  </div>

  <!-- FORMULARIO DE INGRESO DE DATOS -->
  <div id="divDetalle" class="oculto">
      <form action="persona_ingreso.php" method="post" name="form_IngPersona" id="form_IngPersona">
          <fieldset>
              <legend>Detalle de la Venta</legend>
              <div class="claveValor">
        <label for="txt_codigo">C&oacute;digo:</label>
        <input type="text" name="txt_codigo" id="txt_codigo" class="pequenio" disabled="disabled" />
      </div>
              <div class="claveValor">
        <label for="txt_nombre">Nombre: *</label>
        <input type="text" name="txt_nombre" id="txt_nombre" maxlength="250" class="campoMedioNormal" tabindex="20" />
      </div>
              <div class="claveValor">
        <label for="txt_dni">DNI: *</label>
        <input type="text" name="txt_dni" id="txt_dni" maxlength="8" class="madioPequenio" tabindex="21" />
      </div>
              <div class="claveValor">
        <label for="txt_direccion">Direcci&oacute;n: </label>
        <input type="text" name="txt_direccion" id="txt_direccion" maxlength="250" class="campoMedioNormal" tabindex="22" />
      </div>
              <div class="claveValor">
        <label for="txt_detalle">Detalle: </label>
        <input type="text" name="txt_detalle" id="txt_detalle" maxlength="250" class="campoNormal" tabindex="23" />
      </div>
      <div class="claveValor">
                  <label for="cmb_estado">Estado: </label>
                  <select id="cmb_estado" name="cmb_estado" class="campoNormal" tabindex="24">
                    <option value="0">Inactivo</option>
                    <option value="1" selected="selected">Activo</option>
                  </select>
      </div>
      <hr />
            <p class="right_text parrafoCerrar">
              <a id="linkCerrar_derivada" href="#"><img src="imagenes/maquetado/remove.png"
              width="32" height="32" alt="Cerrar" title="Cerrar"  /></a></p>
              <input name="send" id="send" type="submit" class="submit"  tabindex="25" value="Guardar" />
              <input type="reset" class="reset" value="Limpiar" />
          </fieldset>
      </form>
  </div>
  <!-- FORMULARIO DE INGRESO DE DATOS -->
  <div id="divMensaje" class="oculto">
      <form action="persona_ingreso.php" method="post" name="form_IngPersona" id="form_IngPersona">
          <fieldset>
              <legend>Datos - Persona</legend>
              <div class="claveValor">
        <label for="txt_codigo">C&oacute;digo:</label>
        <input type="text" name="txt_codigo" id="txt_codigo" class="pequenio" disabled="disabled" />
      </div>
              <div class="claveValor">
        <label for="txt_nombre">Nombre: *</label>
        <input type="text" name="txt_nombre" id="txt_nombre" maxlength="250" class="campoMedioNormal" tabindex="20" />
      </div>
              <div class="claveValor">
        <label for="txt_dni">DNI: *</label>
        <input type="text" name="txt_dni" id="txt_dni" maxlength="8" class="madioPequenio" tabindex="21" />
      </div>
              <div class="claveValor">
        <label for="txt_direccion">Direcci&oacute;n: </label>
        <input type="text" name="txt_direccion" id="txt_direccion" maxlength="250" class="campoMedioNormal" tabindex="22" />
      </div>
              <div class="claveValor">
        <label for="txt_detalle">Detalle: </label>
        <input type="text" name="txt_detalle" id="txt_detalle" maxlength="250" class="campoNormal" tabindex="23" />
      </div>
      <div class="claveValor">
                  <label for="cmb_estado">Estado: </label>
                  <select id="cmb_estado" name="cmb_estado" class="campoNormal" tabindex="24">
                    <option value="0">Inactivo</option>
                    <option value="1" selected="selected">Activo</option>
                  </select>
      </div>
      <hr />
            <p class="right_text parrafoCerrar">
              <a id="linkCerrar" href="#"><img src="imagenes/maquetado/remove.png"
              width="32" height="32" alt="Cerrar" title="Cerrar"  /></a></p>
              <input name="send" id="send" type="submit" class="submit"  tabindex="25" value="Guardar" />
              <input type="reset" class="reset" value="Limpiar" />
          </fieldset>
      </form>
  </div>

  <!-- FORMULARIO DE INGRESO DE DATOS -->
  <div id="divSelectCliente" class="oculto">
      <form action="persona_ingreso.php" method="post" name="form_IngPersona" id="form_IngPersona">
          <fieldset>
              <legend>Detalle de la Venta</legend>
              <div class="claveValor">
        <label for="txt_codigo">C&oacute;digo:</label>
        <input type="text" name="txt_codigo" id="txt_codigo" class="pequenio" disabled="disabled" />
      </div>
              <div class="claveValor">
        <label for="txt_nombre">Nombre: *</label>
        <input type="text" name="txt_nombre" id="txt_nombre" maxlength="250" class="campoMedioNormal" tabindex="20" />
      </div>
              <div class="claveValor">
        <label for="txt_dni">DNI: *</label>
        <input type="text" name="txt_dni" id="txt_dni" maxlength="8" class="madioPequenio" tabindex="21" />
      </div>
              <div class="claveValor">
        <label for="txt_direccion">Direcci&oacute;n: </label>
        <input type="text" name="txt_direccion" id="txt_direccion" maxlength="250" class="campoMedioNormal" tabindex="22" />
      </div>
              <div class="claveValor">
        <label for="txt_detalle">Detalle: </label>
        <input type="text" name="txt_detalle" id="txt_detalle" maxlength="250" class="campoNormal" tabindex="23" />
      </div>
      <div class="claveValor">
                  <label for="cmb_estado">Estado: </label>
                  <select id="cmb_estado" name="cmb_estado" class="campoNormal" tabindex="24">
                    <option value="0">Inactivo</option>
                    <option value="1" selected="selected">Activo</option>
                  </select>
      </div>
      <hr />
            <p class="right_text parrafoCerrar">
              <a id="linkCerrar_derivada" href="#"><img src="imagenes/maquetado/remove.png"
              width="32" height="32" alt="Cerrar" title="Cerrar"  /></a></p>
              <input name="send" id="send" type="submit" class="submit"  tabindex="25" value="Guardar" />
              <input type="reset" class="reset" value="Limpiar" />
          </fieldset>
      </form>
  </div>
</body>
</html>