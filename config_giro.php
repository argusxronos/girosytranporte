<?php
require 'libs/mysql.class.php';
// A Config array should be setup from a config file with these parameters:
$config = array();

// DATOS SERVIDOR TCENTRAL
/*$config['host'] = '190.41.188.147';
$config['user'] = 'jonatan';
$config['pass'] = 'riveracas0';
$config['db'] = 'bd_giro';
*/
$config['host'] = 'localhost';
$config['user'] = 'jrivera';
$config['pass'] = 'riveracas0';
$config['db'] = 'bd_giro';
// Then simply connect to your DB this way:
$db_giro = new DB($config);
?>

