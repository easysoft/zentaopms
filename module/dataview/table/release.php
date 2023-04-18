<?php
$this->app->loadLang('release');
$this->app->loadLang('product');
$this->app->loadLang('project');
$this->app->loadLang('build');

$schema = new stdclass();

$schema->primaryTable = 'release';

$schema->tables = array();
$schema->tables['release'] = 'zt_release';
$schema->tables['product'] = 'zt_product';
$schema->tables['project'] = 'zt_project';
$schema->tables['build']   = 'zt_build';

$schema->joins = array();
$schema->joins['product'] = 'release.product = product.id';
$schema->joins['project'] = 'release.project = project.id';
$schema->joins['build']   = 'release.build   = build.id';

$schema->fields = array();
$schema->fields['id']       = array('type' => 'number', 'name' => $this->lang->release->id);
$schema->fields['product']  = array('type' => 'object', 'name' => $this->lang->release->product, 'object' => 'product', 'show' => 'product.name');
$schema->fields['project']  = array('type' => 'object', 'name' => $this->lang->release->project, 'object' => 'project', 'show' => 'project.name');
$schema->fields['build']    = array('type' => 'object', 'name' => $this->lang->release->build,   'object' => 'build',   'show' => 'build.name');
$schema->fields['name']     = array('type' => 'string', 'name' => $this->lang->release->name);
$schema->fields['status']   = array('type' => 'option', 'name' => $this->lang->release->status, 'options' => $this->lang->release->statusList);
$schema->fields['desc']     = array('type' => 'string', 'name' => $this->lang->release->desc);
$schema->fields['date']     = array('type' => 'date',   'name' => $this->lang->release->date);
$schema->fields['stories']  = array('type' => 'string', 'name' => $this->lang->release->stories);
$schema->fields['bugs']     = array('type' => 'string', 'name' => $this->lang->release->bugs);
$schema->fields['leftBugs'] = array('type' => 'string', 'name' => $this->lang->release->leftBugs);

$schema->objects = array();

$schema->objects['product'] = array();
$schema->objects['product']['id']   = array('type' => 'number', 'name' => $this->lang->product->id);
$schema->objects['product']['name'] = array('type' => 'string', 'name' => $this->lang->product->name);

$schema->objects['project'] = array();
$schema->objects['project']['id']   = array('type' => 'number', 'name' => $this->lang->project->id);
$schema->objects['project']['name'] = array('type' => 'string', 'name' => $this->lang->project->name);

$schema->objects['build'] = array();
$schema->objects['build']['id']   = array('type' => 'number', 'name' => $this->lang->build->id);
$schema->objects['build']['name'] = array('type' => 'string', 'name' => $this->lang->build->common);
