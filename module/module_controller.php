<?php

namespace module;

class Exception extends \Exception {}

class module_controller {

    private $_config = [];
    private $_dir = '';
    /** Объект класса */
    private static $_object    = null;

    public function __construct($dir = null) {
        $this->_dir = $dir ?? __DIR__;
        $this->_get_config($this->_dir);
        $this->_set_autoload();
//        $this->_config = array_merge($this->_config, require ('config.php'));
//var_dump($this->_config);
    }



    /** Загружает config данные */
    /**
     * @param $dir
     * @return void
     */
    private function _get_config($dir) {
        $result = [];
        $cdir = scandir($dir);
        foreach ($cdir as $v) {
            # Если это системный элемент
            if (in_array($v, [".",".."]))       { continue; }
            # Если есть маркер "удалённого" элемент
            if (mb_substr($v, 0, 3) == '___')   { continue; }
            # Если это не директория
            $_dir_module = $dir . DIRECTORY_SEPARATOR . $v;
            if (!is_dir($_dir_module))          { continue; }
//            # Если нет файла настроек
//            $_file_config = $_dir_module . DIRECTORY_SEPARATOR . 'config.php';
//            if (!file_exists($_file_config))    { continue; }
//            # Загружаем данные
//            $this->_config[$v] = require ($_file_config);
            $this->_config['module\\' . $v] = $v;
        }
    }



    /**
     * Устанавливает автозагрущик обрабатывающий модули
     * @return void
     */
    private function _set_autoload() {
        spl_autoload_register(function ($class) {
echo 'Ищем: ' . $class;
echo "\r\n";
            $arr_class = explode('\\', $class);
            if (count($arr_class) < 3)                         { return false; }
            $group = array_shift($arr_class);
            if ('module' !== $group)                           { return false; }
            $module = $arr_class[0];
            $key = "{$group}\\{$module}";
            if (!key_exists($key, $this->_config))             { return false; }
            $path = implode(DIRECTORY_SEPARATOR, $arr_class);
            require ($this->_dir . DIRECTORY_SEPARATOR . $path . '.php');
        });
    }


    /**
     * Вывод содержимого внешнего ресурса
     * @param $file
     * @return false|void
     * @throws \Exception
     */
    function getPublicResource($file) {
        $arr_file = explode('/', $file);
        if (count($arr_file) < 2)                                    { throw new Exception('Путь к файлу задан неверно: ' . $file, 404); }
        $module = array_shift($arr_file);
        if (!key_exists('module\\' . $module, $this->_config))   { throw new Exception('Модуль ' . $module . ' не найден.', 404); }
        $path = implode(DIRECTORY_SEPARATOR, $arr_file);
        $_file = $this->_dir . DIRECTORY_SEPARATOR . $module .  DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path;
        if (!file_exists($_file))                                    { throw new Exception('Файл ' . $file . ' не найден.', 404);}
        return file_get_contents($_file);
    }

}
