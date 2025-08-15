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

- 获取通过周期待办的生成的待办数据为空的情况，结果为 0 @0
- 获取有周期待办生成的待办数量，结果为 3 @3
- 获取有周期待办生成的待办数据详细信息
 - 第2条的account属性 @admin
 - 第2条的name属性 @周期待办：每月的每天，提前1天生成
 - 第2条的begin属性 @0801
 - 第2条的end属性 @1801

*/

initData();

$todo = new todoTest();
$cycleList = $todo->getCycleListTest($initCycleData = false);
r(count($cycleList)) && p() && e('0'); // 获取通过周期待办的生成的待办数据为空的情况，结果为 0

$cycleList = $todo->getCycleListTest($initCycleData = true);
r(count($cycleList)) && p() && e('3'); // 获取有周期待办生成的待办数量，结果为 3
r($cycleList) && p('2:account,name,begin,end') && e('admin,周期待办：每月的每天，提前1天生成,0801,1801'); // 获取有周期待办生成的待办数据详细信息