#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testtask')->gen(5);

su('admin');

/**

title=测试 testtaskModel->start();
cid=19218
pid=1

*/

$uid = uniqid();

$task1 = array('id' => 0);
$task2 = array('id' => '');
$task3 = array('id' => 'id');
$task4 = array('id' => 2);
$task5 = array('id' => 3);
$task6 = array('id' => 4);
$task7 = array('id' => 6);
$task8 = array('id' => 1, 'status' => 'doing', 'realBegan' => '2023-09-11', 'uid' => $uid, 'comment' => '');
$task9 = array('id' => 5, 'status' => 'doing', 'realBegan' => '2023-09-11', 'uid' => $uid, 'comment' => 'comment');

$testtask = new testtaskModelTest();

r($testtask->startTest($task1)) && p() && e(0); // 测试单 ID 为 0 返回 false。
r($testtask->startTest($task2)) && p() && e(0); // 测试单 ID 为空字符串返回 false。
r($testtask->startTest($task3)) && p() && e(0); // 测试单 ID 为字符串返回 false。
r($testtask->startTest($task4)) && p() && e(0); // 测试单 ID 对应的测试单状态为 doing 返回 false。
r($testtask->startTest($task5)) && p() && e(0); // 测试单 ID 对应的测试单状态为 done 返回 false。
r($testtask->startTest($task6)) && p() && e(0); // 测试单 ID 对应的测试单状态为 blocked 返回 false。
r($testtask->startTest($task7)) && p() && e(0); // 测试单 ID 对应的测试单不存在返回 false。

r($testtask->startTest($task8)) && p('task:id|status|realBegan', '|') && e('1|doing|2023-09-11'); // 开始状态为 wait 的测试单，备注为空，成功后检测测试单信息和日志。
r($testtask->startTest($task9)) && p('task:id|status|realBegan', '|') && e('5|doing|2023-09-11'); // 开始状态为 wait 的测试单，备注不为空，成功后检测测试单信息和日志。
