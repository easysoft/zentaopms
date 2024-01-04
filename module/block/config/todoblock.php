<?php
global $lang, $app;
$app->loadLang('todo');
$app->loadLang('my');
$app->loadModuleConfig('my');

$config->block->todo = new stdclass();
$config->block->todo->dtable = new stdclass();
$config->block->todo->dtable->fieldList = array();
$config->block->todo->dtable->fieldList['id']['name']  = 'id';
$config->block->todo->dtable->fieldList['id']['title'] = $lang->idAB;
$config->block->todo->dtable->fieldList['id']['type']  = 'id';
$config->block->todo->dtable->fieldList['id']['fixed'] = 'left';
$config->block->todo->dtable->fieldList['id']['sort']  = 'number';

$config->block->todo->dtable->fieldList['name']          = $config->my->todo->dtable->fieldList['name'];
$config->block->todo->dtable->fieldList['name']['fixed'] = 'left';
$config->block->todo->dtable->fieldList['name']['sort']  = true;

$config->block->todo->dtable->fieldList['pri']          = $config->my->todo->dtable->fieldList['pri'];
$config->block->todo->dtable->fieldList['pri']['width'] = '60px';
$config->block->todo->dtable->fieldList['pri']['sort']  = true;

$config->block->todo->dtable->fieldList['status'] = $config->my->todo->dtable->fieldList['status'];
$config->block->todo->dtable->fieldList['type']   = $config->my->todo->dtable->fieldList['type'];
$config->block->todo->dtable->fieldList['date']   = $config->my->todo->dtable->fieldList['date'];
$config->block->todo->dtable->fieldList['begin']  = $config->my->todo->dtable->fieldList['begin'];
$config->block->todo->dtable->fieldList['end']    = $config->my->todo->dtable->fieldList['end'];

$config->block->todo->dtable->fieldList['status']['sort'] = true;
$config->block->todo->dtable->fieldList['type']['sort']   = true;
$config->block->todo->dtable->fieldList['date']['sort']   = 'date';
$config->block->todo->dtable->fieldList['begin']['sort']  = true;
$config->block->todo->dtable->fieldList['end']['sort']    = true;

unset($config->block->todo->dtable->fieldList['pri']['group']);
unset($config->block->todo->dtable->fieldList['status']['group']);
unset($config->block->todo->dtable->fieldList['type']['group']);
unset($config->block->todo->dtable->fieldList['date']['group']);
unset($config->block->todo->dtable->fieldList['begin']['group']);
unset($config->block->todo->dtable->fieldList['end']['group']);
unset($config->block->todo->dtable->fieldList['status']['sortType']);
unset($config->block->todo->dtable->fieldList['type']['sortType']);
unset($config->block->todo->dtable->fieldList['date']['sortType']);
unset($config->block->todo->dtable->fieldList['begin']['sortType']);
unset($config->block->todo->dtable->fieldList['end']['sortType']);

$config->block->todo->dtable->short = new stdclass();
$config->block->todo->dtable->short->fieldList['id']     = $config->block->todo->dtable->fieldList['id'];
$config->block->todo->dtable->short->fieldList['name']   = $config->block->todo->dtable->fieldList['name'];
$config->block->todo->dtable->short->fieldList['status'] = $config->block->todo->dtable->fieldList['status'];
