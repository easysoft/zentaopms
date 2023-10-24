<?php
$this->app->loadLang('product');
$this->app->loadLang('program');
$this->app->loadLang('tree');

$schema = new stdclass();

$schema->primaryTable = 'product';

$schema->tables = array();
$schema->tables['product']   = 'zt_product';
$schema->tables['program']   = 'zt_project';
$schema->tables['line']      = 'zt_module';

$schema->joins = array();
$schema->joins['program']   = 'product.program = program.id';
$schema->joins['line']      = 'product.line    = line.id';

$schema->fields = array();
$schema->fields['id']          = array('type' => 'number', 'name' => $this->lang->product->id);
$schema->fields['program']     = array('type' => 'object', 'name' => $this->lang->product->program, 'object' => 'program', 'show' => 'program.name');
$schema->fields['line']        = array('type' => 'object', 'name' => $this->lang->product->line, 'object' => 'line', 'show' => 'line.name');
$schema->fields['name']        = array('type' => 'string', 'name' => $this->lang->product->name);
$schema->fields['code']        = array('type' => 'string', 'name' => $this->lang->product->code);
$schema->fields['type']        = array('type' => 'option', 'name' => $this->lang->product->type, 'options' => $this->lang->product->typeList);
$schema->fields['status']      = array('type' => 'option', 'name' => $this->lang->product->status, 'options' => $this->lang->product->statusList);
$schema->fields['desc']        = array('type' => 'string', 'name' => $this->lang->product->desc);
$schema->fields['PO']          = array('type' => 'user',   'name' => $this->lang->product->PO);
$schema->fields['QD']          = array('type' => 'user',   'name' => $this->lang->product->QD);
$schema->fields['RD']          = array('type' => 'user',   'name' => $this->lang->product->RD);
$schema->fields['createdBy']   = array('type' => 'user',   'name' => $this->lang->product->createdBy);
$schema->fields['createdDate'] = array('type' => 'date',   'name' => $this->lang->product->createdDate);

$schema->objects = array();

$schema->objects['program'] = array();
$schema->objects['program']['id']   = array('type' => 'number', 'name' => $this->lang->program->id);
$schema->objects['program']['name'] = array('type' => 'string', 'name' => $this->lang->program->name);

$schema->objects['line'] = array();
$schema->objects['line']['name'] = array('type' => 'string', 'name' => $this->lang->product->line);
