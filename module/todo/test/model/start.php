#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    $todo = zdTable('todo');
    $todo->id->range('1-3');
    $todo->account->prefix('admin')->range('1-3');
    $todo->date->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
    $todo->begin->range('1710');
    $todo->end->range('1740');
    $todo->feedback->range('0');
    $todo->type->range('custom');
    $todo->cycle->range('0');
    $todo->idvalue->range('0');
    $todo->pri->range("3");
    $todo->name->prefix('测试待办')->range('1-3');
    $todo->desc->range('描述');
    $todo->status->range('wait{1},doing{1},done{1}');
    $todo->private->range('0');
    $todo->assignedTo->prefix('admin')->range('1-3');
    $todo->assignedBy->prefix('admin')->range('1-3');
    $todo->finishedBy->prefix('admin')->range('1-3');
    $todo->closedBy->prefix('admin')->range('1-3');
    $todo->deleted->range('0');
    $todo->vision->range('1.0');

    $todo->gen(3);
}

/**

title=测试 todoModel->start();
timeout=0
cid=1

- 执行todo模块的start方法，参数是1属性status @doing

- 执行todo模块的start方法，参数是2属性status @doing

- 执行todo模块的start方法，参数是3属性status @doing



*/

$todo = new todoTest();

initData();

r($todo->startTest(1)) && p('status') && e('doing'); //开启一个状态为wait的todo
r($todo->startTest(2)) && p('status') && e('doing'); //开启一个状态为doing的todo
r($todo->startTest(3)) && p('status') && e('doing'); //开启一个状态为done的todo