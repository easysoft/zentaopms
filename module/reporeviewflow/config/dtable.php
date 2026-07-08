<?php
global $lang;
$config->reporeviewflow->dtable = new stdclass();

$config->reporeviewflow->dtable = new stdclass();
$config->reporeviewflow->dtable->fieldList['id']['name']     = 'id';
$config->reporeviewflow->dtable->fieldList['id']['title']    = $lang->idAB;
$config->reporeviewflow->dtable->fieldList['id']['type']     = 'id';
$config->reporeviewflow->dtable->fieldList['id']['sortType'] = false;

$config->reporeviewflow->dtable->fieldList['name']['title']    = $lang->reporeviewflow->flowName;
$config->reporeviewflow->dtable->fieldList['name']['type']     = 'shortTitle';
$config->reporeviewflow->dtable->fieldList['name']['name']     = 'name';
$config->reporeviewflow->dtable->fieldList['name']['sortType'] = false;
$config->reporeviewflow->dtable->fieldList['name']['group']    = 1;

$config->reporeviewflow->dtable->fieldList['status']['title']     = $lang->reporeviewflow->status;
$config->reporeviewflow->dtable->fieldList['status']['type']      = 'status';
$config->reporeviewflow->dtable->fieldList['status']['statusMap'] = $lang->reporeviewflow->flowStatusList;
$config->reporeviewflow->dtable->fieldList['status']['sortType']  = false;
$config->reporeviewflow->dtable->fieldList['status']['group']     = 2;

$config->reporeviewflow->dtable->fieldList['desc']['title']    = $lang->reporeviewflow->desc;
$config->reporeviewflow->dtable->fieldList['desc']['type']     = 'desc';
$config->reporeviewflow->dtable->fieldList['desc']['sortType'] = false;
$config->reporeviewflow->dtable->fieldList['desc']['group']    = 4;

$config->reporeviewflow->dtable->fieldList['actions']['name']  = 'actions';
$config->reporeviewflow->dtable->fieldList['actions']['title'] = $lang->actions;
$config->reporeviewflow->dtable->fieldList['actions']['type']  = 'actions';
$config->reporeviewflow->dtable->fieldList['actions']['width'] = 100;
$config->reporeviewflow->dtable->fieldList['actions']['menu']  = array('enable|disable', 'edit', 'delete');
$config->reporeviewflow->dtable->fieldList['actions']['list']  = $config->reporeviewflow->actionList;
