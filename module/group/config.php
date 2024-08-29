<?php
$config->group = new stdclass();
$config->group->create = new stdclass();
$config->group->edit   = new stdclass();
$config->group->create->requiredFields = 'name';
$config->group->edit->requiredFields   = 'name';
$config->group->maxToolBarCount        = 13;

$config->group->acl = new stdclass();
$config->group->acl->objectTypes['programs'] = 'program';
$config->group->acl->objectTypes['projects'] = 'project';
$config->group->acl->objectTypes['products'] = 'product';
$config->group->acl->objectTypes['sprints']  = 'sprint';

$config->priv = new stdclass();
$config->priv->create = new stdclass();
$config->priv->edit   = new stdclass();
$config->priv->create->requiredFields = 'name,moduleName,methodName,view,module';
$config->priv->edit->requiredFields   = 'name,moduleName,methodName,view,module';

$config->privPackage = new stdclass();
$config->privPackage->create = new stdclass();
$config->privPackage->edit   = new stdclass();
$config->privPackage->create->requiredFields = 'name,module';
$config->privPackage->edit->requiredFields   = 'name,module';

global $lang;
$config->group->priv = new stdclass();
$config->group->priv->search['module']                   = 'priv';
$config->group->priv->search['fields']['name']           = $lang->group->privName;
$config->group->priv->search['fields']['view']           = $lang->group->view;
$config->group->priv->search['fields']['module']         = $lang->group->module;
$config->group->priv->search['fields']['package']        = $lang->privpackage->belong;
$config->group->priv->search['fields']['recommendPrivs'] = $lang->group->recommendPrivs;
$config->group->priv->search['fields']['dependPrivs']    = $lang->group->dependentPrivs;
$config->group->priv->search['fields']['desc']           = $lang->group->privDesc;

$config->group->priv->search['params']['name']           = array('operator' => 'include', 'control' => 'input',   'values' => '');
$config->group->priv->search['params']['view']           = array('operator' => '=',       'control' => 'select',  'values' => '');
$config->group->priv->search['params']['module']         = array('operator' => '=',       'control' => 'select',  'values' => '');
$config->group->priv->search['params']['package']        = array('operator' => '=',       'control' => 'select',  'values' => '');
$config->group->priv->search['params']['recommendPrivs'] = array('operator' => 'include', 'control' => 'select',  'values' => '');
$config->group->priv->search['params']['dependPrivs']    = array('operator' => 'include', 'control' => 'select',  'values' => '');
$config->group->priv->search['params']['desc']           = array('operator' => 'include', 'control' => 'input',   'values' => '');

$config->group->actionList = array();
$config->group->actionList['manageView']['icon'] = 'eye';
$config->group->actionList['manageView']['text'] = $lang->group->manageView;
$config->group->actionList['manageView']['hint'] = $lang->group->manageView;
$config->group->actionList['manageView']['url']  = helper::createLink('group', 'manageView', 'groupID={id}');

$config->group->actionList['managePriv']['icon']  = 'lock';
$config->group->actionList['managePriv']['text']  = $lang->group->managePriv;
$config->group->actionList['managePriv']['hint']  = $lang->group->managePriv;
$config->group->actionList['managePriv']['url']   = helper::createLink('group', 'managepriv', 'type=byPackage&groupID={id}');
$config->group->actionList['managePriv']['class'] = 'group-managepriv-btn';

$config->group->actionList['manageProjectAdmin']['icon']        = 'persons';
$config->group->actionList['manageProjectAdmin']['text']        = $lang->group->manageProjectAdmin;
$config->group->actionList['manageProjectAdmin']['hint']        = $lang->group->manageProjectAdmin;
$config->group->actionList['manageProjectAdmin']['url']         = helper::createLink('group', 'manageProjectAdmin', 'groupID={id}');

$config->group->actionList['manageMember']['icon']        = 'persons';
$config->group->actionList['manageMember']['text']        = $lang->group->manageMember;
$config->group->actionList['manageMember']['hint']        = $lang->group->manageMember;
$config->group->actionList['manageMember']['url']         = helper::createLink('group', 'manageMember', 'groupID={id}');
$config->group->actionList['manageMember']['data-toggle'] = 'modal';
$config->group->actionList['manageMember']['data-size']   = 'lg';
$config->group->actionList['manageMember']['class']       = 'group-manageMember-btn';

$config->group->actionList['edit']['icon']        = 'edit';
$config->group->actionList['edit']['text']        = $lang->group->edit;
$config->group->actionList['edit']['hint']        = $lang->group->edit;
$config->group->actionList['edit']['url']         = helper::createLink('group', 'edit', 'groupID={id}');
$config->group->actionList['edit']['data-toggle'] = 'modal';

$config->group->actionList['copy']['icon']        = 'copy';
$config->group->actionList['copy']['text']        = $lang->group->copy;
$config->group->actionList['copy']['hint']        = $lang->group->copy;
$config->group->actionList['copy']['url']         = helper::createLink('group', 'copy', 'groupID={id}');
$config->group->actionList['copy']['data-toggle'] = 'modal';

$config->group->actionList['delete']['icon'] = 'trash';
$config->group->actionList['delete']['text'] = $lang->group->delete;
$config->group->actionList['delete']['hint'] = $lang->group->delete;
$config->group->actionList['delete']['url']  = 'javascript:confirmDelete("{id}", "{name}")';

$config->group->hiddenPriv   = array('system-dashboard', 'store-browse', 'store-appView', 'space-getStoreAppInfo', 'system-dblist', 'system-configdomain', 'system-ossview');
$config->group->showNodePriv = array('browse', 'create', 'edit', 'view', 'instruction', 'destroy');

include 'packagemanager.php';
