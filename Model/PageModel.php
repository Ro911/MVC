<?php

class PageModel
{
    public function getById($id)
    {
        // это тестовая модель, потом сделаем таблицу со страницами в БД и будем оттуда доставать текст

        $pages = array(
            1 => 'This is home page',
            2 => 'This is about page'
        );

        return $pages[$id];
    }

}