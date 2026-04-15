<?php
global $lang,$app;
$config->space->dtable = new stdclass();

$config->space->dtable->fieldList['id']['title']    = 'ID';
$config->space->dtable->fieldList['id']['name']     = 'id';
$config->space->dtable->fieldList['id']['type']     = 'id';
$config->space->dtable->fieldList['id']['sortType'] = false;

$config->space->dtable->fieldList['name']['title']    = $lang->space->name;
$config->space->dtable->fieldList['name']['name']     = 'name';
$config->space->dtable->fieldList['name']['type']     = 'shortTitle';
$config->space->dtable->fieldList['name']['flex']     = 4;
$config->space->dtable->fieldList['name']['hint']     = true;
$config->space->dtable->fieldList['name']['sortType'] = false;
$config->space->dtable->fieldList['name']['link']     = helper::createLink('repo', 'maintain', 'inSpace=1&space={id}');;
$config->space->dtable->fieldList['name']['group']    = 1;

$config->space->dtable->fieldList['manager']['title']     = $lang->space->manager;
$config->space->dtable->fieldList['manager']['name']      = 'manager';
$config->space->dtable->fieldList['manager']['sortType']  = false;
$config->space->dtable->fieldList['manager']['hint']      = true;
$config->space->dtable->fieldList['manager']['delimiter'] = ',';
$config->space->dtable->fieldList['manager']['width']     = 350;
$config->space->dtable->fieldList['manager']['group']     = 2;

$config->space->dtable->fieldList['desc']['title'] = $lang->space->desc;
$config->space->dtable->fieldList['desc']['name']  = 'desc';
$config->space->dtable->fieldList['desc']['type']  = 'desc';
$config->space->dtable->fieldList['desc']['group'] = 3;

$config->space->dtable->fieldList['createdDate']['title']      = $lang->space->createdDate;
$config->space->dtable->fieldList['createdDate']['name']       = 'createdDate';
$config->space->dtable->fieldList['createdDate']['type']       = 'datetime';
$config->space->dtable->fieldList['createdDate']['formatDate'] = 'YYYY-MM-dd hh:mm';
$config->space->dtable->fieldList['createdDate']['sortType']   = false;
$config->space->dtable->fieldList['createdDate']['group']      = 4;

$config->space->dtable->fieldList['actions']['name']     = 'actions';
$config->space->dtable->fieldList['actions']['title']    = $lang->actions;
$config->space->dtable->fieldList['actions']['type']     = 'actions';
$config->space->dtable->fieldList['actions']['sortType'] = false;
$config->space->dtable->fieldList['actions']['fixed']    = 'right';
$config->space->dtable->fieldList['actions']['menu']     = array('members', 'group', 'edit');
$config->space->dtable->fieldList['actions']['list']     = $config->space->actionList;
$config->space->dtable->fieldList['actions']['width']    = 100;

$app->loadLang('group');
$config->spaceGroup = new stdclass();
$config->spaceGroup->dtable = new stdclass();

$config->spaceGroup->dtable->fieldList['id']['title']    = $lang->idAB;
$config->spaceGroup->dtable->fieldList['id']['name']     = 'id';
$config->spaceGroup->dtable->fieldList['id']['type']     = 'checkID';
$config->spaceGroup->dtable->fieldList['id']['sort']     = 'number';
$config->spaceGroup->dtable->fieldList['id']['fixed']    = 'left';
$config->spaceGroup->dtable->fieldList['id']['checkbox'] = false;
$config->spaceGroup->dtable->fieldList['id']['width']    = '80';
$config->spaceGroup->dtable->fieldList['id']['group']    = 1;

$config->spaceGroup->dtable->fieldList['name']['title'] = $lang->group->name;
$config->spaceGroup->dtable->fieldList['name']['name']  = 'name';
$config->spaceGroup->dtable->fieldList['name']['fixed'] = 'left';
$config->spaceGroup->dtable->fieldList['name']['flex']  = 1;
$config->spaceGroup->dtable->fieldList['name']['type']  = 'title';
$config->spaceGroup->dtable->fieldList['name']['sort']  = true;
$config->spaceGroup->dtable->fieldList['name']['group'] = 1;

$config->spaceGroup->dtable->fieldList['desc']['title'] = $lang->group->desc;
$config->spaceGroup->dtable->fieldList['desc']['name']  = 'desc';
$config->spaceGroup->dtable->fieldList['desc']['type']  = 'desc';
$config->spaceGroup->dtable->fieldList['desc']['sort']  = true;
$config->spaceGroup->dtable->fieldList['desc']['group'] = 2;

$config->spaceGroup->dtable->fieldList['users']['title'] = $lang->group->users;
$config->spaceGroup->dtable->fieldList['users']['name']  = 'users';
$config->spaceGroup->dtable->fieldList['users']['type']  = 'desc';
$config->spaceGroup->dtable->fieldList['users']['hint']  = true;
$config->spaceGroup->dtable->fieldList['users']['sort']  = true;
$config->spaceGroup->dtable->fieldList['users']['group'] = 3;

$config->spaceGroup->dtable->fieldList['actions']['name']     = 'actions';
$config->spaceGroup->dtable->fieldList['actions']['title']    = $lang->actions;
$config->spaceGroup->dtable->fieldList['actions']['type']     = 'actions';
$config->spaceGroup->dtable->fieldList['actions']['width']    = '140';
$config->spaceGroup->dtable->fieldList['actions']['menu']     = array('managePriv', 'manageGroupMember', 'editGroup', 'deleteGroup');
$config->spaceGroup->dtable->fieldList['actions']['sortType'] = false;
$config->spaceGroup->dtable->fieldList['actions']['fixed']    = 'right';

$config->spaceGroup->dtable->fieldList['actions']['list']['managePriv']['icon'] = 'lock';
$config->spaceGroup->dtable->fieldList['actions']['list']['managePriv']['text'] = $lang->group->managePriv;
$config->spaceGroup->dtable->fieldList['actions']['list']['managePriv']['hint'] = $lang->group->managePriv;
$config->spaceGroup->dtable->fieldList['actions']['list']['managePriv']['url']  = helper::createLink('space', 'managePriv', "spaceID={devopsSpace}&groupID={id}");

$config->spaceGroup->dtable->fieldList['actions']['list']['manageGroupMember']['icon']        = 'persons';
$config->spaceGroup->dtable->fieldList['actions']['list']['manageGroupMember']['text']        = $lang->group->manageMember;
$config->spaceGroup->dtable->fieldList['actions']['list']['manageGroupMember']['hint']        = $lang->group->manageMember;
$config->spaceGroup->dtable->fieldList['actions']['list']['manageGroupMember']['url']         = helper::createLink('space', 'manageGroupMember', "groupID={id}");
$config->spaceGroup->dtable->fieldList['actions']['list']['manageGroupMember']['data-toggle'] = 'modal';
$config->spaceGroup->dtable->fieldList['actions']['list']['manageGroupMember']['data-size']   = 'lg';

$config->spaceGroup->dtable->fieldList['actions']['list']['editGroup']['icon']        = 'edit';
$config->spaceGroup->dtable->fieldList['actions']['list']['editGroup']['text']        = $lang->group->edit;
$config->spaceGroup->dtable->fieldList['actions']['list']['editGroup']['hint']        = $lang->group->edit;
$config->spaceGroup->dtable->fieldList['actions']['list']['editGroup']['url']         = helper::createLink('space', 'editGroup', "groupID={id}");
$config->spaceGroup->dtable->fieldList['actions']['list']['editGroup']['data-toggle'] = 'modal';
$config->spaceGroup->dtable->fieldList['actions']['list']['editGroup']['data-size']   = 'sm';

$config->spaceGroup->dtable->fieldList['actions']['list']['deleteGroup']['icon'] = 'trash';
$config->spaceGroup->dtable->fieldList['actions']['list']['deleteGroup']['text'] = $lang->group->delete;
$config->spaceGroup->dtable->fieldList['actions']['list']['deleteGroup']['hint'] = $lang->group->delete;
$config->spaceGroup->dtable->fieldList['actions']['list']['deleteGroup']['url']  = 'javascript:confirmDelete("{id}", "{name}")';

$config->spaceMember = new stdclass();
$config->spaceMember->dtable = new stdclass();

$config->spaceMember->dtable->fieldList['id']['title']    = $lang->idAB;
$config->spaceMember->dtable->fieldList['id']['name']     = 'id';
$config->spaceMember->dtable->fieldList['id']['type']     = 'checkID';
$config->spaceMember->dtable->fieldList['id']['sort']     = 'number';
$config->spaceMember->dtable->fieldList['id']['fixed']    = 'left';
$config->spaceMember->dtable->fieldList['id']['checkbox'] = false;
$config->spaceMember->dtable->fieldList['id']['group']    = 1;

$config->spaceMember->dtable->fieldList['account']['title'] = $lang->space->account;
$config->spaceMember->dtable->fieldList['account']['name']  = 'account';
$config->spaceMember->dtable->fieldList['account']['fixed'] = 'left';
$config->spaceMember->dtable->fieldList['account']['flex']  = 1;
$config->spaceMember->dtable->fieldList['account']['type']  = 'user';
$config->spaceMember->dtable->fieldList['account']['sort']  = false;
$config->spaceMember->dtable->fieldList['account']['group'] = 1;

$config->spaceMember->dtable->fieldList['role']['title'] = $lang->space->role;
$config->spaceMember->dtable->fieldList['role']['name']  = 'role';
$config->spaceMember->dtable->fieldList['role']['type']  = 'user';
$config->spaceMember->dtable->fieldList['role']['sort']  = false;
$config->spaceMember->dtable->fieldList['role']['group'] = 1;
$config->spaceMember->dtable->fieldList['role']['map'] = $lang->space->roleList;
$config->spaceMember->dtable->fieldList['role']['width'] = 80;

$config->spaceMember->dtable->fieldList['group']['title'] = $lang->space->memberGroup;
$config->spaceMember->dtable->fieldList['group']['name']  = 'group';
$config->spaceMember->dtable->fieldList['group']['sort']  = true;
$config->spaceMember->dtable->fieldList['group']['group'] = 3;
$config->spaceMember->dtable->fieldList['group']['width'] = 300;

$config->spaceMember->dtable->fieldList['repo']['title'] = $lang->space->accessRepo;
$config->spaceMember->dtable->fieldList['repo']['name']  = 'repo';
$config->spaceMember->dtable->fieldList['repo']['sort']  = true;
$config->spaceMember->dtable->fieldList['repo']['group'] = 4;
$config->spaceMember->dtable->fieldList['repo']['width'] = 300;

//$config->spaceMember->dtable->fieldList['artifactRepo']['title'] = $lang->space->accessArtifact;
//$config->spaceMember->dtable->fieldList['artifactRepo']['name']  = 'artifactRepo';
//$config->spaceMember->dtable->fieldList['artifactRepo']['sort']  = true;
//$config->spaceMember->dtable->fieldList['artifactRepo']['group'] = 5;
//$config->spaceMember->dtable->fieldList['artifactRepo']['width'] = 300;

$config->spaceMember->dtable->fieldList['actions']['name']     = 'actions';
$config->spaceMember->dtable->fieldList['actions']['title']    = $lang->actions;
$config->spaceMember->dtable->fieldList['actions']['type']     = 'actions';
$config->spaceMember->dtable->fieldList['actions']['width']    = 60;
$config->spaceMember->dtable->fieldList['actions']['menu']     = array('removeMember');
$config->spaceMember->dtable->fieldList['actions']['sortType'] = false;
$config->spaceMember->dtable->fieldList['actions']['fixed']    = 'right';

$config->spaceMember->dtable->fieldList['actions']['list']['removeMember']['icon']         = 'unlink';
$config->spaceMember->dtable->fieldList['actions']['list']['removeMember']['text']         = $lang->space->removeMember;
$config->spaceMember->dtable->fieldList['actions']['list']['removeMember']['hint']         = $lang->space->removeMember;
$config->spaceMember->dtable->fieldList['actions']['list']['removeMember']['ajaxSubmit']   = true;
$config->spaceMember->dtable->fieldList['actions']['list']['removeMember']['data-confirm'] = array('message' => $lang->space->notice->confirmRemoveMember, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->spaceMember->dtable->fieldList['actions']['list']['removeMember']['url']          = helper::createLink('space', 'removeMember', "spaceID={spaceID}&account={account}");
