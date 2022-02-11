function centraVentana(ancho,alto) {
	if (window.screen) {
		var aw = screen.availWidth;var ah = screen.availHeight;window.resizeTo(ancho,alto);window.moveTo((aw-ancho)/2,(ah-alto)/2);
	}
}
function algunCheck(form){
	for(i=0 ; i<form.elements.length; i++){
		if(form.elements[i].type == "checkbox"){if(form.elements[i].checked){return true;}}
	}
	return false;
}
function esTeclaNumero(e) {
	var valid = "0123456789";
	var key = String.fromCharCode(event.keyCode);
		if (valid.indexOf("" + key) == "-1") return false;
}
function validarHora(strHora){
	if (longitudcorrecta(strHora, 5)) {
		strHora += ":00";
	}
	return !(!(/[0-2][0-9]:[0-5][0-9]:[0-5][0-9]/.test(strHora)) || (strHora.substring(0,2)<0 || strHora.substring(0,2)>23));
}
function formateafecha(valor){
  var l = StringTokenizer(valor, "/");
  return rellena(l[0], "0", 2) + "/" + rellena(l[1], "0", 2) + "/" + l[2];
}
function estelefono(valor){
  var pattern = "/\\b(^(\\d+)(\\-\\d+)$)\\b/gi";
	return valor.match(eval(pattern));
}
function esEntero(numero){
  tokens = StringTokenizer(numero, '.');
  return ( (tokens.length<=1)&&(esnumero(numero)) );
}
function validaDecimal(numero, dec){
  tokens = StringTokenizer(numero, '.');
  return (( tokens.length>1 )? (tokens[tokens.length-1].length > 0 && tokens[tokens.length-1].length <= dec) : true)&&(esdecimal(numero));
}
function esdecimal(valor){
  var pattern = "/\\b(^(\\d+)(\\.\\d+)$)\\b/gi";
	return valor.match(eval(pattern));
}
function validacorreo(myString) {
	return myString.match(/\b(^(\S+@).+((\.gob)|(\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.biz)|(\.org)|(\..{2,2}))$)\b/gi)
}
/* fn & ext Rt T F */
function validanombrearchivo(nombre, ext){
  var pattern = "/\\b(^(((\\S)|(\\s))+)(\\."+ext+")$)\\b/gi";
	return nombre.match(eval(pattern));
}
/* Rt Arr */
function StringTokenizer(cad, delim){
  var cads = new Array();
  var n = cad.length;
  var j = 0;
  var ic = 0;
  for (i=0;i<n;i++){
    if ( cad.charAt(i)==delim ){ cads[j] = cad.substring(ic, i); ic = i+1; j++; }
  }
  cads[j] = cad.substring(ic, n);
  return cads;
}
/*Rt m ltr: mes # / 1-12, may 1 o 0 M o m, cap 1 o 0 M 1ra lt*/
function mesenletras(mes, may, cap){
  if ( !esnumero(mes) ) return "-";
  var imes = parseInt(mes, 10);
  var tmes = "";
  if ( imes == 1 ) tmes = "enero";
  else if ( imes == 2 ) tmes = "febrero";
  else if ( imes == 3 ) tmes = "marzo";
  else if ( imes == 4 ) tmes = "abril";
  else if ( imes == 5 ) tmes = "mayo";
  else if ( imes == 6 ) tmes = "junio";
  else if ( imes == 7 ) tmes = "julio";
  else if ( imes == 8 ) tmes = "agosto";
  else if ( imes == 9 ) tmes = "setiembre";
  else if ( imes == 10 ) tmes = "octubre";
  else if ( imes == 11 ) tmes = "noviembre";
  else if ( imes == 12 ) tmes = "diciembre";
  if ( may == 1) tmes = tmes.toUpperCase(); 
  if ( cap == 1) tmes = tmes.substring(0,1).toUpperCase() + tmes.substring(1, tmes.length);
  return tmes;
}
/* -1: err, 1: f1>f2, 2: f1<f2, 0: f1=f2 */
function comparafecha(fecha1, fecha2){
  if ( !checkdate(fecha1) || !checkdate(fecha2) ) return -1;
  dia = fecha1.substring(0,2)
  mes = fecha1.substring(3,5)
  anho = fecha1.substring(6,10)
  fecha1x = anho + mes + dia
  dia = fecha2.substring(0,2)
  mes = fecha2.substring(3,5)
  anho = fecha2.substring(6,10)
  fecha2x = anho + mes + dia
  return (fecha1x>fecha2x?1:(fecha1x<fecha2x?2:0));
}
function nada(){}
function corta(campo, longitud, cars) {
  if (campo.value.length>longitud) campo.value=campo.value.substring(0,longitud);
  cuenta(campo, cars);
}
function cuenta(campo, cars) { cars.value=campo.value.length; }
function rellena(dato, caracter, tamanho){
  dato_trim = trim(dato);
  len = dato_trim.length;
  dato_fill = "";
  for (var i=0;i<tamanho-len;i++){ dato_fill+=caracter; }
  dato_fill+=dato_trim;
  return dato_fill;
}
function checkdate(fecha){
  var err=0
  if ( fecha.length != 10) err=1
  dia = fecha.substring(0,2)
  slash1 = fecha.substring(2,3)
  mes = fecha.substring(3,5)
  slash2 = fecha.substring(5,6)
  anho = fecha.substring(6,10)
  if ( dia<1 || dia>31) err = 1
  if ( slash1 != '/' ) err = 1
  if ( mes<1 || mes>12) err = 1
  if ( slash1 == '/' && slash2 != '/' ) err = 1
  if ( anho < 0 || anho > 2200 ) err = 1
  if ( mes == 4 || mes == 6 || mes == 9 || mes == 11 ){
    if (dia==31) err=1
  }
  if (mes == 2){
    var g = parseInt(anho/4)
    if (isNaN(g)){
      err = 1
    }
    if (dia >29) err =1
    if (dia ==29 && ((anho/4)!=parseInt(anho/4))) err=1
  }
  return (!(err==1));
}
function esnulo(campo){ return (campo == null||campo=="");}
function esnulooguion(campo){
  return esnulo(campo) || ( trim( campo ) == "-" );
}
function esnumero(campo){ return (!(isNaN( campo )));}
function longitudcorrecta( campo, len ){
  if ( campo != null ) return ( campo.length == len );
  else return false;
}
function mayuscula(campo){return campo.toUpperCase();}
function minuscula(campo){return campo.toLowerCase();}
function eslongrucok(ruc){return ( ruc.length == 11 );}
// validacion longitud dni
function eslongdniok(ruc){return ( ruc.length == 8 );}
function eslongcontrasenhaok(contrasenha){
  return (contrasenha.length >= longcontrasenhaok());
}
function longcontrasenhaok(){ return 6;}
function esnegativo(valor){ return (valor < 0);}
function esrucok(ruc){
  return (!( esnulo(ruc) || !esnumero(ruc) || !eslongrucok(ruc) || !valruc(ruc) ));
}
function esdniok(ruc){
  return (!( esnulo(ruc) || !esnumero(ruc) || !eslongdniok(ruc)));
}
function valruc(valor){
  valor = trim(valor)
  if ( esnumero( valor ) ) {
    if ( valor.length == 8 ){
      suma = 0
      for (i=0; i<valor.length-1;i++){
        digito = valor.charAt(i) - '0';
        if ( i==0 ) suma += (digito*2)
        else suma += (digito*(valor.length-i))
      }
      resto = suma % 11;
      if ( resto == 1) resto = 11;
      if ( resto + ( valor.charAt( valor.length-1 ) - '0' ) == 11 ){
        return true
      }
    } else if ( valor.length == 11 ){
      suma = 0
      x = 6
      for (i=0; i<valor.length-1;i++){
        if ( i == 4 ) x = 8
        digito = valor.charAt(i) - '0';
        x--
        if ( i==0 ) suma += (digito*x)
        else suma += (digito*x)
      }
      resto = suma % 11;
      resto = 11 - resto
      
      if ( resto >= 10) resto = resto - 10;
      if ( resto == valor.charAt( valor.length-1 ) - '0' ){
        return true
      }      
    }
  }
  return false
}
function longitudmayor( campo, len ){
  return ( campo != null )? (campo.length > len) : false;
}
function estaentre(campo, inicio, fin){
  if ( campo != null ) return ( campo.length >= inicio && campo.length <= fin );
  else return false;
}
var sorry="SUNAT - Derechos Reservados 2004";
function click(e){
   if (document.all) if (event.button == 2){alert(sorry);return false;} 
   if (document.layers) if (e.which == 3){alert(sorry);return false;}
}
if (document.layers){ document.captureEvents(Event.MOUSEDOWN);}
document.onmousedown=click;
function abreventana(i, j) {
  window.open(i, j, "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=yes,resizable=yes,copyhistory=0,width=600,height=450")
}
var da = (document.all) ? 1 : 0;
var pr = (window.print) ? 1 : 0;
var mac = (navigator.userAgent.indexOf("Mac") != -1); 
function printPage(frame, arg) {
  if (frame == window){printThis();}
  else {link = arg; printFrame(frame); }
  return false;
}
function printThis() {
  if (pr) { // NS4, IE5
    window.print();
  } else if (da && !mac) { // IE4 (Windows)
    vbPrintPage();
  } else { // other browsers
    alert("Disculpe, su browser no soporta esta aplicacion.");
  }
}
function trim(cadena){
  cadena2 = "";
  len = cadena.length;
  for ( var i=0; i <= len ; i++ ) if ( cadena.charAt(i) != " " ){cadena2+=cadena.charAt(i);	}
  return cadena2;
}
function printFrame(frame) {
  if (pr && da) { // IE5
    frame.focus();
    window.print();
    link.focus();
  } else if (pr) { // NS4
    frame.print();
  } else if (da && !mac) { // IE4 (Windows)
    frame.focus();
    setTimeout("vbPrintPage(); link.focus();", 100);
  } else { // other browsers
    alert("Disculpe, su browser no soporta esta aplicacion.");
  }
}
if (da && !pr && !mac) with (document) {
  writeln('<'+'OBJECT ID="WB" WIDTH="0" HEIGHT="0" CLASSID="clsid:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>');
  writeln('<'+'SCRIPT LANGUAGE="VBScript">');
  writeln('Sub window_onunload');
  writeln('  On Error Resume Next');
  writeln('  Set WB = nothing');
  writeln('End Sub');
  writeln('Sub vbPrintPage');
  writeln('  OLECMDID_PRINT = 6');
  writeln('  OLECMDEXECOPT_DONTPROMPTUSER = 2');
  writeln('  OLECMDEXECOPT_PROMPTUSER = 1');
  writeln('  On Error Resume Next');
  writeln('  WB.ExecWB OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER');
  writeln('End Sub');
  writeln('<'+'/SCRIPT>');
}