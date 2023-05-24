#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('project')->config('execution')->gen(6);
zdTable('task')->config('task')->gen(6);

/**

title=taskModel->batchChangeModule();
timeout=0
cid=1

*/

$taskIDList  = array('0','2','10');

$task = new taskTest();
r(count($task->batchChangeModuleTest($taskIDList, 1))) && p() && e('1');   // 包含不存在或者错误的ID列表，返回批量修改成功的id
r(count($task->batchChangeModuleTest($taskIDList, 1))) && p() && e('0');   // 模块无需更新的列表，返回批量修改成功的id
