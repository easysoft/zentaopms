#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
/**

title=instanceModel->getServiceCount();
timeout=0
cid=1

- 查看获取到的所有服务的数量 @1
- 查看获取到的所有服务的数量 @1

*/

zdTable('user')->gen(5);
zdTable('instance')->gen(5);
zdTable('space')->gen(5);

global $tester, $app;
$tester->loadModel('instance');

su('admin');
$instance = $tester->instance->getServiceCount();
r($instance) && p('') && e('1'); // 查看获取到的所有服务的数量

su('user1');
$instance = $tester->instance->getServiceCount();
r($instance) && p('') && e('1'); // 查看获取到的所有服务的数量
