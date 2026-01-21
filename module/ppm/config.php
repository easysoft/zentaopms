<?php
global $lang, $app;
$module = $app->rawModule;
if($module != 'ppm' && $module != 'pullreq') $module = 'ppm';

$config->ppm = new stdclass();
$config->ppm->messageTips = '<i class="icon icon-alert text-danger"></i><span class="ml-2">%s</span>';

$config->ppm->mergeImages = array();
$config->ppm->mergeImages['merge']  = 'static/images/merge.gif';
$config->ppm->mergeImages['squash'] = 'static/images/squash.gif';
$config->ppm->mergeImages['rebase'] = 'static/images/rebase.gif';
$config->ppm->mergeImages['fast']   = 'static/images/fast.gif';

$config->ppm->create = new stdclass();
$config->ppm->create->skippedFields  = 'reviewer';
$config->ppm->create->requiredFields = 'sourceBranch,targetBranch,title,repoID';

$config->ppm->edit = new stdclass;
$config->ppm->edit->skippedFields  = 'projectID,compile';
$config->ppm->edit->requiredFields = 'hostID,sourceProject,sourceBranch,targetProject,targetBranch,title,repoID';

$config->ppm->editor = new stdclass();
$config->ppm->editor->diff   = array('id' => 'commentText', 'tools' => 'simpleTools');
$config->ppm->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->ppm->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');

$config->ppm->apicreate = new stdclass();
$config->ppm->apicreate->requiredFields = 'repoID,sourceBranch,targetBranch,mergeStatus,jobID';

$config->ppm->maps = new stdclass;
$config->ppm->maps->sync = array();
$config->ppm->maps->sync['title']         = 'title|field|';
$config->ppm->maps->sync['description']   = 'description|field|';
$config->ppm->maps->sync['assignee']      = 'assignees|userPairs|id';
$config->ppm->maps->sync['reviewer']      = 'reviewers|userPairs|id';
$config->ppm->maps->sync['targetBranch']  = 'target_branch|field|';
$config->ppm->maps->sync['sourceBranch']  = 'source_branch|field|';
$config->ppm->maps->sync['sourceProject'] = 'source_project_id|field|';
$config->ppm->maps->sync['targetProject'] = 'target_project_id|field|';
$config->ppm->maps->sync['status']        = 'state|field|';
$config->ppm->maps->sync['mergeStatus']   = 'merge_status|field|';
$config->ppm->maps->sync['isFlow']        = 'flow|field|';

$config->mrapproval = new stdclass();
$config->mrapproval->create = new stdclass();
$config->mrapproval->create->skippedFields  = '';
$config->mrapproval->create->requiredFields = 'mrID,account,date,action';

$config->ppm->gitServiceList = array('gitlab', 'gitea', 'gogs');

$config->ppm->actionList = array();
$config->ppm->actionList['view'] = array();
$config->ppm->actionList['view']['icon']     = 'eye';
$config->ppm->actionList['view']['hint']     = $lang->ppm->view;
$config->ppm->actionList['view']['url']      = helper::createLink($module, 'view', "id={id}");
$config->ppm->actionList['view']['data-app'] = $app->tab;

$config->ppm->actionList['edit'] = array();
$config->ppm->actionList['edit']['icon']     = 'edit';
$config->ppm->actionList['edit']['hint']     = $lang->edit;
$config->ppm->actionList['edit']['url']      = helper::createLink($module, 'edit', "id={id}");
$config->ppm->actionList['edit']['data-app'] = $app->tab;

$config->ppm->actionList['diff'] = array();
$config->ppm->actionList['diff']['icon']     = 'diff';
$config->ppm->actionList['diff']['hint']     = $lang->ppm->diff;
$config->ppm->actionList['diff']['url']      = helper::createLink($module, 'diff', "id={id}");
$config->ppm->actionList['diff']['data-app'] = $app->tab;

$config->ppm->actionList['link'] = array();
$config->ppm->actionList['link']['icon']     = 'link';
$config->ppm->actionList['link']['hint']     = $lang->ppm->link;
$config->ppm->actionList['link']['url']      = helper::createLink($module, 'link', "id={id}");
$config->ppm->actionList['link']['data-app'] = $app->tab;

$config->ppm->actionList['delete'] = array();
$config->ppm->actionList['delete']['icon']         = 'trash';
$config->ppm->actionList['delete']['hint']         = $lang->ppm->delete;
$config->ppm->actionList['delete']['url']          = helper::createLink($module, 'delete', "id={id}");
$config->ppm->actionList['delete']['data-confirm'] = array('message' => $lang->ppm->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->ppm->actionList['delete']['className']    = 'ajax-submit';
$config->ppm->actionList['delete']['data-app']     = $app->tab;

$config->ppm->actionList['accept'] = array();
$config->ppm->actionList['accept']['icon']      = 'flow';
$config->ppm->actionList['accept']['text']      = $lang->{$module}->acceptMR;
$config->ppm->actionList['accept']['url']       = helper::createLink($module, 'accept', "id={id}");
$config->ppm->actionList['accept']['data-app']  = $app->tab;
$config->ppm->actionList['accept']['className'] = 'ajax-submit';

$config->ppm->actionList['approval'] = array();
$config->ppm->actionList['approval']['icon']        = 'ok';
$config->ppm->actionList['approval']['text']        = $lang->ppm->approve;
$config->ppm->actionList['approval']['url']         = helper::createLink($module, 'approval', "id={id}&action=approve");
$config->ppm->actionList['approval']['data-toggle'] = 'modal';
$config->ppm->actionList['approval']['data-app']    = $app->tab;

$config->ppm->actionList['reject'] = array();
$config->ppm->actionList['reject']['icon']        = 'bug';
$config->ppm->actionList['reject']['text']        = $lang->ppm->reject;
$config->ppm->actionList['reject']['url']         = helper::createLink($module, 'approval', "id={id}&action=reject");
$config->ppm->actionList['reject']['data-toggle'] = 'modal';
$config->ppm->actionList['reject']['data-app']    = $app->tab;

$config->ppm->actionList['close'] = array();
$config->ppm->actionList['close']['icon']      = 'off';
$config->ppm->actionList['close']['text']      = $lang->ppm->close;
$config->ppm->actionList['close']['url']       = helper::createLink($module, 'close', "id={id}");
$config->ppm->actionList['close']['className'] = 'ajax-submit';
$config->ppm->actionList['close']['data-app']  = $app->tab;

$config->ppm->actionList['reopen'] = array();
$config->ppm->actionList['reopen']['icon']      = 'restart';
$config->ppm->actionList['reopen']['text']      = $lang->ppm->reopen;
$config->ppm->actionList['reopen']['url']       = helper::createLink($module, 'reopen', "id={id}");
$config->ppm->actionList['reopen']['className'] = 'ajax-submit';
$config->ppm->actionList['reopen']['data-app']  = $app->tab;

$app->loadLang('release');
$app->loadLang('story');
$app->loadLang('bug');
$app->loadLang('build');
$app->loadLang('task');
$config->ppm->actionList['unlinkStory'] = array();
$config->ppm->actionList['unlinkStory']['icon'] = 'unlink';
$config->ppm->actionList['unlinkStory']['hint'] = $lang->release->unlinkStory;
$config->ppm->actionList['unlinkStory']['url']  = 'javascript: unlinkObject("story", "{id}")';

$config->ppm->actionList['unlinkBug'] = array();
$config->ppm->actionList['unlinkBug']['icon'] = 'unlink';
$config->ppm->actionList['unlinkBug']['hint'] = $lang->release->unlinkBug;
$config->ppm->actionList['unlinkBug']['url']  = 'javascript: unlinkObject("bug", "{id}")';

$config->ppm->view = new stdclass();
$config->ppm->view->operateList = array('accept', 'approval', 'reject', 'close', 'edit', 'reopen', 'delete');

$config->ppm->groupPrivs = array();
$config->ppm->groupPrivs['commitlogs'] = 'view';
$config->ppm->groupPrivs['diff']       = 'view';
$config->ppm->groupPrivs['link']       = 'view';

$config->ppm->actions = new stdclass();
$config->ppm->actions->view = array();
$config->ppm->actions->view['mainActions'] = array('review', 'edit', 'close', 'reopen');
