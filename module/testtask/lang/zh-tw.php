<?php
/**
 * The testtask module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: zh-tw.php 4490 2013-02-27 03:27:05Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testtask->index            = "版本首頁";
$lang->testtask->create           = "提交測試";
$lang->testtask->reportChart      = '報表統計';
$lang->testtask->delete           = "刪除版本";
$lang->testtask->view             = "概況";
$lang->testtask->edit             = "編輯版本";
$lang->testtask->browse           = "版本列表";
$lang->testtask->linkCase         = "關聯用例";
$lang->testtask->selectVersion    = "選擇版本";
$lang->testtask->unlinkCase       = "移除";
$lang->testtask->batchUnlinkCases = "批量移除用例";
$lang->testtask->batchAssign      = "批量指派";
$lang->testtask->runCase          = "執行";
$lang->testtask->batchRun         = "批量執行";
$lang->testtask->results          = "結果";
$lang->testtask->createBug        = "提Bug";
$lang->testtask->assign           = '指派';
$lang->testtask->cases            = '用例';
$lang->testtask->groupCase        = "分組瀏覽用例";
$lang->testtask->pre              = '上一個';
$lang->testtask->next             = '下一個';
$lang->testtask->start            = "開始";
$lang->testtask->close            = "關閉";
$lang->testtask->wait             = "待測版本";
$lang->testtask->block            = "阻塞";
$lang->testtask->activate         = "激活";
$lang->testtask->testing          = "測試中版本";
$lang->testtask->blocked          = "被阻塞版本";
$lang->testtask->done             = "已測版本";
$lang->testtask->totalStatus      = "全部";
$lang->testtask->all              = "全部" . $lang->productCommon;

$lang->testtask->id             = '編號';
$lang->testtask->common         = '測試版本';
$lang->testtask->product        = '所屬' . $lang->productCommon;
$lang->testtask->project        = '所屬' . $lang->projectCommon;
$lang->testtask->build          = '版本';
$lang->testtask->owner          = '負責人';
$lang->testtask->pri            = '優先順序';
$lang->testtask->name           = '名稱';
$lang->testtask->begin          = '開始日期';
$lang->testtask->end            = '結束日期';
$lang->testtask->desc           = '描述';
$lang->testtask->mailto         = '抄送給';
$lang->testtask->status         = '當前狀態';
$lang->testtask->assignedTo     = '指派給';
$lang->testtask->linkVersion    = '版本';
$lang->testtask->lastRunAccount = '執行人';
$lang->testtask->lastRunTime    = '執行時間';
$lang->testtask->lastRunResult  = '結果';
$lang->testtask->reportField    = '測試總結';
$lang->testtask->files          = '上傳附件';

$lang->testtask->legendDesc      = '版本描述';
$lang->testtask->legendReport    = '測試總結';
$lang->testtask->legendBasicInfo = '基本信息';

$lang->testtask->statusList['wait']    = '未開始';
$lang->testtask->statusList['doing']   = '進行中';
$lang->testtask->statusList['done']    = '已完成';
$lang->testtask->statusList['blocked'] = '被阻塞';

$lang->testtask->priList[0] = '';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = '未關聯';
$lang->testtask->linkByBuild   = '複製版本';
$lang->testtask->linkByStory   = '按需求關聯';
$lang->testtask->linkByBug     = '按Bug關聯';
$lang->testtask->linkBySuite   = '按套件關聯';
$lang->testtask->passAll       = '全部通過';
$lang->testtask->pass          = '通過';
$lang->testtask->fail          = '失敗';
$lang->testtask->showResult    = '共執行<span class="text-info">%s</span>次';
$lang->testtask->showFail      = '失敗<span class="text-danger">%s</span>次';

$lang->testtask->confirmDelete     = '您確認要刪除該版本嗎？';
$lang->testtask->confirmUnlinkCase = '您確認要移除該用例嗎？';
$lang->testtask->noticeNoOther     = '該產品還沒有其他測試版本';

$lang->testtask->assignedToMe  = '指派給我';
$lang->testtask->allCases      = '所有用例';

$lang->testtask->lblCases      = '用例列表';
$lang->testtask->lblUnlinkCase = '移除用例';
$lang->testtask->lblRunCase    = '執行用例';
$lang->testtask->lblResults    = '執行結果';

$lang->testtask->placeholder = new stdclass();
$lang->testtask->placeholder->begin = '開始日期';
$lang->testtask->placeholder->end   = '結束日期';

$lang->testtask->mail = new stdclass();
$lang->testtask->mail->create = new stdclass();
$lang->testtask->mail->edit   = new stdclass();
$lang->testtask->mail->close  = new stdclass();
$lang->testtask->mail->create->title = "%s創建了版本 #%s:%s";
$lang->testtask->mail->edit->title   = "%s編輯了版本 #%s:%s";
$lang->testtask->mail->close->title  = "%s關閉了版本 #%s:%s";

$lang->testtask->action = new stdclass();
$lang->testtask->action->testtaskopened  = '$date, 由 <strong>$actor</strong> 創建版本 <strong>$extra</strong>。' . "\n";
$lang->testtask->action->testtaskstarted = '$date, 由 <strong>$actor</strong> 啟動版本 <strong>$extra</strong>。' . "\n";
$lang->testtask->action->testtaskclosed  = '$date, 由 <strong>$actor</strong> 完成版本 <strong>$extra</strong>。' . "\n";

$lang->testtask->unexecuted = '未執行';

/* 統計報表。*/
$lang->testtask->report = new stdclass();
$lang->testtask->report->common = '報表';
$lang->testtask->report->select = '請選擇報表類型';
$lang->testtask->report->create = '生成報表';
    
$lang->testtask->report->charts['testTaskPerRunResult'] = '用例結果統計';
$lang->testtask->report->charts['testTaskPerType']      = '用例類型統計';
$lang->testtask->report->charts['testTaskPerModule']    = '用例模組統計';
$lang->testtask->report->charts['testTaskPerRunner']    = '用例執行人統計';
$lang->testtask->report->charts['bugSeverityGroups']    = 'Bug嚴重級別分佈';
$lang->testtask->report->charts['bugStatusGroups']      = 'Bug狀態分佈';
$lang->testtask->report->charts['bugOpenedByGroups']    = 'Bug創建者分佈';
$lang->testtask->report->charts['bugResolvedByGroups']  = 'Bug解決者分佈';
$lang->testtask->report->charts['bugResolutionGroups']  = 'Bug解決方案分佈';
$lang->testtask->report->charts['bugModuleGroups']      = 'Bug模組分佈';

$lang->testtask->report->options = new stdclass();
$lang->testtask->report->options->graph  = new stdclass();
$lang->testtask->report->options->type   = 'pie';
$lang->testtask->report->options->width  = 500;
$lang->testtask->report->options->height = 140;
