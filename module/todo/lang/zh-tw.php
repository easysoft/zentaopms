<?php
/**
 * The todo module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: zh-tw.php 5022 2013-07-05 06:50:39Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->todo->common       = '待辦';
$lang->todo->index        = "待辦一覽";
$lang->todo->create       = "添加待辦";
$lang->todo->createCycle  = "創建周期待辦";
$lang->todo->assignTo     = "指派給";
$lang->todo->activate     = "激活";
$lang->todo->batchCreate  = "批量添加";
$lang->todo->edit         = "編輯";
$lang->todo->close        = "關閉";
$lang->todo->batchClose   = "批量關閉";
$lang->todo->batchEdit    = "批量編輯";
$lang->todo->view         = "待辦詳情";
$lang->todo->finish       = "完成";
$lang->todo->batchFinish  = "批量完成";
$lang->todo->export       = "導出";
$lang->todo->delete       = "刪除";
$lang->todo->import2Today = "導入到今天";
$lang->todo->import       = "導入";
$lang->todo->legendBasic  = "基本信息";
$lang->todo->cycle        = "周期";
$lang->todo->cycleConfig  = "周期設置";

$lang->todo->reasonList['story'] = "轉需求";
$lang->todo->reasonList['task']  = "轉任務";
$lang->todo->reasonList['bug']   = "轉Bug";
$lang->todo->reasonList['done']  = "完成";

$lang->todo->id          = '編號';
$lang->todo->account     = '所有者';
$lang->todo->date        = '日期';
$lang->todo->begin       = '開始';
$lang->todo->end         = '結束';
$lang->todo->beginAB     = '開始';
$lang->todo->endAB       = '結束';
$lang->todo->beginAndEnd = '起止時間';
$lang->todo->idvalue     = '關聯編號';
$lang->todo->type        = '類型';
$lang->todo->pri         = '優先順序';
$lang->todo->name        = '待辦名稱';
$lang->todo->status      = '狀態';
$lang->todo->desc        = '描述';
$lang->todo->private     = '私人事務';
$lang->todo->cycleDay    = '天';
$lang->todo->cycleWeek   = '周';
$lang->todo->cycleMonth  = '月';
$lang->todo->deadline    = '過期時間';

$lang->todo->every      = '間隔';
$lang->todo->beforeDays = "<span class='input-group-addon'>提前</span>%s<span class='input-group-addon'>天生成待辦</span>";
$lang->todo->dayNames   = array(1 => '星期一', 2 => '星期二', 3 => '星期三', 4 => '星期四', 5 => '星期五', 6 => '星期六', 0 => '星期日');

$lang->todo->confirmBug   = '該Todo關聯的是Bug #%s，需要修改它嗎？';
$lang->todo->confirmTask  = '該Todo關聯的是Task #%s，需要修改它嗎？';
$lang->todo->confirmStory = '該Todo關聯的是Story #%s，需要修改它嗎？';

$lang->todo->statusList['wait']   = '未開始';
$lang->todo->statusList['doing']  = '進行中';
$lang->todo->statusList['done']   = '已完成';
$lang->todo->statusList['closed'] = '已關閉';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[3] = '一般';
$lang->todo->priList[1] = '最高';
$lang->todo->priList[2] = '較高';
$lang->todo->priList[4] = '最低';
$lang->todo->priList[0] = '';

$lang->todo->typeList['custom'] = '自定義';
$lang->todo->typeList['cycle']  = '周期';
$lang->todo->typeList['bug']    = 'Bug';
$lang->todo->typeList['task']   = $lang->projectCommon . '任務';
$lang->todo->typeList['story']  = $lang->projectCommon . '需求';

global $config;
if($config->global->flow == 'onlyTest' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['task']);
if($config->global->flow == 'onlyTask' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['bug']);

$lang->todo->confirmDelete  = "您確定要刪除這條待辦嗎？";
$lang->todo->thisIsPrivate  = '這是一條私人事務。:)';
$lang->todo->lblDisableDate = '暫時不設定時間';
$lang->todo->lblBeforeDays  = "提前%s天生成待辦";
$lang->todo->lblClickCreate = "點擊添加待辦";
$lang->todo->noTodo         = '該類型沒有待辦事務';
$lang->todo->noAssignedTo   = '被指派人不能為空';

$lang->todo->periods['all']        = '所有待辦';
$lang->todo->periods['thisYear']   = '本年';
$lang->todo->periods['future']     = '待定';
$lang->todo->periods['before']     = '未完';
$lang->todo->periods['cycle']      = '周期';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, 由 <strong>$actor</strong> $extra。$appendLink', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, 由 <strong>$actor</strong> 標記為<strong>$extra</strong>。', 'extra' => 'statusList');
