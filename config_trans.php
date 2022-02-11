<?php
	require 'libs/mysql.class.php';
	// A Config array should be setup from a config file with these parameters:
	$config = array();
	// DATOR SERVIDOR CANADA
	/*$config['host'] = '72.232.204.203';
	$config['user'] = 'ricardo';
	$config['pass'] = 'gian1904';
	$config['db'] = 'bdtransportenuevo';*/
	// DATOS SERVIDOR TCENTRAL
	//$config['host'] = '190.41.147.188';
	$config['host'] = 'localhost';
	$config['user'] = 'jrivera';
	$config['pass'] = 'riveracas0';
	$config['db'] = 'bdtransportenuevo';
	// Then simply connect to your DB this way:
	$db_transporte = new DB($config);
?>
