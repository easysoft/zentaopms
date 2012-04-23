<?php
/**
 * The todo module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: zh-tw.php 2609 2012-02-21 13:40:19Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->todo->common       = 'TODO';
$lang->todo->index        = "todo一覽";
$lang->todo->create       = "新增TODO";
$lang->todo->batchCreate  = "批量添加";
$lang->todo->edit         = "更新TODO";
$lang->todo->view         = "TODO詳情";
$lang->todo->viewAB       = "詳情";
$lang->todo->markDone     = "未完成";
$lang->todo->markWait     = "已完成";
$lang->todo->markDoing    = "已完成";
$lang->todo->mark         = "更改狀態";
$lang->todo->export       = "導出";
$lang->todo->delete       = "刪除TODO";
$lang->todo->browse       = "瀏覽TODO";
$lang->todo->import2Today = "導入到今天";
$lang->todo->changeStatus = "更改";

$lang->todo->id          = '編號';
$lang->todo->account     = '所有者';
$lang->todo->date        = '日期';
$lang->todo->begin       = '開始時間';
$lang->todo->beginAB     = '開始';
$lang->todo->end         = '結束時間';
$lang->todo->endAB       = '結束';
$lang->todo->beginAndEnd = '起止時間';
$lang->todo->type        = '類型';
$lang->todo->pri         = '優先順序';
$lang->todo->name        = '名稱';
$lang->todo->status      = '狀態';
$lang->todo->desc        = '描述';
$lang->todo->private     = '私人事務';
$lang->todo->idvalue     = '任務或Bug';

$lang->todo->week         = '星期';
$lang->todo->today        = '今天';
$lang->todo->weekDateList = '一,二,三,四,五,六,天';
$lang->todo->dayInFuture  = '暫不指定';
$lang->todo->confirmBug   = '該Todo關聯的是Bug #%s，需要修改它嗎？';
$lang->todo->confirmTask  = '該Todo關聯的是Task #%s，需要修改它嗎？';

$lang->todo->statusList['wait']     = '未開始';
$lang->todo->statusList['doing']    = '進行中';
$lang->todo->statusList['done']     = '已完成';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[3] = '一般';
$lang->todo->priList[1] = '最高';
$lang->todo->priList[2] = '較高';
$lang->todo->priList[4] = '最低';

$lang->todo->typeList->custom = '自定義';
$lang->todo->typeList->bug    = 'Bug';
$lang->todo->typeList->task   = '項目任務';

$lang->todo->confirmDelete  = "您確定要刪除這個todo嗎？";
$lang->todo->successMarked  = "成功切換狀態！";
$lang->todo->thisIsPrivate  = '這是一條私人事務。:)';
$lang->todo->lblDisableDate = '暫時不設定時間';

$lang->todo->thisWeekTodos = '本週計劃';
$lang->todo->lastWeekTodos = '上周工作';
$lang->todo->futureTodos   = '暫不指定';
$lang->todo->allDaysTodos  = '所有TODO';
$lang->todo->allUndone     = '之前未完';
$lang->todo->todayTodos    = '今日安排';

$lang->todo->action->marked = array('main' => '$date, 由 <strong>$actor</strong> 標記為<strong>$extra</strong>。', 'extra' => $lang->todo->statusList);
