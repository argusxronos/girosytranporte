<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
	<link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/guia_remision.css" />
	<!-- Icono -->
	<link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
    <title>.::GUIA REMISI�N::.</title>
	<!--  ESCRIPT PARA ORDENAR LA IMPRESION EN CUANTO CARGE LA PAGINA -->
	<script language="JavaScript">
		function imprimir()
		{
			window.print();
			
			/*if (navigator.appName=="Netscape"){
			
				{   
			}*/
			if (navigator.appName == "Microsoft Internet Explorer")
			{
				window.onfocus = function() 
				{
					window.open('','_parent','');
					window.close(); 
				}
			}
			else
			{
				window.onfocus = function() 
				{
				window.open('', '_self', '');
				window.close();
				}
			}
		}
	</script>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>.::TC Impresi&oacute;n Boleta::.</title>
    <!-- Script para validar el navegador -->
    <script language="javascript" src="js/navegador.js"> 
    </script>
</head>

<body onload="imprimir();" style="font-family:arial,verdana,sans-serif;"><!--  -->
<!--[if (IE 7) | (IE 8)]>
  <div class="buttom_doc">
    <table width="100%" border="0">
          <tr>
              <td width="14%">&nbsp;</td>
              <td width="11%">&nbsp;</td>
              <td width="11%">&nbsp;</td>
              <td><?php echo $us_nombre ?></td>
          </tr>
      </table>
  </div>
  <div class="content">
    
    <table width="100%" border="0">
      <tr>
      <th height="80" colspan="5">&nbsp;</th>
      </tr>
        <tr>
      <td height="26" colspan="3" style="padding-left:63px;"><?php echo $fecha;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fecha;?></td>
          <td width="12%">&nbsp;</td>
          <td width="9%">&nbsp;</td>
      </tr>
        <tr>
      <td width="10%" height="26">&nbsp;</td>
          <td colspan="2"><?php echo $nom_completo_remit;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
        <tr>
      <td height="26">&nbsp;</td>
          <td colspan="2"><?php echo $ruc_remit;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
        <tr>
      <td height="26">&nbsp;</td>
          <td colspan="2"><?php echo $nom_agen_origen;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
        <tr>
      <td height="30">&nbsp;</td>
          <td colspan="2">AGENCIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $nom_agen_destino;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
        <tr>
      <td height="26">&nbsp;</td>
          <td colspan="2"><?php echo $nom_completo_consig;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
        <tr>
      <td height="26">&nbsp;</td>
          <td colspan="2"><?php echo $ruc_consig;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
        <tr height="26">
      <td>&nbsp;</td>
          <td width="60%">&nbsp;</td>
          <td width="9%">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
  <?PHP
    echo '<tr  height="26">';
      echo '<td style="padding-left:25px;">&nbsp;</td>';
      echo '<td>&nbsp;</td>';
      echo '<td>FLETE</td>';
      echo '<td>REPARTO</td>';
      echo '<td>TOTAL</td>';
    echo '</tr>';
    for ($fila = 0; $fila < count($Mov_Array_List); $fila++)
    {
      $cantidad = $Mov_Array_List[$fila][0];
      $descripcion = $Mov_Array_List[$fila][1];
      $flete = $Mov_Array_List[$fila][2];
      $carrera = $Mov_Array_List[$fila][3];
      $importe = $Mov_Array_List[$fila][4];
      echo '<tr height="26">';
        echo '<td style="padding-left:25px;">'.$cantidad.'</td>';
        echo '<td>'.$descripcion.'</td>';
        echo '<td>'.$flete.'</td>';
        echo '<td>'.$carrera.'</td>';
        echo '<td>'.$importe.'</td>';
      echo '</tr>';
    }
  ?>
    </table>
  </div>
<![endif]-->
<!--[if FF ]>-->
  <div class="buttom_doc" style="letter-spacing: 0px; font-size:15px; ">
    <table width="100%" border="0">
          <tr>
              <td width="14%">&nbsp;</td>
              <td width="11%">&nbsp;</td>
              <td width="11%">&nbsp;</td>
              <td ><?php echo $us_nombre ?></td>
          </tr>
      </table>
  </div>
  <div class="content" style="letter-spacing: 0px; font-size:15px; ">
    
    <table width="100%" border="0">
      <tr>
            <th height="95" colspan="5">&nbsp;</th>
      </tr>
      <tr style="line-height: 25px;">
            <td height="26" colspan="3" style="padding-left:63px;"><?php echo $fecha;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fecha;?></td>
            <td width="12%">&nbsp;</td>
            <td width="9%">&nbsp;</td>
      </tr>
      <tr style="line-height: 25px;">
         <td width="10%" height="26" >&nbsp;</td>
          <td colspan="2"><?php echo $nom_completo_remit;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
      <tr style="line-height: 25px;">
        <td height="26">&nbsp;</td>
          <td colspan="2"><?php echo $ruc_remit;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
      <tr style="line-height: 25px;">
      <td height="26">&nbsp;</td>
          <td colspan="2"><?php echo $nom_agen_origen;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
      <tr style="line-height: 25px;">
          <td height="30">&nbsp;</td>
          <td colspan="2">AGENCIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $nom_agen_destino;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
      <tr style="line-height: 25px;">
        <td height="26">&nbsp;</td>
          <td colspan="2"><?php echo $nom_completo_consig;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
     <tr style="line-height: 25px;">
      <td height="26">&nbsp;</td>
          <td colspan="2" ><?php echo $ruc_consig;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
        <tr height="26">
          <td>&nbsp;</td>
          <td width="60%">&nbsp;</td>
          <td width="9%">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
  <?PHP
    echo '<tr  height="36" style="padding-top: 20px;">';
      echo '<td style="padding-left:25px; ">&nbsp;</td>';
      echo '<td>&nbsp;</td>';
      echo '<td>FLETE</td>';
      echo '<td>REPARTO</td>';
      echo '<td>TOTAL</td>';
    echo '</tr>';
    for ($fila = 0; $fila < count($Mov_Array_List); $fila++)
    {
      $cantidad = $Mov_Array_List[$fila][0];
      $descripcion = $Mov_Array_List[$fila][1];
      $flete = $Mov_Array_List[$fila][2];
      $carrera = $Mov_Array_List[$fila][3];
      $importe = $Mov_Array_List[$fila][4];
      echo '<tr height="26">';
        echo '<td style="padding-left:25px;">'.$cantidad.'</td>';
        echo '<td>'.$descripcion.'</td>';
        echo '<td>'.$flete.'</td>';
        echo '<td>'.$carrera.'</td>';
        echo '<td>'.$importe.'</td>';
      echo '</tr>'; 
    }
  ?>
    </table>
  </div>

</body>
</html>
