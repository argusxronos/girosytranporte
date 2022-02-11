<?php
	// definir la zona horaria predeterminada a usar. Disponible desde PHP 5.1
	date_default_timezone_set('America/Lima');
	if ((!isset($_SESSION['IS_LOGGED'])) || $_SESSION['TIPO_USUARIO'] < 2)
	{
		echo "<SCRIPT LANGUAGE='javascript'>
					location.href = 'index.php';
					// alert('Solo usuarios que han iniciado sesión pueden acceder a esta página.');
			  </SCRIPT>";
		exit;
	}
?>
