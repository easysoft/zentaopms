<?php
$this->app->loadLang('task');
$this->app->loadLang('project');
$this->app->loadLang('execution');
$this->app->loadLang('story');
$this->app->loadLang('tree');
$this->app->loadLang('dataview');

$schema = new stdclass();

$schema->primaryTable = 'task';

$schema->tables = array();
$schema->tables['task']       = 'zt_task';
$schema->tables['project']    = 'zt_project';
$schema->tables['execution']  = 'zt_project';
$schema->tables['story']      = 'zt_story';
$schema->tables['taskmodule'] = 'zt_module';

$schema->joins = array();
$schema->joins['execution']  = 'task.execution = execution.id';
$schema->joins['project']    = 'task.project   = project.id';
$schema->joins['story']      = 'task.story     = story.id';
$schema->joins['taskmodule'] = 'task.module    = taskmodule.id';

$schema->fields = array();
$schema->fields['project']      = array('type' => 'object', 'name' => $this->lang->task->project, 'object' => 'project', 'show' => 'project.name');
$schema->fields['execution']    = array('type' => 'object', 'name' => $this->lang->task->execution, 'object' => 'execution', 'show' => 'execution.name');
$schema->fields['story']        = array('type' => 'object', 'name' => $this->lang->task->story, 'object' => 'story', 'show' => 'story.title');
$schema->fields['taskmodule']   = array('type' => 'object', 'name' => $this->lang->task->module, 'object' => 'taskmodule', 'show' => 'taskmodule.name');
$schema->fields['name']         = array('type' => 'string', 'name' => $this->lang->task->name);
$schema->fields['pri']          = array('type' => 'option', 'name' => $this->lang->task->pri, 'options' => $this->lang->task->priList);
$schema->fields['type']         = array('type' => 'option', 'name' => $this->lang->task->type, 'options' => $this->lang->task->typeList);
$schema->fields['status']       = array('type' => 'option', 'name' => $this->lang->task->status, 'options' => $this->lang->task->statusList);
$schema->fields['desc']         = array('type' => 'string', 'name' => $this->lang->task->desc);
$schema->fields['estimate']     = array('type' => 'string', 'name' => $this->lang->task->estimate);
$schema->fields['consumed']     = array('type' => 'string', 'name' => $this->lang->task->consumed);
$schema->fields['left']         = array('type' => 'string', 'name' => $this->lang->task->left);
$schema->fields['estStarted']   = array('type' => 'date',   'name' => $this->lang->task->estStarted);
$schema->fields['deadline']     = array('type' => 'date',   'name' => $this->lang->task->deadline);
$schema->fields['assignedTo']   = array('type' => 'user',   'name' => $this->lang->task->assignedTo);
$schema->fields['finishedBy']   = array('type' => 'user',   'name' => $this->lang->task->finishedBy);
$schema->fields['closedBy']     = array('type' => 'user',   'name' => $this->lang->task->closedBy);
$schema->fields['openedBy']     = array('type' => 'user',   'name' => $this->lang->task->openedBy);
$schema->fields['openedDate']   = array('type' => 'date',   'name' => $this->lang->task->openedDate);
$schema->fields['canceledBy']   = array('type' => 'user',   'name' => $this->lang->task->canceledBy);
$schema->fields['closedReason'] = array('type' => 'option', 'name' => $this->lang->task->closedReason, 'options' => $this->lang->task->reasonList);

$schema->objects = array();

$schema->objects['project'] = array();
$schema->objects['project']['id']   = array('type' => 'number', 'name' => $this->lang->project->id);
$schema->objects['project']['name'] = array('type' => 'string', 'name' => $this->lang->project->name);

$schema->objects['execution'] = array();
$schema->objects['execution']['id']   = array('type' => 'number', 'name' => $this->lang->execution->id);
$schema->objects['execution']['name'] = array('type' => 'string', 'name' => $this->lang->execution->name);

$schema->objects['story'] = array();
$schema->objects['story']['id']    = array('type' => 'number', 'name' => $this->lang->story->id);
$schema->objects['story']['title'] = array('type' => 'string', 'name' => $this->lang->story->title);

$schema->objects['taskmodule'] = array();
$schema->objects['taskmodule']['id']   = array('type' => 'number', 'name' => $this->lang->dataview->id);
$schema->objects['taskmodule']['name'] = array('type' => 'string', 'name' => $this->lang->tree->name);
