<?php


class LoginForm
{
    public $username;
    public $password;

    public function __construct(Request $request)
    {
        $this->username = $request->post('username');
        $this->password = $request->post('password');
    }

    public function isValid()
    {
        return $this->username !== '' && $this->password !== '';
    }

}