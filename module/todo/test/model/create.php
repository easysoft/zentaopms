#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    $todo = zdTable('todo');
    $todo->id->range('1');
    $todo->name->prefix("待办")->range('1');
    $todo->date->range('`2023-04-23`');
    $todo->type->range('custom');
    $todo->gen(1, '', true);
}

/**

title=测试 todoModel->create();
cid=1
pid=1

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

global $tester;
$tester->loadModel('todo');

initData();

$todoWithoutName = new stdclass;
$todoWithoutName->name = '';
$todoWithoutName->date = date('Y-m-d');
$todoWithoutName->type = 'custom';

$todoInvalidDate = new stdclass;
$todoInvalidDate->name = 'todoInvalidDate';
$todoInvalidDate->date = 'today';
$todoInvalidDate->type = 'custom';

$todoValid = new stdclass;
$todoValid->name = 'todoValid';
$todoValid->date = date('Y-m-d');
$todoValid->type = 'custom';

$todoValid1 = new stdclass;
$todoValid1->name = 'todoValid1';
$todoValid1->date = date('Y-m-d');
$todoValid1->type = 'custom';

/**
 * 1. 如果r函数返回的是一个复杂的结构，比如混合内容的数组或者嵌套的数组，p函数是无法获取返回值中的某些特定值的，需要自己编写助手函数，比如在../todo.class.php中编写。
 * 2. 如果ztf执行没有生成注释，需要检查php脚本是否执行报错，建议先使用php执行编写的测试用例再用ztf执行。
 */
r($tester->todo->create($todoValid))       && p() && e('2');
r($tester->todo->create($todoWithoutName)) && p() && e('0');
r($tester->todo->create($todoInvalidDate)) && p() && e('0');
r($tester->todo->create($todoValid1))      && p() && e('3');
exit(0);

r($todo->createTest($accountList[0], $b_noname)) && p()                   && e('『待办名称』不能为空。');
r($todo->createTest($accountList[1], $todo1))    && p('name,type,status') && e('时间待定的月周期待办,custom,wait');
r($todo->createTest($accountList[2], $todo2))    && p('name,type,status') && e('测试单转Bug13,bug,doing');
r($todo->createTest($accountList[0], $todo3))    && p('name,type,status') && e('开发任务11,task,done');
r($todo->createTest($accountList[0], $todo4))    && p('name,type,status') && e('用户需求1,story,closed');
