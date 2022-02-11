<?php
	// SCRIPT PARA REALIZA VALIDACIONES
	// Fucion para los errores
	function MsjErrores($msj)
	{
		global $Error;
		$Error = true;
		global $MsjError;
		$MsjError = $MsjError .'<br />' .$msj;
	}
	/***********************************************/
	/* SCRIPT PARA OPTENER EL IP REAL DE LA MAKINA */
	/***********************************************/
	function getRealIP() {
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
           $ip = getenv("HTTP_CLIENT_IP");
	   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
			   $ip = getenv("HTTP_X_FORWARDED_FOR");
	   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
			   $ip = getenv("REMOTE_ADDR");
	   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
			   $ip = $_SERVER['REMOTE_ADDR'];
	   else
			   $ip = "IP desconocida";
	   return($ip);
	}
	/**********************************************/
	/* VALIDDACION PARA EL INGRESO DE LOS NOMBRES */
	/**********************************************/
	function ValidacionNombrePersona($string, $nombre)
	{
		// VERIFICAMOS SI SE INGRESO LOS DATOS DEL REMITENTE
		if (strlen($string) == 0)
		{
			MsjErrores('Debe Ingresar el nombre del '.$nombre);
		}
		else
		{
			LimiteCaracteres($string, $nombre, 5, 50);
			StrDuplicidadCaract($string, $nombre);
		}
	}
	function ValicacionDNI ($var_dni)
	{
		
		if (strlen($var_dni) == 0)
			MsjErrores('Debe <span>ingresar D.N.I.</span> de consignatario.');
		else
		{
			esNumerico($var_dni, 'D.N.I.');
			LimiteCaracteres($var_dni, 'D.N.I.', 8, 8);
		}
	}
	
	
	
	function LimiteCaracteres ($cadena, $nombre, $limitInf, $limitSup)
	{
		if (strlen($cadena) < $limitInf)
		{
			MsjErrores($nombre .' debe tener como m&iacute;nimo ' .$limitInf .' carateres.');
		}
		MaxCaracteres ($cadena, $nombre, $limitSup);
	}
	function MaxCaracteres ($cadena, $nombre, $limitSup)
	{
		if (strlen($cadena) > $limitSup)
		{
			MsjErrores($nombre .' debe tener como m&aacute;ximo ' .$limitSup .' carateres.');
		}
	}
	function MinCaracteres ($cadena, $nombre, $limit)
	{
		if (strlen($cadena) < $limit)
		{
			MsjErrores($nombre .' debe tener como m&iacute;nimo ' .$limit .' carateres.');
		}
	}
	function esNumerico($num, $nombre)
	{
		if (!is_numeric($num))
		{
			MsjErrores($nombre .' debe ser numerico.');
		}
	}
	function StrDuplicidadCaract($string, $nombre)
	{
		$cont = 0;
		for ($x = 1; $x < strlen($string); $x++)
		{
			
		}
	}
	// validamos la fecha
	
	function isDate($i_sDate, $nombre)
	{
	  $blnValid = TRUE;
	   // check the format first (may not be necessary as we use checkdate() below)
	   if(!ereg ("^[0-9]{2}/[0-9]{2}/[0-9]{4}$", $i_sDate))
	   {
		$blnValid = FALSE;
	   }
	   else //format is okay, check that days, months, years are okay
	   {
		  $arrDate = explode("/", $i_sDate); // break up date by slash
		  $intDay = $arrDate[0];
		  $intMonth = $arrDate[1];
		  $intYear = $arrDate[2];
	 
		  $intIsDate = checkdate($intMonth, $intDay, $intYear);
	   
		 if(!$intIsDate)
		 {
		 	MsjErrores($nombre .' debe ser un fecha valida.');
			$blnValid = FALSE;
		 }
	 
	   }//end else
	   
	   return ($blnValid);
	} //end function isDate
	
	
	// FUNCION PARA QUITAR LOS DOBLES ESPACIOS ENTRE LAS PALABRAS
	function quitar_espacios_dobles($cadena)
	{
		$limpia    = '';
		$parts    = array();
		// dividir la cadena con todos los espacios que exista
		$parts = split(' ',$cadena);
		foreach($parts as $subcadena)
		{
			// de cada subcadena elimino sus espacios a los lados
			$subcadena = trim($subcadena);
			// Unimos con un espacio para rearmar la cadena pero omitiendo los que sean espacio en blanco
			if($subcadena!='')
			{
				$limpia .= $subcadena.' ';
			}
		}
		$limpia = trim($limpia);
		return $limpia;
	}
	
	function right($value, $count){
		return substr ($value, ($count*-1));
	}
	 
	function left($string, $count){
		return substr ($string, 0, $count);
	}
?>