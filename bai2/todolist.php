<?php
class TodoList
{
    private $tasks;

    public function __construct($filename)
    {
        if (file_exists($filename)) {
            $this->tasks = json_decode(file_get_contents($filename), true);
        } else {
            $this->tasks = [];
        }
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function addTask($task)
    {
        $this->tasks[] = $task;
    }

    public function save($filename)
    {
        file_put_contents($filename, json_encode($this->tasks, JSON_PRETTY_PRINT));
    }

    public function filterByPriority($priority)
    {
        return array_filter($this->tasks, function ($task) use ($priority) {
            return $task['priority'] === $priority;
        });
    }

    public function searchTasks($keyword)
    {
        return array_filter($this->tasks, function ($task) use ($keyword) {
            return stripos($task['title'], $keyword) !== false || stripos($task['description'], $keyword) !== false;
        });
    }

    public function updateTaskStatus($taskId)
    {
        foreach ($this->tasks as &$task) {
            if ($task['id'] === $taskId) {
                $task['status'] = !$task['status'];
                break;
            }
        }
    }
}

?>