<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged.php");
	require_once('../config_giro.php');
	$id = $_GET['ID'];
	$value = 0;
	if(isset($_GET['value']) && strlen($_GET['value']) > 0)
		$value = $_GET['value'];
	// Modificamos el estado copiado a 0
	$db_giro->query("UPDATE `g_movimiento` 
					SET `esta_copiado`= IF(`esta_copiado` = 0, 1, 0)
					, `copiado_pagina`= '".$value."'
					WHERE `id_movimiento`='".$id."';");
	// MOSTRAMOS EL CAMBIO EN LA PAGINA WEB
	if (isset($_GET['to']) && $_GET['to'] == 'uncopied')
		echo '<input type="text" name="txt_copiado_'.$id.'" id="txt_copiado_'.$id.'" value="0" title="Ingrese el n&uacute;mero de p&aacute;gina del cuaderno en la que fue copiado el Giro y presione ENTER." style="width:30px;text-align:center;"  onkeypress="Update_Copy(event, this, '.$id.');" onkeyup = "extractNumber(this,0,false);" onfocus="this.select();" />';
	else
		echo '<input type="checkbox" name="txt_copiado_'.$id.'" value="'.$id.'" onClick="Update_Uncopy(event, this, '.$id.');" checked="checked" title="P&aacute;gina: '.$value.'."  />/<span title="N&uacute;mero de la p&aacute;gina en la que fue copiada">'.$value.'<span>';
?>
