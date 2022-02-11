<?php
	// SI NO HAY ERRORES, OBTENEMOS LOS DATOS
	require_once '../config_giro.php';
	$db_giro->query("SET SQL_SAFE_UPDATES=0;");
	
	$db_giro->query("UPDATE `g_movimiento` 
					SET `autorizado`= 0 
					WHERE `g_movimiento`.`esta_cancelado` = 0
					AND `g_movimiento`.`esta_anulado` = 0
					AND `g_movimiento`.`autorizado` = 1
					AND DATEDIFF(CURDATE(),`g_movimiento`.`fecha_emision`) >= 30;");
	
	$db_giro->query("SET SQL_SAFE_UPDATES=1;");
?>
<script language="JavaScript">
	if (navigator.appName == "Microsoft Internet Explorer")
	{
		window.open('','_parent','');
		window.close(); 
	}
	else
	{
		window.open('', '_self', '');
		window.close();
	}
</script>