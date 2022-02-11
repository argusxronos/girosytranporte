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

var ajax_verified_vale =nuevoAjax();

function Update_Verified(e, inputObj)
{
	var aleatorio = Math.floor(Math.random()*101);
	var id_giro = inputObj.value;
	//var campo = document.getElementById('cbox_copiado_' + id_giro.toString()).value;
	var url = "js/ajax-esta-verificada.php?ID="+id_giro+"&r="+aleatorio;
	//alert(url);
	ajax_verified_vale.open("GET", url, true);
	ajax_verified_vale.onreadystatechange=Set_Verified_Vale;
	ajax_verified_vale.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_verified_vale.send(null);
}

function Set_Verified_Vale()
{
	if (ajax_verified_vale.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
		
	}
	if (ajax_verified_vale.readyState==4)
	{
		if (ajax_verified_vale.status==200)
		{
			//alert('updated');
			document.getElementById("div_error").innerHTML = ajax_verified_vale.responseText;
		}
		else
		{
			alert("Error Giro no Verificado. " + ajax_verified_vale.responseText);
		}
	} 
}