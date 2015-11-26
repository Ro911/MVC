<?php

class IndexController extends Controller
{
    /**
     * действие для главной странички - Home
     *
     * @param Request $request
     * @return int
     */
    public function indexAction(Request $request)
    {
        $model = new PageModel();
        $text = $model->getById(1);

        $args = array(
            'text' => $text
        );

        return $this->render('index', $args);
    }


    /**
     * действие для странички о нас - About
     *
     * @param Request $request
     * @return int
     */
    public function aboutAction(Request $request)
    {
        $model = new PageModel();
        $text = $model->getById(2);

        $args = array(
            'text' => $text
        );

        return $this->render('about', $args);
    }


    /**
     * действие для странички с контактной формой - Contact
     *
     * @param Request $request
     * @return int
     */
    public function contactAction(Request $request)
    {
        $form = new ContactForm($request);

        if ($request->isPost()) {
            if ($form->isValid()) {

                // todo: email + insert into DB table via MessageModel

                Session::setFlash('Booya! Message sent!');

                // todo: добавить в базовый класс метод redirect($route). Тогда тут будет $this->redirect('contact')
                header('Location: /contact');
                die;
            } else {
                Session::setFlash('Fail');
            }
        }

        $args = array(
            // todo: допилить во вьюхе, чтоб в полях оставались значения при невалидной форме (саму валидацию в классе тоже надо доделать, вроде делали когда-то)
            'form' => $form
        );

        return $this->render('contact', $args);
    }

}