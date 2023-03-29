<?php

use module\module_controller;

require_once ('module/module_controller.php');

//$config = [];

$al = new module_controller();

echo $al->getPublicResource('test/js/test.js');

echo 'Задаём alias: fileUpload1';
echo "\r\n";
use module\test\test as fileUpload1;

echo 'Задаём alias: fileUpload';
echo "\r\n";
class_alias('module\test\test', 'fileUpload');

echo "\r\n";
echo "\r\n";
echo 'Активация fileUpload1';
echo "\r\n";
new fileUpload1();

echo "\r\n";
echo 'Активация fileUpload';
echo "\r\n";
new \fileUpload();

echo "\r\n";
echo 'Активация \module\test\test';
echo "\r\n";
new \module\test\test();

try
{
    echo $al->getPublicResource('test/js/test1.js');
}
catch (\module\Exception $e)
{
    echo $e->getMessage();
}
echo "\r\n";

//# Автозагрузчик
//autoload(__DIR__);