<?php
$config->my->testcase = new stdclass();
$config->my->testcase->dtable = new stdclass();

$config->my->testcase->dtable->fieldList['id']['name']     = 'id';
$config->my->testcase->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->testcase->dtable->fieldList['id']['type']     = 'checkID';
$config->my->testcase->dtable->fieldList['id']['fixed']    = 'left';
$config->my->testcase->dtable->fieldList['id']['sortType'] = true;

$config->my->testcase->dtable->fieldList['title']['name']     = 'title';
$config->my->testcase->dtable->fieldList['title']['title']    = $lang->testcase->title;
$config->my->testcase->dtable->fieldList['title']['type']     = 'title';
$config->my->testcase->dtable->fieldList['title']['link']     = array('module' => 'testcase', 'method' => 'view', 'params' => 'caseID={id}&version={version}');
$config->my->testcase->dtable->fieldList['title']['styleMap'] = array('--color-link' => 'color');
$config->my->testcase->dtable->fieldList['title']['fixed']    = 'left';
$config->my->testcase->dtable->fieldList['title']['sortType'] = true;

$config->my->testcase->dtable->fieldList['pri']    = $config->testcase->dtable->fieldList['pri'];
$config->my->testcase->dtable->fieldList['scene']  = $config->testcase->dtable->fieldList['scene'];
$config->my->testcase->dtable->fieldList['type']   = $config->testcase->dtable->fieldList['type'];
$config->my->testcase->dtable->fieldList['status'] = $config->testcase->dtable->fieldList['status'];

$config->my->testcase->dtable->fieldList['product']['title']    = $lang->testcase->product;
$config->my->testcase->dtable->fieldList['product']['type']     = 'text';
$config->my->testcase->dtable->fieldList['product']['sortType'] = true;
$config->my->testcase->dtable->fieldList['product']['show']     = true;

$config->my->testcase->dtable->fieldList['taskName']['name']     = 'taskName';
$config->my->testcase->dtable->fieldList['taskName']['title']    = $lang->testtask->common;
$config->my->testcase->dtable->fieldList['taskName']['type']     = 'text';
$config->my->testcase->dtable->fieldList['taskName']['group']    = 'testtask';
$config->my->testcase->dtable->fieldList['taskName']['sortType'] = true;

$config->my->testcase->dtable->fieldList['stage']         = $config->testcase->dtable->fieldList['stage'];
$config->my->testcase->dtable->fieldList['precondition']  = $config->testcase->dtable->fieldList['precondition'];
$config->my->testcase->dtable->fieldList['story']         = $config->testcase->dtable->fieldList['story'];
$config->my->testcase->dtable->fieldList['keywords']      = $config->testcase->dtable->fieldList['keywords'];
$config->my->testcase->dtable->fieldList['openedBy']      = $config->testcase->dtable->fieldList['openedBy'];
$config->my->testcase->dtable->fieldList['openedDate']    = $config->testcase->dtable->fieldList['openedDate'];
$config->my->testcase->dtable->fieldList['reviewedBy']    = $config->testcase->dtable->fieldList['reviewedBy'];
$config->my->testcase->dtable->fieldList['reviewedDate']  = $config->testcase->dtable->fieldList['reviewedDate'];
$config->my->testcase->dtable->fieldList['lastRunner']    = $config->testcase->dtable->fieldList['lastRunner'];
$config->my->testcase->dtable->fieldList['lastRunDate']   = $config->testcase->dtable->fieldList['lastRunDate'];
$config->my->testcase->dtable->fieldList['lastRunResult'] = $config->testcase->dtable->fieldList['lastRunResult'];
$config->my->testcase->dtable->fieldList['bugs']          = $config->testcase->dtable->fieldList['bugs'];
$config->my->testcase->dtable->fieldList['results']       = $config->testcase->dtable->fieldList['results'];
$config->my->testcase->dtable->fieldList['stepNumber']    = $config->testcase->dtable->fieldList['stepNumber'];
if($config->edition != 'open')
{
    $config->my->testcase->dtable->fieldList['relatedObject'] = $config->testcase->dtable->fieldList['relatedObject'];
    unset($config->my->testcase->dtable->fieldList['relatedObject']['show']);
}
$config->my->testcase->dtable->fieldList['version']        = $config->testcase->dtable->fieldList['version'];
$config->my->testcase->dtable->fieldList['lastEditedBy']   = $config->testcase->dtable->fieldList['lastEditedBy'];
$config->my->testcase->dtable->fieldList['lastEditedDate'] = $config->testcase->dtable->fieldList['lastEditedDate'];
$config->my->testcase->dtable->fieldList['actions']        = $config->testcase->dtable->fieldList['actions'];

$config->my->testcase->dtable->fieldList['actions']['list']['edit']['data-toggle']   = 'modal';
$config->my->testcase->dtable->fieldList['actions']['list']['edit']['data-size']     = 'lg';
$config->my->testcase->dtable->fieldList['actions']['list']['create']['data-toggle'] = 'modal';
$config->my->testcase->dtable->fieldList['actions']['list']['create']['data-size']   = 'lg';
$config->my->testcase->dtable->fieldList['actions']['menu'] = array('runCase', 'runResult', 'edit', 'createBug', 'create');
