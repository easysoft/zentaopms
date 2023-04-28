#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    $project = zdTable('todo');
    $project->id->range('1-5');
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

    $project->gen(5);
}

/**

title=测试 todoModel->assignTo();
timeout=0
cid=1

- 执行todo模块的assignTo方法，参数是$todoIDList[0], $todo1
 - 属性assignedTo @test1
 - 属性date @20300101
 - 属性begin @1000
 - 属性end @1400

- 执行todo模块的assignTo方法，参数是$todoIDList[1], $todo2
 - 属性assignedTo @test1
 - 属性begin @2400
 - 属性end @2400

- 执行todo模块的assignTo方法，参数是$todoIDList[2], $todo3
 - 属性assignedTo @test1
 - 属性begin @1002
 - 属性end @1402

- 执行todo模块的assignTo方法，参数是$todoIDList[3], $todo4
 - 属性assignedTo @admin
 - 属性date @20300101
 - 属性begin @1200
 - 属性end @1502

- 执行todo模块的assignTo方法，参数是$todoIDList[4], $todo5
 - 属性assignedTo @admin
 - 属性date @20300101
 - 属性begin @2400
 - 属性end @2400



*/

initData();

$todoIDList = array('1', '2', '3', '4', '5');

$todo1 = new stdclass();
$todo1->assignedTo     = 'test1';
$todo1->future         = 'on';
$todo1->begin          = 1000;
$todo1->end            = 1400;

$todo2 = new stdclass();
$todo2->assignedTo     = 'test1';
$todo2->lblDisableDate = 'on';

$todo3 = new stdclass();
$todo3->assignedTo     = 'test1';
$todo3->begin          = 1002;
$todo3->end            = 1402;

$todo4 = new stdclass();
$todo4->assignedTo     = 'admin';
$todo4->future         = 'on';
$todo4->begin          = 1200;
$todo4->end            = 1502;

$todo5 = new stdclass();
$todo5->assignedTo     = 'admin';
$todo5->future         = 'on';
$todo5->lblDisableDate = 'on';

$todo = new todoTest();

r($todo->assignToTest($todoIDList[0], $todo1)) && p('assignedTo,date,begin,end') && e('test1,20300101,1000,1400'); // 指派todo 1给test1 选择待定
r($todo->assignToTest($todoIDList[1], $todo2)) && p('assignedTo,begin,end')      && e('test1,2400,2400');          // 指派todo 2给test1 暂时不设定时间
r($todo->assignToTest($todoIDList[2], $todo3)) && p('assignedTo,begin,end')      && e('test1,1002,1402');          // 指派todo 3给test1
r($todo->assignToTest($todoIDList[3], $todo4)) && p('assignedTo,date,begin,end') && e('admin,20300101,1200,1502'); // 指派todo 4给admin future=on 验证 date默认值
r($todo->assignToTest($todoIDList[4], $todo5)) && p('assignedTo,date,begin,end') && e('admin,20300101,2400,2400'); // 指派todo 5给admin future=on lblDisableDate=on 验证date,begin,end默认值