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
	 $_SESSION['OFICINA'] = '';
	unset($_SESSION['userLogin']);
	unset($_SESSION['isLogged']);
	session_destroy();
	echo "<SCRIPT LANGUAGE='javascript'>
				location.href = 'index.php';
		  </SCRIPT>";
	exit;
}
?>