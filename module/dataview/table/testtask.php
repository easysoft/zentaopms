<?php
$this->app->loadLang('product');
$this->app->loadLang('project');
$this->app->loadLang('execution');
$this->app->loadLang('build');
$this->app->loadLang('testtask');

$schema = new stdclass();

$schema->primaryTable = 'testtask';

$schema->tables = array();
$schema->tables['product']   = 'zt_product';
$schema->tables['project']   = 'zt_project';
$schema->tables['execution'] = 'zt_project';
$schema->tables['build']     = 'zt_build';
$schema->tables['testtask']  = 'zt_testtask';

$schema->joins = array();
$schema->joins['product']   = 'product.id   = testtask.product';
$schema->joins['project']   = 'project.id   = testtask.project';
$schema->joins['execution'] = 'execution.id = testtask.execution';
$schema->joins['build']     = 'build.id     = testtask.build';

$schema->fields = array();
$schema->fields['product']        = array('type' => 'object', 'name' => $this->lang->testtask->product, 'object' => 'product', 'show' => 'product.name');
$schema->fields['project']        = array('type' => 'object', 'name' => $this->lang->testtask->project, 'object' => 'project', 'show' => 'project.name');
$schema->fields['execution']      = array('type' => 'object', 'name' => $this->lang->testtask->execution, 'object' => 'execution', 'show' => 'execution.name');
$schema->fields['build']          = array('type' => 'object', 'name' => $this->lang->testtask->build, 'object' => 'build', 'show' => 'build.name');

$schema->fields['id']     = array('type' => 'number', 'name' => $this->lang->testtask->id);
$schema->fields['name']   = array('type' => 'string', 'name' => $this->lang->testtask->name);
$schema->fields['type']   = array('type' => 'option', 'name' => $this->lang->testtask->type, 'options' => $this->lang->testtask->typeList);
$schema->fields['owner']  = array('type' => 'user',   'name' => $this->lang->testtask->owner);
$schema->fields['pri']    = array('type' => 'option', 'name' => $this->lang->testtask->pri, 'options' => $this->lang->testtask->priList);
$schema->fields['begin']  = array('type' => 'date',   'name' => $this->lang->testtask->begin);
$schema->fields['end']    = array('type' => 'date',   'name' => $this->lang->testtask->end);
$schema->fields['status'] = array('type' => 'option', 'name' => $this->lang->testtask->status, 'options' => $this->lang->testtask->statusList);

$schema->objects = array();

$schema->objects['product'] = array();
$schema->objects['product']['id']   = array('type' => 'number', 'name' => $this->lang->product->id);
$schema->objects['product']['name'] = array('type' => 'string', 'name' => $this->lang->product->name);

$schema->objects['project'] = array();
$schema->objects['project']['id']   = array('type' => 'number', 'name' => $this->lang->project->id);
$schema->objects['project']['name'] = array('type' => 'string', 'name' => $this->lang->project->name);

$schema->objects['execution'] = array();
$schema->objects['execution']['id']   = array('type' => 'number', 'name' => $this->lang->execution->id);
$schema->objects['execution']['name'] = array('type' => 'string', 'name' => $this->lang->execution->name);

$schema->objects['build'] = array();
$schema->objects['build']['id']   = array('type' => 'number', 'name' => $this->lang->build->id);
$schema->objects['build']['name'] = array('type' => 'string', 'name' => $this->lang->build->name);
