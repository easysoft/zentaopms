<?php
global $lang, $app;
$app->loadLang('gitlab');

$config->gitlab->dtable = new stdclass();

$config->gitlab->dtable->fieldList['id']['title']    = 'ID';
$config->gitlab->dtable->fieldList['id']['name']     = 'id';
$config->gitlab->dtable->fieldList['id']['type']     = 'number';
$config->gitlab->dtable->fieldList['id']['sortType'] = 'desc';
$config->gitlab->dtable->fieldList['id']['checkbox'] = false;
$config->gitlab->dtable->fieldList['id']['width']    = '80';

$config->gitlab->dtable->fieldList['name']['title']    = $lang->gitlab->name;
$config->gitlab->dtable->fieldList['name']['name']     = 'name';
$config->gitlab->dtable->fieldList['name']['type']     = 'desc';
$config->gitlab->dtable->fieldList['name']['sortType'] = true;
$config->gitlab->dtable->fieldList['name']['hint']     = true;
$config->gitlab->dtable->fieldList['name']['minWidth'] = '356';

$config->gitlab->dtable->fieldList['url']['title']    = $lang->gitlab->url;
$config->gitlab->dtable->fieldList['url']['name']     = 'url';
$config->gitlab->dtable->fieldList['url']['type']     = 'desc';
$config->gitlab->dtable->fieldList['url']['sortType'] = true;
$config->gitlab->dtable->fieldList['url']['hint']     = true;
$config->gitlab->dtable->fieldList['url']['minWidth'] = '356';

$config->gitlab->actionList = array();
$config->gitlab->actionList['edit']['icon'] = 'edit';
$config->gitlab->actionList['edit']['text'] = $lang->gitlab->edit;
$config->gitlab->actionList['edit']['hint'] = $lang->gitlab->edit;
$config->gitlab->actionList['edit']['url']  = helper::createLink('gitlab', 'edit',"gitlabID={id}");

$config->gitlab->actionList['bindUser']['icon'] = 'lock';
$config->gitlab->actionList['bindUser']['text'] = $lang->gitlab->bindUser;
$config->gitlab->actionList['bindUser']['hint'] = $lang->gitlab->bindUser;
$config->gitlab->actionList['bindUser']['url']  = helper::createLink('gitlab', 'bindUser',"gitlabID={id}");

$config->gitlab->actionList['delete']['icon']       = 'trash';
$config->gitlab->actionList['delete']['text']       = $lang->gitlab->delete;
$config->gitlab->actionList['delete']['hint']       = $lang->gitlab->delete;
$config->gitlab->actionList['delete']['ajaxSubmit'] = true;
$config->gitlab->actionList['delete']['url']        = helper::createLink('gitlab', 'delete',"gitlabID={id}");

$config->gitlab->dtable->fieldList['actions']['name']     = 'actions';
$config->gitlab->dtable->fieldList['actions']['title']    = $lang->actions;
$config->gitlab->dtable->fieldList['actions']['type']     = 'actions';
$config->gitlab->dtable->fieldList['actions']['width']    = '160';
$config->gitlab->dtable->fieldList['actions']['sortType'] = false;
$config->gitlab->dtable->fieldList['actions']['fixed']    = 'right';
$config->gitlab->dtable->fieldList['actions']['menu']     = array('edit', 'bindUser', 'delete');
$config->gitlab->dtable->fieldList['actions']['list']     = $config->gitlab->actionList;

$config->gitlab->dtable->bindUser = new stdclass();
$config->gitlab->dtable->bindUser->fieldList['gitlabUser']['title']    = $lang->gitlab->gitlabAccount;
$config->gitlab->dtable->bindUser->fieldList['gitlabUser']['type']     = 'avatarName';
$config->gitlab->dtable->bindUser->fieldList['gitlabUser']['sortType'] = false;
$config->gitlab->dtable->bindUser->fieldList['gitlabUser']['width']    = 300;

$config->gitlab->dtable->bindUser->fieldList['gitlabEmail']['title'] = $lang->gitlab->gitlabEmail;
$config->gitlab->dtable->bindUser->fieldList['gitlabEmail']['type']  = 'text';
$config->gitlab->dtable->bindUser->fieldList['gitlabEmail']['width'] = 200;

$config->gitlab->dtable->bindUser->fieldList['email']['title'] = $lang->gitlab->zentaoEmail;
$config->gitlab->dtable->bindUser->fieldList['email']['type']  = 'text';
$config->gitlab->dtable->bindUser->fieldList['email']['width'] = 200;

$config->gitlab->dtable->bindUser->fieldList['zentaoUsers']['title']   = array('html' => $lang->gitlab->zentaoAccount . "<span class='text-gray'>{$lang->gitlab->accountDesc}</span>");
$config->gitlab->dtable->bindUser->fieldList['zentaoUsers']['type']    = 'control';
$config->gitlab->dtable->bindUser->fieldList['zentaoUsers']['control'] = 'picker';
$config->gitlab->dtable->bindUser->fieldList['zentaoUsers']['width']   = 300;

$config->gitlab->dtable->bindUser->fieldList['status']['title'] = $lang->gitlab->bindingStatus;
$config->gitlab->dtable->bindUser->fieldList['status']['type']  = 'html';
$config->gitlab->dtable->bindUser->fieldList['status']['width'] = 100;
$config->gitlab->dtable->bindUser->fieldList['status']['map']   = $lang->gitlab->bindStatus;

$config->gitlab->dtable->project = new stdclass();

$config->gitlab->dtable->project->fieldList['id']['title']    = $lang->gitlab->id;
$config->gitlab->dtable->project->fieldList['id']['sortType'] = true;

$config->gitlab->dtable->project->fieldList['name']['title']    = $lang->gitlab->project->name;
$config->gitlab->dtable->project->fieldList['name']['name']     = 'name_with_namespace';
$config->gitlab->dtable->project->fieldList['name']['sortType'] = true;

$config->gitlab->dtable->project->fieldList['star']['title'] = '';

$config->gitlab->dtable->project->fieldList['lastUpdate']['title']    = $lang->gitlab->lastUpdate;
$config->gitlab->dtable->project->fieldList['lastUpdate']['type']     = 'date';
$config->gitlab->dtable->project->fieldList['lastUpdate']['sortType'] = false;

$config->gitlab->dtable->project->fieldList['actions']['name']     = 'actions';
$config->gitlab->dtable->project->fieldList['actions']['title']    = $lang->actions;
$config->gitlab->dtable->project->fieldList['actions']['type']     = 'actions';
$config->gitlab->dtable->project->fieldList['actions']['menu']     = array('browseBranch', 'browseTag', 'manageBranchPriv', 'manageTagPriv', 'manageProjectMembers', 'createWebhook', 'importIssue', 'editProject', 'deleteProject');

$config->gitlab->dtable->project->fieldList['actions']['list']['browseBranch']['icon'] = 'treemap';
$config->gitlab->dtable->project->fieldList['actions']['list']['browseBranch']['hint'] = $lang->gitlab->browseBranch;
$config->gitlab->dtable->project->fieldList['actions']['list']['browseBranch']['url']  = helper::createLink('gitlab', 'browseBranch', 'gitlabID={id}&projectID={projectID}');

$config->gitlab->dtable->project->fieldList['actions']['list']['browseTag']['icon'] = 'tag';
$config->gitlab->dtable->project->fieldList['actions']['list']['browseTag']['hint'] = $lang->gitlab->browseTag;
$config->gitlab->dtable->project->fieldList['actions']['list']['browseTag']['url']  = array('module' => 'gitlab', 'method' => 'browseTag', 'params' => 'gitlabID={id}&projectID={projectID}');
$config->gitlab->dtable->project->fieldList['actions']['list']['browseTag']['url']  = helper::createLink('gitlab', 'browseTag', 'gitlabID={id}&projectID={projectID}');

$config->gitlab->dtable->project->fieldList['actions']['list']['manageBranchPriv']['icon'] = 'tag';
$config->gitlab->dtable->project->fieldList['actions']['list']['manageBranchPriv']['hint'] = $lang->gitlab->browseBranchPriv;
$config->gitlab->dtable->project->fieldList['actions']['list']['manageBranchPriv']['url']  = helper::createLink('gitlab', 'manageBranchPriv', 'gitlabID={id}&projectID={projectID}');

$config->gitlab->dtable->project->fieldList['actions']['list']['manageTagPriv']['icon'] = 'tag-lock';
$config->gitlab->dtable->project->fieldList['actions']['list']['manageTagPriv']['hint'] = $lang->gitlab->browseTagPriv;
$config->gitlab->dtable->project->fieldList['actions']['list']['manageTagPriv']['url']  = helper::createLink('gitlab', 'manageTagPriv', 'gitlabID={id}&projectID={projectID}');

$config->gitlab->dtable->project->fieldList['actions']['list']['manageProjectMembers']['icon'] = 'team';
$config->gitlab->dtable->project->fieldList['actions']['list']['manageProjectMembers']['hint'] = $lang->gitlab->manageProjectMembers;
$config->gitlab->dtable->project->fieldList['actions']['list']['manageProjectMembers']['url']  = helper::createLink('gitlab', 'manageProjectMembers', 'repoID={repoID}');

$config->gitlab->dtable->project->fieldList['actions']['list']['createWebhook']['icon']      = 'change';
$config->gitlab->dtable->project->fieldList['actions']['list']['createWebhook']['hint']      = $lang->gitlab->createWebhook;
$config->gitlab->dtable->project->fieldList['actions']['list']['createWebhook']['url']       = helper::createLink('gitlab', 'createWebhook', 'repoID={repoID}');
$config->gitlab->dtable->project->fieldList['actions']['list']['createWebhook']['className'] = 'ajax-submit';

$config->gitlab->dtable->project->fieldList['actions']['list']['importIssue']['icon'] = 'link';
$config->gitlab->dtable->project->fieldList['actions']['list']['importIssue']['hint'] = $lang->gitlab->importIssue;
$config->gitlab->dtable->project->fieldList['actions']['list']['importIssue']['url']  = array('module' => 'gitlab', 'method' => 'importIssue', 'params' => 'gitlabID={id}&projectID={projectID}');
$config->gitlab->dtable->project->fieldList['actions']['list']['importIssue']['url']  = helper::createLink('gitlab', 'importIssue', 'gitlabID={id}&projectID={projectID}');

$config->gitlab->dtable->project->fieldList['actions']['list']['editProject']['icon'] = 'edit';
$config->gitlab->dtable->project->fieldList['actions']['list']['editProject']['hint'] = $lang->gitlab->editProject;
$config->gitlab->dtable->project->fieldList['actions']['list']['editProject']['url']  = helper::createLink('gitlab', 'editProject', 'gitlabID={id}&projectID={projectID}');

$config->gitlab->dtable->project->fieldList['actions']['list']['deleteProject']['icon']      = 'trash';
$config->gitlab->dtable->project->fieldList['actions']['list']['deleteProject']['hint']      = $lang->gitlab->deleteProject;
$config->gitlab->dtable->project->fieldList['actions']['list']['deleteProject']['url']  = helper::createLink('gitlab', 'deleteProject', 'gitlabID={id}&projectID={projectID}');
$config->gitlab->dtable->project->fieldList['actions']['list']['deleteProject']['className'] = 'ajax-submit';
