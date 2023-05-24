#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(3);

/**

title=taskModel->batchChangeModule();
timeout=0
cid=1

- 将模块ID更新成一个大数字，查看能否更新成功
 - 第1条的module属性 @1
 - 第1条的id属性 @1

- 包含不存在或者错误的ID列表，返回批量修改成功的数量 @3

- 正常更新模块ID，查看更新后的模块ID
 - 第1条的module属性 @100
 - 第1条的id属性 @1

- 将模块ID更新成一个负数，查看能否更新成功
 - 第1条的module属性 @100
 - 第1条的id属性 @1

- 正常更新模块ID，查看更新后的模块ID
 - 第2条的module属性 @200
 - 第2条的id属性 @2

*/

$taskIDList  = array(1, 2, 3, 100, 999, -3);

$task = new taskTest();
r($task->batchChangeModuleTest($taskIDList, 100000000000)) && p('1:module,id') && e('1,1');   // 将模块ID更新成一个大数字，查看能否更新成功
r(count($task->batchChangeModuleTest($taskIDList, 100)))   && p()              && e('3');     // 包含不存在或者错误的ID列表，返回批量修改成功的数量
r($task->batchChangeModuleTest($taskIDList, 100))          && p('1:module,id') && e('100,1'); // 正常更新模块ID，查看更新后的模块ID
r($task->batchChangeModuleTest($taskIDList, -1))           && p('1:module,id') && e('100,1'); // 将模块ID更新成一个负数，查看能否更新成功
r($task->batchChangeModuleTest($taskIDList, 200))          && p('2:module,id') && e('200,2'); // 正常更新模块ID，查看更新后的模块ID