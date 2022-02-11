<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	// VERIFICAMOS SI ESTA LOGEADO
	// VERIFICAMOS SI ESTA LOGEADO
	require_once("is_logged.php");
	require_once 'cnn/config_giro.php';
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
	
	function UserByID($id_user)
	{
		$Users_Array = $_SESSION['USERS'];
		$Usuario = '';
		for ($fila = 0; $fila < count($Users_Array); $fila++)
		{
			if($Users_Array[$fila][0] == $id_user)
			{
				$Usuario = $Users_Array[$fila][1];
				break;
			}
		}
		return $Usuario;
	}
	function UserNombreByID($id_user)
	{
		$Users_Array = $_SESSION['USERS'];
		$UserName = '';
		for ($fila = 0; $fila < count($Users_Array); $fila++)
		{
			if($Users_Array[$fila][0] == $id_user)
			{
				$UserName = utf8_encode($Users_Array[$fila][2]);
				break;
			}
		}
		return $UserName;
	}
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Cancelar Envio de Encomienda y/o Anular Liquidaci&oacute;n.</h1>
    <?php 
	if (!isset($_GET['btn_buscar']) || strlen($_GET['txt_num_liquidacion']) == 0)
	{
?>
	<!-- Content unit - One column -->
	<div class="column1-unit">
        <div id="zona-busqueda">
          <h1>Zona de Busqueda</h1>
        <?php echo '<h3>'.date("l j \d\e F, Y, g:i a").', por <a href="mailto:sugerencias@turismocentral.com.pe">Administrador </a></h3>'; ?>
            <form method="get" action="e_cancelar.php" name="buscar_liquidacion" >
                <table width="100%" border="0">
                    <tr>
                        <th width="50%" style="text-align:right;">Nro. Liquidaci&oacute;n :</th>
                      	<td width="50%"><input type="text" name="txt_num_liquidacion" style="width:220px; text	-transform:uppercase;" value="" onkeypress="return handleEnter(this,event);" tabindex="1" /></td>
                    </tr>
                    <tr>
                        <th style="text-align:right;">
                            <span><input name="btn_buscar" id="btn_buscar" type="submit" class="button" value="Buscar" tabindex="2" /></span>					</th>
                        <th style="text-align:left; ">
                            <span><input type="reset" name="btn_limpiar" id="btn_reset" class="button" value="Limpiar" tabindex="20" style="margin-left:35px;" /></span>					</th>
                    </tr>
                </table>
    
          </form>
        </div>
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
    <div id="div_error">
    </div>
<?PHP
	}
	elseif (strlen($_GET['txt_num_liquidacion']) > 0)
	{
		$id_liquidacion = 0;
		// MOSTRAMOS EL GIRO A CANCELAR
		$num_liquidacion = $_GET['txt_num_liquidacion'];
		// CREAMOS LAS VARIABLES PARA LA CAPTURAR DE ERRORES
		$Error = false;
		$MsjError = '';
		
		// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
		include_once('function/validacion.php');
		/***********************************************************************/
		/* VERIFICAMOS SI EL IDMOVIMIENTO EXISTE Y NO ESTA CANCELADO Y ANULADO */
		/***********************************************************************/
		
		$db_giro->query("SELECT COUNT(`e_liquidacion`.`id_liquidacion`)  
						AS 'EXISTE'
						FROM `e_liquidacion`
						WHERE `e_liquidacion`.`id_oficina_origen` = ".$_SESSION['ID_OFICINA']."
						AND `e_liquidacion`.`liq_estado` = 1
						AND `e_liquidacion`.`liq_num_doc` = ".$num_liquidacion.";");
		$existe_mov = $db_giro->get('EXISTE');
		if ($existe_mov == 0)
		{
			MsjErrores('Liquidación no encontrada, intentelo de nuevo o consulte con el administrador.');
		}
		/***************************************/
		/* OBTENEMOS LOS DATOS DEL MOVIMIENTOS */
		/***************************************/
		if ($Error == false)
		{
			// OBTENEMOS LOS DATOS DE LA LIQUIDACION
			$db_giro->query("SELECT
			`e_liquidacion`.`id_liquidacion`,
			`e_liquidacion`.`id_usuario`,
			`e_liquidacion`.`id_oficina_origen`,
			`e_liquidacion`.`id_oficina_destino`,
			`e_liquidacion`.`liq_fecha`,
			`e_liquidacion`.`liq_hora`,
			`e_liquidacion`.`liq_chofer`,
			`e_liquidacion`.`liq_pullman`
			FROM `bd_giro`.`e_liquidacion`
			WHERE `e_liquidacion`.`id_oficina_origen` = ".$_SESSION['ID_OFICINA']."
			AND `e_liquidacion`.`liq_estado` = 1
			AND `e_liquidacion`.`liq_num_doc` = ".$num_liquidacion."
			LIMIT 1;
			");
			$LIQ_Array = $db_giro->get();
			// MOSTRAMOS LOS DATOS
			if (count($LIQ_Array) > 0)
			{
				//OBTENEMOS LOS DATOS EN LAS VARIABLES
				$id_liquidacion = $LIQ_Array[0][0];
				$id_usuario = $LIQ_Array[0][1];
				$id_oficina_origen = $LIQ_Array[0][2];
				$id_oficina_destino = $LIQ_Array[0][3];
				$fecha = $LIQ_Array[0][4];
				$Hora = $LIQ_Array[0][5];
				$chofer = $LIQ_Array[0][6];
				$pullman = $LIQ_Array[0][7];
?>
	<div class="column1-unit">
      <div class="contactform">
        <form name="giro_form" id="anulacion_form" method="post" action="e_cancelar_action.php?insert" class="">
            <table border="0">
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th style="width:120px;">Fecha : </th>
                <td><?php echo $fecha; ?></td>
                <th style="width:120px;">Hora : </th>
                <td><?php echo $Hora; ?></td>
              </tr>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th title="Agencia Origen de la Liquidaci&oacute;n">Ag. Origen :</th>
                <td><?PHP echo OficinaByID($id_oficina_origen); ?></td>
                <th title="Agecia Destino de la Liquidaci&oacute;n">Ag. Destino : </th>
                <td><span><?PHP echo OficinaByID($id_oficina_destino); ?></span></td>
              </tr>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th>Chofer : </th>
                <td colspan="4"><?php echo utf8_encode($chofer); ?></td>
              </tr>
              <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                <th>Pullman : </th>
                <td><?php echo $pullman; ?></td>
                <th>Documento  : </th>
                <td><span><?php echo $num_liquidacion; ?></span></td>
              </tr>
              </table>
<?php
			$db_giro->query("SELECT `e_mov_detalle`.`id_movimiento`
			, `e_mov_detalle`.`e_num_item`
			, `e_movimiento`.`id_oficina_destino`
			, CONCAT(RIGHT(CONCAT('00000', CAST(`e_movimiento`.`num_serie` AS CHAR)),4)
			, '-'
			, RIGHT(CONCAT('0000000', CAST(`e_movimiento`.`num_documento` AS CHAR)),8)) AS `NUM_GUIA`
			, IF(`CONSIG`.`per_tipo` = 'PERSONA', `CONSIG`.`per_nombre`, `CONSIG`.`per_razon_social`) AS `CONSIGNATARIO`
			, CAST(CONCAT(`e_mov_detalle`.`md_cantidad`
			, ' - '
			, `e_mov_detalle`.`md_descripcion`) AS CHAR) AS `CONTENIDO`
			, `e_mov_detalle`.`md_carrera`
			, `e_mov_detalle`.`md_importe`
			FROM `e_liquidacion_detalle`
			INNER JOIN `e_mov_detalle`
			ON `e_liquidacion_detalle`.`id_movimiento` = `e_mov_detalle`.`id_movimiento`
			AND `e_liquidacion_detalle`.`e_num_item` = `e_mov_detalle`.`e_num_item`
			INNER JOIN `e_movimiento`
			ON `e_mov_detalle`.`id_movimiento` = `e_movimiento`.`id_movimiento`
			INNER JOIN `e_persona` AS `CONSIG`
			ON `CONSIG`.`id_persona` = `e_movimiento`.`id_consignatario`
			WHERE  `e_liquidacion_detalle`.`id_liquidacion` = ".$id_liquidacion."
			AND `e_liquidacion_detalle`.`ld_estado` = 1
			AND `e_mov_detalle`.`md_estado` = 2
			ORDER BY `e_movimiento`.`id_oficina_destino`
			, `e_movimiento`.`num_serie` ASC
			, `e_movimiento`.`num_documento` ASC
			, `e_mov_detalle`.`id_movimiento`
			, `e_mov_detalle`.`e_num_item`;");
			$Array_liquidacion_list = $db_giro->get();
		if (count($Array_liquidacion_list) > 0)
		{
		/* SI NO HAY ERRORES EN LA TRANSACCION MOSTRAMOS LA LISTA */
			echo '<table border="0">';
				echo '<tr>';
					echo '<th style="width:50px; text-align:center;"># GUIAS</th>';
					echo '<th style="width:325px;">CONSIGNATARIO</th>';
					echo '<th style="width:300px; text-align:center;">CONTENIDO DE LA GUIA</th>';
					echo '<th style="width:40px; text-align:center;">CARRERA</th>';
					echo '<th style="width:40px; text-align:center;">VALOR</th>';
					echo '<th style="width:40px; text-align:center;">ACCI&Oacute;N</th>'; 	  
				echo '</tr>';
			$Oficina_Actual = 0;
			$guia_actual = '';
          	for ($fila = 0; $fila < count($Array_liquidacion_list); $fila ++)
			{
				$id_movimiento = $Array_liquidacion_list[$fila][0];
				$num_item = $Array_liquidacion_list[$fila][1];
				$oficina = $Array_liquidacion_list[$fila][2];
				$guia = $Array_liquidacion_list[$fila][3];
				$consignatario = utf8_encode($Array_liquidacion_list[$fila][4]);
				$descripcion = utf8_encode($Array_liquidacion_list[$fila][5]);
				$carrera = $Array_liquidacion_list[$fila][6];
				$importe = $Array_liquidacion_list[$fila][7];
				if ($Oficina_Actual != $oficina)
				{
					echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
						echo '<td colspan="6" style="text-align:center;"><span>'.OficinaByID($oficina).'</span></td>';
					echo '</tr>';
					$Oficina_Actual = $oficina;
				}
				
				echo '<tr id="div_tr_'.$id_movimiento.$num_item.'"  onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'">';
					if ($guia_actual != $guia)
					{
						echo '<td style="text-align:center;">'.$guia.'</td>';
						echo '<td>'.$consignatario.'</td>';
						$guia_actual = $guia;
					}
					else
					{
						echo '<td style="text-align:center;">&nbsp;</td>';
						echo '<td>&nbsp;</td>';
					}
					echo '<td>'.$descripcion.'</td>';
					echo '<td style="text-align:right;">'.$carrera.'</td>';
					echo '<td style="text-align:right;">'.$importe.'</td>';
					echo '<td style="text-align:center;"><a style="cursor: hand;" onclick="Delete_Item_Liq('.$id_movimiento.', '.$num_item.', '.$id_liquidacion.')"><img src="./img/operacion/Symbol-Delete.png" width="24" height="24" style="margin-left:16px;" title="Eliminar esta encomienda de la lista." style="text-align:center;" /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		else
		{
			/* MOSTRAMO EL MENSAJE DE ERROR EN UNA TABLA */
			echo '<table border="0">';
			  echo '<tr>';
				echo '<th style="width:50px; text-align:center;"># GUIAS</th>';
				echo '<th style="width:295px;">CONSIGNATARIO</th>';
				echo '<th style="width:220px; text-align:center;">CONTENIDO DE LA GUIA</th>';
				echo '<th style="width:80px; text-align:center;">CARRERA</th>';
				echo '<th style="width:80px; text-align:center;">VALOR</th>';
				echo '<th style="width:90px; text-align:center;">ACCI&Oacute;N</th>'; 	  
			  echo '</tr>';
			  echo '<tr>';
					echo '<td colspan="6" style="text-align:center;"><span>No hay encomiendas Pendientes</span></td>';
			  echo '</tr>';
			echo '</table>';
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
                    </span>                        </td>
                <th>Agencia : </th>
                <td><span>
                <?PHP
                    /* MOSTRAMOS EL NOMBRE DE LA AGENCIA DONDE SE REALIZA LA OPERACION */
                    echo strtoupper($_SESSION['OFICINA']);
                ?>				
                    </span>                        </td>
              </tr>
              <tr>
                <th colspan="5"><input name="txt_id_liquidacion" id="txt_id_liquidacion" type="hidden" value="<?php echo $id_liquidacion; ?>" readonly="readonly" /></th>
              </tr>
              <tr>
                <th colspan="2" style="text-align:right;" id="132">
                    <span><input name="btn_guardar" id="btn_guardar" type="submit" class="button" style="width:220px;" value="Anular Todos" tabindex="19" onclick="this.disabled = 'true'; this.value = 'Enviando...'; document.anulacion_form.submit();" /></span>                        </th>
                <td colspan="2" style="text-align:left; padding-left:40px;">
                    <span><input type="button" name="btn_regresar" id="btn_regresar" class="button" style="width:220px;" value="Regresar" tabindex="6" onclick="document.location.href='g_anulacion.php';" /></span>                        </td>
              </tr>
            </table>
        </form>
      </div>              
    </div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
<?PHP
			}
			else
			{
				MsjErrores('No se encontr&oacute; registro de la liquidaci&oacute;m, vuelva a intentarlo o consulte con el Administrador');
				// MOSTRAMOS EL MENSAJE DE ERROR
				echo '<!-- Content unit - One column -->';
				echo '<div class="column1-unit">';
					echo '<h1>Error con la Operaci&oacute;n</h1>';
					echo '<p>'.utf8_encode($MsjError).'</p>';
				echo '</div>';
				echo '<!-- Limpiar Unidad del Contenido -->';
				echo '<hr class="clear-contentunit" />';
			}
		}
		else
		{
			// MOSTRAMOS EL MENSAJE DE ERROR
			echo '<!-- Content unit - One column -->';
			echo '<div class="column1-unit">';
				echo '<h1>Error con la Operaci&oacute;n</h1>';
				echo '<p>'.utf8_encode($MsjError).'</p>';
			echo '</div>';
			echo '<p style="text-align:center;"><input type="button" name="btn_regresar" id="btn_regresar" value="Cancelar otro Envio" class="button" style="width:200px;" onclick="this.disabled = \'true\'; this.value = \'Enviando...\';location.href=\'e_cancelar.php\'" ></p>';
			echo '<!-- Limpiar Unidad del Contenido -->';
			echo '<hr class="clear-contentunit" />';
		}
	}
 ?>
</div>