<?php
$this->app->loadLang('account');

$schema = new stdclass();

$schema->primaryTable = 'effort';

$schema->tables = array();
$schema->tables['effort'] = 'zt_effort';

$schema->fields = array();
$schema->fields['id']      = array('type' => 'number', 'name' => $this->lang->account->id);
$schema->fields['account'] = array('type' => 'user',   'name' => $this->lang->account->name);

