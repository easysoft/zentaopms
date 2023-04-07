<?php
$this->app->loadLang('task');
$this->app->loadLang('project');
$this->app->loadLang('execution');

$teamLang = new stdclass();
$teamLang->type = '对象类型';
$teamLang->typeList['task']      = '任务';
$teamLang->typeList['execution'] = '执行';
$teamLang->typeList['project']   = '项目';

$schema = new stdclass();

$schema->primaryTable = 'team';

$schema->tables = array();
$schema->tables['team']      = 'zt_team';
$schema->tables['project']   = 'zt_project';
$schema->tables['execution'] = 'zt_project';
$schema->tables['task']      = 'zt_task';

$schema->joins = array();
$schema->joins['execution'] = 'team.root = execution.id';
$schema->joins['project']   = 'team.root = project.id';
$schema->joins['task']      = 'team.root = task.id';

$schema->fields = array();
$schema->fields['root']     = array('type' => 'object', 'name' => $this->lang->execution->common,  'object' => 'execution', 'show' => 'execution.name');
$schema->fields['account']  = array('type' => 'user', 'name' => $this->lang->team->account);
$schema->fields['type']     = array('type' => 'option', 'name' => $teamLang->type, 'options' => $teamLang->typeList);

$schema->objects = array();

$schema->objects['execution'] = array();
$schema->objects['execution']['id']   = array('type' => 'number', 'name' => $this->lang->execution->id);
$schema->objects['execution']['name'] = array('type' => 'string', 'name' => $this->lang->execution->name);
