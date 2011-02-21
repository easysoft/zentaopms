<?php
/**
 * The todo module Japanese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: en.php 996 2010-08-02 14:19:13Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->todo->common    = 'のTODO';
$lang->todo->index     = "インデックス";
$lang->todo->create    = "作成";
$lang->todo->edit      = "[編集]";
$lang->todo->view      = "情報";
$lang->todo->viewAB    = "情報";
$lang->todo->markDone  = "アサンダー";
$lang->todo->markWait  = "完了";
$lang->todo->markDoing = "行う";
$lang->todo->mark      = "変更状況";
$lang->todo->delete    = "削除";
$lang->todo->browse    = "ブラウズ";
$lang->todo->import2Today = "今日のインポート";
$lang->todo->changeStatus = "変更";

$lang->todo->id          = 'IDは';
$lang->todo->account     = '所有者';
$lang->todo->date        = '日付';
$lang->todo->begin       = '開始時間';
$lang->todo->beginAB     = '開始';
$lang->todo->end         = '終了時間';
$lang->todo->endAB       = '終了';
$lang->todo->beginAndEnd = 'BeginとEnd';
$lang->todo->type        = 'タイプ';
$lang->todo->pri         = '優先順位';
$lang->todo->name        = '名';
$lang->todo->status      = 'ステータス';
$lang->todo->desc        = '降順';
$lang->todo->private     = 'プライベート';
$lang->todo->idvalue     = 'タスクやバグ';

$lang->todo->week  = '（1）';  // date function's param.
$lang->todo->today = '今日';
$lang->todo->weekDateList = '';
$lang->todo->dayInFeature = '機能';
$lang->todo->confirmBug   = 'This todo linked to bug #%s，chang it also?';
$lang->todo->confirmTask  = 'This todo linked to task #%s，chang it also?';

$lang->todo->statusList['wait']     = '待機';
$lang->todo->statusList['doing']    = '行う';
$lang->todo->statusList['done']     = '完了';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[3] = '3';
$lang->todo->priList[1] = '1';
$lang->todo->priList[2] = '2';
$lang->todo->priList[4] = '4';

$lang->todo->typeList->custom = 'カスタム';
$lang->todo->typeList->bug    = 'バグ';
$lang->todo->typeList->task   = 'タスク';

$lang->todo->confirmDelete  = "あなたはこの藤堂を削除してよろしいですか？";
$lang->todo->successMarked  = "正常にステータスを変更";;
$lang->todo->thisIsPrivate  = '正常にステータスを変更';
$lang->todo->lblDisableDate = 'セットタイム最近';

$lang->todo->thisWeekTodos = '今週';
$lang->todo->lastWeekTodos = '先週';
$lang->todo->allDaysTodos  = 'すべてのToDo';
$lang->todo->allUndone     = 'アサンダー';
$lang->todo->todayTodos    = '今日';

$lang->todo->action->marked = array('main' => '$date, Change status to <stong>$extra</strong> by <strong>$actor</strong>。', 'extra' => $lang->todo->statusList);
