<?php
$config->custom = new stdClass();

$config->custom->story = new stdClass();
$config->custom->story->fields['priList']          = '优先级';
$config->custom->story->fields['sourceList']       = '来源';
$config->custom->story->fields['reasonList']       = '关闭原因';
$config->custom->story->fields['reviewResultList'] = '评审结果';
$config->custom->story->fields['stageList']        = '阶段';
$config->custom->story->fields['statusList']       = '状态';
$config->custom->story->canAdd = 'reasonList,reviewResultList,sourceList,priList';

$config->custom->task = new stdClass();
$config->custom->task->fields['priList']    = '优先级';
$config->custom->task->fields['typeList']   = '类型';
$config->custom->task->fields['reasonList'] = '关闭原因';
$config->custom->task->fields['statusList'] = '状态';
$config->custom->task->canAdd = 'priList,typeList';

$config->custom->bug = new stdClass();
$config->custom->bug->fields['priList']        = '优先级';
$config->custom->bug->fields['severityList']   = '严重程度';
$config->custom->bug->fields['osList']         = '操作系统';
$config->custom->bug->fields['browserList']    = '浏览器';
$config->custom->bug->fields['typeList']       = '类型';
$config->custom->bug->fields['resolutionList'] = '解决方案';
$config->custom->bug->fields['statusList']     = '状态';
$config->custom->bug->canAdd = 'priList,severityList,osList,browserList,typeList,resolutionList';

$config->custom->testcase = new stdClass();
$config->custom->testcase->fields['priList']    = '优先级';
$config->custom->testcase->fields['typeList']   = '类型';
$config->custom->testcase->fields['stageList']  = '阶段';
$config->custom->testcase->fields['resultList'] = '执行结果';
$config->custom->testcase->fields['statusList'] = '状态';
$config->custom->testcase->canAdd = 'priList,typeList,stageList';

$config->custom->testtask = new stdClass();
$config->custom->testtask->fields['priList']    = '优先级';
$config->custom->testtask->fields['statusList'] = '状态';
$config->custom->testtask->canAdd = 'priList';

$config->custom->todo = new stdClass();
$config->custom->todo->fields['priList']    = '优先级';
$config->custom->todo->fields['typeList']   = '类型';
$config->custom->todo->fields['statusList'] = '状态';
$config->custom->todo->canAdd = 'priList,typeList';

$config->custom->user = new stdClass();
$config->custom->user->fields['roleList']   = '角色';
$config->custom->user->fields['statusList'] = '状态';
$config->custom->user->canAdd = 'roleList';
