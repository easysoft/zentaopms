#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testtask.class.php';

zdTable('testtask')->gen(7);

su('admin');

/**

title=测试 testtaskModel->activate();
cid=1
pid=1

*/

$uid = uniqid();

$task1 = array('id' => 0);
$task2 = array('id' => '');
$task3 = array('id' => 'id');
$task4 = array('id' => 1);
$task5 = array('id' => 2);
$task6 = array('id' => 8);
$task7 = array('id' => 3, 'status' => 'doing', 'uid' => $uid, 'comment' => '');
$task8 = array('id' => 7, 'status' => 'doing', 'uid' => $uid, 'comment' => 'comment');
$task9 = array('id' => 4, 'status' => 'doing', 'uid' => $uid, 'comment' => 'comment');

$testtask = new testtaskTest();

r($testtask->activateTest($task1)) && p() && e(0); // 测试单 ID 为 0 返回 false。
r($testtask->activateTest($task2)) && p() && e(0); // 测试单 ID 为空字符串返回 false。
r($testtask->activateTest($task3)) && p() && e(0); // 测试单 ID 为字符串返回 false。
r($testtask->activateTest($task4)) && p() && e(0); // 测试单 ID 对应的测试单状态为 wait 返回 false。
r($testtask->activateTest($task5)) && p() && e(0); // 测试单 ID 对应的测试单状态为 doing 返回 false。
r($testtask->activateTest($task6)) && p() && e(0); // 测试单 ID 对应的测试单不存在返回 false。

r($testtask->activateTest($task7)) && p('task:id|status;action:objectType|action|comment;history[0]:field|old|new', '|') && e('3|doing;testtask|activated|~~;status|done|doing');       // 激活状态为 closed 的测试单，备注为空，成功后检测测试单信息和日志。
r($testtask->activateTest($task8)) && p('task:id|status;action:objectType|action|comment;history[0]:field|old|new', '|') && e('7|doing;testtask|activated|comment;status|done|doing');  // 激活状态为 closed 的测试单，备注不为空，成功后检测测试单信息和日志。
r($testtask->activateTest($task9)) && p('task:id|status;action:objectType|action|comment;history[0]:field|old|new', '|') && e('4|doing;testtask|activated|comment;status|blocked|doing'); // 激活状态为 blocked 的测试单，备注不为空，成功后检测测试单信息和日志。
