<?php
/**
 * The bug module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: zh-tw.php 4536 2013-03-02 13:39:37Z wwccss $
 * @link        http://www.zentao.net
 */
/* 欄位列表。*/
$lang->bug->common           = 'Bug';
$lang->bug->id               = 'Bug編號';
$lang->bug->product          = '所屬' . $lang->productCommon;
$lang->bug->branch           = '分支/平台';
$lang->bug->productplan      = '所屬計劃';
$lang->bug->module           = '所屬模組';
$lang->bug->moduleAB         = '模組';
$lang->bug->project          = '所屬' . $lang->projectCommon;
$lang->bug->story            = '相關需求';
$lang->bug->task             = '相關任務';
$lang->bug->title            = 'Bug標題';
$lang->bug->severity         = '嚴重程度';
$lang->bug->severityAB       = '級別';
$lang->bug->pri              = '優先順序';
$lang->bug->type             = 'Bug類型';
$lang->bug->os               = '操作系統';
$lang->bug->browser          = '瀏覽器';
$lang->bug->steps            = '重現步驟';
$lang->bug->status           = 'Bug狀態';
$lang->bug->statusAB         = '狀態';
$lang->bug->activatedCount   = '激活次數';
$lang->bug->activatedCountAB = '激活次數';
$lang->bug->confirmed        = '是否確認';
$lang->bug->toTask           = '轉任務';
$lang->bug->toStory          = '轉需求';
$lang->bug->mailto           = '抄送給';
$lang->bug->openedBy         = '由誰創建';
$lang->bug->openedDate       = '創建日期';
$lang->bug->openedDateAB     = '創建日期';
$lang->bug->openedBuild      = '影響版本';
$lang->bug->assignedTo       = '指派給';
$lang->bug->assignedDate     = '指派日期';
$lang->bug->resolvedBy       = '解決者';
$lang->bug->resolvedByAB     = '解決';
$lang->bug->resolution       = '解決方案';
$lang->bug->resolutionAB     = '方案';
$lang->bug->resolvedBuild    = '解決版本';
$lang->bug->resolvedDate     = '解決日期';
$lang->bug->resolvedDateAB   = '解決日期';
$lang->bug->deadline         = '截止日期';
$lang->bug->closedBy         = '由誰關閉';
$lang->bug->closedDate       = '關閉日期';
$lang->bug->duplicateBug     = '重複ID';
$lang->bug->lastEditedBy     = '最後修改者';
$lang->bug->linkBug          = '相關Bug';
$lang->bug->linkBugs         = '關聯相關Bug';
$lang->bug->unlinkBug        = '移除相關Bug';
$lang->bug->case             = '相關用例';
$lang->bug->files            = '附件';
$lang->bug->keywords         = '關鍵詞';
$lang->bug->lastEditedByAB   = '修改者';
$lang->bug->lastEditedDateAB = '修改日期';
$lang->bug->lastEditedDate   = '修改日期';
$lang->bug->fromCase         = '來源用例';
$lang->bug->toCase           = '生成用例';
$lang->bug->colorTag         = '顏色標籤';

/* 方法列表。*/
$lang->bug->index              = '首頁';
$lang->bug->create             = '提Bug';
$lang->bug->batchCreate        = '批量添加';
$lang->bug->confirmBug         = '確認';
$lang->bug->batchConfirm       = '批量確認';
$lang->bug->edit               = '編輯';
$lang->bug->batchEdit          = '批量編輯';
$lang->bug->batchChangeModule  = '批量修改模組';
$lang->bug->batchClose         = '批量關閉';
$lang->bug->assignTo           = '指派';
$lang->bug->batchAssignTo      = '批量指派';
$lang->bug->browse             = 'Bug列表';
$lang->bug->view               = 'Bug詳情';
$lang->bug->resolve            = '解決';
$lang->bug->batchResolve       = '批量解決';
$lang->bug->close              = '關閉';
$lang->bug->activate           = '激活';
$lang->bug->reportChart        = '報表統計';
$lang->bug->export             = '導出數據';
$lang->bug->delete             = '刪除';
$lang->bug->deleted            = '已刪除';
$lang->bug->saveTemplate       = '保存模板';
$lang->bug->setPublic          = '設為公共模板';
$lang->bug->deleteTemplate     = '刪除模板';
$lang->bug->confirmStoryChange = '確認需求變動';
$lang->bug->copy               = '複製Bug';

/* 查詢條件列表。*/
$lang->bug->assignToMe     = '指派給我';
$lang->bug->openedByMe     = '由我創建';
$lang->bug->resolvedByMe   = '由我解決';
$lang->bug->closedByMe     = '由我關閉';
$lang->bug->assignToNull   = '未指派';
$lang->bug->unResolved     = '未解決';
$lang->bug->toClosed       = '待關閉';
$lang->bug->unclosed       = '未關閉';
$lang->bug->longLifeBugs   = '久未處理';
$lang->bug->postponedBugs  = '被延期';
$lang->bug->overdueBugs    = '過期Bug';
$lang->bug->allBugs        = '所有';
$lang->bug->byQuery        = '搜索';
$lang->bug->needConfirm    = '需求變動';
$lang->bug->allProduct     = '所有' . $lang->productCommon;

$lang->bug->ditto       = '同上';
$lang->bug->dittoNotice = '該bug與上一bug不屬於同一產品！';

/* 頁面標籤。*/
$lang->bug->lblAssignedTo               = '當前指派';
$lang->bug->lblMailto                   = '抄送給';
$lang->bug->lblLastEdited               = '最後修改';
$lang->bug->lblResolved                 = '由誰解決';
$lang->bug->allUsers                    = '所有用戶';
$lang->bug->allBuilds                   = '所有';
$lang->bug->createBuild                 = '新建';

/* legend列表。*/
$lang->bug->legendBasicInfo             = '基本信息';
$lang->bug->legendAttatch               = '附件';
$lang->bug->legendPrjStoryTask          = $lang->projectCommon . '/需求/任務';
$lang->bug->lblTypeAndSeverity          = '類型/嚴重程度';
$lang->bug->lblSystemBrowserAndHardware = '系統/瀏覽器';
$lang->bug->legendSteps                 = '重現步驟';
$lang->bug->legendComment               = '備註';
$lang->bug->legendLife                  = 'BUG的一生';
$lang->bug->legendMisc                  = '其他相關';
$lang->bug->legendRelated               = '其他信息';

/* 功能按鈕。*/
$lang->bug->buttonConfirm        = '確認';

/* 交互提示。*/
$lang->bug->confirmChangeProduct  = "修改{$lang->productCommon}會導致相應的{$lang->projectCommon}、需求和任務發生變化，確定嗎？";
$lang->bug->confirmDelete         = '您確認要刪除該Bug嗎？';
$lang->bug->setTemplateTitle      = '請輸入bug模板標題';
$lang->bug->remindTask            = '該Bug已經轉化為任務，是否更新任務(編號:%s)狀態 ?';
$lang->bug->skipClose             = 'Bug %s 不是已解決狀態，不能關閉。';
$lang->bug->applyTemplate         = '應用模板';
$lang->bug->confirmDeleteTemplate = '您確認要刪除該模板嗎？';

/* 模板。*/
$lang->bug->tplStep   = "<p>[步驟]</p>\n";
$lang->bug->tplResult = "<p>[結果]</p>\n";
$lang->bug->tplExpect = "<p>[期望]</p>";

/* 各個欄位取值列表。*/
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[3] = '3';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']        = '';
$lang->bug->osList['all']     = '全部';
$lang->bug->osList['windows'] = 'Windows';
$lang->bug->osList['win8']    = 'Windows 8';
$lang->bug->osList['win7']    = 'Windows 7';
$lang->bug->osList['vista']   = 'Windows Vista';
$lang->bug->osList['winxp']   = 'Windows XP';
$lang->bug->osList['win2012'] = 'Windows 2012';
$lang->bug->osList['win2008'] = 'Windows 2008';
$lang->bug->osList['win2003'] = 'Windows 2003';
$lang->bug->osList['win2000'] = 'Windows 2000';
$lang->bug->osList['android'] = 'Android';
$lang->bug->osList['ios']     = 'IOS';
$lang->bug->osList['wp8']     = 'WP8';
$lang->bug->osList['wp7']     = 'WP7';
$lang->bug->osList['symbian'] = 'Symbian';
$lang->bug->osList['linux']   = 'Linux';
$lang->bug->osList['freebsd'] = 'FreeBSD';
$lang->bug->osList['osx']     = 'OS X';
$lang->bug->osList['unix']    = 'Unix';
$lang->bug->osList['others']  = '其他';

$lang->bug->browserList['']         = '';
$lang->bug->browserList['all']      = '全部';
$lang->bug->browserList['ie']       = 'IE系列';
$lang->bug->browserList['ie11']     = 'IE11';
$lang->bug->browserList['ie10']     = 'IE10';
$lang->bug->browserList['ie9']      = 'IE9';
$lang->bug->browserList['ie8']      = 'IE8';
$lang->bug->browserList['ie7']      = 'IE7';
$lang->bug->browserList['ie6']      = 'IE6';
$lang->bug->browserList['chrome']   = 'chrome';
$lang->bug->browserList['firefox']  = 'firefox系列';
$lang->bug->browserList['firefox4'] = 'firefox4';
$lang->bug->browserList['firefox3'] = 'firefox3';
$lang->bug->browserList['firefox2'] = 'firefox2';
$lang->bug->browserList['opera']    = 'opera系列';
$lang->bug->browserList['oprea11']  = 'opera11';
$lang->bug->browserList['oprea10']  = 'opera10';
$lang->bug->browserList['opera9']   = 'opera9';
$lang->bug->browserList['safari']   = 'safari';
$lang->bug->browserList['maxthon']  = '傲游';
$lang->bug->browserList['uc']       = 'UC';
$lang->bug->browserList['other']    = '其他';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = '代碼錯誤';
$lang->bug->typeList['interface']    = '界面優化';
$lang->bug->typeList['designchange'] = '設計變更';
$lang->bug->typeList['newfeature']   = '新增需求';
$lang->bug->typeList['designdefect'] = '設計缺陷';
$lang->bug->typeList['config']       = '配置相關';
$lang->bug->typeList['install']      = '安裝部署';
$lang->bug->typeList['security']     = '安全相關';
$lang->bug->typeList['performance']  = '性能問題';
$lang->bug->typeList['standard']     = '標準規範';
$lang->bug->typeList['automation']   = '測試腳本';
$lang->bug->typeList['trackthings']  = '事務跟蹤';
$lang->bug->typeList['others']       = '其他';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = '激活';
$lang->bug->statusList['resolved'] = '已解決';
$lang->bug->statusList['closed']   = '已關閉';

$lang->bug->confirmedList[1] = '已確認';
$lang->bug->confirmedList[0] = '未確認';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = '設計如此';
$lang->bug->resolutionList['duplicate']  = '重複Bug';
$lang->bug->resolutionList['external']   = '外部原因';
$lang->bug->resolutionList['fixed']      = '已解決';
$lang->bug->resolutionList['notrepro']   = '無法重現';
$lang->bug->resolutionList['postponed']  = '延期處理';
$lang->bug->resolutionList['willnotfix'] = "不予解決";
$lang->bug->resolutionList['tostory']    = '轉為需求';

/* 統計報表。*/
$lang->bug->report = new stdclass();
$lang->bug->report->common = '報表';
$lang->bug->report->select = '請選擇報表類型';
$lang->bug->report->create = '生成報表';

$lang->bug->report->charts['bugsPerProject']        = $lang->projectCommon . 'Bug數量';
$lang->bug->report->charts['bugsPerBuild']          = '版本Bug數量';
$lang->bug->report->charts['bugsPerModule']         = '模組Bug數量';
$lang->bug->report->charts['openedBugsPerDay']      = '每天新增Bug數';
$lang->bug->report->charts['resolvedBugsPerDay']    = '每天解決Bug數';
$lang->bug->report->charts['closedBugsPerDay']      = '每天關閉的Bug數';
$lang->bug->report->charts['openedBugsPerUser']     = '每人提交的Bug數';
$lang->bug->report->charts['resolvedBugsPerUser']   = '每人解決的Bug數';
$lang->bug->report->charts['closedBugsPerUser']     = '每人關閉的Bug數';
$lang->bug->report->charts['bugsPerSeverity']       = 'Bug嚴重程度統計';
$lang->bug->report->charts['bugsPerResolution']     = 'Bug解決方案統計';
$lang->bug->report->charts['bugsPerStatus']         = 'Bug狀態統計';
$lang->bug->report->charts['bugsPerActivatedCount'] = 'Bug激活次數統計';
$lang->bug->report->charts['bugsPerPri']            = 'Bug優先順序統計';
$lang->bug->report->charts['bugsPerType']           = 'Bug類型統計';
$lang->bug->report->charts['bugsPerAssignedTo']     = '指派給統計';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug處理時間統計';
//$lang->bug->report->charts['bugHistories']       = 'Bug處理步驟統計';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph  = new stdclass();
$lang->bug->report->options->type   = 'pie';
$lang->bug->report->options->width  = 500;
$lang->bug->report->options->height = 140;

$lang->bug->report->bugsPerProject        = new stdclass();
$lang->bug->report->bugsPerBuild          = new stdclass();
$lang->bug->report->bugsPerModule         = new stdclass();
$lang->bug->report->openedBugsPerDay      = new stdclass();
$lang->bug->report->resolvedBugsPerDay    = new stdclass();
$lang->bug->report->closedBugsPerDay      = new stdclass();
$lang->bug->report->openedBugsPerUser     = new stdclass();
$lang->bug->report->resolvedBugsPerUser   = new stdclass();
$lang->bug->report->closedBugsPerUser     = new stdclass();
$lang->bug->report->bugsPerSeverity       = new stdclass();
$lang->bug->report->bugsPerResolution     = new stdclass();
$lang->bug->report->bugsPerStatus         = new stdclass();
$lang->bug->report->bugsPerActivatedCount = new stdclass();
$lang->bug->report->bugsPerType           = new stdclass();
$lang->bug->report->bugsPerPri            = new stdclass();
$lang->bug->report->bugsPerAssignedTo     = new stdclass();
$lang->bug->report->bugLiveDays           = new stdclass();
$lang->bug->report->bugHistories          = new stdclass();

$lang->bug->report->bugsPerProject->graph        = new stdclass();
$lang->bug->report->bugsPerBuild->graph          = new stdclass();
$lang->bug->report->bugsPerModule->graph         = new stdclass();
$lang->bug->report->openedBugsPerDay->graph      = new stdclass();
$lang->bug->report->resolvedBugsPerDay->graph    = new stdclass();
$lang->bug->report->closedBugsPerDay->graph      = new stdclass();
$lang->bug->report->openedBugsPerUser->graph     = new stdclass();
$lang->bug->report->resolvedBugsPerUser->graph   = new stdclass();
$lang->bug->report->closedBugsPerUser->graph     = new stdclass();
$lang->bug->report->bugsPerSeverity->graph       = new stdclass();
$lang->bug->report->bugsPerResolution->graph     = new stdclass();
$lang->bug->report->bugsPerStatus->graph         = new stdclass();
$lang->bug->report->bugsPerActivatedCount->graph = new stdclass();
$lang->bug->report->bugsPerType->graph           = new stdclass();
$lang->bug->report->bugsPerPri->graph           = new stdclass();
$lang->bug->report->bugsPerAssignedTo->graph     = new stdclass();
$lang->bug->report->bugLiveDays->graph           = new stdclass();
$lang->bug->report->bugHistories->graph          = new stdclass();

$lang->bug->report->bugsPerProject->graph->xAxisName     = $lang->projectCommon;
$lang->bug->report->bugsPerBuild->graph->xAxisName       = '版本';
$lang->bug->report->bugsPerModule->graph->xAxisName      = '模組';

$lang->bug->report->openedBugsPerDay->type                = 'bar';
$lang->bug->report->openedBugsPerDay->graph->xAxisName   = '日期';

$lang->bug->report->resolvedBugsPerDay->type              = 'bar';
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = '日期';

$lang->bug->report->closedBugsPerDay->type                = 'bar';
$lang->bug->report->closedBugsPerDay->graph->xAxisName   = '日期';

$lang->bug->report->openedBugsPerUser->graph->xAxisName  = '用戶';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName= '用戶';
$lang->bug->report->closedBugsPerUser->graph->xAxisName  = '用戶';

$lang->bug->report->bugsPerSeverity->graph->xAxisName       = '嚴重程度';
$lang->bug->report->bugsPerResolution->graph->xAxisName     = '解決方案';
$lang->bug->report->bugsPerStatus->graph->xAxisName         = '狀態';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = '激活次數';
$lang->bug->report->bugsPerPri->graph->xAxisName            = '優先順序';
$lang->bug->report->bugsPerType->graph->xAxisName           = '類型';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName     = '指派給';
$lang->bug->report->bugLiveDays->graph->xAxisName           = '處理時間';
$lang->bug->report->bugHistories->graph->xAxisName          = '處理步驟';

/* 操作記錄。*/
$lang->bug->action = new stdclass();
$lang->bug->action->resolved          = array('main' => '$date, 由 <strong>$actor</strong> 解決，方案為 <strong>$extra</strong> $appendLink。', 'extra' => 'resolutionList');
$lang->bug->action->tostory           = array('main' => '$date, 由 <strong>$actor</strong> 轉為<strong>需求</strong>，編號為 <strong>$extra</strong>。');
$lang->bug->action->totask            = array('main' => '$date, 由 <strong>$actor</strong> 導入為<strong>任務</strong>，編號為 <strong>$extra</strong>。');
$lang->bug->action->linked2plan         = array('main' => '$date, 由 <strong>$actor</strong> 關聯到計劃 <strong>$extra</strong>。');
$lang->bug->action->unlinkedfromplan    = array('main' => '$date, 由 <strong>$actor</strong> 從計劃 <strong>$extra</strong> 移除。');
$lang->bug->action->linked2build        = array('main' => '$date, 由 <strong>$actor</strong> 關聯到版本 <strong>$extra</strong>。');
$lang->bug->action->unlinkedfrombuild   = array('main' => '$date, 由 <strong>$actor</strong> 從版本 <strong>$extra</strong> 移除。');
$lang->bug->action->linked2release      = array('main' => '$date, 由 <strong>$actor</strong> 關聯到發佈 <strong>$extra</strong>。');
$lang->bug->action->unlinkedfromrelease = array('main' => '$date, 由 <strong>$actor</strong> 從發佈 <strong>$extra</strong> 移除。');
$lang->bug->action->linkrelatedbug      = array('main' => '$date, 由 <strong>$actor</strong> 關聯相關Bug <strong>$extra</strong>。');
$lang->bug->action->unlinkrelatedbug    = array('main' => '$date, 由 <strong>$actor</strong> 移除相關Bug <strong>$extra</strong>。');

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->chooseBuilds = '選擇相關版本...';
$lang->bug->placeholder->newBuildName = '新版本名稱';

$lang->bug->featureBar['browse']['unclosed']      = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['all']           = $lang->bug->allBugs;
$lang->bug->featureBar['browse']['assigntome']    = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['openedbyme']    = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['resolvedbyme']  = $lang->bug->resolvedByMe;
$lang->bug->featureBar['browse']['unconfirmed']   = $lang->bug->confirmedList[0];
$lang->bug->featureBar['browse']['assigntonull']  = $lang->bug->assignToNull;
$lang->bug->featureBar['browse']['unresolved']    = $lang->bug->unResolved;
$lang->bug->featureBar['browse']['toclosed']      = $lang->bug->toClosed;
$lang->bug->featureBar['browse']['longlifebugs']  = $lang->bug->longLifeBugs;
$lang->bug->featureBar['browse']['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->featureBar['browse']['overduebugs']   = $lang->bug->overdueBugs;
$lang->bug->featureBar['browse']['needconfirm']   = $lang->bug->needConfirm;
