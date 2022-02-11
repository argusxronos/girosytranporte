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

var ajax_edit_bol_anulada = nuevoAjax();
var ajax_desanular_bol_anulada = nuevoAjax();
var id_giro = 0;

function Edit_Giro_Anulado(inputObj, event, id, cont)
{
	
	var aleatorio = Math.floor(Math.random()*10000001);
	id_giro = id;
	//var campo = document.getElementById('cbox_copiado_' + id_giro.toString()).value;
	var url = "js/ajax-edit-giro-anulado.php?ID="+id_giro+"&cont="+cont+"&r="+aleatorio;
	//alert(url);
	ajax_edit_bol_anulada.open("GET", url, true);
	ajax_edit_bol_anulada.onreadystatechange=Set_Edit_Giro_Anulado;
	ajax_edit_bol_anulada.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_edit_bol_anulada.send(null);
}

function Set_Edit_Giro_Anulado()
{
	if (ajax_edit_bol_anulada.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
	}
	if (ajax_edit_bol_anulada.readyState==4)
	{
		if (ajax_edit_bol_anulada.status==200)
		{
			
			document.getElementById("Div_td_actgiro_" + id_giro).innerHTML = ajax_edit_bol_anulada.responseText;
			//window.location = window.location;
		}
		else
		{
			alert("Error al Ingresar desanular Giro, Presione F5 y vuelva a intentarlo." + ajax_edit_bol_anulada.responseText);
		}
	} 
}


function Desanular_Giro_Anulado(inputObj, event, id, monto, flete, cont)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	id_giro = id;
	if (keyCode == 13)
	{
		var aleatorio = Math.floor(Math.random()*101);
		id_giro = id;
		//var campo = document.getElementById('cbox_copiado_' + id_giro.toString()).value;
		var url = "js/ajax-desanular-giro.php?ID="+id+"&MONTO="+monto+"&FLETE="+flete+"&cont="+cont+"&r="+aleatorio;
		ajax_desanular_bol_anulada.open("GET", url, true);
		ajax_desanular_bol_anulada.onreadystatechange=Set_Desanular_Giro_Anulado;
		ajax_desanular_bol_anulada.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		ajax_desanular_bol_anulada.send(null);
	}
}

function Set_Desanular_Giro_Anulado()
{
	if (ajax_desanular_bol_anulada.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
		//document.getElementById("Div_tr_" + id_giro).innerHTML = '<td colspan="7" style="text-aling:center;">Cargando...</td>';
	}
	if (ajax_edit_bol_anulada.readyState==4)
	{
		if (ajax_desanular_bol_anulada.status==200)
		{
			
			document.getElementById("Div_tr_" + id_giro).innerHTML = ajax_desanular_bol_anulada.responseText;
			//window.location = window.location;
		}
		else
		{
			alert("Error al Ingresar desanular Giro, Presione F5 y vuelva a intentarlo." + ajax_edit_bol_anulada.responseText);
		}
	} 
}
