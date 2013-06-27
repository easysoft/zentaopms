<?php 
if($this->session->taskType) die(js::locate($this->createLink('my', 'task', "type={$this->session->taskType}"), 'parent'));
die(js::locate($this->createLink('project', 'task', "projectID=$project->id"), 'parent'));
?>
