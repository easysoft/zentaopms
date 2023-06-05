<?php
$config->testreport->dtable = new stdclass();

$config->testreport->dtable->operateList = array('edit', 'delete');

global $lang;

$config->testreport->actionList['edit']['icon']        = 'edit';
$config->testreport->actionList['edit']['hint']        = $lang->testreport->edit;
$config->testreport->actionList['edit']['text']        = $lang->testreport->edit;
$config->testreport->actionList['edit']['url']         = helper::createLink('testreport', 'edit', 'reportID={id}', '', true);
$config->testreport->actionList['edit']['data-toggle'] = 'modal';
$config->testreport->actionList['edit']['order']       = 5;
$config->testreport->actionList['edit']['show']        = 'clickable';

$config->testreport->actionList['delete']['icon']  = 'trash';
$config->testreport->actionList['delete']['hint']  = $lang->testreport->delete;
$config->testreport->actionList['delete']['text']  = $lang->testreport->delete;
$config->testreport->actionList['delete']['url']   = helper::createLink('testreport', 'delete', 'reportID={id}');
$config->testreport->actionList['delete']['order'] = 10;
$config->testreport->actionList['delete']['show']  = 'clickable';

$config->testreport->dtable->fieldList['id']['name']  = 'id';
$config->testreport->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testreport->dtable->fieldList['id']['type']  = 'ID';
$config->testreport->dtable->fieldList['id']['align'] = 'left';
$config->testreport->dtable->fieldList['id']['fixed'] = 'left';

$config->testreport->dtable->fieldList['title']['name']     = 'title';
$config->testreport->dtable->fieldList['title']['title']    = $lang->testreport->title;
$config->testreport->dtable->fieldList['title']['type']     = 'title';
$config->testreport->dtable->fieldList['title']['minWidth'] = '200';
$config->testreport->dtable->fieldList['title']['fixed']    = 'left';
$config->testreport->dtable->fieldList['title']['link']     = helper::createLink('testreport', 'view', "testreportID={id}");

$config->testreport->dtable->fieldList['execution']['name']     = 'execution';
$config->testreport->dtable->fieldList['execution']['title']    = $lang->testreport->execution;
$config->testreport->dtable->fieldList['execution']['type']     = 'text';
$config->testreport->dtable->fieldList['execution']['sortType'] = true;

$config->testreport->dtable->fieldList['tasks']['name']  = 'tasks';
$config->testreport->dtable->fieldList['tasks']['title'] = $lang->testreport->testtask;
$config->testreport->dtable->fieldList['tasks']['type']  = 'text';

$config->testreport->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->testreport->dtable->fieldList['createdBy']['title']    = $lang->testreport->createdBy;
$config->testreport->dtable->fieldList['createdBy']['type']     = 'user';
$config->testreport->dtable->fieldList['createdBy']['sortType'] = true;
$config->testreport->dtable->fieldList['createdBy']['align']    = 'left';

$config->testreport->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->testreport->dtable->fieldList['createdDate']['title']    = $lang->testreport->createdDate;
$config->testreport->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->testreport->dtable->fieldList['createdDate']['sortType'] = true;

$config->testreport->dtable->fieldList['actions']['name']       = 'actions';
$config->testreport->dtable->fieldList['actions']['title']      = $lang->actions;
$config->testreport->dtable->fieldList['actions']['type']       = 'actions';
$config->testreport->dtable->fieldList['actions']['width']      = '140';
$config->testreport->dtable->fieldList['actions']['sortType']   = false;
$config->testreport->dtable->fieldList['actions']['fixed']      = 'right';
$config->testreport->dtable->fieldList['actions']['actionsMap'] = array();
foreach($config->testreport->dtable->operateList as $operate)
{
    $settings = $config->testreport->actionList[$operate];
    $settings['text'] = '';

    $config->testreport->dtable->fieldList['actions']['actionsMap'][$operate] = $settings;
}
