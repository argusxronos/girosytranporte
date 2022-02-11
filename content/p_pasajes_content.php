<?php 
	/* CODIGO PARA OBTENER LOS CODIGOS Y NOMBRES DE LAS OFICINAS */
	$Oficina_Array = $_SESSION['OFICINAS'];
	$ofici_nombre=$_SESSION['OFICINA'];
	require_once 'cnn/config_master.php';
	$existe = '';
	$existen = '';
	// CREAMOS LA CONSULTA DE BUSQUEDA
	if(isset($_GET['buscar']))
	{
		$buscar=$_POST['buscar'];
		$agencia=$_POST['cmb_agencia'];		
		$sql = "SELECT salida.`fecha`,ruta.`destino`,salida.`hora`,oficinas.`oficina`,bus.`flota`,bus.`marca`,id_salida,oficinas.`idoficina`,bus.`id_bus`,ruta.`id_ruta`
			FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
			INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
			INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
			WHERE salida.`fecha`='$buscar'";
		if($agencia > 0)
			$sql = $sql . " AND oficinas.`idoficina`='$agencia'";
		$sql_rows = "SELECT COUNT(id_salida) AS TOTAL
			FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
			INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
			INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
			WHERE salida.`fecha`='$buscar'";
		if($agencia > 0)
			$sql_rows = $sql_rows . " AND oficinas.`idoficina`='$agencia'";
	}
	else {
		$sql = "SELECT salida.`fecha`,ruta.`destino`,salida.`hora`,oficinas.`oficina`,bus.`flota`,bus.`marca`,id_salida,oficinas.`idoficina`,bus.`id_bus`,ruta.`id_ruta`
			FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
			INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
			INNER JOIN oficinas ON salida.`idoficina`=oficinas.`idoficina`
			WHERE fecha = CURDATE() AND oficinas.`oficina`='$ofici_nombre'";
		$sql_rows = "SELECT COUNT(id_salida) AS TOTAL
			FROM salida INNER JOIN bus ON bus.`id_bus`=salida.`id_bus` 
			INNER JOIN ruta ON salida.`id_ruta`=ruta.`id_ruta` 
			INNER JOIN oficinas ON salida.`idoficina`= oficinas.`idoficina`
			WHERE fecha=CURDATE() AND oficinas.`oficina`='$ofici_nombre'";
	}	
	// AREA PARA LA PAGINACION 
	$page = $_GET['page'];
	$cantidad = 20;
	
	$paginacion = new Paginacion($cantidad, $page);
	
	$from = $paginacion->getFrom();
	$sql = $sql ." ORDER BY salida.`fecha` DESC LIMIT $from, $cantidad;";
	
	$sql_rows = $sql_rows .';';
		
	// REALIZAMOS LA CONSULTA A LA BD
	$db_transporte->query($sql_rows);
	$totalRows = $db_transporte->get('TOTAL');
	
	$db_transporte->query($sql);
	$Trans_Array = $db_transporte->get();
	
?>
<!-- B.1 MAIN CONTENT -->
<div class="main-content">
        
	<!-- Pagetitle -->
	<h1 class="pagetitle">Venta de Pasajes</h1>
    <?php 
	if (!isset($_GET['ID']))
	{
?>

<!-- Script para mensaje de confirmacion de eliminacion de datos -->
	<script>
    function confirmDelete(link) {
        if (confirm("¿Esta seguro de eliminar los datos?")) {
            doAjax(link.href, "POST"); // doAjax needs to send the "confirm" field
        }
        return false;
    }
	</script>
<!--fin de script-->

	<!-- Contenido del Formulario -->
	<div class="column1-unit">
		<script type="text/javascript">
			function validar(e) {
				var tecla = (document.all) ? e.keyCode : e.which;
				var contenido = document.getElementById("hr").value;
				if (tecla==8 || tecla==0)
					return true;
				if (contenido == "" || contenido < 2)
					patron =/\d/;
				else if (contenido == 2)
					patron =/[0-4]/;
				else return false;
				te = String.fromCharCode(tecla);
				return patron.test(te);
			}
		</script>
		<?php
			if(isset($_GET['asientos_bus'])){		
				$bus=$_GET['asientos_bus'];
				$id_salidas=$_GET['salida'];
				$destino_salida=$_GET['destino'];
				$origen=$_GET['origen'];
				$fecha_salida=$_GET['fecha'];
				$hora_salida=$_GET['hora'];
				$id_rutas=$_GET['ruta'];								
				//echo $bus; Muestra la id del bus
				//echo $id_salidas;  Muestra la id de la salida
				$piso1="SELECT*FROM configuracion_bus WHERE id_bus='$bus' and piso='1' ORDER BY fila";				
				$db_transporte->query($piso1);				
				$Piso1_Array = $db_transporte->get();	
				
				$piso2="SELECT*FROM configuracion_bus WHERE id_bus='$bus' and piso='2' ORDER BY fila";
				$db_transporte->query($piso2);
				$Piso2_Array=$db_transporte->get();

				$db_transporte->query("SELECT estado,hora_reserva,asiento,fecha_viaje,id_salida FROM record_cliente WHERE id_salida='$id_salidas' AND  estado='8'");
				$Cambiar_Estados_Array=$db_transporte->get();
				for($h=0;$h<count($Cambiar_Estados_Array);$h++){
					if($Cambiar_Estados_Array[$h][1]<=date("H:i:s") && $Cambiar_Estados_Array[$h][3]==$fecha_salida){
						$salida_elim=$Cambiar_Estados_Array[$h][4];
						$db_transporte->query("DELETE FROM record_cliente WHERE id_salida='$salida_elim' AND estado='8'");
						echo date('H:i:s');
						//echo $Cambiar_Estados_Array[$h][3];
						//echo $fecha_salida;
						//echo $Cambiar_Estados_Array[$h][1];
						//echo $Cambiar_Estados_Array[$h][2];
					}
				}				
		?>			
			<div id='contenido'>		
			<!--PRIMER PISO DE  BUSES- ASIENTOS LIBRES, RESERVADOS Y OCUPADOS-->
				<div id="contenido_izquierdo">
					<p style="text-align:center;">Primer Piso</p>
					<!-- primera columna 4-->
					<table>
					<?php
						$dir="1&salida=$id_salidas&origen=$origen&destino=$destino_salida&fecha=$fecha_salida&hora=$hora_salida&ruta=$id_rutas&bus=$bus";
						$db_transporte->query("SELECT estado,asiento,idoficina,serie_boleto,numero_boleto FROM record_cliente WHERE id_salida='$id_salidas' AND piso='1'");
						$Estados_Array=$db_transporte->get();
						//$numero=0;						
						for($i=0;$i<count($Piso1_Array);$i++){
					?>
							<tr>
								<?php if($Piso1_Array[$i][8]=="TI" || $Piso1_Array[$i][8]=="TM" || $Piso1_Array[$i][8]=="TD" || $Piso1_Array[$i][8]=="TV"){?>
									<td><img src="./images/tv.jpeg" width="45" height="25" title="TV." /></td>
									<?php }elseif($Piso1_Array[$i][8]=="ES"){
										echo '<td><img src="./images/esc.jpeg" width="40" height="30" title="Escalera." /></td>';
									}elseif($Piso1_Array[$i][8]=="TR"){
										echo '<td><img src="./images/tripu.jpeg" width="40" height="25" title="Tripulación"/></td>';
									}elseif($Piso1_Array[$i][8]=="BA"){
										echo '<td><img src="./images/baño.jpeg" width="40" height="30" title="Baño"/></td>';
									}elseif($Piso1_Array[$i][8]=="" || $Piso1_Array[$i][8]==" "){
										echo '<td><img src="./images/vacio.jpg" width="40" height="20"/></td>';
									}else{?>
									<!--mostrar los estados de cada asiento por codigo de salida-->
									<td>
										<?php
										for($var=0;$var<count($Estados_Array);$var++){
											if($Estados_Array[$var][1]==$Piso1_Array[$i][8] && $Estados_Array[$var][0]==1){
												$estados="vendidoM";
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][8]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][8];echo $Piso1_Array[$i][8];?></span>
										</div>
										<?php
											}
											if($Estados_Array[$var][1]==$Piso1_Array[$i][8] && $Estados_Array[$var][0]==2){
												$estados="vendidoF";
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][8]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe = $Piso1_Array[$i][8];echo $Piso1_Array[$i][8];?></span>
										</div>
										<?php
											}
											if($Estados_Array[$var][1]==$Piso1_Array[$i][8] && $Estados_Array[$var][0]==8){
												$estado_ofi=$Estados_Array[$var][2];
												$db_transporte->query("SELECT oficina AS ofi FROM oficinas WHERE idoficina='$estado_ofi'");
												$estados=$db_transporte->get("ofi");
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][8]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][8];echo $Piso1_Array[$i][8];?></span>
										</div>
										<?php
											}
										}
										if($existe!=$Piso1_Array[$i][8]){
												$estados="libre";
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_ventas.php?asientos=<?php echo $Piso1_Array[$i][8]?>&p=<?php echo $dir;?>'" >
											<span><?php echo $Piso1_Array[$i][8];?></span>
										</div>
										<?php
											}
										?>
									</td>
								<?php }?>												
								<?php if($Piso1_Array[$i][7]=="TI" || $Piso1_Array[$i][7]=="TM" || $Piso1_Array[$i][7]=="TD" || $Piso1_Array[$i][7]=="TV"){?>
									<td><img src="./images/tv.jpeg" width="45" height="25" title="TV." /></td>
									<?php }elseif($Piso1_Array[$i][7]=="ES"){
										echo '<td><img src="./images/esc.jpeg" width="40" height="30" title="Escalera." /></td>';
									}elseif($Piso1_Array[$i][7]=="TR"){
										echo '<td><img src="./images/tripu.jpeg" width="40" height="25" title="Tripulación"/></td>';
									}elseif($Piso1_Array[$i][7]=="BA"){
										echo '<td><img src="./images/baño.jpeg" width="40" height="30" title="Baño"/></td>';
									}elseif($Piso1_Array[$i][7]=="" || $Piso1_Array[$i][7]==" "){
										echo '<td><img src="./images/vacio.jpg" width="40" height="20"/></td>';
									}else {?>
									<!--Pasa numero de asiento al formulario form_ventas.php-->
									<td>										
										<?php
											for($var=0;$var<count($Estados_Array);$var++){
											if($Estados_Array[$var][1]==$Piso1_Array[$i][7] && $Estados_Array[$var][0]==1){
												$estados="vendidoM";
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][7]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][7];echo $Piso1_Array[$i][7];?></span>
										</div>
										<?php
											}
											if($Estados_Array[$var][1]==$Piso1_Array[$i][7] && $Estados_Array[$var][0]==2){
												$estados="vendidoF";	
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][7]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][7];echo $Piso1_Array[$i][7];?></span>
										</div>
										<?php
											}
											if($Estados_Array[$var][1]==$Piso1_Array[$i][7] && $Estados_Array[$var][0]==8){
												$estado_ofi=$Estados_Array[$var][2];
												$db_transporte->query("SELECT oficina AS ofi FROM oficinas WHERE idoficina='$estado_ofi'");
												$estados=$db_transporte->get("ofi");												
										?>	
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][7]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][7];echo $Piso1_Array[$i][7];?></span>
										</div>	
										<?php
											}
										}	
										if($existe!=$Piso1_Array[$i][7]){
												$estados="libre";																																																								
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_ventas.php?asientos=<?php echo $Piso1_Array[$i][7]?>&p=<?php echo $dir;?>'" >
											<span><?php echo $Piso1_Array[$i][7];?></span>
										</div>
										<?php
											}
										?>
									</td>
								<?php }?>
								<?php if($Piso1_Array[$i][6]=="TI" || $Piso1_Array[$i][6]=="TM" || $Piso1_Array[$i][6]=="TD" || $Piso1_Array[$i][6]=="TV"){?>
									<td><img src="./images/tv.jpeg" width="45" height="25" title="TV." /></td>
									<?php }elseif($Piso1_Array[$i][6]=="ES"){
										echo '<td><img src="./images/esc.jpeg" width="40" height="30" title="Escalera." /></td>';
									}elseif($Piso1_Array[$i][6]=="TR"){
										echo '<td><img src="./images/tripu.jpeg" width="40" height="25" title="Tripulación"/></td>';
									}elseif($Piso1_Array[$i][6]=="BA"){
										echo '<td><img src="./images/baño.jpeg" width="40" height="30" title="Baño"/></td>';
									}elseif($Piso1_Array[$i][6]=="" || $Piso1_Array[$i][6]==" "){
										echo '<td><img src="./images/vacio.jpg" width="40" height="20" /></td>';
									}else {?>
									<td>										
										<?php
											for($var=0;$var<count($Estados_Array);$var++){
											if($Estados_Array[$var][1]==$Piso1_Array[$i][6] && $Estados_Array[$var][0]==1){
												$estados="vendidoM";
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][6]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][6];echo $Piso1_Array[$i][6];?></span>
										</div>
										<?php
											}
											if($Estados_Array[$var][1]==$Piso1_Array[$i][6] && $Estados_Array[$var][0]==2){
												$estados="vendidoF";	
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][6]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][6];echo $Piso1_Array[$i][6];?></span>
										</div>
										<?php
											}
											if($Estados_Array[$var][1]==$Piso1_Array[$i][6] && $Estados_Array[$var][0]==8){
												$estado_ofi=$Estados_Array[$var][2];
												$db_transporte->query("SELECT oficina AS ofi FROM oficinas WHERE idoficina='$estado_ofi'");
												$estados=$db_transporte->get("ofi");												
										?>	
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][6]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][6];echo $Piso1_Array[$i][6];?></span>
										</div>	
										<?php
											}
										}	
										if($existe!=$Piso1_Array[$i][6]){
												$estados="libre";																																																								
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_ventas.php?asientos=<?php echo $Piso1_Array[$i][6]?>&p=<?php echo $dir;?>'" >
											<span><?php echo $Piso1_Array[$i][6];?></span>
										</div>
										<?php
											}
										?>
									</td>
								<?php }?>
								<?php if($Piso1_Array[$i][5]=="TI" || $Piso1_Array[$i][5]=="TM" || $Piso1_Array[$i][5]=="TD" || $Piso1_Array[$i][5]=="TV"){?>
									<td><img src="./images/tv.jpeg" width="45" height="25" title="TV." /></td>
									<?php }elseif($Piso1_Array[$i][5]=="ES"){
										echo '<td><img src="./images/esc.jpeg" width="40" height="30" title="Escalera." /></td>';
									}elseif($Piso1_Array[$i][5]=="TR"){
										echo '<td><img src="./images/tripu.jpeg" width="40" height="25" title="Tripulación"/></td>';
									}elseif($Piso1_Array[$i][5]=="BA"){
										echo '<td><img src="./images/baño.jpeg" width="40" height="30" title="Baño"/></td>';
									}elseif($Piso1_Array[$i][5]=="" || $Piso1_Array[$i][5]==" "){
										echo '<td><img src="./images/vacio.jpg" width="40" height="20" /></td>';
									}else {?>
									<td>										
										<?php
											for($var=0;$var<count($Estados_Array);$var++){
											if($Estados_Array[$var][1]==$Piso1_Array[$i][5] && $Estados_Array[$var][0]==1){
												$estados="vendidoM";
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][5]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][5];echo $Piso1_Array[$i][5];?></span>
										</div>
										<?php
											}
											if($Estados_Array[$var][1]==$Piso1_Array[$i][5] && $Estados_Array[$var][0]==2){
												$estados="vendidoF";	
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][5]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][5];echo $Piso1_Array[$i][5];?></span>
										</div>
										<?php
											}
											if($Estados_Array[$var][1]==$Piso1_Array[$i][5] && $Estados_Array[$var][0]==8){
												$estado_ofi=$Estados_Array[$var][2];
												$db_transporte->query("SELECT oficina AS ofi FROM oficinas WHERE idoficina='$estado_ofi'");
												$estados=$db_transporte->get("ofi");												
										?>	
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][5]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][5];echo $Piso1_Array[$i][5];?></span>
										</div>	
										<?php
											}
										}	
										if($existe!=$Piso1_Array[$i][5]){
												$estados="libre";																																																								
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_ventas.php?asientos=<?php echo $Piso1_Array[$i][5]?>&p=<?php echo $dir;?>'" >
											<span><?php echo $Piso1_Array[$i][5];?></span>
										</div>
										<?php
											}
										?>
									</td>
								<?php }?>
								<?php if($Piso1_Array[$i][4]=="TI" || $Piso1_Array[$i][4]=="TM" || $Piso1_Array[$i][4]=="TD" || $Piso1_Array[$i][4]=="TV"){?>
									<td><img src="./images/tv.jpeg" width="45" height="25" title="TV." /></td>
									<?php }elseif($Piso1_Array[$i][4]=="ES"){
										echo '<td><img src="./images/esc.jpeg" width="40" height="30" title="Escalera." /></td>';
									}elseif($Piso1_Array[$i][4]=="TR"){
										echo '<td><img src="./images/tripu.jpeg" width="40" height="25" title="Tripulación"/></td>';
									}elseif($Piso1_Array[$i][4]=="BA"){
										echo '<td><img src="./images/baño.jpeg" width="40" height="30" title="Baño"/></td>';
									}elseif($Piso1_Array[$i][4]=="" || $Piso1_Array[$i][4]==" "){
										echo '<td><img src="./images/vacio.jpg" width="40" height="20" /></td>';
									}else {?>
									<td>
										<?php
											for($var=0;$var<count($Estados_Array);$var++){
											if($Estados_Array[$var][1]==$Piso1_Array[$i][4] && $Estados_Array[$var][0]==1){
												$estados="vendidoM";
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][4]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][4];echo $Piso1_Array[$i][4];?></span>
										</div>
										<?php
											}
											if($Estados_Array[$var][1]==$Piso1_Array[$i][4] && $Estados_Array[$var][0]==2){
												$estados="vendidoF";	
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][4]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][4];echo $Piso1_Array[$i][4];?></span>
										</div>
										<?php
											}
											if($Estados_Array[$var][1]==$Piso1_Array[$i][4] && $Estados_Array[$var][0]==8){
												$estado_ofi=$Estados_Array[$var][2];
												$db_transporte->query("SELECT oficina AS ofi FROM oficinas WHERE idoficina='$estado_ofi'");
												$estados=$db_transporte->get("ofi");												
										?>	
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso1_Array[$i][4]?>&p=<?php echo $dir;?>'" >
											<span><?php $existe=$Piso1_Array[$i][4];echo $Piso1_Array[$i][4];?></span>
										</div>
										<?php
											}
										}	
										if($existe!=$Piso1_Array[$i][4]){
												$estados="libre";																																																								
										?>
										<div class="<?php echo $estados;?>" onclick="location.href='p_form_ventas.php?asientos=<?php echo $Piso1_Array[$i][4]?>&p=<?php echo $dir;?>'" >
											<span><?php echo $Piso1_Array[$i][4];?></span>
										</div>
										<?php
											}
										?>										
									</td>
								<?php }?>
							</tr>							
					<?php							
						}								
					?>	
					</table>								
				</div>
				<!--SEGUNDO PISO DE  BUSES- ASIENTOS LIBRES, RESERVADOS Y OCUPADOS-->
				<div id="contenido_derecho">
					<p style="text-align:center;">Segundo Piso</p>
					<table>
					<?php	
						$dir2="2&salida=$id_salidas&origen=$origen&destino=$destino_salida&fecha=$fecha_salida&hora=$hora_salida&ruta=$id_rutas&bus=$bus";								
						$db_transporte->query("SELECT estado,asiento,idoficina,serie_boleto,numero_boleto FROM record_cliente WHERE id_salida='$id_salidas' AND piso='2'");
						$Estados_Array2=$db_transporte->get();
						for($x=0;$x<count($Piso2_Array);$x++){							
					?>														
							<tr>
								<?php if($Piso2_Array[$x][8]=="TI" || $Piso2_Array[$x][8]=="TM" || $Piso2_Array[$x][8]=="TD" || $Piso2_Array[$x][8]=="TV"){?>
									<td><img src="./images/tv.jpeg" width="45" height="25" title="TV." /></td>
									<?php }elseif($Piso2_Array[$x][8]=="ES"){
										echo '<td><img src="./images/esc.jpeg" width="40" height="30" title="Escalera." /></td>';
									}elseif($Piso2_Array[$x][8]=="TR"){
										echo '<td><img src="./images/tripu.jpeg" width="40" height="25" title="Tripulación"/></td>';
									}elseif($Piso2_Array[$x][8]=="BA"){
										echo '<td><img src="./images/baño.jpeg" width="40" height="30" title="Baño"/></td>';
									}elseif($Piso2_Array[$x][8]=="" || $Piso2_Array[$x][8]==" "){
										echo '<td><img src="./images/vacio.jpg" width="40" height="20" /></td>';
									}else {?>
									<td>
										<?php
										for($num=0;$num<count($Estados_Array2);$num++){											
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][8] && $Estados_Array2[$num][0]==1){
												$estado="vendidoM";
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][8]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][8];echo $Piso2_Array[$x][8];?></span>
										</div>
										<?php
											}
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][8] && $Estados_Array2[$num][0]==2){
												$estado="vendidoF";	
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][8]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][8];echo $Piso2_Array[$x][8];?></span>
										</div>
										<?php
											}
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][8] && $Estados_Array2[$num][0]==8){
												$estado_ofi2=$Estados_Array2[$num][2];
												$db_transporte->query("SELECT oficina AS ofi FROM oficinas WHERE idoficina='$estado_ofi2'");
												$estado=$db_transporte->get("ofi");												
										?>	
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][8]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][8];echo $Piso2_Array[$x][8];?></span>
										</div>
										<?php
											}
										}	
										if($existen!=$Piso2_Array[$x][8]){
												$estado="libre";																																																								
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_ventas.php?asientos=<?php echo $Piso2_Array[$x][8]?>&p=<?php echo $dir2;?>'" >
											<span><?php echo $Piso2_Array[$x][8];?></span>
										</div>
										<?php
											}
										?>
									</td>
								<?php }?>
								<?php if($Piso2_Array[$x][7]=="TI" || $Piso2_Array[$x][7]=="TM" || $Piso2_Array[$x][7]=="TD" || $Piso2_Array[$x][7]=="TV"){?>
									<td><img src="./images/tv.jpeg" width="45" height="25" title="TV." /></td>
									<?php }elseif($Piso2_Array[$x][7]=="ES"){
										echo '<td><img src="./images/esc.jpeg" width="40" height="30" title="Escalera." /></td>';
									}elseif($Piso2_Array[$x][7]=="TR"){
										echo '<td><img src="./images/tripu.jpeg" width="40" height="25" title="Tripulación"/></td>';
									}elseif($Piso2_Array[$x][7]=="BA"){
										echo '<td><img src="./images/baño.jpeg" width="40" height="30" title="Baño"/></td>';
									}elseif($Piso2_Array[$x][7]=="" || $Piso2_Array[$x][7]==" "){
										echo '<td><img src="./images/vacio.jpg" width="40" height="20" /></td>';
									}else {?>
									<td>
										<?php
										for($num=0;$num<count($Estados_Array2);$num++){											
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][7] && $Estados_Array2[$num][0]==1){
												$estado="vendidoM";
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][7]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][7];echo $Piso2_Array[$x][7];?></span>
										</div>
										<?php
											}
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][7] && $Estados_Array2[$num][0]==2){
												$estado="vendidoF";	
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][7]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][7];echo $Piso2_Array[$x][7];?></span>
										</div>
										<?php
											}
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][7] && $Estados_Array2[$num][0]==8){
												$estado_ofi2=$Estados_Array2[$num][2];
												$db_transporte->query("SELECT oficina AS ofi FROM oficinas WHERE idoficina='$estado_ofi2'");
												$estado=$db_transporte->get("ofi");												
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][7]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][7];echo $Piso2_Array[$x][7];?></span>
										</div>
										<?php
											}
										}	
										if($existen!=$Piso2_Array[$x][7]){
												$estado="libre";																																																								
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_ventas.php?asientos=<?php echo $Piso2_Array[$x][7]?>&p=<?php echo $dir2;?>'" >
											<span><?php echo $Piso2_Array[$x][7];?></span>
										</div>
										<?php
											}
										?>
									</td>
								<?php }?>
								<?php if($Piso2_Array[$x][6]=="TI" || $Piso2_Array[$x][6]=="TM" || $Piso2_Array[$x][6]=="TD" || $Piso2_Array[$x][6]=="TV"){?>
									<td><img src="./images/tv.jpeg" width="45" height="25" title="TV." /></td>
									<?php }elseif($Piso2_Array[$x][6]=="ES"){
										echo '<td><img src="./images/esc.jpeg" width="40" height="30" title="Escalera." /></td>';
									}elseif($Piso2_Array[$x][6]=="TR"){
										echo '<td><img src="./images/tripu.jpeg" width="40" height="25" title="Tripulación"/></td>';
									}elseif($Piso2_Array[$x][6]=="BA"){
										echo '<td><img src="./images/baño.jpeg" width="40" height="30" title="Baño"/></td>';
									}elseif($Piso2_Array[$x][6]=="" || $Piso2_Array[$x][6]==" "){
										echo '<td><img src="./images/vacio.jpg" width="40" height="20" /></td>';
									}else {?>
									<td>
										<?php
										for($num=0;$num<count($Estados_Array2);$num++){											
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][6] && $Estados_Array2[$num][0]==1){
												$estado="vendidoM";
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][6]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][6];echo $Piso2_Array[$x][6];?></span>
										</div>
										<?php
											}
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][6] && $Estados_Array2[$num][0]==2){
												$estado="vendidoF";	
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][6]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][6];echo $Piso2_Array[$x][6];?></span>
										</div>
										<?php
											}
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][6] && $Estados_Array2[$num][0]==8){
												$estado_ofi2=$Estados_Array2[$num][2];
												$db_transporte->query("SELECT oficina AS ofi FROM oficinas WHERE idoficina='$estado_ofi2'");
												$estado=$db_transporte->get("ofi");												
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][6]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][6];echo $Piso2_Array[$x][6];?></span>
										</div>
										<?php
											}
										}	
										if($existen!=$Piso2_Array[$x][6]){
												$estado="libre";																																																								
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_ventas.php?asientos=<?php echo $Piso2_Array[$x][6]?>&p=<?php echo $dir2;?>'" >
											<span><?php echo $Piso2_Array[$x][6];?></span>
										</div>
										<?php
											}
										?>
									</td>
								<?php }?>
								<?php if($Piso2_Array[$x][5]=="TI" || $Piso2_Array[$x][5]=="TM" || $Piso2_Array[$x][5]=="TD" || $Piso2_Array[$x][5]=="TV"){?>
									<td><img src="./images/tv.jpeg" width="45" height="25" title="TV." /></td>
									<?php }elseif($Piso2_Array[$x][5]=="ES"){
										echo '<td><img src="./images/esc.jpeg" width="40" height="30" title="Escalera." /></td>';
									}elseif($Piso2_Array[$x][5]=="TR"){
										echo '<td><img src="./images/tripu.jpeg" width="40" height="25" title="Tripulación"/></td>';
									}elseif($Piso2_Array[$x][5]=="BA"){
										echo '<td><img src="./images/baño.jpeg" width="40" height="30" title="Baño"/></td>';
									}elseif($Piso2_Array[$x][5]=="" || $Piso2_Array[$x][5]==" "){
										echo '<td><img src="./images/vacio.jpg" width="40" height="20" /></td>';
									}else {?>
									<td>
										<?php
										for($num=0;$num<count($Estados_Array2);$num++){											
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][5] && $Estados_Array2[$num][0]==1){
												$estado="vendidoM";
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][5]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][5];echo $Piso2_Array[$x][5];?></span>
										</div>
										<?php
											}
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][5] && $Estados_Array2[$num][0]==2){
												$estado="vendidoF";	
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][5]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][5];echo $Piso2_Array[$x][5];?></span>
										</div>
										<?php
											}
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][5] && $Estados_Array2[$num][0]==8){
												$estado_ofi2=$Estados_Array2[$num][2];
												$db_transporte->query("SELECT oficina AS ofi FROM oficinas WHERE idoficina='$estado_ofi2'");
												$estado=$db_transporte->get("ofi");												
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][5]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][5];echo $Piso2_Array[$x][5];?></span>
										</div>
										<?php
											}
										}	
										if($existen!=$Piso2_Array[$x][5]){
												$estado="libre";																																																								
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_ventas.php?asientos=<?php echo $Piso2_Array[$x][5]?>&p=<?php echo $dir2;?>'" >
											<span><?php echo $Piso2_Array[$x][5];?></span>
										</div>
										<?php
											}
										?>
									</td>
								<?php }?>
								<?php if($Piso2_Array[$x][4]=="TI" || $Piso2_Array[$x][4]=="TM" || $Piso2_Array[$x][4]=="TD" || $Piso2_Array[$x][4]=="TV"){?>
									<td><img src="./images/tv.jpeg" width="45" height="25" title="TV." /></td>
									<?php }elseif($Piso2_Array[$x][4]=="ES"){
										echo '<td><img src="./images/esc.jpeg" width="40" height="30" title="Escalera." /></td>';
									}elseif($Piso2_Array[$x][4]=="TR"){
										echo '<td><img src="./images/tripu.jpeg" width="40" height="25" title="Tripulación"/></td>';
									}elseif($Piso2_Array[$x][4]=="BA"){
										echo '<td><img src="./images/baño.jpeg" width="40" height="30" title="Baño"/></td>';
									}elseif($Piso2_Array[$x][4]=="" || $Piso2_Array[$x][4]==" "){
										echo '<td><img src="./images/vacio.jpg" width="40" height="20"/></td>';
									}else {?>
									<td>
										<?php
										for($num=0;$num<count($Estados_Array2);$num++){											
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][4] && $Estados_Array2[$num][0]==1){
												$estado="vendidoM";
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][4]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][4];echo $Piso2_Array[$x][4];?></span>
										</div>
										<?php
											}
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][4] && $Estados_Array2[$num][0]==2){
												$estado="vendidoF";	
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][4]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][4];echo $Piso2_Array[$x][4];?></span>
										</div>
										<?php
											}
											if($Estados_Array2[$num][1]==$Piso2_Array[$x][4] && $Estados_Array2[$num][0]==8){
												$estado_ofi2=$Estados_Array2[$num][2];
												$db_transporte->query("SELECT oficina AS ofi FROM oficinas WHERE idoficina='$estado_ofi2'");
												$estado=$db_transporte->get("ofi");												
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_venta_detalle.php?asientos=<?php echo $Piso2_Array[$x][4]?>&p=<?php echo $dir2;?>'" >
											<span><?php $existen=$Piso2_Array[$x][4];echo $Piso2_Array[$x][4];?></span>
										</div>
										<?php
											}
										}	
										if($existen!=$Piso2_Array[$x][4]){
												$estado="libre";																																																								
										?>
										<div class="<?php echo $estado;?>" onclick="location.href='p_form_ventas.php?asientos=<?php echo $Piso2_Array[$x][4]?>&p=<?php echo $dir2;?>'" >
											<span><?php echo $Piso2_Array[$x][4];?></span>
										</div>
										<?php
											}
										?>
									</td>
								<?php }?>
							</tr>
					<?php
						}
					?>
					</table>
				</div>
			</div>
			<p></p>
			<p style="text-align:center;"><input class="button" type="button" name="btn_regresar" id="btn_regresar" value="Regresar" onclick="location.href='p_pasajes.php'" style="width:170px;" ></p>
		<?php
			}
		?>	
	<?php if(!isset($_GET['asientos_bus'])){?>
	  <h1>Buscar Salidas- <span>Selecione la Fecha y Oficina a buscar</span></h1>
	  <?php echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; ?>
		<!--<legend>Nuevo Registro</legend>-->
		<div class='column1-unit'>
				<div class='contactform'>
				<form name="buscar_copiar_salida_form" method='post' id="buscar_copiar_salida_form" action='p_pasajes.php?buscar'>
					<table>
						<tr>
							<th><span>*</span>Oficina: </th>
								<td>
								<!--<select name="cmb_agencia_origen" class="combo" tabindex="1" onkeypress="return handleEnter(this, event)" title="Ruta de Destino." style="font-size:13px; font-weight:600;" onchange="Get_Oficinas_Numeracion_Derivado('E_DERIVADO');">-->
								<select name="cmb_agencia" id="cmb_agencia" class="combo" title="Agencia de origen." tabindex="1" onkeypress="return handleEnter(this, event)" style="font-size:13px; font-weight:600;" >
								  	<?php
										if (count($Oficina_Array) == 0)
										{
											echo '<option value="">[ NO HAY OFICINAS...! ]</option>';
										}
										else
										{
											echo '<option value="" selected="selected">[ TODAS LAS OFICINAS ]</option>';
											for ($fila = 0; $fila < count($Oficina_Array); $fila++)
											{
												if(isset($_SESSION['ID_OFICINA']) && $_SESSION['ID_OFICINA'] == $Oficina_Array[$fila][0] && !isset($_POST['buscar']))
													echo '<option selected="selected" value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
												elseif (isset($_POST['buscar']) && $_POST['cmb_agencia'] == $Oficina_Array[$fila][0])
													echo '<option selected="selected" value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
												else
													echo '<option value="'.$Oficina_Array[$fila][0].'"> '.$Oficina_Array[$fila][1].' </option>';
											}
										}
									?>										
								</select>				
								</td>
							<td colspan="2" style="text-align:right;">									
								<input id='buscar' type='text' name='buscar' value="<?php if(!isset($_POST['buscar'])) echo date("Y-m-d"); else echo $_POST['buscar']; ?>" title="Buscar Salida por Fecha." tabindex="1" style="width:150px; text-align:center; font-size:120%; font-weight:bold;text-transform:uppercase;">
								<input type="button" value="Cal" class="button" onClick="displayCalendar(document.forms[0].buscar,'yyyy-mm-dd',this)" style="width:54px;" onkeypress="return handleEnter(this, event)" >
								<input name="btn_Buscar" id="btn_Buscard" type="submit" class="button" value="Buscar" tabindex="8"  />
							</td>
						  </tr>								  						
					</table>
				</form>									
			</div>
		</div>		
	</div>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
 	<!-- Contenido de las consultas-->
	<div class="column1-unit">
	  	<!-- MOSTRAMOS EL RESULTADO DE LA BUSQUEDA -->
	    <?php
	    //if(isset($_GET['buscar'] )){
			if (count ($Trans_Array) > 0)
			{
				echo '<h1>Registro de Salidas</h1>';
				echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>';
				echo '<table width="100%" border="0">';
					echo '<tr>';
						echo '<th title="Fecha">Fecha</th>';
						echo '<th title="Hora de salida">Hora</th>';
						//echo '<th title="Oficina de salida">Origen</th>';
						echo '<th title="Destino">Destino</th>';
						echo '<th title="Flota de Bus">Flota</th>';
						echo '<th title="Marca de Bus">Marca</th>';
						echo '<th title="Vender pasajes" style="width:50px; text-align:center;">Vender.</th>';
						//echo '<th title="Eliminar Valores">Delete.</th>';
					echo '</tr>';
				for ($fila = 0; $fila < count($Trans_Array); $fila++)
				{
					$fecha= utf8_encode($Trans_Array[$fila][0]);
					$destino = utf8_encode($Trans_Array[$fila][1]);
					$hora =$Trans_Array[$fila][2];
					$oficina = $Trans_Array[$fila][3];
					$flota = $Trans_Array[$fila][4];
					$marca = $Trans_Array[$fila][5];
					$id_salida=$Trans_Array[$fila][6];
					$idbus=$Trans_Array[$fila][8];
					$id_ruta=$Trans_Array[$fila][9];
					echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
						echo "<td>$fecha</td>";
						echo "<td>$hora</td>";
						//echo "<td>$oficina</td>";
						echo "<td>$destino</td>";
						echo "<td>$flota</td>";
						echo "<td>$marca</td>";
						//echo "<td>$id_ruta</td>";
						//////////////////////////////////////////////////// envia los valores id_bus y id_salida en p_pasajes.php?////////////////////////////////
						echo '<td style="text-align:center;">
								<a href="p_pasajes.php?asientos_bus='.$idbus.'&origen='.$oficina.'&salida='.$id_salida.'&fecha='.$fecha.'&destino='.$destino.'&hora='.$hora.'&ruta='.$id_ruta.'" >
									<img src="./images/Symbol-Update.png" width="24" height="24" title="Modificar." /><!--[if IE 7]/><!-->
								</a><!--<![endif]-->
							  </td>';
						//echo '<td style="text-align:center;"><a href="p_salida_action.php?delete='.$id_salida.'" onclick="return confirmDelete(this);"><img src="./img/operacion/Symbol-Delete.png" width="24" height="24" title="Eliminar." /><!--[if IE 7]/><!--></a><!--<![endif]--></td>';
					echo "</tr>";
				}
					echo '<div class="paginacion">';
					echo '<tr>';
						$url = 'p_copiar_salida.php?';
						$back = "&laquo;Atras";
						$next = "Siguiente&raquo;";
						echo '<th colspan="6" style="text-align:center;">';
						$paginacion->generaPaginacion($totalRows, $back, $next, $url);
						echo '</th>';
					echo '</tr>';
					echo '</div>';				
				echo '</table>';
			}
			else{
				echo '<h1>No existen salidas registradas</h1>';
				echo '<h3>'.date("l j \d\e F, Y, h:i A").'</h3>'; 
			}
		?>
	</div>
	<?php }?>
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
    <div id="div_error">
    </div>
<?PHP
	}
	elseif (isset($_GET['ID']))
	{
?>	
	<!-- Limpiar Unidad del Contenido -->
	<hr class="clear-contentunit" />
<?PHP		
	// MOSTRAMOS EL MENSAJE DE ERROR
	echo '<!-- Content unit - One column -->';
	echo '<div class="column1-unit">';
		echo '<h1>Error con la Operación</h1>';
		echo '<p>'.$MsjError.'</p>';
	echo '</div>';
	echo '<!-- Limpiar Unidad del Contenido -->';
	echo '<hr class="clear-contentunit" />';	
	}
 ?>	
</div>