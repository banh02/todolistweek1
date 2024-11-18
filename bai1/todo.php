<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}

$username = $_SESSION['username'];
$todosFile = 'todolist.json';

// Hàm đọc file JSON
function readJSON($filename)
{
    return file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];
}

// Hàm ghi file JSON
function writeJSON($filename, $data)
{
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

// Nạp danh sách công việc
$todos = readJSON($todosFile);
$userTodos = $todos[$username] ?? [];

// Xử lý thêm, sửa, xóa công việc
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $title = $_POST['title'];
        $status = $_POST['status'];
        $content = $_POST['content'];
        $userTodos[] = ['title' => $title, 'status' => $status, 'content' => $content];
    }

    if ($_POST['action'] === 'edit') {
        $index = $_POST['index'];
        $userTodos[$index]['title'] = $_POST['title'];
        $userTodos[$index]['status'] = $_POST['status'];
        $userTodos[$index]['content'] = $_POST['content'];
    }

    if ($_POST['action'] === 'delete') {
        $index = $_POST['index'];
        unset($userTodos[$index]);
        $userTodos = array_values($userTodos); // Reindex array
    }

    $todos[$username] = $userTodos;
    writeJSON($todosFile, $todos);
    header('Location: todo.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoList</title>
</head>

<body>
    <h1>Chào, <?= htmlspecialchars($username) ?></h1>
    <a href="logout.php">Đăng xuất</a>

    <h2>Danh sách công việc</h2>
    <ul>
        <?php foreach ($userTodos as $index => $todo): ?>
            <li>
                <strong>Tiêu đề:</strong> <?= htmlspecialchars($todo['title']) ?><br>
                <strong>Trạng thái:</strong> <?= htmlspecialchars($todo['status']) ?><br>
                <strong>Nội dung:</strong> <?= htmlspecialchars($todo['content']) ?><br>
                <form action="todo.php" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <button type="submit">Xóa</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Thêm công việc</h2>
    <form action="todo.php" method="POST">
        <input type="hidden" name="action" value="add">
        <label for="title">Tiêu đề:</label>
        <input type="text" id="title" name="title" required><br>
        <label for="status">Trạng thái:</label>
        <select id="status" name="status">
            <option value="incomplete">Incomplete</option>
            <option value="completed">Completed</option>
        </select><br>
        <label for="content">Nội dung:</label>
        <textarea id="content" name="content" required></textarea><br>
        <button type="submit">Thêm</button>
    </form>
</body>

</html>