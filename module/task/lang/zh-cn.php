<?php
/**
 * The task module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->task->index              = "任务一览";
$lang->task->create             = "新增";
$lang->task->batchCreate        = "批量添加";
$lang->task->batchEdit          = "批量编辑";
$lang->task->import             = "导入之前未完任务";
$lang->task->edit               = "编辑";
$lang->task->delete             = "删除";
$lang->task->view               = "查看任务";
$lang->task->logEfforts         = "记录工时";
$lang->task->start              = "开始";
$lang->task->finish             = "完成";
$lang->task->close              = "关闭";
$lang->task->batchClose         = "批量关闭";
$lang->task->cancel             = "取消";
$lang->task->activate           = "激活";
$lang->task->export             = "导出";
$lang->task->reportChart        = "报表统计";
$lang->task->fromBug            = '来源Bug';
$lang->task->confirmStoryChange = "确认需求变动";

$lang->task->common            = '任务';
$lang->task->id                = '编号';
$lang->task->project           = '所属项目';
$lang->task->module            = '所属模块';
$lang->task->story             = '相关需求';
$lang->task->storyVersion      = '需求版本';
$lang->task->name              = '任务名称';
$lang->task->type              = '任务类型';
$lang->task->pri               = '优先级';
$lang->task->mailto            = '抄送给';
$lang->task->estimate          = '最初预计';
$lang->task->estimateAB        = '预';
$lang->task->left              = '预计剩余';
$lang->task->leftAB            = '剩';
$lang->task->consumed          = '已经消耗';
$lang->task->consumedAB        = '耗';
$lang->task->hour              = '小时';
$lang->task->estStarted        = '预计开始';
$lang->task->realStarted       = '实际开始';
$lang->task->deadline          = '截止日期';
$lang->task->deadlineAB        = '截止';
$lang->task->status            = '任务状态';
$lang->task->statusCustom      = '状态排序';
$lang->task->desc              = '任务描述';
$lang->task->assign            = '指派';
$lang->task->assignTo          = $lang->task->assign;
$lang->task->assignedTo        = '指派给';
$lang->task->assignedToAB      = '指派给';
$lang->task->assignedDate      = '指派日期';
$lang->task->openedBy          = '由谁创建';
$lang->task->openedByAB        = '创建者';
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

$lang->task->same  = '同上';
$lang->task->notes = '(注：“任务类型”、“任务名称”、“优先级”和“预计工时”必需填写，否则此行无效)';

$lang->task->statusList['']        = '';
$lang->task->statusList['wait']    = '未开始';
$lang->task->statusList['doing']   = '进行中';
$lang->task->statusList['done']    = '已完成';
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
$lang->task->afterChoices['toTastList']     = '返回任务列表';
$lang->task->afterChoices['toStoryList']    = '返回需求列表';

$lang->task->buttonEdit       = '编辑';
$lang->task->buttonClose      = '关闭';
$lang->task->buttonCancel     = '取消';
$lang->task->buttonActivate   = '激活';
$lang->task->buttonLogEfforts = '记录工时';
$lang->task->buttonDelete     = '删除';
$lang->task->buttonBackToList = '返回';
$lang->task->buttonStart      = '开始';
$lang->task->buttonDone       = '完成';

$lang->task->legendBasic  = '基本信息';
$lang->task->legendEffort = '工时信息';
$lang->task->legendLife   = '任务的一生';
$lang->task->legendDesc   = '任务描述';
$lang->task->legendAction = '操作';

$lang->task->ajaxGetUserTasks    = "接口:我的任务";
$lang->task->ajaxGetProjectTasks = "接口:项目任务";
$lang->task->confirmDelete       = "您确定要删除这个任务吗？";
$lang->task->copyStoryTitle      = "同需求";
$lang->task->afterSubmit         = "添加之后";
$lang->task->successSaved        = "成功添加，";
$lang->task->delayWarning        = " <strong class='delayed f-14px'> 延期%s天 </strong>";
$lang->task->remindBug           = "该任务为Bug转化得到，是否更新Bug:%s ?";

/* 统计报表。*/
$lang->task->report->common = '报表';
$lang->task->report->select = '请选择报表类型';
$lang->task->report->create = '生成报表';

$lang->task->report->charts['tasksPerProject']      = '项目任务数统计';
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

$lang->task->report->options->swf                     = 'pie2d';
$lang->task->report->options->width                   = 'auto';
$lang->task->report->options->height                  = 300;
$lang->task->report->options->graph->baseFontSize     = 12;
$lang->task->report->options->graph->showNames        = 1;
$lang->task->report->options->graph->formatNumber     = 1;
$lang->task->report->options->graph->decimalPrecision = 0;
$lang->task->report->options->graph->animation        = 0;
$lang->task->report->options->graph->rotateNames      = 0;
$lang->task->report->options->graph->yAxisName        = 'COUNT';
$lang->task->report->options->graph->pieRadius        = 100; // 饼图直径。
$lang->task->report->options->graph->showColumnShadow = 0;   // 是否显示柱状图阴影。

$lang->task->report->tasksPerProject->graph->xAxisName      = '项目';
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

$lang->task->report->finishedTasksPerDay->swf                = 'column2d';
$lang->task->report->finishedTasksPerDay->height             = 400;
$lang->task->report->finishedTasksPerDay->graph->xAxisName   = '日期';
$lang->task->report->finishedTasksPerDay->graph->rotateNames = '1';

$lang->task->placeholder->estimate = '对该任务最初的预计';
$lang->task->placeholder->mailto   = '输入用户名进行选择'; 
