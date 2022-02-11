<?php
	session_start();
	require_once("../is_logged_niv2.php");
	require_once('../config_giro.php');
	// definir la zona horaria predeterminada a usar. Disponible desde PHP 5.1
	date_default_timezone_set('America/Lima');
	$id = $_GET['ID'];
	$valor_carrera = $_GET['value'];
	if (strlen($valor_carrera) == 0)
	{
		$valor_carrera = 0;
	}
  echo '<a style="color: green;" onClick="Edit_Carrera(event, '.$id.');">' . number_format($valor_carrera,2) .'</a>';
?>
