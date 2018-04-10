<?php
require 'class.php';

$mysqlServerIp = "localhost";
$mysqlServerPort = "3306";
$mysqlDbName = "user6";
$mysqlDbCharset = "UTF8";
$mysqlDsn = "mysql:host=".$mysqlServerIp.";port=".$mysqlServerPort.";dbname=".$mysqlDbName.";charset=".$mysqlDbCharset.";";
$myUserDb = 'user6' ;
$myPwdDb = 'user6' ;
$dbCnx = new PDO($mysqlDsn, $myUserDb, $myPwdDb);
?>
