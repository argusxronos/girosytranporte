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

var ajax_Verified_Emit =nuevoAjax();

function Update_Verified_Emit(e, inputObj)
{
	var aleatorio = Math.floor(Math.random()*10000001);
	var id_giro = inputObj.value;
	//var campo = document.getElementById('cbox_copiado_' + id_giro.toString()).value;
	var url = "js/ajax-esta-verificada-emit.php?ID="+id_giro+"&r="+aleatorio;
	//alert(url);
	ajax_Verified_Emit.open("GET", url, true);
	ajax_Verified_Emit.onreadystatechange=Set_Verified_Emit;
	ajax_Verified_Emit.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_Verified_Emit.send(null);
}

function Set_Verified_Emit()
{
	if (ajax_Verified_Emit.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
		
	}
	if (ajax_Verified_Emit.readyState==4)
	{
		if (ajax_Verified_Emit.status==200)
		{
			//alert('updated');
			document.getElementById("div_error").innerHTML = ajax_Verified_Emit.responseText;
		}
		else
		{
			alert("Error al Verificar el Giro, presione F5 y vuelva a intentarlo." + ajax_Verified_Emit.responseText);
		}
	} 
}