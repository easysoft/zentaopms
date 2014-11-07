<?php
$lang->custom->common    = '自定義';
$lang->custom->index     = '首頁';
$lang->custom->set       = '自定義配置';
$lang->custom->restore   = '恢復預設';
$lang->custom->key       = '鍵';
$lang->custom->value     = '值';

$lang->custom->object['story']    = '需求';
$lang->custom->object['task']     = '任務';
$lang->custom->object['bug']      = 'Bug';
$lang->custom->object['testcase'] = '用例';
$lang->custom->object['testtask'] = '版本';
$lang->custom->object['todo']     = '待辦';
$lang->custom->object['user']     = '用戶';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList']          = '優先順序';
$lang->custom->story->fields['sourceList']       = '來源';
$lang->custom->story->fields['reasonList']       = '關閉原因';
$lang->custom->story->fields['stageList']        = '階段';
$lang->custom->story->fields['statusList']       = '狀態';
$lang->custom->story->fields['reviewResultList'] = '評審結果';
$lang->custom->story->fields['review']           = '評審流程';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList']    = '優先順序';
$lang->custom->task->fields['typeList']   = '類型';
$lang->custom->task->fields['reasonList'] = '關閉原因';
$lang->custom->task->fields['statusList'] = '狀態';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList']        = '優先順序';
$lang->custom->bug->fields['severityList']   = '嚴重程度';
$lang->custom->bug->fields['osList']         = '操作系統';
$lang->custom->bug->fields['browserList']    = '瀏覽器';
$lang->custom->bug->fields['typeList']       = '類型';
$lang->custom->bug->fields['resolutionList'] = '解決方案';
$lang->custom->bug->fields['statusList']     = '狀態';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList']    = '優先順序';
$lang->custom->testcase->fields['typeList']   = '類型';
$lang->custom->testcase->fields['stageList']  = '階段';
$lang->custom->testcase->fields['resultList'] = '執行結果';
$lang->custom->testcase->fields['statusList'] = '狀態';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['priList']    = '優先順序';
$lang->custom->testtask->fields['statusList'] = '狀態';

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = '優先順序';
$lang->custom->todo->fields['typeList']   = '類型';
$lang->custom->todo->fields['statusList'] = '狀態';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['roleList']   = '職位';
$lang->custom->user->fields['statusList'] = '狀態';

$lang->custom->currentLang = '適用當前語言';
$lang->custom->allLang     = '適用所有語言';

$lang->custom->confirmRestore = '是否要恢復預設語言配置？';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userRole = '鍵的長度必須小於20個字元！';

$lang->custom->storyReview   = '評審流程';
$lang->custom->reviewList[1] = '開啟';
$lang->custom->reviewList[0] = '關閉';
