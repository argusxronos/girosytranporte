/*****************************************/
/***** POR: JONATAN RIVERA CASTAÑEDA *****/
/***** FECHA: 23/12/2011 *****************/
/***** DESCRIPCION: CODIGO PARA GENERAS EL DOCUMENTO DE LIQUIDACION */

// AJAX CARGAR LA LISTA DE ENCOMIENDAS

var ajax_E_Recep_Enc = nuevoAjax();

var vID_MOVIMIENTO = 0;
var vNUM_ITEM = 0;

function E_Recep_Enc(id_mov, num_item, Operacion)
{
	var aleatorio = Math.floor(Math.random()*10000001);
	//var id_giro = inputObj.value;
	vID_MOVIMIENTO = id_mov;
	vNUM_ITEM = num_item;
	var url = "js/ajax-e-recepcion.php?IDMOV="+vID_MOVIMIENTO+"&INUM="+vNUM_ITEM+"&TOPER="+Operacion+"&r="+aleatorio;
	ajax_E_Recep_Enc.open("GET", url, true);
	ajax_E_Recep_Enc.onreadystatechange=Set_E_Recep_Enc;
	ajax_E_Recep_Enc.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajax_E_Recep_Enc.send(null);
}

function Set_E_Recep_Enc()
{
	if (ajax_E_Recep_Enc.readyState==1)
	{
		// Mientras carga elimino la opcion "Selecciona Opcion" y pongo "Cargando..."
		/*document.getElementById("cmb_usuario").length=0;
		var nuevaOpcion=document.createElement("option"); 
		nuevaOpcion.value=0;
		nuevaOpcion.innerHTML = "Cargando...";
		document.getElementById("cmb_usuario").appendChild(nuevaOpcion); 
		nuevaOpcion.disabled = true;*/
	}
	if (ajax_E_Recep_Enc.readyState==4)
	{
		if (ajax_E_Recep_Enc.status==200)
		{
			document.getElementById("div_td_" + vID_MOVIMIENTO + vNUM_ITEM).innerHTML = ajax_E_Recep_Enc.responseText;
		}
		else
		{
			alert("Error: NO SE RECEPCIOND3 LA ENCOMIENDA, CONEXION MUY LENTA A INTERNET. \n PRESIONE F5 PARA VERIFICAR LAS ENCOMIENDAS RECIBIDAS" + ajax_E_Recep_Enc.responseText);
		}
	} 
}

