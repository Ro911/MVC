<?php

class BookController extends Controller
{
    /**
     * действие для отображения списка книг - Books
     *
     * @param Request $request
     * @return int
     */
    public function indexAction(Request $request)
    {
        $bookModel = new BookModel();

        $page = $request->get('page');

        $count = $bookModel->getBooksCount();
        $books = $bookModel->getList($page);

        $pagination = new Pagination(array(
               'itemsCount' => $count,
               'itemsPerPage' => BookModel::BOOKS_PER_PAGE,
               'currentPage' => $page
        ));


        $args = array(
            'books' => $books,
            'pagination' => $pagination
        );

        return $this->render('index', $args);
    }


    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function showAction(Request $request)
    {
        $id = $request->get('id');
        $model = new BookModel();

        $book = $model->getById($id);

        $args = array(
            'book' => $book
        );

        return $this->render('show', $args);
    }


    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function editAction(Request $request)
    {
        // if not signed in - redirect
        if (!Session::has('user')) {
            header('Location: /index.php');
        }

        $id = $request->get('id');

        $args = array(
            'id' => $id
        );

        return $this->render('edit', $args);
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    public function addToCartAction(Request $request)
    {
        $id = $request->get('id');

        if (!$id) {
            throw new Exception('Bad request', 400);
        }

        $cart = new Cart();
        $cart->addProduct($id);

        header('Location: /cart/show');
    }

    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function showCartAction(Request $request)
    {
        $cart = new Cart();
        $productsId = $cart->getProducts();

        $args = array(
            'productsId' => $productsId
        );

        return $this->render('cart', $args);
    }

}