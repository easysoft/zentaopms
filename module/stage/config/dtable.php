<?php
global $lang;
$config->stage->dtable = new stdclass();
$config->stage->dtable->fieldList['id']['type'] = 'id';

$config->stage->dtable->fieldList['sort']['title'] = $lang->stage->order;
$config->stage->dtable->fieldList['sort']['fixed'] = 'left';
$config->stage->dtable->fieldList['sort']['align'] = 'center';
$config->stage->dtable->fieldList['sort']['group'] = 1;

$config->stage->dtable->fieldList['name']['type'] = 'title';

$config->stage->dtable->fieldList['type']['type']      = 'status';
$config->stage->dtable->fieldList['type']['statusMap'] = $lang->stage->typeList;
$config->stage->dtable->fieldList['type']['group']     = 2;

$config->stage->dtable->fieldList['actions']['type'] = 'actions';
$config->stage->dtable->fieldList['actions']['menu'] = array('edit', 'delete');
$config->stage->dtable->fieldList['actions']['list'] = $config->stage->actionList;
