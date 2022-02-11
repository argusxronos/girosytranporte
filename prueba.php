<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Prueba</title>
<style>
*{ margin:0px; padding:0px; outline:none;}
#lista{ width:100px;; margin: 50px;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; visibility:hidden}
#lista dt{ border: 1px solid #000; background-color:#666; color:#FFF;  padding-left:3px; margin-top:3px; line-height:16px}
#lista dd{ padding:3px; border-bottom:1px solid #000;border-left:1px solid #000; border-right:1px solid #000; overflow:hidden}
body{font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;}
#flecha{border-bottom:0; border-right:6px solid #666; border-top:7px solid #FFF; border-left:6px solid #666; width:0; height:0; position:relative; left:75px; top:-10px}
</style>

<script>
var EvRegister=[]; 
var panino=(function(){
/* ---- métodos privados ---- */
	var metodosPrivados={
		addEvent: function(type, fn ) {
			if ( this.addEventListener ) {
				this.addEventListener( type, fn, false );
			} else if(this.attachEvent){
				var _this=this;
				var f= function(){fn.call(_this,window.event);}
				this.attachEvent( 'on'+type, f);
				this[fn.toString()+type]=f;
			}else{
				this['on'+type]=fn;
			}
			var ev={_obj:this,_evType:type,_fn:fn};
    		window.EvRegister.push(ev);
			return this;
		},
		removeEvent: function(type, fn ) {
			if( this.removeEventListener){
				this.removeEventListener( type, fn, false );
			}
    		else if(this.detachEvent){
				this.detachEvent('on'+type,this[fn.toString()+type]);
				this[fn.toString()+type]=null;
			}
			else{
	 	 		this['on'+type]=function(){};
			}
			for (var ii= 0, l=window.EvRegister.length; ii < l ; ii++) {
        		if (window.EvRegister[ii]._evType== type && window.EvRegister[ii]._obj==this && window.EvRegister[ii]._fn==fn) {
            		window.EvRegister.splice(ii, 1);
            		break;
					
        		}
    		} 
    		return this;
   		 },
		 css:function(propiedad,valor){
		 	if(!valor)
				return this.style[propiedad];
			this.style[propiedad]=valor;
			return this;
		 },
		 hover:function(a,b){
		 	this.addEvent('mouseover', a );
			this.addEvent('mouseout', b );
			return this;
		 },
		 alfa:function(value){
			this.style.opacity = value;
    		this.style.MozOpacity = value;
    		this.style.KhtmlOpacity = value;
    		this.style.filter = 'alpha(opacity=' + value*100 + ')';
    		this.style.zoom=1;
			return this;
		},
		toggle:function(a,b){
			this.style.display=this.style.display=='none'?'block':'none';
			if(!!a && !!b)
				a.parentNode.replaceChild(b,a);
			return this;
		},
		extendido:true
		 
	}
/* ---- métodos públicos ---- */
	return{
		extend:function(el,obj){
			if(el.extendido && el!=metodosPrivados)return el;
			for(var i in obj)
				el[i]=obj[i];
			return el;
		},
		get:function(id){
			if(!document.getElementById(id))return false;
			return panino.extend(document.getElementById(id),metodosPrivados);
		},
		getO:function(obj){
			return panino.extend(obj,metodosPrivados);
		},
		add:function(obj){
			panino.extend(metodosPrivados,obj);
		}
	}	
})();
function unregisterAllEvents() {
    while (EvRegister.length > 0) {
        panino.getO(EvRegister[0]._obj).removeEvent(EvRegister[0]._evType,EvRegister[0]._fn);
    }
}
var $=panino.get;
panino.getO(window).addEvent('unload',unregisterAllEvents);

function Acordeon(id,abierto){
	this.abierto=abierto || 0;
	this.id=id;
	this.init=function(){
		var _this=this;
		for(var i=0,els; els=$(this.id).getElementsByTagName('dt')[i];i++){
			var maxExpand=$(this.id).getElementsByTagName('dd')[i].offsetHeight;
			if(!this.abierto || this.abierto!=i+1){
				$(this.id).getElementsByTagName('dd')[i].style.height=0;
				$(this.id).getElementsByTagName('dd')[i].style.display='none';
				$(this.id).getElementsByTagName('dd')[i].creciendo=1;
			}else{
				$(this.id).getElementsByTagName('dd')[i].creciendo=0;
				$(this.id).getElementsByTagName('dd')[i].style.height=maxExpand+'px';
			}
			
			(function(){
				var _maxExpand=maxExpand;
				var numero=i;
				panino.getO(els).addEvent('click',function(){_this.efectuar(_maxExpand,numero);}).css('cursor','pointer');
				panino.getO(window).addEvent('unload',function(){$(_this.id).getElementsByTagName('dd')[numero].t=null;$(_this.id).getElementsByTagName('dd')[numero].creciendo=null;});
			})()
		}
		$(this.id).css('visibility','visible');
	}
	this.efectuar=function(maximo,elemento){
		var _this=this;
		if($(_this.id).getElementsByTagName('dd')[elemento].t!=null && typeof $(_this.id).getElementsByTagName('dd')[elemento].t!='undefined' && $(_this.id).getElementsByTagName('dd')[elemento].t.done_!=true)return;
		var inicio=parseInt($(this.id).getElementsByTagName('dd')[elemento].style.height);
		var fin= $(_this.id).getElementsByTagName('dd')[elemento].creciendo ? maximo  : 0; 
		$(_this.id).getElementsByTagName('dd')[elemento].creciendo=!$(_this.id).getElementsByTagName('dd')[elemento].creciendo;
		$(_this.id).getElementsByTagName('dd')[elemento].t=new Transition(SineCurve, 500, function(percentage) {
			if(fin<inicio){
				var delta=inicio-fin;
    			$(_this.id).getElementsByTagName('dd')[elemento].style.height=(inicio-(percentage*delta))+'px';
			}
			else{
				var delta=fin-inicio;
				$(_this.id).getElementsByTagName('dd')[elemento].style.height=(inicio+(percentage*delta))+'px';
			}
			if(parseInt($(_this.id).getElementsByTagName('dd')[elemento].style.height)<1)
				$(_this.id).getElementsByTagName('dd')[elemento].style.display='none';
			else
				$(_this.id).getElementsByTagName('dd')[elemento].style.display='block';
			});
		$(_this.id).getElementsByTagName('dd')[elemento].t.run();
	}
	
}
function Transition(curve, milliseconds, callback) {
		this.done_=false;
    	this.curve_ = curve;
    	this.milliseconds_ = milliseconds;
    	this.callback_ = callback;
    	this.start_ = new Date().getTime();
		this.run=function() {
			var _this=this;
   			if(!this.hasNext()) {
				window['globalIntervalo']=0;
				return;
			}
    		this.callback_(this.next());
			setTimeout(function(){_this.run.call(_this);}, 0);
		}
		this.hasNext=function() {
    		if(this.done_)
				return false;
    		var now = new Date().getTime();
    		if ((now - this.start_) > this.milliseconds_) {
       			this.done_ = true;
    		}
    		return true;
		}
		this.next=function() {
    		var now = new Date().getTime();
    		var percentage = Math.min(1, (now - this.start_) / this.milliseconds_);
			return this.curve_(percentage);
		}
}

function SineCurve(percentage) {
	return (1 - Math.cos(percentage * Math.PI)) / 2;
}
onload=function(){
var t=new Acordeon('lista');
t.init();
}
</script>
</head>

<body>

<form id="form1" name="form1" method="post" action="">
<dl id="lista">
<dt>seleccionar<div id="flecha"></div></dt>
<dd>
	<?php	
		require_once 'cnn/config_master.php';						
		$db_transporte->query("SELECT*FROM configuracion_bus WHERE id_bus='25' ORDER BY piso");
		$asientos_bus = $db_transporte->get();

		$db_transporte->query("SELECT piso,asiento FROM record_cliente WHERE id_salida='16233695'");
		$asientos_ocupados=$db_transporte->get();	
		if(count($asientos_bus)!=0)	{
			for($var=0;$var<count($asientos_bus);$var++){
				for($vari=0;$vari<count($asientos_ocupados);$vari++){
					if($asientos_ocupados[$vari][1]==$asientos_bus[$var][8] && $asientos_ocupados[$vari][0]==$asientos_bus[$var][3]){
						$existe=$asientos_bus[$var][8];													
					}
					if($asientos_ocupados[$vari][1]==$asientos_bus[$var][7] && $asientos_ocupados[$vari][0]==$asientos_bus[$var][3]){
						$existe2=$asientos_bus[$var][7];													
					}			
					if($asientos_ocupados[$vari][1]==$asientos_bus[$var][6] && $asientos_ocupados[$vari][0]==$asientos_bus[$var][3]){
						$existe3=$asientos_bus[$var][6];													
					}							
					if($asientos_ocupados[$vari][1]==$asientos_bus[$var][5] && $asientos_ocupados[$vari][0]==$asientos_bus[$var][3]){
						$existe4=$asientos_bus[$var][5];													
					}
					if($asientos_ocupados[$vari][1]==$asientos_bus[$var][4] && $asientos_ocupados[$vari][0]==$asientos_bus[$var][3]	){
						$existe5=$asientos_bus[$var][4];													
					}
				}
				if($existe!=$asientos_bus[$var][8]){
					if($asientos_bus[$var][8]!="TM" && $asientos_bus[$var][8]!="TI" && $asientos_bus[$var][8]!="TD" && $asientos_bus[$var][8]!="TV" && $asientos_bus[$var][8]!="ES" && $asientos_bus[$var][8]!="TI" && $asientos_bus[$var][8]!="TR" && $asientos_bus[$var][8]!="BA" && $asientos_bus[$var][8]!="" && $asientos_bus[$var][8]!=" "){
						echo '<input name="libres" id="libres" type="checkbox" value="'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][8].'">'.$asientos_bus[$var][3].' - '.$asientos_bus[$var][8].'<br/>';	
					}
				}
				if($existe2!=$asientos_bus[$var][7]){
					if($asientos_bus[$var][7]!="TM" && $asientos_bus[$var][7]!="TI" && $asientos_bus[$var][7]!="TD" && $asientos_bus[$var][7]!="TV" && $asientos_bus[$var][7]!="ES" && $asientos_bus[$var][7]!="TI" && $asientos_bus[$var][7]!="TR" && $asientos_bus[$var][7]!="BA" && $asientos_bus[$var][7]!="" && $asientos_bus[$var][7]!=" "){
						echo '<input name="libres" id="libres" type="checkbox" value="'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][7].'">'.$asientos_bus[$var][3].' - '.$asientos_bus[$var][7].'<br/>';	
					}
				}
				if($existe3!=$asientos_bus[$var][6]){
					if($asientos_bus[$var][6]!="TM" && $asientos_bus[$var][6]!="TI" && $asientos_bus[$var][6]!="TD" && $asientos_bus[$var][6]!="TV" && $asientos_bus[$var][6]!="ES" && $asientos_bus[$var][6]!="TI" && $asientos_bus[$var][6]!="TR" && $asientos_bus[$var][6]!="BA" && $asientos_bus[$var][6]!="" && $asientos_bus[$var][6]!=" "){
						echo '<input name="libres" id="libres" type="checkbox" value="'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][6].'">'.$asientos_bus[$var][3].' - '.$asientos_bus[$var][6].'<br/>';		
					}
				}
				if($existe4!=$asientos_bus[$var][5]){
					if($asientos_bus[$var][5]!="TM" && $asientos_bus[$var][5]!="TI" && $asientos_bus[$var][5]!="TD" && $asientos_bus[$var][5]!="TV" && $asientos_bus[$var][5]!="ES" && $asientos_bus[$var][5]!="TI" && $asientos_bus[$var][5]!="TR" && $asientos_bus[$var][5]!="BA" && $asientos_bus[$var][5]!="" && $asientos_bus[$var][5]!=" "){
						echo '<input name="libres" id="libres" type="checkbox" value="'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][5].'">'.$asientos_bus[$var][3].' - '.$asientos_bus[$var][5].'<br/>';		
					}
				}
				if($existe5!=$asientos_bus[$var][4]){
					if($asientos_bus[$var][4]!="TM" && $asientos_bus[$var][4]!="TI" && $asientos_bus[$var][4]!="TD" && $asientos_bus[$var][4]!="TV" && $asientos_bus[$var][4]!="ES" && $asientos_bus[$var][4]!="TI" && $asientos_bus[$var][4]!="TR" && $asientos_bus[$var][4]!="BA" && $asientos_bus[$var][4]!="" && $asientos_bus[$var][4]!=" "){
						echo '<input name="libres" id="libres" type="checkbox" value="'.$asientos_bus[$var][3].'-'.$asientos_bus[$var][4].'">'.$asientos_bus[$var][3].' - '.$asientos_bus[$var][4].'<br/>';	
					}
				}																	
			}								
		}
		
	?>
</dd>
</dl>

</form>
</body>
</html>