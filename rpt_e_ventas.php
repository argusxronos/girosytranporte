<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged_niv2.php");
	// CREAMOS LAS VARIABLES PARA LA CAPTURA DE ERRORES
	$Error = false;
	$MsjError = '';
	// CONEXION CON EL SERVIDOR
	require_once 'cnn/config_master.php';
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	//Funciones para obtener IDs o Nombres de algunos campos
	require_once('function/getIDorName.php');
	require_once('function/validacion.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="expires" content="3600" />
  <meta name="revisit-after" content="2 days" />
  <meta name="robots" content="index,follow" />
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
  <title>.::Rep. Encomienda Ventas::.</title>
  
  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_setup.css" />
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_text.css" />
  <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
  
  <!-- Calendario -->
  <script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
  <!-- Script para usar Enter en vez de TAB -->
  <script type="text/javascript" src="js/close_session.js"></script>
  <!-- Script para validar el navegador -->
  <?php
  	if ($_SESSION['TIPO_USUARIO'] == 1)
		echo '<script type="text/javascript" src="js/navegador.js"></script>';
  ?>
</head>

<body <?php if(isset($_SESSION['IS_LOGGED'])) echo 'onbeforeunload="ConfirmClose()" onunload="HandleOnClose()"'; ?>>
  <!-- START Main Page Container -->
  <div class="page-container">
   <!-- For alternative headers START PASTE here -->
    <!-- START A. HEADER -->
	<?php include_once('header.php'); ?>
	<!-- END A. HEADER -->
   <!-- For alternative headers END PASTE here -->
    <!-- START B. MAIN -->
    <div class="main">
      <!-- START B.1 MAIN CONTENT -->
	<?php
	if (isset($_GET['btn_buscar']))
	{
		// DECLARAMOS LAS VARIABLES PARA EL REPORTE
		$id_oficina = "";
		$TOTAL = 0;
		$TOTAL_DOLAR = 0;
		$fecha_inicio = "";
		$fecha_fin = "";
		
		if(!isset($_GET['cmb_agencia']) || strlen($_GET['cmb_agencia']) == 0)
		{
			MsjErrores('Debe seleccionar una Oficina.');
		}
		else
		{
			$id_oficina = $_GET['cmb_agencia']; //Value es el ID
		}
		
		if (isset($_GET['txt_fecha_ini']) && isset($_GET['txt_fecha_fin']))
		{
			$date = $_GET['txt_fecha_ini'];
 			$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
			$fecha_inicio = new DateTime($date);
			$date = $_GET['txt_fecha_fin'];
 			$date = substr($date,6,4) . "-" . substr($date,3,2) . "-" .substr($date,0,2);
			$fecha_fin = new DateTime($date);
		}
		if ($fecha_inicio > $fecha_fin)
		{
			MsjErrores('Fecha de Inicio debe ser menor a la fecha fin.');
		}
		
		if ($Error == FALSE)
		{	
			// OBTENEMOS LOS DATOS PARA EL REPORTE EN SOLES
			$sql_soles ="SELECT 
						DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d-%m-%Y') AS `e_fecha_emision`, 
							TIME_FORMAT(`e_movimiento`.`e_hora_emision`, '%r') AS `e_hora_emision`,

						CONCAT(RIGHT(CONCAT('0000',CAST(`e_movimiento`.`num_serie` AS CHAR)),4), '-',
						RIGHT(CONCAT('00000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS 'serieNumero',

						(select 
							if(`e_persona`.`per_tipo` = 'PERSONA',`e_persona`.`per_nombre` , `e_persona`.`per_razon_social`) 
							from `e_persona`
							where `e_persona`.`id_persona` = `e_movimiento`.`id_remitente` 
						) as 'remitente',
					 
						(select 
							if(`e_persona`.`per_tipo` = 'PERSONA',`e_persona`.`per_nombre` , `e_persona`.`per_razon_social`) 
							from `e_persona`
							where `e_persona`.`id_persona` = `e_movimiento`.`id_consignatario` 
						) as 'Consignatario',
						
						`e_movimiento`.`id_oficina_destino`,
						`e_movimiento`.`e_total`
						
					FROM `e_movimiento`
					WHERE `e_movimiento`.`id_oficina_origen` = ".$id_oficina;
						
			if ($fecha_inicio->format("d-m-Y") == $fecha_fin->format("d-m-Y"))
				$sql_soles = $sql_soles ." AND `e_movimiento`.`e_fecha_emision` = '".$fecha_inicio->format("Y-m-d")."' ";
			else
				$sql_soles = $sql_soles ." AND `e_movimiento`.`e_fecha_emision` BETWEEN '".$fecha_inicio->format("Y-m-d")."' AND '".$fecha_fin->format("Y-m-d")."' ";
			if (isset($_GET['cmb_agencia_origen']) &&  $_GET['cmb_agencia_origen'] > 0)
				$sql_soles = $sql_soles ." AND `e_movimiento`.`id_oficina_origen` = '".$_GET['cmb_agencia_origen']."' ";

			// ALGUNAS CONDICIONESA MAS
			$sql_soles = $sql_soles ." AND `e_movimiento`.`tipo_moneda` = 1";
			$sql_soles = $sql_soles ." ORDER BY `e_movimiento`.`num_serie` DESC, `e_movimiento`.`num_documento` DESC,
			`e_movimiento`.`e_fecha_emision` DESC, `e_movimiento`.`e_hora_emision` DESC;";

			//Almacenamos la consulta en un array
			$db_giro->query($sql_soles);
			$G_CanceladoSol_Array = $db_giro->get();
		}
	}
	?>	
      <!-- B.1 MAIN CONTENT -->
		<div class="main-content">
			<div id="zona-busqueda">
            <!-- Content unit - One column -->
            <h1 class="pagetitle">Reporte Encomienda-Ventas</h1>
            
            <form method="get" action="rpt_e_ventas.php" name="buscar_e_ventas" >
                <table width="100%" border="0">
                    <tr>
						<th width="117">Agencia	Origen: </th>
						<th width="264"><select name="cmb_agencia" class="combo" title="Agencia origen" tabindex="1" style="width:220px;" onkeypress="return handleEnter(this, event)" >
                             <?php
								if (count($Oficina_Array) == 0)
								{
									echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
								}
								else
								{
									echo '<option value="" selected="selected">[ Seleccione Oficina ]</option>';
									for ($fila = 0; $fila < count($Oficina_Array); $fila++)
									{
										if (isset($_GET['cmb_agencia_entrega']) && $_GET['cmb_agencia_entrega'] == $Oficina_Array[$fila][0])
										{
											echo '<option selected="selected" value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
										}
										else
											echo '<option value="'.$Oficina_Array[$fila][0].'" > '.$Oficina_Array[$fila][1].' </option>';
									}
									echo '<option value="0" >TODOS</option>';
								}
                              ?>
						</select></th>
					  <th width="100"></th>
					  <th width="281">	</th>
					</tr>
                    <tr>
                    	<th>Fecha Inicio:</th>
                        <td><input name="txt_fecha_ini" id="txt_fecha" type="text" value="<?php if(isset($_GET['btn_buscar'])) echo $fecha_inicio->format("d/m/Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly="readonly" style="width:150px;" tabindex="2" onkeypress="return handleEnter(this, event)">
                        <input name="button1" type="button" class="button" style="width:54px;" tabindex="2" onclick="displayCalendar(document.forms[0].txt_fecha_ini,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal" /></td>
                        <th>Fecha Fin:</th>
                        <td><input name="txt_fecha_fin" id="txt_fecha2" type="text" value="<?php if(isset($_GET['btn_buscar'])) echo $fecha_fin->format("d/m/Y"); else echo date('d\/m\/Y'); ?>" title="Fecha de envio." readonly="readonly" style="width:150px;" tabindex="3" onkeypress="return handleEnter(this, event)">
                        <input name="button2" type="button" class="button" style="width:54px;" tabindex="4" onclick="displayCalendar(document.forms[0].txt_fecha_fin,'dd/mm/yyyy',this)" onkeypress="return handleEnter(this, event)" value="Cal" /></td>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:right;">
                            <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="8" /></span></th>
                        <th colspan="2" style="text-align:left; ">
                            <span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="9" style="margin-left:35px;" /></span></th>
                    </tr>
                </table>
            </form>
            <!-- Limpiar Unidad del Contenido -->
            <hr class="clear-contentunit" />
            </div> <!--fin zona de busqueda-->
			
			<div class="column1-unit">
			<?php
			if($Error == TRUE)
			{
				echo '<h1>Error de Consulta.</h1>';
				echo '<h3>'.date("l j \d\e F, Y, h:i A").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
				echo '<p>'.$MsjError.'</p>';
				echo '</div>';
				echo '<hr class="clear-contentunit" />';
			}
			else
			{
				if (isset($_GET['btn_buscar']))
				{
			?>

			  <h1>Reporte Encomienda - Ventas de la agencia <?php echo OficinaByID($id_oficina)?>.</h1>
			  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
			  <div class="contactform">
				<!-- PARA MOSTRAR LOS MOVIMIENTOS EN SOLES -->
				<p style="color:#FF0000;">Movimiento en Soles (S/.) desde <span><?php echo $fecha_inicio->format("d/m/Y"); ?></span> hasta <span><?php echo $fecha_fin->format("d/m/Y"); ?></span></p>
				<table border="0" style="margin:2.0em 0 0.2em 0px; width:100%;">
				  <tr>
					<th style="text-align:center">#</th>
					<th style ="width:79px" id="fecha_rpt_e_venta" class="row_rpt_e_venta">Fecha/<br />Hora</th>
					<th>Serie/ <br />Numero</th>
					<th style ="width:190px" id="remitente_rpt_e_venta" class="row_rpt_e_venta">Remitente</th>
					<th style ="width:190px" id="consignatario_rpt_e_venta" class="row_rpt_e_venta">Consignatario</th>
					<th>Of. Destino</th>
					<th style="text-align:right;" >Monto (S/.)</th>
				  </tr>
					<?php
					if (count($G_CanceladoSol_Array) > 0)
					{
						$cont = 1;
						$current_serie = 'valorInicial';
						$suma_flete = 0;

						for ($fila = 0; $fila < count($G_CanceladoSol_Array); $fila++ )
						{
							$fecha = $G_CanceladoSol_Array[$fila][0] .'<br/>' .$G_CanceladoSol_Array[$fila][1];
							$serieNumero = $G_CanceladoSol_Array[$fila][2];
							$serie=substr($serieNumero, 0, 4) ;
							$remitente = utf8_encode($G_CanceladoSol_Array[$fila][3]);
							$consignatario = utf8_encode($G_CanceladoSol_Array[$fila][4]);
							$oficina_destino = OficinaByID($G_CanceladoSol_Array[$fila][5]);
							$usuario_login = UserByID($G_CanceladoSol_Array[$fila][7]);
							$usuario_name = UserNombreByID($G_CanceladoSol_Array[$fila][7]);
							
							if ($current_serie == 'valorInicial') $current_serie = $serie; 
							else if ($current_serie != $serie)
							{
								echo '<tr>';
									echo '<td colspan=6 style="text-align:right; font-weight:bold; color:red; height:26px; ">TOTAL SERIE '.$current_serie.'</td>';
									echo '<td style="text-align:right; font-weight:bold; color:red; " >'.number_format ($suma_flete,2).'</td>';
								echo '</tr>';
								$current_serie = $serie;
								$suma_flete = 0;
								
							}
					 ?>
					<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
						<td style="text-align:center"><?php echo $cont;?></td>
						<td ><?php echo $fecha;?></td>
						<td><?php echo $serieNumero;?></td>
						<td ><?php echo $remitente;?></td>
						<td ><?php echo $consignatario;?></td>
						<td><?php echo $oficina_destino;?></td>
						<!--echo '<td title="'.$usuario_name.'">'.$usuario_login.'</td>'-->
						<td style="text-align:right;"><?php echo $G_CanceladoSol_Array[$fila][6];?></td>
						<?php $TOTAL = $TOTAL + $G_CanceladoSol_Array[$fila][6]; $suma_flete += $G_CanceladoSol_Array[$fila][6];?>
					</tr>
					<?php				
						$cont++;
					   }//END FOR
				    }//END IF
					else
					   echo '<td colspan="7" style="text-align:center;">NO HAY REGISTROS.</td>';
					echo '<tr>';
						echo '<td colspan=6 style="text-align:right; font-weight:bold; color:red; height:26px;" >TOTAL SERIE '.$current_serie.'</td>';
						echo '<td style="text-align:right; font-weight:bold; color:red;  ">'.number_format ($suma_flete,2).'</td>';
					echo '</tr>';
					?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td style="text-align:right; font-weight:bold; color:green; font-size:14px;">TOTAL: S/.</td>
						<td style="text-align:right; font-weight:bold; color:green; font-size:14px;"><?PHP echo number_format ($TOTAL,2); ?></td>
					</tr>
				</table>

				<table width="100%" border="0">
                  <tr>
                    <th colspan="4" scope="row" style="text-align:center;"><span>
                      <input type="button" name="btn_print" id="btn_print" class="button" value="Imprimir Reporte" tabindex="20" onclick="window.print()" style="width:250px;"/>
                    </span></th>
                  </tr>
                </table>
			  </div>
			<!-- Limpiar Unidad del Contenido -->
			<hr class="clear-contentunit" />
			<?php
					}//END IF 198
				}//END ELSE 201
			?>
		</div>
	  <!--END B.1 MAIN CONTENT -->
    </div>
	<!-- END B. MAIN -->
    <!-- START C. FOOTER AREA -->
    <?php include_once('footer.php'); ?>
	<!-- END C. FOOTER AREA -->
  </div> 
  <!-- END Main Page Container -->
</body>
</html>
