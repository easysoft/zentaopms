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
cid=1
pid=1

*/

initData();

$todo = new todoTest();
r($todo->createByCycleTest()) && p() && e('1'); // 判断创建周期的待办数据创建成功，返回结果为1
