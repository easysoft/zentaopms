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
$lang->todo->create       = "添加待办";
$lang->todo->createCycle  = "创建周期待办";
$lang->todo->assignTo     = "指派给";
$lang->todo->assignedDate = "指派日期";
$lang->todo->assignAction = "指派待办";
$lang->todo->start        = "开始待办";
$lang->todo->activate     = "激活待办";
$lang->todo->batchCreate  = "批量添加";
$lang->todo->edit         = "编辑待办";
$lang->todo->close        = "关闭待办";
$lang->todo->batchClose   = "批量关闭";
$lang->todo->batchEdit    = "批量编辑";
$lang->todo->view         = "待办详情";
$lang->todo->finish       = "完成待办";
$lang->todo->batchFinish  = "批量完成";
$lang->todo->export       = "导出待办";
$lang->todo->delete       = "删除待办";
$lang->todo->import2Today = "导入到今天";
$lang->todo->import       = "导入";
$lang->todo->legendBasic  = "基本信息";
$lang->todo->cycle        = "周期";
$lang->todo->cycleConfig  = "周期设置";

$lang->todo->reasonList['story'] = "转{$lang->storyCommon}";
$lang->todo->reasonList['task']  = "转任务";
$lang->todo->reasonList['bug']   = "转Bug";
$lang->todo->reasonList['done']  = "完成";

$lang->todo->id           = '编号';
$lang->todo->account      = '所有者';
$lang->todo->date         = '日期';
$lang->todo->begin        = '开始';
$lang->todo->end          = '结束';
$lang->todo->beginAB      = '开始';
$lang->todo->endAB        = '结束';
$lang->todo->beginAndEnd  = '起止时间';
$lang->todo->idvalue      = '关联编号';
$lang->todo->type         = '类型';
$lang->todo->pri          = '优先级';
$lang->todo->name         = '待办名称';
$lang->todo->status       = '状态';
$lang->todo->desc         = '描述';
$lang->todo->private      = '私人事务';
$lang->todo->cycleDay     = '天';
$lang->todo->cycleWeek    = '周';
$lang->todo->cycleMonth   = '月';
$lang->todo->assignedTo   = '指派给';
$lang->todo->assignedBy   = '由谁指派';
$lang->todo->finishedBy   = '由谁完成';
$lang->todo->finishedDate = '完成时间';
$lang->todo->closedBy     = '由谁关闭';
$lang->todo->closedDate   = '关闭时间';
$lang->todo->deadline     = '过期时间';

$lang->todo->every      = '间隔';
$lang->todo->beforeDays = "<span class='input-group-addon'>提前</span>%s<span class='input-group-addon'>天生成待办</span>";
$lang->todo->dayNames   = array(1 => '星期一', 2 => '星期二', 3 => '星期三', 4 => '星期四', 5 => '星期五', 6 => '星期六', 0 => '星期日');

$lang->todo->confirmBug   = '该Todo关联的是Bug #%s，需要修改它吗？';
$lang->todo->confirmTask  = '该Todo关联的是Task #%s，需要修改它吗？';
$lang->todo->confirmStory = '该Todo关联的是Story #%s，需要修改它吗？';

$lang->todo->statusList['wait']   = '未开始';
$lang->todo->statusList['doing']  = '进行中';
$lang->todo->statusList['done']   = '已完成';
$lang->todo->statusList['closed'] = '已关闭';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[0] = '';
$lang->todo->priList[3] = '一般';
$lang->todo->priList[1] = '最高';
$lang->todo->priList[2] = '较高';
$lang->todo->priList[4] = '最低';

$lang->todo->typeList['custom']   = '自定义';
$lang->todo->typeList['cycle']    = '周期';
$lang->todo->typeList['bug']      = 'Bug';
$lang->todo->typeList['task']     = $lang->projectCommon . '任务';
$lang->todo->typeList['story']    = $lang->projectCommon . $lang->storyCommon;

global $config;
if($config->global->flow == 'onlyTest' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['task']);
if($config->global->flow == 'onlyTask' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['bug']);

$lang->todo->confirmDelete  = "您确定要删除这条待办吗？";
$lang->todo->thisIsPrivate  = '这是一条私人事务。:)';
$lang->todo->lblDisableDate = '暂时不设定时间';
$lang->todo->lblBeforeDays  = "提前%s天生成待办";
$lang->todo->lblClickCreate = "点击添加待办";
$lang->todo->noTodo         = '该类型没有待办事务';
$lang->todo->noAssignedTo   = '被指派人不能为空';
$lang->todo->unfinishedTodo = '待办ID %s 不是完成状态，不能关闭。';

$lang->todo->periods['all']        = '所有待办';
$lang->todo->periods['thisYear']   = '本年';
$lang->todo->periods['future']     = '待定';
$lang->todo->periods['before']     = '未完';
$lang->todo->periods['cycle']      = '周期';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, 由 <strong>$actor</strong> $extra。$appendLink', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, 由 <strong>$actor</strong> 标记为<strong>$extra</strong>。', 'extra' => 'statusList');
