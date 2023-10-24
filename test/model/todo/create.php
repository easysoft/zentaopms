#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->create();
cid=1
pid=1

创建没有名字的待办 >> 『待办名称』不能为空。
创建自定义待办 >> 时间待定的月周期待办,custom,wait
创建bug待办 >> 测试单转Bug13,bug,doing
创建task待办 >> 开发任务11,task,done
创建story待办 >> 用户需求1,story,closed

*/

$accountList = array('admin', 'dev1', 'test1');

$b_noname = new stdclass();
$b_noname->name = '';
$b_noname->date = 'today';
$b_noname->type = 'custom';

$todo1 = new stdclass();
$todo1->name   = '时间待定的月周期待办';
$todo1->type   = 'custom';
$todo1->date   = '+3 days';
$todo1->config = array('day' => '', 'specify' => array('month' => 0, 'day' => 1), 'month' => array(1,3,5), 'type' => 'month', 'beforeDays' => 2, 'end' => '2025-01-01');

$todo2 = new stdclass();
$todo2->name   = 'bug待办';
$todo2->type   = 'bug';
$todo2->date   = 'today';
$todo2->bug    = '313';
$todo2->status = 'doing';
$todo2->uid    = '313';

$todo3 = new stdclass();
$todo3->name   = 'task待办';
$todo3->type   = 'task';
$todo3->date   = 'today';
$todo3->task   = '1';
$todo3->status = 'done';
$todo3->uid    = '1';

$todo4 = new stdclass();
$todo4->name   = 'story待办';
$todo4->type   = 'story';
$todo4->date   = 'today';
$todo4->story  = '1';
$todo4->status = 'closed';
$todo4->uid    = '1';

$todo = new todoTest();

r($todo->createTest($accountList[0], $b_noname)) && p()                   && e('『待办名称』不能为空。');           //创建没有名字的待办
r($todo->createTest($accountList[1], $todo1))    && p('name,type,status') && e('时间待定的月周期待办,custom,wait'); //创建自定义待办
r($todo->createTest($accountList[2], $todo2))    && p('name,type,status') && e('测试单转Bug13,bug,doing');          //创建bug待办
r($todo->createTest($accountList[0], $todo3))    && p('name,type,status') && e('开发任务11,task,done');             //创建task待办
r($todo->createTest($accountList[0], $todo4))    && p('name,type,status') && e('用户需求1,story,closed');           //创建story待办
