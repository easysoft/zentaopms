<?php
/**
 * The programplan module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: zh-tw.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->programplan->common        = '項目階段';
$lang->programplan->browse        = '瀏覽甘特圖';
$lang->programplan->gantt         = '甘特圖';
$lang->programplan->list          = '階段列表';
$lang->programplan->create        = '設置階段';
$lang->programplan->edit          = '編輯';
$lang->programplan->delete        = '刪除階段';
$lang->programplan->createSubPlan = '創建二級階段';

$lang->programplan->parent           = '父階段';
$lang->programplan->emptyParent      = '無';
$lang->programplan->name             = '階段名稱';
$lang->programplan->subStageName     = '子階段名稱';
$lang->programplan->percent          = '工作量占比';
$lang->programplan->percentAB        = '工作量占比';
$lang->programplan->planPercent      = '工作量';
$lang->programplan->attribute        = '階段類型';
$lang->programplan->milestone        = '里程碑';
$lang->programplan->taskProgress     = '任務進度';
$lang->programplan->task             = '任務';
$lang->programplan->begin            = '計劃開始';
$lang->programplan->end              = '計劃完成';
$lang->programplan->realBegan        = '實際開始';
$lang->programplan->realEnd          = '實際完成';
$lang->programplan->ac               = '實際花費';
$lang->programplan->sv               = '進度偏差率';
$lang->programplan->cv               = '成本偏差率';
$lang->programplan->planDateRange    = '計划起始日期';
$lang->programplan->realDateRange    = '實際起始日期';
$lang->programplan->output           = '輸出';
$lang->programplan->openedBy         = '由誰創建';
$lang->programplan->openedDate       = '創建日期';
$lang->programplan->editedBy         = '由誰編輯';
$lang->programplan->editedDate       = '編輯日期';
$lang->programplan->duration         = '工期';
$lang->programplan->version          = '版本號';
$lang->programplan->full             = '全屏';
$lang->programplan->today            = '今天';
$lang->programplan->exporting        = '導出';
$lang->programplan->exportFail       = '導出失敗';
$lang->programplan->hideCriticalPath = '隱藏關鍵路徑';
$lang->programplan->showCriticalPath = '顯示關鍵路徑';
$lang->programplan->errorBegin       = '階段的開始時間不能小於所屬項目的開始時間%s';
$lang->programplan->errorEnd         = '階段的結束時間不能大於所屬項目的結束時間%s';
$lang->programplan->emptyBegin       = '『計劃開始』日期不能為空';
$lang->programplan->emptyEnd         = '『計劃完成』日期不能為空';
$lang->programplan->checkBegin       = '『計劃開始』應當為合法的日期';
$lang->programplan->checkEnd         = '『計劃完成』應當為合法的日期';

$lang->programplan->milestoneList[1] = '是';
$lang->programplan->milestoneList[0] = '否';

$lang->programplan->noData        = '暫無數據。';
$lang->programplan->children      = '二級計劃';
$lang->programplan->childrenAB    = '子';
$lang->programplan->confirmDelete = '確定要刪除當前計劃嗎？';
$lang->programplan->workloadTips  = '子階段工作量占比按百分百的比例進行拆分';

$lang->programplan->stageCustom = new stdClass();
$lang->programplan->stageCustom->date = '顯示日期';
$lang->programplan->stageCustom->task = '顯示任務';

$lang->programplan->error                  = new stdclass();
$lang->programplan->error->percentNumber   = '"工作量占比"必須為數字';
$lang->programplan->error->planFinishSmall = '"計劃完成時間"必須大於"計劃開始時間"';
$lang->programplan->error->percentOver     = '工作量占比累計不應當超過100%';
$lang->programplan->error->createdTask     = '已分解任務,不可添加子階段';
$lang->programplan->error->parentWorkload  = '子階段的工作量之和不能大於父階段的工作量:%s';
$lang->programplan->error->parentDuration  = '子階段計劃開始、計劃完成不能超過父階段';
