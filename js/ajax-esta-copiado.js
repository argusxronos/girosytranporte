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

var ajax_copied = nuevoAjax();
var id = 0;
function Update_Copy(e, inputObj, id_giro)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	if (keyCode == 13) 
	{
		id = id_giro;
		var aleatorio = Math.floor(Math.random()*10000001);
		//var id_giro = inputObj.value;
		var value = document.getElementById('txt_copiado_' + id_giro.toString()).value;
		var url = "js/ajax-esta-copiado.php?ID="+id_giro+"&value="+value+"&r="+aleatorio;
		//alert(url);
		ajax_copied.open("GET", url, true);
		ajax_copied.onreadystatechange=Set;
		ajax_copied.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		ajax_copied.send(null);
	}
}

function Set()
{
	if (ajax_copied.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
		
	}
	if (ajax_copied.readyState==4)
	{
		if (ajax_copied.status==200)
		{
			//alert('updated');
			document.getElementById("div_td_pg_" + id.toString()).innerHTML = ajax_copied.responseText;
		}
		else
		{
			alert("Error: NO SE COPIO EL GIRO, PRESIONES F5 E INTENTELO DE NUEVO" + ajax_copied.responseText);
		}
	} 
}
var ajax_uncopied = nuevoAjax();
function Update_Uncopy(e, inputObj, id_giro)
{
	id = id_giro;
	var aleatorio = Math.floor(Math.random()*10000001);
	//var id_giro = inputObj.value;
	var url = "js/ajax-esta-copiado.php?ID="+id_giro+"&to=uncopied&r="+aleatorio;
	//alert(url);
	ajax_uncopied.open("GET", url, true);
	ajax_uncopied.onreadystatechange=Set_uncopied;
	ajax_uncopied.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_uncopied.send(null);
}

function Set_uncopied()
{
	if (ajax_uncopied.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
		
	}
	if (ajax_uncopied.readyState==4)
	{
		if (ajax_uncopied.status==200)
		{
			//alert('updated');
			document.getElementById("div_td_pg_" + id.toString()).innerHTML = ajax_uncopied.responseText;
		}
		else
		{
			alert("Error: NO SE COPIO EL GIRO, PRESIONES F5 E INTENTELO DE NUEVO" + ajax_uncopied.responseText);
		}
	} 
}

function G_Verficar_Clave(clave_real, id_mov)
{
	var clave_sup = prompt("Por favor, Ingrese su Clave","");
	if (isNumeric(clave_sup))
	{
		if (clave_sup.length == 4)
		{
			document.location.href='g_entrega.php?ID=' + id_mov;
		}
		else
		{
			alert('La Clave debe ser de 4 digitos.');
		}
	}
	else
	{
		alert('La Clave debe ser Num\xE9rica.');
	}
}

function isNumeric(value) {
  if (value != null && !value.toString().match(/^[-]?\d*\.?\d*$/)) return false;
  return true;
}