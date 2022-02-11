<?php
	if(isset($_SESSION['LAST_SESSION']) && $_SESSION['LAST_SESSION'] != '0')
	{
?>
<div class="header-bottom">
	<div class="nav2">
	  <ul>
      <li><a href="index.php">Inicio</a></li>
	  </ul>
<?php
	  if(
		  isset($_SESSION['TIPO_USUARIO'])
		  && ($_SESSION['TIPO_USUARIO'] == 1
		  || $_SESSION['TIPO_USUARIO'] == 3
		  || $_SESSION['TIPO_USUARIO'] == 9
		  || $_SESSION['TIPO_USUARIO'] == 5)
	  )
	  {
?>	  
	  <ul>
      <li><a href="#">Giros</a>
		<ul>
<?php
			  if(
				isset($_SESSION['TIPO_USUARIO'])
				&& ($_SESSION['TIPO_USUARIO'] == 1
				|| $_SESSION['TIPO_USUARIO'] == 9
				|| $_SESSION['TIPO_USUARIO'] == 5)
			  )
			  {
?>
			  <li><a href="g_envio.php">Enviar Giro</a></li>
			  <li><a href="g_anulacion.php">Anular Giro</a></li>
			  <li><a href="g_entrega.php">Pagar Giro</a></li>
        <li><a href="g_modificar_destino.php">Cambiar Destino</a></li>
<?php
			  }
			  if(isset($_SESSION['TIPO_USUARIO']) 
			  && ($_SESSION['TIPO_USUARIO'] == 1  
			  || $_SESSION['TIPO_USUARIO'] == 3 
			  || $_SESSION['TIPO_USUARIO'] == 9
			  || $_SESSION['TIPO_USUARIO'] == 5))
			  {
?>
        <li><a href="g_derivar.php">Giro otra Agencia</a></li>
<?php
			  }
			  if(isset($_SESSION['TIPO_USUARIO']) 
			  && ($_SESSION['TIPO_USUARIO'] == 1 
			  || $_SESSION['TIPO_USUARIO'] == 9
			  || $_SESSION['TIPO_USUARIO'] == 5))
			  {
?>
			  <li><a href="#" style="color:#F00">----- CONSULTAS -----</a></li>
			  <li><a href="rpt_g_emitidos.php">Giros Emitidos</a></li>
        <li><a href="rpt_g_cancelados.php">Giros Cancelados</a></li>
<?php
			  }
			  if(isset($_SESSION['TIPO_USUARIO']) && ($_SESSION['TIPO_USUARIO'] == 2 || $_SESSION['TIPO_USUARIO'] == 9 || $_SESSION['TIPO_USUARIO'] == 3))
			  {
?>
				  <li><a href="#" style="color:#F00">-- ADMNISTRACIÓN </a></li>
				  <li><a href="g_autorizar.php">Autorizar Giro</a></li>
          <li><a href="cronjob/g_desautorizar.php" target="_blank">Desautorizar Giros</a></li>
          <li><a href="g_modificar.php">Modificar Giro</a></li>
          <li><a href="g_cambiar_consig.php">Modificar Consig.</a></li>
          <li><a href="g_cambiar_clave.php">Modificar Clave.</a></li>
          <li></li>
          <li><a href="g_verificar_emitidos.php">Verifcar Giros Emitidos</a> </li>
          <li><a href="g_verificar_pagados.php">Verifcar Giros Pagados</a> </li>
          <li><a href="#" style="color:#F00">------ REPORTES ------</a></li>
          <li><a href="rpt_g_giros_emitidos.php">Giros Emitidos</a></li>
          <li><a href="rpt_g_giros_entregados.php">Giros Cancelados</a></li>
          <li><a href="rpt_g_giros_recibidos.php">Giros Recibidos</a></li>
<?php 
				  if(isset($_SESSION['TIPO_USUARIO']) && ($_SESSION['TIPO_USUARIO'] == 9 || $_SESSION['TIPO_USUARIO'] == 3))
				  {
					   echo '<li><a href="rpt_g_giros_resumen.php">Resumen de Giros</a></li>';
				  }

			  }
?>
			</ul>
		</li>
	  </ul>
<?php
	}
	if($_SESSION['TIPO_USUARIO'] == 9
	|| $_SESSION['TIPO_USUARIO'] == 4
	|| $_SESSION['TIPO_USUARIO'] == 5)
	{
?>
	  <ul>
		<li><a href="#">Encomiendas</a>
			<ul>
<?php
			  if(isset($_SESSION['TIPO_USUARIO']) 
			  && ($_SESSION['TIPO_USUARIO'] == 9
			  || $_SESSION['TIPO_USUARIO'] == 4
        || $_SESSION['TIPO_USUARIO'] == 5))
			  {
?>
			  	<li><a style="text-align:center"><span>------- Envio -------</span></a></li>
          <li><a href="e_envio.php">Enviar Encomienda</a></li>
          <li><a href="e_anulacion.php">Anular Encomienda</a></li>
          <li><a href="e_liquidacion.php">Liquidación</a></li>
          <li><a href="e_cancelar.php">Cancelar Envio</a></li>
          <li><a href="e_modificar_destino.php" title="Cambiar el destino de una Encomienda.">Camb. Destino</a></li>
          <li><a href="e_derivada.php" title="Registrar Encomiendas enviadas a esta agencia.">Enc. otra Agencia</a></li>
          <li><a style="text-align:center"><span>----- Recepción -----</span></a></li>
          <li><a href="e_recepcion.php" title="Recepcionar Encomiendas.">Recepcionar Enc.</a></li>
          <li><a style="text-align:center"><span>----- Entrega -----</span></a></li>
          <li><a href="e_entrega.php" title="Entregar la encomienda al consignatario.">Entregar Encomienda</a></li>
          <li><a style="text-align:center"><span>----- Consultas -----</span></a></li>
          <li><a href="rpt_e_registradas.php" title="Encomiendas Entregadas en esta Agencia.">Enc. Registradas</a></li>
          <li><a href="rpt_e_entregadas.php" title="Encomiendas entregadas en esta Agencia.">Enc. Entregadas</a></li>
          <li><a href="rpt_e_liquidacion.php" title="Encomiendas entregadas en esta Agencia.">Liquidaciones</a></li>
          <li><a href="esq_e_seguimiento.php" title="Tracking informativo.">Seguimiento</a></li>
          <li><a style="text-align:center"><span>----- Reportes -----</span></a></li>
          <li><a href="rpt_e_recepcionadas.php" title="Encomiendas entregadas en esta Agencia.">Enc. Recepcionadas</a></li>
          <li><a href="rpt_e_ventas.php" title="Encomiendas vendidas">Reg. Ventas</a></li>
<?php
			  }
?>
            </ul>
        </li>
       </ul>
<?PHP
	}
?>    
       <ul>
		<li><a href="#">Mantenimento</a>
			<ul>
<?php
			  if(isset($_SESSION['TIPO_USUARIO']) 
			  && ($_SESSION['TIPO_USUARIO'] == 9			  
				|| $_SESSION['TIPO_USUARIO'] == 5))
			  {
				  //echo $_SESSION['TIPO_USUARIO'];
?>
		<li><a style="text-align:center"><span>Tablas Maestras</span></a></li>
          <li><a href="p_oficina.php" title="oficinas">Nuevas Oficinas</a></li>
          <li><a href="p_bus.php" title="Buses">Nuevos Buses</a></li>
          <li><a href="p_ruta.php" title="rutas">Nuevas Rutas</a></li>          
          <li><a href="p_subruta.php" title="sub-rutas">Nuevas Sub-Rutas</a></li>
          <li><a href="p_salida.php" title="salidas">Nuevas Salidas</a></li>
          <li><a href="p_copiar_salida.php" title="Copiar salidas">Salidas Automaticas</a></li>
          <li><a href="p_tripulacion.php" title="tripulantes">Nuevos Tripulantes</a></li>
          <li><a href="p_numeracion.php" title="numeracion">Numeración</a></li>                            
          <li><a href="p_pasajes.php">Pasajes Diarias Pasajes</a></li>                      
          <!--<li><a style="text-align:center"><span>----- Buses -----</span></a></li>-->          
          
<?php
			  }			  
			  if(isset($_SESSION['TIPO_USUARIO']) 
			  && ($_SESSION['TIPO_USUARIO'] == 4			  
				|| $_SESSION['TIPO_USUARIO'] == 1)){
					//echo $_SESSION['TIPO_USUARIO'];
?>
		<li><a style="text-align:center"><span>Tablas Maestras</span></a></li>
			  <li><a href="p_salida.php" title="salidas">Nuevas Salidas</a></li>
			  <li><a href="p_copiar_salida.php" title="Copiar salidas">Salidas Automaticas</a></li>
		      <li><a href="p_clientes.php">Nuevos Clientes</a></li>           
			  <li><a href="p_pasajes.php">Pasajes Diarias Pasajes</a></li>  
<?php
			  }
?>
            </ul>
        </li>
       </ul>
                     


      
	<ul>
		<li><a href="#">Vales</a>
			<ul>				
				<li><a href="v_pasajes_pagados.php">Pasajes Pagados</a></li>
				<li><a href="v_vale.php">Entregar Vale</a></li>
				<li><a href="v_pgrupo.php">Pasajes en Grupo</a></li>
			</ul>
		</li>
	</ul>
	  <ul>
		<li>
			<?PHP
				if(!isset($_SESSION['IS_LOGGED']))
				{
					echo '<a href="log_in.php">Iniciar Sesión</a>';
				}
				else
				{
					echo '<a href="log_out.php?logout"  style="color:#FF0000;">Cerrar Sesión</a>';
					echo '<ul>';
						echo '<li><a href="change_password.php">Cambiar Contraseña</a></li>';
					echo '</ul>';
				}
			?>
		</li>
	  </ul>
      
      	
	</div>
</div>
<?PHP
	}
?>
