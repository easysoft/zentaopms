<?php
$this->app->loadLang('action');

$schema = new stdclass();

$schema->primaryTable = 'action';

$schema->tables = array();
$schema->tables['action'] = 'zt_action';

$schema->fields = array();
$schema->fields['id']    = array('type' => 'number', 'name' => $this->lang->action->id);
$schema->fields['actor'] = array('type' => 'user',   'name' => $this->lang->action->actor);

