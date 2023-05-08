#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

function initData ()
{
    zdTable('todo')->config('getcyclelist')->gen(5);
}

/**

title=测试 todoTao::getCycleList();
timeout=0
cid=1

- 执行todo模块的getCycleListTest方法，参数是$initCycleData = false @0

- 执行todo模块的getCycleListTest方法，参数是$initCycleData = true @1

*/

initData();

$todo = new todoTest();
r($todo->getCycleListTest($initCycleData = false)) && p() && e('0'); // 获取通过周期待办的生成的待办数据为空的情况
r($todo->getCycleListTest($initCycleData = true)) && p() && e('1');  // 获取有周期待办生成的待办数据
