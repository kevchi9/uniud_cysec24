<?php
define('DBSERVER', 'localhost');
define('DBUSERNAME', 'postgres');
define('DBPASSWORD', 'postgres');
define('DBNAME', 'cysec');

$db = pg_connect("host=localhost port=5432 dbname=cysec user=postgres password=postgres");
if($db == false) {
    die("Error: connection error." . pg_connect_error());
}
?>