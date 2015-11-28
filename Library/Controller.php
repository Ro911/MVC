<?php

abstract class Controller
{
    /**
     * @param $template
     * @param array $args
     * @return string
     * @throws Exception
     */
    protected function render($template, array $args = array())
    {
        extract($args);

        // название каталога формируется по названию контроллера IndexController -> каталог Index
        $templateDir = str_replace('Controller', '', get_class($this));

        // полный путь к шаблону содержит путь к папке View в виде константы VIEW_DIR
        $templateFile = VIEW_DIR . $templateDir . DS . $template . '.phtml';

        if (!file_exists($templateFile)) {
            throw new Exception("{$templateFile} not found", 404);
        }

        // открываем буфер вывода, далее - подключение шаблона.
        // там можно использовать переменные, которые определены в контроллере - с готовыми данными
        ob_start();
        require $templateFile;

        // очистка буфера и возврат строки с динамческим контентом в переменную $content
        $content = ob_get_clean();

        // еще раз открываем буфер, подключаем главную разметку и там есть переменная $content, она подставляется
        // тут главное, чтоб из контроллера не передавалась переменная с названием $content :)
        // это делается для того, чтобы мы могли определять аргументы для главного шаблона в из контроллера. Например title страницы
        ob_start();
        require VIEW_DIR . 'layout.phtml';
        return ob_get_clean();
    }


    /**
     * почти то же, что и обычный рендер, но вьюха конкретная. Как параметры передаем код и сообщение ошибки
     * как видим тут дублируется код (как в методе выше), можно сделать приватный метод чтоб это обойти
     * TODO: желательно не выводить на екран оригинальные сообщения которые содержат инфо о базе, размещении файлов и тп, а записывать их в лог.
     * TODO: при этом на екран должно выводится более общее сообщение типа internal server error, bad request, not found, etc
     * @param $code
     * @param $message
     * @return string
     */
    public static function renderError($code, $message)
    {
        ob_start();
        require VIEW_DIR . 'error.phtml';
        $content = ob_get_clean();

        ob_start();
        require VIEW_DIR . 'layout.phtml';
        return ob_get_clean();
    }
}