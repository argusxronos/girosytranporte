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
		$letters = urldecode($_GET['letters']);
		//  VERIFICAMOS SI HAY REGISTROS
		if (isset($_GET['TBUSQUEDA']) && $_GET['TBUSQUEDA'] == 'FRECUENTES')
		{
			if (isset($_GET['idremit']) && $_GET['idremit'] > 0)
			{
				// SI HAY MAS DE UN REGISTRO MOSTRAMOS LOS RESULTADOS
				$db_giro->query("SELECT `e_persona`.`id_persona`,  `e_persona`.`per_nombre`
								FROM `e_persona`
								INNER JOIN `e_persona_frecuente`
								ON `e_persona`.`id_persona` = `e_persona_frecuente`.`id_persona_consig`
								WHERE `e_persona_frecuente`.`id_persona_remit` = ".$_GET['idremit']."
								AND `e_persona`.`per_estado` = 1
								AND `e_persona`.`per_tipo` = 'PERSONA'
								ORDER BY `e_persona_frecuente`.`pf_frecuencia` DESC
								LIMIT 15;");
				$Personas_Array = $db_giro->get();
			}
		}
		elseif(isset($_GET['TBUSQUEDA']) && $_GET['TBUSQUEDA'] == 'EMPRESA')
		{
			$db_giro->query("SELECT `e_persona`.`id_persona`,  `e_persona`.`per_razon_social`
							FROM `e_persona`
							WHERE REPLACE(`e_persona`.`per_razon_social`, ' ','') LIKE REPLACE('%".$letters."%', ' ','')
							AND `per_estado` = 1
							AND `e_persona`.`per_tipo` = 'EMPRESA'
							LIMIT 15;");
			$Personas_Array = $db_giro->get();
		}
		elseif(isset($_GET['TBUSQUEDA']) && $_GET['TBUSQUEDA'] == 'EMPRESA_FRECUENTES')
		{
			if (isset($_GET['idremit']) && $_GET['idremit'] > 0)
			{
				// SI HAY MAS DE UN REGISTRO MOSTRAMOS LOS RESULTADOS
				$db_giro->query("SELECT `e_persona`.`id_persona`,  `e_persona`.`per_razon_social`
								FROM `e_persona`
								INNER JOIN `e_persona_frecuente`
								ON `e_persona`.`id_persona` = `e_persona_frecuente`.`id_persona_consig`
								WHERE `e_persona_frecuente`.`id_persona_remit` = ".$_GET['idremit']."
								AND `e_persona`.`per_estado` = 1
								AND `e_persona`.`per_tipo` = 'EMPRESA'
								ORDER BY `e_persona_frecuente`.`pf_frecuencia` DESC
								LIMIT 15;");
				$Personas_Array = $db_giro->get();
			}
		}
		else
		{
			// SI HAY MAS DE UN REGISTRO MOSTRAMOS LOS RESULTADOS
			$db_giro->query("SELECT `e_persona`.`id_persona`,  `e_persona`.`per_nombre`
							FROM `e_persona`
							WHERE REPLACE(`e_persona`.`per_nombre`, ' ','') LIKE REPLACE('%".$letters."%', ' ','')
							AND `per_estado` = 1
							AND `e_persona`.`per_tipo` = 'PERSONA'
							LIMIT 15;");
			$Personas_Array = $db_giro->get();
		}
		for ($fila = 0; $fila < count($Personas_Array); $fila++)
		{
			echo $Personas_Array[$fila][0]."###".utf8_encode($Personas_Array[$fila][1])."|";
		}
	}
	elseif(isset($_GET['getDescripcion']) && isset($_GET['letters']))
	{
		$letters = urldecode($_GET['letters']);
		$db_giro->query("SELECT `md_descripcion` FROM `e_mov_detalle`
		WHERE `md_descripcion` LIKE '".$letters."%'
		GROUP BY `md_descripcion`
		ORDER BY `md_descripcion`
		LIMIT 15;");
		$Descripcion_Array = $db_giro->get();
		for ($fila = 0; $fila < count($Descripcion_Array); $fila++)
		{
			echo "###".utf8_encode($Descripcion_Array[$fila][0])."|";
		}
	}
?>
