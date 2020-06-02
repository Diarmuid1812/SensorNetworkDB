<?php
/** @var $dbLink PDO*/

if(!defined('DB_PARAM_DEFINED'))
{
    define('DB_PARAM_DEFINED',true);
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'sensornetwork');
    define('CHARSET', 'utf8');
    define('ERR_MODE', PDO::ERRMODE_EXCEPTION);
}
try{

    $dbLink = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME.";charset=".CHARSET, DB_USERNAME, DB_PASSWORD);
    /***Set PDO error reporting attribute***/
    $dbLink->setAttribute(PDO::ATTR_ERRMODE, ERR_MODE);
    $dbLink ->exec("SET SQL_SAFE_UPDATES=1");
} catch(PDOException $e){
    throw $e;
    //die("ERROR: Could not connect. " . $e->getMessage());
}