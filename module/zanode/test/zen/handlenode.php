#!/usr/bin/env php
<?php

/**

title=测试 zanodeZen::handleNode();
timeout=0
cid=0

- 测试步骤1: 正常执行boot操作
 - 属性result @success
 - 属性message @操作成功
- 测试步骤2: 正常执行destroy操作
 - 属性result @success
 - 属性message @操作成功
- 测试步骤3: 正常执行suspend操作
 - 属性result @success
 - 属性message @操作成功
- 测试步骤4: 节点状态为restoring时执行操作属性result @fail
- 测试步骤5: 节点状态为creating_img时执行操作属性result @fail
- 测试步骤6: 节点状态为creating_snap时执行操作属性result @fail
- 测试步骤7: 正常执行resume操作
 - 属性result @success
 - 属性message @操作成功

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
global $app;
if(!isset($app->rawModule)) $app->rawModule = 'zanode';
if(!isset($app->rawMethod)) $app->rawMethod = 'test';

zenData('host')->gen(0);

su('admin');

$zanodeTest = new zanodeTest();

// 准备测试数据: 创建不同状态的节点
$host = zenData('host');
$host->id->range('1-10');
$host->name->range('node1,node2,node3,node4,node5,node6,node7');
$host->extranet->range('192.168.1.1,192.168.1.2,192.168.1.3,192.168.1.4,192.168.1.5,192.168.1.6,192.168.1.7');
$host->zap->range('8086');
$host->tokenSN->range('token1,token2,token3,token4,token5,token6,token7');
$host->status->range('running,running,running,restoring,creating_img,creating_snap,suspend');
$host->type->range('node');
$host->gen(7);

r($zanodeTest->handleNodeTest(1, 'boot')) && p('result,message') && e('success,操作成功'); // 测试步骤1: 正常执行boot操作
r($zanodeTest->handleNodeTest(2, 'destroy')) && p('result,message') && e('success,操作成功'); // 测试步骤2: 正常执行destroy操作
r($zanodeTest->handleNodeTest(3, 'suspend')) && p('result,message') && e('success,操作成功'); // 测试步骤3: 正常执行suspend操作
r($zanodeTest->handleNodeTest(4, 'boot')) && p('result') && e('fail'); // 测试步骤4: 节点状态为restoring时执行操作
r($zanodeTest->handleNodeTest(5, 'boot')) && p('result') && e('fail'); // 测试步骤5: 节点状态为creating_img时执行操作
r($zanodeTest->handleNodeTest(6, 'boot')) && p('result') && e('fail'); // 测试步骤6: 节点状态为creating_snap时执行操作
r($zanodeTest->handleNodeTest(7, 'resume')) && p('result,message') && e('success,操作成功'); // 测试步骤7: 正常执行resume操作