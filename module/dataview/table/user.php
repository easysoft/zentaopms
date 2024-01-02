<?php
$this->app->loadLang('user');
$this->app->loadLang('dept');

$schema = new stdclass();

$schema->primaryTable = 'user';

$schema->tables = array();
$schema->tables['user'] = 'zt_user';
$schema->tables['dept'] = 'zt_dept';

$schema->joins = array();
$schema->joins['dept'] = 'dept.id = user.dept';

$schema->fields = array();
$schema->fields['id']   = array('type' => 'number', 'name' => $this->lang->user->id);
$schema->fields['dept'] = array('type' => 'object', 'name' => $this->lang->user->dept, 'object' => 'dept', 'show' => 'dept.name');
