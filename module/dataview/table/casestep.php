<?php
$this->app->loadLang('testcase');

$casestepLang = new stdclass();
$casestepLang->type = '步骤类型';
$casestepLang->typeList['step']  = '步骤';
$casestepLang->typeList['group'] = '分组';

$schema = new stdclass();

$schema->primaryTable = 'casestep';

$schema->tables = array();
$schema->tables['casestep'] = 'zt_casestep';
$schema->tables['testcase'] = 'zt_case';

$schema->joins = array();
$schema->joins['testcase'] = 'testcase.id = casestep.`case`';

$schema->fields = array();
$schema->fields['case']    = array('type' => 'object', 'name' => $this->lang->testcase->common, 'object' => 'testcase', 'show' => 'testcase.title');
$schema->fields['type']    = array('type' => 'option', 'name' => $casestepLang->type, 'options' => $casestepLang->typeList);
$schema->fields['desc']    = array('type' => 'string', 'name' => $this->lang->testcase->desc);
$schema->fields['expect']  = array('type' => 'string', 'name' => $this->lang->testcase->expect);
$schema->fields['version'] = array('type' => 'number',    'name' => $this->lang->testcase->version);

$schema->objects = array();
$schema->objects['testcase'] = array();
$schema->objects['testcase']['id']    = array('type' => 'number', 'name' => $this->lang->testcase->id);
$schema->objects['testcase']['title'] = array('type' => 'string', 'name' => $this->lang->testcase->title);
