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
cid=19268

- 根据待办数据构建一个周期待办数据，验证name,type,status字段的结果为：我的待办,cycle,wait
 - 属性name @我的待办
 - 属性type @custom
 - 属性status @wait
 - 属性account @admin
 - 属性desc @这是待办描述1

*/

initData();

global $tester;
$tester->loadModel('todo');
$todo = $tester->todo->getByID(1);

r($tester->todo->buildCycleTodo($todo)) && p('name,type,status,account,desc') && e('我的待办,custom,wait,admin,这是待办描述1'); // 根据待办数据构建一个周期待办数据，验证name,type,status字段的结果为：我的待办,cycle,wait