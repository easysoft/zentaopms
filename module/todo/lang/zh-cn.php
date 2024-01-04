<?php
/**
 * The todo module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: zh-cn.php 5022 2013-07-05 06:50:39Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->todo->index        = '待办一览';
$lang->todo->create       = '添加待办';
$lang->todo->createCycle  = '创建周期待办';
$lang->todo->assignTo     = '指派给';
$lang->todo->assignedDate = '指派日期';
$lang->todo->assignAction = '指派待办';
$lang->todo->start        = '开始待办';
$lang->todo->activate     = '激活待办';
$lang->todo->batchCreate  = '批量添加';
$lang->todo->edit         = '编辑待办';
$lang->todo->close        = '关闭待办';
$lang->todo->batchClose   = '批量关闭';
$lang->todo->batchEdit    = '批量编辑';
$lang->todo->view         = '待办详情';
$lang->todo->finish       = '完成待办';
$lang->todo->batchFinish  = '批量完成';
$lang->todo->export       = '导出待办';
$lang->todo->delete       = '删除待办';
$lang->todo->import2Today = '修改日期';
$lang->todo->import       = '导入';
$lang->todo->legendBasic  = '基本信息';
$lang->todo->cycle        = '周期';
$lang->todo->cycleConfig  = '周期设置';
$lang->todo->project      = "所属{$lang->projectCommon}";
$lang->todo->product      = "所属{$lang->productCommon}";
$lang->todo->execution    = "所属{$lang->execution->common}";
$lang->todo->changeDate   = '修改日期';
$lang->todo->future       = '待定';
$lang->todo->timespanTo   = '至';
$lang->todo->transform    = '转化';

$lang->todo->reasonList['story'] = "转{$lang->SRCommon}";
$lang->todo->reasonList['task']  = '转任务';
$lang->todo->reasonList['bug']   = '转Bug';
$lang->todo->reasonList['done']  = '完成';

$lang->todo->id           = '编号';
$lang->todo->account      = '由谁创建';
$lang->todo->date         = '日期';
$lang->todo->begin        = '开始';
$lang->todo->end          = '结束';
$lang->todo->beginAB      = '开始';
$lang->todo->endAB        = '结束';
$lang->todo->beginAndEnd  = '起止时间';
$lang->todo->objectID     = '关联编号';
$lang->todo->type         = '类型';
$lang->todo->pri          = '优先级';
$lang->todo->name         = '待办名称';
$lang->todo->status       = '状态';
$lang->todo->desc         = '描述';
$lang->todo->config       = '配置';
$lang->todo->private      = '私人事务';
$lang->todo->cycleDay     = '天';
$lang->todo->cycleWeek    = '周';
$lang->todo->cycleMonth   = '月';
$lang->todo->cycleYear    = '年';
$lang->todo->day          = '日';
$lang->todo->assignedTo   = '指派给';
$lang->todo->assignedBy   = '由谁指派';
$lang->todo->finishedBy   = '由谁完成';
$lang->todo->finishedDate = '完成时间';
$lang->todo->closedBy     = '由谁关闭';
$lang->todo->closedDate   = '关闭时间';
$lang->todo->deadline     = '过期时间';
$lang->todo->deleted      = '已删除';
$lang->todo->ditto        = '同上';
$lang->todo->from         = '从';
$lang->todo->generate     = '生成待办';
$lang->todo->advance      = '提前';
$lang->todo->cycleType    = '周期类型';
$lang->todo->monthly      = '每月';
$lang->todo->weekly       = '每周';

$lang->todo->cycleDaysLabel  = '间隔天数';
$lang->todo->beforeDaysLabel = '提前天数';

$lang->todo->every        = '间隔';
$lang->todo->specify      = '指定';
$lang->todo->everyYear    = '每年';
$lang->todo->beforeDays   = "<span class='input-group-addon'>提前</span>%s<span class='input-group-addon'>天生成待办</span>";
$lang->todo->dayNames     = array(1 => '星期一', 2 => '星期二', 3 => '星期三', 4 => '星期四', 5 => '星期五', 6 => '星期六', 0 => '星期日');
$lang->todo->specifiedDay = array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);

$lang->todo->confirmBug     = '该待办关联的是Bug #%s，需要修改它吗？';
$lang->todo->confirmTask    = '该待办关联的是Task #%s，需要修改它吗？';
$lang->todo->confirmStory   = '该待办关联的是Story #%s，需要修改它吗？';
$lang->todo->noOptions      = '您暂时没有待处理的%s，请重新选择待办类型。';
$lang->todo->summary        = '本页共 <strong>%s</strong> 项待办，未开始 <strong>%s</strong>，进行中 <strong>%s</strong>。';
$lang->todo->checkedSummary = '共选择 <strong>%total%</strong> 项待办，未开始 <strong>%wait%</strong>，进行中 <strong>%doing%</strong>。';

$lang->todo->abbr = new stdclass();
$lang->todo->abbr->start  = '开始';
$lang->todo->abbr->finish = '完成';

$lang->todo->statusList['wait']   = '未开始';
$lang->todo->statusList['doing']  = '进行中';
$lang->todo->statusList['done']   = '已完成';
$lang->todo->statusList['closed'] = '已关闭';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[1] = 1;
$lang->todo->priList[2] = 2;
$lang->todo->priList[3] = 3;
$lang->todo->priList[4] = 4;

$lang->todo->typeList['custom']   = '自定义';
$lang->todo->typeList['cycle']    = '周期';
$lang->todo->typeList['bug']      = 'Bug';
$lang->todo->typeList['task']     = '任务';
$lang->todo->typeList['story']    = $lang->SRCommon;
$lang->todo->typeList['testtask'] = '测试单';

$lang->todo->fromList['bug']   = '相关Bug';
$lang->todo->fromList['task']  = '相关任务';
$lang->todo->fromList['story'] = '相关' . $lang->SRCommon;

$lang->todo->confirmDelete  = '您确定要删除这条待办吗？';
$lang->todo->thisIsPrivate  = '这是一条私人事务。:)';
$lang->todo->lblDisableDate = '暂时不设定时间';
$lang->todo->lblBeforeDays  = '提前%s天生成待办';
$lang->todo->lblClickCreate = '点击添加待办';
$lang->todo->noTodo         = '该类型没有待办事务';
$lang->todo->noAssignedTo   = '被指派人不能为空';
$lang->todo->unfinishedTodo = '待办ID %s 不是完成状态，不能关闭。';
$lang->todo->today          = '今日待办';
$lang->todo->selectProduct  = "请选择{$lang->productCommon}";
$lang->todo->privateTip     = '我创建且指派给我的待办才能设为私人事务，设为私人事务后只有我可以看到';

$lang->todo->periods['all']             = '指派自己';
$lang->todo->periods['before']          = '未完';
$lang->todo->periods['future']          = '待定';
$lang->todo->periods['thisWeek']        = '本周';
$lang->todo->periods['thisMonth']       = '本月';
$lang->todo->periods['thisYear']        = '本年';
$lang->todo->periods['assignedToOther'] = '指派他人';
$lang->todo->periods['cycle']           = '周期';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, 由 <strong>$actor</strong> $extra。$appendLink', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, 由 <strong>$actor</strong> 标记为<strong>$extra</strong>。', 'extra' => 'statusList');
