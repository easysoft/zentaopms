<?php
/**
 * The todo module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     todo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->todo->common = 'ToDo';
$lang->todo->index = 'ToDo一覧';
$lang->todo->create = '新規';
$lang->todo->createCycle = 'サイクル新規';
$lang->todo->assignTo = 'アサイン';
$lang->todo->assignedDate = 'アサイン日';
$lang->todo->assignAction = 'ToDoアサイン';
$lang->todo->start = '开始待办';
$lang->todo->activate = 'アクティブ';
$lang->todo->batchCreate = '一括追加';
$lang->todo->edit = '編集';
$lang->todo->close = 'クローズ';
$lang->todo->batchClose = '一括クローズ';
$lang->todo->batchEdit = '一括編集';
$lang->todo->view = '詳細';
$lang->todo->finish = 'ToDo完成';
$lang->todo->batchFinish = '一括完成';
$lang->todo->export = 'エクスポート';
$lang->todo->delete = '削除';
$lang->todo->import2Today = '今日にインポート';
$lang->todo->import = 'インポート';
$lang->todo->legendBasic = '基本情報';
$lang->todo->cycle = 'サイクル';
$lang->todo->cycleConfig = 'サイクル設定';

$lang->todo->reasonList['story'] = $lang->storyCommon . 'に変更';
$lang->todo->reasonList['task'] = 'タスクに変更';
$lang->todo->reasonList['bug'] = 'バグに変更';
$lang->todo->reasonList['done'] = '完成';

$lang->todo->id = '番号';
$lang->todo->account = '所有者';
$lang->todo->date = '日付';
$lang->todo->begin = '開始時間';
$lang->todo->end = '終了時間';
$lang->todo->beginAB = '開始';
$lang->todo->endAB = '終了';
$lang->todo->beginAndEnd = '開始、終了時間';
$lang->todo->idvalue = '番号関連';
$lang->todo->type = 'タイプ';
$lang->todo->pri = '優先度';
$lang->todo->name = '名称';
$lang->todo->status = 'ステータス';
$lang->todo->desc = '説明';
$lang->todo->private = 'プライベート';
$lang->todo->cycleDay = '日';
$lang->todo->cycleWeek = '週';
$lang->todo->cycleMonth = '月';
$lang->todo->assignedTo = '指派给';
$lang->todo->assignedBy = '由谁指派';
$lang->todo->finishedBy = '由谁完成';
$lang->todo->finishedDate = '完成时间';
$lang->todo->closedBy = '由谁关闭';
$lang->todo->closedDate = '关闭时间';
$lang->todo->deadline = '期限切れ';

$lang->todo->every = '間隔';
$lang->todo->beforeDays = "<span class='input-group-addon'>ToDo生成を</span>%s<span class='input-group-addon'>日繰り上げ</span>";
$lang->todo->dayNames = array(1 => '月曜日', 2 => '火曜日', 3 => '水曜日', 4 => '木曜日', 5 => '金曜日', 6 => '土曜日', 0 => '日曜日');

$lang->todo->confirmBug = '当該Todoの関連はBug #%s、修正しますか。';
$lang->todo->confirmTask = '当該Todoの関連はTask #%s、修正しますか。';
$lang->todo->confirmStory = '当該Todoの関連はStory #%s、修正しますか。';

$lang->todo->statusList['wait'] = '未着手';
$lang->todo->statusList['doing'] = '作業中';
$lang->todo->statusList['done'] = '完了';
$lang->todo->statusList['closed'] = 'クローズ済';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[0] = '';
$lang->todo->priList[3] = '通常';
$lang->todo->priList[1] = '最優先';
$lang->todo->priList[2] = '高め';
$lang->todo->priList[4] = '低め';

$lang->todo->typeList['custom'] = 'カスタマイズ';
$lang->todo->typeList['cycle'] = 'サイクル';
$lang->todo->typeList['bug'] = 'バグ';
$lang->todo->typeList['task'] = $lang->projectCommon . 'タスク';
$lang->todo->typeList['story'] = $lang->projectCommon . $lang->storyCommon;

global $config;
if($config->global->flow == 'onlyTest' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['task']);
if($config->global->flow == 'onlyTask' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['bug']);

$lang->todo->confirmDelete = '当該ToDoを削除してもよろしいですか？';
$lang->todo->thisIsPrivate = 'プライベートです。:)';
$lang->todo->lblDisableDate = '時間を設定しない';
$lang->todo->lblBeforeDays = 'ToDoの生成を%s日繰り上げ';
$lang->todo->lblClickCreate = 'クリックしてToDoを追加';
$lang->todo->noTodo = '当該タイプはToDo事務がない';
$lang->todo->noAssignedTo = '担当者を入力してください';
$lang->todo->unfinishedTodo = '待办ID %s 不是完成状态，不能关闭。';

$lang->todo->periods['all'] = '全て';
$lang->todo->periods['thisYear'] = '今年';
$lang->todo->periods['future'] = '未定';
$lang->todo->periods['before'] = '未完了';
$lang->todo->periods['cycle'] = 'サイクル';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date、 <strong>$actor</strong> より  $extra。$appendLink', 'extra' => 'reasonList');
$lang->todo->action->marked = array('main' => '$date、 <strong>$actor</strong> より<strong>$extra</strong>としてマークしました。', 'extra' => 'statusList');
