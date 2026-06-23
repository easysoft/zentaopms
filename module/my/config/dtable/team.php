<?php
$config->my->team = new stdclass();
$config->my->team->dtable = clone $config->company->user->dtable;
$config->my->team->dtable->fieldList['realname']['link'] = array('module' => 'user', 'method' => 'view', 'params' => 'userid={id}&from=my');
if(isset($config->my->team->dtable->fieldList['actions']['list']['edit']['url']['params'])) $config->my->team->dtable->fieldList['actions']['list']['edit']['url']['params'] = 'userID={id}&from=my';
