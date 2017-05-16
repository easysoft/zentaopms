<?php
/**
 * The project module zh-tw file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: zh-tw.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* 欄位列表。*/
$lang->project->common        = $lang->projectCommon . '視圖';
$lang->project->allProjects   = '全部';
$lang->project->type          = $lang->projectCommon . '類型';
$lang->project->name          = $lang->projectCommon . '名稱';
$lang->project->code          = $lang->projectCommon . '代號';
$lang->project->begin         = '開始日期';
$lang->project->end           = '結束日期';
$lang->project->dateRange     = '起始日期';
$lang->project->to            = '至';
$lang->project->days          = '可用工作日';
$lang->project->day           = '天';
$lang->project->workHour      = '工時';
$lang->project->status        = $lang->projectCommon . '狀態';
$lang->project->desc          = $lang->projectCommon . '描述';
$lang->project->owner         = '負責人';
$lang->project->PO            = $lang->productCommon . '負責人';
$lang->project->PM            = $lang->projectCommon . '負責人';
$lang->project->QD            = '測試負責人';
$lang->project->RD            = '發佈負責人';
$lang->project->acl           = '訪問控制';
$lang->project->teamname      = '團隊名稱';
$lang->project->order         = $lang->projectCommon . '排序';
$lang->project->products      = '相關' . $lang->productCommon;
$lang->project->whitelist     = '分組白名單';
$lang->project->totalEstimate = '總預計';
$lang->project->totalConsumed = '總消耗';
$lang->project->totalLeft     = '總剩餘';
$lang->project->Left          = '剩餘';
$lang->project->progess       = '進度';
$lang->project->hours         = '預計 %s 消耗 %s 剩餘 %s';
$lang->project->viewBug       = '查看bug';
$lang->project->noProduct     = "無{$lang->productCommon}{$lang->projectCommon}";
$lang->project->createStory   = "新增需求";
$lang->project->all           = '所有';
$lang->project->undone        = '未完成';
$lang->project->unclosed      = '未關閉';
$lang->project->typeDesc      = "運維{$lang->projectCommon}沒有需求、bug、版本、測試功能，同時禁用燃盡圖。";
$lang->project->mine          = '我負責：';
$lang->project->other         = '其他：';
$lang->project->deleted       = '已刪除';
$lang->project->delayed       = '已延期';

$lang->project->start    = '開始';
$lang->project->activate = '激活';
$lang->project->putoff   = '延期';
$lang->project->suspend  = '掛起';
$lang->project->close    = '關閉';

$lang->project->typeList['sprint']    = "短期$lang->projectCommon";
$lang->project->typeList['waterfall'] = "長期$lang->projectCommon";
$lang->project->typeList['ops']       = "運維$lang->projectCommon";

$lang->project->endList[7]    = '一星期';
$lang->project->endList[14]   = '兩星期';
$lang->project->endList[31]   = '一個月';
$lang->project->endList[62]   = '兩個月';
$lang->project->endList[93]   = '三個月';
$lang->project->endList[186]  = '半年';
$lang->project->endList[365]  = '一年';

$lang->team = new stdclass();
$lang->team->account    = '用戶';
$lang->team->role       = '角色';
$lang->team->join       = '加盟日';
$lang->team->hours      = '可用工時/天';
$lang->team->days       = '可用工日';
$lang->team->totalHours = '總計';
 
$lang->project->basicInfo = '基本信息';
$lang->project->otherInfo = '其他信息';

/* 欄位取值列表。*/
$lang->project->statusList['wait']      = '未開始';
$lang->project->statusList['doing']     = '進行中';
$lang->project->statusList['suspended'] = '已掛起';
$lang->project->statusList['done']      = '已完成';

$lang->project->aclList['open']    = "預設設置(有{$lang->projectCommon}視圖權限，即可訪問)";
$lang->project->aclList['private'] = "私有{$lang->projectCommon}(只有{$lang->projectCommon}團隊成員才能訪問)";
$lang->project->aclList['custom']  = "自定義白名單(團隊成員和白名單的成員可以訪問)";

/* 方法列表。*/
$lang->project->index            = "{$lang->projectCommon}首頁";
$lang->project->task             = '任務列表';
$lang->project->groupTask        = '分組瀏覽任務';
$lang->project->story            = '需求列表';
$lang->project->bug              = 'Bug列表';
$lang->project->dynamic          = '動態';
$lang->project->build            = '版本列表';
$lang->project->testtask         = '測試任務';
$lang->project->burn             = '燃盡圖';
$lang->project->baseline         = '基準綫';
$lang->project->computeBurn      = '更新';
$lang->project->burnData         = '燃盡圖數據';
$lang->project->fixFirst         = '修改首天工時';
$lang->project->team             = '團隊成員';
$lang->project->doc              = '文檔列表';
$lang->project->manageProducts   = '關聯' . $lang->productCommon;
$lang->project->linkStory        = '關聯需求';
$lang->project->unlinkStoryTasks = '未關聯需求任務';
$lang->project->linkedProducts   = '已關聯';
$lang->project->unlinkedProducts = '未關聯';
$lang->project->view             = "{$lang->projectCommon}概況";
$lang->project->create           = "添加{$lang->projectCommon}";
$lang->project->copy             = "複製{$lang->projectCommon}";
$lang->project->delete           = "刪除{$lang->projectCommon}";
$lang->project->browse           = "瀏覽{$lang->projectCommon}";
$lang->project->edit             = "編輯{$lang->projectCommon}";
$lang->project->batchEdit        = "批量編輯";
$lang->project->manageMembers    = '團隊管理';
$lang->project->unlinkMember     = '移除成員';
$lang->project->unlinkStory      = '移除需求';
$lang->project->batchUnlinkStory = '批量移除需求';
$lang->project->importTask       = '轉入任務';
$lang->project->importBug        = '導入Bug';
$lang->project->updateOrder      = '排序';
$lang->project->tree             = '樹狀圖';

/* 分組瀏覽。*/
$lang->project->allTasks             = '所有';
$lang->project->assignedToMe         = '指派給我';

$lang->project->statusSelects['']             = '更多';
$lang->project->statusSelects['wait']         = '未開始';
$lang->project->statusSelects['doing']        = '進行中';
$lang->project->statusSelects['finishedbyme'] = '我完成';
$lang->project->statusSelects['done']         = '已完成';
$lang->project->statusSelects['closed']       = '已關閉';
$lang->project->statusSelects['cancel']       = '已取消';

$lang->project->groups['']           = '分組查看';
$lang->project->groups['story']      = '需求分組';
$lang->project->groups['status']     = '狀態分組';
$lang->project->groups['pri']        = '優先順序分組';
$lang->project->groups['assignedTo'] = '指派給分組';
$lang->project->groups['finishedBy'] = '完成者分組';
$lang->project->groups['closedBy']   = '關閉者分組';
$lang->project->groups['type']       = '類型分組';

$lang->project->groupFilter['story']['all']         = $lang->project->all;
$lang->project->groupFilter['story']['linked']      = '已關聯需求的任務';
$lang->project->groupFilter['pri']['all']           = $lang->project->all;
$lang->project->groupFilter['pri']['noset']         = '未設置';
$lang->project->groupFilter['assignedTo']['undone'] = '未完成';
$lang->project->groupFilter['assignedTo']['all']    = $lang->project->all;

$lang->project->byQuery              = '搜索';

/* 查詢條件列表。*/
$lang->project->allProject      = "所有{$lang->projectCommon}";
$lang->project->aboveAllProduct = "以上所有{$lang->productCommon}";
$lang->project->aboveAllProject = "以上所有{$lang->projectCommon}";

/* 頁面提示。*/
$lang->project->selectProject   = "請選擇{$lang->projectCommon}";
$lang->project->beginAndEnd     = '起止時間';
$lang->project->lblStats        = '工時統計';
$lang->project->stats           = '可用工時<strong>%s</strong>工時，總共預計<strong>%s</strong>工時，已經消耗<strong>%s</strong>工時，預計剩餘<strong>%s</strong>工時';
$lang->project->taskSummary     = "本頁共 <strong>%s</strong> 個任務，未開始 <strong>%s</strong>，進行中 <strong>%s</strong>，總預計<strong>%s</strong>工時，已消耗<strong>%s</strong>工時，剩餘<strong>%s</strong>工時。";
$lang->project->memberHours     = "%s共有 <strong>%s</strong> 個可用工時，";
$lang->project->groupSummary    = "本組共 <strong>%s</strong> 個任務，未開始 <strong>%s</strong>，進行中 <strong>%s</strong>，總預計<strong>%s</strong>工時，已消耗<strong>%s</strong>工時，剩餘<strong>%s</strong>工時。";
$lang->project->groupSummaryAB  = "總任務<strong>%s</strong> ，未開始<strong>%s</strong>，進行中<strong>%s</strong>。<br />總預計<strong>%s</strong>，已消耗<strong>%s</strong>，剩餘<strong>%s</strong>。";
$lang->project->noTimeSummary   = "本組共 <strong>%s</strong> 個任務，未開始 <strong>%s</strong>，進行中 <strong>%s</strong>";
$lang->project->wbs             = "分解任務";
$lang->project->batchWBS        = "批量分解";
$lang->project->howToUpdateBurn = "<a href='http://api.zentao.net/goto.php?item=burndown&lang=zh-tw' target='_blank' title='如何更新燃盡圖？' class='btn btn-sm'>幫助</a>";
$lang->project->whyNoStories    = "看起來沒有需求可以關聯。請檢查下{$lang->projectCommon}關聯的{$lang->productCommon}中有沒有需求，而且要確保它們已經審核通過。";
$lang->project->productStories  = "{$lang->projectCommon}關聯的需求是{$lang->productCommon}需求的子集，並且只有評審通過的需求才能關聯。請<a href='%s'>關聯需求</a>。";
$lang->project->doneProjects    = '已結束';
$lang->project->selectDept      = '選擇部門';
$lang->project->selectDeptTitle = '選擇一個部門的成員';
$lang->project->copyTeam        = '複製團隊';
$lang->project->copyFromTeam    = "複製自{$lang->projectCommon}團隊： <strong>%s</strong>";
$lang->project->noMatched       = "找不到包含'%s'的$lang->projectCommon";
$lang->project->copyTitle       = "請選擇一個{$lang->projectCommon}來複制";
$lang->project->copyTeamTitle   = "選擇一個{$lang->projectCommon}團隊來複制";
$lang->project->copyNoProject   = "沒有可用的{$lang->projectCommon}來複制";
$lang->project->copyFromProject = "複製自{$lang->projectCommon} <strong>%s</strong>";
$lang->project->cancelCopy      = '取消複製';
$lang->project->byPeriod        = '按時間段';
$lang->project->byUser          = '按用戶';

/* 交互提示。*/
$lang->project->confirmDelete         = "您確定刪除{$lang->projectCommon}[%s]嗎？";
$lang->project->confirmUnlinkMember   = "您確定從該{$lang->projectCommon}中移除該用戶嗎？";
$lang->project->confirmUnlinkStory    = "您確定從該{$lang->projectCommon}中移除該需求嗎？";
$lang->project->errorNoLinkedProducts = "該{$lang->projectCommon}沒有關聯的{$lang->productCommon}，系統將轉到{$lang->productCommon}關聯頁面";
$lang->project->accessDenied          = "您無權訪問該{$lang->projectCommon}！";
$lang->project->tips                  = '提示';
$lang->project->afterInfo             = "{$lang->projectCommon}添加成功，您現在可以進行以下操作：";
$lang->project->setTeam               = '設置團隊';
$lang->project->linkStory             = '關聯需求';
$lang->project->createTask            = '添加任務';
$lang->project->goback                = "返回任務列表";
$lang->project->noweekend             = '去除周末';
$lang->project->withweekend           = '顯示周末';
$lang->project->interval              = '間隔';
$lang->project->fixFirstWithLeft      = '修改剩餘工時';

/* 統計。*/
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption      = "燃盡圖";
$lang->project->charts->burn->graph->xAxisName    = "日期";
$lang->project->charts->burn->graph->yAxisName    = "HOUR";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code      = '團隊內部的簡稱';
$lang->project->placeholder->totalLeft = '項目開始時的總預計工時';

$lang->project->selectGroup = new stdclass();
$lang->project->selectGroup->done = '(已結束)';

$lang->project->orderList['pri_asc']    = "需求優先順序正序";
$lang->project->orderList['pri_desc']   = "需求優先順序倒序";
$lang->project->orderList['id_asc']     = "需求ID正序";
$lang->project->orderList['id_desc']    = "需求ID倒序";
$lang->project->orderList['stage_asc']  = "需求階段正序";
$lang->project->orderList['stage_desc'] = "需求階段倒序";

$lang->project->kanban      = "看板";
$lang->project->printKanban = "打印看板";
$lang->project->bugList     = "Bug列表";

$lang->printKanban = new stdclass();
$lang->printKanban->common  = '看板打印';
$lang->printKanban->content = '內容';
$lang->printKanban->print   = '打印';

$lang->printKanban->taskStatus = '狀態';

$lang->printKanban->typeList['all']       = '全部';
$lang->printKanban->typeList['increment'] = '增量';

$lang->project->featureBar['task']['unclosed']     = $lang->project->unclosed;
$lang->project->featureBar['task']['all']          = $lang->project->allTasks;
$lang->project->featureBar['task']['assignedtome'] = $lang->project->assignedToMe;
$lang->project->featureBar['task']['delayed']      = '已延期';
$lang->project->featureBar['task']['needconfirm']  = '需求變動';
$lang->project->featureBar['task']['status']       = $lang->project->statusSelects[''];

$lang->project->treeLevel = array();
$lang->project->treeLevel['root']    = '全部摺疊';
$lang->project->treeLevel['story']   = '顯示需求';
$lang->project->treeLevel['task']    = '顯示任務';
$lang->project->treeLevel['all']     = '全部展開';

global $config;
if($config->global->flow == 'onlyTask')
{
    unset($lang->project->groups['story']);
    unset($lang->project->featureBar['task']['needconfirm']);
}
