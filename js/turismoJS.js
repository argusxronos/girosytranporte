var divFormularioActual = "";
var vidSalida = 0;
var vDestino = "";

Array.prototype.indexOf = function(s) {
	for (var x=0;x<this.length;x++) if(this[x] == s) return true;	
	return false;
}
String.prototype.trim = function (){
	return this.replace(/^\s*|\s*$/g, "");
}
function trim(stringToTrim) {
	if(stringToTrim != undefined)
		return stringToTrim.replace(/^\s+|\s+$/g,"");
	
}
function ltrim(stringToTrim) {
	return stringToTrim.replace(/^\s+/,"");
}
function rtrim(stringToTrim) {
	return stringToTrim.replace(/\s+$/,"");
}
function checkCampoVacio(){
	if (this === undefined) {
		alert('indefinido');
		return true;
	};
	var newString = this.value.trim();
	return newString.length == 0 ? true : false;
}
function inArray (num) {
	var encontro = false;
	for(var key in this) {
		if (this[key] == num) {
			encontro = true;
			return encontro;
		}
	}
	return encontro;
}
function setFocus() {
	var field = this;
	setTimeout(function(){setFoco(field);},100);
}
function setFoco(field){
	field.select();
	field.focus();
}
function isset(varname){
	if (typeof(window[varname]) != 'undefined') {
		return false;
	};
	return true;
}
/*  ON WINDOWS LOAD */
window.onload = function () {
	
	var url = window.location.href;
	
	if (url.indexOf("p_form_ventas.php") !== -1) 
	{
		var tabCliente = document.getElementById('tabCliente');
		var tabVenta = document.getElementById('tabVenta');
		var tabReserva = document.getElementById('tabReserva');
		var tabVentaOtraAgencia = document.getElementById('tabVentaOtraAgencia');

		var btnVentaPasaje = document.getElementById('btn_guardar_venta');
		var txtDescuento = document.getElementById('txt_descuento');
		
		
		// FUNCION PARA GUARDAR LA VENTA
		btnVentaPasaje.onclick = function () {
			btnVentaPasaje.disabled = 'true';
			btnVentaPasaje.value = 'Enviando...';
			// ENVIAR FORMULARIO
			//document.venta_form.submit();
			return false;
		}
		// DESCUENTOS
		txtDescuento.onkeyup = function(){

			ventaPasaje_calcularDescuento();
			extractNumber(this,2,false);
		}

		tabCliente.onclick = function(){
			
		}
		tabVenta.onclick = function(){
			// VERIFICAR SI SE INGRESO EL CLIENTE
			ventaPasaje_validarCliente(btnVentaPasaje);
			// CARGAMOS LOS DATOS EN LOS CAMPOS OCULTOS
			ventaPasaje_CargarDatos();
			// LLAMAMOS A LA FUNCION QUE CARGA LOS PRECIONS
			precios();
		}

	}
	if (url.indexOf("p.php") !== -1) 
	{
		var btnBuscarSalidas = document.getElementById('btnBuscarSalidas');
		var divBloqueador = document.getElementById('divBloqueador');
		var divIngreso = document.getElementById("divIngresoPequenios");
		var divCerrar = document.getElementById('linkCerrar');
		var divCerrar_venta = document.getElementById('linkCerrar_venta');
		var divCerrar_reserva = document.getElementById('linkCerrar_reserva');
		var divCerrar_venta = document.getElementById('linkCerrar_venta');
		
		var txtBusqueda = document.getElementById('txtBusqueda');

		divBloqueador.onclick = function(){
			// MOSTRAMOS EL FORMULARIO DE INGRESO
			muestraOcultaElemento(divBloqueador);
			muestraOcultaElemento(divFormularioActual);
			return (false);
		}
		btnBuscarSalidas.onclick = function() {
			cargaSalidasTransporte();
		}
		divCerrar.onclick = function () {
			muestraOcultaElemento(divBloqueador);
			muestraOcultaElemento(divFormularioActual);
			return false;
		}
		txtBusqueda.onkeypress= function(event) {
			event = event || window.event;
			return searchCliente(txtBusqueda,event);
		}
	}
}

function ventaPasaje_CargarDatos () {
	//Copiar Datos del Cliente
    var nombres = document.cliente_form.txt_Nombre.value;// extrae el nombre del cliente
    var documentos = document.cliente_form.t_documento.value;// extrae el tipo de documento
    var dni = document.cliente_form.txt_dni.value;// extrae el numero de dni
    var genero = document.cliente_form.genero.value;// extrae el genero del cliente
    var fono = document.cliente_form.n_fono.value;// extrae el telefono del cliente
    var edad = document.cliente_form.txt_edad.value;// extrae la edad del cliente
    var nacionalidad = document.cliente_form.txt_nacionalidad.value;// extrae la nacionalidad del cliente
    var ruc = document.cliente_form.txt_ruc.value; //extrae el numero de ruc
    var razon = document.cliente_form.r_social.value; // extrae la razon social del cliente
    //Copiar datos de Salida de buses
    var origen = document.form_ventas_pasajes.txt_origen.value; // extrae el origen de la salida
    var destino_final = document.form_ventas_pasajes.txt_destino.value; //extrae en destino de la salida
    var fecha_viaje = document.form_ventas_pasajes.txt_fecha.value; //extrae la fecha de viaje
    var hora_viaje = document.form_ventas_pasajes.txt_hora.value; // extrae la hora de viaje
    var boleto = document.form_ventas_pasajes.txt_boleto.value; //extrae en numero de boleto
    var serie = document.form_ventas_pasajes.txt_serie_boleto.value; //extrae en numero de boleto
    var piso = document.form_ventas_pasajes.txt_piso.value; //extrae el piso del bus
    var asiento = document.form_ventas_pasajes.txt_asiento.value; //extrae el asiento
    var importe_final = document.form_ventas_pasajes.txt_importe_total.value; //extrae el importe final
    var codigo_salida = document.form_ventas_pasajes.txt_id_salida.value; //extrae el codigo de la salida
    //Mostrar Datos del Cliente para realizar la venta
    document.venta_form.txt_nombre.value = nombres; // muestra en el formulario venta_form el nombre del cliente
    document.venta_form.txt_documento.value = documentos; // muestra en el formulario venta_form el tipo de documento del cliente
    document.venta_form.txt_ndocumento.value = dni; // muestra en el formulario venta_form el numero de dni del cliente
    document.venta_form.txt_genero.value = genero; // muestra en el formulario venta_form el genero del cliente
    document.venta_form.txt_telefono.value = fono; // muestra en el formulario venta_form el telefono del cliente
    document.venta_form.txt_edad.value = edad; // muestra en el formulario venta_form la edad del cliente
    document.venta_form.txt_nacionalidad.value = nacionalidad; // muestra en el formulario venta_form la nacionalidad del cliente
    document.venta_form.txt_ruc.value = ruc; // muestra en el formulario venta_form el numero de ruc del cliente
    document.venta_form.txt_razon.value=razon; // muestra en el formulario venta_form la razon social del cliente
    //Mostrar Datoa de salida para realizar la venta
    document.venta_form.txt_origen.value=origen; //muestra en el formulario venta_form el origen de la salida
    document.venta_form.txt_salida.value=destino_final; //muestra en el formulario venta_form el destino de la salida
    document.venta_form.txt_fecha_viaje.value=fecha_viaje; //muestra en el formulario venta_form la fecha de viaje
    document.venta_form.txt_hora_viaje.value=hora_viaje; //muestra en el formulario venta_form la hora de viaje
    document.venta_form.txt_boleto.value=boleto; //muestra en el formulario venta_form el boleto del viaje
    document.venta_form.txt_serie_boleto.value=serie; //muestra en el formulario venta_form la serie del boleto de viaje
    document.venta_form.txt_piso.value=piso; //muestra en el formulario venta_form el piso del bus
    document.venta_form.txt_asiento.value=asiento; //muestra en el formulario venta_form el asiento del bus
    document.venta_form.txt_importe_final.value=importe_final; //muestra en el formulario venta_form el importe total a pagar
    document.venta_form.txt_codigo_salida.value=codigo_salida; //muestra en el formulario venta_form el codigo de la salida 
}
function ventaPasaje_validarCliente (btnVentaPasaje) {
	var nombres = document.cliente_form.txt_Nombre.value;// extrae el nombre del cliente
    var dni = document.cliente_form.txt_dni.value;// extrae el numero de dni
    if(nombres.length == 0 || dni.length == 0){
    	alert('Debe ingresar los datos del cliente primero.');
    	// DESABILITAMOS EL BOTON
    	btnVentaPasaje.disabled = true;
    }else{
    	btnVentaPasaje.disabled = false;
    }
}
function ventaPasaje_calcularDescuento () {
	var valDescuento = document.venta_form.txt_descuento.value;
	if (parseInt(valDescuento) > 0)
	{
		var valImporte = document.venta_form.txt_importe.value;
		if (parseInt(valDescuento) > parseInt(valImporte)) {
			valDescuento = 0;
			document.venta_form.txt_descuento.value = 0;
			document.venta_form.txt_descuento.select();
		};
		var total = 0;
		total = valImporte - valDescuento;
		document.venta_form.txt_importe_pagar.value = total;
		document.venta_form.txt_importe_pagar_letras.value = covertirNumLetras(
			document.venta_form.txt_importe_pagar.value, 1
		);
		document.form_ventas_pasajes.txt_importe_total.value = total;
	}else{
		document.venta_form.txt_descuento.value = 0;
		document.venta_form.txt_descuento.select();	
		return false;
	}
}
//Funcion que muestra una imagen de carga mientras
//Se procesa la peticion AJAX
function functionLoading(){
	var elementLoading = document.getElementById("columnBus-unit-left");
	var img = '<div  style="text-align:center;"><img src="img/cargando4.gif"';
	img += 'width="30px" height="30px" style="padding:10px 0 10px 125px;border:0px;" title="Cargando..." /></div>';
	elementLoading.innerHTML = img;
}
function functionLoading2()
{
	var elementLoading = document.getElementById("columnBus-unit-right");
	var img = '<div  style="text-align:center;"><img src="img/cargando4.gif"';
	img += 'width="30px" height="30px" style="padding:10px 0 10px 275px;border:0px;" title="Cargando..." /></div>';
	elementLoading.innerHTML = img;
}
function cargaSalidasTransporte ()
{
	var idOficina = document.getElementById('cmbOficina').value;
	var fecha = document.getElementById('txt_fecha').value;
	var aleatorio = Math.floor(Math.random()*10000001);
	var queryString = "?idO="+idOficina+"&fechaBuscar="+fecha+"&r="+aleatorio;
	var serverAddress = "cargarSalidasTransporte.php" + queryString;
	var cargador = new net.CargadorContenidos(serverAddress, actualizaSalidas, 
		false, "GET", false, false, functionLoading); 
}
function actualizaSalidas (){
	var text = this.req.responseText;
	var elementLoading = document.getElementById("columnBus-unit-left");
	var tabla = "<table><thead><tr>";
	tabla += "<th style=\"width:50px;\">HORA</th>";
	tabla += "<th>Destino</th>";
	tabla += "<th style=\"width:50px;\">FLOTA</th>";
	tabla += "</tr></thead><tbody>";
	if (text) {
		//alert(text);
		var oText = JSON.parse(text);
		
		
		var classTr = "claro";
		for (key in oText) {
			if(oText[key].hora != undefined
			&& oText[key].destino != undefined
			&& oText[key].flota != undefined)
			{
				tabla += "<tr onclick=\"muestraConfiguracionBus('"+oText[key].id+"','"+oText[key].destino+"');\" onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
			
				tabla += "<td>"+oText[key].hora+"</td>";
				tabla += "<td>"+oText[key].destino+"</td>";
				tabla += "<td>"+oText[key].flota+"</td>";
				tabla += "</tr>";
			}
			//classTr = (classTr == "claro") ? "oscuro" : "claro";
		}
	}
	else {
		tabla += "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
		tabla += "<td colspan=\"3\">NO HAY SALIDAS</td>";
		tabla += "</tr>";
	}
	tabla += "</tbody></table>";
	elementLoading.innerHTML = tabla;
}
function muestraConfiguracionBus(valIdSalida, valDestino){
	//alert(valIdSalida);
	vidSalida =  valIdSalida;
	vDestino = valDestino;
	var aleatorio = Math.floor(Math.random()*10000001);
	var queryString = "?idS="+valIdSalida+"&r="+aleatorio;
	var serverAddress = "cargarConfiguracionBus.php" + queryString;
	var cargador = new net.CargadorContenidos(serverAddress, actualizaConfiguracionBus, 
		false, "GET", false, false, functionLoading2); 
}
function actualizaConfiguracionBus(){
	var text = this.req.responseText;
	var elementLoading = document.getElementById("columnBus-unit-right");
	var tabla = "<h1 style=\"text-align:center;margin:2px 0 10px 0;color:red;\">"+vDestino+"</h1>";
	tabla += "<div class=\"unidad_left\">";
	tabla += "<h3 style=\"text-align:center;margin:10px 0 20px 0;\">PRIMER PISO</h3>";
	tabla += "<table>";
	tabla += "<tbody>";
	var vPiso = '';
	var vFila = '';
	var c1 = '';
	var c2 = '';
	var c3 = '';
	var c4 = '';
	var c5 = '';
	var divTd = " id=\"libre\"";
	var ec1 = '';
	var ec2 = '';
	var ec3 = '';
	var ec4 = '';
	var ec5 = '';

	var divN1 = "";
	var divN2 = "";
	var divN3 = "";
	var divN4 = "";
	var divN5 = "";
	var error = "";
	try
	{
		if (text) {
			var oText = JSON.parse(text);
			
			//var classTr = "";

			for (key in oText) {
				vPiso = oText[key].piso;
				c1 = trim(oText[key].n1);
				c2 = trim(oText[key].n2);
				c3 = trim(oText[key].n3);
				c4 = trim(oText[key].n4);
				c5 = trim(oText[key].n5);
				ec1 = trim(oText[key].en1);
				ec2 = trim(oText[key].en2);
				ec3 = trim(oText[key].en3);
				ec4 = trim(oText[key].en4);
				ec5 = trim(oText[key].en5);
				if(vPiso == 1){
					tabla += "<tr>";
					tabla += "<td"+setFuncion(c5, ec5, vPiso)+"><div "+setTipoAsiento(c5, ec5)+"><span>"+setTextoAsiento(c5)+"</span></div></td>";
					tabla += "<td"+setFuncion(c4, ec4, vPiso)+"><div "+setTipoAsiento(c4, ec4)+"><span>"+setTextoAsiento(c4)+"</span></div></td>";
					tabla += "<td"+setFuncion(c3, ec3, vPiso)+"><div "+setTipoAsiento(c3, ec3)+"><span>"+setTextoAsiento(c3)+"</span></div></td>";
					tabla += "<td"+setFuncion(c2, ec2, vPiso)+"><div "+setTipoAsiento(c2, ec2)+"><span>"+setTextoAsiento(c2)+"</span></div></td>";
					tabla += "<td"+setFuncion(c1, ec1, vPiso)+"><div "+setTipoAsiento(c1, ec1)+"><span>"+setTextoAsiento(c1)+"</span></div></td>";
					tabla += "</tr>";
					//classTr = (classTr == "claro") ? "oscuro" : "claro";
				}
			}
		}
		else {
			tabla += "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
			tabla += "<td colspan=\"5\">NO HAY ASIENTOS</td>";
			tabla += "</tr>";
		}
		tabla += "</tbody></table>";
		tabla += "</div>";
		tabla += "<div class=\"unidad_right\">";
		tabla += "<h3 style=\"text-align:center;margin:10px 0 20px 0;\">SEGUNDO PISO</h3>";
		tabla += "<table>";
		tabla += "<tbody>";

		if (text) {
			var oText = JSON.parse(text);
			for (key in oText) {
				vPiso = oText[key].piso;
				c1 = trim(oText[key].n1);
				c2 = trim(oText[key].n2);
				c3 = trim(oText[key].n3);
				c4 = trim(oText[key].n4);
				c5 = trim(oText[key].n5);
				ec1 = trim(oText[key].en1);
				ec2 = trim(oText[key].en2);
				ec3 = trim(oText[key].en3);
				ec4 = trim(oText[key].en4);
				ec5 = trim(oText[key].en5);
				if(vPiso == 2){
					tabla += "<tr>";
					tabla += "<td"+setFuncion(c5, ec5, vPiso)+"><div "+setTipoAsiento(c5, ec5)+"><span>"+setTextoAsiento(c5)+"</span></div></td>";
					tabla += "<td"+setFuncion(c4, ec4, vPiso)+"><div "+setTipoAsiento(c4, ec4)+"><span>"+setTextoAsiento(c4)+"</span></div></td>";
					tabla += "<td"+setFuncion(c3, ec3, vPiso)+"><div "+setTipoAsiento(c3, ec3)+"><span>"+setTextoAsiento(c3)+"</span></div></td>";
					tabla += "<td"+setFuncion(c2, ec2, vPiso)+"><div "+setTipoAsiento(c2, ec2)+"><span>"+setTextoAsiento(c2)+"</span></div></td>";
					tabla += "<td"+setFuncion(c1, ec1, vPiso)+"><div "+setTipoAsiento(c1, ec1)+"><span>"+setTextoAsiento(c1)+"</span></div></td>";
					tabla += "</tr>";
					//classTr = (classTr == "claro") ? "oscuro" : "claro";
				}
			}
		}
		else {
			tabla += "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
			tabla += "<td colspan=\"5\">NO HAY ASIENTOS</td>";
			tabla += "</tr>";
		}

		tabla += "</div>";
		elementLoading.innerHTML = tabla;
	}
	catch(err)
	{
		error="There was an error on this page.\n\n";
		error+="Error description: " + err.message + "\n\n";
		error+="Ajax Request: " + text + "\n\n";// ELIMINAR ESTA LINIEA (SOLO PARA DESARROLLO)
		error+="Click OK to continue.\n\n";
		alert(error);
	}
	
}
function setTipoAsiento (valValor, valEstado){
	var divTd = " id=\"libre\"";
	if(valValor == ''){
		divTd = "";
	}
	if(valValor == "TR"){
		divTd = " id=\"tripulacion\"";
	}
	if(valValor == "ES"){
		divTd = " id=\"escalera\"";
	}
	if(valValor == "BA"){
		divTd = " id=\"banio\"";
	}
	if(valValor == "TM" || valValor == "TI" || valValor == "TD"){
		divTd = " id=\"tv\"";
	}
	if(parseInt(valValor) > 0){
		if (valEstado == 1) {
			var divTd = " id=\"vendidoM\"";
		};
		if (valEstado == 2) {
			var divTd = " id=\"vendidoF\"";
		};
		if (valEstado == 9) {
			var divTd = " id=\"reservado\"";
		};
		if (valEstado == 11) {
			var divTd = " id=\"reservaPagada\"";
		};
	}
	return divTd;
}
function setTextoAsiento (valValor){
	if(valValor == "TM" || valValor == "TI" || valValor == "TD" || valValor == "ES" || valValor == "TR" || valValor == "BA"){
		return "";
	}else {
		return valValor;
	}
}
function setFuncion(valAsiento, valEstado, valPiso){
	var varFuncion = " onclick=\"operaionTransporte('"+valAsiento+"', '"+valEstado+"', '"+valPiso+"')\"";
	if(valAsiento == "TM" 
		|| valAsiento == "TI" 
		|| valAsiento == "TD" 
		|| valAsiento == "ES" 
		|| valAsiento == "TR" 
		|| valAsiento == "BA"
		|| valAsiento == ""){
		varFuncion =  "";
	}

	return varFuncion;
}
function ocultaDiv(){
	var divDetalle = document.getElementById("divDetalle");
	muestraOcultaElemento(divDetalle)
}
function muestraOcultaElemento(valElemento){
	if (valElemento.style.display == "block") {
		valElemento.style.display = "none"
	}
	else {
		valElemento.style.display = "block";
	}
}
function operaionTransporte(valAsiento, valEstado, valPiso){
	/*alert('idSalida: '+ vidSalida +"\n\n"+
		'Valor: '+ valValor +"\n\n"+
		'Estado: '+ valEstado +"\n\n");*/
	var tipoOperacion = document.getElementById('cmbTipoOperacion').value;
	var divFormulario;
	if(valEstado == 9 || valEstado == 11){
		divFormulario =  document.getElementById('divVenta');
	}
	if(valEstado == 2 || valEstado == 1){
		divFormulario =  document.getElementById('divDetalle');
	}
	if(valEstado == ""){
		if(tipoOperacion == 1 && valEstado == ''){
			divFormulario =  document.getElementById('divVenta');
		};
		if (tipoOperacion == 2 && valEstado == '') {
			divFormulario =  document.getElementById('divReserva');
		};
		if (tipoOperacion == 3 && valEstado == '') {
			divFormulario =  document.getElementById('divDerivada');
		};
	}

	divFormularioActual = divFormulario;
	muestraOcultaElemento(divFormularioActual);
	muestraOcultaElemento(divBloqueador);
	cargaDatosSalida (valPiso, valAsiento , valEstado);
}
function cargaDatosSalida (valPiso, valAsiento , valEstado){
	var aleatorio = Math.floor(Math.random()*10000001);
	var queryString = "?id="+vidSalida+"&r="+aleatorio;
	var serverAddress = "js/pasaje/cargarDatosSalida.php" + queryString;
	var cargador = new net.CargadorContenidos(serverAddress, mostrarDatosSalida, 
		false, "GET", false, false, false);
	document.getElementById('txtPiso').value = valPiso;
	document.getElementById('txtAsiento').value = valAsiento;
}
function mostrarDatosSalida(){
	var text = this.req.responseText;
	if (text) {
		var oText = JSON.parse(text);
		document.getElementById("txtFechaViaje").value = oText.fecha;
		document.getElementById("txtHora").value = oText.hora;
		document.getElementById("txtOrigen").value = oText.oficina;
		document.getElementById("txtDestino").value = oText.destino;
	}
	else {
		alert('No se encontro datos, intentelo de nuevo.');
	}
}
/**************************************/
/************ FUNCIONES ***************/
/**************************************/
function handleEnter (field, event) {  
		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;  
		if (keyCode == 13) {  
			var i;  
			for (i = 0; i < field.form.elements.length; i++)  
				if (field == field.form.elements[i])  
					break;  
			i = field.form.elements[i].tabIndex + 1;  
			for( j = 0 ; j < field.form.elements.length; j++){  
				if( field.form.elements[j].tabIndex == i){  
					break;  
				}  
			}
			field.form.elements[j].focus();
			return false;
		}  
		else  
			return true;
}
function searchCliente (field, event) {  
		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;  
		if (keyCode == 13) {  
			alert('Hello world');
			return false;  
		}  
		/*else  
			return true;*/
}
/**************************************/
/********* VALIDACIONES ***************/
/**************************************/
function validarDuplicidadCaracteres(valor, limite, nombre){
	if (valor.value.length == 0) {
		return true;
	};
	var contador = 0;
	var aux = 0;
	var numero = 0;
	for(i=0;i<valor.value.length;i++){
		numero = valor.value.substr(i,1);
		if (aux == numero) {
			contador++;
		};
		aux = numero;
	}
	if (contador > limite) {
		alert('Verifique su ' + nombre.toUpperCase());
		setFocus.call(valor);
		return false;
	};

}