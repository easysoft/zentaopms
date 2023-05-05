#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData()
{
    zdTable('todo')->config('createbycycle')->gen(3);
}

/**

title=测试 todoModel->createByCycle();
cid=1
pid=1

*/

initData();

$todo = new todoTest();
r($todo->createByCycleTest()) && p() && e('1');
