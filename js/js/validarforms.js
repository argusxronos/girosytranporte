
function validacion(formulario) {

	var er_nombre = /^([a-z]|[A-Z]|á|é|í|ó|ú|ñ|ü|[0-9]|\s|\_|\.|-)+$/			
	var er_numero = /^([0-9])+$/			

	//direccion de correo electronico
	var er_email = /^(.+\@.+\..+)$/
	var x
   	
	//comprueba 50 caracteres maximo
	for(x = 1; x < 5; x++) {
		if (formulario.elements[x].value.length > 50) {
			alert('La longitud máxima permitida para cualquier campo es de 50 caracteres.')
			return false
		}
	}   	
      	
	//comprueba campo de nombre
	if(!er_numero.test(formulario.ca1fin.value)) { 
		alert('Acepta solo Numeros.')
		return false
	}
	if(!er_nombre.test(formulario.marca.value)) { 
		alert('Acepta solo letras')
		return false
	}
	if(!er_nombre.test(formulario.carroceria.value)) { 
		alert('Acepta solo letras.')
		return false
	}   	
	if(!er_numero.test(formulario.ca1ini.value)) { 
		alert('Número no valido.')
		return false
	}   	
	if(!er_numero.test(formulario.ca2ini.value)) { 
		alert('Número no valido.')
		return false
	}   	

	
	//comprueba campo de email
	if(!er_email.test(formulario.direccion.value)) { 
		alert('E-mail no válido.')
		return false
	}   	

	//alert('Los campos introducidos son CORRECTOS.')
	return true
}
