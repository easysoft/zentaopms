<?php
$this->app->loadLang('user');

$schema = new stdclass();

$schema->primaryTable = 'effort';

$schema->tables = array();
$schema->tables['effort'] = 'zt_effort';

$schema->fields = array();
$schema->fields['id']      = array('type' => 'number', 'name' => $this->lang->user->id);
$schema->fields['account'] = array('type' => 'user',   'name' => $this->lang->user->realname);
if(isset($this->lang->dataview->consumed)) $schema->fields['consumed'] = array('type' => 'number', 'name' => $this->lang->dataview->consumed);
