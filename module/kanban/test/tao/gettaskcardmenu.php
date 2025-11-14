#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('kanbanexecution')->gen(5);
zenData('task')->loadYaml('rdkanbantask')->gen(20);

/**

title=测试 kanbanModel->gettaskcardmenu();
timeout=0
cid=16987

- 测试获取执行1 task1的操作数量 @8
- 测试获取执行1 task2的操作数量 @8
- 测试获取执行1 task3的操作数量 @6
- 测试获取执行1 task4的操作数量 @5
- 测试获取执行1 task5的操作数量 @5
- 测试获取执行1 task6的操作数量 @8
- 测试获取执行1 task7的操作数量 @8
- 测试获取执行1 task8的操作数量 @6
- 测试获取执行1 task9的操作数量 @5
- 测试获取执行1 task10的操作数量 @5

*/

global $tester;
$tester->loadModel('kanban');
$tasks = $tester->dao->select('*')->from(TABLE_TASK)->fetchAll();

$menu = $tester->kanban->getTaskCardMenu($tasks, 1);
r(count($menu[1])) && p() && e('8');  // 测试获取执行1 task1的操作数量
r(count($menu[2])) && p() && e('8');  // 测试获取执行1 task2的操作数量
r(count($menu[3])) && p() && e('6');  // 测试获取执行1 task3的操作数量
r(count($menu[4])) && p() && e('5');  // 测试获取执行1 task4的操作数量
r(count($menu[5])) && p() && e('5');  // 测试获取执行1 task5的操作数量
r(count($menu[6])) && p() && e('8');  // 测试获取执行1 task6的操作数量
r(count($menu[7])) && p() && e('8');  // 测试获取执行1 task7的操作数量
r(count($menu[8])) && p() && e('6');  // 测试获取执行1 task8的操作数量
r(count($menu[9])) && p() && e('5');  // 测试获取执行1 task9的操作数量
r(count($menu[10])) && p() && e('5'); // 测试获取执行1 task10的操作数量
