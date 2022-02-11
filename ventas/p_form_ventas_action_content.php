<?php
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS
	$usuario=strtoupper($_SESSION['USUARIO']);
	$id_bus=$_POST['txt_bus'];
	$id_ruta=$_POST['txt_ruta'];
	$id_usuario=$_SESSION['ID_USUARIO'];	
	$id_oficina=$_SESSION['ID_OFICINA'];
	//Datos de Clientes
	$nombre_cliente=$_POST['txt_nombre'];
	$tipo_documento=$_POST['txt_documento'];
	$dni=$_POST['txt_ndocumento'];
	$genero_cliente=$_POST['txt_genero'];
	$fono_cliente=$_POST['txt_telefono'];
	$edad_cliente=$_POST['txt_edad'];
	$nacionalidad=$_POST['txt_nacionalidad'];
	$ruc_cliente=$_POST['txt_ruc'];
	$razon_cliente=$_POST['txt_razon'];
	//echo $nombre_cliente,$tipo_documento,$dni,$genero_cliente,$fono_cliente,$edad_cliente,$nacionalidad,$ruc_cliente,$razon_cliente;
	//Datos Salida
	$origen_salida=$_POST['txt_origen'];
	$destino_salida=$_POST['txt_salida'];
	$fecha_viaje=$_POST['txt_fecha_viaje'];
	$hora_viaje=$_POST['txt_hora_viaje'];
	$boleto_viaje=$_POST['txt_boleto'];
	$serie_boleto=$_POST['txt_serie_boleto'];
	$piso_bus=$_POST['txt_piso'];
	$asiento_bus=$_POST['txt_asiento'];
	$importe_final=$_POST['txt_importe_final'];
	$codigo_salida=$_POST['txt_codigo_salida'];
	//echo $origen_salida,$destino_salida,$fecha_viaje,$hora_viaje,$boleto_viaje,$piso_bus,$asiento_bus,$importe_final;
	$destino_viaje=$_POST['txt_destino'];		
	$descuento_viaje=$_POST['txt_descuento'];
	$importe_final_letras=$_POST['txt_importe_pagar_letras'];	
	$id_record='NULL';
	$turno='1';//por mientras el turno del usuario
	$ip= $_SERVER['REMOTE_ADDR'];
	
	// VALIDACIONES PARA LOS DATOS
	
	
	//RIRECCION URL PARA REDIRIGIRME A LA PAGINA DE ASIENTOS
	$direccion="asientos_bus=$id_bus&origen=$origen_salida&salida=$codigo_salida&fecha=$fecha_viaje&destino=$destino_salida&hora=$hora_viaje&ruta=$id_ruta";	
	// PROCEDIMIENTO PARA INGRESAR LOS DATOS SI NO HAY ERRORES
	require_once 'cnn/config_master.php';
		//Registrar Nuevos clientes
		if($genero_cliente=='M'){
			$estado='1';
		}else $estado='2';
		
		$id_cliente = 'NULL';
		$db_transporte->query("SELECT*FROM cliente WHERE nro_documento='$dni'");
		$Cliente_Array = $db_transporte->get(); 
		if(count($Cliente_Array) == 0){
			$db_transporte->query("INSERT INTO cliente 
			(id_cliente,nombres
			,tipo_doc,nro_documento
			,ruc,razon_social
			,nacionalidad
			,sexo,edad
			,direccion
			,telefono_celu
			,e_mail,obs,idconvenio
			) 
			VALUES(
			'$id_cliente'
			,'$nombre_cliente'
			,'$tipo_documento'
			,'$dni'
			,'$ruc_cliente'
			,'$razon_cliente'
			,'$nacionalidad'
			,'$genero_cliente'
			,'$edad_cliente'
			,''
			,'$fono_cliente'
			,''
			,''
			,'0'
			)");							
		}
			
		if(isset($_GET['insertventa']))
		{				
			//OBTENEMOS EL ID DEL CLIENTE REGISTRADO
			$db_transporte->query("SELECT id_cliente AS ID FROM cliente WHERE nro_documento='$dni' LIMIT 1");
			$id_cliente_regis = $db_transporte->get("ID");
			
			//MODIFICAMOS EL NUMERO DE BOLETO POR SERIE QUE ESTE INGRESADO
			$db_transporte->query("UPDATE numeracion_documento 
			SET numero_actual='$boleto_viaje' 
			WHERE serie='$serie_boleto'");
			
			//INGRESAMOS LOS DATOS EN LA TABLA RECORD_CLIENTE PARA GUARDAR LA VENTA DE BOLETO
			$query = "INSERT INTO record_cliente 
			(
			record_cliente.`id_record`
			,record_cliente.`id_salida`
			,record_cliente.`id_cliente`
			,record_cliente.`estado`
			,record_cliente.`fecha`
			,record_cliente.`hora`
			,record_cliente.`piso`
			,record_cliente.`asiento`
			,record_cliente.`fecha_viaje`
			,record_cliente.`hora_viaje`
			,record_cliente.`piso_boleto`
			,record_cliente.`asiento_boleto`
			,record_cliente.`serie_boleto`
			,record_cliente.`numero_boleto`
			,record_cliente.`origen_boleto`
			,record_cliente.`destino_boleto`
			,record_cliente.`importe`
			,record_cliente.`descuento`
			,record_cliente.`importe_letras`
			,record_cliente.`tipo_postergado`
			,record_cliente.`anulado`
			,record_cliente.`adelanto`
			,record_cliente.`nro_ticket`
			,record_cliente.`tipo_reserva`
			,record_cliente.`nro_copias`
			,record_cliente.`obs`
			,record_cliente.`id_usuario`
			,record_cliente.`idoficina`
			,record_cliente.`idhostname`
			,record_cliente.`hora_reserva`
			,record_cliente.`credito`
			,record_cliente.`bk_fecha_viaje`
			,record_cliente.`bk_origen_destino`
			,record_cliente.`n_forma_pago`
			,record_cliente.`t_forma_pago`
			,record_cliente.`id_turno`
			,record_cliente.`n_reserva_otra_agencia`
			,record_cliente.`n_impresion`
			,record_cliente.`u_crea`
			,record_cliente.`d_crea`
			,record_cliente.`h_crea`
			,record_cliente.`u_edita`
			,record_cliente.`d_edita`
			,record_cliente.`h_edita`
			,record_cliente.`u_anula`
			,record_cliente.`d_anula`
			,record_cliente.`h_anula`
			,record_cliente.`serie_boleto_liq`
			,
			record_cliente.`numero_boleto_liq`
			,record_cliente.`liquidacion_pagada`
			)
			VALUES(
			'$id_record'
			,'$codigo_salida'
			,'$id_cliente_regis'
			,'$estado'
			,CURDATE()
			,CURRENT_TIME()
			,'$piso_bus'
			,'$asiento_bus'
			,'$fecha_viaje'
			,'$hora_viaje'
			,'$piso_bus'
			,'$asiento_bus'
			,'$serie_boleto'
			,'$boleto_viaje'
			,'$origen_salida'
			,'$destino_viaje'
			,'$importe_final'
			,'$descuento_viaje'
			,'$importe_final_letras'
			,''
			,''
			,''
			,''
			,''
			,''
			,''
			,'$id_usuario'
			,'$id_oficina'
			,'$ip'
			,''
			,''
			,''
			,''
			,''
			,''
			,'$turno'
			,''
			,''
			,'$usuario'
			,CURDATE()
			,CURRENT_TIME()
			,''
			,''
			,''
			,''
			,''
			,''
			,''
			,''
			,''
			)";
			//echo $query;
			$db_transporte->query($query);
		}
		if(isset($_GET['insertreserva']))
		{
			$tipo_reserva=$_POST['txt_t_reserva'];
			$hora_reserva=$_POST['txt_hora'];	
			//echo $hora_reserva;		
			$otra_oficina=$_POST['cmb_oficina_reserva'];			
			$reserva_pagada=$_POST['reserva_pagada'];
			$reserva_otra_agencia=$_POST['cmb_oficina_reserva'];


			$pri=$_POST['chk1'];
			for($a=0;$a<count($pri);$a++){
				echo $pri[$a];	
				$sql="INSERT INTO record_cliente(record_cliente.id_record,record_cliente.id_salida,record_cliente.id_cliente,
					record_cliente.estado,record_cliente.fecha,record_cliente.hora,record_cliente.piso,record_cliente.asiento,
					record_cliente.fecha_viaje,record_cliente.hora_viaje,record_cliente.piso_boleto,record_cliente.asiento_boleto,
					record_cliente.serie_boleto,record_cliente.numero_boleto,record_cliente.origen_boleto,record_cliente.destino_boleto,
					record_cliente.importe,record_cliente.descuento,record_cliente.importe_letras,record_cliente.tipo_postergado,
					record_cliente.anulado,record_cliente.adelanto,record_cliente.nro_ticket,record_cliente.tipo_reserva.record_cliente.nro_copias,
					record_cliente.obs,record_cliente.id_usuario,record_cliente.idoficina,record_cliente.idhostname,record_cliente.hora_reserva,
					record_cliente.credito,record_cliente.bk_fecha_viaje,record_cliente.bk_origen_destino,record_cliente.n_forma_pago,
					record_cliente.t_forma_pago,record_cliente.id_turno,record_cliente.n_reserva_otra_agencia,record_cliente.n_impresion,
					record_cliente.u_crea,record_cliente.d_crea,record_cliente.h_crea,record_cliente.u_edita,record_cliente.d_edita,
					record_cliente.h_edita,record_cliente.u_anula,record_cliente.d_anula,record_cliente.h_anula,record_cliente.serie_boleto_liq,
					record_cliente.numero_boleto_liq,record_cliente.liquidacion_pagada) VALUES()";
			}
			$pri2=$_POST['chk2'];
			for($a=0;$a<count($pri2);$a++){
				echo $pri2[$a];	
				$sql="SELECT*FROM cliente inner join record_cliente on record_cliente.id_cliente=cleinte.id_cliente";
			}
			$pri3=$_POST['chk3'];
			for($a=0;$a<count($pri3);$a++){
				echo $pri3[$a];
			}
			$pri4=$_POST['chk4'];
			for($a=0;$a<count($pri4);$a++){
				echo $pri4[$a];
			}
			$pri5=$_POST['chk5'];
			for($a=0;$a<count($pri5);$a++){
				echo $pri5[$a];

			}
			//echo $reserva_otra_agencia;
			//echo $reserva_pagada;
			//$lista_reserva=array();
			if($reserva_otra_agencia != ""){
				$id_oficina=$reserva_otra_agencia;
				//echo $id_oficina;
			}

			$lista_reserva=$_POST[lista];
			for($al=0;$al<count($lista_reserva);$al++){
				echo $lista_reserva[$al];	
				//$pisos_reserva = substr($lista_reserva[$al], 0, 1);
			 	//$asientos_reserva = substr($lista_reserva[$al], 2, 3);
			 	//echo $pisos_reserva;
			 	//echo $asientos_reserva;
			 	
			}
			if($reserva_pagada=="1"){
				echo "imprimir";
			}			
			echo $reserva_otra_agencia;
			if ($reserva_otra_agencia!="") {
				echo "Guardar reserva con Id de oficina que se elija anteriormente";
			}

			 /*
			//Estado de reserva = 8
			
			$estado='8';

			$db_transporte->query("SELECT id_cliente AS ID FROM cliente WHERE nro_documento='$dni'");
			$id_cliente_regis=$db_transporte->get("ID");

			//Ingresamos datos para la reserva de pasajes
			$db_transporte->query("INSERT INTO record_cliente (record_cliente.`id_record`,record_cliente.`id_salida`,record_cliente.`id_cliente`,
			record_cliente.`estado`,record_cliente.`fecha`,record_cliente.`hora`,record_cliente.`piso`,record_cliente.`asiento`,
			record_cliente.`fecha_viaje`,record_cliente.`hora_viaje`,record_cliente.`piso_boleto`,record_cliente.`asiento_boleto`,
			record_cliente.`serie_boleto`,record_cliente.`numero_boleto`,record_cliente.`origen_boleto`,record_cliente.`destino_boleto`,
			record_cliente.`importe`,record_cliente.`descuento`,record_cliente.`importe_letras`,record_cliente.`tipo_postergado`,
			record_cliente.`anulado`,record_cliente.`adelanto`,record_cliente.`nro_ticket`,record_cliente.`tipo_reserva`,record_cliente.`nro_copias`,
			record_cliente.`obs`,record_cliente.`id_usuario`,record_cliente.`idoficina`,record_cliente.`idhostname`,record_cliente.`hora_reserva`,
			record_cliente.`credito`,record_cliente.`bk_fecha_viaje`,record_cliente.`bk_origen_destino`,record_cliente.`n_forma_pago`,
			record_cliente.`t_forma_pago`,record_cliente.`id_turno`,record_cliente.`n_reserva_otra_agencia`,record_cliente.`n_impresion`,
			record_cliente.`u_crea`,record_cliente.`d_crea`,record_cliente.`h_crea`,record_cliente.`u_edita`,record_cliente.`d_edita`,
			record_cliente.`h_edita`,record_cliente.`u_anula`,record_cliente.`d_anula`,record_cliente.`h_anula`,record_cliente.`serie_boleto_liq`,
			record_cliente.`numero_boleto_liq`,record_cliente.`liquidacion_pagada`)VALUES('$id_record','$codigo_salida','$id_cliente_regis','$estado',
			CURDATE(),CURRENT_TIME(),'$piso_bus','$asiento_bus','$fecha_viaje','$hora_viaje','','','','','','','','','','','','','','$tipo_reserva','','',
			'$id_usuario','$id_oficina','$ip','$hora_reserva','','','','','','$turno','','','$usuario',CURDATE(),CURRENT_TIME(),'','','','','','','','','')");
			//echo $reserva_pagada;			
			//echo $nombre_cliente,$tipo_documento,$dni,$genero_cliente,$fono_cliente,$edad_cliente,$nacionalidad,$ruc_cliente,$razon_cliente;
			*/
		}
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
<?php
	if ($Error == true )
	{
		echo '<!-- Pagetitle -->';
		echo '<h1 class="pagetitle">Mensaje de Error</h1>';
		echo '<div class="column1-unit">';
	  	echo '<h1>Detalle del o los errores.</h1>';
	  	echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
	  	echo '<p>'.$MsjError.'</p>';	
?>
	<p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; javascript:history.back(1)" ></p>
<?php
		echo '<!-- Limpiar Unidad del Contenido -->';
		echo '<hr class="clear-contentunit" />';
	}
	else
	{
		// MOSTRAMOS EL MENSAJE DE OPERACION SATISFACTORIA		
		echo '<!-- Pagetitle -->';
		echo '<h1 class="pagetitle">Mensaje de Confirmación</h1>';
		echo '<div class="column1-unit">';
	  	if(isset($_GET['insertventa'])){
			echo '<h1>Operación Exitosa.</h1>';
			echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="">Administrador </a></h3>';		
			echo '<p>La venta se almaceno ; <span>Satisfactoriamente</span>.</p>';
		}	
		if(isset($_GET['insertreserva'])){
			echo '<h1>Operación Exitosa.</h1>';
			echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="">Administrador </a></h3>';		
			echo '<p>La Reserva se almaceno; <span>Satisfactoriamente</span>.</p>';
		}	
?>
<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_pasajes.php?<?php echo $direccion;?>'" style="width:170px;" ></p>
<?PHP
	}
?>
</div>
