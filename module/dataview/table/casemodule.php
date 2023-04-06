<?php
$this->app->loadLang('tree');
$this->app->loadLang('dataview');

$schema = new stdclass();

$schema->primaryTable = 'casemodule';

$schema->tables = array();
$schema->tables['casemodule'] = 'zt_module';

$schema->joins = array();

$schema->fields = array();
$schema->fields['id']   = array('type' => 'number', 'name' => $this->lang->dataview->id);
$schema->fields['name'] = array('type' => 'string', 'name' => $this->lang->tree->module);

$schema->objects = array();
