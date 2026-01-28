#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testtask')->loadYaml('testtask')->gen(6);
zenData('action')->gen(6);
zenData('history')->gen(6);
zenData('product')->gen(6);

su('admin');

/**

title=测试 testtaskModel->close();
timeout=0
cid=19159

- 测试单 ID 为 0 返回 false。 @0
- 测试单 ID 为空字符串返回 false。 @0
- 测试单 ID 为字符串返回 false。 @0
- 测试单 ID 对应的测试单状态为 done 返回 false。 @0
- 测试单 ID 对应的测试单不存在返回 false。 @0
- 实际完成日期小于开始日期提示错误信息。第realFinishedDate条的0属性 @实际完成日期不能小于开始日期2023-09-11
- 实际完成日期等于开始日期提示错误信息。第realFinishedDate条的0属性 @实际完成日期不能小于开始日期2023-09-11
- 实际完成日期大于当前日期提示错误信息。第realFinishedDate条的0属性 @实际完成日期不能大于今天
- 关闭状态为 wait 的测试单，备注为空，成功后检测测试单信息和日志。
 - 第task条的id属性 @5
 - 第task条的status属性 @done
 - 第task条的realFinishedDate属性 @2023-09-12 00:00:00
- 关闭状态为 doing 的测试单，备注为空，成功后检测测试单信息和日志。
 - 第task条的id属性 @2
 - 第task条的status属性 @done
 - 第task条的realFinishedDate属性 @2023-09-12 00:00:00
- 关闭状态为 doing 的测试单，备注不为空，成功后检测测试单信息和日志。
 - 第task条的id属性 @6
 - 第task条的status属性 @done
 - 第task条的realFinishedDate属性 @2023-09-12 00:00:00
- 关闭状态为 blocked 的测试单，备注不为空，成功后检测测试单信息和日志。
 - 第task条的id属性 @4
 - 第task条的status属性 @done
 - 第task条的realFinishedDate属性 @2023-09-12 00:00:00

*/

$uid = uniqid();

$task1  = array('id' => 0);
$task2  = array('id' => '');
$task3  = array('id' => 'id');
$task4  = array('id' => 3);
$task5  = array('id' => 7);
$task6  = array('id' => 1, 'status' => 'doing', 'realBegan' => '2023-09-11', 'uid' => $uid, 'comment' => '');
$task7  = array('id' => 1, 'status' => 'done',  'realFinishedDate' => '2023-09-10');
$task8  = array('id' => 1, 'status' => 'done',  'realFinishedDate' => '2023-09-11');
$task9  = array('id' => 1, 'status' => 'done',  'realFinishedDate' => date('Y-m-d', strtotime('+2 days')));
$task10 = array('id' => 5, 'status' => 'done',  'realFinishedDate' => '2023-09-12', 'uid' => $uid, 'comment' => '');
$task11 = array('id' => 2, 'status' => 'done',  'realFinishedDate' => '2023-09-12', 'uid' => $uid, 'comment' => 'comment');
$task12 = array('id' => 6, 'status' => 'done',  'realFinishedDate' => '2023-09-12', 'uid' => $uid, 'comment' => 'comment');
$task13 = array('id' => 4, 'status' => 'done',  'realFinishedDate' => '2023-09-12', 'uid' => $uid, 'comment' => 'comment');

$testtask = new testtaskModelTest();

r($testtask->closeTest($task1)) && p() && e(0); // 测试单 ID 为 0 返回 false。
r($testtask->closeTest($task2)) && p() && e(0); // 测试单 ID 为空字符串返回 false。
r($testtask->closeTest($task3)) && p() && e(0); // 测试单 ID 为字符串返回 false。
r($testtask->closeTest($task4)) && p() && e(0); // 测试单 ID 对应的测试单状态为 done 返回 false。
r($testtask->closeTest($task5)) && p() && e(0); // 测试单 ID 对应的测试单不存在返回 false。

r($testtask->closeTest($task7)) && p('realFinishedDate:0') && e('实际完成日期不能小于开始日期2023-09-11'); // 实际完成日期小于开始日期提示错误信息。
r($testtask->closeTest($task8)) && p('realFinishedDate:0') && e('实际完成日期不能小于开始日期2023-09-11'); // 实际完成日期等于开始日期提示错误信息。
r($testtask->closeTest($task9)) && p('realFinishedDate:0') && e('实际完成日期不能大于今天');               // 实际完成日期大于当前日期提示错误信息。

r($testtask->closeTest($task10)) && p('task:id|status|realFinishedDate', '|') && e('5|done|2023-09-12 00:00:00'); // 关闭状态为 wait 的测试单，备注为空，成功后检测测试单信息和日志。
r($testtask->closeTest($task11)) && p('task:id|status|realFinishedDate', '|') && e('2|done|2023-09-12 00:00:00'); // 关闭状态为 doing 的测试单，备注为空，成功后检测测试单信息和日志。
r($testtask->closeTest($task12)) && p('task:id|status|realFinishedDate', '|') && e('6|done|2023-09-12 00:00:00'); // 关闭状态为 doing 的测试单，备注不为空，成功后检测测试单信息和日志。
r($testtask->closeTest($task13)) && p('task:id|status|realFinishedDate', '|') && e('4|done|2023-09-12 00:00:00'); // 关闭状态为 blocked 的测试单，备注不为空，成功后检测测试单信息和日志。