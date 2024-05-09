#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('user')->gen(5);
su('admin');

zenData('task')->loadYaml('task')->gen(3);

/**

title=taskModel->batchChangeModule();
timeout=0
cid=1

*/

$taskIDList  = array(1, 2, 3, 100, 999, -3);

$task = new taskTest();
r($task->batchChangeModuleTest($taskIDList, 10000000))     && p('1:module,id') && e('10000000,1');                    // 将模块ID更新成一个大数字，查看能否更新成功
r(count($task->batchChangeModuleTest($taskIDList, 100)))   && p()              && e('3');                             // 包含不存在或者错误的ID列表，返回批量修改成功的数量
r($task->batchChangeModuleTest($taskIDList, 100))          && p('1:module,id') && e('100,1');                         // 正常更新模块ID，查看更新后的模块ID
r($task->batchChangeModuleTest($taskIDList, -1))           && p('module:0')    && e('『所属模块』应当不小于『0』。'); // 将模块ID更新成一个负数，查看能否更新成功
r($task->batchChangeModuleTest($taskIDList, 200))          && p('2:module,id') && e('200,2');                         // 正常更新模块ID，查看更新后的模块ID
