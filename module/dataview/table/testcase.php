<?php
$this->app->loadLang('product');
$this->app->loadLang('testcase');
$this->app->loadLang('testtask');
$this->app->loadLang('tree');
$this->app->loadLang('story');
$this->app->loadLang('dataview');

$schema = new stdclass();

$schema->primaryTable = 'testcase';

$schema->tables = array();
$schema->tables['testcase']   = 'zt_case';
$schema->tables['product']    = 'zt_product';
$schema->tables['casemodule'] = 'zt_module';
$schema->tables['story']      = 'zt_story';
$schema->tables['casestep']   = 'zt_casestep';

$schema->joins = array();
$schema->joins['product']    = 'product.id = testcase.product';
$schema->joins['casemodule'] = 'casemodule.id = testcase.module';
$schema->joins['story']      = 'story.id = testcase.story';
$schema->joins['casestep']   = 'casestep.case = testcase.id';

$schema->fields = array();
$schema->fields['id']           = array('type' => 'number', 'name' => $this->lang->testcase->id);
$schema->fields['title']        = array('type' => 'string', 'name' => $this->lang->testcase->title);
$schema->fields['pri']          = array('type' => 'option', 'name' => $this->lang->testcase->pri, 'options' => $this->lang->testcase->priList);
$schema->fields['type']         = array('type' => 'option', 'name' => $this->lang->testcase->type, 'options' => $this->lang->testcase->typeList);
$schema->fields['stage']        = array('type' => 'option', 'name' => $this->lang->testcase->stage, 'options' => $this->lang->testcase->stageList);
$schema->fields['status']       = array('type' => 'option', 'name' => $this->lang->testcase->status, 'options' => $this->lang->testcase->statusList);
$schema->fields['version']      = array('type' => 'number',    'name' => $this->lang->testcase->version);
$schema->fields['product']      = array('type' => 'object', 'name' => $this->lang->testcase->product, 'object' => 'product', 'show' => 'product.name');
$schema->fields['story']        = array('type' => 'object', 'name' => $this->lang->testcase->story, 'object' => 'story', 'show' => 'story.title');
$schema->fields['casemodule']   = array('type' => 'object', 'name' => $this->lang->testcase->module, 'object' => 'casemodule', 'show' => 'casemodule.name');
$schema->fields['openedBy']     = array('type' => 'user',   'name' => $this->lang->testcase->openedBy);
$schema->fields['openedDate']   = array('type' => 'date',   'name' => $this->lang->testcase->openedDate);

$schema->objects = array();

$schema->objects['product'] = array();
$schema->objects['product']['id']   = array('type' => 'number', 'name' => $this->lang->product->id);
$schema->objects['product']['name'] = array('type' => 'string', 'name' => $this->lang->product->common);

$schema->objects['casemodule'] = array();
$schema->objects['casemodule']['id']   = array('type' => 'number', 'name' => $this->lang->dataview->id);
$schema->objects['casemodule']['name'] = array('type' => 'string', 'name' => $this->lang->tree->name);

$schema->objects['story'] = array();
$schema->objects['story']['id']    = array('type' => 'number', 'name' => $this->lang->story->id);
$schema->objects['story']['title'] = array('type' => 'string', 'name' => $this->lang->story->title);
