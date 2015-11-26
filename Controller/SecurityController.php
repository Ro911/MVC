<?php

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        if (Session::has('user')) {
            header('Location: /index.php');
        }

        $form = new LoginForm($request);

        if ($request->isPost()) {
            if ($form->isValid()) {
                $password = new Password($form->password);
                $model = new UserModel();

                try {
                    $user = $model->getUser($form->username, $password);
                    Session::set('user', $user);
                    header('Location: /');
                } catch (Exception $e) {
                    Session::setFlash($e->getMessage());
                }

            } else {
                Session::setFlash('Fill the fields');
            }
        }

        $args = array(
            'form' => $form
        );

        return $this->render('login', $args);
    }


    public function logoutAction(Request $request)
    {
        Session::remove('user');
        header('Location: /login');
    }
}