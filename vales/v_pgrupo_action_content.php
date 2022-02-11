<?php
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS	
	require_once 'cnn/config_master.php';
	if(isset($_GET['insert'])){
		$nro_serie1=$_POST[n_serie1];$nro_boleto1=$_POST[n_boleto1];
		$nro_serie2=$_POST[n_serie2];$nro_boleto2=$_POST[n_boleto2];
		$nro_serie3=$_POST[n_serie3];$nro_boleto3=$_POST[n_boleto3];
		$nro_serie4=$_POST[n_serie4];$nro_boleto4=$_POST[n_boleto4];
		$nro_serie5=$_POST[n_serie5];$nro_boleto5=$_POST[n_boleto5];
		$nro_serie6=$_POST[n_serie6];$nro_boleto6=$_POST[n_boleto6];
		$nro_serie7=$_POST[n_serie7];$nro_boleto7=$_POST[n_boleto7];
		$nro_serie8=$_POST[n_serie8];$nro_boleto8=$_POST[n_boleto8];
		$nro_vale=$_POST[n_vale];
		$usuario=strtoupper($_SESSION['USUARIO']);
		$agencia=strtoupper($_SESSION['OFICINA']);		

		if($nro_boleto1 !="" && $nro_serie1!=""){
			$sql_buscar_pasaje="SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
								record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
								record_cliente.`importe`,record_cliente.`id_record`,
								CONCAT(record_cliente.`serie_boleto`,'-',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
								INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
								WHERE record_cliente.`serie_boleto`='$nro_serie1' AND record_cliente.`numero_boleto`='$nro_boleto1'";
			$db_transporte->query($sql_buscar_pasaje);
			$Pasajes_Array= $db_transporte->get();
			//Mostramos los datos de la busqueda
			$id_record1=$Pasajes_Array[0][9];
			$pasajero1=$Pasajes_Array[0][2];
			$detalle1=$Pasajes_Array[0][7];
			$importe1=$Pasajes_Array[0][8];
			$fecha_viaje1=$Pasajes_Array[0][3];
			$hora_viaje1=$Pasajes_Array[0][4];
			$origen1=$Pasajes_Array[0][5];
			$destino1=$Pasajes_Array[0][6];
			$boleto1=$Pasajes_Array[0][10];
			//fin
			$db_transporte->query("INSERT INTO pasaje_pagado(id_pasaje_pagado,id_record,cliente,fecha_creacion,hora_creacion,usuario_crea,
								agencia_crea,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje)
								VALUES(NULL,'$id_record1','$pasajero1',CURRENT_DATE(),CURRENT_TIME(),'$usuario','$agencia',
								'$detalle1','$importe1','$nro_vale','$fecha_viaje1','$hora_viaje1','$origen1','$destino1','$boleto1')");
			$db_transporte->query("SELECT MAX(id_pasaje_pagado) AS id FROM pasaje_pagado");
			$Array=$db_transporte->get();
			$codigo_pasaje1=$Array[0][0];
			
	        $db_transporte->query("SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje FROM pasaje_pagado
								WHERE id_pasaje_pagado='$codigo_pasaje1'");
		
			$Datos_Pasajes1 = $db_transporte->get();
		}
		if($nro_boleto2 !="" && $nro_serie2!=""){
			$sql_buscar_pasaje2="SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
								record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
								record_cliente.`importe`,record_cliente.`id_record`,
								CONCAT(record_cliente.`serie_boleto`,'-',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
								INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
								WHERE record_cliente.`serie_boleto`='$nro_serie2' AND record_cliente.`numero_boleto`='$nro_boleto2'";
			$db_transporte->query($sql_buscar_pasaje2);
			$Pasajes_Array2= $db_transporte->get();
			//Mostramos los datos de la busqueda
			$id_record2=$Pasajes_Array2[0][9];
			$pasajero2=$Pasajes_Array2[0][2];
			$detalle2=$Pasajes_Array2[0][7];
			$importe2=$Pasajes_Array2[0][8];
			$fecha_viaje2=$Pasajes_Array2[0][3];
			$hora_viaje2=$Pasajes_Array2[0][4];
			$origen2=$Pasajes_Array2[0][5];
			$destino2=$Pasajes_Array2[0][6];
			$boleto2=$Pasajes_Array2[0][10];
			//fin
			$db_transporte->query("INSERT INTO pasaje_pagado(id_pasaje_pagado,id_record,cliente,fecha_creacion,hora_creacion,usuario_crea,
								agencia_crea,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje)
								VALUES(NULL,'$id_record2','$pasajero2',CURRENT_DATE(),CURRENT_TIME(),'$usuario','$agencia',
								'$detalle2','$importe2','$nro_vale','$fecha_viaje2','$hora_viaje2','$origen2','$destino2','$boleto2')");
			$db_transporte->query("SELECT MAX(id_pasaje_pagado) AS id FROM pasaje_pagado");
			$Array=$db_transporte->get();
			$codigo_pasaje2=$Array[0][0];
			
	        $db_transporte->query("SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje FROM pasaje_pagado
								WHERE id_pasaje_pagado='$codigo_pasaje2'");
		
			$Datos_Pasajes2 = $db_transporte->get();
		}
		if($nro_boleto3 !="" && $nro_serie3!=""){
			$sql_buscar_pasaje3="SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
								record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
								record_cliente.`importe`,record_cliente.`id_record`,
								CONCAT(record_cliente.`serie_boleto`,'-',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
								INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
								WHERE record_cliente.`serie_boleto`='$nro_serie3' AND record_cliente.`numero_boleto`='$nro_boleto3'";
			$db_transporte->query($sql_buscar_pasaje3);
			$Pasajes_Array3= $db_transporte->get();
			//Mostramos los datos de la busqueda
			$id_record3=$Pasajes_Array3[0][9];
			$pasajero3=$Pasajes_Array3[0][2];
			$detalle3=$Pasajes_Array3[0][7];
			$importe3=$Pasajes_Array3[0][8];
			$fecha_viaje3=$Pasajes_Array3[0][3];
			$hora_viaje3=$Pasajes_Array3[0][4];
			$origen3=$Pasajes_Array3[0][5];
			$destino3=$Pasajes_Array3[0][6];
			$boleto3=$Pasajes_Array3[0][10];
			//fin
			$db_transporte->query("INSERT INTO pasaje_pagado(id_pasaje_pagado,id_record,cliente,fecha_creacion,hora_creacion,usuario_crea,
								agencia_crea,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje)
								VALUES(NULL,'$id_record3','$pasajero3',CURRENT_DATE(),CURRENT_TIME(),'$usuario','$agencia',
								'$detalle3','$importe3','$nro_vale','$fecha_viaje3','$hora_viaje3','$origen3','$destino3','$boleto3')");
			$db_transporte->query("SELECT MAX(id_pasaje_pagado) AS id FROM pasaje_pagado");
			$Array=$db_transporte->get();
			$codigo_pasaje3=$Array[0][0];
			
	        $db_transporte->query("SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje FROM pasaje_pagado
								WHERE id_pasaje_pagado='$codigo_pasaje3'");
		
			$Datos_Pasajes3 = $db_transporte->get();
		}
		if($nro_boleto4 !="" && $nro_serie4!=""){
			$sql_buscar_pasaje4="SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
								record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
								record_cliente.`importe`,record_cliente.`id_record`,
								CONCAT(record_cliente.`serie_boleto`,'-',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
								INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
								WHERE record_cliente.`serie_boleto`='$nro_serie4' AND record_cliente.`numero_boleto`='$nro_boleto4'";
			$db_transporte->query($sql_buscar_pasaje4);
			$Pasajes_Array4= $db_transporte->get();
			//Mostramos los datos de la busqueda
			$id_record4=$Pasajes_Array4[0][9];
			$pasajero4=$Pasajes_Array4[0][2];
			$detalle4=$Pasajes_Array4[0][7];
			$importe4=$Pasajes_Array4[0][8];
			$fecha_viaje4=$Pasajes_Array4[0][3];
			$hora_viaje4=$Pasajes_Array4[0][4];
			$origen4=$Pasajes_Array4[0][5];
			$destino4=$Pasajes_Array4[0][6];
			$boleto4=$Pasajes_Array4[0][10];
			//fin
			$db_transporte->query("INSERT INTO pasaje_pagado(id_pasaje_pagado,id_record,cliente,fecha_creacion,hora_creacion,usuario_crea,
								agencia_crea,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje)
								VALUES(NULL,'$id_record4','$pasajero4',CURRENT_DATE(),CURRENT_TIME(),'$usuario','$agencia',
								'$detalle4','$importe4','$nro_vale','$fecha_viaje4','$hora_viaje4','$origen4','$destino4','$boleto4')");
			$db_transporte->query("SELECT MAX(id_pasaje_pagado) AS id FROM pasaje_pagado");
			$Array=$db_transporte->get();
			$codigo_pasaje4=$Array[0][0];
			
	        $db_transporte->query("SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje FROM pasaje_pagado
								WHERE id_pasaje_pagado='$codigo_pasaje4'");
		
			$Datos_Pasajes4 = $db_transporte->get();
		}
		if($nro_boleto5 !="" && $nro_serie5 !=""){
			$sql_buscar_pasaje5="SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
								record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
								record_cliente.`importe`,record_cliente.`id_record`,
								CONCAT(record_cliente.`serie_boleto`,'-',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
								INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
								WHERE record_cliente.`serie_boleto`='$nro_serie5' AND record_cliente.`numero_boleto`='$nro_boleto5'";
			$db_transporte->query($sql_buscar_pasaje5);
			$Pasajes_Array5= $db_transporte->get();
			//Mostramos los datos de la busqueda
			$id_record5=$Pasajes_Array5[0][9];
			$pasajero5=$Pasajes_Array5[0][2];
			$detalle5=$Pasajes_Array5[0][7];
			$importe5=$Pasajes_Array5[0][8];
			$fecha_viaje5=$Pasajes_Array5[0][3];
			$hora_viaje5=$Pasajes_Array5[0][4];
			$origen5=$Pasajes_Array5[0][5];
			$destino5=$Pasajes_Array5[0][6];
			$boleto5=$Pasajes_Array5[0][10];
			//fin
			$db_transporte->query("INSERT INTO pasaje_pagado(id_pasaje_pagado,id_record,cliente,fecha_creacion,hora_creacion,usuario_crea,
								agencia_crea,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje)
								VALUES(NULL,'$id_record5','$pasajero5',CURRENT_DATE(),CURRENT_TIME(),'$usuario','$agencia',
								'$detalle5','$importe5','$nro_vale','$fecha_viaje5','$hora_viaje5','$origen5','$destino5','$boleto5')");
			$db_transporte->query("SELECT MAX(id_pasaje_pagado) AS id FROM pasaje_pagado");
			$Array=$db_transporte->get();
			$codigo_pasaje5=$Array[0][0];
			
	        $db_transporte->query("SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje FROM pasaje_pagado
								WHERE id_pasaje_pagado='$codigo_pasaje5'");
		
			$Datos_Pasajes5 = $db_transporte->get();
		}
		if($nro_boleto6 !="" && $nro_serie6 !=""){
			$sql_buscar_pasaje6="SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
								record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
								record_cliente.`importe`,record_cliente.`id_record`,
								CONCAT(record_cliente.`serie_boleto`,'-',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
								INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
								WHERE record_cliente.`serie_boleto`='$nro_serie6' AND record_cliente.`numero_boleto`='$nro_boleto6'";
			$db_transporte->query($sql_buscar_pasaje6);
			$Pasajes_Array6= $db_transporte->get();
			//Mostramos los datos de la busqueda
			$id_record6=$Pasajes_Array6[0][9];
			$pasajero6=$Pasajes_Array6[0][2];
			$detalle6=$Pasajes_Array6[0][7];
			$importe6=$Pasajes_Array6[0][8];
			$fecha_viaje6=$Pasajes_Array6[0][3];
			$hora_viaje6=$Pasajes_Array6[0][4];
			$origen6=$Pasajes_Array6[0][5];
			$destino6=$Pasajes_Array6[0][6];
			$boleto6=$Pasajes_Array6[0][10];
			//fin
			$db_transporte->query("INSERT INTO pasaje_pagado(id_pasaje_pagado,id_record,cliente,fecha_creacion,hora_creacion,usuario_crea,
								agencia_crea,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje)
								VALUES(NULL,'$id_record6','$pasajero6',CURRENT_DATE(),CURRENT_TIME(),'$usuario','$agencia',
								'$detalle6','$importe6','$nro_vale','$fecha_viaje6','$hora_viaje6','$origen6','$destino6','$boleto6')");
			$db_transporte->query("SELECT MAX(id_pasaje_pagado) AS id FROM pasaje_pagado");
			$Array=$db_transporte->get();
			$codigo_pasaje6=$Array[0][0];
			
	        $db_transporte->query("SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje FROM pasaje_pagado
								WHERE id_pasaje_pagado='$codigo_pasaje6'");
		
			$Datos_Pasajes6 = $db_transporte->get();
		}
		if($nro_boleto7 !="" && $nro_serie7 !=""){
			$sql_buscar_pasaje7="SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
								record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
								record_cliente.`importe`,record_cliente.`id_record`,
								CONCAT(record_cliente.`serie_boleto`,'-',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
								INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
								WHERE record_cliente.`serie_boleto`='$nro_serie7' AND record_cliente.`numero_boleto`='$nro_boleto7'";
			$db_transporte->query($sql_buscar_pasaje7);
			$Pasajes_Array7= $db_transporte->get();
			//Mostramos los datos de la busqueda
			$id_record7=$Pasajes_Array7[0][9];
			$pasajero7=$Pasajes_Array7[0][2];
			$detalle7=$Pasajes_Array7[0][7];
			$importe7=$Pasajes_Array7[0][8];
			$fecha_viaje7=$Pasajes_Array7[0][3];
			$hora_viaje7=$Pasajes_Array7[0][4];
			$origen7=$Pasajes_Array7[0][5];
			$destino7=$Pasajes_Array7[0][6];
			$boleto7=$Pasajes_Array7[0][10];
			//fin
			$db_transporte->query("INSERT INTO pasaje_pagado(id_pasaje_pagado,id_record,cliente,fecha_creacion,hora_creacion,usuario_crea,
								agencia_crea,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje)
								VALUES(NULL,'$id_record7','$pasajero7',CURRENT_DATE(),CURRENT_TIME(),'$usuario','$agencia',
								'$detalle7','$importe7','$nro_vale','$fecha_viaje7','$hora_viaje7','$origen7','$destino7','$boleto7')");
			$db_transporte->query("SELECT MAX(id_pasaje_pagado) AS id FROM pasaje_pagado");
			$Array=$db_transporte->get();
			$codigo_pasaje7=$Array[0][0];
			
	        $db_transporte->query("SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje FROM pasaje_pagado
								WHERE id_pasaje_pagado='$codigo_pasaje7'");
		
			$Datos_Pasajes7 = $db_transporte->get();
		}
		if($nro_boleto8 !="" && $nro_serie8 !=""){
			$sql_buscar_pasaje8="SELECT record_cliente.`asiento_boleto`,record_cliente.`piso_boleto`,cliente.`nombres`,record_cliente.`fecha_viaje`,
								record_cliente.`hora_viaje`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,record_cliente.`tipo_reserva`,
								record_cliente.`importe`,record_cliente.`id_record`,
								CONCAT(record_cliente.`serie_boleto`,'-',record_cliente.`numero_boleto`) AS boleto FROM record_cliente
								INNER JOIN cliente ON cliente.`id_cliente`=record_cliente.`id_cliente`
								WHERE record_cliente.`serie_boleto`='$nro_serie8' AND record_cliente.`numero_boleto`='$nro_boleto8'";
			$db_transporte->query($sql_buscar_pasaje8);
			$Pasajes_Array8= $db_transporte->get();
			//Mostramos los datos de la busqueda
			$id_record8=$Pasajes_Array8[0][9];
			$pasajero8=$Pasajes_Array8[0][2];
			$detalle8=$Pasajes_Array8[0][7];
			$importe8=$Pasajes_Array8[0][8];
			$fecha_viaje8=$Pasajes_Array8[0][3];
			$hora_viaje8=$Pasajes_Array8[0][4];
			$origen8=$Pasajes_Array8[0][5];
			$destino8=$Pasajes_Array8[0][6];
			$boleto8=$Pasajes_Array8[0][10];
			//fin
			$db_transporte->query("INSERT INTO pasaje_pagado(id_pasaje_pagado,id_record,cliente,fecha_creacion,hora_creacion,usuario_crea,
								agencia_crea,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje)
								VALUES(NULL,'$id_record8','$pasajero8',CURRENT_DATE(),CURRENT_TIME(),'$usuario','$agencia',
								'$detalle8','$importe8','$nro_vale','$fecha_viaje8','$hora_viaje8','$origen8','$destino8','$boleto8')");
			$db_transporte->query("SELECT MAX(id_pasaje_pagado) AS id FROM pasaje_pagado");
			$Array=$db_transporte->get();
			$codigo_pasaje8=$Array[0][0];
			
	        $db_transporte->query("SELECT cliente,detalle,monto,nro_guia_interna,fecha_viaje,hora_viaje,origen_agencia,destino_agencia,nro_pasaje FROM pasaje_pagado
								WHERE id_pasaje_pagado='$codigo_pasaje8'");		
			$Datos_Pasajes8 = $db_transporte->get();
		}

	}
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
	<div class="Print_Vale">
		<div class="vale_content">
			<table width="400" border="0">		
			<?php			
            $monto_total=$Datos_Pasajes1[0][2]+$Datos_Pasajes2[0][2]+$Datos_Pasajes3[0][2]+$Datos_Pasajes4[0][2]+$Datos_Pasajes5[0][2]+$Datos_Pasajes6[0][2]+
            $Datos_Pasajes7[0][2]+$Datos_Pasajes8[0][2];
			?>	  
			  <tr>
				<td colspan="3" class="monto" style="height:10px;font-size:23px;">S/.<?php echo number_format($monto_total,2);?></td>
			  </tr>
			  <tr>
				<td style="font-size:6px;letter-spacing:3px;">DETALLE: </td>
				<td class="text_left" style="line-height:150%;font-size:6px" colspan="2"><?php echo $detalle1;?></td>				
			  </tr>
			  <tr>
			  	<td style="font-size:5px;width:60px;letter-spacing:3px; text-align:center;">BOLETO: </td>
			  	<td style="font-size:5px;width:250px;letter-spacing:3px; text-align:center;">CLIENTE: </td>
			  	<td style="font-size:5px;width:40px;letter-spacing:3px; text-align:center;">MONTO: </td>
			  </tr>
			  <?php
			  if($nro_boleto1 !="" && $nro_serie1 !=""){
			  	echo '<tr>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes1[0][8].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes1[0][0].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;text-align:center;">'.$Datos_Pasajes1[0][2].'</td>
					  </tr>';
			  }
			  if($nro_boleto2 !="" && $nro_serie2 !=""){
			  	echo '<tr>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes2[0][8].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes2[0][0].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;text-align:center;">'.$Datos_Pasajes2[0][2].'</td>
					  </tr>';
			  }
			  if($nro_boleto3 !="" && $nro_serie3 !=""){
			  	echo '<tr>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes3[0][8].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes3[0][0].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;text-align:center;">'.$Datos_Pasajes3[0][2].'</td>
					  </tr>';
			  }
			  if($nro_boleto4 !="" && $nro_serie4 !=""){
			  	echo '<tr>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes4[0][8].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes4[0][0].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;text-align:center;">'.$Datos_Pasajes4[0][2].'</td>
					  </tr>';
			  }
			  if($nro_boleto5 !="" && $nro_serie5 !=""){
			  	echo '<tr>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes5[0][8].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes5[0][0].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;text-align:center;">'.$Datos_Pasajes5[0][2].'</td>
					  </tr>';
			  }
			  if($nro_boleto6 !="" && $nro_serie6 !=""){
			  	echo '<tr>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes6[0][8].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes6[0][0].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;text-align:center;">'.$Datos_Pasajes6[0][2].'</td>
					  </tr>';
			  }
			  if($nro_boleto7 !="" && $nro_serie7 !=""){
			  	echo '<tr>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes7[0][8].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes7[0][0].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;text-align:center;">'.$Datos_Pasajes7[0][2].'</td>
					  </tr>';
			  }
			  if($nro_boleto8 !="" && $nro_serie8 !=""){
			  	echo '<tr>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes8[0][8].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;line-height:150%;">'.$Datos_Pasajes8[0][0].'</td>
					  	<td style="font-size:5px;letter-spacing:3px;text-align:center;">'.$Datos_Pasajes8[0][2].'</td>
					  </tr>';
			  }
			  ?>			  	  		
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">USUARIO: </td>
				<td class="text_left" colspan="2"><?php echo $_SESSION['USUARIO']; ?></td>
			  </tr>
			  
			  <tr><td colspan="3"></td></tr>
			  <tr><td colspan="3"></td></tr>
			  <tr><td colspan="3"></td></tr>			  
			  <tr style="height:5px;">
				<th  colspan="3" class="firma_vale"><HR></th>
			  </tr>
			  <tr>
				<th  colspan="3" class="text_center" style="letter-spacing:4px;height:5px;">RECIBÍ CONFORME	</th>
			  </tr>
			  <tr>
				<th  colspan="3" class="text_center" style="letter-spacing:4px;height:5px;">D.N.I.: </th>
			  </tr>
			</table>
	  </div>		
	</div>

	<script language="JavaScript"> 
			window.print();
			window.onfocus = function() 
			{
				/*window.open('','_parent','');*/
				location.href='v_pgrupo.php';
			}
	</script>
</div>

