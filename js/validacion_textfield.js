var nav = window.Event ? true : false;

	
function acceptNum(field, event) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;  
	if (keyCode == 13)
	{
		return handleEnter(field, event);
	}
	else
	{
		// NOTA: Backspace = 8->si, Enter = 13->no, \'0\' = 48, \'9\' = 57, \'.\'=46->si
		var key = nav ? event.which : event.keyCode;
		return (key < 13 || key ==46 || (key >= 48 && key <= 57));
	}
}
function acceptletras(field, event) { 
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	if (keyCode == 13)
	{
		return handleEnter(field, event)
	}
	else
	{
		if (keyCode == 8)
		{
			return true;
		}
		//alert(keyCode);
		patron =/[A-Za-z0-9\.()\/\-\&ñÑáéíóúÁÉÍÓÚ,\s]/; 
		te = String.fromCharCode(keyCode);
		if (patron.test(te))
		{
			if(document.getElementById(field.name + '_hidden'))
				document.getElementById(field.name + '_hidden').value = '';
		}
		if (keyCode == 241 || keyCode == 209 || keyCode == 225 || keyCode == 193 || keyCode == 201 || keyCode == 233 || keyCode == 205 || keyCode == 237 || keyCode == 243 || keyCode == 211 || keyCode == 218 || keyCode == 250)
		{
			if(document.getElementById(field.name + '_hidden'))
				document.getElementById(field.name + '_hidden').value = '';

			return String.fromCharCode(keyCode);
		}
		return patron.test(te);
	}
}

function acceptletras2(field, event) { 
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	if (keyCode == 13)
	{
		return handleEnter(field, event);
	}
	else
	{
		if (keyCode == 8)
		{
			return true;
		}
		//alert(keyCode);
		patron =/[A-Za-z\/ñÑáéíóúÁÉÍÓÚ,\s]/; 
		te = String.fromCharCode(keyCode);
		return patron.test(te);
	}
}

function acceptletras_descripcion(inputObj, event) { 
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	if (keyCode == 13)
	{
		if(inputObj.value == "")
		{
			return true;
		}
		else
			return E_Insert_Temp(inputObj, event);
	}
	else
	{
		if (keyCode == 8)
		{
			return true;
		}
		//alert(keyCode);
		patron =/[A-Za-z0-9\/.\ñÑáéíóúÁÉÍÓÚ,\s]/; 
		te = String.fromCharCode(keyCode);
		return patron.test(te);
	}
}

function acceptletras_descripcion2(inputObj, event) { 
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; 
	if (keyCode == 13)
	{
		if(inputObj.value == "")
		{
			return true;
		}
		else
			return handleEnter(inputObj, event);
	}
	else
	{
		if (keyCode == 8)
		{
			return true;
		}
		//alert(keyCode);
		patron =/[A-Za-z0-9()\+\/.\ñ\ÑáéíóúÁÉÍÓÚ,\s]/;
		te = String.fromCharCode(keyCode);
		return patron.test(te);
	}
}

function jsf_Empty_Clave(field)
{
	if ((field.value).length == 0)
	{
		alert('Por favor, ingrese una CLAVE DE SEGURIDAD.');
	}
	else if ((field.value).length < 4)
	{
		document.getElementById(field.name).focus();
		document.getElementById(field.name).select();
		alert('La CLAVE DE SEGURIDAD debe ser de 4 digitos.');
	}
}
function validaciones(email){
	if(!er_email.test(email.email.value)) { 
		alert('E-mail no válido.')
		return false
	} 
}
