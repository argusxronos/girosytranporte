<?php
	require 'libs/mysql.class.php';
	// A Config array should be setup from a config file with these parameters:
	$config = array();
	$config2 = array();
	// DATOR SERVIDOR CANADA
	/*$config['host'] = '72.232.204.203';
	$config['user'] = 'ricardo';
	$config['pass'] = 'gian1904';
	$config['db'] = 'bdtransportenuevo';*/
	
	// DATOS SERVIDOR TCENTRAL BD_TRANSPORTENUEVO
	//$config['host'] = '192.168.1.43';
	$config['host'] = 'localhost';
	$config['user'] = 'jrivera';
	$config['pass'] = 'riveracas0';
	//$config['db'] = 'bdtransporte';
	$config['db'] = 'bdtransportenuevo';
	
	// DATOS SERVIDOR LOCAL BD_GIRO 
	$config2['host'] = 'localhost';
	$config2['user'] = 'jrivera';
	$config2['pass'] = 'riveracas0';
	$config2['db'] = 'bd_giro';
	// Then simply connect to your DB this way:
	$db_transporte = new DB($config);
	$db_giro = new DB($config2);
?>
