#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testtask.class.php';

zdTable('testtask')->gen(4);

su('admin');

/**

title=测试 testtaskModel->start();
cid=1
pid=1

*/

$task1 = array('id' => 0);
$task2 = array('id' => '');
$task3 = array('id' => 'id');
$task4 = array('id' => 2);
$task5 = array('id' => 3);
$task6 = array('id' => 4);
$task7 = array('id' => 5);
$task8 = array('id' => 1, 'status' => 'doing', 'realBegan' => '2023-09-11', 'comment' => 'comment');

$testtask = new testtaskTest();

r($testtask->startTest($task1)) && p() && e(0); // 测试单 ID 为 0 返回 false。
r($testtask->startTest($task2)) && p() && e(0); // 测试单 ID 为空字符串返回 false。
r($testtask->startTest($task3)) && p() && e(0); // 测试单 ID 为字符串返回 false。
r($testtask->startTest($task4)) && p() && e(0); // 测试单 ID 对应的测试单状态为 doing 返回 false。
r($testtask->startTest($task5)) && p() && e(0); // 测试单 ID 对应的测试单状态为 done 返回 false。
r($testtask->startTest($task6)) && p() && e(0); // 测试单 ID 对应的测试单状态为 blocked 返回 false。
r($testtask->startTest($task7)) && p() && e(0); // 测试单 ID 对应的测试单不存在返回 false。

r($testtask->startTest($task8)) && p('task:id|status|realBegan;action:objectType|action|comment;history[0]:field|old|new;history[1]:field|old|new', '|') && e('1|doing|2023-09-11;testtask|started|comment;status|wait|doing;realBegan|~~|2023-09-11'); // 开始测试单 1 成功后检测测试单信息和日志。
