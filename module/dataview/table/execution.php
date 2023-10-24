<?php
$this->app->loadLang('execution');
$this->app->loadLang('project');
$this->app->loadLang('product');

$schema = new stdclass();

$schema->primaryTable = 'execution';

$schema->tables = array();
$schema->tables['project']   = 'zt_project';
$schema->tables['execution'] = 'zt_project';

$schema->joins = array();
$schema->joins['project'] = 'execution.project = project.id';

$schema->fields = array();
$schema->fields['project']     = array('type' => 'object', 'name' => $this->lang->project->name, 'object' => 'project', 'show' => 'project.name');
$schema->fields['name']        = array('type' => 'string', 'name' => $this->lang->execution->name);
$schema->fields['code']        = array('type' => 'string', 'name' => $this->lang->execution->code);
$schema->fields['type']        = array('type' => 'option', 'name' => $this->lang->execution->type, 'options' => $this->lang->execution->typeList);
$schema->fields['status']      = array('type' => 'option', 'name' => $this->lang->execution->status, 'options' => $this->lang->execution->statusList);
$schema->fields['desc']        = array('type' => 'string', 'name' => $this->lang->execution->desc);
$schema->fields['begin']       = array('type' => 'date',   'name' => $this->lang->execution->begin);
$schema->fields['end']         = array('type' => 'date',   'name' => $this->lang->execution->end);
$schema->fields['PO']          = array('type' => 'user',   'name' => $this->lang->product->PO);
$schema->fields['PM']          = array('type' => 'user',   'name' => $this->lang->execution->PM);
$schema->fields['QD']          = array('type' => 'user',   'name' => $this->lang->execution->QD);
$schema->fields['RD']          = array('type' => 'user',   'name' => $this->lang->execution->RD);
$schema->fields['openedBy']    = array('type' => 'user',   'name' => $this->lang->execution->openedBy);
$schema->fields['openedDate']  = array('type' => 'date',   'name' => $this->lang->execution->openedDate);

$schema->objects = array();

$schema->objects['project'] = array();
$schema->objects['project']['id']   = array('type' => 'number', 'name' => $this->lang->project->id);
$schema->objects['project']['name'] = array('type' => 'string', 'name' => $this->lang->project->name);
