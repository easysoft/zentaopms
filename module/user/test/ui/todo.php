#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/todo.ui.class.php';

/**

title=开源版user模块视图层todo显示测试
timeout=0
cid=1

- 用户待办页面菜单导航和分页功能测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @用户待办页面菜单导航和分页功能测试成功

*/

zenData('company')->gen(1);

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range('admin,dev,qa');
$user->gender->range('f,m');
$user->gen(3);

$today = date('Y-m-d');

$lastYearBaseTS = strtotime('-1 year', strtotime($today));
$lastYearDates  = array();
for($i = 0; $i < 2; $i++)
{
    $lastYearDates[] = date('Y-m-d', strtotime("+{$i} month", $lastYearBaseTS));
}

$lastMonthTS    = strtotime('last month');
$lastMonthYMD   = date('Y-m-', $lastMonthTS);
$checkDays      = array(1, 15);
$lastMonthDates = array();
foreach($checkDays as $d)
{
    $lastMonthDates[] = $lastMonthYMD . sprintf('%02d', $d);
}

$weekStart     = date('Y-m-d', strtotime('monday this week'));
$thisWeekDates = array();
for($i = 0; $i < 3; $i++)
{
    $thisWeekDates[] = date('Y-m-d', strtotime("{$weekStart} +{$i} day"));
}

$futureDates = array_fill(0, 3, FUTURE_DATE);
$userDates   = array_merge($futureDates, $lastYearDates, $lastMonthDates, $thisWeekDates);
$allDates    = array_merge($userDates, $userDates, $userDates);

$todo = zenData('todo');
$todo->id->range('1-60');
$todo->account->range('admin{10},user1{10},user2{10}');
$todo->date->range(implode(',', array_map(fn($d) => "`{$d}`", $allDates)));
$todo->type->range('custom');
$todo->pri->range('1,2,3');
$todo->name->range('1-30')->prefix('待办任务');
$todo->desc->range('1-30')->prefix('这是待办任务描述');
$todo->status->range('doing{3},wait{3},done{4}');
$todo->begin->range('0800,0900,1000,1100,1400');
$todo->end->range('1700,1800,1900,2000,2100');
$todo->assignedTo->range('admin{5},user1{10},user2{10},admin{5}');
$todo->assignedBy->range('admin{10},user1{10},user2{10}');
$todo->assignedDate->range('`2023-12-01 09:00:00`');
$todo->objectID->range('0');
$todo->vision->range('rnd');
$todo->cycle->range('1,0{9},1,0{9},1,0{9}');
$todo->private->range('0,1');
$todo->deleted->range('0');
$todo->finishedBy->range('');
$todo->finishedDate->range('`0000-00-00 00:00:00`');
$todo->closedBy->range('');
$todo->closedDate->range('`0000-00-00 00:00:00`');
$todo->feedback->range('0');
$todo->fields->setField('config')->range('`{"type":"day","day":3,"beforeDays":0,"begin":"2024-01-01"}`');
$todo->gen(30);

global $uiTester;
$users = $uiTester->dao->select('*')->from('zt_user')->fetchAll();
$todos = $uiTester->dao->select('*')->from('zt_todo')->fetchAll();

$todoTester = new todoTester();

r($todoTester->verifyUserTodoContentAndPagination($users, $todos)) && p('status,message') && e('SUCCESS,用户待办页面内容测试成功');
$todoTester->closeBrowser();