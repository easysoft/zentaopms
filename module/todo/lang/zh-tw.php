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
$lang->todo->create       = "新增";
$lang->todo->batchCreate  = "批量添加";
$lang->todo->edit         = "更新待辦";
$lang->todo->batchEdit    = "批量編輯";
$lang->todo->view         = "待辦詳情";
$lang->todo->finish       = "完成";
$lang->todo->batchFinish  = "批量完成";
$lang->todo->export       = "導出";
$lang->todo->delete       = "刪除待辦";
$lang->todo->import2Today = "導入到今天";
$lang->todo->import       = "導入";
$lang->todo->legendBasic  = "基本信息";

$lang->todo->id          = '編號';
$lang->todo->account     = '所有者';
$lang->todo->date        = '日期';
$lang->todo->begin       = '開始';
$lang->todo->end         = '結束';
$lang->todo->beginAB     = '開始';
$lang->todo->endAB       = '結束';
$lang->todo->beginAndEnd = '起止時間';
$lang->todo->type        = '類型';
$lang->todo->pri         = '優先順序';
$lang->todo->name        = '名稱';
$lang->todo->status      = '狀態';
$lang->todo->desc        = '描述';
$lang->todo->private     = '私人事務';

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

$lang->todo->typeList['custom'] = '自定義';
$lang->todo->typeList['bug']    = 'Bug';
$lang->todo->typeList['task']   = $lang->projectCommon . '任務';

global $config;
if($config->global->flow == 'onlyTest' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['task']);
if($config->global->flow == 'onlyTask' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['bug']);

$lang->todo->confirmDelete  = "您確定要刪除這條待辦嗎？";
$lang->todo->thisIsPrivate  = '這是一條私人事務。:)';
$lang->todo->lblDisableDate = '暫時不設定時間';
$lang->todo->noTodo         = '該類型沒有待辦事務';

$lang->todo->periods['today']      = '今日';
$lang->todo->periods['yesterday']  = '昨日';
$lang->todo->periods['thisWeek']   = '本週';
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
$lang->todo->action->marked    = array('main' => '$date, 由 <strong>$actor</strong> 標記為<strong>$extra</strong>。', 'extra' => 'statusList');
