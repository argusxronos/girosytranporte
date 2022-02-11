var ajax_get_Client = new sack(); /*Simple AJAX Code-Kit (SACK) v1.6.1  js/buscar/ajax.js*/
var currentClientID_REMIT=false;
var currentClientID_CONSIG=false;

var ajax_get_Client_byID = new sack();

function getClientData(TDOC, TPER)
{
	var clientId = '';
	var clientid_remit = '';
	var clientname = '';
	var id_ofincia_origen = 0;
	var id_oficina_destino = 0;
  
	if (TPER == 'REMITENTE')
	{
		clientId = document.getElementById('txt_remit_dni').value.replace(/[^0-9]/g,'');
		clientname = document.getElementById('txt_remit').value;
	}
	if(TPER == 'CONSIGNATARIO')
	{
		clientid_remit = document.getElementById('txt_remit_dni').value.replace(/[^0-9]/g,'');
		clientId = document.getElementById('txt_consig_dni').value.replace(/[^0-9]/g,'');
		if(document.getElementById('txt_consig'))
		{
			clientname = document.getElementById('txt_consig').value;
		}
	}
 
	
	if (TDOC == 'BOLETA')
	{
		if (TPER == 'REMITENTE') 
		{
			if (clientId.length > 0)
			{
				if(!esdniok(clientId))
				{
					alert('Por favor, ingrese numero de DNI valido.');
					if (TPER == 'REMITENTE')
					{
						document.getElementById('txt_remit_dni').select();
						document.getElementById('txt_remit_dni').focus();
						return;
					}
				}
			}
			else return;
		}
		if (TPER == 'CONSIGNATARIO')
		{
			if (clientId.length > 0)
			{
				if(!esdniok(clientId))
				{
					alert('Por favor, ingrese numero de DNI valido.');
					if (TPER == 'CONSIGNATARIO')
					{
						document.getElementById('txt_consig_dni').select();
						document.getElementById('txt_consig_dni').focus();
						return;
					}
				}
			}
			else return;

		}
	}
	if (TDOC == 'FACTURA')
	{
		if (TPER == 'REMITENTE')
		{
			id_ofincia_origen = document.getElementById('cmb_agencia_origen').value;
			if (clientId.length > 0)
			{
				if(!esrucok(clientId))
				{
					alert('Por favor, ingrese numero de RUC valido.');
					if (TPER == 'REMITENTE')
					{
						document.getElementById('txt_remit_dni').select();
						document.getElementById('txt_remit_dni').focus();
						return;
					}
				}
			}
			else return;
		}
		if (TPER == 'CONSIGNATARIO')
		{
			if (document.getElementById('cmb_agencia_destino'))
			{
				id_ofincia_destino = document.getElementById('cmb_agencia_destino').value;
			}
			if (clientId.length > 0)
			{
				if(!esdniok(clientId))
				{
					alert('Por favor, ingrese numero de DNI valido.');
					if (TPER == 'CONSIGNATARIO')
					{
						document.getElementById('txt_consig_dni').select();
						document.getElementById('txt_consig_dni').focus();
						return;
					}
				}
			}
			else return;
		}	
	}
	if (TDOC == 'GUIA REMISION')
	{
		id_ofincia_origen = document.getElementById('cmb_agencia_origen').value;
		if (document.getElementById('cmb_agencia_destino').value)
		{
			id_oficina_destino = document.getElementById('cmb_agencia_destino').value;
		}
		if (clientId.length > 0)
		{
			if(!esrucok(clientId)){
				alert('Por favor, ingrese numero de RUC valido.');
				if (TPER == 'REMITENTE')
				{
					document.getElementById('txt_remit_dni').select();
					document.getElementById('txt_remit_dni').focus();
					return;
				}
				if (TPER == 'CONSIGNATARIO')
				{
					document.getElementById('txt_consig_dni').select();
					document.getElementById('txt_consig_dni').focus();
					return;
				}
			}
		}
		else return;
	}
	
	if (TPER == 'REMITENTE' && clientname.length == 0)
	{
		if(currentClientID_REMIT != clientId)
		{
			var aleatorio = Math.floor(Math.random()*10000001);
			currentClientID_REMIT = clientId
			ajax_get_Client.requestFile = 'js/buscar/ajax-get-Client.php?getClientId='+clientId+"&TDOC="+TDOC+"&TPER="+TPER+"&IDOF_ORIGEN="+id_ofincia_origen+"&r="+aleatorio;
			ajax_get_Client.onCompletion = showClientData;
			ajax_get_Client.runAJAX();
		}
	}
	else if(TPER == 'CONSIGNATARIO' && clientname.length == 0 && clientid_remit.length > 0)
	{
		if(currentClientID_CONSIG != clientId)
		{
			var aleatorio = Math.floor(Math.random()*10000001);
			currentClientID_CONSIG = clientId
			ajax_get_Client.requestFile = 'js/buscar/ajax-get-Client.php?getClientId='+clientId+"&TDOC="+TDOC+"&TPER="+TPER+"&IDOF_DESTINO="+id_oficina_destino+"&r="+aleatorio;
			ajax_get_Client.onCompletion = showClientData;
			ajax_get_Client.runAJAX();
		}
	}
}

function showClientData()
{
	var formObj = document.forms['encomienda_form'];	
	eval(ajax_get_Client.response);
}

function getClientDataById(TDOC, TPER)
{
	var id_persona = '';
	var id_ofincia_origen = 0;
	var id_oficina_destino = 0;
	if (document.getElementById('cmb_agencia_origen').value)
	{
		id_ofincia_origen = document.getElementById('cmb_agencia_origen').value;
	}
	if (document.getElementById('cmb_agencia_destino').value)
	{
		id_oficina_destino = document.getElementById('cmb_agencia_destino').value;
	}
	
	if (TPER == 'REMITENTE')
	{
		id_persona = document.getElementById('txt_remit_hidden').value.replace(/[^0-9]/g,'');
		num_documeto = document.getElementById('txt_remit_dni').value.replace(/[^0-9]/g,'');
	}
	if(TPER == 'CONSIGNATARIO')
	{
		id_persona = document.getElementById('txt_consig_hidden').value.replace(/[^0-9]/g,'');
		num_documeto = document.getElementById('txt_consig_dni').value.replace(/[^0-9]/g,'');
	}
	
	if((num_documeto.length == 8 || num_documeto.length == 11) && id_persona.length == 0)
	{
		return;
	}
	else if (id_persona.length > 0 && num_documeto.length == 0)
	{
		if(currentClientID_REMIT != id_persona)
		{
			if (TPER == 'REMITENTE')
			{
				if(currentClientID_REMIT != id_persona)
				{
					var aleatorio = Math.floor(Math.random()*10000001);
					currentClientID_REMIT = id_persona
					ajax_get_Client_byID.requestFile = 'js/buscar/ajax-get-ClientByID.php?getClientID='+id_persona+"&TDOC="+TDOC+"&TPER="+TPER+"&IDOF_ORIGEN="+id_ofincia_origen+"&r="+aleatorio;
					ajax_get_Client_byID.onCompletion = showClientDataById;
					ajax_get_Client_byID.runAJAX();
				}
			}
			else if(TPER == 'CONSIGNATARIO')
			{
				if(currentClientID_CONSIG != id_persona)
				{
					var aleatorio = Math.floor(Math.random()*10000001);
					currentClientID_CONSIG = id_persona
					ajax_get_Client_byID.requestFile = 'js/buscar/ajax-get-ClientByID.php?getClientID='+id_persona+"&TDOC="+TDOC+"&TPER="+TPER+"&IDOF_DESTINO="+id_oficina_destino+"&r="+aleatorio;
					ajax_get_Client_byID.onCompletion = showClientDataById;
					ajax_get_Client_byID.runAJAX();
				}
			}
		}
	}
	else
	{	
		return;
	}
}

function showClientDataById()
{
	var formObj = document.forms['encomienda_form'];	
	eval(ajax_get_Client_byID.response);
}
