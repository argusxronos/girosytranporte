<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8">
    <title>.::TC-Boleto::.</title>
    <!-- Hoja de Estilos -->
    <link rel="stylesheet" type="text/css" media="screen,projection,print" href="./css/boleto.css" />
    <!-- Icono -->
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico" />
    <!--  SCRIPT PARA ORDENAR LA IMPRESION EN CUANTO CARGE LA PAGINA -->
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
</head>

<body>

<?php echo '<!--[if (IE 7) | (IE 8)]>'; ?>
    <div class="content">
        
        <table border="0" class="left">
          <tr>
            <td height="20" colspan="3" style="padding-left:720px;padding-top:25px;">13 - 12 - 2012</td>
          </tr>
          <tr>
          	<td height="26" colspan="2" style="padding-left:0px;padding-top:20px;">USUARIO JONATAN RIVERA C</td>
            <td style="text-align:right;padding-right:150px;padding-top:20px;">10443249554</td>
          </tr>
          <tr>
          	<td height="26" colspan="2" style="padding-left:0px;">PASAJERO JASON RIVERA C</td>
            <td style="text-align:right;padding-right:180px;">44324955</td>
          </tr>
          <tr>
          	<td height="26" style="padding-left:10px;padding-top:25px;">14-12-2012</td>
            <td style="padding-left:250px;padding-top:25px;">08:00:00 PM</td>
            <td></td>
          </tr>
          <tr>
          	<td height="26" style="padding-left:0px;">HUANUCO TARAPACA</td>
            <td style="padding-left:250px;">56 - 1</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
          	<td height="26" style="padding-left:25px;">PUCALLPA</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
          	<td height="26" style="padding-left:70px;padding-top:15px;" >S/. 46.00</td>
            <td>180 - 165486</td>
            <td></td>
          </tr>
          <tr>
          	<td height="26" colspan="2" style="padding-left:10px;">CUARENTA Y SEIS CON 00/100 NUEVOS SOLES</td>
            <td></td>
          </tr>
          <tr>
          	<td></td>
            <td></td>
            <td style="text-align:center; padding-right:100px;">JRIVEAR <br /> 08:52:00</td>
          </tr>
          
          <tr>
          	<td></td>
            <td></td>
            <td></td>
          </tr>
        </table>
        <table class="right">
          <tr>
          	<td height="26" style="padding-top:70px;padding-left:40px;">01/12/2012</td>
          </tr>
          <tr>
          	<td height="26" style="padding-top:10px;">HUANUCO TARAPACA</td>
          </tr>
          <tr>
          	<td height="26" style="padding-left:13px;">PUCALLPA</td>
          </tr>
          <tr>
          	<td height="26"  style="padding-left:30px;">14-12-2012</td>
          </tr>
          <tr>
          	<td height="26" style="padding-top:20px;">08:00:00 PM</td>
          </tr>
          <tr>
          	<td height="26" style="padding-top:20px;">56 - 1</td>
          </tr>
          <tr>
          	<td height="26" style="padding-top:20px;">S/. 46.00</td>
          </tr>
        </table>
    </div>
<?php echo '<![endif]-->'; ?>
<?php echo '<!--[if FF ]>-->'; ?>
	<div class="content_mozilla">
        
        <table border="0" class="left">
          <tr>
            <td colspan="3" style="padding-left:200px;padding-top:0px;">13 - 12 - 2012</td>
          </tr>
          <tr>
          	<td height="26" colspan="2" style="padding-left:0px;padding-top:20px;">USUARIO JONATAN RIVERA C</td>
            <td style="text-align:right;padding-top:20px;">10443249554</td>
          </tr>
          <tr>
          	<td colspan="2" style="padding-left:0px;">PASAJERO JASON RIVERA C</td>
            <td style="text-align:right;">44324955</td>
          </tr>
          <tr>
          	<td style="padding-left:10px;padding-top:0px;">14-12-2012</td>
            <td style="padding-left:250px;padding-top:0px;">08:00:00 PM</td>
            <td></td>
          </tr>
          <tr>
          	<td style="padding-left:0px;">HUANUCO TARAPACA</td>
            <td style="padding-left:250px;">56 - 1</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
          	<td style="padding-left:25px;">PUCALLPA</td>
            <td></td>
            <td></td>
          </tr>
          <tr>
          	<td style="padding-left:70px;padding-top:0px;" >S/. 46.00</td>
            <td>180 - 165486</td>
            <td></td>
          </tr>
          <tr>
          	<td colspan="2" style="padding-left:10px;">CUARENTA Y SEIS CON 00/100 NUEVOS SOLES</td>
            <td></td>
          </tr>
          <tr>
          	<td></td>
            <td></td>
            <td style="text-align:center;">JRIVEAR <br /> 08:52:00</td>
          </tr>
          
          <tr>
          	<td></td>
            <td></td>
            <td></td>
          </tr>
        </table>
        <table class="right">
          <tr>
          	<td style="padding-top:20px;padding-left:40px;">01/12/2012</td>
          </tr>
          <tr>
          	<td style="padding-top:0px;">HUANUCO TARAPACA</td>
          </tr>
          <tr>
          	<td style="padding-left:13px;">PUCALLPA</td>
          </tr>
          <tr>
          	<td style="padding-left:30px;">14-12-2012</td>
          </tr>
          <tr>
          	<td style="padding-top:0px;">08:00:00 PM</td>
          </tr>
          <tr>
          	<td style="padding-top:0px;">56 - 1</td>
          </tr>
          <tr>
          	<td style="padding-top:0px;">S/. 46.00</td>
          </tr>
        </table>
    </div>
<?php echo '<!--[end if]-->'; ?>
</body>
</html>
