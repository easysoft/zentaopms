<?php
/**
 * The todo module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: zh-tw.php 5022 2013-07-05 06:50:39Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->todo->common       = '待辦';
$lang->todo->index        = '待辦一覽';
$lang->todo->create       = '添加待辦';
$lang->todo->createCycle  = '創建周期待辦';
$lang->todo->assignTo     = '指派給';
$lang->todo->assignedDate = '指派日期';
$lang->todo->assignAction = '指派待辦';
$lang->todo->start        = '開始待辦';
$lang->todo->activate     = '激活待辦';
$lang->todo->batchCreate  = '批量添加';
$lang->todo->edit         = '編輯待辦';
$lang->todo->close        = '關閉待辦';
$lang->todo->batchClose   = '批量關閉';
$lang->todo->batchEdit    = '批量編輯';
$lang->todo->view         = '待辦詳情';
$lang->todo->finish       = '完成待辦';
$lang->todo->batchFinish  = '批量完成';
$lang->todo->export       = '導出待辦';
$lang->todo->delete       = '刪除待辦';
$lang->todo->import2Today = '導入到今天';
$lang->todo->import       = '導入';
$lang->todo->legendBasic  = '基本信息';
$lang->todo->cycle        = '周期';
$lang->todo->cycleConfig  = '周期設置';
$lang->todo->project      = '所屬項目';
$lang->todo->product      = '所屬產品';
$lang->todo->execution    = "所屬{$lang->execution->common}";
$lang->todo->timespanTo   = '至';
$lang->todo->transform    = '轉化';

$lang->todo->reasonList['story'] = "轉{$lang->SRCommon}";
$lang->todo->reasonList['task']  = '轉任務';
$lang->todo->reasonList['bug']   = '轉Bug';
$lang->todo->reasonList['done']  = '完成';

$lang->todo->id           = '編號';
$lang->todo->account      = '由誰創建';
$lang->todo->date         = '日期';
$lang->todo->begin        = '開始';
$lang->todo->end          = '結束';
$lang->todo->beginAB      = '開始';
$lang->todo->endAB        = '結束';
$lang->todo->beginAndEnd  = '起止時間';
$lang->todo->objectID     = '關聯編號';
$lang->todo->type         = '類型';
$lang->todo->pri          = '優先順序';
$lang->todo->name         = '待辦名稱';
$lang->todo->status       = '狀態';
$lang->todo->desc         = '描述';
$lang->todo->config       = '配置';
$lang->todo->private      = '私人事務';
$lang->todo->cycleDay     = '天';
$lang->todo->cycleWeek    = '周';
$lang->todo->cycleMonth   = '月';
$lang->todo->cycleYear    = '年';
$lang->todo->day          = '日';
$lang->todo->assignedTo   = '指派給';
$lang->todo->assignedBy   = '由誰指派';
$lang->todo->finishedBy   = '由誰完成';
$lang->todo->finishedDate = '完成時間';
$lang->todo->closedBy     = '由誰關閉';
$lang->todo->closedDate   = '關閉時間';
$lang->todo->deadline     = '過期時間';
$lang->todo->deleted      = '已刪除';
$lang->todo->from         = '從';
$lang->todo->generate     = '生成待辦';
$lang->todo->advance      = '提前';
$lang->todo->cycleType    = '週期類型';
$lang->todo->monthly      = '每月';
$lang->todo->weekly       = '每週';

$lang->todo->cycleDaysLabel  = '間隔天數';
$lang->todo->beforeDaysLabel = '提前天數';

$lang->todo->every        = '間隔';
$lang->todo->specify      = '指定';
$lang->todo->everyYear    = '每年';
$lang->todo->beforeDays   = "<span class='input-group-addon'>提前</span>%s<span class='input-group-addon'>天生成待辦</span>";
$lang->todo->dayNames     = array(1 => '星期一', 2 => '星期二', 3 => '星期三', 4 => '星期四', 5 => '星期五', 6 => '星期六', 0 => '星期日');
$lang->todo->specifiedDay = array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);

$lang->todo->confirmBug   = '該Todo關聯的是Bug #%s，需要修改它嗎？';
$lang->todo->confirmTask  = '該Todo關聯的是Task #%s，需要修改它嗎？';
$lang->todo->confirmStory = '該Todo關聯的是Story #%s，需要修改它嗎？';

$lang->todo->statusList['wait']   = '未開始';
$lang->todo->statusList['doing']  = '進行中';
$lang->todo->statusList['done']   = '已完成';
$lang->todo->statusList['closed'] = '已關閉';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[1] = 1;
$lang->todo->priList[2] = 2;
$lang->todo->priList[3] = 3;
$lang->todo->priList[4] = 4;

$lang->todo->typeList['custom']   = '自定義';
$lang->todo->typeList['cycle']    = '周期';
$lang->todo->typeList['bug']      = 'Bug';
$lang->todo->typeList['task']     = '任務';
$lang->todo->typeList['story']    = $lang->SRCommon;
$lang->todo->typeList['testtask'] = '測試單';

$lang->todo->confirmDelete  = '您確定要刪除這條待辦嗎？';
$lang->todo->thisIsPrivate  = '這是一條私人事務。:)';
$lang->todo->lblDisableDate = '暫時不設定時間';
$lang->todo->lblBeforeDays  = '提前%s天生成待辦';
$lang->todo->lblClickCreate = '點擊添加待辦';
$lang->todo->noTodo         = '該類型沒有待辦事務';
$lang->todo->noAssignedTo   = '被指派人不能為空';
$lang->todo->unfinishedTodo = '待辦ID %s 不是完成狀態，不能關閉。';
$lang->todo->today          = '今日待辦';
$lang->todo->privateTip     = '指派給我的待辦才能設為私人事務，設為私人事務後只有被指派人可以看到';

$lang->todo->periods['all']             = '所有';
$lang->todo->periods['before']          = '未完';
$lang->todo->periods['future']          = '待定';
$lang->todo->periods['thisWeek']        = '本週';
$lang->todo->periods['thisMonth']       = '本月';
$lang->todo->periods['thisYear']        = '本年';
$lang->todo->periods['assignedToOther'] = '指派他人';
$lang->todo->periods['cycle']           = '周期';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, 由 <strong>$actor</strong> $extra。$appendLink', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, 由 <strong>$actor</strong> 標記為<strong>$extra</strong>。', 'extra' => 'statusList');
