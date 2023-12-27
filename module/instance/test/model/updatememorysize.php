#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('instance')->gen(5);
zdTable('space')->gen(5);

/**

title=instanceModel->updateMemorySize();
timeout=0
cid=1

- 编辑应用的内存，查看返回结果 @0
- 调整内存失败，查看错误信息： @调整内存失败

*/

global $tester;
$tester->loadModel('instance');

$instance = $tester->instance->getByID(1);

r($tester->instance->updateMemorySize($instance, 1024)) && p('') && e('0'); // 编辑应用的内存，查看返回结果
r(dao::getError()) && p('0') && e('调整内存失败'); // 调整内存失败，查看错误信息：