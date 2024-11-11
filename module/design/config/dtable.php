<?php
global $lang,$app;
$config->design->dtable = new stdclass();
$config->design->linkcommit = new stdclass();
$config->design->viewcommit = new stdclass();
$config->design->linkcommit->dtable = new stdclass();
$config->design->viewcommit->dtable = new stdclass();

$config->design->viewcommit->actionList['unlinkCommit']['icon']         = 'unlink';
$config->design->viewcommit->actionList['unlinkCommit']['hint']         = $lang->design->unlinkCommit;
$config->design->viewcommit->actionList['unlinkCommit']['url']          = helper::createLink('design', 'unlinkCommit', "designID=%s&commitID={id}");
$config->design->viewcommit->actionList['unlinkCommit']['className']    = 'ajax-submit';
$config->design->viewcommit->actionList['unlinkCommit']['data-confirm'] = $lang->design->confirmUnlink;

$config->design->dtable->fieldList['id']['title'] = $lang->idAB;
$config->design->dtable->fieldList['id']['type']  = 'id';

$config->design->dtable->fieldList['name']['title'] = $lang->design->name;
$config->design->dtable->fieldList['name']['type']  = 'title';
$config->design->dtable->fieldList['name']['link']  = array('module' => 'design', 'method' => 'view', 'params' => 'designID={id}');

$config->design->dtable->fieldList['product']['title']    = $lang->design->product;
$config->design->dtable->fieldList['product']['type']     = 'desc';
$config->design->dtable->fieldList['product']['sortType'] = true;

$config->design->dtable->fieldList['type']['type']      = 'status';
$config->design->dtable->fieldList['type']['statusMap'] = $lang->design->typeList;
$config->design->dtable->fieldList['type']['sortType']  = true;

$config->design->dtable->fieldList['assignedTo']['title']       = $lang->design->assignedTo;
$config->design->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->design->dtable->fieldList['assignedTo']['assignLink']  = array('module' => 'design', 'method' => 'assignTo', 'params' => 'designID={id}');
$config->design->dtable->fieldList['assignedTo']['data-toggle'] = 'modal';
$config->design->dtable->fieldList['assignedTo']['sortType']    = false;

$config->design->dtable->fieldList['createdBy']['title']    = $lang->design->createdBy;
$config->design->dtable->fieldList['createdBy']['type']     = 'user';
$config->design->dtable->fieldList['createdBy']['sortType'] = true;

$config->design->dtable->fieldList['createdDate']['type'] = 'date';

if($config->edition != 'open')
{
    $config->design->dtable->fieldList['relatedObject']['name']        = 'relatedObject';
    $config->design->dtable->fieldList['relatedObject']['title']       = $lang->custom->relateObject;
    $config->design->dtable->fieldList['relatedObject']['sortType']    = false;
    $config->design->dtable->fieldList['relatedObject']['width']       = '70';
    $config->design->dtable->fieldList['relatedObject']['type']        = 'text';
    $config->design->dtable->fieldList['relatedObject']['link']        = common::hasPriv('custom', 'showRelationGraph') ? "RAWJS<function(info){ if(info.row.data.relatedObject == 0) return 0; else return '" . helper::createLink('custom', 'showRelationGraph', 'objectID={id}&objectType=design') . "'; }>RAWJS" : null;
    $config->design->dtable->fieldList['relatedObject']['data-toggle'] = 'modal';
    $config->design->dtable->fieldList['relatedObject']['data-size']   = 'lg';
    $config->design->dtable->fieldList['relatedObject']['show']        = true;
    $config->design->dtable->fieldList['relatedObject']['group']       = 2;
    $config->design->dtable->fieldList['relatedObject']['flex']        = false;
    $config->design->dtable->fieldList['relatedObject']['align']       = 'center';
}

$config->design->dtable->fieldList['actions']['type'] = 'actions';
$config->design->dtable->fieldList['actions']['menu'] = array('edit', 'viewCommit', 'delete');
$config->design->dtable->fieldList['actions']['list'] = $config->design->actionList;

$app->loadLang('repo');

$config->design->linkcommit->dtable->fieldList['revision']['title']    = $lang->repo->revisionA;
$config->design->linkcommit->dtable->fieldList['revision']['type']     = 'title';
$config->design->linkcommit->dtable->fieldList['revision']['link']     = helper::createLink('repo', 'revision', 'repoID=%s&objectID=%s&revision={revision}');
$config->design->linkcommit->dtable->fieldList['revision']['sortType'] = false;
$config->design->linkcommit->dtable->fieldList['revision']['checkbox'] = true;

$config->design->linkcommit->dtable->fieldList['commit']['title']    = $lang->repo->commit;
$config->design->linkcommit->dtable->fieldList['commit']['type']     = 'category';
$config->design->linkcommit->dtable->fieldList['commit']['sortType'] = false;

$config->design->linkcommit->dtable->fieldList['time']['title']    = $lang->repo->time;
$config->design->linkcommit->dtable->fieldList['time']['type']     = 'date';
$config->design->linkcommit->dtable->fieldList['time']['sortType'] = false;

$config->design->linkcommit->dtable->fieldList['committer']['title']    = $lang->repo->committer;
$config->design->linkcommit->dtable->fieldList['committer']['sortType'] = false;

$config->design->linkcommit->dtable->fieldList['comment']['title']    = $lang->repo->comment;
$config->design->linkcommit->dtable->fieldList['comment']['type']     = 'html';
$config->design->linkcommit->dtable->fieldList['comment']['sortType'] = false;
$config->design->linkcommit->dtable->fieldList['comment']['hint']     = '{commentHint}';

$config->design->viewcommit->dtable->fieldList['id']['title']    = $lang->design->submission;
$config->design->viewcommit->dtable->fieldList['id']['format']   = 'RAWJS<function(val){return `#${val}`;}>RAWJS';
$config->design->viewcommit->dtable->fieldList['id']['link']     = array('url' => helper::createLink('design', 'revision', 'repoID={id}'), 'target' => '_blank');
$config->design->viewcommit->dtable->fieldList['id']['target']   = '_blank';
$config->design->viewcommit->dtable->fieldList['id']['sortType'] = false;

$config->design->viewcommit->dtable->fieldList['committer']['title']    = $lang->design->commitBy;
$config->design->viewcommit->dtable->fieldList['committer']['sortType'] = false;

$config->design->viewcommit->dtable->fieldList['time']['title']    = $lang->design->commitDate;
$config->design->viewcommit->dtable->fieldList['time']['type']     = 'date';
$config->design->viewcommit->dtable->fieldList['time']['sortType'] = false;

$config->design->viewcommit->dtable->fieldList['comment']['title']    = $lang->design->comment;
$config->design->viewcommit->dtable->fieldList['comment']['type']     = 'html';
$config->design->viewcommit->dtable->fieldList['comment']['sortType'] = false;
$config->design->viewcommit->dtable->fieldList['comment']['hint']     = '{originalComment}';

$config->design->viewcommit->dtable->fieldList['actions']['type'] = 'actions';
$config->design->viewcommit->dtable->fieldList['actions']['menu'] = array('unlinkCommit');
$config->design->viewcommit->dtable->fieldList['actions']['list'] = $config->design->viewcommit->actionList;

$app->loadLang('task');
$config->design->affect = new stdclass();
$config->design->affect->tasks = new stdclass();
$config->design->affect->tasks->fields['id']         = array('name' => 'id',         'title' => $lang->task->id,         'type' => 'id',     'sortType' => false);
$config->design->affect->tasks->fields['name']       = array('name' => 'name',       'title' => $lang->task->name,       'type' => 'title',  'sortType' => false);
$config->design->affect->tasks->fields['assignedTo'] = array('name' => 'assignedTo', 'title' => $lang->task->assignedTo, 'type' => 'user',   'sortType' => false);
$config->design->affect->tasks->fields['status']     = array('name' => 'status',     'title' => $lang->task->status,     'type' => 'status', 'sortType' => false, 'statusMap' => $lang->task->statusList);
$config->design->affect->tasks->fields['consumed']   = array('name' => 'consumed',   'title' => $lang->task->consumed,   'type' => 'number', 'sortType' => false);
$config->design->affect->tasks->fields['left']       = array('name' => 'left',       'title' => $lang->task->left,       'type' => 'number', 'sortType' => false);
