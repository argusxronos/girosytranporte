<?php
	// definir la zona horaria predeterminada a usar. Disponible desde PHP 5.1
	date_default_timezone_set('America/Lima');
	if (!isset($_SESSION['USUARIO']) && !isset($_SESSION['IS_LOGGED']))
	{
		echo "<script type='text/javascript'> location.href = 'log_in.php'; </script>";
		exit;
	}
?>
