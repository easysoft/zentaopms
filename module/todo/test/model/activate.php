#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    $todo = zdTable('todo');
    $todo->id->range('1-4');
    $todo->account->prefix('admin')->range('1-4');
    $todo->begin->range('1710');
    $todo->end->range('1740');
    $todo->feedback->range('0');
    $todo->type->range('custom');
    $todo->cycle->range('0');
    $todo->idvalue->range('0');
    $todo->pri->range("3");
    $todo->name->prefix('测试待办')->range('1-4');
    $todo->desc->range('描述');
    $todo->status->range('wait');
    $todo->private->range('0');
    $todo->assignedTo->prefix('admin')->range('1-4');
    $todo->assignedBy->prefix('admin')->range('1-4');
    $todo->finishedBy->prefix('admin')->range('1-4');
    $todo->closedBy->prefix('admin')->range('1-4');
    $todo->deleted->range('0');
    $todo->vision->range('1.0');

    $todo->gen(4);
}

/**

title=测试 todoModel->activate();
timeout=0
cid=1

- 执行todo模块的activate方法，参数是$todoIDList[0]属性status @wait

- 执行todo模块的activate方法，参数是$todoIDList[1]属性status @wait

- 执行todo模块的activate方法，参数是$todoIDList[2]属性status @wait



*/

$todoIDList = array('1', '2', '3');

$todo = new todoTest();

initData();

r($todo->activateTest($todoIDList[0])) && p('status') && e('wait'); // 激活一个状态为wait的todo
r($todo->activateTest($todoIDList[1])) && p('status') && e('wait'); // 激活一个状态为doing的todo
r($todo->activateTest($todoIDList[2])) && p('status') && e('wait'); // 激活一个状态为done的todo
