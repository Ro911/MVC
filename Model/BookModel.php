<?php

class BookModel
{
    const BOOKS_PER_PAGE = 10;

    public function getList($page, $perPage = self::BOOKS_PER_PAGE)
    {
        $page = (int)$page;

        // тут смотрим на номер. если отрицательный - юзер идет лесом. обартите внимание на код ответа
        if ($page < 0) {
            throw new Exception('Bad request' , 400);
        }

        // страницы передаются в человеческой нумерации, потому уменьшаем, если тм не ноль (что будет, если, к примеру, в адресной строке вообще не было страницы)
        if ($page) {
            $page--;
        }

        $offset = $page * $perPage;
        $query = "select * from books where status = 1 limit {$offset}, {$perPage}";

        $db = DbConnection::getInstance()->getPDO();
        $sth = $db->query($query);
        $sth->execute();

        $books = array();

        // тут можно было и fetchAll, но мне захотелось переписать дату в нужном формате
        while ($book = $sth->fetch(PDO::FETCH_ASSOC)) {
            $created = DateTime::createFromFormat('Y-m-d H:i:s', $book['created']);
            $book['created'] = $created->format('d M Y');
            $books[] = $book;
        }

        return $books;
    }

    public function getBooksCount()
    {
        $db = DbConnection::getInstance()->getPDO();
        $sth = $db->query("select count(*) as count from books where status = 1");
        $sth->execute();

        // обратите внимание на параметр, это не FETCH_ASSOC. Это для того, чтобы выцепить именно значение
        return $sth->fetch(PDO::FETCH_COLUMN);

    }

    public function getById($id)
    {
        $db = DbConnection::getInstance()->getPDO();
        $sth = $db->prepare('select b.title, b.description, b.price, a.name as author from books b join authors a on a.id = b.author_id where status = 1 and b.id = :book_id');
        $params = array('book_id' => $id);
        $sth->execute($params);

        $book = $sth->fetch(PDO::FETCH_ASSOC);
        if (!$book) {
            throw new Exception('Book not found, sorry', 404);
        }

        return $book;
    }



}
