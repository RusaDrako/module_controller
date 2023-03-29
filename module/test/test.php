<?php
namespace module\test;

class test {

    function __construct() {
        echo "\r\n";
        echo 'Йоооо!!!';
        echo "\r\n";
        echo __NAMESPACE__;
        echo "\r\n";
        echo get_class($this);
        echo "\r\n";
    }

}