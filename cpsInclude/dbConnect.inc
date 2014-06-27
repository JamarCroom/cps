<?php
$mysql='localhost';
$dbName = 'mainebhs_bhsDB';
$userNameDb='mainebhs_bhsUser';
$passwordDb='Bhs%20pass%2033!#';
$db = new PDO("mysql:host=$mysql;dbname=$dbName;",$userNameDb,$passwordDb);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
?>