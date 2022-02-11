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

var ajax_edit_boleta_giro = nuevoAjax();
var id_giro = 0;
// PARA PODER EDITAR EL VALE DEL GIRO
var ajax_update_boleta_giro =nuevoAjax();

function Edit_Boleta_Giro(inputObj, event, id, serie, numero, cont)
{
	var aleatorio = Math.floor(Math.random()*10000001);
	id_giro = id;
	//var campo = document.getElementById('cbox_copiado_' + id_giro.toString()).value;
	var url = "js/ajax-edit-boleta-giro.php?ID="+id_giro+"&SERIE="+serie+"&NUMERO="+numero+"&CONT="+cont+"&r="+aleatorio;
	//alert(url);
	ajax_edit_boleta_giro.open("GET", url, true);
	ajax_edit_boleta_giro.onreadystatechange = Set_Edit_Boleta_Giro;
	ajax_edit_boleta_giro.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_edit_boleta_giro.send(null);
	//setTimeout(function() { document.getElementById("txt_vale_" + id_giro).focus(); }, 10);
}

function Set_Edit_Boleta_Giro()
{
	if (ajax_edit_boleta_giro.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
	}
	if (ajax_edit_boleta_giro.readyState==4)
	{
		if (ajax_edit_boleta_giro.status==200)
		{
			//alert('updated');
			document.getElementById("Div_td_bol_" + id_giro).innerHTML = ajax_edit_boleta_giro.responseText;
			
			//document.getElementById("txt_vale_" + id_giro).focus();
			//window.location = window.location;
			//setTimeout(function() { document.getElementById("txt_vale_" + id_giro).focus(); }, 10);
		}
		else
		{
			alert("Error al intentar Numero de Boleta del Giro." + ajax_edit_boleta_giro.status);
		}
	}
}

function Update_Boleta_Giro(inputObj, event, id, serie, numero, cont)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	
	if (keyCode == 13)
	{
		var aleatorio = Math.floor(Math.random()*10000001);
		var value = inputObj.value;
		id_giro = id;
		//var campo = document.getElementById('cbox_copiado_' + id_giro.toString()).value;
		var url = "js/ajax-update-boleta-giro.php?ID="+id_giro+"&SERIE="+serie+"&NUMERO="+numero+"&CONT="+cont+"&r="+aleatorio;
		//alert(url);
		ajax_update_boleta_giro.open("GET", url, true);
		ajax_update_boleta_giro.onreadystatechange = Set_Update_Boleta_Giro;
		ajax_update_boleta_giro.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		ajax_update_boleta_giro.send(null);
	}
	
	
}

function Set_Update_Boleta_Giro()
{
	if (ajax_update_boleta_giro.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
		
	}
	if (ajax_update_boleta_giro.readyState==4)
	{
		if (ajax_update_boleta_giro.status==200)
		{
			//alert('updated');
			document.getElementById("Div_tr_" + id_giro).innerHTML = ajax_update_boleta_giro.responseText;
			
			
			//window.location = window.location;
		}
		else
		{
			alert("Error al intentar MODIFICAR la numeraci&oacute;n de la Boleta." + ajax_update_boleta_giro.responseText);
		}
	}
}