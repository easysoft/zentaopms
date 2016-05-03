<?php
$lang->custom->common    = '自定义';
$lang->custom->index     = '首页';
$lang->custom->set       = '自定义配置';
$lang->custom->restore   = '恢复默认';
$lang->custom->key       = '键';
$lang->custom->value     = '值';
$lang->custom->flow      = '流程';
$lang->custom->select    = '请选择流程：';
$lang->custom->branch    = '多分支';

$lang->custom->object['story']    = '需求';
$lang->custom->object['task']     = '任务';
$lang->custom->object['bug']      = 'Bug';
$lang->custom->object['testcase'] = '用例';
$lang->custom->object['testtask'] = '版本';
$lang->custom->object['todo']     = '待办';
$lang->custom->object['user']     = '用户';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList']          = '优先级';
$lang->custom->story->fields['sourceList']       = '来源';
$lang->custom->story->fields['reasonList']       = '关闭原因';
$lang->custom->story->fields['stageList']        = '阶段';
$lang->custom->story->fields['statusList']       = '状态';
$lang->custom->story->fields['reviewResultList'] = '评审结果';
$lang->custom->story->fields['review']           = '评审流程';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList']    = '优先级';
$lang->custom->task->fields['typeList']   = '类型';
$lang->custom->task->fields['reasonList'] = '关闭原因';
$lang->custom->task->fields['statusList'] = '状态';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList']        = '优先级';
$lang->custom->bug->fields['severityList']   = '严重程度';
$lang->custom->bug->fields['osList']         = '操作系统';
$lang->custom->bug->fields['browserList']    = '浏览器';
$lang->custom->bug->fields['typeList']       = '类型';
$lang->custom->bug->fields['resolutionList'] = '解决方案';
$lang->custom->bug->fields['statusList']     = '状态';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList']    = '优先级';
$lang->custom->testcase->fields['typeList']   = '类型';
$lang->custom->testcase->fields['stageList']  = '阶段';
$lang->custom->testcase->fields['resultList'] = '执行结果';
$lang->custom->testcase->fields['statusList'] = '状态';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['priList']    = '优先级';
$lang->custom->testtask->fields['statusList'] = '状态';

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = '优先级';
$lang->custom->todo->fields['typeList']   = '类型';
$lang->custom->todo->fields['statusList'] = '状态';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['roleList']   = '职位';
$lang->custom->user->fields['statusList'] = '状态';

$lang->custom->currentLang = '适用当前语言';
$lang->custom->allLang     = '适用所有语言';

$lang->custom->confirmRestore = '是否要恢复默认配置？';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userRole  = '键的长度必须小于20个字符！';
$lang->custom->notice->indexPage['product'] = "从8.2版本起增加了产品主页视图，是否默认进入产品主页？";
$lang->custom->notice->indexPage['project'] = "从8.2版本起增加了项目主页视图，是否默认进入项目主页？";
$lang->custom->notice->indexPage['qa']      = "从8.2版本起增加了测试主页视图，是否默认进入测试主页？";

$lang->custom->storyReview   = '评审流程';
$lang->custom->reviewList[1] = '开启';
$lang->custom->reviewList[0] = '关闭';

$lang->custom->productProject = new stdclass();
$lang->custom->productProject->relation['0_0'] = '产品 - 项目';
$lang->custom->productProject->relation['0_1'] = '产品 - 迭代';
$lang->custom->productProject->relation['1_1'] = '项目 - 迭代';

$lang->custom->productProject->notice = '请根据实际情况选择适合自己团队的概念。';

$lang->custom->menuTip  = '点击显示或隐藏导航条目，拖拽来更改显示顺序。';
$lang->custom->saveFail = '保存失败！';
