<?php
/**
 * The todo module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: zh-cn.php 5022 2013-07-05 06:50:39Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->todo->common       = '待办';
$lang->todo->index        = "待办一览";
$lang->todo->create       = "新增";
$lang->todo->batchCreate  = "批量添加";
$lang->todo->edit         = "更新待办";
$lang->todo->batchEdit    = "批量编辑";
$lang->todo->view         = "待办详情";
$lang->todo->finish       = "完成";
$lang->todo->batchFinish  = "批量完成";
$lang->todo->export       = "导出";
$lang->todo->delete       = "删除待办";
$lang->todo->import2Today = "导入到今天";
$lang->todo->import       = "导入";
$lang->todo->legendBasic  = "基本信息";

$lang->todo->id          = '编号';
$lang->todo->account     = '所有者';
$lang->todo->date        = '日期';
$lang->todo->begin       = '开始';
$lang->todo->end         = '结束';
$lang->todo->beginAB     = '开始';
$lang->todo->endAB       = '结束';
$lang->todo->beginAndEnd = '起止时间';
$lang->todo->type        = '类型';
$lang->todo->pri         = '优先级';
$lang->todo->name        = '名称';
$lang->todo->status      = '状态';
$lang->todo->desc        = '描述';
$lang->todo->private     = '私人事务';

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

$lang->todo->typeList['custom'] = '自定义';
$lang->todo->typeList['bug']    = 'Bug';
$lang->todo->typeList['task']   = $lang->projectCommon . '任务';

global $config;
if($config->global->flow == 'onlyTest' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['task']);
if($config->global->flow == 'onlyTask' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['bug']);

$lang->todo->confirmDelete  = "您确定要删除这条待办吗？";
$lang->todo->thisIsPrivate  = '这是一条私人事务。:)';
$lang->todo->lblDisableDate = '暂时不设定时间';
$lang->todo->noTodo         = '该类型没有待办事务';

$lang->todo->periods['today']      = '今日';
$lang->todo->periods['yesterday']  = '昨日';
$lang->todo->periods['thisWeek']   = '本周';
$lang->todo->periods['lastWeek']   = '上周';
$lang->todo->periods['thisMonth']  = '本月';
$lang->todo->periods['lastmonth']  = '上月';
$lang->todo->periods['thisSeason'] = '本季';
$lang->todo->periods['thisYear']   = '本年';
$lang->todo->periods['future']     = '待定';
$lang->todo->periods['before']     = '未完';
$lang->todo->periods['all']        = '所有';

$lang->todo->action = new stdclass();
$lang->todo->action->finished  = array('main' => '$date, 由 <strong>$actor</strong>完成');
$lang->todo->action->marked    = array('main' => '$date, 由 <strong>$actor</strong> 标记为<strong>$extra</strong>。', 'extra' => 'statusList');
