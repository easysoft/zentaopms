#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData ()
{
    zenData('todo')->loadYaml('buildcycletodo')->gen(5);
}

/**

title=测试 todoTao::buildCycleTodo();
timeout=0
cid=1

*/

initData();

global $tester;
$tester->loadModel('todo');
$todo = $tester->todo->getByID(1);

r($tester->todo->buildCycleTodo($todo)) && p('name,type,status') && e('我的待办,custom,wait'); // 根据待办数据构建一个周期待办数据，验证name,type,status字段的结果为：我的待办,cycle,wait
