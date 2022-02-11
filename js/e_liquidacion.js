/*****************************************/
/***** POR: JONATAN RIVERA CASTAÑEDA *****/
/***** FECHA: 23/12/2011 *****************/
/***** DESCRIPCION: CODIGO PARA GENERAS EL DOCUMENTO DE LIQUIDACION */

function OptionsList(theSel)
{
	var selLength = theSel.length;
	var selectedText = new Array();
	var selectedValues = new Array();
	var selectedCount = 0;
	var list_oficinas = '';
	var i;
	// OBTENEMOS EL TIPO DE LIQUIDACION
	var t_liq = document.getElementById("cmd_tipo_liq").value;
	
	if (selLength == 0)
	{
		alert('Debe seleccionar oficinas para crear la liquidaci\xF3n.');
		return;
	}
	else if(selLength > 7)
	{
		alert('El limite de oficinas es de 7.');
		return;
	}
	// Find the selected Options in reverse order
	// and delete them from the 'from' Select.
	for(i=selLength-1; i>=0; i--)
	{
	selectedText[selectedCount] = theSel.options[i].text;
	selectedValues[selectedCount] = theSel.options[i].value;
	selectedCount++;
	}
	for(i=selectedValues.length-1; i>=0; i--)
	{
	list_oficinas = list_oficinas + '(' + selectedValues[i] + ')';
	}
	// UTILIZAMOS AJAX PARA CREAR LA LISTA DE ENCOMIENDAS DE LA LIQUIDACION
	Insert_temp_DLiq(list_oficinas, selLength);
}

// AJAX CARGAR LA LISTA DE ENCOMIENDAS

var ajax_insert_temp_liq = nuevoAjax();

function Insert_temp_DLiq(List_Oficinas, Num_Oficinas)
{
	var aleatorio = Math.floor(Math.random()*10000001);
	//var id_giro = inputObj.value;
	var codigo = document.getElementById('txt_codigo').value;
	var fecha = document.getElementById('txt_fecha').value;
	// OBTENEMOS EL TIPO DE LIQUIDACION
	var tipo_liq = document.getElementById('cmd_tipo_liq').value;
	
	var url = "js/ajax-insert-temp-liq.php?DESTINOS="+List_Oficinas+"&CODIGO="+codigo+"&FECHA="+fecha+"&TLIQ="+tipo_liq+"&NUMOF="+Num_Oficinas+"&r="+aleatorio;
	ajax_insert_temp_liq.open("GET", url, true);
	ajax_insert_temp_liq.onreadystatechange=Set_Insert_temp_DLiq;
	ajax_insert_temp_liq.setRequestHeader('Content-Type',  'application/x-www-form-urlencoded');
	ajax_insert_temp_liq.send(null);
}

function Set_Insert_temp_DLiq()
{
	if (ajax_insert_temp_liq.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
	}
	if (ajax_insert_temp_liq.readyState==4)
	{
		if (ajax_insert_temp_liq.status==200)
		{
			//alert('updated');
			document.getElementById("div_list_encomiendas").innerHTML = ajax_insert_temp_liq.responseText;
		}
		else
		{
			alert("Error: NO SE PUEDE CARGAR LA LISTA" + ajax_insert_temp_liq.responseText);
		}
	} 
}

// AJAX PARA ELIMINAR UNA ENCOMIENDA DE LA LISTA TEMPORAL PARA LA LIQUIDACION

var ajax_delete_temp_liq = nuevoAjax();
var vid_movimiento = 0;
var vnum_item = 0;
function Delete_temp_DLiq(id_movimiento, num_item)
{
	vid_movimiento = id_movimiento;
	vnum_item = num_item;
	var aleatorio = Math.floor(Math.random()*10000001);
	//var id_giro = inputObj.value;
	var codigo = document.getElementById('txt_codigo').value;
	var fecha = document.getElementById('txt_fecha').value;
	var url = "js/ajax-delete-temp-liq.php?ID="+id_movimiento+"&ITEM="+num_item+"&CODIGO="+codigo+"&FECHA="+fecha+"&r="+aleatorio;
	ajax_delete_temp_liq.open("GET", url, true);
	ajax_delete_temp_liq.onreadystatechange=Set_Delete_temp_DLiq;
	ajax_delete_temp_liq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_delete_temp_liq.send(null);
}

function Set_Delete_temp_DLiq()
{
	if (ajax_delete_temp_liq.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
	}
	if (ajax_delete_temp_liq.readyState==4)
	{
		if (ajax_delete_temp_liq.status==200)
		{
			var parent = document.getElementById("div_tr_"+ vid_movimiento + vnum_item).parentNode;
			parent.removeChild(document.getElementById("div_tr_"+ vid_movimiento + vnum_item));
		}
		else
		{
			alert("Error: NO SE PUEDE CARGAR LA LISTA" + ajax_delete_temp_liq.responseText);
		}
	} 
}


// AJAX PARA ELIMINAR UNA ENCOMIENDA DE LA LISTA DE LIQUIDACION

var ajax_del_item_liq = nuevoAjax();
var vid_liquidacion = 0;

function Delete_Item_Liq(id_movimiento, num_item, id_liquidacion)
{
	vid_movimiento = id_movimiento;
	vnum_item = num_item;
	vid_liquidacion = id_liquidacion;
	var aleatorio = Math.floor(Math.random()*10000001);
	//var id_giro = inputObj.value;
	var url = "js/ajax-del-item-liq.php?IDMOVIMIENTO="+id_movimiento+"&ITEM="+num_item+"&IDLIQUIDACION="+vid_liquidacion+"&r="+aleatorio;
	ajax_del_item_liq.open("GET", url, true);
	ajax_del_item_liq.onreadystatechange=Set_Delete_Item_Liq;
	ajax_del_item_liq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_del_item_liq.send(null);
}

function Set_Delete_Item_Liq()
{
	if (ajax_del_item_liq.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
	}
	if (ajax_del_item_liq.readyState==4)
	{
		if (ajax_del_item_liq.status==200)
		{
			var parent = document.getElementById("div_tr_"+ vid_movimiento + vnum_item).parentNode;
			parent.removeChild(document.getElementById("div_tr_"+ vid_movimiento + vnum_item));
		}
		else
		{
			alert("Error: NO SE PUEDE CARGAR LA LISTA" + ajax_del_item_liq.responseText);
		}
	} 
}


function ChangeTipoLiquidacion(theSel)
{
	var selLength = theSel.length;
	// SI LA LISTA DE AGENCIAS PARA LA LIQUIDACION ES MAYOR A CERO
	if (selLength > 0)
	{
		// VOLVEMOS A CARGAR LA LISTA PARA LA LIQUIDACION
		OptionsList(theSel);
	}
}
