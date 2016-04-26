<?php
$config->custom = new stdClass();
$config->custom->canAdd['story']    = 'reasonList,reviewResultList,sourceList,priList';
$config->custom->canAdd['task']     = 'priList,typeList';
$config->custom->canAdd['bug']      = 'priList,severityList,osList,browserList,typeList,resolutionList';
$config->custom->canAdd['testcase'] = 'priList,typeList,stageList';
$config->custom->canAdd['testtask'] = 'priList';
$config->custom->canAdd['todo']     = 'priList,typeList';
$config->custom->canAdd['user']     = 'roleList';

$config->menu = new stdclass();
$config->menu->main = array();
$config->menu->main['novice']  = 'my,company,product,project,qa,doc,report,admin';
