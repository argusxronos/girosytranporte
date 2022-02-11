function copiarDatos()
{
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

    //Mostrar Datos del cliente para realizar la reserva
    document.reserva_form.txt_nombre.value=nombres;
    document.reserva_form.txt_documento.value=documentos;
    document.reserva_form.txt_ndocumento.value=dni;
    document.reserva_form.txt_genero.value=genero;
    document.reserva_form.txt_telefono.value=fono;
    document.reserva_form.txt_edad.value=edad;
    document.reserva_form.txt_nacionalidad.value=nacionalidad;
    document.reserva_form.txt_ruc.value=ruc;
    document.reserva_form.txt_razon.value=razon; 
    //Mostrar Datos de salida para realizar la reserva
    document.reserva_form.txt_fecha_viaje.value=fecha_viaje;
    document.reserva_form.txt_hora_viaje.value=hora_viaje;
    document.reserva_form.txt_piso.value=piso;
    document.reserva_form.txt_asiento.value=asiento;
    document.reserva_form.txt_codigo_salida.value=codigo_salida;
    document.reserva_form.txt_salida.value=destino_final;
    document.reserva_form.txt_origen.value=origen;

}
