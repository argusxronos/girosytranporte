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

var ajax_edit_vale = nuevoAjax();
var id_giro = 0;
// PARA PODER EDITAR EL VALE DEL GIRO
var ajax_update_vale =nuevoAjax();

function Edit_Vale_Giro(num_vale, event, id)
{
	var aleatorio = Math.floor(Math.random()*10000001);
	var value = num_vale;
	id_giro = id;
	//var campo = document.getElementById('cbox_copiado_' + id_giro.toString()).value;
	var url = "js/ajax-edit-vale-giro.php?ID="+id_giro+"&value="+value+"&r="+aleatorio;
	//alert(url);
	ajax_edit_vale.open("GET", url, true);
	ajax_edit_vale.onreadystatechange = Set;
        
	ajax_edit_vale.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_edit_vale.send(null);
	//setTimeout(function() { document.getElementById("txt_vale_" + id_giro).focus(); }, 10);
}

function Set()
{
	if (ajax_edit_vale.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
	}
	if (ajax_edit_vale.readyState==4)
	{
		if (ajax_edit_vale.status==200)
		{
			//alert('updated');
			document.getElementById("td_vale_" + id_giro).innerHTML = ajax_edit_vale.responseText;
			
			//document.getElementById("txt_vale_" + id_giro).focus();
			//window.location = window.location;
			//setTimeout(function() { document.getElementById("txt_vale_" + id_giro).focus(); }, 10);
		}
		else
		{
			alert("Error al intentar editar Vale." + ajax.status);
		}
	}
}

function Update_Vale_Giro(inputObj, event, id)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	
	if (keyCode == 13)
	{
		var aleatorio = Math.floor(Math.random()*10000001);
		var value = inputObj.value;
		id_giro = id;
		//var campo = document.getElementById('cbox_copiado_' + id_giro.toString()).value;
		var url = "js/ajax-update-vale-giro.php?ID="+id_giro+"&value="+value+"&r="+aleatorio;
		//alert(url);
		ajax_update_vale.open("GET", url, true);
                alert("edit giro");
		ajax_update_vale.onreadystatechange = Set_Update_Vale_Giro;
		ajax_update_vale.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		ajax_update_vale.send(null);
	}
	
	
}

function Set_Update_Vale_Giro()
{
	if (ajax_update_vale.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
		
	}
	if (ajax_update_vale.readyState==4)
	{
		if (ajax_update_vale.status==200)
		{
			//alert('updated');
			document.getElementById("td_vale_" + id_giro).innerHTML = ajax_update_vale.responseText;
			
			
			//window.location = window.location;
		}
		else
		{
			alert("Error al intentar actualizar el numero de Vale." + ajax_update_vale.responseText);
		}
	}
}