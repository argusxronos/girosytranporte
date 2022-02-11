<?php
	$id = $_GET['ID'];
	$cont = $_GET['cont'];
	echo '<form name="frm_giro_anulado_'.$id.'" class="">

<input name="txt_monto_'.$id.'" tabindex="11" type="text" style="width:50px;color:red;" onkeypress="return handleEnter(this,event);" onkeyup="extractNumber(this,2,false); 
document.frm_giro_anulado_'.$id.'.txt_flete_'.$id.'.value = Math.round((this.value * 0.10)*100)/100;" /> 
- <input name="txt_flete_'.$id.'" tabindex="12" type="text" onkeyup="extractNumber(this,2,false);" style="width:50px;" onfocus="this.select();" onkeypress="Desanular_Giro_Anulado(this, event, '.$id.', document.frm_giro_anulado_'.$id.'.txt_monto_'.$id.'.value, document.frm_giro_anulado_'.$id.'.txt_flete_'.$id.'.value, '.$cont.');" />

</form>';
?>

