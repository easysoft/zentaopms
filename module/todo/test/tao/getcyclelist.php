#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

function initData()
{
    zenData('todo')->loadYaml('getcyclelist')->gen(5);
}

/**

title=测试 todoTao::getCycleList();
timeout=0
cid=1

*/

initData();

$todo = new todoTest();
r($todo->getCycleListTest($initCycleData = false)) && p() && e('0'); // 获取通过周期待办的生成的待办数据为空的情况，结果为 0
r($todo->getCycleListTest($initCycleData = true))  && p() && e('1'); // 获取有周期待办生成的待办数据，结果为 1
