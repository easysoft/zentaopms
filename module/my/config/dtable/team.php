<?php
$config->my->team = new stdclass();
$config->my->team->dtable = $config->company->user->dtable;
$config->my->team->dtable->fieldList['realname']['link'] = array('module' => 'user', 'method' => 'view', 'params' => 'userid={id}&from=my');
unset($config->my->team->dtable->fieldList['actions']);
