<?php
$lang->testreport->common   = '測試報告';
$lang->testreport->browse   = '報告列表';
$lang->testreport->create   = '創建報告';
$lang->testreport->edit     = '編輯報告';
$lang->testreport->delete   = '刪除報告';
$lang->testreport->export   = '導出報告';
$lang->testreport->view     = '報告詳情';
$lang->testreport->recreate = '重新生成報告';

$lang->testreport->title       = '標題';
$lang->testreport->bugTitle    = 'Bug 標題';
$lang->testreport->storyTitle  = '需求標題';
$lang->testreport->project     = '所屬項目';
$lang->testreport->testtask    = '測試版本';
$lang->testreport->tasks       = $lang->testreport->testtask;
$lang->testreport->startEnd    = '起止時間';
$lang->testreport->owner       = '負責人';
$lang->testreport->members     = '參與人員';
$lang->testreport->begin       = '開始時間';
$lang->testreport->end         = '結束時間';
$lang->testreport->stories     = '測試的需求';
$lang->testreport->bugs        = '測試的Bug';
$lang->testreport->builds      = '版本信息';
$lang->testreport->goal        = '項目目標';
$lang->testreport->cases       = '用例';
$lang->testreport->bugInfo     = '缺陷分佈';
$lang->testreport->report      = '總結';
$lang->testreport->legacyBugs  = '遺留的Bug';
$lang->testreport->createdDate = '創建時間';
$lang->testreport->objectID    = '所屬對象';
$lang->testreport->profile     = '概況';
$lang->testreport->value       = '值';
$lang->testreport->none        = '無';
$lang->testreport->all         = '所有報告';
$lang->testreport->deleted     = '已刪除';

$lang->testreport->legendBasic       = '基本信息';
$lang->testreport->legendStoryAndBug = '測試範圍';
$lang->testreport->legendBuild       = '測試輪次';
$lang->testreport->legendCase        = '執行的用例';
$lang->testreport->legendLegacyBugs  = '遺留的Bug';
$lang->testreport->legendReport      = '報表';
$lang->testreport->legendComment     = '總結';
$lang->testreport->legendMore        = '更多功能';

$lang->testreport->bugSeverityGroups   = 'Bug嚴重級別分佈';
$lang->testreport->bugTypeGroups       = 'Bug類型別分佈';
$lang->testreport->bugStatusGroups     = 'Bug狀態分佈';
$lang->testreport->bugOpenedByGroups   = 'Bug創建者分佈';
$lang->testreport->bugResolvedByGroups = 'Bug解決者分佈';
$lang->testreport->bugResolutionGroups = 'Bug解決方案分佈';
$lang->testreport->bugModuleGroups     = 'Bug模組分佈';
$lang->testreport->legacyBugs          = '遺留的Bug';
$lang->testreport->bugConfirmedRate    = '有效Bug率 (方案為已解決或延期 / 狀態為已解決或已關閉)';
$lang->testreport->bugCreateByCaseRate = '用例發現Bug率 (用例創建的Bug / 時間區間中新增的Bug)';

$lang->testreport->caseSummary    = '共有<strong>%s</strong>個用例，共執行<strong>%s</strong>個用例，產生了<strong>%s</strong>個結果，失敗的用例有<strong>%s</strong>個。';
$lang->testreport->buildSummary   = '共測試了<strong>%s</strong>個版本。';
$lang->testreport->confirmDelete  = '是否刪除該報告？';
$lang->testreport->moreNotice     = '更多功能可以參考禪道擴展機制進行擴展，也可以聯繫我們進行定製。';
$lang->testreport->exportNotice   = "由<a href='http://www.zentao.net' target='_blank' style='color:grey'>禪道項目管理軟件</a>導出";
$lang->testreport->noReport       = "報表還沒有生成，請稍候查看。";
$lang->testreport->foundBugTip    = "影響版本在測試輪次內，並且創建時間在測試時間範圍內產生的Bug數。";
$lang->testreport->legacyBugTip   = "Bug狀態是激活，或Bug的解決時間在測試結束時間之後。";
$lang->testreport->fromCaseBugTip = "測試時間範圍內，用例執行失敗後創建的Bug";
$lang->testreport->errorTrunk     = "主幹版本不能創建測試報告，請修改關聯版本！";
$lang->testreport->moreProduct    = "只能對同一個產品生成測試報告。";

$lang->testreport->bugSummary = <<<EOD
共發現<strong>%s</strong>個Bug <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->foundBugTip}'><i class='icon-exclamation-sign'></i></a>，
遺留<strong>%s</strong>個Bug <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->legacyBugTip}'><i class='icon-exclamation-sign'></i></a>。
用例執行產生<strong>%s</strong>個Bug <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->fromCaseBugTip}'><i class='icon-exclamation-sign'></i></a>。
有效Bug率（方案為已解決或延期 / 狀態為已解決或已關閉）：<strong>%s</strong>，用例發現Bug率（用例創建的Bug / 發現Bug數）：<strong>%s</strong>
EOD;
