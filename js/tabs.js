$(document).ready(function()
{
	$(".tab_content").hide();
	$("ul.tabs li:first").addClass("active").show();
	$(".tab_content:first").show();

	$("ul.tabs li").click(function()
       {
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		$(".tab_content").hide();

		var activeTab = $(this).find("a").attr("href");
		$(activeTab).fadeIn();
		return false;
	});
});



/*MOSTRAR PRECIOS DE SUB RUTAS INICIO*/

function precios()
{
	var cadena = document.venta_form.cmb_ruta.options[document.venta_form.cmb_ruta.selectedIndex].text;			
	var precio2=cadena.slice(cadena.length-2);				
			
	var valor_espacio = cadena.substring(0,cadena.length-3);
	var precio1=valor_espacio.slice(valor_espacio.length-2);
	var destino=cadena.substring(0,cadena.length-6);
					
	document.venta_form.txt_precio1.value = precio1;  	
	document.venta_form.txt_precio2.value = precio2;  
	document.venta_form.txt_destino.value =	destino;
	
	
	var piso= document.form_ventas_pasajes.txt_piso.value;
	if(piso==1){
		document.venta_form.txt_importe.value = precio1;
		document.form_ventas_pasajes.txt_importe_total.value=document.venta_form.txt_importe.value;
		document.venta_form.txt_importe_letras.value = covertirNumLetras(precio1);
	}else {
		document.venta_form.txt_importe.value = precio2;
		document.form_ventas_pasajes.txt_importe_total.value=precio2;
		document.venta_form.txt_importe_letras.value = covertirNumLetras(precio2);
	}
}
/*MOSTRAR PRECIOS SUB RUTAS FIN*/

/*HABILITAR Y DESABILITAR SEGUN MARCAN EL CHEKBOX EN RESERVA_FORM*/
function deshabilitarcombo(){
if (document.reserva_form.otra_agencia.checked) {
document.reserva_form.cmb_oficina_reserva.disabled=false;
}
else{
document.reserva_form.cmb_oficina_reserva.disabled=true;
}
}


/*Crear Una lista de datos*/
function add(valor) {
elem=document.getElementById('lista').options.length;
var selOpcion=new Option(valor);
document.getElementById('lista').options[elem]=selOpcion;
var len = document.form1.detalle.length -1;
document.form1.detalle.selectedIndex = len;

}

function DeleteSecondListItem(){
     //var fl = document.getElementById('firstlist');
     var sl = document.getElementById('lista');    
     for (i = 0; i < sl.options.length; i++){
       if(sl.options[i].selected){
         //fl.add(sl.options[i],null);
         // O... 
          sl.remove(sl.options[i],null);
       }
     }
     return true;
   }
