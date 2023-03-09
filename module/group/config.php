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

$config->privPackage = new stdclass();
$config->privPackage->create = new stdclass();
$config->privPackage->edit   = new stdclass();
$config->privPackage->create->requiredFields = 'name,module';
$config->privPackage->edit->requiredFields   = 'name,module';

global $lang;
$config->group->priv = new stdclass();
$config->group->priv->search['module']                   = 'priv';
$config->group->priv->search['fields']['name']           = $lang->group->name;
$config->group->priv->search['params']['name']           = array('operator' => 'include', 'control' => 'input',  'values' => '');

