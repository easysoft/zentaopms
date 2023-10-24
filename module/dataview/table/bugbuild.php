<?php
$this->app->loadLang('bug');
$this->app->loadLang('product');
$this->app->loadLang('testtask');
$this->app->loadLang('build');
$this->app->loadLang('execution');
$this->app->loadLang('project');
$this->app->loadLang('tree');
$this->app->loadLang('dataview');

$schema = new stdclass();

$schema->primaryTable = 'bug';

$schema->tables = array();
$schema->tables['bug']          = 'zt_bug';
$schema->tables['product']      = 'zt_product';
$schema->tables['testtask']     = 'zt_testtask';
$schema->tables['testcase']     = 'zt_case';
$schema->tables['build']        = 'zt_build';
$schema->tables['execution']    = 'zt_project';
$schema->tables['project']      = 'zt_project';
$schema->tables['module']       = 'zt_module';
$schema->tables['casemodule']   = 'zt_module';

$schema->joins = array();
$schema->joins['product']     = 'product.id = bug.product';
$schema->joins['testtask']    = 'testtask.id = bug.testtask';
$schema->joins['build']       = 'build.id = testtask.build';
$schema->joins['execution']   = 'execution.id = build.execution';
$schema->joins['project']     = 'project.id = build.project';
$schema->joins['module']      = 'module.id = bug.module';
$schema->joins['testcase']    = 'testcase.id = bug.case';
$schema->joins['casemodule']  = 'casemodule.id = testcase.module';

$schema->fields = array();
$schema->fields['id']           = array('type' => 'number',   'name' => $this->lang->bug->id);
$schema->fields['title']        = array('type' => 'string',   'name' => $this->lang->bug->title);
$schema->fields['steps']        = array('type' => 'text',     'name' => $this->lang->bug->steps);
$schema->fields['status']       = array('type' => 'option',   'name' => $this->lang->bug->status, 'options' => $this->lang->bug->statusList);
$schema->fields['confirmed']    = array('type' => 'option',   'name' => $this->lang->bug->confirmed, 'options' => $this->lang->bug->confirmedList);
$schema->fields['severity']     = array('type' => 'option',   'name' => $this->lang->bug->severity, 'options' => $this->lang->bug->severityList);
$schema->fields['product']      = array('type' => 'object',   'name' => $this->lang->bug->product, 'object' => 'product', 'show' => 'product.name');
$schema->fields['project']      = array('type' => 'object',   'name' => $this->lang->bug->project, 'object' => 'project', 'show' => 'project.name');
$schema->fields['build']        = array('type' => 'object',   'name' => $this->lang->build->common, 'object' => 'build', 'show' => 'build.name');
$schema->fields['module']       = array('type' => 'object',   'name' => $this->lang->bug->module, 'object' => 'module', 'show' => 'module.name');
$schema->fields['testtask']     = array('type' => 'object',   'name' => $this->lang->testtask->common, 'object' => 'testtask', 'show' => 'testtask.name');
$schema->fields['pri']          = array('type' => 'option',   'name' => $this->lang->bug->pri, 'options' => $this->lang->bug->priList);
$schema->fields['openedBy']     = array('type' => 'user',     'name' => $this->lang->bug->openedBy);
$schema->fields['openedDate']   = array('type' => 'datetime', 'name' => $this->lang->bug->openedDate);
$schema->fields['resolvedBy']   = array('type' => 'user',     'name' => $this->lang->bug->resolvedBy);
$schema->fields['resolution']   = array('type' => 'option',   'name' => $this->lang->bug->resolution, 'options' => $this->lang->bug->resolutionList);
$schema->fields['resolvedDate'] = array('type' => 'datetime', 'name' => $this->lang->bug->resolvedDate);
$schema->fields['casemodule']   = array('type' => 'object',   'name' => $this->lang->tree->module, 'object' => 'casemodule', 'show' => 'casemodule.name');

$schema->objects = array();

$schema->objects['product'] = array();
$schema->objects['product']['id']   = array('type' => 'number', 'name' => $this->lang->product->id);
$schema->objects['product']['name'] = array('type' => 'string', 'name' => $this->lang->product->common);

$schema->objects['testtask'] = array();
$schema->objects['testtask']['id']   = array('type' => 'number', 'name' => $this->lang->testtask->id);
$schema->objects['testtask']['name'] = array('type' => 'string', 'name' => $this->lang->testtask->common);

$schema->objects['build'] = array();
$schema->objects['build']['id']   = array('type' => 'number', 'name' => $this->lang->build->id);
$schema->objects['build']['name'] = array('type' => 'string', 'name' => $this->lang->build->common);

$schema->objects['execution'] = array();
$schema->objects['execution']['id']   = array('type' => 'number', 'name' => $this->lang->dataview->id);
$schema->objects['execution']['name'] = array('type' => 'string', 'name' => $this->lang->execution->common);

$schema->objects['project'] = array();
$schema->objects['project']['id']   = array('type' => 'number', 'name' => $this->lang->project->id);
$schema->objects['project']['name'] = array('type' => 'string', 'name' => $this->lang->project->common);

$schema->objects['build'] = array();
$schema->objects['build']['id']   = array('type' => 'number', 'name' => $this->lang->build->id);
$schema->objects['build']['name'] = array('type' => 'string', 'name' => $this->lang->build->common);

$schema->objects['module'] = array();
$schema->objects['module']['id']   = array('type' => 'number', 'name' => $this->lang->dataview->id);
$schema->objects['module']['name'] = array('type' => 'string', 'name' => $this->lang->tree->module);

$schema->objects['testcase'] = array();
$schema->objects['testcase']['id']    = array('type' => 'number', 'name' => $this->lang->dataview->id);
$schema->objects['testcase']['title'] = array('type' => 'string', 'name' => $this->lang->testcase->common);

$schema->objects['casemodule'] = array();
$schema->objects['casemodule']['id']   = array('type' => 'number', 'name' => $this->lang->dataview->id);
$schema->objects['casemodule']['name'] = array('type' => 'string', 'name' => $this->lang->dataview->name);
