<?php
$this->app->loadLang('product');
$this->app->loadLang('project');
$this->app->loadLang('execution');
$this->app->loadLang('build');

$schema = new stdclass();

$schema->primaryTable = 'build';

$schema->tables = array();
$schema->tables['product']   = 'zt_product';
$schema->tables['project']   = 'zt_project';
$schema->tables['execution'] = 'zt_project';
$schema->tables['build']     = 'zt_build';

$schema->joins = array();
$schema->joins['product']   = 'product.id   = build.product';
$schema->joins['project']   = 'project.id   = build.project';
$schema->joins['execution'] = 'execution.id = build.execution';

$schema->fields = array();
$schema->fields['product']   = array('type' => 'object', 'name' => $this->lang->build->product, 'object' => 'product', 'show' => 'product.name');
$schema->fields['project']   = array('type' => 'object', 'name' => $this->lang->build->project, 'object' => 'project', 'show' => 'project.name');
$schema->fields['execution'] = array('type' => 'object', 'name' => $this->lang->build->execution, 'object' => 'execution', 'show' => 'execution.name');
$schema->fields['name']      = array('type' => 'string', 'name' => $this->lang->build->name);
$schema->fields['builder']   = array('type' => 'user',   'name' => $this->lang->build->builder);
$schema->fields['stories']   = array('type' => 'string', 'name' => $this->lang->build->stories);
$schema->fields['bugs']      = array('type' => 'string', 'name' => $this->lang->build->bugs);
$schema->fields['date']      = array('type' => 'date',   'name' => $this->lang->build->date);
$schema->fields['desc']      = array('type' => 'string', 'name' => $this->lang->build->desc);

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
