<?php


abstract class Router
{
    // список роутов - лучше в отдельный файл
    private static $routes = array(

        'index' => array(
            'pattern' => '/index.php',
            'controller' => 'Index',
            'action' => 'index',
            'params' => array()
        ),

        'about' => array(
            'pattern' => '/about',
            'controller' => 'Index',
            'action' => 'about',
            'params' => array()
        ),

        'contact' => array(
            'pattern' => '/contact',
            'controller' => 'Index',
            'action' => 'contact',
            'params' => array()
        ),

        'books_list' => array(
            'pattern' => '/books{slash}{page}',
            'controller' => 'Book',
            'action' => 'index',
            'params' => array(
                'slash' => '/?',
                'page' => '[1-9]?[0-9]*'
            )
        ),

        'book_page' => array(
            'pattern' => '/book-{id}\.html',
            'controller' => 'Book',
            'action' => 'show',
            'params' => array(
                'id' => '[1-9][0-9]*'
            )
        ),

        'book_edit' => array(
            'pattern' => '/books/edit{slash}{id}',
            'controller' => 'Book',
            'action' => 'edit',
            'params' => array(
                'slash' => '/?',
                'id' => '[1-9][0-9]*'
            )
        ),

        'add_to_cart' => array(
            'pattern' => '/cart/add/{id}',
            'controller' => 'Book',
            'action' => 'addToCart',
            'params' => array(
                'id' => '[1-9][0-9]*'
            )
        ),

        'show_cart' => array(
            'pattern' => '/cart/show',
            'controller' => 'Book',
            'action' => 'showCart',
            'params' => array()
        ),

        'login' => array(
            'pattern' => '/login',
            'controller' => 'Security',
            'action' => 'login',
            'params' => array()
        ),

        'logout' => array(
            'pattern' => '/logout',
            'controller' => 'Security',
            'action' => 'logout',
            'params' => array()
        )


    );

    private static $controller;
    private static $action;


    /**
     * @param $url
     * @throws Exception
     */
    public static function parse($url)
    {
        // принимаем урл, берем часть до параметров, обрезаем закрывающий слеш, если есть
        $arr = explode('?', $url);
        $url = rtrim($arr[0], '/');

        // если пусто - то на главную
        if (!$url) {
            self::$controller = 'IndexController';
            self::$action = 'indexAction';
            return;
        }

        // перебор роутов на предмет совпадения решулярки с урлом
        foreach (self::$routes as $route => $item) {
            $regex = $item['pattern'];

            foreach ($item['params'] as $k => $v) {
                $regex = str_replace('{' . $k . '}', '(' . $v . ')', $regex);
            }

            // если совпало
            if (preg_match('@^' . $regex . '$@', $url, $matches)) {
                array_shift($matches);
                if ($matches) {
                    foreach ($item as $k1 => $v1) {
                        $matches = array_combine(array_keys($item['params']), $matches);
                    }
                }

                // определяем названия класса/метода контроллера
                self::$controller = $item['controller'] . 'Controller';
                self::$action = $item['action'] . 'Action';
                $_GET = $matches + $_GET;
                break;
            }
        }

        // если так ничего и не нашли - 404
        if (is_null(self::$controller) || is_null(self::$action)) {
            throw new Exception('Page not found', 404);
        }
    }


    /**
     * TODO
     *
     * @param $routeName
     * @param array $params
     */
    public static function getUrl($routeName, array $params = array())
    {
        // todo: получить УРЛ, подставить всюжу во вьюхах, напрмиер ссылках в атрибут href <?=Router::getRoute('contact') и т.д.
        // $routeName = например books_list. Применяем str_replace и вместо параметров типа {blah} подставляем значения по соответсвующим ключам из 'params' в отдельном роуте
    }







    /**
     * @return mixed
     */
    public static function getController()
    {
        return self::$controller;
    }

    /**
     * @return mixed
     */
    public static function getAction()
    {
        return self::$action;
    }



}