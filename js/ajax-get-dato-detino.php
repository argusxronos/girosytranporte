<?php
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("../is_logged.php");
	require_once('../config_trans.php');
	
	$id = $_GET['ID'];
	$TYPE = $_GET['TYPE'];	
	// SI NO TENEMOS LA SESION DE LA NUMERACION DE LOS DOCUMENTOS LOS CARGAMOS
	/* CODIGO PARA OBTENER LOS CODIGO Y NOMBRES DE LOS DOCUMENTOS ASIGNADOS A ESTA OFICINA */
	$db_transporte->query("SELECT oficinas.`idoficina`,oficinas.`oficina`,ruta.`destino`,ruta.`hora`,id_ruta
							FROM oficinas INNER JOIN ruta ON oficinas.`idoficina`=ruta.`idoficina`
							WHERE ruta.`id_ruta`='$id';");
	$Datos_Array = $db_transporte->get();
	if (count($Datos_Array) > 0){
		echo '<table>';
			echo '<tr id="DivDocumentoSN">';
				echo '<th><span>*</span>Destino : </th>';
				echo '<td id="dato2"><input id="txt_destino"  disabled="" type="text" name="txt_destino" value="'.$Datos_Array[0][2].'" title="Destino." tabindex="3" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>';
				echo '<th><span>*</span>Hora : </th>';
				echo '<td id="dato2"><input id="txt_hora"  type="text" name="txt_hora" value="'.$Datos_Array[0][3].'" tabindex="4" style="width:200px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;"></td>';
		   echo '</tr>';
		echo '</table>';		
		  }
?>
