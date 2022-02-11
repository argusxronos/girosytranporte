<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged.php");
	$id = $_GET['ID'];
	$serie = $_GET['SERIE'];
	$numero = $_GET['NUMERO'];
	$cont = $_GET['CONT'];
	echo '<form name="frm_giro_boleta_'.$id.'" class="">

<input name="txt_serie_'.$id.'" value="'.$serie.'" tabindex="15" type="text" style="width:30px;color:red;" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,2,false);" onfocus="this.select();"	 /> 
- <input name="txt_numero_'.$id.'" value="'.$numero.'" tabindex="16" type="text" onkeyup="extractNumber(this,2,false);" style="width:50px;" onfocus="this.select();" onkeypress="Update_Boleta_Giro(this, event, '.$id.', document.frm_giro_boleta_'.$id.'.txt_serie_'.$id.'.value, document.frm_giro_boleta_'.$id.'.txt_numero_'.$id.'.value, '.$cont.');" />

</form>';
?>
