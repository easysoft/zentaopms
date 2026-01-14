#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 todoModel->createByCycle();
timeout=0
cid=19252

- 周期性待办ID为1的待办属性
 - 第0条的id属性 @1
 - 第0条的name属性 @周期待办：提前1天，间隔一天生成
 - 第0条的status属性 @doing
- 周期性待办ID为2的待办属性
 - 第1条的id属性 @2
 - 第1条的name属性 @周期待办：每月的每天，提前1天生成
 - 第1条的status属性 @doing
- 周期性待办ID为3的待办属性
 - 第2条的id属性 @3
 - 第2条的name属性 @周期待办：每周的每天，提前1天生成
 - 第2条的status属性 @doing
- 周期性待办ID为4的待办属性
 - 第3条的id属性 @4
 - 第3条的name属性 @周期待办：每月的每天，提前1天生成
 - 第3条的status属性 @wait
- 周期性待办ID为9的待办属性
 - 第8条的id属性 @9
 - 第8条的name属性 @周期待办：每周的每天，提前1天生成
 - 第8条的status属性 @wait

*/

global $tester;
$tester->loadModel('todo');

su('admin');
zenData('todo')->loadYaml('createbycycle')->gen(3);

global $tester;
$todos = $tester->loadModel('todo')->getList();

r(count($todos)) && p() && e('0'); // 测试前，待办数据为0

$todo = new todoModelTest();
$todo->createByCycleTest();
$todoList = $tester->dao->select('*')->from(TABLE_TODO)->where('deleted')->eq('0')->fetchAll();
r($todoList) && p('0:id,name,status') && e('1,周期待办：提前1天，间隔一天生成,doing');   // 周期性待办ID为1的待办属性
r($todoList) && p('1:id,name,status') && e('2,周期待办：每月的每天，提前1天生成,doing'); // 周期性待办ID为2的待办属性
r($todoList) && p('2:id,name,status') && e('3,周期待办：每周的每天，提前1天生成,doing'); // 周期性待办ID为3的待办属性
r($todoList) && p('3:id,name,status') && e('4,周期待办：每月的每天，提前1天生成,wait');  // 周期性待办ID为4的待办属性
r($todoList) && p('8:id,name,status') && e('9,周期待办：每周的每天，提前1天生成,wait');  // 周期性待办ID为9的待办属性