<?php
$dsn = 'mysql:dbname=woofi7_MinefieldCrafting;host=webd5.monhebergeur.net';
$user = 'woofi7_admin';
$password = '***REMOVED***';

try {
    $sql = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}

date_default_timezone_set('America/Montreal');
?>