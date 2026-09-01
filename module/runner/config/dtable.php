<?php
$config->runner->dtable = new stdclass();

$config->runner->dtable->fieldList['id']['title'] = 'ID';
$config->runner->dtable->fieldList['id']['type']  = 'id';

$config->runner->dtable->fieldList['name']['title']       = $lang->runner->name;
$config->runner->dtable->fieldList['name']['name']        = 'name';
$config->runner->dtable->fieldList['name']['fixed']       = 'left';
$config->runner->dtable->fieldList['name']['type']        = 'shortTitle';
$config->runner->dtable->fieldList['name']['sortType']    = false;
$config->runner->dtable->fieldList['name']['hint']        = true;
$config->runner->dtable->fieldList['name']['show']        = true;
$config->runner->dtable->fieldList['name']['required']    = true;
$config->runner->dtable->fieldList['name']['checkbox']    = false;

$config->runner->dtable->fieldList['labels']['title']    = $lang->runner->labels;
$config->runner->dtable->fieldList['labels']['type']     = 'text';
$config->runner->dtable->fieldList['labels']['sortType'] = false;
$config->runner->dtable->fieldList['labels']['hint']     = true;
$config->runner->dtable->fieldList['labels']['show']     = true;
$config->runner->dtable->fieldList['labels']['width']    = 120;

$config->runner->dtable->fieldList['runnerStatus']['title']     = $lang->runner->status;
$config->runner->dtable->fieldList['runnerStatus']['name']      = 'runnerStatus';
$config->runner->dtable->fieldList['runnerStatus']['type']      = 'status';
$config->runner->dtable->fieldList['runnerStatus']['statusMap'] = $lang->runner->statusList;
$config->runner->dtable->fieldList['runnerStatus']['show']      = true;
$config->runner->dtable->fieldList['runnerStatus']['sortType']  = false;

$config->runner->dtable->fieldList['os']['title']    = $lang->runner->plat;
$config->runner->dtable->fieldList['os']['name']     = 'os';
$config->runner->dtable->fieldList['os']['sortType'] = true;
$config->runner->dtable->fieldList['os']['hint']     = true;
$config->runner->dtable->fieldList['os']['show']     = true;
$config->runner->dtable->fieldList['os']['map']      = $lang->runner->osList;

$config->runner->dtable->fieldList['arch']['title']    = $lang->runner->arch;
$config->runner->dtable->fieldList['arch']['name']     = 'arch';
$config->runner->dtable->fieldList['arch']['sortType'] = true;
$config->runner->dtable->fieldList['arch']['hint']     = true;
$config->runner->dtable->fieldList['arch']['show']     = true;
$config->runner->dtable->fieldList['arch']['map']      = $lang->runner->archList;

$config->runner->dtable->fieldList['runtime']['title']    = $lang->runner->runtime;
$config->runner->dtable->fieldList['runtime']['name']     = 'runtime';
$config->runner->dtable->fieldList['runtime']['sortType'] = true;
$config->runner->dtable->fieldList['runtime']['hint']     = true;
$config->runner->dtable->fieldList['runtime']['show']     = true;
$config->runner->dtable->fieldList['runtime']['type']     = 'text';

$config->runner->dtable->fieldList['version']['title']    = $lang->runner->version;
$config->runner->dtable->fieldList['version']['name']     = 'version';
$config->runner->dtable->fieldList['version']['sortType'] = false;
$config->runner->dtable->fieldList['version']['hint']     = true;
$config->runner->dtable->fieldList['version']['show']     = true;

$config->runner->dtable->fieldList['ip']['title']    = $lang->runner->ip;
$config->runner->dtable->fieldList['ip']['name']     = 'ip';
$config->runner->dtable->fieldList['ip']['sortType'] = false;
$config->runner->dtable->fieldList['ip']['hint']     = true;
$config->runner->dtable->fieldList['ip']['show']     = true;
$config->runner->dtable->fieldList['ip']['type']     = 'text';

$config->runner->dtable->fieldList['actions']['name']  = 'actions';
$config->runner->dtable->fieldList['actions']['title'] = $lang->actions;
$config->runner->dtable->fieldList['actions']['type']  = 'actions';
$config->runner->dtable->fieldList['actions']['width'] = '80';
$config->runner->dtable->fieldList['actions']['menu']  = array('enable|disable', 'edit', 'delete');
$config->runner->dtable->fieldList['actions']['list']  = $config->runner->actionList;
