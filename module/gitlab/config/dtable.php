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
$config->gitlab->dtable->bindUser->fieldList['status']['html']  = true;
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

$config->gitlab->dtable->project->fieldList['actions']['name']  = 'actions';
$config->gitlab->dtable->project->fieldList['actions']['title'] = $lang->actions;
$config->gitlab->dtable->project->fieldList['actions']['type']  = 'actions';
$config->gitlab->dtable->project->fieldList['actions']['menu']  = array('browseBranch', 'browseTag', 'manageBranchPriv', 'manageTagPriv', 'manageProjectMembers', 'createWebhook', 'importIssue', 'editProject', 'deleteProject');

$config->gitlab->dtable->project->fieldList['actions']['list']['browseBranch']['icon'] = 'treemap';
$config->gitlab->dtable->project->fieldList['actions']['list']['browseBranch']['hint'] = $lang->gitlab->browseBranch;
$config->gitlab->dtable->project->fieldList['actions']['list']['browseBranch']['url']  = helper::createLink('gitlab', 'browseBranch', 'gitlabID={gitlabID}&projectID={id}');

$config->gitlab->dtable->project->fieldList['actions']['list']['browseTag']['icon'] = 'tag';
$config->gitlab->dtable->project->fieldList['actions']['list']['browseTag']['hint'] = $lang->gitlab->browseTag;
$config->gitlab->dtable->project->fieldList['actions']['list']['browseTag']['url']  = helper::createLink('gitlab', 'browseTag', 'gitlabID={gitlabID}&projectID={id}');

$config->gitlab->dtable->project->fieldList['actions']['list']['manageBranchPriv']['icon'] = 'branch-lock';
$config->gitlab->dtable->project->fieldList['actions']['list']['manageBranchPriv']['hint'] = $lang->gitlab->browseBranchPriv;
$config->gitlab->dtable->project->fieldList['actions']['list']['manageBranchPriv']['url']  = helper::createLink('gitlab', 'manageBranchPriv', 'gitlabID={gitlabID}&projectID={id}');

$config->gitlab->dtable->project->fieldList['actions']['list']['manageTagPriv']['icon'] = 'tag-lock';
$config->gitlab->dtable->project->fieldList['actions']['list']['manageTagPriv']['hint'] = $lang->gitlab->browseTagPriv;
$config->gitlab->dtable->project->fieldList['actions']['list']['manageTagPriv']['url']  = helper::createLink('gitlab', 'manageTagPriv', 'gitlabID={gitlabID}&projectID={id}');

$config->gitlab->dtable->project->fieldList['actions']['list']['manageProjectMembers']['icon'] = 'team';
$config->gitlab->dtable->project->fieldList['actions']['list']['manageProjectMembers']['hint'] = $lang->gitlab->manageProjectMembers;
$config->gitlab->dtable->project->fieldList['actions']['list']['manageProjectMembers']['url']  = helper::createLink('gitlab', 'manageProjectMembers', 'repoID={repoID}');

$config->gitlab->dtable->project->fieldList['actions']['list']['createWebhook']['icon']         = 'change';
$config->gitlab->dtable->project->fieldList['actions']['list']['createWebhook']['hint']         = $lang->gitlab->createWebhook;
$config->gitlab->dtable->project->fieldList['actions']['list']['createWebhook']['url']          = helper::createLink('gitlab', 'createWebhook', 'repoID={repoID}&confirm=yes');
$config->gitlab->dtable->project->fieldList['actions']['list']['createWebhook']['className']    = 'ajax-submit';
$config->gitlab->dtable->project->fieldList['actions']['list']['createWebhook']['data-confirm'] = $lang->gitlab->confirmAddWebhook;

$config->gitlab->dtable->project->fieldList['actions']['list']['importIssue']['icon'] = 'link';
$config->gitlab->dtable->project->fieldList['actions']['list']['importIssue']['hint'] = $lang->gitlab->importIssue;
$config->gitlab->dtable->project->fieldList['actions']['list']['importIssue']['url']  = helper::createLink('gitlab', 'importIssue', 'gitlabID={gitlabID}&projectID={id}');

$config->gitlab->dtable->project->fieldList['actions']['list']['editProject']['icon'] = 'edit';
$config->gitlab->dtable->project->fieldList['actions']['list']['editProject']['hint'] = $lang->gitlab->editProject;
$config->gitlab->dtable->project->fieldList['actions']['list']['editProject']['url']  = helper::createLink('gitlab', 'editProject', 'gitlabID={gitlabID}&projectID={id}');

$config->gitlab->dtable->project->fieldList['actions']['list']['deleteProject']['icon']         = 'trash';
$config->gitlab->dtable->project->fieldList['actions']['list']['deleteProject']['hint']         = $lang->gitlab->deleteProject;
$config->gitlab->dtable->project->fieldList['actions']['list']['deleteProject']['url']          = helper::createLink('gitlab', 'deleteProject', 'gitlabID={gitlabID}&projectID={id}&confirm=yes');
$config->gitlab->dtable->project->fieldList['actions']['list']['deleteProject']['className']    = 'ajax-submit';
$config->gitlab->dtable->project->fieldList['actions']['list']['deleteProject']['data-confirm'] = $lang->gitlab->project->confirmDelete;

$config->gitlab->dtable->group = new stdclass();

$config->gitlab->dtable->group->fieldList['id']['title']    = $lang->gitlab->group->id;
$config->gitlab->dtable->group->fieldList['id']['sortType'] = true;

$config->gitlab->dtable->group->fieldList['fullName']['title'] = $lang->gitlab->group->name;
$config->gitlab->dtable->group->fieldList['fullName']['name']  = 'fullName';
$config->gitlab->dtable->group->fieldList['fullName']['type']  = 'avatarBtn';
$config->gitlab->dtable->group->fieldList['fullName']['sortType'] = true;

$config->gitlab->dtable->group->fieldList['path']['title']    = $lang->gitlab->group->path;
$config->gitlab->dtable->group->fieldList['path']['name']     = 'path';
$config->gitlab->dtable->group->fieldList['path']['sortType'] = true;

$config->gitlab->dtable->group->fieldList['createOn']['title'] = $lang->gitlab->group->createOn;

$config->gitlab->dtable->group->fieldList['actions']['name']  = 'actions';
$config->gitlab->dtable->group->fieldList['actions']['title'] = $lang->actions;
$config->gitlab->dtable->group->fieldList['actions']['type']  = 'actions';
$config->gitlab->dtable->group->fieldList['actions']['menu']  = array('manageGroupMembers', 'editGroup', 'deleteGroup');

$config->gitlab->dtable->group->fieldList['actions']['list']['manageGroupMembers']['icon'] = 'team';
$config->gitlab->dtable->group->fieldList['actions']['list']['manageGroupMembers']['hint'] = $lang->gitlab->group->manageMembers;
$config->gitlab->dtable->group->fieldList['actions']['list']['manageGroupMembers']['url']  = helper::createLink('gitlab', 'manageGroupMembers', 'gitlabID={gitlabID}&groupID={id}');

$config->gitlab->dtable->group->fieldList['actions']['list']['editGroup']['icon'] = 'edit';
$config->gitlab->dtable->group->fieldList['actions']['list']['editGroup']['hint'] = $lang->gitlab->editGroup;
$config->gitlab->dtable->group->fieldList['actions']['list']['editGroup']['url']  = helper::createLink('gitlab', 'editGroup', 'gitlabID={gitlabID}&groupID={id}');

$config->gitlab->dtable->group->fieldList['actions']['list']['deleteGroup']['icon']      = 'trash';
$config->gitlab->dtable->group->fieldList['actions']['list']['deleteGroup']['hint']      = $lang->gitlab->deleteGroup;
$config->gitlab->dtable->group->fieldList['actions']['list']['deleteGroup']['url']       = helper::createLink('gitlab', 'deleteGroup', 'gitlabID={gitlabID}&groupID={id}&confirm=yes');
$config->gitlab->dtable->group->fieldList['actions']['list']['deleteGroup']['className'] = 'ajax-submit';
$config->gitlab->dtable->group->fieldList['actions']['list']['deleteGroup']['data-confirm'] = $lang->gitlab->group->confirmDelete;

$config->gitlab->dtable->user = new stdclass();

$config->gitlab->dtable->user->fieldList['id']['title'] = $lang->gitlab->user->id;
$config->gitlab->dtable->user->fieldList['id']['type']  = 'id';
$config->gitlab->dtable->user->fieldList['id']['width'] = '80px';

$config->gitlab->dtable->user->fieldList['user']['title'] = $lang->gitlab->user->name;
$config->gitlab->dtable->user->fieldList['user']['name']  = 'name';
$config->gitlab->dtable->user->fieldList['user']['type']  = 'avatarName';
$config->gitlab->dtable->user->fieldList['user']['sortType'] = true;

$config->gitlab->dtable->user->fieldList['createOn']['title'] = $lang->gitlab->user->createOn;

$config->gitlab->dtable->user->fieldList['lastActivity']['title'] = $lang->gitlab->user->lastActivity;

$config->gitlab->dtable->user->fieldList['actions']['name']  = 'actions';
$config->gitlab->dtable->user->fieldList['actions']['title'] = $lang->actions;
$config->gitlab->dtable->user->fieldList['actions']['type']  = 'actions';
$config->gitlab->dtable->user->fieldList['actions']['menu']  = array('editUser', 'deleteUser');

$config->gitlab->dtable->user->fieldList['actions']['list']['editUser']['icon'] = 'edit';
$config->gitlab->dtable->user->fieldList['actions']['list']['editUser']['hint'] = $lang->gitlab->editUser;
$config->gitlab->dtable->user->fieldList['actions']['list']['editUser']['url']  = helper::createLink('gitlab', 'editUser', 'gitlabID={gitlabID}&userID={id}');

$config->gitlab->dtable->user->fieldList['actions']['list']['deleteUser']['icon']         = 'trash';
$config->gitlab->dtable->user->fieldList['actions']['list']['deleteUser']['hint']         = $lang->gitlab->deleteUser;
$config->gitlab->dtable->user->fieldList['actions']['list']['deleteUser']['url']          = helper::createLink('gitlab', 'deleteUser', 'gitlabID={gitlabID}&userID={id}&confirm=yes');
$config->gitlab->dtable->user->fieldList['actions']['list']['deleteUser']['className']    = 'ajax-submit';
$config->gitlab->dtable->user->fieldList['actions']['list']['deleteUser']['data-confirm'] = $lang->gitlab->user->confirmDelete;

$config->gitlab->dtable->branch = new stdclass();
$config->gitlab->dtable->branch->fieldList['name']['title'] = $lang->gitlab->branch->name;
$config->gitlab->dtable->branch->fieldList['name']['type']  = 'title';

$config->gitlab->dtable->branch->fieldList['lastCommitter']['title'] = $lang->gitlab->branch->lastCommitter;
$config->gitlab->dtable->branch->fieldList['lastCommitter']['type']  = 'text';

$config->gitlab->dtable->branch->fieldList['lastCommittedDate']['title'] = $lang->gitlab->branch->lastCommittedDate;
$config->gitlab->dtable->branch->fieldList['lastCommittedDate']['type']  = 'text';
$config->gitlab->dtable->branch->fieldList['lastCommittedDate']['sortType'] = true;

$config->gitlab->dtable->tag = new stdclass();
$config->gitlab->dtable->tag->fieldList['name']['title'] = $lang->gitlab->branch->name;
$config->gitlab->dtable->tag->fieldList['name']['type']  = 'title';

$config->gitlab->dtable->tag->fieldList['lastCommitter']['title'] = $lang->gitlab->branch->lastCommitter;
$config->gitlab->dtable->tag->fieldList['lastCommitter']['type']  = 'text';

$config->gitlab->dtable->tag->fieldList['updated']['title'] = $lang->gitlab->branch->lastCommittedDate;
$config->gitlab->dtable->tag->fieldList['updated']['type']  = 'text';

$config->gitlab->dtable->tag->fieldList['actions']['name']  = 'actions';
$config->gitlab->dtable->tag->fieldList['actions']['title'] = $lang->actions;
$config->gitlab->dtable->tag->fieldList['actions']['type']  = 'actions';
$config->gitlab->dtable->tag->fieldList['actions']['menu']  = array('deleteTag');

$config->gitlab->dtable->tag->fieldList['actions']['list']['deleteTag']['icon']         = 'trash';
$config->gitlab->dtable->tag->fieldList['actions']['list']['deleteTag']['hint']         = $lang->gitlab->deleteTag;
$config->gitlab->dtable->tag->fieldList['actions']['list']['deleteTag']['url']          = helper::createLink('gitlab', 'deleteTag', 'gitlabID={gitlabID}&projectID={projectID}&tag_name={tagName}');
$config->gitlab->dtable->tag->fieldList['actions']['list']['deleteTag']['data-confirm'] = $lang->gitlab->tag->confirmDelete;
$config->gitlab->dtable->tag->fieldList['actions']['list']['deleteTag']['className']    = 'ajax-submit';
