<?php
	session_start();
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("../function/validacion.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
	$Error = false;
	$MsjError = '';
	// OBTENEMOS EL ID OFICINA Y EL ID USUARIO
	$ID_OFIC = $_SESSION['ID_OFICINA'];
	$ID_USUARIO = $_SESSION['ID_USUARIO'];
	// OBTENMOS LOS DATOS DE LA ENCOMIENDA
	$CODIGO = $_GET['CODIGO'];
	$NUM_ITEM = $_GET['NUM_ITEM'];
	$TIPO_DOC = '';
	if (isset($_GET['TDOC']) && strlen($_GET['TDOC']) > 0)
	{
		$TIPO_DOC = $_GET['TDOC'];
	}
	require_once("../is_logged.php");
	require_once('../config_giro.php');
	// definir la zona horaria predeterminada a usar. Disponible desde PHP 5.1
	date_default_timezone_set('America/Lima');
	$fecha_actual = new DateTime(date("Y-m-d"));
	$db_giro->query("CALL `USP_DELETE_TEMP_BY_IDITEM`
					(
						@vERROR
						, @vMSJ_ERROR
						, $ID_USUARIO
						, $ID_OFIC
						, '$CODIGO'
						, '$NUM_ITEM'
						, '".$fecha_actual->format("Y-m-d")."'
					);");
	if (!$db_giro)
	{
		MsjErrores('Error en la transacción, Comuniquese con el Administrador.');
	}
	else
	{
		$db_giro->query("SELECT @vERROR AS `ERROR`, @vMSJ_ERROR AS `MSJ_ERROR`;");
		$Error_Array = $db_giro->get();
		$Error = $Error_Array[0][0];
		$MsjError = str_replace("\n", "<br>", $Error_Array[0][1]);
	}
	// MOSTRAMOS LOS RESULTADOS PARA LA GUIA INTERNA
	if ($TIPO_DOC == 'GUIA INTERNA')
	{
		$db_giro->query("SELECT `temp_mov_detalle`.`md_cantidad`
						, `temp_mov_detalle`.`md_descripcion`
						, `temp_mov_detalle`.`temp_item`
						FROM `temp_mov_detalle`
						WHERE `temp_mov_detalle`.`id_usuario` = $ID_USUARIO
						AND `temp_mov_detalle`.`id_oficina` = $ID_OFIC
						AND `temp_mov_detalle`.`tmp_codigo` = $CODIGO;");
		$List_Item = $db_giro->get();
		$List_Cantidad = "";
		echo '<table width="710" border="0">';
		if ($Error == true)
		{
			echo "<tr>";
				echo '<th colspan="3" style="text-align:center;"><span>'.$MsjError.'</span></th>';
			echo "</tr>";

		}
		for ($fila = 0; $fila < count($List_Item); $fila++)
		{
			
			  echo "<tr>";
				echo '<td width="108" style="text-align:center;">'.$List_Item[$fila][0].'</td>';
				echo '<td width="542">'.$List_Item[$fila][1].'</td>';
				echo '<td width="46" style="text-align:center;"><img src="./img/operacion/Symbol-Delete.png" width="24" height="24" title="Eliminar esta Encomienda."  onclick="E_Delete_Temp('.$List_Item[$fila][2].', \'GUIA INTERNA\')" onMouseOver="this.style.cursor=\'hand\'" /></td>';
			  echo "</tr>";
		}
		echo "</table>";
	}
	// MOSTRAMOS LOS RESULTADOS PARA LA BOLETA
	else
	{
		$db_giro->query("SELECT `temp_mov_detalle`.`md_cantidad`
						, `temp_mov_detalle`.`md_descripcion`
						, `temp_mov_detalle`.`temp_item`
						, `temp_mov_detalle`.`md_flete`
						, `temp_mov_detalle`.`md_carrera`
						, (`temp_mov_detalle`.`md_flete` +  `temp_mov_detalle`.`md_carrera`) AS 'IMPORTE'
						FROM `temp_mov_detalle`
						WHERE `temp_mov_detalle`.`id_usuario` = $ID_USUARIO
						AND `temp_mov_detalle`.`id_oficina` = $ID_OFIC
						AND `temp_mov_detalle`.`tmp_codigo` = $CODIGO
						AND `temp_mov_detalle`.`md_fecha` = '".$fecha_actual->format("Y-m-d")."';");
		$List_Item = $db_giro->get();
		$List_Cantidad = "";
		// OBTENEMOS EL TOTAL DEL IMPORTE
		$db_giro->query("SELECT SUM(`temp_mov_detalle`.`md_importe`) AS 'TOTAL'
						FROM `temp_mov_detalle`
						WHERE `temp_mov_detalle`.`id_usuario` = $ID_USUARIO
						AND `temp_mov_detalle`.`id_oficina` = $ID_OFIC
						AND `temp_mov_detalle`.`tmp_codigo` = $CODIGO
						AND `temp_mov_detalle`.`md_fecha` = '".$fecha_actual->format("Y-m-d")."';");
		$total = $db_giro->get('TOTAL');
		echo '<table width="710" border="0">';
		if ($Error == true)
		{
			echo "<tr>";
				echo '<th colspan="6" style="text-align:center;"><span>'.$MsjError.'</span></th>';
			echo "</tr>";

		}
				
		for ($fila = 0; $fila < count($List_Item); $fila++)
		{
			
			  echo '<tr style="vertical-align:middle;">';
				echo '<td width="200" style="text-align:center;">'.$List_Item[$fila][0].'</td>';
				echo '<td width="660" style="vertical-align:middle;" >'.$List_Item[$fila][1].'<img src="./img/operacion/Symbol-Delete.png" width="15" height="15" title="Eliminar esta Encomienda." onMouseOver="this.style.cursor=\'hand\'" onclick="E_Delete_Temp('.$List_Item[$fila][2].')" /></td>';
				echo '<td width="90">'.$List_Item[$fila][3].'</td>';
				echo '<td width="100">'.$List_Item[$fila][4].'</td>';
				echo '<td width="70" style="text-align:center;">'.$List_Item[$fila][5].'</td>';
			  echo "</tr>";
		}
		echo "<tr>";
				echo '<th colspan="4" style="text-align:right;"><span>Total</span></th>';
				echo '<th style="text-align:right;"><span>'.$total.'</span></th>';
		echo "</tr>";
		echo "</table>";
	}
?>