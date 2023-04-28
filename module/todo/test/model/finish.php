#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    $project = zdTable('todo');
    $project->id->range('1-100');
    $project->account->range('admin');
    $project->date->prefix('2023-04-2')->range('1-9');
    $project->begin->range('1000-1100');
    $project->end->range('1200-1300');
    $project->type->range("custom");
    $project->cycle->range("0");
    $project->pri->range("3");
    $project->name->prefix('我的待办')->range("1-5");
    $project->desc->prefix('这是我的描述')->range("1-5");
    $project->status->range("wait,doing,done");

    $project->gen(10);
}

/**

title=测试完成待办 todoModel->finish();
timeout=0
cid=1

- 执行todo模块的finish方法，参数是$todoIDList[0]属性status @done

- 执行todo模块的finish方法，参数是$todoIDList[1]属性status @done

- 执行todo模块的finish方法，参数是$todoIDList[2]属性status @done



*/

initData();

$todoIDList = range(1,3);

$todo = new todoTest();

r($todo->finishTest($todoIDList[0])) && p('status') && e('done'); // 结束一个状态为wait的todo
r($todo->finishTest($todoIDList[1])) && p('status') && e('done'); // 结束一个状态为doing的todo
r($todo->finishTest($todoIDList[2])) && p('status') && e('done'); // 结束一个状态为done的todo