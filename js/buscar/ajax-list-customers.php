<?php
	/*$conn = mysql_connect("localhost","root","123456");
	mysql_select_db("bd_giro",$conn);
	
	if(isset($_GET['getPersonByLetters']) && isset($_GET['letters'])){
		$letters = $_GET['letters'];
		$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
		$res = mysql_query("SELECT `g_persona`.`id_persona`,  `g_persona`.`per_ape_nom`
									FROM `g_persona`
									WHERE `g_persona`.`per_ape_nom` LIKE '".$letters."%'") or die(mysql_error());
		#echo "1###select ID,countryName from ajax_countries where countryName like '".$letters."%'|";
		while($inf = mysql_fetch_array($res)){
			echo $inf["id_persona"]."###".$inf["per_ape_nom"]."|";
		}	
	}*/
	
	require_once '../../config_giro.php';
	// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
	require_once("../../function/validacion.php");
	//require_once '../../cnn/config_giro.php'; // NO SE POR K ESTE LINK NO FUNCIONA
	
	if(isset($_GET['getPersonByLetters']) && isset($_GET['letters'])){
		/*$letters = str_replace("\xF1", "\xD1", $_GET['letters']);
		$letters = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($letters)))));*/
		$letters = $_GET['letters'];
		//  VERIFICAMOS SI HAY REGISTROS
		
		// SI HAY MAS DE UN REGISTRO MOSTRAMOS LOS RESULTADOS
		$db_giro->query("SELECT `g_persona`.`id_persona`,  `g_persona`.`per_ape_nom`
						FROM `g_persona`
						WHERE `g_persona`.`per_ape_nom` LIKE '".$letters."%'
						AND `per_estado` = 1
						LIMIT 15;");
		$Personas_Array = $db_giro->get();
		for ($fila = 0; $fila < count($Personas_Array); $fila++)
		{
			echo $Personas_Array[$fila][0]."###".utf8_encode($Personas_Array[$fila][1])."|";
		}
	}
?>
