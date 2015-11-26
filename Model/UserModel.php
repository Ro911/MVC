<?php


class UserModel
{
    public function getUser($username, $password)
    {

        $db = DbConnection::getInstance()->getPDO();
        $sth = $db->prepare('SELECT id, username FROM users WHERE username = :username AND password = :password');
        $params = array(
            'username' => $username,
            'password' => (string)$password
        );

        $sth->execute($params);

        $data = $sth->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            throw new Exception('User not found');
        }

        return $data;
    }
}