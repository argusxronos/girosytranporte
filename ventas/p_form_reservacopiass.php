<?php
$Oficina_Array = $_SESSION['OFICINAS'];//encuentra los nombres de la oficinas
require_once 'cnn/config_master.php';
$id_salida=$_GET[salida];
$nro_asiento=$_GET[asientos];
$destino_salida=$_GET[destino];
$origen_ofi=$_GET[origen];
$fecha_salida=$_GET[fecha];
$hora_salida=$_GET[hora];
$piso=$_GET[p];
$ruta=$_GET[ruta];
$id_bus=$_GET[bus];
$ruta=$_GET[ruta];
$direccion="asientos=$nro_asiento&p=$piso&origen=$origen_ofi&salida=$id_salida&destino=$destino_salida&fecha=$fecha_salida&hora=$hora_salida&ruta=$ruta";
//$direccion=$_GET[];
?>
<style>
#lista{ width:100px;; margin: 5px;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; visibility:hidden}
#lista dt{ border: 1px solid #000; background-color:#666; color:#FFF;  padding-left:3px; margin-top:3px; line-height:16px}
#lista dd{ padding:3px; border-bottom:1px solid #000;border-left:1px solid #000; border-right:1px solid #000; overflow:hidden}
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

<div class='cliform'>
	<form id='reserva_form' name='reserva_form' action="p_form_ventas_action.php?insertreserva" method="post">
		<h3>Tipo: Reserva</h3>
		<table>
			<tr>
				<th>Tipo de Reserva:</th>			
				<td colspan="5">
					<input name="txt_t_reserva" id="txt_t_reserva" type="text" value="" title="Tipo de Reserva." style="width:500px;font-size:110%; font-weight:bold;text-transform:uppercase;" onfocus="copiarDatos()">
				</td>
			</tr>
			<tr>
				<th>Hora de reserva:</th>
				<td>
				<!-- echo date(" h:i:s A "); muestra la hora actual de la maquina-->				
					<input name="txt_hora" id="txt_hora" type="text" value="<?php $h1=$hora_salida;echo date('h:i:s A', strtotime("$h1 - 3 hour"));?>" title="hora de reservación" style="width:200px; text-align:center; font-size:110%;font-weight:bold; text-transform:uppercase;">
				</td>
			</tr>							
		</table>
		<br/>
		<h3>Reserva en Grupos</h3>
		<table>	
			<tr>
				<th colspan="4">Seleccionar el piso e ingresar el número de asiento y hacer click en agregar</th>				
				<th colspan="2">Piso-Asiento:</th>								
				<td><input name="otra_agencia" id="otra_agencia" type="Checkbox" title="reserva de otra Agencia" onclick="deshabilitarcombo()"><span> Reserva de otra Agencia</span></td>				
			</tr>			
			<tr>
				<th style="width:60px;">Piso:</th>
				<td style="width:60px;">
					<select name="cmb_pisos" id="cmb_pisos" style="width:50px;text-align:center;font-size:110%;font-weight:bold;">
						<option value="1">1</option>
						<option value="2">2</option>
					</select>
				</td>
				<th style="width:60px;">Asiento:</th>
				<td style="width:160px;">
					<input name="txt_asientos" id="txt_asientos" type="text" title="Numero de Asiento" style="width:50px;font-size:110%;font-weight:bold;" maxlength="2" onkeyup="extractNumber(this,0,false);" />
					<input name="button" type="button" class="button" style="width:80px;" onclick="add(document.getElementById('cmb_pisos').value +'-'+ document.getElementById('txt_asientos').value)" value="Agregar" />
				</td>
				<td colspan="2" rowspan="3">
					<form id="form1" name="form1" method="post" action="">
						<dl id="lista">
						<dt>seleccionar<div id="flecha"></div></dt>
						<dd>
							<?php	
								require_once 'cnn/config_master.php';						
								$db_transporte->query("SELECT*FROM configuracion_bus WHERE id_bus='$id_bus' ORDER BY piso");
								$asientos_bus = $db_transporte->get();

								$db_transporte->query("SELECT piso,asiento FROM record_cliente WHERE id_salida='$id_salida'");
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
				</td>				
				<td>
					<select name="cmb_oficina_reserva" id="cmb_oficina_reserva" class="combo" title="Seleccionar oficina a cual reservar" style="font-size:13px;font-weight:600; width:200px;" disabled>
						<?php
							if(count($Oficina_Array)==0){
								echo '<option value="">[NO HAY OFICINAS....!]</option>';
							}else{
								echo '<option value="">[Selecione su Oficina]</option>';
								for($fila=0;$fila<count($Oficina_Array);$fila++){
									echo '<option value="'.$Oficina_Array[$fila][0].'">'.$Oficina_Array[$fila][1].'</option>';
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align:center;font_size:120%;">
					<input name="eliminar_asiento" type="button" class="button" value="Eliminar Asiento Reservado" style="width:250px;" onclick="DeleteSecondListItem();"/>
				</td>	
				<td></td>				
			</tr>
			<tr>
				<th colspan="4">
					<input name="reserva_pagada" id="reserva_pagada"type="Checkbox" title="Reserva pagada de otra Agencia"> Reserva pagada en otra agencia
				</th>				
				<td></td>				
			</tr>	
			<tr>
				<td colspan="7" style="text-align:center; font-size:120%;">
					<input name="btn_guardar_reserva" id="btn_guardar_reserva" type="submit" class="button" value="Guardar Reserva" style="width:180px;" onclick="this.disabled = 'true'; this.value = 'Enviando...';" />
				</td>
			</tr>
			<!--Mostrar los datos de los clientes para realizar su reserva-->
			<input type="hidden" name="txt_nombre" id="txt_nombre">
			<input type="hidden" name="txt_documento" id="txt_documento">
			<input type="hidden" name="txt_ndocumento" id="txt_ndocumento">
			<input type="hidden" name="txt_genero" id="txt_genero">
			<input type="hidden" name="txt_telefono" id="txt_telefono">
			<input type="hidden" name="txt_edad" id="txt_edad">
			<input type="hidden" name="txt_nacionalidad" id="txt_nacionalidad">
			<input type="hidden" name="txt_ruc" id="txt_ruc">
			<input type="hidden" name="txt_razon" id="txt_razon">
			<!--Mostrar los datos de viaje para realizar su reserva-->	
			<input type="hidden" name="txt_fecha_viaje" id="txt_fecha_viaje">		
			<input type="hidden" name="txt_hora_viaje" id="txt_hora_viaje">
			<input type="hidden" name="txt_piso" id="txt_piso">
			<input type="hidden" name="txt_asiento" id="txt_asiento">
			<input type="hidden" name="txt_codigo_salida" id="txt_codigo_salida">
			<input type="hidden" name="txt_bus" id="txt_bus" value="<?php echo $id_bus;?>">
			<input type="hidden" name="txt_ruta" id="txt_ruta" value="<?php echo $ruta;?>">
			<input type="hidden" name="txt_salida" id="txt_salida">
			<input type="hidden" name="txt_origen" id="txt_origen">
		</table>
	</form>
</div>
