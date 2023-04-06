<?php
$this->app->loadLang('testcase');
$this->app->loadLang('testtask');
$this->app->loadLang('tree');
$this->app->loadLang('project');
$this->app->loadLang('execution');
$this->app->loadLang('build');
$this->app->loadLang('product');
$this->app->loadLang('dataview');

$schema = new stdclass();

$schema->primaryTable = 'testrun';

$schema->tables = array();
$schema->tables['testrun']    = 'zt_testrun';
$schema->tables['testcase']   = 'zt_case';
$schema->tables['testtask']   = 'zt_testtask';
$schema->tables['casemodule'] = 'zt_module';
$schema->tables['project']    = 'zt_project';
$schema->tables['execution']  = 'zt_project';
$schema->tables['build']      = 'zt_build';
$schema->tables['product']    = 'zt_product';

$schema->joins = array();
$schema->joins['testcase']   = 'testcase.id   = testrun.case';
$schema->joins['product']    = 'product.id    = testcase.product';
$schema->joins['testtask']   = 'testtask.id   = testrun.task';
$schema->joins['casemodule'] = 'casemodule.id = testcase.module';
$schema->joins['project']    = 'project.id    = testtask.project';
$schema->joins['execution']  = 'execution.id  = testtask.execution';
$schema->joins['build']      = 'build.id      = testtask.build';

$schema->fields = array();
$schema->fields['testtask']      = array('type' => 'object', 'name' => $this->lang->testtask->common, 'object' => 'testtask', 'show' => 'testtask.name');
$schema->fields['testcase']      = array('type' => 'object', 'name' => $this->lang->testcase->common, 'object' => 'testcase', 'show' => 'testcase.title');
$schema->fields['assignedTo']    = array('type' => 'user',   'name' => $this->lang->testcase->assignedTo);
$schema->fields['project']       = array('type' => 'object', 'name' => $this->lang->project->common, 'object' => 'project', 'show' => 'execution.name');
$schema->fields['build']         = array('type' => 'object', 'name' => $this->lang->build->common, 'object' => 'build', 'show' => 'build.name');
$schema->fields['execution']     = array('type' => 'object', 'name' => $this->lang->execution->common, 'object' => 'execution', 'show' => 'execution.name');
$schema->fields['casemodule']    = array('type' => 'object', 'name' => $this->lang->tree->common, 'object' => 'casemodule', 'show' => 'casemodule.name');
$schema->fields['lastRunner']    = array('type' => 'user',   'name' => $this->lang->testtask->lastRunner);
$schema->fields['lastRunDate']   = array('type' => 'user',   'name' => $this->lang->testtask->lastRunDate);
$schema->fields['lastRunResult'] = array('type' => 'option', 'name' => $this->lang->testtask->lastRunResult, 'options' => $this->lang->testcase->resultList);

$schema->objects = array();

$schema->objects['testcase'] = array();
$schema->objects['testcase']['id']    = array('type' => 'number', 'name' => $this->lang->testcase->id);
$schema->objects['testcase']['title'] = array('type' => 'string', 'name' => $this->lang->testcase->title);

$schema->objects['testtask'] = array();
$schema->objects['testtask']['id']   = array('type' => 'number', 'name' => $this->lang->testtask->id);
$schema->objects['testtask']['name'] = array('type' => 'string', 'name' => $this->lang->testtask->name);

$schema->objects['testcase'] = array();
$schema->objects['testcase']['id']    = array('type' => 'number', 'name' => $this->lang->testcase->id);
$schema->objects['testcase']['title'] = array('type' => 'string', 'name' => $this->lang->testcase->title);

$schema->objects['build'] = array();
$schema->objects['build']['id']   = array('type' => 'number', 'name' => $this->lang->dataview->id);
$schema->objects['build']['name'] = array('type' => 'string', 'name' => $this->lang->build->name);

$schema->objects['project'] = array();
$schema->objects['project']['id']   = array('type' => 'number', 'name' => $this->lang->project->id);
$schema->objects['project']['name'] = array('type' => 'string', 'name' => $this->lang->project->name);

$schema->objects['execution'] = array();
$schema->objects['execution']['id']   = array('type' => 'number', 'name' => $this->lang->execution->id);
$schema->objects['execution']['name'] = array('type' => 'string', 'name' => $this->lang->execution->name);

$schema->objects['casemodule'] = array();
$schema->objects['casemodule']['id']   = array('type' => 'number', 'name' => $this->lang->dataview->id);
$schema->objects['casemodule']['name'] = array('type' => 'string', 'name' => $this->lang->dataview->name);

$schema->objects['product'] = array();
$schema->objects['product']['id']   = array('type' => 'number', 'name' => $this->lang->product->id);
$schema->objects['product']['name'] = array('type' => 'string', 'name' => $this->lang->product->name);
