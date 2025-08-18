#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

function initData()
{
    zenData('todo')->loadYaml('createbycycle')->gen(3);
}

/**

title=测试 todoModel->createByCycle();
timeout=0
cid=1

- 测试前，待办数据为0 @0
- 判断创建周期的待办数据创建成功，返回结果为1 @1
- 测试后，待办数据为2 @2
- 查看待办的详细数据
 - 第0条的id属性 @4
 - 第0条的account属性 @admin
 - 第0条的type属性 @custom

*/

initData();

global $tester;
$todos = $tester->loadModel('todo')->getList();

r(count($todos)) && p() && e('0'); // 测试前，待办数据为0

$todo = new todoTest();
$todo->createByCycleTest();
r($todo->createByCycleTest()) && p() && e('1'); // 判断创建周期的待办数据创建成功，返回结果为1

$todos = $tester->loadModel('todo')->getList();
r(count($todos)) && p() && e('2'); // 测试后，待办数据为2
r($todos) && p('0:id,account,type') && e('4,admin,custom'); // 查看待办的详细数据