<?php
global $lang;
$config->backup->dtable = new stdclass();
$config->backup->dtable->fieldList['time']['title'] = $lang->backup->time;
$config->backup->dtable->fieldList['time']['name']  = 'time';
$config->backup->dtable->fieldList['time']['type']  = 'datetime';
$config->backup->dtable->fieldList['time']['fixed'] = 'left';
$config->backup->dtable->fieldList['time']['show']  = true;
$config->backup->dtable->fieldList['time']['group'] = 1;

$config->backup->dtable->fieldList['file']['title'] = $lang->backup->files;
$config->backup->dtable->fieldList['file']['name']  = 'file';
$config->backup->dtable->fieldList['file']['type']  = 'title';
$config->backup->dtable->fieldList['file']['fixed'] = 'left';
$config->backup->dtable->fieldList['file']['show']  = true;
$config->backup->dtable->fieldList['file']['group'] = 1;

$config->backup->dtable->fieldList['allCount']['title'] = $lang->backup->allCount;
$config->backup->dtable->fieldList['allCount']['name']  = 'allCount';
$config->backup->dtable->fieldList['allCount']['type']  = 'count';
$config->backup->dtable->fieldList['allCount']['show']  = true;
$config->backup->dtable->fieldList['allCount']['group'] = 2;

$config->backup->dtable->fieldList['count']['title'] = $lang->backup->count;
$config->backup->dtable->fieldList['count']['name']  = 'count';
$config->backup->dtable->fieldList['count']['type']  = 'count';
$config->backup->dtable->fieldList['count']['show']  = true;
$config->backup->dtable->fieldList['count']['group'] = 3;

$config->backup->dtable->fieldList['size']['title'] = $lang->backup->size;
$config->backup->dtable->fieldList['size']['name']  = 'size';
$config->backup->dtable->fieldList['size']['type']  = 'text';
$config->backup->dtable->fieldList['size']['show']  = true;
$config->backup->dtable->fieldList['size']['group'] = 4;

$config->backup->dtable->fieldList['status']['title'] = $lang->backup->status;
$config->backup->dtable->fieldList['status']['name']  = 'status';
$config->backup->dtable->fieldList['status']['type']  = 'text';
$config->backup->dtable->fieldList['status']['show']  = true;
$config->backup->dtable->fieldList['status']['group'] = 5;

$config->backup->dtable->fieldList['actions']['title']    = $lang->actions;
$config->backup->dtable->fieldList['actions']['name']     = 'actions';
$config->backup->dtable->fieldList['actions']['type']     = 'actions';
$config->backup->dtable->fieldList['actions']['width']    = 'auto';
$config->backup->dtable->fieldList['actions']['fixed']    = 'right';
$config->backup->dtable->fieldList['actions']['list']     = $config->backup->actionList;
$config->backup->dtable->fieldList['actions']['menu']     = $config->backup->menu ?? array_keys($config->backup->actionList);
$config->backup->dtable->fieldList['actions']['required'] = true;
$config->backup->dtable->fieldList['actions']['group']    = 6;
