<?php
/**
 * The todo module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->todo->common       = 'TODO';
$lang->todo->index        = "todo一览";
$lang->todo->create       = "新增TODO";
$lang->todo->batchCreate  = "批量添加";
$lang->todo->edit         = "更新TODO";
$lang->todo->view         = "TODO详情";
$lang->todo->viewAB       = "详情";
$lang->todo->markDone     = "未完成";
$lang->todo->markWait     = "已完成";
$lang->todo->markDoing    = "已完成";
$lang->todo->mark         = "更改状态";
$lang->todo->export       = "导出";
$lang->todo->delete       = "删除TODO";
$lang->todo->browse       = "浏览TODO";
$lang->todo->import2Today = "导入到今天";
$lang->todo->changeStatus = "更改";

$lang->todo->id          = '编号';
$lang->todo->account     = '所有者';
$lang->todo->date        = '日期';
$lang->todo->begin       = '开始时间';
$lang->todo->beginAB     = '开始';
$lang->todo->end         = '结束时间';
$lang->todo->endAB       = '结束';
$lang->todo->beginAndEnd = '起止时间';
$lang->todo->type        = '类型';
$lang->todo->pri         = '优先级';
$lang->todo->name        = '名称';
$lang->todo->status      = '状态';
$lang->todo->desc        = '描述';
$lang->todo->private     = '私人事务';
$lang->todo->idvalue     = '任务或Bug';

$lang->todo->notes = '(注：“日期”、“名称”必需填写，否则此行无效)';

$lang->todo->week         = '星期';
$lang->todo->today        = '今天';
$lang->todo->weekDateList = '一,二,三,四,五,六,天';
$lang->todo->dayInFuture  = '暂不指定';
$lang->todo->confirmBug   = '该Todo关联的是Bug #%s，需要修改它吗？';
$lang->todo->confirmTask  = '该Todo关联的是Task #%s，需要修改它吗？';

$lang->todo->statusList['wait']     = '未开始';
$lang->todo->statusList['doing']    = '进行中';
$lang->todo->statusList['done']     = '已完成';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[3] = '一般';
$lang->todo->priList[1] = '最高';
$lang->todo->priList[2] = '较高';
$lang->todo->priList[4] = '最低';

$lang->todo->typeList->custom = '自定义';
$lang->todo->typeList->bug    = 'Bug';
$lang->todo->typeList->task   = '项目任务';

$lang->todo->confirmDelete  = "您确定要删除这个todo吗？";
$lang->todo->successMarked  = "成功切换状态！";
$lang->todo->thisIsPrivate  = '这是一条私人事务。:)';
$lang->todo->lblDisableDate = '暂时不设定时间';

$lang->todo->thisWeekTodos = '本周计划';
$lang->todo->lastWeekTodos = '上周工作';
$lang->todo->futureTodos   = '暂不指定';
$lang->todo->allDaysTodos  = '所有TODO';
$lang->todo->allUndone     = '之前未完';
$lang->todo->todayTodos    = '今日安排';

$lang->todo->action->marked = array('main' => '$date, 由 <strong>$actor</strong> 标记为<strong>$extra</strong>。', 'extra' => $lang->todo->statusList);
