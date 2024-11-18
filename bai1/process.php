<?php
session_start();

$usersFile = 'users.json';
$todosFile = 'todolist.json';

function readJSON($filename)
{
    return file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];
}

function writeJSON($filename, $data)
{
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

if ($_POST['action'] === 'register') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $users = readJSON($usersFile);
    if (isset($users[$username])) {
        echo "Tên đăng nhập đã tồn tại. <a href='index.html'>Quay lại</a>";
    } else {
        $users[$username] = $password;
        writeJSON($usersFile, $users);
        echo "Đăng ký thành công! <a href='index.html'>Quay lại</a>";
    }
}

if ($_POST['action'] === 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $users = readJSON($usersFile);
    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['username'] = $username;
        header('Location: todo.php');
        exit;
    } else {
        echo "Đăng nhập thất bại. <a href='index.html'>Quay lại</a>";
    }
}
?>