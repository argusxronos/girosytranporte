// JavaScript Document
var myclose = false;

function ConfirmClose()
{
	if (event.clientY < 0)
	{
		setTimeout('myclose=false',100);
		myclose=true;
	}
}

function HandleOnClose()
{
	if (myclose==true)
	{
		window.open('log_out_x.php?logout','BOLETA','scrollbars=no, resizable=no, width=50, height=50, status=no, location=no, toolbar=no');
	}
}