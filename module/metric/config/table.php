<?php
global $lang;
$config->metric->dtable = new stdclass();

$config->metric->dtable->definition = new stdclass();
$config->metric->dtable->definition->fieldList['id']['title']    = $lang->idAB;
$config->metric->dtable->definition->fieldList['id']['type']     = 'checkID';
$config->metric->dtable->definition->fieldList['id']['flex']     = '1';
$config->metric->dtable->definition->fieldList['id']['sortType'] = true;
$config->metric->dtable->definition->fieldList['id']['required'] = true;
$config->metric->dtable->definition->fieldList['id']['group']    = 1;

$config->metric->dtable->definition->fieldList['name']['title']    = $lang->metric->name;
$config->metric->dtable->definition->fieldList['name']['type']     = 'title';
$config->metric->dtable->definition->fieldList['name']['link']     = array('module' => 'metric', 'method' => 'view', 'params' => 'metricID={id}');
$config->metric->dtable->definition->fieldList['name']['minWidth'] = '300';
$config->metric->dtable->definition->fieldList['name']['required'] = true;
$config->metric->dtable->definition->fieldList['name']['group']    = 1;

$config->metric->dtable->definition->fieldList['stage']['title']     = $lang->metric->stage;
$config->metric->dtable->definition->fieldList['stage']['type']      = 'status';
$config->metric->dtable->definition->fieldList['stage']['statusMap'] = $lang->metric->stageList;
$config->metric->dtable->definition->fieldList['stage']['minWidth']  = '80';
$config->metric->dtable->definition->fieldList['stage']['required']  = true;
$config->metric->dtable->definition->fieldList['stage']['group']     = 2;

$config->metric->dtable->definition->fieldList['scope']['title']    = $lang->metric->scope;
$config->metric->dtable->definition->fieldList['scope']['type']     = 'category';
$config->metric->dtable->definition->fieldList['scope']['map']      = $lang->metric->scopeList;
$config->metric->dtable->definition->fieldList['scope']['minWidth'] = '80';
$config->metric->dtable->definition->fieldList['scope']['required'] = true;
$config->metric->dtable->definition->fieldList['scope']['group']    = 3;

$config->metric->dtable->definition->fieldList['object']['title']    = $lang->metric->object;
$config->metric->dtable->definition->fieldList['object']['type']     = 'category';
$config->metric->dtable->definition->fieldList['object']['map']      = $lang->metric->objectList;
$config->metric->dtable->definition->fieldList['object']['minWidth'] = '80';
$config->metric->dtable->definition->fieldList['object']['required'] = true;
$config->metric->dtable->definition->fieldList['object']['group']    = 3;

$config->metric->dtable->definition->fieldList['purpose']['title']    = $lang->metric->purpose;
$config->metric->dtable->definition->fieldList['purpose']['type']     = 'category';
$config->metric->dtable->definition->fieldList['purpose']['map']      = $lang->metric->purposeList;
$config->metric->dtable->definition->fieldList['purpose']['minWidth'] = '80';
$config->metric->dtable->definition->fieldList['purpose']['required'] = true;
$config->metric->dtable->definition->fieldList['purpose']['group']    = 3;

$config->metric->dtable->definition->fieldList['unit']['title']    = $lang->metric->unit;
$config->metric->dtable->definition->fieldList['unit']['type']     = 'text';
$config->metric->dtable->definition->fieldList['unit']['map']      = $lang->metric->unitList;
$config->metric->dtable->definition->fieldList['unit']['minWidth'] = '40';
$config->metric->dtable->definition->fieldList['unit']['required'] = true;
$config->metric->dtable->definition->fieldList['unit']['group']    = 3;

$config->metric->dtable->definition->fieldList['desc']['title']    = $lang->metric->desc;
$config->metric->dtable->definition->fieldList['desc']['type']     = 'text';
$config->metric->dtable->definition->fieldList['desc']['minWidth'] = '400';
$config->metric->dtable->definition->fieldList['desc']['required'] = true;
$config->metric->dtable->definition->fieldList['desc']['group']    = 4;

$config->metric->dtable->definition->fieldList['createdBy']['title']    = $lang->metric->createdBy;
$config->metric->dtable->definition->fieldList['createdBy']['type']     = 'text';
$config->metric->dtable->definition->fieldList['createdBy']['minWidth'] = '100';
$config->metric->dtable->definition->fieldList['createdBy']['required'] = true;
$config->metric->dtable->definition->fieldList['createdBy']['group']    = 4;

$config->metric->dtable->definition->fieldList['actions']['name']     = 'actions';
$config->metric->dtable->definition->fieldList['actions']['title']    = $lang->actions;
$config->metric->dtable->definition->fieldList['actions']['type']     = 'actions';
$config->metric->dtable->definition->fieldList['actions']['sortType'] = false;
$config->metric->dtable->definition->fieldList['actions']['list']     = $config->metric->actionList;
$config->metric->dtable->definition->fieldList['actions']['fixed']    = 'right';
$config->metric->dtable->definition->fieldList['actions']['menu']     = array('edit', 'implement', 'delist', 'delete');
