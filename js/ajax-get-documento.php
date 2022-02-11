<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged.php");
	require_once('../config_trans.php');
	
	$id = $_GET['ID'];
	$TYPE = $_GET['TYPE'];
	$IDOFICINA  = $_GET['IDOFICINA'];
	// SI NO TENEMOS LA SESION DE LA NUMERACION DE LOS DOCUMENTOS LOS CARGAMOS
	/* CODIGO PARA OBTENER LOS CODIGO Y NOMBRES DE LOS DOCUMENTOS ASIGNADOS A ESTA OFICINA */
	$db_transporte->query("SELECT `nc`.`serie`, (`nc`.`numero_actual` + 1) AS `numero_actual`
							FROM `numeracion_documento` AS `nc`
							WHERE `nc`.`idoficina` = '".$IDOFICINA."'
							AND (`nc`.`tipo_operacion` = ".$TYPE."
							OR `nc`.`tipo_operacion` = 4)
							AND `nc`.`id` = ".$id.";");
	$Numeracion_Array = $db_transporte->get();
	if (count($Numeracion_Array) > 0)
		echo '<input name="txt_serie" id="txt_serie" type="text" value="'.$Numeracion_Array[0][0].'" readonly="readonly" 
				title="Número de Serie" style="width:90px; font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" /> 
				- 
				<input name="txt_numero" id="txt_numero" type="text" value="'.$Numeracion_Array[0][1].'" class="field" tabindex="5"
				 title="Número del Documento." style=" font-size:140%; color:#FF0000; font-weight:bold; text-align:center;" 
				 onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,0,false);" onfocus="this.select()" />';
?>
