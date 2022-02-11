<?php
	// VARIABLE PARA CONTROLAR LAS SESIONES
	session_start();
	$_SESSION['INTENTOS_SESION'] = $_SESSION['INTENTOS_SESION'] + 1;
	// validacion de numero de intento
	
	// ARCHICO NECESARIO PARA LAS CONSULTAS
	require_once 'cnn/config_trans.php';
	$id_oficina = $_POST['cmb_oficina'];
	$usuario = $_POST['txt_usuario'];
	$password = $_POST['txt_contrasenia'];
	
	$db_transporte->query("SELECT count(`user`.`id_usuario`) as 'T_USERS'
							FROM `tusuario` as `user`
							WHERE `user`.`c_login` = '$usuario' AND  `user`.`idoficina` = '$id_oficina' 
							AND `user`.`c_password_web` = password('$password')
							AND `user`.`c_esta_activo` = 1;");
	$result = $db_transporte->get('T_USERS');
	
	if ($result == 1)
	{
		// CONSULTA PARA OBTENER EL ID DEL USUARIO
		$db_transporte->query("SELECT `user`.`id_usuario` as 'ID_USERS', `user`.`t_usuario` as 'USERS', `user`.`c_tipo_usuario`, CAST(ifnull(`user`.`c_ultima_sesion`,'0' )AS CHAR) as 'WAS_LOGGED' 
								FROM `tusuario` as `user`
								WHERE `user`.`c_login` = '".$usuario."' 
								AND  `user`.`idoficina` = '".$id_oficina."' 
								AND `user`.`c_password_web` = password('".$password."') LIMIT 1;");
		$usuario_array = $db_transporte->get();
		// login them in
		$_SESSION['ID_USUARIO'] = $usuario_array[0][0];
		// OBTENEMOS EL NOMBRE DEL USUARIO
		$_SESSION['USUARIO'] = $usuario_array[0][1];
		// OBTENEMOS EL TIPO DE USUARIO
		$_SESSION['TIPO_USUARIO'] = $usuario_array[0][2];
		$_SESSION['IS_LOGGED'] = 1;
		$_SESSION['ID_OFICINA'] = $id_oficina;
		$_SESSION['LAST_SESSION'] = $usuario_array[0][3];
		// OBTENEMOS EL NOMBRE DE LA OFICINA
		$Oficina_Array = $_SESSION['OFICINAS'];
		if (count($Oficina_Array) > 0)
		{
			for ($fila = 0; $fila < count($Oficina_Array); $fila++)
			{
				if ($_SESSION['ID_OFICINA'] == $Oficina_Array[$fila][0])
				{
					$_SESSION['OFICINA'] = $Oficina_Array[$fila][1];
					break;
				}
			}
		}
		else
		$_SESSION['OFICINA'] = 'Error.';
		// OBTENEMOS LA LISTA DE USUARIOS Y LO ALMACENAMOS EN UN ARRAY
		if (!isset($_SESSION['USERS']))
		{
			$db_transporte->query("SELECT `tusuario`.`id_usuario`, `tusuario`.`c_login`, `tusuario`.`t_usuario`, `tusuario`.`idoficina`
							FROM `tusuario`;");
			$_SESSION['USERS'] = $db_transporte->get();
		}
		/* CODIGO PARA GUARDAR EN UNA VARIABLE SESSION LOS DOCUMETNOS DE ESTA OFICINA */
		/* Eliminado por compatibilidad con IE7
		$db_srv_canada->query("SELECT `nc`.`id`, `nc`.`descripcion_documento`, `nc`.`id_documento`, `nc`.`serie`, `nc`.`id_documento`
								FROM `bdtransportenuevo`.`numeracion_documento` AS `nc`
								WHERE `nc`.`idoficina` = '".$id_oficina."';");
		$_SESSION['DOCUMENTOS'] = $db_srv_canada->get();
		$usuario_array = $_SESSION['DOCUMENTOS'];*/
		// CODIGO PARA REDIRECCIONAR A LA PAGINA DE INICIO LUEGO DEL LOGEO 
		if(isset($_SESSION['LAST_SESSION']) && $_SESSION['LAST_SESSION'] == 0)
		{
			echo "<SCRIPT LANGUAGE='javascript'>
					location.href = 'change_password.php';
			  </SCRIPT>";
			exit;
		}
		else
		{
			echo "<SCRIPT LANGUAGE='javascript'>
						location.href = 'index.php';
				  </SCRIPT>";
			exit;
		}
	}
	else
	{
		// basck to entrance
		echo "<SCRIPT LANGUAGE='javascript'>
					location.href = 'log_in.php?of=$id_oficina&user=$usuario';
					</SCRIPT>";
		exit;
	}
?>
