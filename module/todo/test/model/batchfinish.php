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
    $project->status->range("wait");

    $project->gen(10);
}

/**

title=测试批量完成待办 todoModel->batchFinish();
timeout=0
cid=1

- 执行todo模块的getById方法，参数是$todoIDList[0]属性status @done

- 执行todo模块的getById方法，参数是$todoIDList[1]属性status @done

- 执行todo模块的getById方法，参数是$todoIDList[2]属性status @done



*/

$todoIDList = array('1', '2', '3');

$todo = new todoTest();
$todo->batchFinishTest($todoIDList);

r($todo->getByIdTest($todoIDList[0])) && p('status') && e('done'); // 批量完成todo验证状态done
r($todo->getByIdTest($todoIDList[1])) && p('status') && e('done'); // 批量完成todo验证状态done
r($todo->getByIdTest($todoIDList[2])) && p('status') && e('done'); // 批量完成todo验证状态done