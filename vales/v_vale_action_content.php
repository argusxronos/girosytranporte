<?php
	// INCLUIMOS SCRIPT PARA LAS VALIDACIONES
	include_once('function/validacion.php');
	include_once('function/getIDorName.php');
	// CREAMOS UNA VARIABLE PARA ALMACENAR LOS DATOS	
	require_once 'cnn/config_master.php';
	if(isset($_GET['insert'])){
		$id_vale="NULL";
		$monto=$_POST[monto_vale];
		$motivo=strtoupper($_POST[motivo]);
		$empleado=strtoupper($_POST[empleado]);
		$usuario=strtoupper($_SESSION['USUARIO']);
		$agencia=strtoupper($_SESSION['OFICINA']);
		$db_transporte->query("INSERT INTO vales(vales.`id_vale`,vales.`motivo`,vales.`monto`,vales.`empleado`,vales.`fecha_crea`,vales.`hora_crea`,
								vales.`u_crea`,vales.`agencia`)
								VALUES('$id_vale','$motivo','$monto','$empleado',CURRENT_DATE(),CURRENT_TIME(),'$usuario','$agencia')");
		$db_transporte->query("SELECT MAX(id_vale) AS id FROM vales");
		$Array=$db_transporte->get();
		$codigo=$Array[0][0];
		$db_transporte->query("SELECT monto,fecha_crea,agencia,motivo,empleado,u_crea FROM vales
								WHERE id_vale='$codigo'");
		$Vale_Array=$db_transporte->get();
		//INGRESAMOS LOS DATOS EN UN ARRAY
		if(count($Vale_Array)>0){
			//OBTENEMOS LOS VALORES
			$monto_vale=$Vale_Array[0][0];
			$fecha_vale=$Vale_Array[0][1];
			$agencia_vale=$Vale_Array[0][2];
			$motivo_vale=$Vale_Array[0][3];
			$empleado_vale=$Vale_Array[0][4];
			$usuario_vale=$Vale_Array[0][5];
		}		
	}
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
	<div class="Print_Vale">
		<div class="vale_content">
			<table width="400" border="0">		
			<?php			
            $ano = substr($fecha_vale, 0, 4);
            $mes = substr($fecha_vale, 5, 2);
            $dia = substr($fecha_vale, -2);
            $fecha_vale = $dia . '/' . $mes . '/' . $ano;           
			?>	  
			  <tr>
				<td colspan="2" class="monto" style="height:10px;">S/.<?php echo number_format($monto_vale,2);?></td>
			  </tr>
			  <tr>
				<td style="font-size:7px;width:70px;letter-spacing:3px;">FECHA: </td>
				<td class="text_left" style="font-size:95%;width:300px;"><?php echo $fecha_vale;?></td>
			  </tr>
			  <tr>
			  	<td style="font-size:7px;width:70px;letter-spacing:3px;">HORA: </td>
			  	<td class="text_left"><?php echo date(" h:i A");?></td>
			  </tr>
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">AGENCIA:</td>
				<td class="text_left"><?php echo $agencia_vale;?></td>
			  </tr>
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">MOTIVO: </td>
				<td class="text_left" style="line-height:220%;"><?php echo $motivo;?></td>				
			  </tr>
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">SOLICITA: </td>
				<td class="text_left"><?php echo $empleado_vale;?></td>
			  </tr>			  
			  <tr>
				<td style="font-size:7px;letter-spacing:3px;">USUARIO: </td>
				<td class="text_left"><?php echo $_SESSION['USUARIO']; ?></td>
			  </tr>
			  
			  <tr><td colspan="2"></td></tr>
			  <tr><td colspan="2"></td></tr>
			  <tr><td colspan="2"></td></tr>			  
			  <tr style="height:5px;">
				<th  colspan="2" class="firma_vale"><HR></th>
			  </tr>
			  <tr>
				<th  colspan="2" class="text_center" style="letter-spacing:4px;height:5px;">RECIBÍ CONFORME	</th>
			  </tr>
			  <tr>
				<th  colspan="2" class="text_center" style="letter-spacing:4px;height:5px;">D.N.I.: </th>
			  </tr>
			</table>
	  </div>		
	</div>

	<script language="JavaScript"> 
			window.print();
			window.onfocus = function() 
			{
				/*window.open('','_parent','');*/
				location.href='v_vale.php';
			}
	</script>
</div>

