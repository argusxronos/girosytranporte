// JavaScript Document
//Descripcion del motor ajax
var net = new Object();

//Constructor
net.CargadorContenidos = function(url, funcion, funcionError, metodo, parametros, contentType, functionLoading)
{
	this.url = url;
	this.req = null;
	this.onload = funcion;
	this.onerror = (funcionError) ? funcionError : this.defaultError;
	this.functionLoading = (functionLoading) ? functionLoading : false;
	this.cargaContenidoXML(url, metodo, parametros, contentType);
}

net.CargadorContenidos.prototype = {
	cargaContenidoXML : function (url, metodo, parametros, contentType) {
		try
		{
			// Creacion del objeto AJAX para navegadores no IE
			this.req = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e1)
		{
			try
			{
				// Creacion del objet AJAX para IE
				this.req = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e2)
			{
				if (!this.req && typeof XMLHttpRequest!='undefined') 
					this.req = new XMLHttpRequest();
			}
		}
/*
		try {
			this.req = new XMLHttpRequest();
		}
		catch (e1) {
			try {
				this.req = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e2) {
				this.req = new ActiveXObject("Msxml2.XMLHTTP");
			}
		}*/
		
		
		if (this.req) {
			
			try {
				if (this.req.readyState == 4 || this.req.readyState == 0) {
					var loader = this;
					this.req.onreadystatechange = function() {
						loader.onReadyState.call(loader); 
					}
					this.req.open(metodo, url, true);
					if (contentType) {
						this.req.setRequestHeader("Content-Type", contentType);	
					}
					
					this.req.send(parametros);
				}
			}
			catch(e1) {
				this.onerror.call(this);
			}
		}		
	},
	
	onReadyState : function () {
		if (this.req.readyState == 4) {
			if (this.req.status == 200 || this.req.status == 0) {
				this.onload.call(this);
			}
			else {
				this.onerror.call(this);
			}
		}
		else if (this.functionLoading) {
			this.functionLoading();
		}
	},
	
	defaultError : function () {
		alert("Se ha producido un error al obtener los datos"
			  +"\n\nreadyState: " + this.req.readyState
			  +"\nstatus: " + this.req.status+" - "+this.req.statusText
			  +"\nheaders: " + this.req.getAllResponseHeaders());
	}		
}