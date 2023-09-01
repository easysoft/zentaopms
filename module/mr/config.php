<?php
global $lang, $app;
$config->mr = new stdclass();

$config->mr->create = new stdclass();
$config->mr->create->skippedFields  = 'projectID,compile';
$config->mr->create->requiredFields = 'gitlabID,sourceProject,sourceBranch,targetProject,targetBranch,title,repoID';

$config->mr->edit = new stdclass;
$config->mr->edit->skippedFields  = 'projectID,compile';
$config->mr->edit->requiredFields = 'gitlabID,sourceProject,sourceBranch,targetProject,targetBranch,title,repoID';

$config->mr->editor = new stdclass();
$config->mr->editor->diff = array('id' => 'commentText', 'tools' => 'simpleTools');

$config->mr->apicreate = new stdclass();
$config->mr->apicreate->requiredFields = 'repoID,sourceBranch,targetBranch,mergeStatus,jobID';

$config->mr->maps = new stdclass;
$config->mr->maps->sync = array();
$config->mr->maps->sync['title']         = 'title|field|';
$config->mr->maps->sync['description']   = 'description|field|';
$config->mr->maps->sync['assignee']      = 'assignees|userPairs|id';
$config->mr->maps->sync['reviewer']      = 'reviewers|userPairs|id';
$config->mr->maps->sync['targetBranch']  = 'target_branch|field|';
$config->mr->maps->sync['sourceBranch']  = 'source_branch|field|';
$config->mr->maps->sync['sourceProject'] = 'source_project_id|field|';
$config->mr->maps->sync['targetProject'] = 'target_project_id|field|';
$config->mr->maps->sync['status']        = 'state|field|';
$config->mr->maps->sync['mergeStatus']   = 'merge_status|field|';

$config->mrapproval = new stdclass();
$config->mrapproval->create = new stdclass();
$config->mrapproval->create->skippedFields  = '';
$config->mrapproval->create->requiredFields = 'mrID,account,date,action';

$config->mr->gitServiceList = array('gitlab', 'gitea', 'gogs');

$config->mr->actionList['view']['icon'] = 'eye';
$config->mr->actionList['view']['hint'] = $lang->mr->view;
$config->mr->actionList['view']['url']  = helper::createLink('mr', 'view', "MRID={id}");

$config->mr->actionList['edit']['icon'] = 'edit';
$config->mr->actionList['edit']['hint'] = $lang->mr->edit;
$config->mr->actionList['edit']['url']  = helper::createLink('mr', 'edit', "MRID={id}");

$config->mr->actionList['diff']['icon'] = 'diff';
$config->mr->actionList['diff']['hint'] = $lang->mr->diff;
$config->mr->actionList['diff']['url']  = helper::createLink('mr', 'diff', "MRID={id}");

$config->mr->actionList['link']['icon'] = 'link';
$config->mr->actionList['link']['hint'] = $lang->mr->link;
$config->mr->actionList['link']['url']  = helper::createLink('mr', 'link', "MRID={id}");

$config->mr->actionList['delete']['icon']         = 'trash';
$config->mr->actionList['delete']['hint']         = $lang->mr->delete;
$config->mr->actionList['delete']['url']          = helper::createLink('mr', 'delete', "MRID={id}&confirm=yes");
$config->mr->actionList['delete']['data-confirm'] = $lang->mr->confirmDelete;
$config->mr->actionList['delete']['className']    = 'ajax-submit';

$config->mr->actionList['accept']['icon']        = 'flow';
$config->mr->actionList['accept']['text']        = $lang->mr->acceptMR;
$config->mr->actionList['accept']['url']         = helper::createLink('mr', 'accept', "MRID={id}");

$config->mr->actionList['approval']['icon']        = 'ok';
$config->mr->actionList['approval']['text']        = $lang->mr->approve;
$config->mr->actionList['approval']['url']         = helper::createLink('mr', 'approval', "MRID={id}&action=approve");
$config->mr->actionList['approval']['data-toggle'] = 'modal';

$config->mr->actionList['reject']['icon']        = 'bug';
$config->mr->actionList['reject']['text']        = $lang->mr->reject;
$config->mr->actionList['reject']['url']         = helper::createLink('mr', 'approval', "MRID={id}&action=reject");
$config->mr->actionList['reject']['data-toggle'] = 'modal';

$config->mr->actionList['close']['icon']      = 'off';
$config->mr->actionList['close']['text']      = $lang->mr->close;
$config->mr->actionList['close']['url']       = helper::createLink('mr', 'close', "MRID={id}");
$config->mr->actionList['close']['className'] = 'ajax-submit';

$config->mr->actionList['reopen']['icon']      = 'restart';
$config->mr->actionList['reopen']['text']      = $lang->mr->reopen;
$config->mr->actionList['reopen']['url']       = helper::createLink('mr', 'reopen', "MRID={id}");
$config->mr->actionList['reopen']['className'] = 'ajax-submit';

$app->loadLang('release');
$app->loadLang('story');
$app->loadLang('bug');
$app->loadLang('build');
$app->loadLang('task');
$config->mr->actionList['unlinkStory']['icon'] = 'unlink';
$config->mr->actionList['unlinkStory']['hint'] = $lang->release->unlinkStory;
$config->mr->actionList['unlinkStory']['url']  = 'javascript: unlinkObject("story", "{id}")';

$config->mr->actionList['unlinkBug']['icon'] = 'unlink';
$config->mr->actionList['unlinkBug']['hint'] = $lang->release->unlinkBug;
$config->mr->actionList['unlinkBug']['url']  = 'javascript: unlinkObject("bug", "{id}")';

$config->mr->view = new stdclass();
$config->mr->view->operateList = array('accept', 'approval', 'reject', 'close', 'edit', 'reopen', 'delete');
