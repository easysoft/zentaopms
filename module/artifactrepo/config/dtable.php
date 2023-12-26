<?php
global $lang, $app;
$app->loadLang('repo');

$config->artifactrepo->dtable = new stdclass();

$config->artifactrepo->dtable->fieldList['name']['title'] = $lang->artifactrepo->name;
$config->artifactrepo->dtable->fieldList['name']['type']  = 'title';
$config->artifactrepo->dtable->fieldList['name']['width'] = '300';

$config->artifactrepo->dtable->fieldList['format']['title']    = $lang->artifactrepo->format;
$config->artifactrepo->dtable->fieldList['format']['sortType'] = true;

$config->artifactrepo->dtable->fieldList['products']['title'] = $lang->repo->product;
$config->artifactrepo->dtable->fieldList['products']['name']  = 'productNames';
$config->artifactrepo->dtable->fieldList['products']['width'] = '300';
$config->artifactrepo->dtable->fieldList['products']['hint']  = true;

$config->artifactrepo->dtable->fieldList['type']['title']    = $lang->artifactrepo->type;
$config->artifactrepo->dtable->fieldList['type']['sortType'] = true;

$config->artifactrepo->dtable->fieldList['status']['title']    = $lang->artifactrepo->status;
$config->artifactrepo->dtable->fieldList['status']['sortType'] = true;
$config->artifactrepo->dtable->fieldList['status']['map']      = $lang->artifactrepo->statusList;

$config->artifactrepo->dtable->fieldList['url']['title'] = $lang->artifactrepo->url;
$config->artifactrepo->dtable->fieldList['url']['width'] = '400';
$config->artifactrepo->dtable->fieldList['url']['hint']  = true;

$config->artifactrepo->dtable->fieldList['actions']['type'] = 'actions';
$config->artifactrepo->dtable->fieldList['actions']['menu'] = array('edit', 'delete');

$config->artifactrepo->dtable->fieldList['actions']['list']['edit']['icon'] = 'edit';
$config->artifactrepo->dtable->fieldList['actions']['list']['edit']['hint'] = $lang->edit;
$config->artifactrepo->dtable->fieldList['actions']['list']['edit']['url']  = array('module' => 'artifactrepo', 'method' => 'edit', 'params' => 'id={id}');

$config->artifactrepo->dtable->fieldList['actions']['list']['delete']['icon']         = 'trash';
$config->artifactrepo->dtable->fieldList['actions']['list']['delete']['hint']         = $lang->artifactrepo->delete;
$config->artifactrepo->dtable->fieldList['actions']['list']['delete']['className']    = 'ajax-submit';
$config->artifactrepo->dtable->fieldList['actions']['list']['delete']['data-confirm'] = $lang->artifactrepo->confirmDelete;
$config->artifactrepo->dtable->fieldList['actions']['list']['delete']['url']          = array('module' => 'artifactrepo', 'method' => 'delete', 'params' => 'id={id}');
