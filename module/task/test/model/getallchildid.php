#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('task')->loadYaml('task')->gen(15);

/**

title=taskModel->getAllChildId();
timeout=0
cid=18788

- 测试1：传入taskID=0，应该返回空数组 @0
- 测试2：传入不存在的任务ID，应该返回空数组 @0
- 测试3：传入已删除的任务ID，应该返回空数组 @0
- 测试4：获取taskID=6的所有子任务ID（包含自己）
 - 属性6 @6
 - 属性7 @7
- 测试5：获取taskID=6的所有子任务ID（不包含自己）属性7 @7
- 测试6：获取taskID=1的所有子任务ID（包含自己）
 - 属性1 @1
 - 属性15 @15
 - 属性14 @14
- 测试7：获取taskID=1的所有子任务ID（不包含自己）
 - 属性15 @15
 - 属性14 @14
- 测试8：获取taskID=2的所有子任务ID（包含自己）
 - 属性2 @2
 - 属性9 @9
- 测试9：获取taskID=2的所有子任务ID（不包含自己）属性9 @9
- 测试10：获取taskID=3的所有子任务ID（包含自己）
 - 属性3 @3
 - 属性13 @13
- 测试11：获取taskID=3的所有子任务ID（不包含自己）属性13 @13

*/

$taskModel = $tester->loadModel('task');

r(count($taskModel->getAllChildId(0)))   && p() && e(0); // 测试1：传入taskID=0，应该返回空数组
r(count($taskModel->getAllChildId(999))) && p() && e(0); // 测试2：传入不存在的任务ID，应该返回空数组
r(count($taskModel->getAllChildId(11)))  && p() && e(0); // 测试3：传入已删除的任务ID，应该返回空数组

r($taskModel->getAllChildId(6, true))  && p('6,7') && e('6,7');         // 测试4：获取taskID=6的所有子任务ID（包含自己）
r($taskModel->getAllChildId(6, false)) && p('7') && e('7');             // 测试5：获取taskID=6的所有子任务ID（不包含自己）
r($taskModel->getAllChildId(1, true))  && p('1,15,14') && e('1,15,14'); // 测试6：获取taskID=1的所有子任务ID（包含自己）
r($taskModel->getAllChildId(1, false)) && p('15,14') && e('15,14');     // 测试7：获取taskID=1的所有子任务ID（不包含自己）
r($taskModel->getAllChildId(2, true))  && p('2,9') && e('2,9');         // 测试8：获取taskID=2的所有子任务ID（包含自己）
r($taskModel->getAllChildId(2, false)) && p('9') && e('9');             // 测试9：获取taskID=2的所有子任务ID（不包含自己）
r($taskModel->getAllChildId(3, true))  && p('3,13') && e('3,13');       // 测试10：获取taskID=3的所有子任务ID（包含自己）
r($taskModel->getAllChildId(3, false)) && p('13') && e('13');           // 测试11：获取taskID=3的所有子任务ID（不包含自己）