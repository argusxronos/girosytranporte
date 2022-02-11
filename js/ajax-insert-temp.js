// JavaScript Document
function nuevoAjax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por
	lo que se puede copiar tal como esta aqui */
	var xmlhttp=false;
	try
	{
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			// Creacion del objet AJAX para IE
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E)
		{
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}

var ajax_insert_temp = nuevoAjax();

function E_Insert_Temp(inputObj, event)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	
	if (keyCode == 13)
	{
		/* VALIDACIONES */
		
		j = document.forms[0].cmb_documento.selectedIndex; 

		tipo_documento = document.forms[0].cmb_documento[j].text; 

		var codigo = document.getElementById('txt_codigo').value;
		
		var aleatorio = Math.floor(Math.random()*100000001);
		var cantidad = document.getElementById('txt_cant').value;
		var descripcion = document.getElementById('txt_descripcion').value;
		var flete = '';
		var carrera = '';
		var e_codigo = '';
		var e_unit = '';
		var peso = '';
		if (document.getElementById('txt_e_codigo'))
		{
			e_codigo = document.getElementById('txt_e_codigo').value;
		}
		if (document.getElementById('txt_unid'))
		{
			e_unit = document.getElementById('txt_unid').value;
		}
		if (document.getElementById('txt_flete'))
		{
			flete = document.getElementById('txt_flete').value;
		}
		if (document.getElementById('txt_carrera'))
		{
			carrera = document.getElementById('txt_carrera').value;
		}
		if (document.getElementById('txt_peso'))
		{
			peso = document.getElementById('txt_peso').value;
		}
		var url = "js/ajax-insert-temp.php?CODIGO="+codigo+"&CANTIDAD="+cantidad+"&DESCRIP="+descripcion+"&FLETE="+flete+"&CARRERA="+carrera+"&TDOC="+tipo_documento+"&ECODIGO="+e_codigo+"&UNID="+e_unit+"&PESO="+peso+"&r="+aleatorio;
		ajax_insert_temp.open("GET", url, true);
		ajax_insert_temp.onreadystatechange=Set_E_Insert_Temp;
		ajax_insert_temp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		ajax_insert_temp.send(null);
		document.getElementById('txt_descripcion').value = '';
		if (document.getElementById('txt_cant'))
		{
			document.getElementById('txt_cant').value = '1';
		}
		if (document.getElementById('txt_flete'))
		{
			document.getElementById('txt_flete').value = '0.00';
		}
		if (document.getElementById('txt_carrera'))
		{
			document.getElementById('txt_carrera').value = '0.00';
		}
		if (document.getElementById('txt_e_codigo'))
		{
			document.getElementById('txt_e_codigo').value = '';
		}
		if (document.getElementById('txt_unid'))
		{
			document.getElementById('txt_unid').value = '1';
		}
		if (document.getElementById('txt_peso'))
		{
			document.getElementById('txt_peso').value = '';
		}
		document.getElementById('txt_cant').focus();
		/*document.getElementById('txt_descripcion').value = '';
		document.getElementById('txt_descripcion').focus();*/
		return false;
	}
	else
		return true;
}

function Set_E_Insert_Temp()
{
	if (ajax_insert_temp.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
	}
	if (ajax_insert_temp.readyState==4)
	{
		if (ajax_insert_temp.status==200)
		{
			
			document.getElementById("Div_List_Items").innerHTML = ajax_insert_temp.responseText;
			//window.location = window.location;
		}
		else
		{
			alert("Error al Ingresar desanular Giro, Presione F5 y vuelva a intentarlo." + ajax_insert_temp.responseText);
		}
	}
}



// ****************************************************** //
// FUNCION PARA ELIMINAR UN REGISTRO DE LA TABLA TEMPORAL //
// ****************************************************** //
var ajax_delete_temp = nuevoAjax();
function E_Delete_Temp(num_item, TDOC)
{
		var codigo = document.getElementById('txt_codigo').value;
		var aleatorio = Math.floor(Math.random()*100000001);
		var url = "js/ajax-delete-temp.php?CODIGO="+codigo+"&NUM_ITEM="+num_item+"&TDOC="+TDOC+"&r="+aleatorio;
		ajax_delete_temp.open("GET", url, true);
		ajax_delete_temp.onreadystatechange=Set_E_Delete_Temp;
		ajax_delete_temp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		ajax_delete_temp.send(null);
		document.getElementById('txt_descripcion').value = '';
		document.getElementById('txt_descripcion').focus();
		return false;
}

function Set_E_Delete_Temp()
{
	
	if (ajax_delete_temp.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
	}
	if (ajax_delete_temp.readyState==4)
	{
		if (ajax_delete_temp.status==200)
		{
			document.getElementById("Div_List_Items").innerHTML = ajax_delete_temp.responseText;
			//window.location = window.location;
		}
		else
		{
			alert("Error al eliminar Encomienda, Presione F5 y vuelva a intentarlo." + ajax_delete_temp.responseText);
		}
	}
}