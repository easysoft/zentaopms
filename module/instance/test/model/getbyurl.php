#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('instance')->gen(5);

/**

title=instanceModel->getByUrl();
timeout=0
cid=1

- 查看获取到的第一条instance属性id @1
- 查看获取到的第二条instance属性id @2
- 查看不存在的url @0

*/

global $tester;
$tester->loadModel('instance');

$instance = $tester->instance->getByUrl("rila.dops.corp.cc");
r($instance) && p('id') && e('1'); // 查看获取到的第一条instance

$instance = $tester->instance->getByUrl("7czx.dops.corp.cc");
r($instance) && p('id') && e('2'); // 查看获取到的第二条instance

$instance = $tester->instance->getByUrl("www.zentao.net");
r($instance) && p('') && e('0'); // 查看不存在的url