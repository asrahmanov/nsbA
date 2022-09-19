<?php
ini_set('session.gc_maxlifetime', 172800);
ini_set('session.cookie_lifetime', 172800);
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

ini_set('memory_limit', '1024M');
ini_set('upload_max_size', '256M');
ini_set('post_max_size', '256M');
ini_set('max_execution_time', '300');







//phpinfo();
use app\engine\App;


include __DIR__ . '/../vendor/autoload.php';
$config = include __DIR__ .'/../config/config.php';


//$result = password_hash("nbsFrAdmin2020", PASSWORD_DEFAULT);
//var_dump($result);
try {

App::call()->run($config);


} catch (\Exception $error){
    echo "<pre>";
    var_dump($error);
    echo "</pre>";
};

