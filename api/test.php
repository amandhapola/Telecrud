<?php
require 'vendor/autoload.php';
$app = new Slim();
// var_dump($app);
$app->get('/hello/:name',function($name){
     echo "Hello".$name;
});
// echo "hello";
// echo "hello";
$app->run();
?>