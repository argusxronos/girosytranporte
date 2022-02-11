
function GetDatos(type)
{
	var aleatorio = Math.floor(Math.random()*101);
	var campo = document.getElementById('cmb_destino').value;
	var url = "js/ajax-get-dato-detino.php?ID="+campo+"&TYPE="+type+"&r="+aleatorio;
	ajax_user.open("GET", url, true);
	ajax_user.onreadystatechange = SetDatos;
	ajax_user.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_user.send(null);
}
function SetDatos()
{
	if (ajax_user.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		document.getElementById("txt_hora").length=0;		
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("txt_hora").appendChild(nuevaOpcion); 		
		nuevaOpcion.disabled = true;
	}
	if (ajax_user.readyState==4)
	{
		if (ajax_user.status==200)
		{
			document.getElementById("DivDocumento").innerHTML = ajax_user.responseText;
		}
		else
		{
			alert("Error " + ajax_user.responseText);
		}
	} 
}

function Get_Datos_Destino(TYPE)
{
	GetDatos(TYPE);	
}
