#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

function initData ()
{
    zdTable('todo')->config('buildcycletodo')->gen(5);
}

/**

title=测试 todoTao::buildCycleTodo();
timeout=0
cid=1

- 执行todo模块的buildCycleTodo方法，参数是$todo
 - 属性name @我的待办
 - 属性type @cycle
 - 属性status @wait

*/

initData();

global $tester;
$tester->loadModel('todo');
$todo = $tester->todo->getByID(1);

r($tester->todo->buildCycleTodo($todo)) && p('name,type,status') && e('我的待办,cycle,wait');
