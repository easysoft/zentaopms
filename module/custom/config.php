<?php
$config->custom = new stdClass();
$config->custom->canAdd['story']    = 'reasonList,sourceList,priList';
$config->custom->canAdd['task']     = 'priList,typeList,reasonList';
$config->custom->canAdd['bug']      = 'priList,severityList,osList,browserList,typeList,resolutionList';
$config->custom->canAdd['testcase'] = 'priList,typeList,stageList,resultList,statusList';
$config->custom->canAdd['testtask'] = 'priList';
$config->custom->canAdd['todo']     = 'priList,typeList';
$config->custom->canAdd['user']     = 'roleList';
$config->custom->canAdd['block']    = '';

$config->custom->requiredModules[10] = 'todo';

$config->custom->requiredModules[15] = 'product';
$config->custom->requiredModules[20] = 'story';
$config->custom->requiredModules[25] = 'productplan';
$config->custom->requiredModules[30] = 'release';

$config->custom->requiredModules[35] = 'project';
$config->custom->requiredModules[40] = 'task';
$config->custom->requiredModules[45] = 'build';

$config->custom->requiredModules[50] = 'bug';
$config->custom->requiredModules[55] = 'testcase';
$config->custom->requiredModules[60] = 'testsuite';
$config->custom->requiredModules[65] = 'testreport';
$config->custom->requiredModules[70] = 'testtask';

$config->custom->requiredModules[75] = 'doc';

$config->custom->requiredModules[80] = 'group';
$config->custom->requiredModules[85] = 'user';

$config->custom->requiredModules[95]  = 'entry';
$config->custom->requiredModules[100] = 'webhook';

$config->custom->fieldList['group'] = 'name,desc';

$config->custom->excludeFieldList['product']    = 'order';
$config->custom->excludeFieldList['story']      = 'version,duplicateStory,linkStories,childStories,toBug,fromBug';
$config->custom->excludeFieldList['project']    = 'order';
$config->custom->excludeFieldList['task']       = 'fromBug';
$config->custom->excludeFieldList['bug']        = 'storyVersion,toTask,toStory,hardware,found,confirmed,activatedCount,activatedDate,linkBug,case,caseVersion,result,testtask,repo';
$config->custom->excludeFieldList['testcase']   = 'version,lastRunResult,lastRunDate,lastRunner,linkCase,fromBug';
$config->custom->excludeFieldList['testreport'] = 'objectType';
$config->custom->excludeFieldList['doc']        = 'version';
$config->custom->excludeFieldList['user']       = 'ranzhi,visits,ip,last';
