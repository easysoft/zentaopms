#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('instance')->gen(5);
zdTable('space')->gen(5);

/**

title=instanceModel->countOldDomain();
timeout=0
cid=1

- 查看获取到的所有服务的数量 @1
- 查看获取到的所有服务的数量 @1

*/

global $tester;
$tester->loadModel('instance');

$oldDomain = $tester->instance->countOldDomain();
r($oldDomain) && p('') && e('5'); // 查看获取到的所有服务的数量
