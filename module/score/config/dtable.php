<?php
global $lang;
$config->score->dtable = new stdclass();

$config->score->dtable->fieldList['module']['name']  = 'module';
$config->score->dtable->fieldList['module']['title'] = $lang->score->module;
$config->score->dtable->fieldList['module']['type']  = 'category';
$config->score->dtable->fieldList['module']['fixed'] = 'left';

$config->score->dtable->fieldList['method']['name']  = 'method';
$config->score->dtable->fieldList['method']['title'] = $lang->score->method;
$config->score->dtable->fieldList['method']['type']  = 'text';
$config->score->dtable->fieldList['method']['fixed'] = 'left';

$config->score->dtable->fieldList['times']['name']     = 'times';
$config->score->dtable->fieldList['times']['title']    = $lang->score->times;
$config->score->dtable->fieldList['times']['type']     = 'count';
$config->score->dtable->fieldList['times']['sortType'] = false;
$config->score->dtable->fieldList['times']['group']    = 2;

$config->score->dtable->fieldList['hour']['name']     = 'hour';
$config->score->dtable->fieldList['hour']['title']    = $lang->score->hour;
$config->score->dtable->fieldList['hour']['type']     = 'count';
$config->score->dtable->fieldList['hour']['sortType'] = false;
$config->score->dtable->fieldList['hour']['group']    = 2;

$config->score->dtable->fieldList['score']['name']     = 'score';
$config->score->dtable->fieldList['score']['title']    = $lang->score->score;
$config->score->dtable->fieldList['score']['type']     = 'count';
$config->score->dtable->fieldList['score']['sortType'] = false;
$config->score->dtable->fieldList['score']['group']    = 2;

$config->score->dtable->fieldList['desc']['name']  = 'desc';
$config->score->dtable->fieldList['desc']['title'] = $lang->score->desc;
$config->score->dtable->fieldList['desc']['type']  = 'desc';
