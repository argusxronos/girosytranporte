<?php 
	// VERIFICAMOS SI ESTA LOGEADO
	session_start();
	require_once("is_logged.php");
	//include_once('function/date_add.php');
	$OficinaByID='';
	function OficinaByID($id_ofic)
	{
		$Ofic_Array = $_SESSION['OFICINAS'];
		$Oficina = '';
		for ($fila = 0; $fila < count($_SESSION['OFICINAS']); $fila++)
		{
			if($_SESSION['OFICINAS'][$fila][0] == $id_ofic)
			{
				$Oficina = $_SESSION['OFICINAS'][$fila][1];
				break;
			}
		}
		return $Oficina;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="expires" content="3600" />
  <meta name="revisit-after" content="2 days" />
  <meta name="robots" content="index,follow" />
  <meta name="publisher" content="Your publisher infos here ..." />
  <meta name="copyright" content="Your copyright infos here ..." />
  <meta name="author" content="Design: Wolfgang (www.1-2-3-4.info) / Modified: Your Name" />
  <meta name="distribution" content="global" />
  <meta name="description" content="Your page description here ..." />
  <meta name="keywords" content="Your keywords, keywords, keywords, here ..." />
  <!-- Hoja de Estilos -->
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_setup.css" />
  <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/layout1_text.css" />
  <!-- Icono -->
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
  
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/validate_numbers.js"> 
  </script>
  <!-- Script para usar Enter en vez de TAB -->
  <script language="javascript" src="js/close_session.js"> 
  </script>
  <title>.::Anular Encomienda::.</title>
  <!-- Script para validar el navegador -->
  <?php
  	if ($_SESSION['TIPO_USUARIO'] == 1)
	{
		echo '<script language="javascript" src="js/navegador.js"></script>';
	}
  ?>
</head>

<!-- Global IE fix to avoid layout crash when single word size wider than column width -->
<!--[if IE]><style type="text/css"> body {word-wrap: break-word;}</style><![endif]-->

<body <?php if (!isset($_GET['btn_buscar'])) echo 'OnLoad="document.buscar_giro.txt_serie.focus();"'; if(isset($_SESSION['IS_LOGGED'])) echo 'onbeforeunload="ConfirmClose()" onunload="HandleOnClose()"'; ?>>
  <!-- START Main Page Container -->
  <div class="page-container">

   <!-- For alternative headers START PASTE here -->

    <!-- START A. HEADER -->
	<?php include_once('header.php'); ?>
	<!-- END A. HEADER -->

   <!-- For alternative headers END PASTE here -->

    <!-- START B. MAIN -->
    <div class="main">
<?php
	if (isset($_GET['btn_buscar']))
	{
		// DECLARAMOS LAS VARIABLES PARA LA OPERACION
		$id_oficina = $_SESSION['ID_OFICINA'];
		$doc_serie = '';
		$doc_numero = '';
		$id_mov = 0;
		// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
		$Error = false;
		$MsjError = '';
		// INCLUIMOS EL ARCHIVO PAR VALIDACIONES
		require_once("function/validacion.php");
		// OBTENEMOS LOS DATOS
		if (!isset($_GET['txt_serie']) || strlen($_GET['txt_serie']) == 0)
		{
			MsjErrores('Ingrese el n&uacute;mero de serie.');
		}
		else
		{
			esNumerico($_GET['txt_serie'], 'N&uacute;mero de Serie');
			$doc_serie = intval($_GET['txt_serie']);
		}
		if (!isset($_GET['txt_num_boleta']) || strlen($_GET['txt_num_boleta']) == 0)
		{
			MsjErrores('Ingrese el n&uacute;mero de documento.');
		}
		else
		{
			esNumerico($_GET['txt_num_boleta'], 'N&uacute;mero de Documento');
			$doc_numero = intval($_GET['txt_num_boleta']);
		}
		if ($Error == false)
		{
			// SI NO HAY ERRORES, OBTENEMOS LOS DATOS
			require_once 'cnn/config_giro.php';
			$sql = "SELECT `e_movimiento`.`id_movimiento`
					, `REMITENTE`.`per_nombre` as `REMIT`
					, `REMITENTE`.`per_num_dni` as `REMIT_RUC`
					, IFNULL(`REMITENTE`.`per_direccion`,'SIN DIRECCION') as `REMIT_DIRECCION`
					, `CONSIGNATARIO`.`per_nombre` as `CONSIG`
					, `e_movimiento`.`id_oficina_origen`
					, `e_movimiento`.`id_oficina_destino`
					, DATE_FORMAT(`e_movimiento`.`e_fecha_emision`,'%d-%m-%Y') AS `FECHA`
					, TIME_FORMAT(`e_movimiento`.`e_hora_emision`,'%r') AS `HORA`
					, `e_movimiento`.`e_documento`
					, CONCAT(RIGHT(CONCAT('00000', CAST(`e_movimiento`.`num_serie` AS CHAR)),4)
					, '-'
					, RIGHT(CONCAT('0000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS `NUM_GUIA`
					, `e_movimiento`.`e_subtotal`
					, `e_movimiento`.`e_igv`
					, `e_movimiento`.`e_total`
					, `e_movimiento`.`id_usuario`
					FROM `e_movimiento`
					INNER JOIN `e_persona` AS `REMITENTE`
					ON `e_movimiento`.`id_remitente` = `REMITENTE`.`id_persona`
					INNER JOIN `e_persona` AS `CONSIGNATARIO`
					ON `e_movimiento`.`id_consignatario` = `CONSIGNATARIO`.`id_persona`
					WHERE `e_movimiento`.`e_estado` = 1
					AND `e_movimiento`.`e_estado` <> 8
					AND `e_movimiento`.`num_serie` = '".$doc_serie."'
					AND `e_movimiento`.`num_documento` = '".$doc_numero."'";
			if (isset($_SESSION['TIPO_USUARIO']) && $_SESSION['TIPO_USUARIO'] <> 9)
				$sql = $sql ." AND `e_movimiento`.`id_oficina_origen` = '".$_SESSION['ID_OFICINA']."'";
			$sql = $sql ." LIMIT 1;";
			$db_giro->query($sql);
			$Enco_Array = $db_giro->get();
			// ASIGNAMOS LOS VALORES A LAS VARIABLES
			$id_mov = $Enco_Array[0][0];
			$remit_nombre = $Enco_Array[0][1];
			$remit_dni = $Enco_Array[0][2];
			$remit_direccion = $Enco_Array[0][3];
			$consig_nombre = $Enco_Array[0][4];
			$id_oficina_origen = $Enco_Array[0][5];
			$id_oficina_destino = $Enco_Array[0][6];
			$fecha = $Enco_Array[0][7];
			$Hora = $Enco_Array[0][8];
			$TIPO_DOC = $Enco_Array[0][9];
			$num_guia = $Enco_Array[0][10];
			$subtotal = $Enco_Array[0][11];
			$igv = $Enco_Array[0][12];
			$total = $Enco_Array[0][13];
			$id_usuario = $Enco_Array[0][14];
			
			// OBTENEMOS DETALLE DE LA ENCOMIENDA
			$sql = "SELECT `e_mov_detalle`.`md_codigo`
					, `e_mov_detalle`.`md_cantidad`
					, `e_mov_detalle`.`md_unidad`
					, `e_mov_detalle`.`md_descripcion`
					, `e_mov_detalle`.`md_peso`
					, `e_mov_detalle`.`md_flete`
					FROM `e_mov_detalle`
					WHERE `id_movimiento` = '".$id_mov."';";
			$db_giro->query($sql);
			$Enco_Array = $db_giro->get();
			
		}
	}
?>
      <!-- START B.1 MAIN CONTENT -->
      <!-- B.1 MAIN CONTENT -->
        <div class="main-content">
<?php
	if (!isset($_GET['btn_buscar']))
	{
?>
            <!-- Pagetitle -->
            <h1 class="pagetitle">Anulaci&oacute;n de Encomienda.</h1>
        
            <!-- Content unit - One column -->
            <h1 class="block">Zona de Busqueda</h1>
            <form method="get" action="e_anulacion.php" name="buscar_giro" >
                    <table width="100%" border="0">
                        <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                            <th>Serie :</th>
                            <td><input type="text" name="txt_serie" style="width:220px;" value="" onkeypress="return handleEnter(this,event);" tabindex="1" /></td>
                            <th>N&uacute;mero Boleta :</th>
                            <td><input type="text" name="txt_num_boleta" style="width:220px;" value="" onkeypress="return handleEnter(this,event);" tabindex="2" /></td>
                        </tr>
                        <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                            <td colspan="4"><span>*</span> Serie = 1 en caso de <span>Guia Interna</span></td>
                        </tr>
                        <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                            <td colspan="2" style="text-align:right;">
                                <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="3" /></span>
                            </td>
                            <td colspan="2" style="text-align:left; ">
                                <span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="4" style="margin-left:35px;" /></span>
                            </td>
                        </tr>
                        
                    </table>
        
            </form>
            
            <!-- Limpiar Unidad del Contenido -->
            <hr class="clear-contentunit" />
<?php
	}
	else
	{
		if ($Error == true)
		{
					// Mostramos los Errores
					// MOSTRAMOS EL RESULTADO DE LOS ERRORES
					echo '<!-- Pagetitle -->';
					echo '<h1 class="pagetitle">Mensaje de Error</h1>';
					echo '<div class="column1-unit">';
					  echo '<h1>Detalle del o los errores.</h1>   ';                         
					  echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
					  echo '<p>'.$MsjError.'</p>';
?>
					  <p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; javascript:history.back(1)" ></p>
<?PHP
					echo '<!-- Limpiar Unidad del Contenido -->';
					echo '<hr class="clear-contentunit" />';
					
		}
		elseif ($Error == false)
		{
			if (count($Enco_Array) == 0)
			{
						// MOSTRAMOS EL RESULTADO DE LOS ERRORES
						echo '<!-- Pagetitle -->';
						echo '<h1 class="pagetitle">Mensaje de Error</h1>';
						echo '<div class="column1-unit">';
						  echo '<h1>Detalle del o los errores.</h1>   ';                         
						  echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>';
						  echo '<p>No se encontro detalles del giro.</p>';
				?>
						  <p style="text-align:center;"><input class="button" type="button" name="txtRegresar" id="txtRegresar" value="Regresar" onclick="this.disabled = 'true'; this.value = 'Enviando...'; javascript:history.back(1)" ></p>
<?PHP
						echo '<!-- Limpiar Unidad del Contenido -->';
						echo '<hr class="clear-contentunit" />';
			}
			else
			{
					// MOSTRAMOS LOS DATOS DEL GIRO PARA LA CONFIRMACION
?>

            <!-- Pagetitle -->
            <h1 class="pagetitle">Anular Giro</h1>
        
            <h1 class="block">Confirme los datos del giro.</h1>
            <!-- Content unit - One column -->
            <div class="column1-unit">
              <div class="contactform">
                <form name="anulacion_form" id="anulacion_form" method="post" action="e_anulacion_action.php?insert" class="">
                    <table border="0">
                      <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                        <th>Fecha : </th>
                        <td><?php echo $fecha; ?></td>
                        <th>Hora : </th>
                        <td><?php echo $Hora; ?></td>
                      </tr>
                      <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                        <th>Agencia :</th>
                        <td><?PHP echo OficinaByID($id_oficina_origen); ?></td>
                        <th>Destino : </th>
                        <td><?PHP echo OficinaByID($id_oficina_destino); ?></td>
                      </tr>
                      <tr id="DivDocumentoSN"  onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                        <th>Documento : </th>
                        <td><?php echo $TIPO_DOC; ?></td>
                        <td colspan="2">GU&Iacute;A: <span><?php echo $num_guia; ?></span></td>
                      </tr>
                      <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                        <th>Remitente : </th>
                        <td colspan="4"><?php echo utf8_encode($remit_nombre); ?></td>
                      </tr>
                      <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                        <th>Consignatario : </th>
                        <td colspan="4"><?php echo utf8_encode($consig_nombre); ?></td>
                      </tr>
                      </table>
                      <table>

<?php
						// MOSTRAMOS LOS RESULTADOS PARA LA GUIA INTERNA
						if ($TIPO_DOC == 'GUIA INTERNA')
						{
							$db_giro->query("SELECT `e_mov_detalle`.`md_cantidad`
											, `e_mov_detalle`.`md_descripcion`
											, `e_mov_detalle`.`e_num_item`
											FROM `e_mov_detalle`
											WHERE `e_mov_detalle`.`id_movimiento` = '".$id_mov."';");
							$List_Item = $db_giro->get();
							$List_Cantidad = "";
							echo '<table width="710" border="0">';
							echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">
								<th style="text-align:center;">CANT</th>
								<th colspan="4" style="text-align:center;">DESCRIPCI&Oacute;N <span>( Limite 5 Items )</span></th>
							</tr>';
							for ($fila = 0; $fila < count($List_Item); $fila++)
							{
								  echo '<tr  onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
									echo '<td width="108" style="text-align:center;">'.$List_Item[$fila][0].'</td>';
									echo '<td width="542">'.$List_Item[$fila][1].'</td>';
									echo '<td width="46" style="text-align:center;"></td>';
								  echo "</tr>";
							}
							echo "</table>";
						}
						// MOSTRAMOS LOS RESULTADOS PARA LA BOLETA
						if ($TIPO_DOC == 'BOLETA' || $TIPO_DOC == 'FACTURA')
						{
							$db_giro->query("SELECT `e_mov_detalle`.`md_cantidad`
											, `e_mov_detalle`.`md_descripcion`
											, `e_mov_detalle`.`e_num_item`
											, `e_mov_detalle`.`md_flete`
											, `e_mov_detalle`.`md_carrera`
											, (`e_mov_detalle`.`md_flete` +  `e_mov_detalle`.`md_carrera`) AS 'IMPORTE'
											FROM `e_mov_detalle`
											WHERE `e_mov_detalle`.`id_movimiento` = '".$id_mov."';");
							$List_Item = $db_giro->get();
							$List_Cantidad = "";
							// OBTENEMOS EL TOTAL DEL IMPORTE
							$db_giro->query("SELECT SUM(`e_mov_detalle`.`md_importe`) AS 'TOTAL'
											FROM `e_mov_detalle`
											WHERE `e_mov_detalle`.`id_movimiento` = '".$id_mov."';");
							$total = $db_giro->get('TOTAL');
							echo '<table width="710" border="0">';
							echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">
								<th>CANT</th>
								<th style="text-align:center;">DESCRIPCI&Oacute;N</th>
								<th>FLETE</th>
								<th style="text-align:center;">CARRERA</th>
								<th style="text-align:center;">TOTAL</th>
							</tr>';
							for ($fila = 0; $fila < count($List_Item); $fila++)
							{
								
								  echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
									echo '<td width="200" style="text-align:center;">'.$List_Item[$fila][0].'</td>';
									echo '<td width="660" style="vertical-align:middle;" >'.$List_Item[$fila][1].'</td>';
									echo '<td width="90">'.$List_Item[$fila][3].'</td>';
									echo '<td width="100">'.$List_Item[$fila][4].'</td>';
									echo '<td width="70" style="text-align:center;">'.$List_Item[$fila][5].'</td>';
								  echo "</tr>";
							}
							echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
									echo '<th colspan="4" style="text-align:right;"><span>Total</span></th>';
									echo '<th style="text-align:right;"><span>'.$total.'</span></th>';
							echo "</tr>";
							echo "</table>";
						}
						// MOSTRAMOS LOS RESULTADOS PARA LA BOLETA
						if ($TIPO_DOC == 'GUIA REMISION')
						{
							$db_giro->query("SELECT 
											`e_mov_detalle`.`e_num_item`
											, `e_mov_detalle`.`md_codigo`
											, `e_mov_detalle`.`md_cantidad`
											, `e_mov_detalle`.`md_unidad`
											, `e_mov_detalle`.`md_descripcion`
											, `e_mov_detalle`.`md_peso`
											, `e_mov_detalle`.`md_flete`
											, `e_mov_detalle`.`md_importe`
											FROM `e_mov_detalle`
											WHERE `e_mov_detalle`.`id_movimiento` = '".$id_mov."';");
							$List_Item = $db_giro->get();
							$List_Cantidad = "";
							// OBTENEMOS EL TOTAL DEL IMPORTE
							$db_giro->query("SELECT 
							SUM(`e_mov_detalle`.`md_importe`) AS 'TOTAL'
							FROM `e_mov_detalle`
							WHERE `e_mov_detalle`.`id_movimiento` = '".$id_mov."';");
							$total = $db_giro->get('TOTAL');
							echo '<table width="710" border="0">';
							echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">
								<th>C&Oacute;DIGO</th>
								<th style="text-align:center;">CANT</th>
								<th style="text-align:center;">UNID.</th>
								<th style="text-align:center;">DESCRIPCI&Oacute;N<span> ( Limite 5 Items )</span></th>
								<th style="text-align:center;">PESO</th>
								<th style="text-align:center;">FLETE</th>
							</tr>';
							for ($fila = 0; $fila < count($List_Item); $fila++)
							{
								
								  echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
									echo '<td width="200" style="text-align:center;">'.$List_Item[$fila][1].'</td>';
									echo '<td width="100" style="text-align:center;">'.$List_Item[$fila][2].'</td>';
									echo '<td width="120" style="text-align:center;">'.$List_Item[$fila][3].'</td>';
									echo '<td width="560" style="vertical-align:middle;" >'.$List_Item[$fila][4].'</td>';
									echo '<td width="100">'.$List_Item[$fila][5].'</td>';
									echo '<td width="70" style="text-align:right;">'.$List_Item[$fila][6].'</td>';
								  echo "</tr>";
							}
							echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
									echo '<th colspan="5" style="text-align:right;"><span>Total</span></th>';
									echo '<th style="text-align:right;"><span>'.$total.'</span></th>';
							echo "</tr>";
							echo "</table>";
						}
?>
                      </table>
                      <table>
                      <tr style="height:20px; font-size:80%;">
                        <th>Usuario:</th>
                        <td><span>
                        <?PHP
                            /* MOSTRAMOS EL NOMBRE DEL USURIO QUE REALIZA LA OPERACION */
                            echo strtoupper($_SESSION['USUARIO']);
                        ?>				
                            </span></td>
                        <th>Agencia : </th>
                        <td><span>
                        <?PHP
                            /* MOSTRAMOS EL NOMBRE DE LA AGENCIA DONDE SE REALIZA LA OPERACION */
                            echo strtoupper($_SESSION['OFICINA']);
                        ?>				
                            </span></td>
                      </tr>
                      <tr>
                        <th colspan="5"><input name="txt_id_movimiento" type="hidden" value="<?php echo $id_mov; ?>" readonly="readonly" /></th>
                      </tr>
                      <tr>
                        <th colspan="2" style="text-align:right;" id="132">
                            <span><input name="btn_guardar" id="btn_guardar" type="submit" class="button" value="Anular" tabindex="19" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.anulacion_form.submit();" /></span>                        </th>
                        <td colspan="2" style="text-align:left; padding-left:40px;">
                            <span><input type="button" name="btn_regresar" id="btn_regresar" class="button" value="Regresar" tabindex="6" onclick="document.location.href='g_anulacion.php';" /></span>                        </td>
                      </tr>
                    </table>
                </form>
              </div>              
            </div>
            
            <!-- Limpiar Unidad del Contenido -->
            <hr class="clear-contentunit" />
      
<?php
			}
		}	
	}
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



