#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 todoModel->createByCycle();
timeout=0
cid=19252

- 测试前，待办数据为0 @0
- 生成后的普通待办数量 @7
- 第1条生成待办属性
 - 第0条的id属性 @4
 - 第0条的objectID属性 @2
 - 第0条的type属性 @custom
 - 第0条的name属性 @周期待办：每月的每天，提前1天生成
 - 第0条的status属性 @wait
- 第1条生成待办日期 @1
- 第2条生成待办属性
 - 第1条的id属性 @5
 - 第1条的objectID属性 @2
 - 第1条的type属性 @custom
 - 第1条的name属性 @周期待办：每月的每天，提前1天生成
 - 第1条的status属性 @wait
- 第2条生成待办日期 @1
- 第3条生成待办属性
 - 第2条的id属性 @6
 - 第2条的objectID属性 @2
 - 第2条的type属性 @custom
 - 第2条的name属性 @周期待办：每月的每天，提前1天生成
 - 第2条的status属性 @wait
- 第3条生成待办日期 @1
- 第4条生成待办属性
 - 第3条的id属性 @7
 - 第3条的objectID属性 @3
 - 第3条的type属性 @custom
 - 第3条的name属性 @周期待办：每周的每天，提前1天生成
 - 第3条的status属性 @wait
- 第4条生成待办日期 @1
- 第5条生成待办属性
 - 第4条的id属性 @8
 - 第4条的objectID属性 @3
 - 第4条的type属性 @custom
 - 第4条的name属性 @周期待办：每周的每天，提前1天生成
 - 第4条的status属性 @wait
- 第5条生成待办日期 @1
- 第6条生成待办属性
 - 第5条的id属性 @9
 - 第5条的objectID属性 @3
 - 第5条的type属性 @custom
 - 第5条的name属性 @周期待办：每周的每天，提前1天生成
 - 第5条的status属性 @wait
- 第6条生成待办日期 @1
- 第7条生成待办属性
 - 第6条的id属性 @10
 - 第6条的objectID属性 @3
 - 第6条的type属性 @custom
 - 第6条的name属性 @周期待办：每周的每天，提前1天生成
 - 第6条的status属性 @wait
- 第7条生成待办日期 @1
- 按天生成的周期待办在当前日期不会补历史数据 @0

*/

global $tester;
$tester->loadModel('todo');

su('admin');
$todoData = zenData('todo')->loadYaml('createbycycle');
$todoData->begin->range('800-1100');
$todoData->private->range('0');
$todoData->gen(3, true, false);

$todos = $tester->loadModel('todo')->getList();
$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$thirdDay = date('Y-m-d', strtotime('+2 day'));
$fourthDay = date('Y-m-d', strtotime('+3 day'));

r(count($todos)) && p() && e('0'); // 测试前，待办数据为0

$todo = new todoModelTest();
$todo->createByCycleTest();
$todoList = $tester->dao->select('id,objectID,type,name,status,date')->from(TABLE_TODO)->where('deleted')->eq('0')->andWhere('type')->eq('custom')->orderBy('id')->fetchAll();
$dayTodoCount = $tester->dao->select('COUNT(1) AS count')->from(TABLE_TODO)->where('deleted')->eq('0')->andWhere('type')->eq('custom')->andWhere('objectID')->eq('1')->fetch('count');

r(count($todoList)) && p() && e('7'); // 生成后的普通待办数量
r($todoList) && p('0:id,objectID,type,name,status') && e('4,2,custom,周期待办：每月的每天，提前1天生成,wait'); // 第1条生成待办属性
r($todoList[0]->date == $today ? 1 : 0) && p() && e('1'); // 第1条生成待办日期
r($todoList) && p('1:id,objectID,type,name,status') && e('5,2,custom,周期待办：每月的每天，提前1天生成,wait'); // 第2条生成待办属性
r($todoList[1]->date == $tomorrow ? 1 : 0) && p() && e('1'); // 第2条生成待办日期
r($todoList) && p('2:id,objectID,type,name,status') && e('6,2,custom,周期待办：每月的每天，提前1天生成,wait'); // 第3条生成待办属性
r($todoList[2]->date == $thirdDay ? 1 : 0) && p() && e('1'); // 第3条生成待办日期
r($todoList) && p('3:id,objectID,type,name,status') && e('7,3,custom,周期待办：每周的每天，提前1天生成,wait'); // 第4条生成待办属性
r($todoList[3]->date == $today ? 1 : 0) && p() && e('1'); // 第4条生成待办日期
r($todoList) && p('4:id,objectID,type,name,status') && e('8,3,custom,周期待办：每周的每天，提前1天生成,wait'); // 第5条生成待办属性
r($todoList[4]->date == $tomorrow ? 1 : 0) && p() && e('1'); // 第5条生成待办日期
r($todoList) && p('5:id,objectID,type,name,status') && e('9,3,custom,周期待办：每周的每天，提前1天生成,wait'); // 第6条生成待办属性
r($todoList[5]->date == $thirdDay ? 1 : 0) && p() && e('1'); // 第6条生成待办日期
r($todoList) && p('6:id,objectID,type,name,status') && e('10,3,custom,周期待办：每周的每天，提前1天生成,wait'); // 第7条生成待办属性
r($todoList[6]->date == $fourthDay ? 1 : 0) && p() && e('1'); // 第7条生成待办日期
r($dayTodoCount) && p() && e('0'); // 按天生成的周期待办在当前日期不会补历史数据