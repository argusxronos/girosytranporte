// JavaScript Document

var keyCd = null;
var altKy = false;
var ctlKy = false;
var shiftKy = false;
var mouseBtn = null;

function exitSession()
{ 
var url = 'your context path and page path';
var redirectUrl = window.location.protocol + window.location.host + url;

if (mouseBtn == null && keyCd==null && Math.abs(window.screenTop) > screen.height && Math.abs(window.screenLeft) > screen.width && Math.abs(event.clientX) > screen.width && Math.abs(event.clientY) > screen.height)
{
altKy = false;
keyCd = null;
ctlKy = false;
shiftKy = false;
window.navigate(redirectUrl);
alert('You have been signed-off from the Application.');
}
else if ((altKy && keyCd == 115) && !ctlKy && !shiftKy && keyCd != 116)
{
altKy = false;
keyCd = null;
ctlKy = false;
shiftKy = false;
window.navigate(redirectUrl);
alert('You have been signed-off from the Application.');
}
else if ((altKy && keyCd == 70) && !ctlKy && !shiftKy && keyCd != 116)
{
altKy = false;
keyCd = null;
ctlKy = false;
shiftKy = false;
//window.navigate(redirectUrl);
alert('You have been signed-off from the Application.');
}

}

function getMouseButton()
{
mouseBtn = event.button;
}

function getKeyCode()
{
keyCd = event.keyCode;
altKy = event.altKey;
ctlKy = event.ctlKey;
shiftKy = event.shiftKey;
}
