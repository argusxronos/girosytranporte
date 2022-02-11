<?php 
/*VERIFICAMOS EL LOGEO*/
session_start(); 	
require_once("../is_logged.php");
/*LIBRERIA*/
require_once("../function/validacion.php");
/*CONEXION */
require_once('../config_giro.php');
$db_giro_d = new DB($config);
$db_insertar_temp = new DB($config);
/*LIMPIAMOS LOS DETALLES*/
$db_giro->query("truncate table temp_mov_detalle;");
/*OBTENEMOS VARIABLES*/
$documento=$_GET['documento'];
$idUsuario = $_SESSION['ID_USUARIO'];
$idOficina = $_SESSION['ID_OFICINA'];
$codigo = $_GET['CODIGO'];
$eCodigo = "NULL";
date_default_timezone_set('America/Lima');
$fechaActual = new DateTime(date("Y-m-d"));
/*VALIDACIONES*/
if(isset($_GET['ECODIGO']) && strlen($_GET['ECODIGO']) > 0)
  $eCodigo = $_GET['ECODIGO'];  
if(isset($_GET['serie']) && strlen($_GET['serie']) > 0 && isset($_GET['numero']) && strlen($_GET['numero']) > 0 )
{
  $serie=$_GET['serie'];
  $numero=$_GET['numero']; 
  /*CONSULTAMOS AL SP para obtener datos*/
  $db_giro->query("CALL `USP_E_TRANSBORDO_VOLCADO`($serie,$numero,'$documento');");
  $res = $db_giro->get();
  if ($res[0][0]){
    $db_giro_d->query("CALL `USP_E_TRANSBORDO_DETALLE`(".$res[0][0].");");
    $detalle = $db_giro_d->get();
    }
}
if(count($res)>0){
?>
<table>
  <tr>
    <td colspan="6" style="height:10px;">
      <span style="margin-left:135px;">D.N.I. / R.U.C.</span> 
      <span style="margin-left:100px;">Apellidos y Nombres / Razón Social</span>
    </td>
  </tr>
  <tr>
    <th>Remitente: </th>
    <td colspan="4">
      <input type="hidden" id="txt_remit_hidden" name="txt_remit_ID" value="<?php echo $res[0][8];?>"/>
      <input name="txt_remit_dni" type="text" id="txt_remit_dni" class="input_documento" maxlength="9" readonly value="<?php echo $res[0][2];?>"/> 
      <span>-</span> 
      <input type="text" name="txt_remit" id="txt_remit" class="input_nombres" readonly value="<?php echo utf8_encode($res[0][1]);?>" />
     </td>
  </tr>
  <tr>
    <th>Destinatario: </th>
    <td colspan="4">
      <input type="hidden" id="txt_consig_hidden" name="txt_consig_ID" value="<?php echo $res[0][9];?>"/>
      <input name="txt_consig_dni" type="text" id="txt_consig_dni" class="input_documento" readonly style='width:110px; height:18px;' value="<?php echo $res[0][4];?>"/> 
      <span>-</span> 
      <input type="text" name="txt_consig" id="txt_consig" class="input_nombres" title="Apellidos del Consignatario." readonly value="<?php echo utf8_encode($res[0][3]);?>"/>
    </td>
  </tr>
  <tr>
    <th>Dirección: </th>

    <td colspan="3">
      <input style="text-align:center;" type="text" name="txt_consig_direccion" id="txt_consig_direccion" class="input_direccion" title="Dirección." readonly value="<?php echo utf8_encode($res[0][6]);?>" />
    </td>
    <?php if ($res[0][5]==1) {
    echo "<td colspan='1'>";
           echo "<input type='text' id='carrera_gt' name='carrera_gt' readonly value='CON CARRERA' style='text-align: center; font-weight: bold; background: red; color: white;'/>";
    echo "</td>";}
    else echo "<td colspan='1' style='text-align: center; font-weight: bold; color: white; width: 154px;'>AGENCIA</td>";
    ?>
  
  </tr>

</table>
<div id="Div_List_Items"> 
  <table width="725" border="0">
  <tr>
    <th style="text-align:center; width:85px;">CANTIDAD</th>
    <th style="text-align:center; width: 395px;">DESCRIPCIÓN</th>
    <th style="text-align:center; width: 41px;">FLETE</th>
    <th style="text-align:center;" id="prueba" >CARRERA</th>
    <th style="text-align:center;"> TOTAL</th>
  </tr>
    <?php 
    $total=0;
    $carreraTotal=0;
    $carrera=0;

    for($i=0; $i<count($detalle); $i++){
    ?>
            <tr>
              <td><?php echo $detalle[$i][0];?></td>
              <td><?php echo utf8_encode($detalle[$i][1]);?></td>
              <td id=<?php echo "fleteT_".$i; ?> ><?php echo $detalle[$i][3];?></td>
              
              <td id=<?php echo "carrera_".$i; ?> >
                <a onClick="Edit_Carrera(event, <?php echo $i; ?>);">
                  <?php echo number_format($carrera,2);?>
                </a>
              </td>
              <td> <?php echo number_format($carrera+$detalle[$i][3],2);?> </td> 
              <input type="hidden" id=<?php echo "totalGT_".$i; ?> value="<?php echo $detalle[$i][3]; ?>" />
            </tr>
    <?php      
      $total+=$detalle[$i][4];   
      $carreraTotal+=$detalle[$i][3]; 
      $valorFlete = $detalle[$i][3];
      $cantidad=$detalle[$i][0];
      $descripcion = utf8_decode(strtoupper(urldecode(trim(quitar_espacios_dobles($detalle[$i][1])))));
      $db_insertar_temp->query("CALL `USP_E_INSERT_TEMP`
						(
							@vERROR
							, @vMSJ_ERROR
							, $idUsuario
							, $idOficina
							, '$codigo'
							, '$documento'
							, $eCodigo
							, $cantidad
							, 'NULL'
							, '$descripcion'
							, 'NULL'
							, $valorFlete
							, $carrera
							, $valorFlete
							, '".$fechaActual->format("Y-m-d")."'
						);");
    }  ?>
 
  <tr>
    <th colspan="4" style="text-align:right;"><span>Total S/.</span></th>
    <th style="text-align:right;"><span><?php echo number_format($carreraTotal,2);?></span></th>
  </tr>
  </table>
</div>  
<?php }
else{ 
  echo '
<table>
  <tr>
    <td colspan="6" style="height:10px;text-align: center; ">';
     if(isset($res)){ echo '<span style="font-size:20px; color: green;">NO HAY REGISTROS</span>';}
     else{ echo '<span style="font-size:20px; color: green;">Compruebe que ha ingresado Serie y Número</span>';}
  echo ' 
    </td>
  </tr>
</table>';
} ?>
