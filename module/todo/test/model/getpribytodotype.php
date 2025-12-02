#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

/**

title=测试 todoModel->getPriByTodoType();
timeout=0
cid=19261

- 获取类型为bug,ID为1对象的优先级，结果为1 @1
- 获取类型为bug,ID为1对象的优先级，结果为3 @3
- 获取类型为bug,ID为1对象的优先级，结果为1 @1
- 获取类型为bug,ID为1对象的优先级，结果为3 @3
- 获取类型为bug,ID为1对象的优先级，结果为1 @1

*/

su('admin');

zenData('todo')->loadYaml('todo')->gen(10);
zenData('bug')->gen(10);
zenData('task')->gen(10);
zenData('story')->gen(10);
zenData('testtask')->gen(10);

$todoTest = new todoTest();
r($todoTest->getPriByTodoTypeTest('bug',      1)) && p() && e('1'); // 获取类型为bug,ID为1对象的优先级，结果为1
r($todoTest->getPriByTodoTypeTest('task',     3)) && p() && e('3'); // 获取类型为bug,ID为1对象的优先级，结果为3
r($todoTest->getPriByTodoTypeTest('story',    5)) && p() && e('1'); // 获取类型为bug,ID为1对象的优先级，结果为1
r($todoTest->getPriByTodoTypeTest('testtask', 7)) && p() && e('3'); // 获取类型为bug,ID为1对象的优先级，结果为3
r($todoTest->getPriByTodoTypeTest('bug',      9)) && p() && e('1'); // 获取类型为bug,ID为1对象的优先级，结果为1
