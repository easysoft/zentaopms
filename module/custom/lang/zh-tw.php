<?php
$lang->custom->common    = '自定義';
$lang->custom->index     = '首頁';
$lang->custom->set       = '自定義配置';
$lang->custom->restore   = '恢復預設';
$lang->custom->key       = '鍵';
$lang->custom->value     = '值';
$lang->custom->flow      = '流程';
$lang->custom->working   = '工作方式';
$lang->custom->select    = '請選擇流程：';
$lang->custom->branch    = '多分支';

$lang->custom->object['story']    = '需求';
$lang->custom->object['task']     = '任務';
$lang->custom->object['bug']      = 'Bug';
$lang->custom->object['testcase'] = '用例';
$lang->custom->object['testtask'] = '版本';
$lang->custom->object['todo']     = '待辦';
$lang->custom->object['user']     = '用戶';
$lang->custom->object['block']    = '區塊';

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
$lang->custom->task->fields['hours']      = '工時';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList']        = '優先順序';
$lang->custom->bug->fields['severityList']   = '嚴重程度';
$lang->custom->bug->fields['osList']         = '操作系統';
$lang->custom->bug->fields['browserList']    = '瀏覽器';
$lang->custom->bug->fields['typeList']       = '類型';
$lang->custom->bug->fields['resolutionList'] = '解決方案';
$lang->custom->bug->fields['statusList']     = '狀態';
$lang->custom->bug->fields['longlife']       = '久未處理天數';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList']    = '優先順序';
$lang->custom->testcase->fields['typeList']   = '類型';
$lang->custom->testcase->fields['stageList']  = '階段';
$lang->custom->testcase->fields['resultList'] = '執行結果';
$lang->custom->testcase->fields['statusList'] = '狀態';
$lang->custom->testcase->fields['review']     = '評審流程';

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

$lang->custom->block->fields['closed'] = '關閉的區塊';

$lang->custom->currentLang = '適用當前語言';
$lang->custom->allLang     = '適用所有語言';

$lang->custom->confirmRestore = '是否要恢復預設配置？';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userRole             = '鍵的長度必須小於20個字元！';
$lang->custom->notice->canNotAdd            = '該項參與運算，不提供自定義添加功能';
$lang->custom->notice->forceReview          = "指定人提交的%s必須評審。";
$lang->custom->notice->longlife             = 'Bug列表頁面的久未處理標籤中，列出設置天數之前未處理的Bug。';
$lang->custom->notice->priListKey           = '優先順序的鍵應當為數字！';
$lang->custom->notice->severityListKey      = 'Bug嚴重程度的鍵應當為數字！';
$lang->custom->notice->indexPage['product'] = "從8.2版本起增加了產品主頁視圖，是否預設進入產品主頁？";
$lang->custom->notice->indexPage['project'] = "從8.2版本起增加了項目主頁視圖，是否預設進入項目主頁？";
$lang->custom->notice->indexPage['qa']      = "從8.2版本起增加了測試主頁視圖，是否預設進入測試主頁？";

$lang->custom->storyReview   = '評審流程';
$lang->custom->forceReview   = '強制評審';
$lang->custom->reviewList[1] = '開啟';
$lang->custom->reviewList[0] = '關閉';

$lang->custom->workingHours   = '每天可用工時';
$lang->custom->weekend        = '休息日';
$lang->custom->weekendList[2] = '雙休';
$lang->custom->weekendList[1] = '單休';

$lang->custom->productProject = new stdclass();
$lang->custom->productProject->relation['0_0'] = '產品 - 項目';
$lang->custom->productProject->relation['0_1'] = '產品 - 迭代';
$lang->custom->productProject->relation['1_1'] = '項目 - 迭代';

$lang->custom->productProject->notice = '請根據實際情況選擇適合自己團隊的概念。';

$lang->custom->workingList['full']      = '完整研發管理工具';
$lang->custom->workingList['onlyTest']  = '測試管理工具';
$lang->custom->workingList['onlyStory'] = '需求管理工具';
$lang->custom->workingList['onlyTask']  = '任務管理工具';

$lang->custom->menuTip  = '點擊顯示或隱藏導航條目，拖拽來更改顯示順序。';
$lang->custom->saveFail = '保存失敗！';
