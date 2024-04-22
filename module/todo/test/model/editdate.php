#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

/**

title=测试 todoModel->editDateTest();
cid=1
pid=1

*/

$todo = new todoTest();

zenData('todo')->loadYaml('editdate')->gen(5);

r($todo->editDateTest(array(1),    '2023-06-07')) && p() && e('1');  // 修改id为1的待办的日期
r($todo->editDateTest(array(2, 3), '2023-04-27')) && p() && e('1');  // 修改id为2,3的待办的日期
