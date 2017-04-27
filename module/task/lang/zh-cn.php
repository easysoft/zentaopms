<?php
/**
 * The task module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: zh-cn.php 5040 2013-07-06 06:22:18Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->task->index              = "任务一览";
$lang->task->create             = "建任务";
$lang->task->batchCreate        = "批量添加";
$lang->task->batchEdit          = "批量编辑";
$lang->task->batchChangeModule  = "批量修改模块";
$lang->task->batchClose         = "批量关闭";
$lang->task->batchCancel        = "批量取消";
$lang->task->edit               = "编辑";
$lang->task->delete             = "删除";
$lang->task->deleted            = "已删除";
$lang->task->delayed            = '延期';
$lang->task->view               = "查看任务";
$lang->task->logEfforts         = "记录工时";
$lang->task->record             = "工时";
$lang->task->start              = "开始";
$lang->task->restart            = "继续";
$lang->task->finish             = "完成";
$lang->task->pause              = "暂停";
$lang->task->close              = "关闭";
$lang->task->cancel             = "取消";
$lang->task->activate           = "激活";
$lang->task->export             = "导出数据";
$lang->task->reportChart        = "报表统计";
$lang->task->fromBug            = '来源Bug';
$lang->task->case               = '相关用例';
$lang->task->confirmStoryChange = "确认需求变动";
$lang->task->progess            = '进度';
$lang->task->progessTips        = '已消耗/(已消耗+剩余)';
$lang->task->copy               = '复制任务';

$lang->task->common            = '任务';
$lang->task->id                = '编号';
$lang->task->project           = '所属' . $lang->projectCommon;
$lang->task->module            = '所属模块';
$lang->task->moduleAB          = '模块';
$lang->task->story             = '相关需求';
$lang->task->storySpec         = '需求描述';
$lang->task->storyVerify       = '验收标准';
$lang->task->name              = '任务名称';
$lang->task->type              = '任务类型';
$lang->task->pri               = '优先级';
$lang->task->mailto            = '抄送给';
$lang->task->estimate          = '最初预计';
$lang->task->estimateAB        = '预';
$lang->task->left              = '预计剩余';
$lang->task->leftAB            = '剩';
$lang->task->consumed          = '总消耗';
$lang->task->consumedAB        = '消耗';
$lang->task->hour              = '小时';
$lang->task->consumedThisTime  = '工时';
$lang->task->leftThisTime      = '剩余';
$lang->task->datePlan          = '日程规划';
$lang->task->estStarted        = '预计开始';
$lang->task->realStarted       = '实际开始';
$lang->task->date              = '日期';
$lang->task->deadline          = '截止日期';
$lang->task->deadlineAB        = '截止';
$lang->task->status            = '任务状态';
$lang->task->desc              = '任务描述';
$lang->task->assign            = '指派';
$lang->task->assignTo          = $lang->task->assign;
$lang->task->batchAssignTo     = '批量指派';
$lang->task->assignedTo        = '指派给';
$lang->task->assignedToAB      = '指派给';
$lang->task->assignedDate      = '指派日期';
$lang->task->openedBy          = '由谁创建';
$lang->task->openedDate        = '创建日期';
$lang->task->openedDateAB      = '创建';
$lang->task->finishedBy        = '由谁完成';
$lang->task->finishedByAB      = '完成者';
$lang->task->finishedDate      = '完成时间';
$lang->task->finishedDateAB    = '完成';
$lang->task->canceledBy        = '由谁取消';
$lang->task->canceledDate      = '取消时间';
$lang->task->closedBy          = '由谁关闭';
$lang->task->closedDate        = '关闭时间';
$lang->task->closedReason      = '关闭原因';
$lang->task->lastEditedBy      = '最后修改';
$lang->task->lastEditedDate    = '最后修改日期';
$lang->task->lastEdited        = '最后编辑';
$lang->task->recordEstimate    = '工时';
$lang->task->editEstimate      = '编辑工时';
$lang->task->deleteEstimate    = '删除工时';
$lang->task->colorTag          = '颜色标签';
$lang->task->files             = '附件';
$lang->task->hasConsumed       = '已消耗';

$lang->task->ditto         = '同上';
$lang->task->dittoNotice   = "该任务与上一任务不属于同一项目！";
$lang->task->selectAllUser = '全部';
$lang->task->noStory       = '无需求';
$lang->task->noAssigned    = '未指派';
$lang->task->noFinished    = '未完成';
$lang->task->noClosed      = '未关闭';

$lang->task->statusList['']        = '';
$lang->task->statusList['wait']    = '未开始';
$lang->task->statusList['doing']   = '进行中';
$lang->task->statusList['done']    = '已完成';
$lang->task->statusList['pause']   = '已暂停';
$lang->task->statusList['cancel']  = '已取消';
$lang->task->statusList['closed']  = '已关闭';

$lang->task->typeList['']        = '';
$lang->task->typeList['design']  = '设计';
$lang->task->typeList['devel']   = '开发';
$lang->task->typeList['test']    = '测试';
$lang->task->typeList['study']   = '研究';
$lang->task->typeList['discuss'] = '讨论';
$lang->task->typeList['ui']      = '界面';
$lang->task->typeList['affair']  = '事务';
$lang->task->typeList['misc']    = '其他';

$lang->task->priList[0]  = '';
$lang->task->priList[3]  = '3';
$lang->task->priList[1]  = '1';
$lang->task->priList[2]  = '2';
$lang->task->priList[4]  = '4';

$lang->task->reasonList['']       = '';
$lang->task->reasonList['done']   = '已完成';
$lang->task->reasonList['cancel'] = '已取消';

$lang->task->afterChoices['continueAdding'] = '继续为该需求添加任务';
$lang->task->afterChoices['toTaskList']     = '返回任务列表';
$lang->task->afterChoices['toStoryList']    = '返回需求列表';

$lang->task->legendBasic  = '基本信息';
$lang->task->legendEffort = '工时信息';
$lang->task->legendLife   = '任务的一生';
$lang->task->legendDesc   = '任务描述';

$lang->task->confirmDelete         = "您确定要删除这个任务吗？";
$lang->task->confirmDeleteEstimate = "您确定要删除这个记录吗？";
$lang->task->copyStoryTitle        = "同需求";
$lang->task->afterSubmit           = "添加之后";
$lang->task->successSaved          = "成功添加，";
$lang->task->delayWarning          = " <strong class='delayed f-14px'> 延期%s天 </strong>";
$lang->task->remindBug             = "该任务为Bug转化得到，是否更新Bug:%s ?";
$lang->task->confirmChangeProject  = "修改{$lang->projectCommon}会导致相应的所属模块、相关需求和指派人发生变化，确定吗？";
$lang->task->confirmFinish         = '"预计剩余"为0，确认将任务状态改为"已完成"吗？';
$lang->task->confirmRecord         = '"剩余"为0，任务将标记为"已完成"，您确定吗？';
$lang->task->noticeLinkStory       = "没有可关联的相关需求，您可以为当前项目%s，然后%s";
$lang->task->noticeSaveRecord      = '您有尚未保存的工时记录，请先将其保存。';
$lang->task->commentActions        = '%s. %s, 由 <strong>%s</strong> 添加备注。';

$lang->task->error = new stdclass();
$lang->task->error->consumedNumber   = '"已经消耗"必须为数字';
$lang->task->error->estimateNumber   = '"预计剩余"必须为数字';
$lang->task->error->consumedSmall    = '"已经消耗"必须大于之前消耗';
$lang->task->error->consumedThisTime = '请填写"工时"';
$lang->task->error->left             = '请填写"剩余"';
$lang->task->error->work             = '"备注"必须小于255个字符';
$lang->task->error->skipClose        = '任务：%s 不是“已完成”或“已取消”状态，确定要关闭吗？';
$lang->task->error->consumed         = '任务：%s工时不能小于0，忽略该任务工时的改动';

/* 统计报表。*/
$lang->task->report = new stdclass();
$lang->task->report->common = '报表';
$lang->task->report->select = '请选择报表类型';
$lang->task->report->create = '生成报表';
$lang->task->report->value  = '任务数';

$lang->task->report->charts['tasksPerProject']      = $lang->projectCommon . '任务数统计';
$lang->task->report->charts['tasksPerModule']       = '模块任务数统计';
$lang->task->report->charts['tasksPerAssignedTo']   = '指派给统计';
$lang->task->report->charts['tasksPerType']         = '任务类型统计';
$lang->task->report->charts['tasksPerPri']          = '优先级统计';
$lang->task->report->charts['tasksPerStatus']       = '任务状态统计';
$lang->task->report->charts['tasksPerDeadline']     = '截止日期统计';
$lang->task->report->charts['tasksPerEstimate']     = '预计时间统计';
$lang->task->report->charts['tasksPerLeft']         = '剩余时间统计';
$lang->task->report->charts['tasksPerConsumed']     = '消耗时间统计';
$lang->task->report->charts['tasksPerFinishedBy']   = '由谁完成统计';
$lang->task->report->charts['tasksPerClosedReason'] = '关闭原因统计';
$lang->task->report->charts['finishedTasksPerDay']  = '每天完成统计';

$lang->task->report->options = new stdclass();
$lang->task->report->options->graph = new stdclass();
$lang->task->report->options->type   = 'pie';
$lang->task->report->options->width  = 500;
$lang->task->report->options->height = 140;

$lang->task->report->tasksPerProject      = new stdclass();
$lang->task->report->tasksPerModule       = new stdclass();
$lang->task->report->tasksPerAssignedTo   = new stdclass();
$lang->task->report->tasksPerType         = new stdclass();
$lang->task->report->tasksPerPri          = new stdclass();
$lang->task->report->tasksPerStatus       = new stdclass();
$lang->task->report->tasksPerDeadline     = new stdclass();
$lang->task->report->tasksPerEstimate     = new stdclass();
$lang->task->report->tasksPerLeft         = new stdclass();
$lang->task->report->tasksPerConsumed     = new stdclass();
$lang->task->report->tasksPerFinishedBy   = new stdclass();
$lang->task->report->tasksPerClosedReason = new stdclass();
$lang->task->report->finishedTasksPerDay  = new stdclass();

$lang->task->report->tasksPerProject->item      = $lang->projectCommon;
$lang->task->report->tasksPerModule->item       = '模块';
$lang->task->report->tasksPerAssignedTo->item   = '用户';
$lang->task->report->tasksPerType->item         = '类型';
$lang->task->report->tasksPerPri->item          = '优先级';
$lang->task->report->tasksPerStatus->item       = '状态';
$lang->task->report->tasksPerDeadline->item     = '日期';
$lang->task->report->tasksPerEstimate->item     = '预计';
$lang->task->report->tasksPerLeft->item         = '剩余';
$lang->task->report->tasksPerConsumed->item     = '消耗';
$lang->task->report->tasksPerFinishedBy->item   = '用户';
$lang->task->report->tasksPerClosedReason->item = '原因';
$lang->task->report->finishedTasksPerDay->item  = '日期';

$lang->task->report->tasksPerProject->graph      = new stdclass();
$lang->task->report->tasksPerModule->graph       = new stdclass();
$lang->task->report->tasksPerAssignedTo->graph   = new stdclass();
$lang->task->report->tasksPerType->graph         = new stdclass();
$lang->task->report->tasksPerPri->graph          = new stdclass();
$lang->task->report->tasksPerStatus->graph       = new stdclass();
$lang->task->report->tasksPerDeadline->graph     = new stdclass();
$lang->task->report->tasksPerEstimate->graph     = new stdclass();
$lang->task->report->tasksPerLeft->graph         = new stdclass();
$lang->task->report->tasksPerConsumed->graph     = new stdclass();
$lang->task->report->tasksPerFinishedBy->graph   = new stdclass();
$lang->task->report->tasksPerClosedReason->graph = new stdclass();
$lang->task->report->finishedTasksPerDay->graph  = new stdclass();

$lang->task->report->tasksPerProject->graph->xAxisName      = $lang->projectCommon;
$lang->task->report->tasksPerModule->graph->xAxisName       = '模块';
$lang->task->report->tasksPerAssignedTo->graph->xAxisName   = '用户';
$lang->task->report->tasksPerType->graph->xAxisName         = '类型';
$lang->task->report->tasksPerPri->graph->xAxisName          = '优先级';
$lang->task->report->tasksPerStatus->graph->xAxisName       = '状态';
$lang->task->report->tasksPerDeadline->graph->xAxisName     = '日期';
$lang->task->report->tasksPerEstimate->graph->xAxisName     = '时间';
$lang->task->report->tasksPerLeft->graph->xAxisName         = '时间';
$lang->task->report->tasksPerConsumed->graph->xAxisName     = '时间';
$lang->task->report->tasksPerFinishedBy->graph->xAxisName   = '用户';
$lang->task->report->tasksPerClosedReason->graph->xAxisName = '关闭原因';

$lang->task->report->finishedTasksPerDay->type               = 'bar';
$lang->task->report->finishedTasksPerDay->graph->xAxisName   = '日期';
