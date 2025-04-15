<?php
$config->deliverable->dtable = new stdclass();

$config->deliverable->dtable->fieldList['id']['title']    = $lang->idAB;
$config->deliverable->dtable->fieldList['id']['name']     = 'id';
$config->deliverable->dtable->fieldList['id']['type']     = 'checkID';
$config->deliverable->dtable->fieldList['id']['sortType'] = 'desc';
$config->deliverable->dtable->fieldList['id']['checkbox'] = true;
$config->deliverable->dtable->fieldList['id']['width']    = '80';
$config->deliverable->dtable->fieldList['id']['required'] = true;

$config->deliverable->dtable->fieldList['name']['title']    = $lang->deliverable->name;
$config->deliverable->dtable->fieldList['name']['name']     = 'name';
$config->deliverable->dtable->fieldList['name']['type']     = 'title';
$config->deliverable->dtable->fieldList['name']['sortType'] = true;

$config->deliverable->dtable->fieldList['module']['title']    = $lang->deliverable->module;
$config->deliverable->dtable->fieldList['module']['name']     = 'module';
$config->deliverable->dtable->fieldList['module']['type']     = 'category';
$config->deliverable->dtable->fieldList['module']['sortType'] = true;

$config->deliverable->dtable->fieldList['method']['title']    = $lang->deliverable->method;
$config->deliverable->dtable->fieldList['method']['name']     = 'method';
$config->deliverable->dtable->fieldList['method']['type']     = 'category';
$config->deliverable->dtable->fieldList['method']['sortType'] = true;

$config->deliverable->dtable->fieldList['model']['title']    = $lang->deliverable->model;
$config->deliverable->dtable->fieldList['model']['name']     = 'model';
$config->deliverable->dtable->fieldList['model']['type']     = 'text';
$config->deliverable->dtable->fieldList['model']['sortType'] = true;

$config->deliverable->dtable->fieldList['createdBy']['title']    = $lang->deliverable->createdBy;
$config->deliverable->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->deliverable->dtable->fieldList['createdBy']['type']     = 'user';
$config->deliverable->dtable->fieldList['createdBy']['sortType'] = true;

$config->deliverable->dtable->fieldList['createdDate']['title']    = $lang->deliverable->createdDate;
$config->deliverable->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->deliverable->dtable->fieldList['createdDate']['type']     = 'date';
$config->deliverable->dtable->fieldList['createdDate']['sortType'] = true;
