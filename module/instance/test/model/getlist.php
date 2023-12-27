#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('instance')->gen(5);

/**

title=instanceModel->getList();
timeout=0
cid=1

- 查看获取到的所有instance的数量 @5
- 通过搜索条件查看获取到的所有instance的数量 @1
- 通过状态查看获取到的所有instance的数量 @0
- 通过状态查看获取到的所有instance的数量 @0

*/

global $tester;
$tester->loadModel('instance');

$instance = $tester->instance->getList();
r(count($instance)) && p('') && e('5'); // 查看获取到的所有instance的数量

$instance = $tester->instance->getList(null, '', 'sub');
r(count($instance)) && p('') && e('1'); // 通过搜索条件查看获取到的所有instance的数量

$instance = $tester->instance->getList(null, '', '', 'running');
r(count($instance)) && p('') && e('0'); // 通过状态查看获取到的所有instance的数量

$instance = $tester->instance->getList(null, '', '', 'wait');
r(count($instance)) && p('') && e('0'); // 通过状态查看获取到的所有instance的数量