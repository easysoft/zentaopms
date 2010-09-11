<?php
/**
 * The bug module zh-tw file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青島易軟天創網絡科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: zh-tw.php 993 2010-08-02 10:20:01Z wwccss $
 * @link        http://www.zentao.net
 */
/* 欄位列表。*/
$lang->bug->common         = '缺陷管理';
$lang->bug->id             = 'Bug編號';
$lang->bug->product        = '所屬產品';
$lang->bug->module         = '所屬模組';
$lang->bug->path           = '模組路徑';
$lang->bug->project        = '所屬項目';
$lang->bug->story          = '相關需求';
$lang->bug->storyVersion   = '需求版本';
$lang->bug->task           = '相關任務';
$lang->bug->title          = 'Bug標題';
$lang->bug->severity       = '嚴重程度';
$lang->bug->severityAB     = '級別';
$lang->bug->pri            = '優先順序';
$lang->bug->type           = 'Bug類型';
$lang->bug->os             = '操作系統';
$lang->bug->hardware       = '硬件平台';
$lang->bug->browser        = '瀏覽器';
$lang->bug->machine        = '機器硬件';
$lang->bug->found          = '如何發現';
$lang->bug->steps          = '重現步驟';
$lang->bug->status         = 'Bug狀態';
$lang->bug->mailto         = '抄送給';
$lang->bug->openedBy       = '由誰創建';
$lang->bug->openedByAB     = '創建';
$lang->bug->openedDate     = '創建日期';
$lang->bug->openedBuild    = '影響版本';
$lang->bug->assignedTo     = '指派給';
$lang->bug->assignedDate   = '指派日期';
$lang->bug->resolvedBy     = '解決者';
$lang->bug->resolution     = '解決方案';
$lang->bug->resolutionAB   = '方案';
$lang->bug->resolvedBuild  = '解決版本';
$lang->bug->resolvedDate   = '解決日期';
$lang->bug->closedBy       = '由誰關閉';
$lang->bug->closedDate     = '關閉日期';
$lang->bug->duplicateBug   = '重複Bug';
$lang->bug->lastEditedBy   = '最後修改者';
$lang->bug->lastEditedDate = '最後修改日期';
$lang->bug->linkBug        = '相關Bug';
$lang->bug->case           = '相關用例';
$lang->bug->files          = '附件';
$lang->bug->keywords       = '關鍵詞';
$lang->bug->lastEditedByAB   = '修改者';
$lang->bug->lastEditedDateAB = '修改日期';

/* 方法列表。*/
$lang->bug->index          = '首頁';
$lang->bug->create         = '創建Bug';
$lang->bug->edit           = '編輯Bug';
$lang->bug->browse         = 'Bug列表';
$lang->bug->view           = 'Bug詳情';
$lang->bug->resolve        = '解決Bug';
$lang->bug->close          = '關閉Bug';
$lang->bug->activate       = '激活Bug';
$lang->bug->reportChart    = '報表統計';
$lang->bug->delete         = '刪除Bug';
$lang->bug->saveTemplate   = '保存模板';
$lang->bug->deleteTemplate = '刪除模板';
$lang->bug->customFields   = '自定義欄位';
$lang->bug->restoreDefault = '恢復預設';
$lang->bug->ajaxGetUserBugs    = '介面:我的Bug';
$lang->bug->ajaxGetModuleOwner = '介面:獲得模塊的默認指派人';
$lang->bug->confirmStoryChange = '確認需求變動';

/* 查詢條件列表。*/
$lang->bug->selectProduct  = '請選擇產品';
$lang->bug->byModule       = '按模組';
$lang->bug->assignToMe     = '指派給我';
$lang->bug->openedByMe     = '由我創建';
$lang->bug->resolvedByMe   = '由我解決';
$lang->bug->assignToNull   = '未指派';
$lang->bug->longLifeBugs   = '久未處理';
$lang->bug->postponedBugs  = '被延期';
$lang->bug->allBugs        = '所有Bug';
$lang->bug->moduleBugs     = '按模組瀏覽';
$lang->bug->byQuery        = '搜索';
$lang->bug->needConfirm    = '需求有變動的Bug';
$lang->bug->allProduct     = '所有產品';

/* 頁面標籤。*/
$lang->bug->lblProductAndModule         = '產品模組';
$lang->bug->lblProjectAndTask           = '項目任務';
$lang->bug->lblStory                    = '相關需求';
$lang->bug->lblTypeAndSeverity          = '類型/嚴重程度';
$lang->bug->lblSystemBrowserAndHardware = '系統/瀏覽器';
$lang->bug->lblAssignedTo               = '當前指派';
$lang->bug->lblMailto                   = '抄送給';
$lang->bug->lblLastEdited               = '最後修改';
$lang->bug->lblResolved                 = '由誰解決';
$lang->bug->lblAllFields                = '所有欄位';
$lang->bug->lblCustomFields             = '自定義欄位';

/* legend列表。*/
$lang->bug->legendBasicInfo   = '基本信息';
$lang->bug->legendMailto      = '抄送給';
$lang->bug->legendAttatch     = '附件';
$lang->bug->legendLinkBugs    = '相關Bug';
$lang->bug->legendPrjStoryTask= '項目/需求/任務';
$lang->bug->legendCases       = '相關用例';
$lang->bug->legendSteps       = '重現步驟';
$lang->bug->legendAction      = '操作';
$lang->bug->legendHistory     = '歷史記錄';
$lang->bug->legendComment     = '備註';
$lang->bug->legendLife        = 'BUG的一生';
$lang->bug->legendMisc        = '其相關他';

/* 功能按鈕。*/
$lang->bug->buttonCopy     = '複製';
$lang->bug->buttonEdit     = '編輯';
$lang->bug->buttonActivate = '激活';
$lang->bug->buttonResolve  = '解決';
$lang->bug->buttonClose    = '關閉';
$lang->bug->buttonToList   = '返回';

/* 交互提示。*/
$lang->bug->confirmChangeProduct = '修改產品會導致相應的項目、需求和任務發生變化，確定嗎？';
$lang->bug->confirmDelete        = '您確認要刪除該Bug嗎？';
$lang->bug->setTemplateTitle     = '請輸入bug模板標題（保存之前請先填寫bug重現步驟）：';

/* 模板。*/
$lang->bug->tplStep        = "[步驟]\n\n";
$lang->bug->tplResult      = "[結果]\n\n";
$lang->bug->tplExpect      = "[期望]\n\n";

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
$lang->bug->osList['winxp']   = 'Windows XP';
$lang->bug->osList['win7']    = 'Windows 7';
$lang->bug->osList['vista']   = 'Windows Vista';
$lang->bug->osList['win2000'] = 'Windows 2000';
$lang->bug->osList['winnt']   = 'Windows NT';
$lang->bug->osList['win98']   = 'Windows 98';
$lang->bug->osList['linux']   = 'Linux';
$lang->bug->osList['freebsd'] = 'FreeBSD';
$lang->bug->osList['unix']    = 'Unix';
$lang->bug->osList['others']  = '其他';

$lang->bug->browserList['']         = '';
$lang->bug->browserList['all']      = '全部';
$lang->bug->browserList['ie']       = 'IE系列';
$lang->bug->browserList['ie6']      = 'IE6';
$lang->bug->browserList['ie7']      = 'IE7';
$lang->bug->browserList['ie8']      = 'IE8';
$lang->bug->browserList['firefox']  = 'firefox系列';
$lang->bug->browserList['firefox2'] = 'firefox2';
$lang->bug->browserList['firefx3']  = 'firefox3';
$lang->bug->browserList['opera']    = 'opera系列';
$lang->bug->browserList['opera9']   = 'opera9';
$lang->bug->browserList['oprea10']  = 'opera10';
$lang->bug->browserList['safari']   = 'safari';
$lang->bug->browserList['chrome']   = 'chrome';
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
$lang->bug->typeList['Others']       = '其他';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = '激活';
$lang->bug->statusList['resolved'] = '已解決';
$lang->bug->statusList['closed']   = '已關閉';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = '設計如此';
$lang->bug->resolutionList['duplicate']  = '重複Bug';
$lang->bug->resolutionList['external']   = '外部原因';
$lang->bug->resolutionList['fixed']      = '已解決';
$lang->bug->resolutionList['notrepro']   = '無法重現';
$lang->bug->resolutionList['postponed']  = '延期處理';
$lang->bug->resolutionList['willnotfix'] = "不予解決";

/* 統計報表。*/
$lang->bug->report->common        = '統計報表';
$lang->bug->report->select        = '請選擇報表類型';
$lang->bug->report->create        = '生成報表';
$lang->bug->report->selectAll     = '全選';
$lang->bug->report->selectReverse = '反選';

$lang->bug->report->charts['bugsPerProject']     = '項目Bug數量';
$lang->bug->report->charts['bugsPerModule']      = '模組Bug數量';
$lang->bug->report->charts['openedBugsPerDay']   = '每天新增Bug數';
$lang->bug->report->charts['resolvedBugsPerDay'] = '每天解決Bug數';
$lang->bug->report->charts['closedBugsPerDay']   = '每天關閉的Bug數';
$lang->bug->report->charts['openedBugsPerUser']  = '每人提交的Bug數';
$lang->bug->report->charts['resolvedBugsPerUser']= '每人解決的Bug數';
$lang->bug->report->charts['closedBugsPerUser']  = '每人關閉的Bug數';
$lang->bug->report->charts['bugsPerSeverity']    = 'Bug嚴重程度統計';
$lang->bug->report->charts['bugsPerResolution']  = 'Bug解決方案統計';
$lang->bug->report->charts['bugsPerStatus']      = 'Bug狀態統計';
$lang->bug->report->charts['bugsPerType']        = 'Bug類型統計';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug處理時間統計';
//$lang->bug->report->charts['bugHistories']       = 'Bug處理步驟統計';

$lang->bug->report->options->swf                     = 'pie2d';
$lang->bug->report->options->width                   = 'auto';
$lang->bug->report->options->height                  = 300;
$lang->bug->report->options->graph->baseFontSize     = 12;
$lang->bug->report->options->graph->showNames        = 1;
$lang->bug->report->options->graph->formatNumber     = 1;
$lang->bug->report->options->graph->decimalPrecision = 0;
$lang->bug->report->options->graph->animation        = 0;
$lang->bug->report->options->graph->rotateNames      = 0;
$lang->bug->report->options->graph->yAxisName        = 'COUNT';
$lang->bug->report->options->graph->pieRadius        = 100; // 餅圖直徑。
$lang->bug->report->options->graph->showColumnShadow = 0;   // 是否顯示柱狀圖陰影。

$lang->bug->report->bugsPerProject->graph->xAxisName     = '項目';
$lang->bug->report->bugsPerModule->graph->xAxisName      = '模組';

$lang->bug->report->openedBugsPerDay->swf                = 'column2d';
$lang->bug->report->openedBugsPerDay->height             = 400;
$lang->bug->report->openedBugsPerDay->graph->xAxisName   = '日期';
$lang->bug->report->openedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->resolvedBugsPerDay->swf              = 'column2d';
$lang->bug->report->resolvedBugsPerDay->height           = 400;
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = '日期';
$lang->bug->report->resolvedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->closedBugsPerDay->swf                = 'column2d';
$lang->bug->report->closedBugsPerDay->height             = 400;
$lang->bug->report->closedBugsPerDay->graph->xAxisName   = '日期';
$lang->bug->report->closedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->openedBugsPerUser->graph->xAxisName  = '用戶';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName= '用戶';
$lang->bug->report->closedBugsPerUser->graph->xAxisName  = '用戶';

$lang->bug->report->bugsPerSeverity->graph->xAxisName    = '嚴重程度';
$lang->bug->report->bugsPerResolution->graph->xAxisName  = '解決方案';
$lang->bug->report->bugsPerStatus->graph->xAxisName      = '狀態';
$lang->bug->report->bugsPerType->graph->xAxisName        = '類型';
$lang->bug->report->bugLiveDays->graph->xAxisName        = '處理時間';
$lang->bug->report->bugHistories->graph->xAxisName       = '處理步驟';

/* 操作記錄。*/
$lang->bug->action->resolved = array('main' => '$date, 由 <strong>$actor</strong> 解決，方案為 <strong>$extra</strong>。', 'extra' => $lang->bug->resolutionList);
