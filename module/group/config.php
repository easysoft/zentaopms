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

$config->group->hiddenPriv = array('system-dashboard', 'system-dblist', 'system-configdomain', 'system-ossview');

include 'packagemanager.php';
