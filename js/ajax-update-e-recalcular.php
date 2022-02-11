<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged.php");
  require_once('../config_giro.php');
  
  $newTotal =$_GET['newTotal'];
  $carrera=$_GET['CARRERA'];
  $item=$_GET['ID']+1;
  $idUsuario = $_SESSION['ID_USUARIO'];
  $idOficina = $_SESSION['ID_OFICINA'];
  $db_giro->query("update temp_mov_detalle set md_carrera=".$carrera.", md_flete=".$newTotal." 
  where id_usuario=".$idUsuario." and id_oficina=".$idOficina." and temp_item =".$item );
 
  echo number_format($newTotal,2);
?>
