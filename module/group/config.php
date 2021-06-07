<?php
$config->group = new stdclass();
$config->group->create = new stdclass();
$config->group->edit   = new stdclass();
$config->group->create->requiredFields = 'name';
$config->group->edit->requiredFields   = 'name';

$config->group->acl = new stdclass();
$config->group->acl->objectTypes['programs'] = 'program';
$config->group->acl->objectTypes['projects'] = 'project';
$config->group->acl->objectTypes['products'] = 'product';
