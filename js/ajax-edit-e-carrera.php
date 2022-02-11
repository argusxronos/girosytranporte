<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged.php");
	$id = $_GET['ID'];
  $total =$_GET['TOTAL'];
	echo '<input name="txt_carrera_'.$id.'" style="width:60px;" type="text" onkeyup="extractNumber(this,2,false);" 
  onkeypress="return Update_Carrera(this, event,'.$id.');" />';
?>
