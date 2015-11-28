<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// подключение конфига
require '../config.php';


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
    // парсим урл
    Router::parse($_SERVER['REQUEST_URI']);

    $request = new Request();

    // достаем названия контроллера и екшена
    $_controller = Router::getController();
    $_action = Router::getAction();

    // создаем экземпляр контроллера
    $_controller = new $_controller;

    // вызываем действие для обработки запроса - оно сгенерирует динамический контент
    if (!method_exists($_controller, $_action)) {
        throw new Exception("{$_action} not found", 404);
    }

    // немного изменил метод render.
    $content = $_controller->$_action($request);
} catch (PDOException $e) {
    // TODO: Logger::add($e);

    // не палим БД в сообщении. Стандартные строки с сообщениями лучше определить константамив  конфиге
    // чтоб это проверить - поламайте запрос в БД или подключение
    $content = Controller::renderError(500, 'Internal server error');
} catch (Exception $e) {
    // рендеринг вьюхи с ошибкой View/error.phtml - там теперь ее можно украсить как-то
    $content = Controller::renderError($e->getCode(), $e->getMessage());
}

// метод рендер (рендерЕррор) возвращает весь документ, потому просто выводим его
echo $content;

/*
 * если надо выводить какой-то блок разметки для всех страниц и он зависит от базы данных - то делаем в классе Controller метод
 * renderSomeBar(...) - в нем там к базе обратимся и тп
 * можно прямо в layout.phtml писать <?=Controller::renderSomeBlock?> - но в этом методе не должен рекваирится главный шаблон.
 * можно этот метод писать не в контроллере, а сделать какой-то  хелпер абстрактный и так же вызывать
 */
