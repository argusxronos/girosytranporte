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

var ajax_edit_carrera = nuevoAjax();
var ajax_update_carrera = nuevoAjax();
var ajax_recalcular = nuevoAjax();
var id_carrera = 0;

function Edit_Carrera(event, id)
{
  var aleatorio = Math.floor(Math.random()*10000001);
	id_carrera = id;
	var url = "js/ajax-edit-e-carrera.php?ID="+id_carrera+"&r="+aleatorio;
	ajax_edit_carrera.open("GET", url, true);
	ajax_edit_carrera.onreadystatechange = Set;
	ajax_edit_carrera.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_edit_carrera.send(null);
}

function Set()
{
	if (ajax_edit_carrera.readyState==4)
	{
		if (ajax_edit_carrera.status==200)
		{
			document.getElementById("carrera_"+id_carrera).innerHTML = ajax_edit_carrera.responseText;
		}
		else
		{
			alert("Error al intentar editar la Carrera." + ajax.status);
		}
	}
}

function Update_Carrera(inputObj, event, id)
{
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	
	if (keyCode == 13)
	{
		var aleatorio = Math.floor(Math.random()*10000001);
		var valor = inputObj.value;
		id_carrera = id;
    value =  ReCalcular(valor, id_carrera);
    
		var url = "js/ajax-update-e-carrera.php?ID="+id_carrera+"&value="+value+"&r="+aleatorio;
    
    ajax_update_carrera.open("GET", url, true);
		ajax_update_carrera.onreadystatechange = Set_Update_Carrera;
		ajax_update_carrera.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		ajax_update_carrera.send(null);
    
    document.getElementById('btn_guardar').focus();
    return false;
	}else
    return true;
}

function Set_Update_Carrera()
{
  
	if (ajax_update_carrera.readyState==4)
	{

		if (ajax_update_carrera.status==200)
		{
			document.getElementById("carrera_" + id_carrera).innerHTML = ajax_update_carrera.responseText;

		}
		else
		{
			alert("Error al intentar actualizar el valor de la carrera." + ajax.status);
		}
	}
}
function ReCalcular(valor, id )
{
    var id_carrera =id;
    var total= parseFloat(document.getElementById("totalGT_"+id_carrera).value);
    var valorCarrera=parseFloat(valor);
    var newTotal=0;
    if ( total<=valorCarrera )  { valorCarrera=total; }
    else{ newTotal= total-valorCarrera; }
  
    var url="js/ajax-update-e-recalcular.php?newTotal="+newTotal+"&CARRERA="+valorCarrera+"&ID="+id_carrera; 
  
    ajax_recalcular.open("GET", url, true);
    ajax_recalcular.onreadystatechange = Set_ReCalcular;
    ajax_recalcular.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    ajax_recalcular.send(null);
 
    return valorCarrera;
}


function Set_ReCalcular()
{
  
	if (ajax_recalcular.readyState==4)
	{
    
		if (ajax_recalcular.status==200)
		{
      document.getElementById("fleteT_" + id_carrera).innerHTML = ajax_recalcular.responseText;
		}
		else
		{
			alert("Error al intentar recalcular los valores." + ajax.status);
		}
	}
}
