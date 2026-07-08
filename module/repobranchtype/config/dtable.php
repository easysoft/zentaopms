<?php
global $lang;

$config->repobranchtype->dtable = new stdclass();

$config->repobranchtype->dtable->fieldList['id']['name']     = 'id';
$config->repobranchtype->dtable->fieldList['id']['title']    = $lang->idAB;
$config->repobranchtype->dtable->fieldList['id']['type']     = 'id';
$config->repobranchtype->dtable->fieldList['id']['sortType'] = true;
$config->repobranchtype->dtable->fieldList['id']['width']    = 60;

$config->repobranchtype->dtable->fieldList['name']['title']    = $lang->repobranchtype->name;
$config->repobranchtype->dtable->fieldList['name']['type']     = 'title';
$config->repobranchtype->dtable->fieldList['name']['sortType'] = true;
$config->repobranchtype->dtable->fieldList['name']['width']    = 200;

$config->repobranchtype->dtable->fieldList['prefixesDisplay']['title']    = $lang->repobranchtype->prefixes;
$config->repobranchtype->dtable->fieldList['prefixesDisplay']['type']     = 'html';
$config->repobranchtype->dtable->fieldList['prefixesDisplay']['name']     = 'prefixesDisplay';
$config->repobranchtype->dtable->fieldList['prefixesDisplay']['width']    = 300;
$config->repobranchtype->dtable->fieldList['prefixesDisplay']['sortType'] = false;

$config->repobranchtype->dtable->fieldList['desc']['title']    = $lang->repobranchtype->desc;
$config->repobranchtype->dtable->fieldList['desc']['type']     = 'desc';
$config->repobranchtype->dtable->fieldList['desc']['sortType'] = false;

$config->repobranchtype->dtable->fieldList['actions']['name']  = 'actions';
$config->repobranchtype->dtable->fieldList['actions']['title'] = $lang->actions;
$config->repobranchtype->dtable->fieldList['actions']['type']  = 'actions';
$config->repobranchtype->dtable->fieldList['actions']['width'] = 100;
$config->repobranchtype->dtable->fieldList['actions']['menu']  = array('rule', 'edit', 'delete');
$config->repobranchtype->dtable->fieldList['actions']['list']  = $config->repobranchtype->actionList;
