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

title=测试删除todo todoModel->delete();
timeout=0
cid=1

- 执行todo模块的delete方法，参数是8, no
 - 属性id @8
 - 属性deleted @0

- 执行todo模块的getById方法，参数是8属性deleted @1



*/

initData();

$todo = new todoTest();
r($todo->deleteTest(8, 'no')) && p('id,deleted') && e('8,0');      // 删除指定id的待办，取消确认
$todo->deleteTest(8, 'yes');
r($todo->getByIdTest(8))      && p('deleted') && e('1'); // 验证删除id不存在的todo信息