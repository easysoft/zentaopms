<?php
$config->my->score         = new stdclass();
$config->my->score->dtable = new stdclass();

$config->my->score->dtable->fieldList['time']['name']     = 'time';
$config->my->score->dtable->fieldList['time']['title']    = $lang->score->time;
$config->my->score->dtable->fieldList['time']['type']     = 'datetime';
$config->my->score->dtable->fieldList['time']['fixed']    = 'left';
$config->my->score->dtable->fieldList['time']['sortType'] = false;
$config->my->score->dtable->fieldList['time']['align']    = 'left';

$config->my->score->dtable->fieldList['module']['name']     = 'module';
$config->my->score->dtable->fieldList['module']['title']    = $lang->score->module;
$config->my->score->dtable->fieldList['module']['type']     = 'category';
$config->my->score->dtable->fieldList['module']['map']      = $lang->score->modules;
$config->my->score->dtable->fieldList['module']['sortType'] = false;
$config->my->score->dtable->fieldList['module']['align']    = 'left';

$config->my->score->dtable->fieldList['method']['name']     = 'method';
$config->my->score->dtable->fieldList['method']['title']    = $lang->score->method;
$config->my->score->dtable->fieldList['method']['type']     = 'text';
$config->my->score->dtable->fieldList['method']['map']      = $lang->score->methods;
$config->my->score->dtable->fieldList['method']['sortType'] = false;

$config->my->score->dtable->fieldList['before']['name']     = 'before';
$config->my->score->dtable->fieldList['before']['title']    = $lang->score->before;
$config->my->score->dtable->fieldList['before']['type']     = 'count';
$config->my->score->dtable->fieldList['before']['sortType'] = false;
$config->my->score->dtable->fieldList['before']['align']    = 'left';

$config->my->score->dtable->fieldList['score']['name']     = 'score';
$config->my->score->dtable->fieldList['score']['title']    = $lang->score->score;
$config->my->score->dtable->fieldList['score']['type']     = 'number';
$config->my->score->dtable->fieldList['score']['sortType'] = false;
$config->my->score->dtable->fieldList['score']['align']    = 'left';

$config->my->score->dtable->fieldList['after']['name']     = 'after';
$config->my->score->dtable->fieldList['after']['title']    = $lang->score->after;
$config->my->score->dtable->fieldList['after']['type']     = 'count';
$config->my->score->dtable->fieldList['after']['sortType'] = false;
$config->my->score->dtable->fieldList['after']['align']    = 'left';

$config->my->score->dtable->fieldList['desc']['name']  = 'desc';
$config->my->score->dtable->fieldList['desc']['title'] = $lang->score->desc;
$config->my->score->dtable->fieldList['desc']['type']  = 'desc';
