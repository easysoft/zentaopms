<?php
global $lang, $app;
$app->loadLang('bug');

$config->block->bug = new stdclass();
$config->block->bug->dtable = new stdclass();
$config->block->bug->dtable->fieldList = array();
$config->block->bug->dtable->fieldList['id']['name']  = 'id';
$config->block->bug->dtable->fieldList['id']['title'] = $lang->idAB;
$config->block->bug->dtable->fieldList['id']['type']  = 'id';
$config->block->bug->dtable->fieldList['id']['fixed'] = false;
$config->block->bug->dtable->fieldList['id']['sort']  = 'number';
$config->block->bug->dtable->fieldList['id']['fixed'] = 'left';

$config->block->bug->dtable->fieldList['title']['name']        = 'title';
$config->block->bug->dtable->fieldList['title']['title']       = $lang->bug->title;
$config->block->bug->dtable->fieldList['title']['link']        = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');
$config->block->bug->dtable->fieldList['title']['type']        = 'title';
$config->block->bug->dtable->fieldList['title']['fixed']       = false;
$config->block->bug->dtable->fieldList['title']['sort']        = true;
$config->block->bug->dtable->fieldList['title']['fixed']       = 'left';

$config->block->bug->dtable->fieldList['severity']['name']  = 'severity';
$config->block->bug->dtable->fieldList['severity']['title'] = $lang->bug->abbr->severity;
$config->block->bug->dtable->fieldList['severity']['type']  = 'severity';
$config->block->bug->dtable->fieldList['severity']['sort']  = true;

$config->block->bug->dtable->fieldList['pri']['name']  = 'pri';
$config->block->bug->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->block->bug->dtable->fieldList['pri']['type']  = 'pri';
$config->block->bug->dtable->fieldList['pri']['sort']  = true;

$config->block->bug->dtable->fieldList['status']['name']      = 'status';
$config->block->bug->dtable->fieldList['status']['title']     = $lang->bug->abbr->status;
$config->block->bug->dtable->fieldList['status']['type']      = 'status';
$config->block->bug->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;
$config->block->bug->dtable->fieldList['status']['sort']  = true;

$config->block->bug->dtable->fieldList['confirmed']['name']  = 'confirmed';
$config->block->bug->dtable->fieldList['confirmed']['title'] = $lang->bug->abbr->confirmed;
$config->block->bug->dtable->fieldList['confirmed']['type']  = 'category';
$config->block->bug->dtable->fieldList['confirmed']['map']   = $lang->bug->confirmedList;
$config->block->bug->dtable->fieldList['confirmed']['sort']  = true;

$config->block->bug->dtable->fieldList['deadline']['name']  = 'deadline';
$config->block->bug->dtable->fieldList['deadline']['title'] = $lang->bug->deadline;
$config->block->bug->dtable->fieldList['deadline']['type']  = 'date';
$config->block->bug->dtable->fieldList['deadline']['sort']  = 'date';

$config->block->bug->dtable->short = new stdclass();
$config->block->bug->dtable->short->fieldList['id']     = $config->block->bug->dtable->fieldList['id'];
$config->block->bug->dtable->short->fieldList['title']  = $config->block->bug->dtable->fieldList['title'];
$config->block->bug->dtable->short->fieldList['status'] = $config->block->bug->dtable->fieldList['status'];
