<?php
class Task
{
    public $id;
    public $title;
    public $description;
    public $priority;
    public $status;

    public function __construct($id, $title, $description, $priority, $status = false)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->priority = $priority;
        $this->status = $status;
    }

    public function toggleStatus()
    {
        $this->status = !$this->status;
    }
}
?>