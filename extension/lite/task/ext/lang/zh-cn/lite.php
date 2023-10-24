<?php
/**
 * The task module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: zh-cn.php 5040 2013-07-06 06:22:18Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->task->index               = "任务一览";
$lang->task->create              = "建任务";
$lang->task->batchCreateChildren = "批量建子任务";
$lang->task->edit                = "编辑任务";
$lang->task->deleteAction        = "删除任务";
$lang->task->view                = "查看任务";
$lang->task->startAction         = "开始任务";
$lang->task->restartAction       = "继续任务";
$lang->task->finishAction        = "完成任务";
$lang->task->pauseAction         = "暂停任务";
$lang->task->closeAction         = "关闭任务";
$lang->task->cancelAction        = "取消任务";
$lang->task->activateAction      = "激活任务";
$lang->task->exportAction        = "导出任务";
$lang->task->copy                = '复制任务';
$lang->task->waitTask            = '未开始的任务';
$lang->task->region              = '所属区域';
$lang->task->lane                = '所属泳道';
$lang->task->execution           = '所属看板';

$lang->task->module       = '所属目录';
$lang->task->allModule    = '所有目录';
$lang->task->common       = '任务';
$lang->task->name         = '任务名称';
$lang->task->type         = '任务类型';
$lang->task->status       = '任务状态';
$lang->task->desc         = '任务描述';
$lang->task->assignAction = '指派任务';
$lang->task->multiple     = '多人任务';
$lang->task->children     = '子任务';
$lang->task->parent       = '父任务';

/* Fields of zt_taskestimate. */
$lang->task->task = '任务';

$lang->task->dittoNotice       = "该任务与上一任务不属于同一%s！";
$lang->task->yesterdayFinished = '昨日完成任务数';
$lang->task->allTasks          = '总任务';

$lang->task->afterChoices['continueAdding'] = "继续为该{$lang->SRCommon}添加任务";
$lang->task->afterChoices['toTaskList']     = '返回任务列表';

$lang->task->legendLife   = '任务的一生';
$lang->task->legendDesc   = '任务描述';
$lang->task->legendDetail = '任务详情';

$lang->task->confirmDelete         = "您确定要删除这个任务吗？";
$lang->task->confirmDeleteEstimate = "您确定要删除这个记录吗？";
$lang->task->confirmFinish         = '"预计剩余"为0，确认将任务状态改为"已完成"吗？';
$lang->task->confirmRecord         = '"剩余"为0，任务将标记为"已完成"，您确定吗？';
$lang->task->confirmTransfer       = '"当前剩余"为0，任务将被转交，您确定吗？';
$lang->task->noTask                = '暂时没有任务。';
$lang->task->kanbanDenied          = '请先创建看板';
$lang->task->createDenied          = "你不能在该{$lang->projectCommon}添加任务";
$lang->task->cannotDeleteParent    = '不能删除父任务。';
$lang->task->addChildTask          = '因该任务已经产生消耗，为保证数据一致性，我们会帮您创建一条同名子任务记录该消耗。';

$lang->task->error->skipClose       = '任务：%s 不是“已完成”或“已取消”状态，确定要关闭吗？';
$lang->task->error->consumed        = '任务：%s工时不能小于0，忽略该任务工时的改动';
$lang->task->error->assignedTo      = '当前状态的多人任务不能指派给任务团队外的成员。';
$lang->task->error->alreadyStarted  = '此任务已被启动，不能重复启动！';
$lang->task->error->alreadyConsumed = '当前选中的父任务已有消耗。';

/* Report. */
$lang->task->report->value = '任务数';

$lang->task->report->charts['tasksPerExecution'] = '按' . $lang->executionCommon . '任务数统计';
$lang->task->report->charts['tasksPerModule']    = '按模块任务数统计';
$lang->task->report->charts['tasksPerType']      = '按任务类型统计';
$lang->task->report->charts['tasksPerStatus']    = '按任务状态统计';
