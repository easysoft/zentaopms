<?php
$config->custom = new stdClass();
$config->custom->canAdd['story']    = 'reasonList,reviewResultList,sourceList,priList';
$config->custom->canAdd['task']     = 'priList,typeList,reasonList';
$config->custom->canAdd['bug']      = 'priList,severityList,osList,browserList,typeList,resolutionList';
$config->custom->canAdd['testcase'] = 'priList,typeList,stageList,resultList,statusList';
$config->custom->canAdd['testtask'] = 'priList';
$config->custom->canAdd['todo']     = 'priList,typeList';
$config->custom->canAdd['user']     = 'roleList';
$config->custom->canAdd['block']    = '';

$config->custom->moblieHidden['main']    = array('repo');
$config->custom->moblieHidden['product'] = array('branch', 'module', 'create');
$config->custom->moblieHidden['project'] = array('create', 'effort', 'product');
$config->custom->moblieHidden['my']      = array('effort', 'changePassword', 'manageContacts');
