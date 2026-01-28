#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testtask')->gen(5);

su('admin');

/**

title=测试 testtaskModel->block();
timeout=0
cid=19158

- 测试单 ID 为 0 返回 false。 @0
- 测试单 ID 为空字符串返回 false。 @0
- 测试单 ID 为字符串返回 false。 @0
- 测试单 ID 对应的测试单状态为 done 返回 false。 @0
- 测试单 ID 对应的测试单状态为 blocked 返回 false。 @0
- 测试单 ID 对应的测试单不存在返回 false。 @0
- 阻塞状态为 wait 的测试单，备注为空，成功后检测测试单信息和日志。
 - 第task条的id属性 @1
 - 第task条的status属性 @blocked
- 阻塞状态为 wait 的测试单，备注不为空，成功后检测测试单信息和日志。
 - 第task条的id属性 @5
 - 第task条的status属性 @blocked
- 阻塞状态为 doing 的测试单，备注不为空，成功后检测测试单信息和日志。
 - 第task条的id属性 @2
 - 第task条的status属性 @blocked

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

$testtask = new testtaskModelTest();

r($testtask->blockTest($task1)) && p() && e(0); // 测试单 ID 为 0 返回 false。
r($testtask->blockTest($task2)) && p() && e(0); // 测试单 ID 为空字符串返回 false。
r($testtask->blockTest($task3)) && p() && e(0); // 测试单 ID 为字符串返回 false。
r($testtask->blockTest($task4)) && p() && e(0); // 测试单 ID 对应的测试单状态为 done 返回 false。
r($testtask->blockTest($task5)) && p() && e(0); // 测试单 ID 对应的测试单状态为 blocked 返回 false。
r($testtask->blockTest($task6)) && p() && e(0); // 测试单 ID 对应的测试单不存在返回 false。

r($testtask->blockTest($task8))  && p('task:id|status', '|') && e('1|blocked'); // 阻塞状态为 wait 的测试单，备注为空，成功后检测测试单信息和日志。
r($testtask->blockTest($task9))  && p('task:id|status', '|') && e('5|blocked'); // 阻塞状态为 wait 的测试单，备注不为空，成功后检测测试单信息和日志。
r($testtask->blockTest($task10)) && p('task:id|status', '|') && e('2|blocked'); // 阻塞状态为 doing 的测试单，备注不为空，成功后检测测试单信息和日志。