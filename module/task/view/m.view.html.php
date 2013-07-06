<?php 
$this->session->set('taskID', $task->id);
if($this->session->taskType) die($this->locate($this->createLink('my', 'task', "type={$this->session->taskType}")));
die($this->locate($this->createLink('project', 'task', "projectID=$project->id")));
?>
