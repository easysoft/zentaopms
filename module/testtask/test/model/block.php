#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testtask.class.php';

zdTable('testtask')->gen(5);

su('admin');

/**

title=测试 testtaskModel->block();
cid=1
pid=1

*/

$uid = uniqid();

$task1  = array('id' => 0);
$task2  = array('id' => '');
$task3  = array('id' => 'id');
$task4  = array('id' => 3);
$task5  = array('id' => 4);
$task6  = array('id' => 6);
$task8  = array('id' => 1, 'status' => 'blocked', 'uid' => $uid, 'comment' => '');
$task9  = array('id' => 5, 'status' => 'blocked', 'uid' => $uid, 'comment' => 'comment');
$task10 = array('id' => 2, 'status' => 'blocked', 'uid' => $uid, 'comment' => 'comment');

$testtask = new testtaskTest();

r($testtask->blockTest($task1)) && p() && e(0); // 测试单 ID 为 0 返回 false。
r($testtask->blockTest($task2)) && p() && e(0); // 测试单 ID 为空字符串返回 false。
r($testtask->blockTest($task3)) && p() && e(0); // 测试单 ID 为字符串返回 false。
r($testtask->blockTest($task4)) && p() && e(0); // 测试单 ID 对应的测试单状态为 done 返回 false。
r($testtask->blockTest($task5)) && p() && e(0); // 测试单 ID 对应的测试单状态为 blocked 返回 false。
r($testtask->blockTest($task6)) && p() && e(0); // 测试单 ID 对应的测试单不存在返回 false。

r($testtask->blockTest($task8))  && p('task:id|status;action:objectType|action|comment;history[0]:field|old|new', '|') && e('1|blocked;testtask|blocked|~~;status|wait|blocked');       // 阻塞状态为 wait 的测试单，备注为空，成功后检测测试单信息和日志。
r($testtask->blockTest($task9))  && p('task:id|status;action:objectType|action|comment;history[0]:field|old|new', '|') && e('5|blocked;testtask|blocked|comment;status|wait|blocked');  // 阻塞状态为 wait 的测试单，备注不为空，成功后检测测试单信息和日志。
r($testtask->blockTest($task10)) && p('task:id|status;action:objectType|action|comment;history[0]:field|old|new', '|') && e('2|blocked;testtask|blocked|comment;status|doing|blocked'); // 阻塞状态为 doing 的测试单，备注不为空，成功后检测测试单信息和日志。
