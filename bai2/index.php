<?php
require 'todolist.php';

$todoList = new TodoList('data.json');

// Khởi tạo $tasks với mảng rỗng để tránh lỗi Undefined variable
$tasks = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        // Thêm công việc mới vào TodoList
        $todoList->addTask([
            'id' => uniqid(),
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'priority' => $_POST['priority'],
            'status' => false
        ]);
    } elseif (isset($_POST['filter'])) {
        // Lọc các công việc theo mức độ ưu tiên
        $tasks = $todoList->filterByPriority($_POST['priority']);
    } elseif (isset($_POST['search'])) {
        // Tìm kiếm các công việc theo từ khóa
        $tasks = $todoList->searchTasks($_POST['keyword']);
    } elseif (isset($_POST['update_status'])) {
        // Cập nhật trạng thái công việc (đánh dấu hoàn thành)
        $taskId = $_POST['task_id'];
        $todoList->updateTaskStatus($taskId);
    } else {
        // Lấy tất cả công việc trong danh sách
        $tasks = $todoList->getTasks();
    }

    // Lưu lại danh sách công việc vào tệp JSON
    $todoList->save('data.json');
} else {
    // Khi không có yêu cầu POST, lấy tất cả công việc
    $tasks = $todoList->getTasks();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TodoList</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Todo List</h1>

    <!-- Form để thêm công việc -->
    <form method="POST">
        <input type="text" name="title" placeholder="Tiêu đề" required>
        <input type="text" name="description" placeholder="Mô tả" required>
        <select name="priority">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
        </select>
        <button type="submit" name="add">Thêm</button>
    </form>

    <!-- Form tìm kiếm công việc -->
    <form method="POST">
        <input type="text" name="keyword" placeholder="Tìm kiếm" required>
        <button type="submit" name="search">Tìm kiếm</button>
    </form>

    <!-- Form lọc công việc theo mức độ ưu tiên -->
    <form method="POST">
        <select name="priority">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
        </select>
        <button type="submit" name="filter">Lọc</button>
    </form>

    <!-- Hiển thị danh sách công việc -->
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <strong><?= htmlspecialchars($task['title']) ?></strong> (<?= htmlspecialchars($task['priority']) ?>)
                <p><?= htmlspecialchars($task['description']) ?></p>

                <!-- Form để cập nhật trạng thái công việc -->
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                    <input type="checkbox" name="status" <?= $task['status'] ? 'checked' : '' ?>>
                    <button type="submit" name="update_status">Cập nhật</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>