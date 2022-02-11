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

var ajax_get_numeracion = nuevoAjax();
var ajax_get_doc_encomienda = nuevoAjax();
var ajax_get_doc_trasbordo = nuevoAjax();

function Get_Trasbordo(event, inputObj)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	
	if (keyCode == 13)
	{
    /*Asignando Variables*/
    var documento ='';
    var serie = 0;
    var numero = 0;
    var aleatorio = Math.floor(Math.random()*10000001);
    var codigo = document.getElementById('txt_codigo').value;
    var e_codigo = '';
    
    /*Se obtiene el valor del radius*/
    var documentos = document.getElementsByName('documento');
    for(var i=0; i<documentos.length; i++) 
      if(documentos[i].checked){documento = documentos[i].value; break;}
      
    /*Obtenemos los valores de los otros campos*/
    serie = document.getElementById('serie_ing').value;
    numero = document.getElementById('numero_ing').value;
    
    if (document.getElementById('txt_e_codigo'))
			e_codigo = document.getElementById('txt_e_codigo').value;

    /*Pasamos las variables al php*/  
    var url ="js/ajax-doc-transbordo.php?documento="+documento+"&serie="+serie+"&numero="+numero+
    "&CODIGO="+codigo+"&TDOC="+documento+"&ECODIGO="+e_codigo+"&r="+aleatorio;
    
    ajax_get_doc_trasbordo.open("GET", url, true);
    ajax_get_doc_trasbordo.onreadystatechange=Set_Transbordo;
    ajax_get_doc_trasbordo.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    ajax_get_doc_trasbordo.send(null);
    document.getElementById('btn_guardar').focus();
    return false;
  }else
    return true;
  
}

function Set_Transbordo()
{
	if (ajax_get_doc_trasbordo.readyState==4)
	{
		if (ajax_get_doc_trasbordo.status==200)
		{
			document.getElementById("doc_transbordo").innerHTML = ajax_get_doc_trasbordo.responseText;
		}
		else
		{
			alert("Error " + ajax_get_doc_trasbordo.responseText);
		}
	} 
}


function Get_Numeracion(event, inputObj, type)
{
	var aleatorio = Math.floor(Math.random()*10000001);
	var id = inputObj.value;
	var id_oficina = 0;
	if (document.getElementById('cmb_agencia_origen'))
		id_oficina = document.getElementById('cmb_agencia_origen').value;
	var url = "js/ajax-get-documento.php?ID="+id+"&IDOFICINA="+id_oficina+"&TYPE="+type+"&r="+aleatorio;
	ajax_get_numeracion.open("GET", url, true);
	ajax_get_numeracion.onreadystatechange=Set_Numeracion;
	ajax_get_numeracion.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_get_numeracion.send(null);
}

function Set_Numeracion()
{
	if (ajax_get_numeracion.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		//document.getElementById("cmb_usuario").length=0;
		//var nuevaOpcion=document.createElement("option"); 
		//nuevaOpcion.value=0;
		//nuevaOpcion.innerHTML = "Cargando...";
		if(document.getElementById("txt_serie"))
			document.getElementById("txt_serie").value = '...'; 
		if (document.getElementById("txt_numero"))
			document.getElementById("txt_numero").value = '...'; 
		//nuevaOpcion.disabled = true;
	}
	if (ajax_get_numeracion.readyState==4)
	{
		if (ajax_get_numeracion.status==200)
		{
			document.getElementById("num_documento2").innerHTML = ajax_get_numeracion.responseText;
		}
		else
		{
			alert("Error " + ajax_get_numeracion.responseText);
		}
	} 
}

function Get_Documento_Encomienda(event, inputObj)
{
	var aleatorio = Math.floor(Math.random()*10000001);
	var dropdownIndex = document.getElementById('cmb_documento').selectedIndex;
	var TD = document.getElementById('cmb_documento')[dropdownIndex].text;
	//var campo = document.getElementById('cbox_copiado_' + id_giro.toString()).value;
	var url = "js/ajax-doc-encomienda.php?TD="+TD+"&r="+aleatorio;
	//alert(url);
	ajax_get_doc_encomienda.open("GET", url, true);
	ajax_get_doc_encomienda.onreadystatechange=Set_Documento_Encomienda;
	ajax_get_doc_encomienda.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_get_doc_encomienda.send(null);
}

function Set_Documento_Encomienda()
{
	if (ajax_get_doc_encomienda.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		//document.getElementById("cmb_usuario").length=0;
		//var nuevaOpcion=document.createElement("option"); 
		//nuevaOpcion.value=0;
		//nuevaOpcion.innerHTML = "Cargando...";
		//nuevaOpcion.disabled = true;
	}
	if (ajax_get_doc_encomienda.readyState==4)
	{
		if (ajax_get_doc_encomienda.status==200)
		{
			//alert('updated');
			document.getElementById("Div_Documento_Content").innerHTML = ajax_get_doc_encomienda.responseText;
		}
		else
		{
			alert("Error " + ajax_get_doc_encomienda.responseText);
		}
	}
}

function Get_Documento_Numeracion(event, inputObj)
{
	Get_Numeracion(event, inputObj, '2');
	Get_Documento_Encomienda(event, inputObj);
}
