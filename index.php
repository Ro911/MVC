<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// подключение конфига
require 'config.php';


// автозагрузка классов - перебираем каждый каталог, где могут лежать классы, и подключаем, если находим
function __autoload($className)
{
    $file = "{$className}.php";
    if (file_exists(LIB_DIR . $file)) {
        require LIB_DIR . $file;
    } elseif (file_exists(CONTROLLER_DIR . $file)) {
        require CONTROLLER_DIR . $file;
    } elseif (file_exists(MODEL_DIR . $file)) {
        require MODEL_DIR . $file;
    } else {
        throw new Exception("{$file} not found", 404);
    }
}

Session::start();


try {
    Router::parse($_SERVER['REQUEST_URI']);

    $request = new Request();

    $_controller = Router::getController();
    $_action = Router::getAction();

    // создаем экземпляр контроллера
    $_controller = new $_controller;

    // вызываем действие для обработки запроса - оно сгенерирует динамический контент
    if (!method_exists($_controller, $_action)) {
        throw new Exception("{$_action} not found", 404);
    }

    $content = $_controller->$_action($request);
} catch (Exception $e) {
    $content = $e->getCode() . " : " . $e->getMessage();
}




require VIEW_DIR . 'layout.phtml';