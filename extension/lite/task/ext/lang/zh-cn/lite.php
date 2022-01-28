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
$lang->task->index               = "卡片一览";
$lang->task->create              = "建卡片";
$lang->task->batchCreateChildren = "批量建子卡片";
$lang->task->edit                = "编辑卡片";
$lang->task->deleteAction        = "删除卡片";
$lang->task->view                = "查看卡片";
$lang->task->startAction         = "开始卡片";
$lang->task->restartAction       = "继续卡片";
$lang->task->finishAction        = "完成卡片";
$lang->task->pauseAction         = "暂停卡片";
$lang->task->closeAction         = "关闭卡片";
$lang->task->cancelAction        = "取消卡片";
$lang->task->activateAction      = "激活卡片";
$lang->task->exportAction        = "导出卡片";
$lang->task->copy                = '复制卡片';
$lang->task->waitTask            = '未开始的卡片';

$lang->task->common            = '卡片';
$lang->task->name              = '卡片名称';
$lang->task->type              = '卡片类型';
$lang->task->status            = '卡片状态';
$lang->task->desc              = '卡片描述';
$lang->task->assignAction      = '指派卡片';
$lang->task->multiple          = '多人卡片';
$lang->task->children          = '子卡片';
$lang->task->parent            = '父卡片';

/* Fields of zt_taskestimate. */
$lang->task->task    = '卡片';

$lang->task->dittoNotice       = "该卡片与上一卡片不属于同一%s！";
$lang->task->yesterdayFinished = '昨日完成卡片数';
$lang->task->allTasks          = '总卡片';

$lang->task->afterChoices['continueAdding'] = "继续为该{$lang->SRCommon}添加卡片";
$lang->task->afterChoices['toTaskList']     = '返回卡片列表';

$lang->task->legendLife   = '卡片的一生';
$lang->task->legendDesc   = '卡片描述';
$lang->task->legendDetail = '卡片详情';

$lang->task->confirmDelete          = "您确定要删除这个卡片吗？";
$lang->task->confirmDeleteEstimate  = "您确定要删除这个记录吗？";
$lang->task->confirmFinish          = '"预计剩余"为0，确认将卡片状态改为"已完成"吗？';
$lang->task->confirmRecord          = '"剩余"为0，卡片将标记为"已完成"，您确定吗？';
$lang->task->confirmTransfer        = '"当前剩余"为0，卡片将被转交，您确定吗？';
$lang->task->noTask                 = '暂时没有卡片。';
$lang->task->kanbanDenied           = '请先创建看板';
$lang->task->createDenied           = '你不能在该项目添加卡片';
$lang->task->cannotDeleteParent     = '不能删除父卡片。';
$lang->task->addChildTask           = '因该卡片已经产生消耗，为保证数据一致性，我们会帮您创建一条同名子卡片记录该消耗。';

$lang->task->error->skipClose         = '卡片：%s 不是“已完成”或“已取消”状态，确定要关闭吗？';
$lang->task->error->consumed          = '卡片：%s工时不能小于0，忽略该卡片工时的改动';
$lang->task->error->assignedTo        = '当前状态的多人卡片不能指派给卡片团队外的成员。';
$lang->task->error->alreadyStarted    = '此卡片已被启动，不能重复启动！';
$lang->task->error->alreadyConsumed   = '当前选中的父卡片已有消耗。';

/* Report. */
$lang->task->report->value  = '卡片数';

$lang->task->report->charts['tasksPerExecution']    = '按' . $lang->executionCommon . '卡片数统计';
$lang->task->report->charts['tasksPerModule']       = '按模块卡片数统计';
$lang->task->report->charts['tasksPerType']         = '按卡片类型统计';
$lang->task->report->charts['tasksPerStatus']       = '按卡片状态统计';
