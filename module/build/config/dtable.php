<?php
global $lang;
$config->build->dtable = new stdclass();

$config->build->dtable->fieldList['id']['title'] = $lang->idAB;
$config->build->dtable->fieldList['id']['name']  = 'id';
$config->build->dtable->fieldList['id']['type']  = 'id';

$config->build->dtable->fieldList['name']['title']    = $lang->build->nameAB;
$config->build->dtable->fieldList['name']['name']     = 'name';
$config->build->dtable->fieldList['name']['link']     = helper::createLink('build', 'view', 'buildID={id}');
$config->build->dtable->fieldList['name']['type']     = 'title';
$config->build->dtable->fieldList['name']['sortType'] = false;

$config->build->dtable->fieldList['product']['title'] = $lang->build->product;
$config->build->dtable->fieldList['product']['name']  = 'productName';
$config->build->dtable->fieldList['product']['type']  = 'desc';
$config->build->dtable->fieldList['product']['group'] = 1;

$config->build->dtable->fieldList['branch']['title'] = $lang->build->branch;
$config->build->dtable->fieldList['branch']['name']  = 'branchName';
$config->build->dtable->fieldList['branch']['type']  = 'desc';
$config->build->dtable->fieldList['branch']['group'] = 1;

$config->build->dtable->fieldList['path']['title'] = $lang->build->url;
$config->build->dtable->fieldList['path']['name']  = 'path';
$config->build->dtable->fieldList['path']['type']  = 'desc';

$config->build->dtable->fieldList['builder']['title']    = $lang->build->builder;
$config->build->dtable->fieldList['builder']['name']     = 'builder';
$config->build->dtable->fieldList['builder']['type']     = 'user';
$config->build->dtable->fieldList['builder']['sortType'] = false;

$config->build->dtable->fieldList['date']['title']    = $lang->build->date;
$config->build->dtable->fieldList['date']['name']     = 'date';
$config->build->dtable->fieldList['date']['type']     = 'date';
$config->build->dtable->fieldList['date']['sortType'] = true;

$config->build->dtable->fieldList['actions']['title']      = $lang->actions;
$config->build->dtable->fieldList['actions']['name']       = 'actions';
$config->build->dtable->fieldList['actions']['type']       = 'actions';
$config->build->dtable->fieldList['actions']['actionsMap'] = $config->build->actionList;
