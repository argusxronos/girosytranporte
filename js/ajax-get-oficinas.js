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

var ajax_user =nuevoAjax();

function GetOficinas(type)
{
	var aleatorio = Math.floor(Math.random()*101);
	var campo = document.getElementById('cmb_agencia_origen').value;
	var url = "js/ajax-get-oficinas.php?ID="+campo+"&TYPE="+type+"&r="+aleatorio;
	ajax_user.open("GET", url, true);
	ajax_user.onreadystatechange = SetUsuarios;
	ajax_user.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_user.send(null);
}
function SetUsuarios()
{
	if (ajax_user.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		document.getElementById("cmb_destinos").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";	
		document.getElementById("cmb_destino").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;
	}
	if (ajax_user.readyState==4)
	{
		if (ajax_user.status==200)
		{
			document.getElementById("div_fila_usuario").innerHTML = ajax_user.responseText;
		}
		else
		{
			alert("Error " + ajax_user.responseText);
		}
	} 
}

function Get_Oficinas_Numeracion_Derivado(TYPE)
{
	GetOficinas(TYPE);	
}

