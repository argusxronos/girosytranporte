<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged.php");
	$id = $_GET['ID'];
	$num_vale = $_GET['value'];
	echo '<input name="txt_vale_'.$id.'" style="width:60px;" type="text" onkeyup="extractNumber(this,2,false);" onKeyPress="Update_Vale_Giro(this,event,'.$id.')" value="'.$num_vale.'" onFocus="this.select();" />';
?>
