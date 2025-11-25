#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 todoModel->editDateTest();
timeout=0
cid=19254

- 获取待办id为1的修改后的日期属性date @2023-06-07
- 获取待办id为2的修改后的日期属性date @2023-04-27
- 获取待办id为2的修改后的日期属性date @2023-04-27
- 获取待办id为1的修改后的日期属性date @2023-02-27
- 获取待办id为2的修改后的日期属性date @2023-02-27
- 获取待办id为3的修改后的日期属性date @2023-02-27

*/
su('admin');
zenData('todo')->loadYaml('editdate')->gen(5);

global $tester;
$tester->loadModel('todo');
$tester->todo->editDate(array(1), '2023-06-07');
$todo = $tester->todo->fetchByID(1);

r($todo) && p('date') && e('2023-06-07');  // 获取待办id为1的修改后的日期

$tester->todo->editDate(array(1, 2), '2023-04-27');
$todo = $tester->todo->fetchByID(1);
r($todo) && p('date') && e('2023-04-27');  // 获取待办id为2的修改后的日期
$todo = $tester->todo->fetchByID(2);
r($todo) && p('date') && e('2023-04-27');  // 获取待办id为2的修改后的日期

$tester->todo->editDate(array(1, 2, 3), '2023-02-27');
$todo = $tester->todo->fetchByID(1);
r($todo) && p('date') && e('2023-02-27');  // 获取待办id为1的修改后的日期
$todo = $tester->todo->fetchByID(2);
r($todo) && p('date') && e('2023-02-27');  // 获取待办id为2的修改后的日期
$todo = $tester->todo->fetchByID(3);
r($todo) && p('date') && e('2023-02-27');  // 获取待办id为3的修改后的日期