<?php
class User
{
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function register($filename)
    {
        $users = json_decode(file_get_contents($filename), true) ?? [];
        foreach ($users as $user) {
            if ($user['username'] === $this->username) {
                return "Tên người dùng đã tồn tại.";
            }
        }
        $users[] = ['username' => $this->username, 'password' => $this->password];
        file_put_contents($filename, json_encode($users));
        return "Đăng ký thành công!";
    }

    public function login($filename)
    {
        $users = json_decode(file_get_contents($filename), true) ?? [];
        foreach ($users as $user) {
            if ($user['username'] === $this->username && $user['password'] === $this->password) {
                return true;
            }
        }
        return false;
    }
}
?>