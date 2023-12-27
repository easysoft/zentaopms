#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('instance')->gen(5);

/**

title=instanceModel->updateStatus();
timeout=0
cid=1

- 编辑后，查看instance的status属性status @creating
- 编辑后，查看instance的status属性status @unknown

*/

global $tester;
$tester->loadModel('instance');

$tester->instance->updateStatus(1, 'creating');
$instance = $tester->instance->getByID(1);
r($instance) && p('status') && e('creating'); // 编辑后，查看instance的status

$tester->instance->updateStatus(1, 'unknown');
$instance = $tester->instance->getByID(1);
r($instance) && p('status') && e('unknown'); // 编辑后，查看instance的status