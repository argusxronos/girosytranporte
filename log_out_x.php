<?php
if (isset($_GET['logout']))
{
	// definir la zona horaria predeterminada a usar. Disponible desde PHP 5.1
	date_default_timezone_set('America/Lima');
	session_start();
	// ACTUALIZAMOS LA ULTIMA SESSION DEL USUARIO
	// SI TODOS LOS DATOS SON CORRECTO NOS CONECTAMOS CON EL SERVIDOR
	require_once 'cnn/config_trans.php';
	$db_transporte->query("UPDATE `tusuario` 
						SET `c_ultima_sesion`='".date("Y-m-d G:i:s")."' 
						WHERE `id_usuario`='".$_SESSION['ID_USUARIO']."';");	
	$_SESSION['isLogged'] = '';
	unset($_SESSION['userLogin']);
	unset($_SESSION['isLogged']);
	session_destroy();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.::TC Cerrar Sesi&oacute;n::.</title>
<script language="JavaScript">
	function cerrar()
	{

		/*if (navigator.appName=="Netscape"){
		
			{   
		}*/
		if (navigator.appName == "Microsoft Internet Explorer")
		{
			window.onfocus = function() 
			{
				window.open('','_parent','');
				window.close(); 
			}
		}
		else
		{
			window.onfocus = function() 
			{
			window.open('', '_self', '');
			window.close();
			}
		}
	}
</script>
</head>

<body onload="cerrar();">
<p>Cerrando Sesi&oacute;n</p>
</body>
</html>
