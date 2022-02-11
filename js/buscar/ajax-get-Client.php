<?php
	/* Replace the data in these two lines with data for your db connection */
	require_once('../../config_giro.php');
	$idClient = '';
	$TDOC = '';
	$TPER = '';
	if (isset($_GET['getClientId']) && strlen($_GET['getClientId']) > 0)
	{
		$idClient = $_GET['getClientId'];
	}
	if (isset($_GET['TDOC']) && strlen($_GET['TDOC']) > 0)
	{
		$TDOC = $_GET['TDOC'];
	}
	if (isset($_GET['TPER']) && strlen($_GET['TPER']) > 0)
	{
		$TPER = $_GET['TPER'];
	}
	$IDOFICINA = 0;
	if (isset($_GET['IDOF_ORIGEN']) && $_GET['IDOF_ORIGEN'] > 0)
	{
		$IDOFICINA = $_GET['IDOF_ORIGEN'];
	}
	if (isset($_GET['IDOF_DESTINO']) && $_GET['IDOF_DESTINO'] > 0)
	{
		$IDOFICINA = $_GET['IDOF_DESTINO'];
	}
	
	if($TDOC == 'FACTURA')
	{  
		if ($TPER == 'REMITENTE')
		{
			$db_giro->query("SELECT `e_persona`.`id_persona`
						, `e_persona`.`per_razon_social`
						FROM `e_persona`
						WHERE `e_persona`.`per_ruc` = '".$idClient."'
						AND `e_persona`.`per_tipo` = 'EMPRESA'
						LIMIT 1;");
			$res = $db_giro->get();
			$id_persona = 0;
			if(count($res)>0)
			{
				$id_persona = $res[0][0];
				echo "formObj.txt_remit_hidden.value = '".$res[0][0]."';\n";
				echo "formObj.txt_remit.value = '".utf8_encode($res[0][1])."';\n";
				//echo "formObj.txt_remit.setAttribute('readonly','readonly');\n";
				echo "formObj.txt_remit_direccion.focus();\n";
				echo "formObj.txt_remit_direccion.select();\n";
			}else{
				echo "formObj.txt_remit_hidden.value = '';\n";
				echo "formObj.txt_remit.value = 'INGRESE NOMBRE';\n";
				echo "formObj.txt_remit.removeAttribute('readOnly');\n";
				echo "formObj.txt_remit.focus();\n";
				echo "formObj.txt_remit.select();\n";
			}
			if ($id_persona > 0)
			{
				$db_giro->query("SELECT 
								IF(LENGTH(IFNULL(`e_direccion`.`dir_text`,'')) = 0,'SIN DIRECCIÓN', `e_direccion`.`dir_text` )
								FROM `e_direccion`
								WHERE `e_direccion`.`id_persona` = '".$id_persona."'
								AND `e_direccion`.`id_oficina` = ".$IDOFICINA."
								LIMIT 1;");
				$res = $db_giro->get();
				if(count($res)>0)
				{
					echo "formObj.txt_remit_direccion.value = '".utf8_encode($res[0][0])."';\n";
					echo "formObj.txt_remit_direccion.focus();\n";
					echo "formObj.txt_remit_direccion.select();\n";
				}
				else
				{
					echo "formObj.txt_remit_direccion.value = '".utf8_encode('INGRESE DIRECCIÓN')."';\n";
				}
			}else
			{
				echo "formObj.txt_remit_direccion.value = '".utf8_encode('INGRESE DIRECCIÓN')."';\n";
			}
		}
		elseif ($TPER == 'CONSIGNATARIO')
		{
			$db_giro->query("SELECT `e_persona`.`id_persona`
						, `e_persona`.`per_nombre`
						, IF(LENGTH(IFNULL(`e_persona`.`per_direccion`,'')) = 0, 'AGENCIA', `e_persona`.`per_direccion`) AS `per_direccion`
						FROM `e_persona`
						WHERE `e_persona`.`per_num_dni` = '".$idClient."'
						LIMIT 1;");
			$res = $db_giro->get();
			if(count($res)>0)
			{
				echo "formObj.txt_consig_hidden.value = '".utf8_encode($res[0][0])."';\n";
				echo "formObj.txt_consig.value = '".utf8_encode($res[0][1])."';\n";
				//echo "formObj.txt_consig.setAttribute('readonly','readonly');\n";
				echo "formObj.txt_consig_direccion.value = '".utf8_encode($res[0][2])."';\n";
				echo "formObj.cbox_carrera.focus();\n";
				
			}else{
				echo "formObj.txt_consig_hidden.value = '';\n";
				echo "formObj.txt_consig.value = 'INGRESE NOMBRE';\n";
				echo "formObj.txt_consig.removeAttribute('readOnly');\n";
				echo "formObj.txt_consig.focus();\n";
				echo "formObj.txt_consig.select();\n";
				echo "formObj.txt_consig_direccion.value = 'AGENCIA';\n";
				
			}
		}  
	}
	elseif($TDOC == 'BOLETA')
	{
		if ($TPER == 'REMITENTE')
		{
			$db_giro->query("SELECT `e_persona`.`id_persona`
					, `e_persona`.`per_nombre`
					FROM `e_persona`
					WHERE `e_persona`.`per_num_dni` = '".$idClient."'
					LIMIT 1;");
			$res = $db_giro->get();
			if(count($res)>0)
			{
				echo "formObj.txt_remit_hidden.value = '".utf8_encode($res[0][0])."';\n";
				echo "formObj.txt_remit.value = '".utf8_encode($res[0][1])."';\n";
				//echo "formObj.txt_remit.setAttribute('readonly','readonly');\n";
				echo "formObj.txt_consig_dni.focus();\n";
			}else{
				echo "formObj.txt_remit_hidden.value = '';\n";
				echo "formObj.txt_remit.value = 'INGRESE NOMBRE';\n";
				echo "formObj.txt_remit.removeAttribute('readOnly');\n";
				echo "formObj.txt_remit.focus();\n";
				echo "formObj.txt_remit.select();\n";
			}
		}
		elseif($TPER == 'CONSIGNATARIO')
		{
			$db_giro->query("SELECT `e_persona`.`id_persona`
					, `e_persona`.`per_nombre`
					, IF(LENGTH(IFNULL(`e_persona`.`per_direccion`,'')) = 0,'AGENCIA', `e_persona`.`per_direccion` )
 					AS `per_direccion`
					FROM `e_persona`
					WHERE `e_persona`.`per_num_dni` = '".$idClient."'
					LIMIT 1;");
			$res = $db_giro->get();
			if(count($res)>0)
			{
				echo "formObj.txt_consig_hidden.value = '".utf8_encode($res[0][0])."';\n";
				echo "formObj.txt_consig.value = '".utf8_encode($res[0][1])."';\n";
				//echo "formObj.txt_consig.setAttribute('readonly','readonly');\n";
				echo "formObj.txt_consig_direccion.value = '".utf8_encode($res[0][2])."';\n";
				//echo "formObj.txt_consig_direccion.select();\n";
				echo "formObj.cbox_carrera.focus();\n";
			}else{
				echo "formObj.txt_consig_hidden.value = '';\n";
				echo "formObj.txt_consig.value = 'INGRESE NOMBRE';\n";
				echo "formObj.txt_consig.focus();\n";
				echo "formObj.txt_consig.select();\n";
				echo "formObj.txt_consig_direccion.value = 'AGENCIA';\n";
				echo "formObj.txt_consig.removeAttribute('readOnly');\n";
			}
		}
	}
	elseif($TDOC == 'GUIA REMISION')
	{
		if ($TPER == 'REMITENTE')
		{
			$db_giro->query("SELECT `e_persona`.`id_persona`
						, `e_persona`.`per_razon_social`
						FROM `e_persona`
						WHERE `e_persona`.`per_ruc` = '".$idClient."'
						AND `e_persona`.`per_tipo` = 'EMPRESA'
						LIMIT 1;");
			$res = $db_giro->get();
			$id_persona = 0;
			if(count($res)>0)
			{
				$id_persona = $res[0][0];
				echo "formObj.txt_remit_hidden.value = '".$res[0][0]."';\n";
				echo "formObj.txt_remit.value = '".utf8_encode($res[0][1])."';\n";
				//echo "formObj.txt_remit.setAttribute('readonly','readonly');\n";
			}else{
				echo "formObj.txt_remit_hidden.value = '';\n";
				echo "formObj.txt_remit.value = 'INGRESE NOMBRE';\n";
				echo "formObj.txt_remit.removeAttribute('readOnly');\n";
				echo "formObj.txt_remit.focus();\n";
				echo "formObj.txt_remit.select();\n";
			}
			if ($id_persona > 0)
			{
				$db_giro->query("SELECT 
								IF(LENGTH(IFNULL(`e_direccion`.`dir_text`,'')) = 0,'SIN DIRECCIÓN', `e_direccion`.`dir_text` )
								FROM `e_direccion`
								WHERE `e_direccion`.`id_persona` = '".$id_persona."'
								AND `e_direccion`.`id_oficina` = ".$IDOFICINA."
								LIMIT 1;");
				$res = $db_giro->get();
				if(count($res)>0)
				{
					echo "formObj.txt_remit_direccion.value = '".utf8_encode($res[0][0])."';\n";
					echo "formObj.txt_remit_direccion.focus();\n";
					echo "formObj.txt_remit_direccion.select();\n";
				}
				else
				{
					echo "formObj.txt_remit_direccion.value = '".utf8_encode('INGRESE DIRECCIÓN')."';\n";
				}
			}else
			{
				echo "formObj.txt_remit_direccion.value = '".utf8_encode('INGRESE DIRECCIÓN')."';\n";
			}
		}
		elseif($TPER == 'CONSIGNATARIO')
		{
			$db_giro->query("SELECT `e_persona`.`id_persona`
						, `e_persona`.`per_razon_social`
						FROM `e_persona`
						WHERE `e_persona`.`per_ruc` = '".$idClient."'
						LIMIT 1;");
			$res = $db_giro->get();
			$id_persona = 0;
			if(count($res)>0)
			{
				$id_persona = $res[0][0];
				echo "formObj.txt_consig_hidden.value = '".$res[0][0]."';\n";
				echo "formObj.txt_consig.value = '".utf8_encode($res[0][1])."';\n";
				//echo "formObj.txt_consig.setAttribute('readonly','readonly');\n";
				echo "formObj.txt_consig_direccion.value = '".utf8_encode($res[0][2])."';\n";
			}else{
				echo "formObj.txt_consig_hidden.value = '';\n";
				echo "formObj.txt_consig.value = 'INGRESE NOMBRE';\n";
				echo "formObj.txt_consig.removeAttribute('readOnly');\n";
				echo "formObj.txt_consig.focus();\n";
				echo "formObj.txt_consig.select();\n";
			}
			if ($id_persona > 0)
			{
				$db_giro->query("SELECT 
								IF(LENGTH(IFNULL(`e_direccion`.`dir_text`,'')) = 0,'SIN DIRECCIÓN', `e_direccion`.`dir_text` )
								FROM `e_direccion`
								WHERE `e_direccion`.`id_persona` = '".$id_persona."'
								AND `e_direccion`.`id_oficina` = ".$IDOFICINA."
								LIMIT 1;");
				$res = $db_giro->get();
				if(count($res)>0)
				{
					echo "formObj.cbox_carrera.focus();\n";
					echo "formObj.txt_consig_direccion.value = '".utf8_encode($res[0][0])."';\n";
				}
				else
				{
					echo "formObj.txt_consig_direccion.value = 'AGENCIA';\n";
				}
			}else
			{
				echo "formObj.txt_consig_direccion.value = 'AGENCIA';\n";
			}
		}
	}
?>