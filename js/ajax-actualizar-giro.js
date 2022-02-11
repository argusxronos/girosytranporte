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
var ajax_insert_vale = nuevoAjax();

var id_giro = 0;

function Update(inputObj, event, id, cont)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	
	if (keyCode == 13)
	{
            var aleatorio = Math.floor(Math.random()*10000001);
            var value = inputObj.value;
            id_giro = id;
            //var campo = document.getElementById('cbox_copiado_' + id_giro.toString()).value;
            var url = "js/ajax-actualizar-giro.php?ID="+id_giro+"&value="+value+"&cont="+cont+"&r="+aleatorio;
            //alert(url);
            ajax_insert_vale.open("GET", url, true);
            ajax_insert_vale.onreadystatechange=Set_Updated_Giro;
            ajax_insert_vale.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            ajax_insert_vale.send(null);
            //location.reload(); 
            
        }
}

function Set_Updated_Giro()
{
	if (ajax_insert_vale.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
	}
	if (ajax_insert_vale.readyState==4)
	{
		if (ajax_insert_vale.status==200)
		{
			
                        document.getElementById("Div_tr_" + id_giro).innerHTML = ajax_insert_vale.responseText;
			//window.location = window.location;

		}
		else
		{
			alert("Error al Ingresar el Vale, Presione F5 y vuelva a intentarlo." + ajax_insert_vale.responseText);
		}
	} 
}

function Update_Giro(field, event, id, cont) 
{
		Update(field, event, id, cont);
}